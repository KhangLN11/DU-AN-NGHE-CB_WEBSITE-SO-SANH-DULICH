<?php

class Location extends Model
{
    public function getPopularLocations(int $limit = 6): array
    {
        $sql = "
            SELECT
                l.location_id,
                l.location_name,
                l.slug,
                l.province_city,
                l.country,
                l.image,
                COUNT(DISTINCT tl.tour_id) AS tour_count
            FROM locations l
            INNER JOIN tour_locations tl
                ON l.location_id = tl.location_id
            INNER JOIN tours t
                ON tl.tour_id = t.tour_id
                AND t.status = 'active'
            WHERE l.status = 'active'
            GROUP BY
                l.location_id,
                l.location_name,
                l.slug,
                l.province_city,
                l.country,
                l.image
            ORDER BY tour_count DESC, l.location_name ASC
            LIMIT :limit
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getActiveLocations(): array
    {
        $sql = "
            SELECT
                location_id,
                location_name
            FROM locations
            WHERE status = 'active'
            ORDER BY location_name ASC
        ";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }
}