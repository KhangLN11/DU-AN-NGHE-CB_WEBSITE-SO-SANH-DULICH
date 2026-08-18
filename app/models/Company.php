<?php

class Company extends Model
{
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
}