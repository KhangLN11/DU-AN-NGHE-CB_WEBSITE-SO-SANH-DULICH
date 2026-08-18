<?php

class Category extends Model
{
    public function getActiveCategories(): array
    {
        $sql = "
            SELECT
                c.category_id,
                c.category_name,
                c.slug,
                c.description,
                COUNT(t.tour_id) AS tour_count
            FROM categories c
            LEFT JOIN tours t
                ON c.category_id = t.category_id
                AND t.status = 'active'
            WHERE c.status = 'active'
            GROUP BY
                c.category_id,
                c.category_name,
                c.slug,
                c.description
            ORDER BY c.category_name ASC
        ";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }
}