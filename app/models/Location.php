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

    public function getPaginatedDestinations(
    array $filters,
    int $limit,
    int $offset
): array {
    $where = [
        "l.status = 'active'"
    ];

    $params = [];

    if (
        ($filters['keyword'] ?? '')
        !== ''
    ) {
        $where[] = "
            (
                l.location_name
                    LIKE :keyword_name
                OR l.province_city
                    LIKE :keyword_province
                OR l.country
                    LIKE :keyword_country
            )
        ";

        $keyword =
            '%'
            . $filters['keyword']
            . '%';

        $params[':keyword_name'] =
            $keyword;

        $params[':keyword_province'] =
            $keyword;

        $params[':keyword_country'] =
            $keyword;
    }

    if (
        ($filters['province'] ?? '')
        !== ''
    ) {
        $where[] =
            'l.province_city = :province';

        $params[':province'] =
            $filters['province'];
    }

    $whereSql = implode(
        ' AND ',
        $where
    );

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
            l.status,

            COUNT(
                DISTINCT tl.tour_id
            ) AS tour_count

        FROM locations l

        LEFT JOIN tour_locations tl
            ON l.location_id =
               tl.location_id

        LEFT JOIN tours t
            ON tl.tour_id =
               t.tour_id
            AND t.status = 'active'

        WHERE {$whereSql}

        GROUP BY
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
            l.status

        ORDER BY
            tour_count DESC,
            l.location_name ASC

        LIMIT :limit
        OFFSET :offset
    ";

    $stmt = $this->db->prepare(
        $sql
    );

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

public function countDestinations(
    array $filters
): int {
    $where = [
        "l.status = 'active'"
    ];

    $params = [];

    if (
        ($filters['keyword'] ?? '')
        !== ''
    ) {
        $where[] = "
            (
                l.location_name
                    LIKE :keyword_name
                OR l.province_city
                    LIKE :keyword_province
                OR l.country
                    LIKE :keyword_country
            )
        ";

        $keyword =
            '%'
            . $filters['keyword']
            . '%';

        $params[':keyword_name'] =
            $keyword;

        $params[':keyword_province'] =
            $keyword;

        $params[':keyword_country'] =
            $keyword;
    }

    if (
        ($filters['province'] ?? '')
        !== ''
    ) {
        $where[] =
            'l.province_city = :province';

        $params[':province'] =
            $filters['province'];
    }

    $whereSql = implode(
        ' AND ',
        $where
    );

    $sql = "
        SELECT COUNT(*)
        FROM locations l
        WHERE {$whereSql}
    ";

    $stmt = $this->db->prepare(
        $sql
    );

    foreach (
        $params as $key => $value
    ) {
        $stmt->bindValue(
            $key,
            $value
        );
    }

    $stmt->execute();

    return (int)
        $stmt->fetchColumn();
}

public function getDestinationProvinces(): array
{
    $sql = "
        SELECT DISTINCT
            province_city
        FROM locations
        WHERE status = 'active'
          AND province_city IS NOT NULL
          AND TRIM(province_city) <> ''
        ORDER BY
            province_city ASC
    ";

    $stmt = $this->db->query(
        $sql
    );

    return $stmt->fetchAll(
        PDO::FETCH_COLUMN
    );
}

public function findActiveBySlug(
    string $slug
): ?array {
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
            l.status,
            l.created_at,
            l.updated_at,

            COUNT(
                DISTINCT t.tour_id
            ) AS tour_count

        FROM locations l

        LEFT JOIN tour_locations tl
            ON l.location_id =
               tl.location_id

        LEFT JOIN tours t
            ON tl.tour_id =
               t.tour_id
            AND t.status = 'active'

        WHERE l.slug = :slug
          AND l.status = 'active'

        GROUP BY
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
            l.status,
            l.created_at,
            l.updated_at

        LIMIT 1
    ";

    $stmt = $this->db->prepare(
        $sql
    );

    $stmt->bindValue(
        ':slug',
        $slug,
        PDO::PARAM_STR
    );

    $stmt->execute();

    $location = $stmt->fetch();

    return $location ?: null;
}

public function getDestinationTours(
    int $locationId,
    int $limit = 6
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

            ti.image_url

        FROM tour_locations tl

        INNER JOIN tours t
            ON tl.tour_id = t.tour_id

        INNER JOIN categories c
            ON t.category_id = c.category_id

        INNER JOIN companies co
            ON t.company_id = co.company_id

        LEFT JOIN locations departure
            ON t.departure_location_id =
               departure.location_id

        LEFT JOIN tour_images ti
            ON t.tour_id = ti.tour_id
            AND ti.is_thumbnail = 1

        WHERE tl.location_id = :location_id
          AND t.status = 'active'

        ORDER BY
            t.featured DESC,
            t.tour_id DESC

        LIMIT :limit
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->bindValue(
        ':location_id',
        $locationId,
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

public function countDestinationTours(
    int $locationId
): int {
    $sql = "
        SELECT COUNT(
            DISTINCT t.tour_id
        )

        FROM tour_locations tl

        INNER JOIN tours t
            ON tl.tour_id = t.tour_id

        WHERE tl.location_id = :location_id
          AND t.status = 'active'
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->bindValue(
        ':location_id',
        $locationId,
        PDO::PARAM_INT
    );

    $stmt->execute();

    return (int)
        $stmt->fetchColumn();
}
}

