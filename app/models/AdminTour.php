<?php

class AdminTour extends Model
{
    public function getPaginatedTours(
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
                t.tour_id,
                t.tour_name,
                t.slug,
                t.price,
                t.duration_days,
                t.duration_nights,
                t.featured,
                t.status,

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

            FROM tours t

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

            WHERE {$whereSql}

            GROUP BY
                t.tour_id,
                t.tour_name,
                t.slug,
                t.price,
                t.duration_days,
                t.duration_nights,
                t.featured,
                t.status,
                c.category_name,
                co.company_name,
                departure.location_name,
                ti.image_url

            ORDER BY
                t.tour_id DESC

            LIMIT :limit
            OFFSET :offset
        ";

        $stmt = $this->db->prepare(
            $sql
        );

        foreach (
            $params
            as $key => $value
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

    public function countTours(
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
            FROM tours t

            INNER JOIN categories c
                ON t.category_id = c.category_id

            INNER JOIN companies co
                ON t.company_id = co.company_id

            WHERE {$whereSql}
        ";

        $stmt = $this->db->prepare(
            $sql
        );

        foreach (
            $params
            as $key => $value
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

    public function getCategories(): array
    {
        $sql = "
            SELECT
                category_id,
                category_name,
                status
            FROM categories
            ORDER BY category_name ASC
        ";

        $stmt = $this->db->query(
            $sql
        );

        return $stmt->fetchAll();
    }

    public function getCompanies(): array
    {
        $sql = "
            SELECT
                company_id,
                company_name,
                status
            FROM companies
            ORDER BY company_name ASC
        ";

        $stmt = $this->db->query(
            $sql
        );

        return $stmt->fetchAll();
    }

    private function applyFilters(
        array &$where,
        array &$params,
        array $filters
    ): void {
        if (
            $filters['keyword'] !== ''
        ) {
            $where[] = "
                (
                    t.tour_name
                        LIKE :keyword_name
                    OR co.company_name
                        LIKE :keyword_company
                    OR c.category_name
                        LIKE :keyword_category
                )
            ";

            $keyword =
                '%'
                . $filters['keyword']
                . '%';

            $params[':keyword_name'] =
                $keyword;

            $params[':keyword_company'] =
                $keyword;

            $params[':keyword_category'] =
                $keyword;
        }

        if (
            $filters['status'] !== ''
        ) {
            $where[] =
                't.status = :status';

            $params[':status'] =
                $filters['status'];
        }

        if (
            $filters['category'] > 0
        ) {
            $where[] =
                't.category_id = :category';

            $params[':category'] =
                $filters['category'];
        }

        if (
            $filters['company'] > 0
        ) {
            $where[] =
                't.company_id = :company';

            $params[':company'] =
                $filters['company'];
        }
    }

    public function getActiveCategories(): array
{
    $sql = "
        SELECT
            category_id,
            category_name
        FROM categories
        WHERE status = 'active'
        ORDER BY category_name ASC
    ";

    $stmt = $this->db->query($sql);

    return $stmt->fetchAll();
}

public function getActiveCompanies(): array
{
    $sql = "
        SELECT
            company_id,
            company_name
        FROM companies
        WHERE status = 'active'
        ORDER BY company_name ASC
    ";

    $stmt = $this->db->query($sql);

    return $stmt->fetchAll();
}

public function getActiveLocations(): array
{
    $sql = "
        SELECT
            location_id,
            location_name,
            province_city,
            country
        FROM locations
        WHERE status = 'active'
        ORDER BY location_name ASC
    ";

    $stmt = $this->db->query($sql);

    return $stmt->fetchAll();
}

public function categoryExists(int $categoryId): bool
{
    $sql = "
        SELECT 1
        FROM categories
        WHERE category_id = :category_id
          AND status = 'active'
        LIMIT 1
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->bindValue(
        ':category_id',
        $categoryId,
        PDO::PARAM_INT
    );

    $stmt->execute();

    return $stmt->fetchColumn() !== false;
}

public function companyExists(int $companyId): bool
{
    $sql = "
        SELECT 1
        FROM companies
        WHERE company_id = :company_id
          AND status = 'active'
        LIMIT 1
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->bindValue(
        ':company_id',
        $companyId,
        PDO::PARAM_INT
    );

    $stmt->execute();

    return $stmt->fetchColumn() !== false;
}

public function locationExists(int $locationId): bool
{
    $sql = "
        SELECT 1
        FROM locations
        WHERE location_id = :location_id
          AND status = 'active'
        LIMIT 1
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->bindValue(
        ':location_id',
        $locationId,
        PDO::PARAM_INT
    );

    $stmt->execute();

    return $stmt->fetchColumn() !== false;
}

public function slugExists(string $slug): bool
{
    $sql = "
        SELECT 1
        FROM tours
        WHERE slug = :slug
        LIMIT 1
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->bindValue(
        ':slug',
        $slug,
        PDO::PARAM_STR
    );

    $stmt->execute();

    return $stmt->fetchColumn() !== false;
}

public function createTour(array $data): int
{
    $sql = "
        INSERT INTO tours (
            category_id,
            company_id,
            departure_location_id,
            tour_name,
            slug,
            short_description,
            description,
            price,
            duration_days,
            duration_nights,
            source_url,
            featured,
            status
        )
        VALUES (
            :category_id,
            :company_id,
            :departure_location_id,
            :tour_name,
            :slug,
            :short_description,
            :description,
            :price,
            :duration_days,
            :duration_nights,
            :source_url,
            :featured,
            :status
        )
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->bindValue(
        ':category_id',
        $data['category_id'],
        PDO::PARAM_INT
    );

    $stmt->bindValue(
        ':company_id',
        $data['company_id'],
        PDO::PARAM_INT
    );

    $stmt->bindValue(
        ':departure_location_id',
        $data['departure_location_id'],
        PDO::PARAM_INT
    );

    $stmt->bindValue(
        ':tour_name',
        $data['tour_name'],
        PDO::PARAM_STR
    );

    $stmt->bindValue(
        ':slug',
        $data['slug'],
        PDO::PARAM_STR
    );

    if ($data['short_description'] === null) {
        $stmt->bindValue(
            ':short_description',
            null,
            PDO::PARAM_NULL
        );
    } else {
        $stmt->bindValue(
            ':short_description',
            $data['short_description'],
            PDO::PARAM_STR
        );
    }

    if ($data['description'] === null) {
        $stmt->bindValue(
            ':description',
            null,
            PDO::PARAM_NULL
        );
    } else {
        $stmt->bindValue(
            ':description',
            $data['description'],
            PDO::PARAM_STR
        );
    }

    $stmt->bindValue(
        ':price',
        $data['price']
    );

    $stmt->bindValue(
        ':duration_days',
        $data['duration_days'],
        PDO::PARAM_INT
    );

    $stmt->bindValue(
        ':duration_nights',
        $data['duration_nights'],
        PDO::PARAM_INT
    );

    if ($data['source_url'] === null) {
        $stmt->bindValue(
            ':source_url',
            null,
            PDO::PARAM_NULL
        );
    } else {
        $stmt->bindValue(
            ':source_url',
            $data['source_url'],
            PDO::PARAM_STR
        );
    }

    $stmt->bindValue(
        ':featured',
        $data['featured'],
        PDO::PARAM_INT
    );

    $stmt->bindValue(
        ':status',
        $data['status'],
        PDO::PARAM_STR
    );

    $stmt->execute();

    return (int) $this->db->lastInsertId();
    }

    public function findById(int $tourId): ?array
{
    $sql = "
        SELECT
            tour_id,
            category_id,
            company_id,
            departure_location_id,
            tour_name,
            slug,
            short_description,
            description,
            price,
            duration_days,
            duration_nights,
            source_url,
            featured,
            status
        FROM tours
        WHERE tour_id = :tour_id
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

    public function slugExistsExcept(
    string $slug,
    int $tourId
): bool {
    $sql = "
        SELECT 1
        FROM tours
        WHERE slug = :slug
          AND tour_id <> :tour_id
        LIMIT 1
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->bindValue(
        ':slug',
        $slug,
        PDO::PARAM_STR
    );

    $stmt->bindValue(
        ':tour_id',
        $tourId,
        PDO::PARAM_INT
    );

    $stmt->execute();

    return $stmt->fetchColumn() !== false;
    }

    public function updateTour(
    int $tourId,
    array $data
): bool {
    $sql = "
        UPDATE tours
        SET
            category_id = :category_id,
            company_id = :company_id,
            departure_location_id = :departure_location_id,
            tour_name = :tour_name,
            slug = :slug,
            short_description = :short_description,
            description = :description,
            price = :price,
            duration_days = :duration_days,
            duration_nights = :duration_nights,
            source_url = :source_url,
            featured = :featured,
            status = :status
        WHERE tour_id = :tour_id
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->bindValue(
        ':category_id',
        $data['category_id'],
        PDO::PARAM_INT
    );

    $stmt->bindValue(
        ':company_id',
        $data['company_id'],
        PDO::PARAM_INT
    );

    $stmt->bindValue(
        ':departure_location_id',
        $data['departure_location_id'],
        PDO::PARAM_INT
    );

    $stmt->bindValue(
        ':tour_name',
        $data['tour_name'],
        PDO::PARAM_STR
    );

    $stmt->bindValue(
        ':slug',
        $data['slug'],
        PDO::PARAM_STR
    );

    if ($data['short_description'] === null) {
        $stmt->bindValue(
            ':short_description',
            null,
            PDO::PARAM_NULL
        );
    } else {
        $stmt->bindValue(
            ':short_description',
            $data['short_description'],
            PDO::PARAM_STR
        );
    }

    if ($data['description'] === null) {
        $stmt->bindValue(
            ':description',
            null,
            PDO::PARAM_NULL
        );
    } else {
        $stmt->bindValue(
            ':description',
            $data['description'],
            PDO::PARAM_STR
        );
    }

    $stmt->bindValue(
        ':price',
        $data['price']
    );

    $stmt->bindValue(
        ':duration_days',
        $data['duration_days'],
        PDO::PARAM_INT
    );

    $stmt->bindValue(
        ':duration_nights',
        $data['duration_nights'],
        PDO::PARAM_INT
    );

    if ($data['source_url'] === null) {
        $stmt->bindValue(
            ':source_url',
            null,
            PDO::PARAM_NULL
        );
    } else {
        $stmt->bindValue(
            ':source_url',
            $data['source_url'],
            PDO::PARAM_STR
        );
    }

    $stmt->bindValue(
        ':featured',
        $data['featured'],
        PDO::PARAM_INT
    );

    $stmt->bindValue(
        ':status',
        $data['status'],
        PDO::PARAM_STR
    );

    $stmt->bindValue(
        ':tour_id',
        $tourId,
        PDO::PARAM_INT
    );

    return $stmt->execute();
    }

    public function deleteTour(int $tourId): bool
{
    try {
        $this->db->beginTransaction();

        $stmt = $this->db->prepare("
            DELETE FROM favorites
            WHERE tour_id = :tour_id
        ");

        $stmt->bindValue(
            ':tour_id',
            $tourId,
            PDO::PARAM_INT
        );

        $stmt->execute();


        $stmt = $this->db->prepare("
            DELETE FROM tour_locations
            WHERE tour_id = :tour_id
        ");

        $stmt->bindValue(
            ':tour_id',
            $tourId,
            PDO::PARAM_INT
        );

        $stmt->execute();


        $stmt = $this->db->prepare("
            DELETE FROM tour_schedules
            WHERE tour_id = :tour_id
        ");

        $stmt->bindValue(
            ':tour_id',
            $tourId,
            PDO::PARAM_INT
        );

        $stmt->execute();


        $stmt = $this->db->prepare("
            DELETE FROM tour_images
            WHERE tour_id = :tour_id
        ");

        $stmt->bindValue(
            ':tour_id',
            $tourId,
            PDO::PARAM_INT
        );

        $stmt->execute();


        $stmt = $this->db->prepare("
            DELETE FROM tours
            WHERE tour_id = :tour_id
        ");

        $stmt->bindValue(
            ':tour_id',
            $tourId,
            PDO::PARAM_INT
        );

        $stmt->execute();

        if ($stmt->rowCount() !== 1) {
            throw new RuntimeException(
                'Không tìm thấy Tour để xóa.'
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

    public function getTourImagePaths(
    int $tourId
): array {
    $sql = "
        SELECT image_url
        FROM tour_images
        WHERE tour_id = :tour_id
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->bindValue(
        ':tour_id',
        $tourId,
        PDO::PARAM_INT
    );

    $stmt->execute();

    return $stmt->fetchAll(
        PDO::FETCH_COLUMN
    );
    }

    public function getAvailableLocations(): array
{
    $sql = "
        SELECT
            location_id,
            location_name,
            province_city,
            country
        FROM locations
        WHERE status = 'active'
        ORDER BY
            province_city ASC,
            location_name ASC
    ";

    $stmt = $this->db->query($sql);

    return $stmt->fetchAll();
    }

    public function getTourLocations(
    int $tourId
): array {
    $sql = "
        SELECT
            tl.location_id,
            tl.sort_order,
            tl.note,
            l.location_name,
            l.province_city,
            l.country
        FROM tour_locations tl
        INNER JOIN locations l
            ON tl.location_id = l.location_id
        WHERE tl.tour_id = :tour_id
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

    public function updateTourLocations(
    int $tourId,
    array $locations
): bool {
    try {
        $this->db->beginTransaction();

        $deleteStmt = $this->db->prepare("
            DELETE FROM tour_locations
            WHERE tour_id = :tour_id
        ");

        $deleteStmt->bindValue(
            ':tour_id',
            $tourId,
            PDO::PARAM_INT
        );

        $deleteStmt->execute();

        if (!empty($locations)) {
            $insertStmt = $this->db->prepare("
                INSERT INTO tour_locations (
                    tour_id,
                    location_id,
                    sort_order,
                    note
                )
                VALUES (
                    :tour_id,
                    :location_id,
                    :sort_order,
                    :note
                )
            ");

            foreach ($locations as $location) {
                $insertStmt->bindValue(
                    ':tour_id',
                    $tourId,
                    PDO::PARAM_INT
                );

                $insertStmt->bindValue(
                    ':location_id',
                    $location['location_id'],
                    PDO::PARAM_INT
                );

                $insertStmt->bindValue(
                    ':sort_order',
                    $location['sort_order'],
                    PDO::PARAM_INT
                );

                if ($location['note'] === null) {
                    $insertStmt->bindValue(
                        ':note',
                        null,
                        PDO::PARAM_NULL
                    );
                } else {
                    $insertStmt->bindValue(
                        ':note',
                        $location['note'],
                        PDO::PARAM_STR
                    );
                }

                $insertStmt->execute();
            }
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

    public function getTourImagesAdmin(
    int $tourId
): array {
    $sql = "
        SELECT
            image_id,
            tour_id,
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

    public function findTourImage(
    int $tourId,
    int $imageId
): ?array {
    $sql = "
        SELECT
            image_id,
            tour_id,
            image_url,
            alt_text,
            is_thumbnail,
            sort_order
        FROM tour_images
        WHERE image_id = :image_id
          AND tour_id = :tour_id
        LIMIT 1
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->bindValue(
        ':image_id',
        $imageId,
        PDO::PARAM_INT
    );

    $stmt->bindValue(
        ':tour_id',
        $tourId,
        PDO::PARAM_INT
    );

    $stmt->execute();

    $image = $stmt->fetch();

    return $image ?: null;
    }

    public function hasTourThumbnail(
    int $tourId
): bool {
    $sql = "
        SELECT 1
        FROM tour_images
        WHERE tour_id = :tour_id
          AND is_thumbnail = 1
        LIMIT 1
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->bindValue(
        ':tour_id',
        $tourId,
        PDO::PARAM_INT
    );

    $stmt->execute();

    return $stmt->fetchColumn() !== false;
    }

    public function getNextImageSortOrder(
    int $tourId
): int {
    $sql = "
        SELECT
            COALESCE(MAX(sort_order), 0)
        FROM tour_images
        WHERE tour_id = :tour_id
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->bindValue(
        ':tour_id',
        $tourId,
        PDO::PARAM_INT
    );

    $stmt->execute();

    return (int) $stmt->fetchColumn() + 1;
    }

    public function addTourImages(
    int $tourId,
    array $images
): bool {
    if (empty($images)) {
        return true;
    }

    try {
        $this->db->beginTransaction();

        $sql = "
            INSERT INTO tour_images (
                tour_id,
                image_url,
                alt_text,
                is_thumbnail,
                sort_order
            )
            VALUES (
                :tour_id,
                :image_url,
                :alt_text,
                :is_thumbnail,
                :sort_order
            )
        ";

        $stmt = $this->db->prepare($sql);

        foreach ($images as $image) {
            $stmt->bindValue(
                ':tour_id',
                $tourId,
                PDO::PARAM_INT
            );

            $stmt->bindValue(
                ':image_url',
                $image['image_url'],
                PDO::PARAM_STR
            );

            if ($image['alt_text'] === null) {
                $stmt->bindValue(
                    ':alt_text',
                    null,
                    PDO::PARAM_NULL
                );
            } else {
                $stmt->bindValue(
                    ':alt_text',
                    $image['alt_text'],
                    PDO::PARAM_STR
                );
            }

            $stmt->bindValue(
                ':is_thumbnail',
                $image['is_thumbnail'],
                PDO::PARAM_INT
            );

            $stmt->bindValue(
                ':sort_order',
                $image['sort_order'],
                PDO::PARAM_INT
            );

            $stmt->execute();
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

    public function updateTourImages(
    int $tourId,
    array $images,
    ?int $thumbnailId
): bool {
    try {
        $this->db->beginTransaction();

        $resetThumbnail = $this->db->prepare("
            UPDATE tour_images
            SET is_thumbnail = 0
            WHERE tour_id = :tour_id
        ");

        $resetThumbnail->bindValue(
            ':tour_id',
            $tourId,
            PDO::PARAM_INT
        );

        $resetThumbnail->execute();

        $updateStmt = $this->db->prepare("
            UPDATE tour_images
            SET
                alt_text = :alt_text,
                sort_order = :sort_order
            WHERE image_id = :image_id
              AND tour_id = :tour_id
        ");

        foreach ($images as $image) {
            if ($image['alt_text'] === null) {
                $updateStmt->bindValue(
                    ':alt_text',
                    null,
                    PDO::PARAM_NULL
                );
            } else {
                $updateStmt->bindValue(
                    ':alt_text',
                    $image['alt_text'],
                    PDO::PARAM_STR
                );
            }

            $updateStmt->bindValue(
                ':sort_order',
                $image['sort_order'],
                PDO::PARAM_INT
            );

            $updateStmt->bindValue(
                ':image_id',
                $image['image_id'],
                PDO::PARAM_INT
            );

            $updateStmt->bindValue(
                ':tour_id',
                $tourId,
                PDO::PARAM_INT
            );

            $updateStmt->execute();
        }

        if ($thumbnailId !== null) {
            $thumbnailStmt = $this->db->prepare("
                UPDATE tour_images
                SET is_thumbnail = 1
                WHERE image_id = :image_id
                  AND tour_id = :tour_id
            ");

            $thumbnailStmt->bindValue(
                ':image_id',
                $thumbnailId,
                PDO::PARAM_INT
            );

            $thumbnailStmt->bindValue(
                ':tour_id',
                $tourId,
                PDO::PARAM_INT
            );

            $thumbnailStmt->execute();

            if ($thumbnailStmt->rowCount() !== 1) {
                throw new RuntimeException(
                    'Ảnh đại diện không hợp lệ.'
                );
            }
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

    public function deleteTourImage(
    int $tourId,
    int $imageId
): bool {
    try {
        $this->db->beginTransaction();

        $image = $this->findTourImage(
            $tourId,
            $imageId
        );

        if ($image === null) {
            throw new RuntimeException(
                'Không tìm thấy ảnh.'
            );
        }

        $wasThumbnail =
            (int) $image['is_thumbnail'] === 1;

        $stmt = $this->db->prepare("
            DELETE FROM tour_images
            WHERE image_id = :image_id
              AND tour_id = :tour_id
        ");

        $stmt->bindValue(
            ':image_id',
            $imageId,
            PDO::PARAM_INT
        );

        $stmt->bindValue(
            ':tour_id',
            $tourId,
            PDO::PARAM_INT
        );

        $stmt->execute();

        if ($stmt->rowCount() !== 1) {
            throw new RuntimeException(
                'Không thể xóa ảnh.'
            );
        }

        if ($wasThumbnail) {
            $nextStmt = $this->db->prepare("
                SELECT image_id
                FROM tour_images
                WHERE tour_id = :tour_id
                ORDER BY
                    sort_order ASC,
                    image_id ASC
                LIMIT 1
            ");

            $nextStmt->bindValue(
                ':tour_id',
                $tourId,
                PDO::PARAM_INT
            );

            $nextStmt->execute();

            $nextImageId =
                $nextStmt->fetchColumn();

            if ($nextImageId !== false) {
                $thumbnailStmt =
                    $this->db->prepare("
                        UPDATE tour_images
                        SET is_thumbnail = 1
                        WHERE image_id = :image_id
                          AND tour_id = :tour_id
                    ");

                $thumbnailStmt->bindValue(
                    ':image_id',
                    (int) $nextImageId,
                    PDO::PARAM_INT
                );

                $thumbnailStmt->bindValue(
                    ':tour_id',
                    $tourId,
                    PDO::PARAM_INT
                );

                $thumbnailStmt->execute();
            }
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

    public function getTourSchedulesAdmin(
    int $tourId
): array {
    $sql = "
        SELECT
            schedule_id,
            tour_id,
            day_number,
            title,
            description
        FROM tour_schedules
        WHERE tour_id = :tour_id
        ORDER BY
            day_number ASC,
            schedule_id ASC
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
}