<?php

class Favorite extends Model
{
    public function exists(
        int $userId,
        int $tourId
    ): bool {
        $sql = "
            SELECT 1
            FROM favorites
            WHERE user_id = :user_id
              AND tour_id = :tour_id
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(
            ':user_id',
            $userId,
            PDO::PARAM_INT
        );

        $stmt->bindValue(
            ':tour_id',
            $tourId,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return $stmt->fetchColumn() !== false;
    }

    public function add(
        int $userId,
        int $tourId
    ): bool {
        if ($this->exists($userId, $tourId)) {
            return true;
        }

        $sql = "
            INSERT INTO favorites (
                user_id,
                tour_id
            )
            VALUES (
                :user_id,
                :tour_id
            )
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(
            ':user_id',
            $userId,
            PDO::PARAM_INT
        );

        $stmt->bindValue(
            ':tour_id',
            $tourId,
            PDO::PARAM_INT
        );

        return $stmt->execute();
    }

    public function remove(
        int $userId,
        int $tourId
    ): bool {
        $sql = "
            DELETE FROM favorites
            WHERE user_id = :user_id
              AND tour_id = :tour_id
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(
            ':user_id',
            $userId,
            PDO::PARAM_INT
        );

        $stmt->bindValue(
            ':tour_id',
            $tourId,
            PDO::PARAM_INT
        );

        return $stmt->execute();
    }

    public function getFavoriteTourIds(
        int $userId
    ): array {
        $sql = "
            SELECT tour_id
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

        return array_map(
            'intval',
            $stmt->fetchAll(
                PDO::FETCH_COLUMN
            )
        );
    }

    public function getUserFavorites(
        int $userId
    ): array {
        $sql = "
            SELECT
                t.tour_id,
                t.tour_name,
                t.slug,
                t.short_description,
                t.price,
                t.duration_days,
                t.duration_nights,
                t.featured,

                c.category_name,

                co.company_name,

                departure.location_name
                    AS departure_name,

                ti.image_url,

                GROUP_CONCAT(
                    DISTINCT destination.location_name
                    ORDER BY tl.sort_order ASC
                    SEPARATOR ', '
                ) AS destinations

            FROM favorites f

            INNER JOIN tours t
                ON f.tour_id = t.tour_id

            INNER JOIN categories c
                ON t.category_id = c.category_id

            INNER JOIN companies co
                ON t.company_id = co.company_id

            LEFT JOIN locations departure
                ON t.departure_location_id
                    = departure.location_id

            LEFT JOIN tour_images ti
                ON t.tour_id = ti.tour_id
                AND ti.is_thumbnail = 1

            LEFT JOIN tour_locations tl
                ON t.tour_id = tl.tour_id

            LEFT JOIN locations destination
                ON tl.location_id
                    = destination.location_id

            WHERE f.user_id = :user_id
              AND t.status = 'active'

            GROUP BY
                t.tour_id,
                t.tour_name,
                t.slug,
                t.short_description,
                t.price,
                t.duration_days,
                t.duration_nights,
                t.featured,
                c.category_name,
                co.company_name,
                departure.location_name,
                ti.image_url

            ORDER BY t.tour_id DESC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(
            ':user_id',
            $userId,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function countByUser(
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
}