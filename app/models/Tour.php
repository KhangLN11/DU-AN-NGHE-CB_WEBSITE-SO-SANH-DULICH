<?php

class Tour extends Model
{
    public function getFeaturedTours(int $limit = 6): array
    {
        $sql = "
            SELECT
                t.tour_id,
                t.tour_name,
                t.slug,
                t.short_description,
                t.price,
                t.duration_days,
                t.duration_nights,
                c.category_name,
                co.company_name,
                ti.image_url
            FROM tours t
            INNER JOIN categories c
                ON t.category_id = c.category_id
            INNER JOIN companies co
                ON t.company_id = co.company_id
            LEFT JOIN tour_images ti
                ON t.tour_id = ti.tour_id
                AND ti.is_thumbnail = 1
            WHERE t.status = 'active'
              AND t.featured = 1
            ORDER BY t.tour_id DESC
            LIMIT :limit
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getPaginatedTours(
        array $filters,
        int $limit,
        int $offset
    ): array {
        $where = ["t.status = 'active'"];
        $params = [];

        $this->applyFilters($where, $params, $filters);

        $whereSql = implode(' AND ', $where);

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

                departure.location_name AS departure_name,

                ti.image_url,

                GROUP_CONCAT(
                    DISTINCT destination.location_name
                    ORDER BY tl.sort_order ASC
                    SEPARATOR ', '
                ) AS destinations

            FROM tours t

            INNER JOIN categories c
                ON t.category_id = c.category_id

            INNER JOIN companies co
                ON t.company_id = co.company_id

            LEFT JOIN locations departure
                ON t.departure_location_id = departure.location_id

            LEFT JOIN tour_images ti
                ON t.tour_id = ti.tour_id
                AND ti.is_thumbnail = 1

            LEFT JOIN tour_locations tl
                ON t.tour_id = tl.tour_id

            LEFT JOIN locations destination
                ON tl.location_id = destination.location_id

            WHERE {$whereSql}

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

            ORDER BY
                t.featured DESC,
                t.tour_id DESC

            LIMIT :limit
            OFFSET :offset
        ";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function countFilteredTours(array $filters): int
    {
        $where = ["t.status = 'active'"];
        $params = [];

        $this->applyFilters($where, $params, $filters);

        $whereSql = implode(' AND ', $where);

        $sql = "
            SELECT COUNT(*)
            FROM tours t
            INNER JOIN categories c
                ON t.category_id = c.category_id
            INNER JOIN companies co
                ON t.company_id = co.company_id
            WHERE {$whereSql}
        ";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    private function applyFilters(
        array &$where,
        array &$params,
        array $filters
    ): void {
        if ($filters['keyword'] !== '') {
            $where[] = "
                (
                    t.tour_name LIKE :keyword_name
                    OR co.company_name LIKE :keyword_company
                    OR EXISTS (
                        SELECT 1
                        FROM tour_locations search_tl
                        INNER JOIN locations search_location
                            ON search_tl.location_id = search_location.location_id
                        WHERE search_tl.tour_id = t.tour_id
                          AND search_location.location_name LIKE :keyword_location
                    )
                )
            ";

            $keyword = '%' . $filters['keyword'] . '%';

            $params[':keyword_name'] = $keyword;
            $params[':keyword_company'] = $keyword;
            $params[':keyword_location'] = $keyword;
        }

        if ($filters['category'] > 0) {
            $where[] = "t.category_id = :category";
            $params[':category'] = $filters['category'];
        }

        if ($filters['company'] > 0) {
            $where[] = "t.company_id = :company";
            $params[':company'] = $filters['company'];
        }

        if ($filters['location'] > 0) {
            $where[] = "
                EXISTS (
                    SELECT 1
                    FROM tour_locations filter_tl
                    WHERE filter_tl.tour_id = t.tour_id
                      AND filter_tl.location_id = :location
                )
            ";

            $params[':location'] = $filters['location'];
        }

        if ($filters['min_price'] !== null) {
            $where[] = "t.price >= :min_price";
            $params[':min_price'] = $filters['min_price'];
        }

        if ($filters['max_price'] !== null) {
            $where[] = "t.price <= :max_price";
            $params[':max_price'] = $filters['max_price'];
        }
    }

    public function findById(int $tourId): ?array
{
    $sql = "
        SELECT
            t.tour_id,
            t.category_id,
            t.company_id,
            t.departure_location_id,
            t.tour_name,
            t.slug,
            t.short_description,
            t.description,
            t.price,
            t.duration_days,
            t.duration_nights,
            t.source_url,
            t.featured,
            t.status,

            c.category_name,

            co.company_name,
            co.logo AS company_logo,
            co.description AS company_description,
            co.address AS company_address,
            co.phone AS company_phone,
            co.email AS company_email,
            co.website AS company_website,

            departure.location_name AS departure_name,
            departure.province_city AS departure_province,
            departure.country AS departure_country

        FROM tours t

        INNER JOIN categories c
            ON t.category_id = c.category_id

        INNER JOIN companies co
            ON t.company_id = co.company_id

        LEFT JOIN locations departure
            ON t.departure_location_id = departure.location_id

        WHERE t.tour_id = :tour_id
          AND t.status = 'active'

        LIMIT 1
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->bindValue(
        ':tour_id',
        $tourId,
        PDO::PARAM_INT
    );

    $stmt->execute();

    $tour = $stmt->fetch();

    return $tour ?: null;
}

public function getTourImages(int $tourId): array
{
    $sql = "
        SELECT
            image_id,
            image_url,
            alt_text,
            is_thumbnail,
            sort_order
        FROM tour_images
        WHERE tour_id = :tour_id
        ORDER BY
            is_thumbnail DESC,
            sort_order ASC,
            image_id ASC
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->bindValue(
        ':tour_id',
        $tourId,
        PDO::PARAM_INT
    );

    $stmt->execute();

    return $stmt->fetchAll();
}

public function getTourSchedules(int $tourId): array
{
    $sql = "
        SELECT
            schedule_id,
            day_number,
            title,
            description
        FROM tour_schedules
        WHERE tour_id = :tour_id
        ORDER BY day_number ASC
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->bindValue(
        ':tour_id',
        $tourId,
        PDO::PARAM_INT
    );

    $stmt->execute();

    return $stmt->fetchAll();
}

public function getTourLocations(int $tourId): array
{
    $sql = "
        SELECT
            l.location_id,
            l.location_name,
            l.slug,
            l.province_city,
            l.country,
            l.address,
            l.latitude,
            l.longitude,
            l.description,
            l.image,
            tl.sort_order,
            tl.note
        FROM tour_locations tl
        INNER JOIN locations l
            ON tl.location_id = l.location_id
        WHERE tl.tour_id = :tour_id
          AND l.status = 'active'
        ORDER BY
            tl.sort_order ASC,
            l.location_name ASC
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->bindValue(
        ':tour_id',
        $tourId,
        PDO::PARAM_INT
    );

    $stmt->execute();

    return $stmt->fetchAll();
}

public function getRelatedTours(
    int $tourId,
    int $categoryId,
    int $limit = 3
): array {
    $sql = "
        SELECT
            t.tour_id,
            t.tour_name,
            t.price,
            t.duration_days,
            t.duration_nights,
            c.category_name,
            co.company_name,
            ti.image_url
        FROM tours t

        INNER JOIN categories c
            ON t.category_id = c.category_id

        INNER JOIN companies co
            ON t.company_id = co.company_id

        LEFT JOIN tour_images ti
            ON t.tour_id = ti.tour_id
            AND ti.is_thumbnail = 1

        WHERE t.status = 'active'
          AND t.tour_id <> :tour_id
          AND t.category_id = :category_id

        ORDER BY
            t.featured DESC,
            t.tour_id DESC

        LIMIT :limit
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->bindValue(
        ':tour_id',
        $tourId,
        PDO::PARAM_INT
    );

    $stmt->bindValue(
        ':category_id',
        $categoryId,
        PDO::PARAM_INT
    );

    $stmt->bindValue(
        ':limit',
        $limit,
        PDO::PARAM_INT
    );

    $stmt->execute();

    return $stmt->fetchAll();
    }

    public function getToursForCompare(array $tourIds): array
{
    $tourIds = array_values(
        array_unique(
            array_filter(
                array_map(
                    'intval',
                    $tourIds
                ),
                function ($id) {
                    return $id > 0;
                }
            )
        )
    );

    $tourIds = array_slice($tourIds, 0, 3);

    if (empty($tourIds)) {
        return [];
    }

    $placeholders = [];

    foreach ($tourIds as $index => $tourId) {
        $placeholders[] = ':tour_' . $index;
    }

    $sql = "
        SELECT
            t.tour_id,
            t.tour_name,
            t.slug,
            t.short_description,
            t.price,
            t.duration_days,
            t.duration_nights,
            t.source_url,
            t.featured,

            c.category_name,

            co.company_name,

            departure.location_name AS departure_name,

            ti.image_url,

            GROUP_CONCAT(
                DISTINCT destination.location_name
                ORDER BY tl.sort_order ASC
                SEPARATOR ', '
            ) AS destinations

        FROM tours t

        INNER JOIN categories c
            ON t.category_id = c.category_id

        INNER JOIN companies co
            ON t.company_id = co.company_id

        LEFT JOIN locations departure
            ON t.departure_location_id = departure.location_id

        LEFT JOIN tour_images ti
            ON t.tour_id = ti.tour_id
            AND ti.is_thumbnail = 1

        LEFT JOIN tour_locations tl
            ON t.tour_id = tl.tour_id

        LEFT JOIN locations destination
            ON tl.location_id = destination.location_id

        WHERE t.status = 'active'
          AND t.tour_id IN (
              " . implode(', ', $placeholders) . "
          )

        GROUP BY
            t.tour_id,
            t.tour_name,
            t.slug,
            t.short_description,
            t.price,
            t.duration_days,
            t.duration_nights,
            t.source_url,
            t.featured,
            c.category_name,
            co.company_name,
            departure.location_name,
            ti.image_url
    ";

    $stmt = $this->db->prepare($sql);

    foreach ($tourIds as $index => $tourId) {
        $stmt->bindValue(
            ':tour_' . $index,
            $tourId,
            PDO::PARAM_INT
        );
    }

    $stmt->execute();

    $rows = $stmt->fetchAll();

    $indexedTours = [];

    foreach ($rows as $tour) {
        $indexedTours[(int) $tour['tour_id']] = $tour;
    }

    $orderedTours = [];

    foreach ($tourIds as $tourId) {
        if (isset($indexedTours[$tourId])) {
            $orderedTours[] = $indexedTours[$tourId];
        }
    }

    return $orderedTours;
}
}