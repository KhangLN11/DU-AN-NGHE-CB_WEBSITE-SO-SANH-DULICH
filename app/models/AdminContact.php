<?php

class AdminContact extends Model
{
    public function getPaginatedContacts(
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
                c.contact_id,
                c.user_id,
                c.full_name,
                c.email,
                c.phone,
                c.subject,
                c.message,
                c.status,
                c.admin_note,
                c.created_at,
                c.updated_at,

                u.full_name AS user_full_name,
                u.email AS user_email

            FROM contacts c

            LEFT JOIN users u
                ON c.user_id = u.user_id

            WHERE {$whereSql}

            ORDER BY
                CASE c.status
                    WHEN 'pending' THEN 1
                    WHEN 'processing' THEN 2
                    WHEN 'resolved' THEN 3
                    ELSE 4
                END,
                c.created_at DESC,
                c.contact_id DESC

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

    public function countContacts(
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
            FROM contacts c
            LEFT JOIN users u
                ON c.user_id = u.user_id
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

    public function getStatusCounts(): array
    {
        $sql = "
            SELECT
                status,
                COUNT(*) AS total
            FROM contacts
            GROUP BY status
        ";

        $stmt = $this->db->query($sql);

        $counts = [
            'pending' => 0,
            'processing' => 0,
            'resolved' => 0
        ];

        foreach ($stmt->fetchAll() as $row) {
            $status =
                $row['status'];

            if (
                array_key_exists(
                    $status,
                    $counts
                )
            ) {
                $counts[$status] =
                    (int) $row['total'];
            }
        }

        return $counts;
    }

    public function findById(
        int $contactId
    ): ?array {
        $sql = "
            SELECT
                c.contact_id,
                c.user_id,
                c.full_name,
                c.email,
                c.phone,
                c.subject,
                c.message,
                c.status,
                c.admin_note,
                c.created_at,
                c.updated_at,

                u.full_name AS user_full_name,
                u.email AS user_email,
                u.status AS user_status

            FROM contacts c

            LEFT JOIN users u
                ON c.user_id = u.user_id

            WHERE c.contact_id = :contact_id

            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(
            ':contact_id',
            $contactId,
            PDO::PARAM_INT
        );

        $stmt->execute();

        $contact = $stmt->fetch();

        return $contact ?: null;
    }

    public function updateContactManagement(
        int $contactId,
        string $status,
        ?string $adminNote
    ): bool {
        $allowedStatuses = [
            'pending',
            'processing',
            'resolved'
        ];

        if (
            !in_array(
                $status,
                $allowedStatuses,
                true
            )
        ) {
            throw new InvalidArgumentException(
                'Trạng thái liên hệ không hợp lệ.'
            );
        }

        $sql = "
            UPDATE contacts
            SET
                status = :status,
                admin_note = :admin_note
            WHERE contact_id = :contact_id
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(
            ':status',
            $status,
            PDO::PARAM_STR
        );

        if ($adminNote === null) {
            $stmt->bindValue(
                ':admin_note',
                null,
                PDO::PARAM_NULL
            );
        } else {
            $stmt->bindValue(
                ':admin_note',
                $adminNote,
                PDO::PARAM_STR
            );
        }

        $stmt->bindValue(
            ':contact_id',
            $contactId,
            PDO::PARAM_INT
        );

        return $stmt->execute();
    }

    public function deleteContact(
        int $contactId
    ): bool {
        $sql = "
            DELETE FROM contacts
            WHERE contact_id = :contact_id
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(
            ':contact_id',
            $contactId,
            PDO::PARAM_INT
        );

        $stmt->execute();

        if ($stmt->rowCount() !== 1) {
            throw new RuntimeException(
                'Không tìm thấy liên hệ để xóa.'
            );
        }

        return true;
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
                    c.full_name
                        LIKE :keyword_name
                    OR c.email
                        LIKE :keyword_email
                    OR c.phone
                        LIKE :keyword_phone
                    OR c.subject
                        LIKE :keyword_subject
                    OR c.message
                        LIKE :keyword_message
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

            $params[':keyword_subject'] =
                $keyword;

            $params[':keyword_message'] =
                $keyword;
        }

        if (
            ($filters['status'] ?? '')
            !== ''
        ) {
            $where[] =
                'c.status = :status';

            $params[':status'] =
                $filters['status'];
        }

        if (
            ($filters['source'] ?? '')
            === 'user'
        ) {
            $where[] =
                'c.user_id IS NOT NULL';
        }

        if (
            ($filters['source'] ?? '')
            === 'guest'
        ) {
            $where[] =
                'c.user_id IS NULL';
        }
    }
}