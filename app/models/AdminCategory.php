<?php

class AdminCategory extends Model
{
    public function getPaginatedCategories(
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
                c.category_id,
                c.category_name,
                c.slug,
                c.description,
                c.status,
                c.created_at,
                c.updated_at,

                COUNT(t.tour_id) AS tour_count

            FROM categories c

            LEFT JOIN tours t
                ON c.category_id = t.category_id

            WHERE {$whereSql}

            GROUP BY
                c.category_id,
                c.category_name,
                c.slug,
                c.description,
                c.status,
                c.created_at,
                c.updated_at

            ORDER BY
                c.category_id DESC

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

    public function countCategories(
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
            FROM categories c
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
                    c.category_name LIKE :keyword_name
                    OR c.slug LIKE :keyword_slug
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
        }

        if (
            ($filters['status'] ?? '')
            !== ''
        ) {
            $where[] =
                'c.status = :status';

            $params[':status'] =
                $filters['status'];
        }
    }

    public function categoryNameExists(
    string $categoryName
): bool {
    $sql = "
        SELECT 1
        FROM categories
        WHERE category_name = :category_name
        LIMIT 1
    ";

    $stmt = $this->db->prepare(
        $sql
    );

    $stmt->bindValue(
        ':category_name',
        $categoryName,
        PDO::PARAM_STR
    );

    $stmt->execute();

    return $stmt->fetchColumn() !== false;
}

public function slugExists(
    string $slug
): bool {
    $sql = "
        SELECT 1
        FROM categories
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

public function createCategory(
    array $data
): int {
    $sql = "
        INSERT INTO categories (
            category_name,
            slug,
            description,
            status
        )
        VALUES (
            :category_name,
            :slug,
            :description,
            :status
        )
    ";

    $stmt = $this->db->prepare(
        $sql
    );

    $stmt->bindValue(
        ':category_name',
        $data['category_name'],
        PDO::PARAM_STR
    );

    $stmt->bindValue(
        ':slug',
        $data['slug'],
        PDO::PARAM_STR
    );

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
        ':status',
        $data['status'],
        PDO::PARAM_STR
    );

    $stmt->execute();

    return (int) $this->db->lastInsertId();
    }

    public function findById(
    int $categoryId
): ?array {
    $sql = "
        SELECT
            category_id,
            category_name,
            slug,
            description,
            status,
            created_at,
            updated_at
        FROM categories
        WHERE category_id = :category_id
        LIMIT 1
    ";

    $stmt = $this->db->prepare(
        $sql
    );

    $stmt->bindValue(
        ':category_id',
        $categoryId,
        PDO::PARAM_INT
    );

    $stmt->execute();

    $category = $stmt->fetch();

    return $category ?: null;
    }

    public function categoryNameExistsExcept(
    string $categoryName,
    int $categoryId
): bool {
    $sql = "
        SELECT 1
        FROM categories
        WHERE category_name = :category_name
          AND category_id <> :category_id
        LIMIT 1
    ";

    $stmt = $this->db->prepare(
        $sql
    );

    $stmt->bindValue(
        ':category_name',
        $categoryName,
        PDO::PARAM_STR
    );

    $stmt->bindValue(
        ':category_id',
        $categoryId,
        PDO::PARAM_INT
    );

    $stmt->execute();

    return $stmt->fetchColumn() !== false;
    }

    public function slugExistsExcept(
    string $slug,
    int $categoryId
): bool {
    $sql = "
        SELECT 1
        FROM categories
        WHERE slug = :slug
          AND category_id <> :category_id
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
        ':category_id',
        $categoryId,
        PDO::PARAM_INT
    );

    $stmt->execute();

    return $stmt->fetchColumn() !== false;
    }

    public function updateCategory(
    int $categoryId,
    array $data
): bool {
    $sql = "
        UPDATE categories
        SET
            category_name = :category_name,
            slug = :slug,
            description = :description,
            status = :status
        WHERE category_id = :category_id
    ";

    $stmt = $this->db->prepare(
        $sql
    );

    $stmt->bindValue(
        ':category_name',
        $data['category_name'],
        PDO::PARAM_STR
    );

    $stmt->bindValue(
        ':slug',
        $data['slug'],
        PDO::PARAM_STR
    );

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
        ':status',
        $data['status'],
        PDO::PARAM_STR
    );

    $stmt->bindValue(
        ':category_id',
        $categoryId,
        PDO::PARAM_INT
    );

    return $stmt->execute();
    }

    public function countToursByCategory(
    int $categoryId
): int {
    $sql = "
        SELECT COUNT(*)
        FROM tours
        WHERE category_id = :category_id
    ";

    $stmt = $this->db->prepare(
        $sql
    );

    $stmt->bindValue(
        ':category_id',
        $categoryId,
        PDO::PARAM_INT
    );

    $stmt->execute();

    return (int) $stmt->fetchColumn();
    }

    public function disableCategory(
    int $categoryId
): bool {
    $sql = "
        UPDATE categories
        SET status = 'inactive'
        WHERE category_id = :category_id
    ";

    $stmt = $this->db->prepare(
        $sql
    );

    $stmt->bindValue(
        ':category_id',
        $categoryId,
        PDO::PARAM_INT
    );

    return $stmt->execute();
    }

    public function deleteCategory(
    int $categoryId
): bool {
    try {
        $this->db->beginTransaction();

        $countStmt = $this->db->prepare("
            SELECT COUNT(*)
            FROM tours
            WHERE category_id = :category_id
        ");

        $countStmt->bindValue(
            ':category_id',
            $categoryId,
            PDO::PARAM_INT
        );

        $countStmt->execute();

        $tourCount =
            (int) $countStmt->fetchColumn();

        if ($tourCount > 0) {
            throw new RuntimeException(
                'Danh mục đang được Tour sử dụng.'
            );
        }

        $deleteStmt = $this->db->prepare("
            DELETE FROM categories
            WHERE category_id = :category_id
        ");

        $deleteStmt->bindValue(
            ':category_id',
            $categoryId,
            PDO::PARAM_INT
        );

        $deleteStmt->execute();

        if ($deleteStmt->rowCount() !== 1) {
            throw new RuntimeException(
                'Không tìm thấy danh mục để xóa.'
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

    

}