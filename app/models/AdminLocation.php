<?php

class AdminLocation extends Model
{
    public function getCountries(): array
    {
    $sql = "
        SELECT DISTINCT country
        FROM locations
        WHERE country IS NOT NULL
          AND TRIM(country) <> ''
        ORDER BY country ASC
    ";

    $stmt = $this->db->query(
        $sql
    );

    return $stmt->fetchAll(
        PDO::FETCH_COLUMN
    );
    }

    public function getPaginatedLocations(
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

                (
                    SELECT COUNT(*)
                    FROM tours departure_tour
                    WHERE departure_tour.departure_location_id
                        = l.location_id
                ) AS departure_tour_count,

                (
                    SELECT COUNT(*)
                    FROM tour_locations destination_tl
                    WHERE destination_tl.location_id
                        = l.location_id
                ) AS destination_tour_count

            FROM locations l

            WHERE {$whereSql}

            ORDER BY
                l.location_id DESC

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

    public function countLocations(
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

        return (int) $stmt->fetchColumn();
    }

    public function findById(
        int $locationId
    ): ?array {
        $sql = "
            SELECT
                location_id,
                location_name,
                slug,
                province_city,
                country,
                address,
                latitude,
                longitude,
                description,
                image,
                status,
                created_at,
                updated_at
            FROM locations
            WHERE location_id = :location_id
            LIMIT 1
        ";

        $stmt = $this->db->prepare(
            $sql
        );

        $stmt->bindValue(
            ':location_id',
            $locationId,
            PDO::PARAM_INT
        );

        $stmt->execute();

        $location = $stmt->fetch();

        return $location ?: null;
    }

    public function slugExists(
        string $slug
    ): bool {
        $sql = "
            SELECT 1
            FROM locations
            WHERE slug = :slug
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

        return $stmt->fetchColumn() !== false;
    }

    public function slugExistsExcept(
        string $slug,
        int $locationId
    ): bool {
        $sql = "
            SELECT 1
            FROM locations
            WHERE slug = :slug
              AND location_id <> :location_id
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

        $stmt->bindValue(
            ':location_id',
            $locationId,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return $stmt->fetchColumn() !== false;
    }

    public function createLocation(
        array $data
    ): int {
        $sql = "
            INSERT INTO locations (
                location_name,
                slug,
                province_city,
                country,
                address,
                latitude,
                longitude,
                description,
                image,
                status
            )
            VALUES (
                :location_name,
                :slug,
                :province_city,
                :country,
                :address,
                :latitude,
                :longitude,
                :description,
                :image,
                :status
            )
        ";

        $stmt = $this->db->prepare(
            $sql
        );

        $this->bindLocationData(
            $stmt,
            $data
        );

        $stmt->execute();

        return (int) $this->db->lastInsertId();
    }

    public function updateLocation(
        int $locationId,
        array $data
    ): bool {
        $sql = "
            UPDATE locations
            SET
                location_name = :location_name,
                slug = :slug,
                province_city = :province_city,
                country = :country,
                address = :address,
                latitude = :latitude,
                longitude = :longitude,
                description = :description,
                image = :image,
                status = :status
            WHERE location_id = :location_id
        ";

        $stmt = $this->db->prepare(
            $sql
        );

        $this->bindLocationData(
            $stmt,
            $data
        );

        $stmt->bindValue(
            ':location_id',
            $locationId,
            PDO::PARAM_INT
        );

        return $stmt->execute();
    }

    public function getDepartureTourCount(
        int $locationId
    ): int {
        $sql = "
            SELECT COUNT(*)
            FROM tours
            WHERE departure_location_id = :location_id
        ";

        $stmt = $this->db->prepare(
            $sql
        );

        $stmt->bindValue(
            ':location_id',
            $locationId,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function getDestinationTourCount(
        int $locationId
    ): int {
        $sql = "
            SELECT COUNT(*)
            FROM tour_locations
            WHERE location_id = :location_id
        ";

        $stmt = $this->db->prepare(
            $sql
        );

        $stmt->bindValue(
            ':location_id',
            $locationId,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function isLocationInUse(
        int $locationId
    ): bool {
        return
            $this->getDepartureTourCount(
                $locationId
            ) > 0
            ||
            $this->getDestinationTourCount(
                $locationId
            ) > 0;
    }

    public function disableLocation(
        int $locationId
    ): bool {
        $sql = "
            UPDATE locations
            SET status = 'inactive'
            WHERE location_id = :location_id
        ";

        $stmt = $this->db->prepare(
            $sql
        );

        $stmt->bindValue(
            ':location_id',
            $locationId,
            PDO::PARAM_INT
        );

        return $stmt->execute();
    }

    public function deleteLocation(
        int $locationId
    ): bool {
        try {
            $this->db->beginTransaction();

            $departureStmt =
                $this->db->prepare("
                    SELECT COUNT(*)
                    FROM tours
                    WHERE departure_location_id
                        = :location_id
                ");

            $departureStmt->bindValue(
                ':location_id',
                $locationId,
                PDO::PARAM_INT
            );

            $departureStmt->execute();

            $departureCount =
                (int) $departureStmt
                    ->fetchColumn();

            $destinationStmt =
                $this->db->prepare("
                    SELECT COUNT(*)
                    FROM tour_locations
                    WHERE location_id
                        = :location_id
                ");

            $destinationStmt->bindValue(
                ':location_id',
                $locationId,
                PDO::PARAM_INT
            );

            $destinationStmt->execute();

            $destinationCount =
                (int) $destinationStmt
                    ->fetchColumn();

            if (
                $departureCount > 0
                || $destinationCount > 0
            ) {
                throw new RuntimeException(
                    'Địa điểm đang được Tour sử dụng.'
                );
            }

            $deleteStmt =
                $this->db->prepare("
                    DELETE FROM locations
                    WHERE location_id = :location_id
                ");

            $deleteStmt->bindValue(
                ':location_id',
                $locationId,
                PDO::PARAM_INT
            );

            $deleteStmt->execute();

            if (
                $deleteStmt->rowCount()
                !== 1
            ) {
                throw new RuntimeException(
                    'Không tìm thấy địa điểm để xóa.'
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

    private function bindLocationData(
        PDOStatement $stmt,
        array $data
    ): void {
        $stmt->bindValue(
            ':location_name',
            $data['location_name'],
            PDO::PARAM_STR
        );

        $stmt->bindValue(
            ':slug',
            $data['slug'],
            PDO::PARAM_STR
        );

        $this->bindNullableString(
            $stmt,
            ':province_city',
            $data['province_city']
        );

        $stmt->bindValue(
            ':country',
            $data['country'],
            PDO::PARAM_STR
        );

        $this->bindNullableString(
            $stmt,
            ':address',
            $data['address']
        );

        if ($data['latitude'] === null) {
            $stmt->bindValue(
                ':latitude',
                null,
                PDO::PARAM_NULL
            );
        } else {
            $stmt->bindValue(
                ':latitude',
                $data['latitude']
            );
        }

        if ($data['longitude'] === null) {
            $stmt->bindValue(
                ':longitude',
                null,
                PDO::PARAM_NULL
            );
        } else {
            $stmt->bindValue(
                ':longitude',
                $data['longitude']
            );
        }

        $this->bindNullableString(
            $stmt,
            ':description',
            $data['description']
        );

        $this->bindNullableString(
            $stmt,
            ':image',
            $data['image']
        );

        $stmt->bindValue(
            ':status',
            $data['status'],
            PDO::PARAM_STR
        );
    }

    private function bindNullableString(
        PDOStatement $stmt,
        string $parameter,
        ?string $value
    ): void {
        if ($value === null) {
            $stmt->bindValue(
                $parameter,
                null,
                PDO::PARAM_NULL
            );

            return;
        }

        $stmt->bindValue(
            $parameter,
            $value,
            PDO::PARAM_STR
        );
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
                    l.location_name
                        LIKE :keyword_name
                    OR l.slug
                        LIKE :keyword_slug
                    OR l.province_city
                        LIKE :keyword_province
                    OR l.country
                        LIKE :keyword_country
                    OR l.address
                        LIKE :keyword_address
                )
            ";

            $keyword =
                '%'
                . $filters['keyword']
                . '%';

            $params[':keyword_name'] =
                $keyword;

            $params[':keyword_slug'] =
                $keyword;

            $params[':keyword_province'] =
                $keyword;

            $params[':keyword_country'] =
                $keyword;

            $params[':keyword_address'] =
                $keyword;
        }

        if (
            ($filters['status'] ?? '')
            !== ''
        ) {
            $where[] =
                'l.status = :status';

            $params[':status'] =
                $filters['status'];
        }

        if (
            ($filters['country'] ?? '')
            !== ''
        ) {
            $where[] =
                'l.country = :country';

            $params[':country'] =
                $filters['country'];
        }
    }
}