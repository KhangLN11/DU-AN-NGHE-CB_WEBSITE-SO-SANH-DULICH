<?php

class AdminCompany extends Model
{
    public function getPaginatedCompanies(
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
                c.company_id,
                c.company_name,
                c.slug,
                c.logo,
                c.description,
                c.address,
                c.phone,
                c.email,
                c.website,
                c.status,
                c.created_at,
                c.updated_at,
                COUNT(t.tour_id) AS tour_count
            FROM companies c
            LEFT JOIN tours t
                ON c.company_id = t.company_id
            WHERE {$whereSql}
            GROUP BY
                c.company_id,
                c.company_name,
                c.slug,
                c.logo,
                c.description,
                c.address,
                c.phone,
                c.email,
                c.website,
                c.status,
                c.created_at,
                c.updated_at
            ORDER BY
                c.company_id DESC
            LIMIT :limit
            OFFSET :offset
        ";

        $stmt = $this->db->prepare(
            $sql
        );

        foreach ($params as $key => $value) {
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

    public function countCompanies(
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
            FROM companies c
            WHERE {$whereSql}
        ";

        $stmt = $this->db->prepare(
            $sql
        );

        foreach ($params as $key => $value) {
            $stmt->bindValue(
                $key,
                $value
            );
        }

        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function findById(
        int $companyId
    ): ?array {
        $sql = "
            SELECT
                company_id,
                company_name,
                slug,
                logo,
                description,
                address,
                phone,
                email,
                website,
                status,
                created_at,
                updated_at
            FROM companies
            WHERE company_id = :company_id
            LIMIT 1
        ";

        $stmt = $this->db->prepare(
            $sql
        );

        $stmt->bindValue(
            ':company_id',
            $companyId,
            PDO::PARAM_INT
        );

        $stmt->execute();

        $company = $stmt->fetch();

        return $company ?: null;
    }

    public function companyNameExists(
        string $companyName
    ): bool {
        $sql = "
            SELECT 1
            FROM companies
            WHERE company_name = :company_name
            LIMIT 1
        ";

        $stmt = $this->db->prepare(
            $sql
        );

        $stmt->bindValue(
            ':company_name',
            $companyName,
            PDO::PARAM_STR
        );

        $stmt->execute();

        return $stmt->fetchColumn() !== false;
    }

    public function companyNameExistsExcept(
        string $companyName,
        int $companyId
    ): bool {
        $sql = "
            SELECT 1
            FROM companies
            WHERE company_name = :company_name
              AND company_id <> :company_id
            LIMIT 1
        ";

        $stmt = $this->db->prepare(
            $sql
        );

        $stmt->bindValue(
            ':company_name',
            $companyName,
            PDO::PARAM_STR
        );

        $stmt->bindValue(
            ':company_id',
            $companyId,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return $stmt->fetchColumn() !== false;
    }

    public function slugExists(
        string $slug
    ): bool {
        $sql = "
            SELECT 1
            FROM companies
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
        int $companyId
    ): bool {
        $sql = "
            SELECT 1
            FROM companies
            WHERE slug = :slug
              AND company_id <> :company_id
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
            ':company_id',
            $companyId,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return $stmt->fetchColumn() !== false;
    }

    public function createCompany(
        array $data
    ): int {
        $sql = "
            INSERT INTO companies (
                company_name,
                slug,
                logo,
                description,
                address,
                phone,
                email,
                website,
                status
            )
            VALUES (
                :company_name,
                :slug,
                :logo,
                :description,
                :address,
                :phone,
                :email,
                :website,
                :status
            )
        ";

        $stmt = $this->db->prepare(
            $sql
        );

        $this->bindCompanyData(
            $stmt,
            $data
        );

        $stmt->execute();

        return (int) $this->db->lastInsertId();
    }

    public function updateCompany(
        int $companyId,
        array $data
    ): bool {
        $sql = "
            UPDATE companies
            SET
                company_name = :company_name,
                slug = :slug,
                logo = :logo,
                description = :description,
                address = :address,
                phone = :phone,
                email = :email,
                website = :website,
                status = :status
            WHERE company_id = :company_id
        ";

        $stmt = $this->db->prepare(
            $sql
        );

        $this->bindCompanyData(
            $stmt,
            $data
        );

        $stmt->bindValue(
            ':company_id',
            $companyId,
            PDO::PARAM_INT
        );

        return $stmt->execute();
    }

    public function countToursByCompany(
        int $companyId
    ): int {
        $sql = "
            SELECT COUNT(*)
            FROM tours
            WHERE company_id = :company_id
        ";

        $stmt = $this->db->prepare(
            $sql
        );

        $stmt->bindValue(
            ':company_id',
            $companyId,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function disableCompany(
        int $companyId
    ): bool {
        $sql = "
            UPDATE companies
            SET status = 'inactive'
            WHERE company_id = :company_id
        ";

        $stmt = $this->db->prepare(
            $sql
        );

        $stmt->bindValue(
            ':company_id',
            $companyId,
            PDO::PARAM_INT
        );

        return $stmt->execute();
    }

    public function deleteCompany(
        int $companyId
    ): bool {
        try {
            $this->db->beginTransaction();

            $countStmt = $this->db->prepare("
                SELECT COUNT(*)
                FROM tours
                WHERE company_id = :company_id
            ");

            $countStmt->bindValue(
                ':company_id',
                $companyId,
                PDO::PARAM_INT
            );

            $countStmt->execute();

            if (
                (int) $countStmt->fetchColumn()
                > 0
            ) {
                throw new RuntimeException(
                    'Công ty đang được Tour sử dụng.'
                );
            }

            $deleteStmt = $this->db->prepare("
                DELETE FROM companies
                WHERE company_id = :company_id
            ");

            $deleteStmt->bindValue(
                ':company_id',
                $companyId,
                PDO::PARAM_INT
            );

            $deleteStmt->execute();

            if ($deleteStmt->rowCount() !== 1) {
                throw new RuntimeException(
                    'Không tìm thấy công ty để xóa.'
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

    private function bindCompanyData(
        PDOStatement $stmt,
        array $data
    ): void {
        $stmt->bindValue(
            ':company_name',
            $data['company_name'],
            PDO::PARAM_STR
        );

        $stmt->bindValue(
            ':slug',
            $data['slug'],
            PDO::PARAM_STR
        );

        $this->bindNullableString(
            $stmt,
            ':logo',
            $data['logo']
        );

        $this->bindNullableString(
            $stmt,
            ':description',
            $data['description']
        );

        $this->bindNullableString(
            $stmt,
            ':address',
            $data['address']
        );

        $this->bindNullableString(
            $stmt,
            ':phone',
            $data['phone']
        );

        $this->bindNullableString(
            $stmt,
            ':email',
            $data['email']
        );

        $this->bindNullableString(
            $stmt,
            ':website',
            $data['website']
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
                    c.company_name LIKE :keyword_name
                    OR c.slug LIKE :keyword_slug
                    OR c.email LIKE :keyword_email
                    OR c.phone LIKE :keyword_phone
                    OR c.address LIKE :keyword_address
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

            $params[':keyword_email'] =
                $keyword;

            $params[':keyword_phone'] =
                $keyword;

            $params[':keyword_address'] =
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
}