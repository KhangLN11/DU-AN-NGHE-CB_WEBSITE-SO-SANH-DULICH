<?php

class AdminUser extends Model
{
    public function getPaginatedUsers(
        array $filters,
        int $limit,
        int $offset
    ): array {
        $where = ['1 = 1'];
        $params = [];

        $this->applyFilters(
            $where,
            $params,
            $filters
        );

        $whereSql = implode(
            ' AND ',
            $where
        );

        $sql = "
            SELECT
                u.user_id,
                u.role_id,
                u.full_name,
                u.email,
                u.phone,
                u.avatar,
                u.status,
                u.created_at,
                u.updated_at,

                r.role_name,
                r.description AS role_description,

                (
                    SELECT COUNT(*)
                    FROM favorites f
                    WHERE f.user_id = u.user_id
                ) AS favorite_count

            FROM users u

            INNER JOIN roles r
                ON u.role_id = r.role_id

            WHERE {$whereSql}

            ORDER BY
                u.user_id DESC

            LIMIT :limit
            OFFSET :offset
        ";

        $stmt = $this->db->prepare($sql);

        foreach (
            $params as $key => $value
        ) {
            $stmt->bindValue(
                $key,
                $value
            );
        }

        $stmt->bindValue(
            ':limit',
            $limit,
            PDO::PARAM_INT
        );

        $stmt->bindValue(
            ':offset',
            $offset,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function countUsers(
        array $filters
    ): int {
        $where = ['1 = 1'];
        $params = [];

        $this->applyFilters(
            $where,
            $params,
            $filters
        );

        $whereSql = implode(
            ' AND ',
            $where
        );

        $sql = "
            SELECT COUNT(*)

            FROM users u

            INNER JOIN roles r
                ON u.role_id = r.role_id

            WHERE {$whereSql}
        ";

        $stmt = $this->db->prepare($sql);

        foreach (
            $params as $key => $value
        ) {
            $stmt->bindValue(
                $key,
                $value
            );
        }

        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function getRoles(): array
    {
        $sql = "
            SELECT
                role_id,
                role_name,
                description
            FROM roles
            ORDER BY
                role_name ASC
        ";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }

    public function findById(
        int $userId
    ): ?array {
        $sql = "
            SELECT
                u.user_id,
                u.role_id,
                u.full_name,
                u.email,
                u.phone,
                u.avatar,
                u.status,
                u.created_at,
                u.updated_at,

                r.role_name,
                r.description AS role_description,

                (
                    SELECT COUNT(*)
                    FROM favorites f
                    WHERE f.user_id = u.user_id
                ) AS favorite_count

            FROM users u

            INNER JOIN roles r
                ON u.role_id = r.role_id

            WHERE u.user_id = :user_id

            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(
            ':user_id',
            $userId,
            PDO::PARAM_INT
        );

        $stmt->execute();

        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function updateStatus(
        int $userId,
        string $status
    ): bool {
        $allowedStatuses = [
            'active',
            'inactive',
            'blocked'
        ];

        if (
            !in_array(
                $status,
                $allowedStatuses,
                true
            )
        ) {
            throw new InvalidArgumentException(
                'Trạng thái tài khoản không hợp lệ.'
            );
        }

        $sql = "
            UPDATE users
            SET status = :status
            WHERE user_id = :user_id
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(
            ':status',
            $status,
            PDO::PARAM_STR
        );

        $stmt->bindValue(
            ':user_id',
            $userId,
            PDO::PARAM_INT
        );

        return $stmt->execute();
    }

    public function getFavoriteCount(
        int $userId
    ): int {
        $sql = "
            SELECT COUNT(*)
            FROM favorites
            WHERE user_id = :user_id
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(
            ':user_id',
            $userId,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function getUserUsage(
    int $userId
): array {
    return [
        'favorites' =>
            $this->getFavoriteCount(
                $userId
            )
    ];
}

public function canDeleteUser(
    int $userId
): bool {
    $usage =
        $this->getUserUsage(
            $userId
        );

    foreach ($usage as $count) {
        if ((int) $count > 0) {
            return false;
        }
    }

    return true;
}

    public function deleteUser(
    int $userId
): bool {
    try {
        $this->db->beginTransaction();

        $favoriteStmt =
            $this->db->prepare("
                SELECT COUNT(*)
                FROM favorites
                WHERE user_id = :user_id
            ");

        $favoriteStmt->bindValue(
            ':user_id',
            $userId,
            PDO::PARAM_INT
        );

        $favoriteStmt->execute();

        $favoriteCount =
            (int) $favoriteStmt
                ->fetchColumn();

        if ($favoriteCount > 0) {
            throw new RuntimeException(
                'Người dùng vẫn còn dữ liệu liên quan.'
            );
        }

        $deleteStmt =
            $this->db->prepare("
                DELETE FROM users
                WHERE user_id = :user_id
            ");

        $deleteStmt->bindValue(
            ':user_id',
            $userId,
            PDO::PARAM_INT
        );

        $deleteStmt->execute();

        if (
            $deleteStmt->rowCount()
            !== 1
        ) {
            throw new RuntimeException(
                'Không tìm thấy người dùng để xóa.'
            );
        }

        $this->db->commit();

        return true;
    } catch (Throwable $error) {
        if ($this->db->inTransaction()) {
            $this->db->rollBack();
        }

        throw $error;
    }
}

    private function applyFilters(
        array &$where,
        array &$params,
        array $filters
    ): void {
        if (
            ($filters['keyword'] ?? '')
            !== ''
        ) {
            $where[] = "
                (
                    u.full_name
                        LIKE :keyword_name
                    OR u.email
                        LIKE :keyword_email
                    OR u.phone
                        LIKE :keyword_phone
                )
            ";

            $keyword =
                '%'
                . $filters['keyword']
                . '%';

            $params[':keyword_name'] =
                $keyword;

            $params[':keyword_email'] =
                $keyword;

            $params[':keyword_phone'] =
                $keyword;
        }

        if (
            ($filters['status'] ?? '')
            !== ''
        ) {
            $where[] =
                'u.status = :status';

            $params[':status'] =
                $filters['status'];
        }

        if (
            ($filters['role'] ?? 0)
            > 0
        ) {
            $where[] =
                'u.role_id = :role';

            $params[':role'] =
                (int) $filters['role'];
        }
    }
}