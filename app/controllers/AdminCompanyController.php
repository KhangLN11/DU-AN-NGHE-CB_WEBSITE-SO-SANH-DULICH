<?php

require_once __DIR__ . '/AdminBaseController.php';
require_once __DIR__ . '/../models/AdminCompany.php';

class AdminCompanyController extends AdminBaseController
{
    public function index(): void
    {
        $this->requireAdmin();

        $companyModel =
            new AdminCompany();

        $filters = [
            'keyword' => trim(
                $_GET['keyword']
                ?? ''
            ),

            'status' => trim(
                $_GET['status']
                ?? ''
            )
        ];

        $allowedStatuses = [
            '',
            'active',
            'inactive'
        ];

        if (
            !in_array(
                $filters['status'],
                $allowedStatuses,
                true
            )
        ) {
            $filters['status'] = '';
        }

        $perPage = 10;

        $page =
            $this->positiveInt(
                $_GET['page']
                ?? 1
            );

        if ($page < 1) {
            $page = 1;
        }

        $totalCompanies =
            $companyModel->countCompanies(
                $filters
            );

        $totalPages = max(
            1,
            (int) ceil(
                $totalCompanies
                / $perPage
            )
        );

        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $offset =
            ($page - 1)
            * $perPage;

        $companies =
            $companyModel
                ->getPaginatedCompanies(
                    $filters,
                    $perPage,
                    $offset
                );

        $successMessage =
            $_SESSION[
                'admin_company_success'
            ]
            ?? null;

        $errorMessage =
            $_SESSION[
                'admin_company_error'
            ]
            ?? null;

        unset(
            $_SESSION[
                'admin_company_success'
            ],
            $_SESSION[
                'admin_company_error'
            ]
        );

        $this->view(
            'admin/companies/index',
            [
                'title' =>
                    'Quản lý công ty - TourCompare Admin',

                'styles' => [
                    'css/admin.css',
                    'css/admin-companies.css'
                ],

                'companies' =>
                    $companies,

                'filters' =>
                    $filters,

                'currentPage' =>
                    $page,

                'totalPages' =>
                    $totalPages,

                'totalCompanies' =>
                    $totalCompanies,

                'successMessage' =>
                    $successMessage,

                'errorMessage' =>
                    $errorMessage
            ],
            'admin'
        );
    }

    public function create(): void
    {
        $this->requireAdmin();

        $this->view(
            'admin/companies/create',
            [
                'title' =>
                    'Thêm công ty - TourCompare Admin',

                'styles' => [
                    'css/admin.css',
                    'css/admin-company-form.css'
                ],

                'errors' =>
                    [],

                'old' =>
                    $this->emptyCompanyForm()
            ],
            'admin'
        );
    }

    public function store(): void
    {
        $this->requireAdmin();

        $companyModel =
            new AdminCompany();

        $data =
            $this->collectCompanyFormData();

        $errors =
            $this->validateCompanyData(
                $companyModel,
                $data
            );

        if ($data['slug'] === '') {
            $data['slug'] =
                $this->makeSlug(
                    $data['company_name']
                );
        } else {
            $data['slug'] =
                $this->makeSlug(
                    $data['slug']
                );
        }

        if ($data['slug'] === '') {
            $errors['slug'] =
                'Không thể tạo slug hợp lệ.';
        } elseif (
            mb_strlen($data['slug']) > 180
        ) {
            $errors['slug'] =
                'Slug không được vượt quá 180 ký tự.';
        } elseif (
            $companyModel->slugExists(
                $data['slug']
            )
        ) {
            $errors['slug'] =
                'Slug này đã tồn tại.';
        }

        if (
            $companyModel->companyNameExists(
                $data['company_name']
            )
        ) {
            $errors['company_name'] =
                'Tên công ty này đã tồn tại.';
        }

        if (!empty($errors)) {
            $this->view(
                'admin/companies/create',
                [
                    'title' =>
                        'Thêm công ty - TourCompare Admin',

                    'styles' => [
                        'css/admin.css',
                        'css/admin-company-form.css'
                    ],

                    'errors' =>
                        $errors,

                    'old' =>
                        $data
                ],
                'admin'
            );

            return;
        }

        $uploadedLogo = null;

        try {
            $uploadedLogo =
                $this->uploadLogo();

            $data['logo'] =
                $uploadedLogo;

            $companyId =
                $companyModel->createCompany(
                    $this->prepareCompanyData(
                        $data
                    )
                );

            $_SESSION[
                'admin_company_success'
            ] =
                'Đã tạo công ty #'
                . $companyId
                . ' thành công.';
        } catch (Throwable $error) {
            if ($uploadedLogo !== null) {
                $this->deleteLogoFile(
                    $uploadedLogo
                );
            }

            $_SESSION[
                'admin_company_error'
            ] =
                $error->getMessage();
        }

        $this->redirect(
            'admin/companies'
        );
    }

    public function edit(
        string $id
    ): void {
        $this->requireAdmin();

        $companyId =
            $this->positiveInt($id);

        if ($companyId === 0) {
            $this->notFound();
        }

        $companyModel =
            new AdminCompany();

        $company =
            $companyModel->findById(
                $companyId
            );

        if ($company === null) {
            $this->notFound();
        }

        $this->view(
            'admin/companies/edit',
            [
                'title' =>
                    'Chỉnh sửa công ty - TourCompare Admin',

                'styles' => [
                    'css/admin.css',
                    'css/admin-company-form.css'
                ],

                'companyId' =>
                    $companyId,

                'errors' =>
                    [],

                'old' =>
                    $company
            ],
            'admin'
        );
    }

    public function update(
        string $id
    ): void {
        $this->requireAdmin();

        $companyId =
            $this->positiveInt($id);

        if ($companyId === 0) {
            $this->notFound();
        }

        $companyModel =
            new AdminCompany();

        $existingCompany =
            $companyModel->findById(
                $companyId
            );

        if ($existingCompany === null) {
            $this->notFound();
        }

        $data =
            $this->collectCompanyFormData();

        $errors =
            $this->validateCompanyData(
                $companyModel,
                $data
            );

        if ($data['slug'] === '') {
            $data['slug'] =
                $this->makeSlug(
                    $data['company_name']
                );
        } else {
            $data['slug'] =
                $this->makeSlug(
                    $data['slug']
                );
        }

        if ($data['slug'] === '') {
            $errors['slug'] =
                'Không thể tạo slug hợp lệ.';
        } elseif (
            mb_strlen($data['slug']) > 180
        ) {
            $errors['slug'] =
                'Slug không được vượt quá 180 ký tự.';
        } elseif (
            $companyModel
                ->slugExistsExcept(
                    $data['slug'],
                    $companyId
                )
        ) {
            $errors['slug'] =
                'Slug này đã được công ty khác sử dụng.';
        }

        if (
            $companyModel
                ->companyNameExistsExcept(
                    $data['company_name'],
                    $companyId
                )
        ) {
            $errors['company_name'] =
                'Tên công ty này đã được công ty khác sử dụng.';
        }

        $data['logo'] =
            $existingCompany['logo'];

        if (!empty($errors)) {
            $this->view(
                'admin/companies/edit',
                [
                    'title' =>
                        'Chỉnh sửa công ty - TourCompare Admin',

                    'styles' => [
                        'css/admin.css',
                        'css/admin-company-form.css'
                    ],

                    'companyId' =>
                        $companyId,

                    'errors' =>
                        $errors,

                    'old' =>
                        array_merge(
                            $existingCompany,
                            $data
                        )
                ],
                'admin'
            );

            return;
        }

        $newLogo = null;
        $oldLogo =
            $existingCompany['logo'];

        try {
            if (
                isset($_POST['remove_logo'])
            ) {
                $data['logo'] = null;
            }

            $newLogo =
                $this->uploadLogo();

            if ($newLogo !== null) {
                $data['logo'] =
                    $newLogo;
            }

            $companyModel->updateCompany(
                $companyId,
                $this->prepareCompanyData(
                    $data
                )
            );

            if (
                $oldLogo !== null
                && $oldLogo !== ''
                && $oldLogo !== $data['logo']
            ) {
                $this->deleteLogoFile(
                    $oldLogo
                );
            }

            $_SESSION[
                'admin_company_success'
            ] =
                'Đã cập nhật công ty #'
                . $companyId
                . ' thành công.';
        } catch (Throwable $error) {
            if ($newLogo !== null) {
                $this->deleteLogoFile(
                    $newLogo
                );
            }

            $_SESSION[
                'admin_company_error'
            ] =
                $error->getMessage();
        }

        $this->redirect(
            'admin/companies'
        );
    }

    public function disable(
        string $id
    ): void {
        $this->requireAdmin();

        $companyId =
            $this->positiveInt($id);

        if ($companyId === 0) {
            $this->notFound();
        }

        $companyModel =
            new AdminCompany();

        $company =
            $companyModel->findById(
                $companyId
            );

        if ($company === null) {
            $this->notFound();
        }

        if (
            $company['status']
            === 'inactive'
        ) {
            $_SESSION[
                'admin_company_error'
            ] =
                'Công ty này đã ở trạng thái tạm ẩn.';

            $this->redirect(
                'admin/companies'
            );
        }

        try {
            $companyModel->disableCompany(
                $companyId
            );

            $_SESSION[
                'admin_company_success'
            ] =
                'Đã vô hiệu hóa công ty "'
                . $company['company_name']
                . '".';
        } catch (Throwable $error) {
            $_SESSION[
                'admin_company_error'
            ] =
                'Không thể vô hiệu hóa công ty.';
        }

        $this->redirect(
            'admin/companies'
        );
    }

    public function delete(
        string $id
    ): void {
        $this->requireAdmin();

        $companyId =
            $this->positiveInt($id);

        if ($companyId === 0) {
            $this->notFound();
        }

        $companyModel =
            new AdminCompany();

        $company =
            $companyModel->findById(
                $companyId
            );

        if ($company === null) {
            $this->notFound();
        }

        $tourCount =
            $companyModel
                ->countToursByCompany(
                    $companyId
                );

        if ($tourCount > 0) {
            $_SESSION[
                'admin_company_error'
            ] =
                'Không thể xóa công ty "'
                . $company['company_name']
                . '" vì đang có '
                . $tourCount
                . ' Tour sử dụng.';

            $this->redirect(
                'admin/companies'
            );
        }

        try {
            $companyModel->deleteCompany(
                $companyId
            );

            if (
                !empty(
                    $company['logo']
                )
            ) {
                $this->deleteLogoFile(
                    $company['logo']
                );
            }

            $_SESSION[
                'admin_company_success'
            ] =
                'Đã xóa công ty #'
                . $companyId
                . ' thành công.';
        } catch (Throwable $error) {
            $_SESSION[
                'admin_company_error'
            ] =
                'Không thể xóa công ty.';
        }

        $this->redirect(
            'admin/companies'
        );
    }

    private function collectCompanyFormData(): array
    {
        return [
            'company_name' => trim(
                $_POST['company_name']
                ?? ''
            ),

            'slug' => trim(
                $_POST['slug']
                ?? ''
            ),

            'logo' => null,

            'description' => trim(
                $_POST['description']
                ?? ''
            ),

            'address' => trim(
                $_POST['address']
                ?? ''
            ),

            'phone' => trim(
                $_POST['phone']
                ?? ''
            ),

            'email' => trim(
                $_POST['email']
                ?? ''
            ),

            'website' => trim(
                $_POST['website']
                ?? ''
            ),

            'status' => trim(
                $_POST['status']
                ?? ''
            )
        ];
    }

    private function validateCompanyData(
        AdminCompany $companyModel,
        array $data
    ): array {
        $errors = [];

        if ($data['company_name'] === '') {
            $errors['company_name'] =
                'Vui lòng nhập tên công ty.';
        } elseif (
            mb_strlen(
                $data['company_name']
            ) < 2
        ) {
            $errors['company_name'] =
                'Tên công ty phải có ít nhất 2 ký tự.';
        } elseif (
            mb_strlen(
                $data['company_name']
            ) > 150
        ) {
            $errors['company_name'] =
                'Tên công ty không được vượt quá 150 ký tự.';
        }

        if (
            mb_strlen(
                $data['description']
            ) > 10000
        ) {
            $errors['description'] =
                'Mô tả quá dài.';
        }

        if (
            mb_strlen(
                $data['address']
            ) > 255
        ) {
            $errors['address'] =
                'Địa chỉ không được vượt quá 255 ký tự.';
        }

        if (
            mb_strlen(
                $data['phone']
            ) > 20
        ) {
            $errors['phone'] =
                'Số điện thoại không được vượt quá 20 ký tự.';
        }

        if (
            mb_strlen(
                $data['email']
            ) > 150
        ) {
            $errors['email'] =
                'Email không được vượt quá 150 ký tự.';
        } elseif (
            $data['email'] !== ''
            && !filter_var(
                $data['email'],
                FILTER_VALIDATE_EMAIL
            )
        ) {
            $errors['email'] =
                'Email không hợp lệ.';
        }

        if (
            mb_strlen(
                $data['website']
            ) > 255
        ) {
            $errors['website'] =
                'Website không được vượt quá 255 ký tự.';
        } elseif (
            $data['website'] !== ''
            && !filter_var(
                $data['website'],
                FILTER_VALIDATE_URL
            )
        ) {
            $errors['website'] =
                'Website không hợp lệ.';
        }

        if (
            !in_array(
                $data['status'],
                [
                    'active',
                    'inactive'
                ],
                true
            )
        ) {
            $errors['status'] =
                'Trạng thái không hợp lệ.';
        }

        return $errors;
    }

    private function prepareCompanyData(
        array $data
    ): array {
        return [
            'company_name' =>
                $data['company_name'],

            'slug' =>
                $data['slug'],

            'logo' =>
                !empty($data['logo'])
                    ? $data['logo']
                    : null,

            'description' =>
                $data['description'] !== ''
                    ? $data['description']
                    : null,

            'address' =>
                $data['address'] !== ''
                    ? $data['address']
                    : null,

            'phone' =>
                $data['phone'] !== ''
                    ? $data['phone']
                    : null,

            'email' =>
                $data['email'] !== ''
                    ? $data['email']
                    : null,

            'website' =>
                $data['website'] !== ''
                    ? $data['website']
                    : null,

            'status' =>
                $data['status']
        ];
    }

    private function emptyCompanyForm(): array
    {
        return [
            'company_name' => '',
            'slug' => '',
            'logo' => null,
            'description' => '',
            'address' => '',
            'phone' => '',
            'email' => '',
            'website' => '',
            'status' => 'active'
        ];
    }

    private function uploadLogo(): ?string
    {
        if (
            !isset($_FILES['logo'])
            || (
                $_FILES['logo']['error']
                ?? UPLOAD_ERR_NO_FILE
            ) === UPLOAD_ERR_NO_FILE
        ) {
            return null;
        }

        $file =
            $_FILES['logo'];

        if (
            $file['error']
            !== UPLOAD_ERR_OK
        ) {
            throw new RuntimeException(
                'Logo tải lên không thành công.'
            );
        }

        if (
            (int) $file['size']
            > 3 * 1024 * 1024
        ) {
            throw new RuntimeException(
                'Logo không được vượt quá 3MB.'
            );
        }

        $imageInfo =
            getimagesize(
                $file['tmp_name']
            );

        if ($imageInfo === false) {
            throw new RuntimeException(
                'Logo không phải hình ảnh hợp lệ.'
            );
        }

        $allowedTypes = [
            IMAGETYPE_JPEG => 'jpg',
            IMAGETYPE_PNG => 'png',
            IMAGETYPE_WEBP => 'webp'
        ];

        $imageType =
            $imageInfo[2];

        if (
            !isset(
                $allowedTypes[$imageType]
            )
        ) {
            throw new RuntimeException(
                'Logo chỉ chấp nhận JPG, PNG hoặc WEBP.'
            );
        }

        $uploadDirectory =
            __DIR__
            . '/../../public/uploads/companies';

        if (
            !is_dir($uploadDirectory)
            && !mkdir(
                $uploadDirectory,
                0755,
                true
            )
            && !is_dir($uploadDirectory)
        ) {
            throw new RuntimeException(
                'Không thể tạo thư mục lưu logo.'
            );
        }

        $extension =
            $allowedTypes[$imageType];

        $fileName =
            'company_'
            . bin2hex(
                random_bytes(12)
            )
            . '.'
            . $extension;

        $destination =
            $uploadDirectory
            . '/'
            . $fileName;

        if (
            !move_uploaded_file(
                $file['tmp_name'],
                $destination
            )
        ) {
            throw new RuntimeException(
                'Không thể lưu logo.'
            );
        }

        return
            '/uploads/companies/'
            . $fileName;
    }

    private function deleteLogoFile(
        string $logo
    ): void {
        $normalized =
            str_replace(
                '\\',
                '/',
                $logo
            );

        $prefix =
            '/uploads/companies/';

        if (
            !str_starts_with(
                $normalized,
                $prefix
            )
        ) {
            return;
        }

        $filePath =
            __DIR__
            . '/../../public/uploads/companies/'
            . basename($normalized);

        if (is_file($filePath)) {
            unlink($filePath);
        }
    }

    private function positiveInt(
        mixed $value
    ): int {
        $number = filter_var(
            $value,
            FILTER_VALIDATE_INT,
            [
                'options' => [
                    'min_range' => 1
                ]
            ]
        );

        return $number === false
            ? 0
            : $number;
    }

    private function makeSlug(
        string $value
    ): string {
        $value = trim(
            mb_strtolower($value)
        );

        $replacements = [
            'à' => 'a',
            'á' => 'a',
            'ạ' => 'a',
            'ả' => 'a',
            'ã' => 'a',
            'â' => 'a',
            'ầ' => 'a',
            'ấ' => 'a',
            'ậ' => 'a',
            'ẩ' => 'a',
            'ẫ' => 'a',
            'ă' => 'a',
            'ằ' => 'a',
            'ắ' => 'a',
            'ặ' => 'a',
            'ẳ' => 'a',
            'ẵ' => 'a',
            'è' => 'e',
            'é' => 'e',
            'ẹ' => 'e',
            'ẻ' => 'e',
            'ẽ' => 'e',
            'ê' => 'e',
            'ề' => 'e',
            'ế' => 'e',
            'ệ' => 'e',
            'ể' => 'e',
            'ễ' => 'e',
            'ì' => 'i',
            'í' => 'i',
            'ị' => 'i',
            'ỉ' => 'i',
            'ĩ' => 'i',
            'ò' => 'o',
            'ó' => 'o',
            'ọ' => 'o',
            'ỏ' => 'o',
            'õ' => 'o',
            'ô' => 'o',
            'ồ' => 'o',
            'ố' => 'o',
            'ộ' => 'o',
            'ổ' => 'o',
            'ỗ' => 'o',
            'ơ' => 'o',
            'ờ' => 'o',
            'ớ' => 'o',
            'ợ' => 'o',
            'ở' => 'o',
            'ỡ' => 'o',
            'ù' => 'u',
            'ú' => 'u',
            'ụ' => 'u',
            'ủ' => 'u',
            'ũ' => 'u',
            'ư' => 'u',
            'ừ' => 'u',
            'ứ' => 'u',
            'ự' => 'u',
            'ử' => 'u',
            'ữ' => 'u',
            'ỳ' => 'y',
            'ý' => 'y',
            'ỵ' => 'y',
            'ỷ' => 'y',
            'ỹ' => 'y',
            'đ' => 'd'
        ];

        $value = strtr(
            $value,
            $replacements
        );

        $value = preg_replace(
            '/[^a-z0-9]+/',
            '-',
            $value
        );

        return trim(
            $value,
            '-'
        );
    }

    private function notFound(): never
    {
        http_response_code(404);

        echo '<h1>404 - Không tìm thấy công ty.</h1>';

        exit;
    }
}