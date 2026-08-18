<?php

class AdminDashboard extends Model
{
    public function getStatistics(): array
    {
        return [
            'total_tours' => $this->countTable('tours'),
            'active_tours' => $this->countActiveTours(),
            'total_users' => $this->countTable('users'),
            'total_categories' => $this->countTable('categories'),
            'total_companies' => $this->countTable('companies'),
            'total_locations' => $this->countTable('locations'),
            'total_favorites' => $this->countTable('favorites'),
            'new_contacts' => $this->countNewContacts()
        ];
    }

    public function getLatestTours(
        int $limit = 5
    ): array {
        $sql = "
            SELECT
                t.tour_id,
                t.tour_name,
                t.price,
                t.status,
                t.featured,
                c.category_name,
                co.company_name
            FROM tours t
            INNER JOIN categories c
                ON t.category_id = c.category_id
            INNER JOIN companies co
                ON t.company_id = co.company_id
            ORDER BY t.tour_id DESC
            LIMIT :limit
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(
            ':limit',
            $limit,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getLatestContacts(
        int $limit = 5
    ): array {
        $sql = "
            SELECT
                contact_id,
                user_id,
                full_name,
                email,
                subject,
                status
            FROM contacts
            ORDER BY contact_id DESC
            LIMIT :limit
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(
            ':limit',
            $limit,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return $stmt->fetchAll();
    }

    private function countTable(
        string $table
    ): int {
        $allowedTables = [
            'tours',
            'users',
            'categories',
            'companies',
            'locations',
            'favorites'
        ];

        if (
            !in_array(
                $table,
                $allowedTables,
                true
            )
        ) {
            return 0;
        }

        $sql = "
            SELECT COUNT(*)
            FROM {$table}
        ";

        $stmt = $this->db->query($sql);

        return (int) $stmt->fetchColumn();
    }

    private function countActiveTours(): int
    {
        $sql = "
            SELECT COUNT(*)
            FROM tours
            WHERE status = 'active'
        ";

        $stmt = $this->db->query($sql);

        return (int) $stmt->fetchColumn();
    }

    private function countNewContacts(): int
    {
        $sql = "
            SELECT COUNT(*)
            FROM contacts
            WHERE status = 'new'
        ";

        $stmt = $this->db->query($sql);

        return (int) $stmt->fetchColumn();
    }
}