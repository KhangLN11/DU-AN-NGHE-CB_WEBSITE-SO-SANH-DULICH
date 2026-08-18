<?php

class User extends Model
{
    public function findByEmail(string $email): ?array
    {
        $sql = "
            SELECT
                u.user_id,
                u.role_id,
                u.full_name,
                u.email,
                u.phone,
                u.password_hash,
                u.avatar,
                u.status,
                u.created_at,
                u.updated_at,
                r.role_name
            FROM users u
            INNER JOIN roles r
                ON u.role_id = r.role_id
            WHERE u.email = :email
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(
            ':email',
            $email,
            PDO::PARAM_STR
        );

        $stmt->execute();

        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function findById(int $userId): ?array
    {
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
                r.role_name
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

    public function create(array $data): int
    {
        $sql = "
            INSERT INTO users (
                role_id,
                full_name,
                email,
                phone,
                password_hash,
                status
            )
            VALUES (
                :role_id,
                :full_name,
                :email,
                :phone,
                :password_hash,
                'active'
            )
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(
            ':role_id',
            $data['role_id'],
            PDO::PARAM_INT
        );

        $stmt->bindValue(
            ':full_name',
            $data['full_name'],
            PDO::PARAM_STR
        );

        $stmt->bindValue(
            ':email',
            $data['email'],
            PDO::PARAM_STR
        );

        if ($data['phone'] === null) {
            $stmt->bindValue(
                ':phone',
                null,
                PDO::PARAM_NULL
            );
        } else {
            $stmt->bindValue(
                ':phone',
                $data['phone'],
                PDO::PARAM_STR
            );
        }

        $stmt->bindValue(
            ':password_hash',
            $data['password_hash'],
            PDO::PARAM_STR
        );

        $stmt->execute();

        return (int) $this->db->lastInsertId();
    }

    public function updateProfile(
        int $userId,
        string $fullName,
        ?string $phone,
        ?string $avatar
    ): bool {
        $sql = "
            UPDATE users
            SET
                full_name = :full_name,
                phone = :phone,
                avatar = :avatar
            WHERE user_id = :user_id
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(
            ':full_name',
            $fullName,
            PDO::PARAM_STR
        );

        if ($phone === null) {
            $stmt->bindValue(
                ':phone',
                null,
                PDO::PARAM_NULL
            );
        } else {
            $stmt->bindValue(
                ':phone',
                $phone,
                PDO::PARAM_STR
            );
        }

        if ($avatar === null) {
            $stmt->bindValue(
                ':avatar',
                null,
                PDO::PARAM_NULL
            );
        } else {
            $stmt->bindValue(
                ':avatar',
                $avatar,
                PDO::PARAM_STR
            );
        }

        $stmt->bindValue(
            ':user_id',
            $userId,
            PDO::PARAM_INT
        );

        return $stmt->execute();
    }

    public function getUserRoleId(): ?int
    {
        $sql = "
            SELECT role_id
            FROM roles
            WHERE role_name = 'USER'
            LIMIT 1
        ";

        $stmt = $this->db->query($sql);

        $roleId = $stmt->fetchColumn();

        return $roleId !== false
            ? (int) $roleId
            : null;
    }
}