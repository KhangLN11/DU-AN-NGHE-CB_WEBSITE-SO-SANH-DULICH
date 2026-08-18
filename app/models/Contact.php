<?php

class Contact extends Model
{
    public function create(array $data): int
    {
        $sql = "
            INSERT INTO contacts (
                user_id,
                full_name,
                email,
                subject,
                message,
                status
            )
            VALUES (
                :user_id,
                :full_name,
                :email,
                :subject,
                :message,
                'new'
            )
        ";

        $stmt = $this->db->prepare($sql);

        if ($data['user_id'] === null) {
            $stmt->bindValue(
                ':user_id',
                null,
                PDO::PARAM_NULL
            );
        } else {
            $stmt->bindValue(
                ':user_id',
                $data['user_id'],
                PDO::PARAM_INT
            );
        }

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

        $stmt->bindValue(
            ':subject',
            $data['subject'],
            PDO::PARAM_STR
        );

        $stmt->bindValue(
            ':message',
            $data['message'],
            PDO::PARAM_STR
        );

        $stmt->execute();

        return (int) $this->db->lastInsertId();
    }
}