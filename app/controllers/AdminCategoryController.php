<?php

require_once __DIR__ . '/AdminBaseController.php';
require_once __DIR__ . '/../models/AdminCategory.php';

class AdminCategoryController extends AdminBaseController
{
    public function index(): void
    {
        $this->requireAdmin();

        $categoryModel =
            new AdminCategory();

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

        $totalCategories =
            $categoryModel->countCategories(
                $filters
            );

        $totalPages = max(
            1,
            (int) ceil(
                $totalCategories
                / $perPage
            )
        );

        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $offset =
            ($page - 1)
            * $perPage;

        $categories =
            $categoryModel
                ->getPaginatedCategories(
                    $filters,
                    $perPage,
                    $offset
                );

        $successMessage =
            $_SESSION[
                'admin_category_success'
            ]
            ?? null;

        $errorMessage =
            $_SESSION[
                'admin_category_error'
            ]
            ?? null;

        unset(
            $_SESSION[
                'admin_category_success'
            ],
            $_SESSION[
                'admin_category_error'
            ]
        );

        $this->view(
            'admin/categories/index',
            [
                'title' =>
                    'Quản lý danh mục - TourCompare Admin',

                'styles' => [
                    'css/admin.css',
                    'css/admin-categories.css'
                ],

                'categories' =>
                    $categories,

                'filters' =>
                    $filters,

                'currentPage' =>
                    $page,

                'totalPages' =>
                    $totalPages,

                'totalCategories' =>
                    $totalCategories,

                'successMessage' =>
                    $successMessage,

                'errorMessage' =>
                    $errorMessage
            ],
            'admin'
        );
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

    public function create(): void
{
    $this->requireAdmin();

    $this->view(
        'admin/categories/create',
        [
            'title' =>
                'Thêm danh mục - TourCompare Admin',

            'styles' => [
                'css/admin.css',
                'css/admin-category-form.css'
            ],

            'errors' => [],

            'old' => [
                'category_name' => '',
                'slug' => '',
                'description' => '',
                'status' => 'active'
            ]
        ],
        'admin'
    );
    }

    public function store(): void
{
    $this->requireAdmin();

    $categoryModel =
        new AdminCategory();

    $data = [
        'category_name' => trim(
            $_POST['category_name']
            ?? ''
        ),

        'slug' => trim(
            $_POST['slug']
            ?? ''
        ),

        'description' => trim(
            $_POST['description']
            ?? ''
        ),

        'status' => trim(
            $_POST['status']
            ?? ''
        )
    ];

    $errors = [];

    if ($data['category_name'] === '') {
        $errors['category_name'] =
            'Vui lòng nhập tên danh mục.';
    } elseif (
        mb_strlen(
            $data['category_name']
        ) < 2
    ) {
        $errors['category_name'] =
            'Tên danh mục phải có ít nhất 2 ký tự.';
    } elseif (
        mb_strlen(
            $data['category_name']
        ) > 100
    ) {
        $errors['category_name'] =
            'Tên danh mục không được vượt quá 100 ký tự.';
    } elseif (
        $categoryModel->categoryNameExists(
            $data['category_name']
        )
    ) {
        $errors['category_name'] =
            'Tên danh mục này đã tồn tại.';
    }

    if ($data['slug'] === '') {
        $data['slug'] =
            $this->makeSlug(
                $data['category_name']
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
        mb_strlen(
            $data['slug']
        ) > 150
    ) {
        $errors['slug'] =
            'Slug không được vượt quá 150 ký tự.';
    } elseif (
        $categoryModel->slugExists(
            $data['slug']
        )
    ) {
        $errors['slug'] =
            'Slug này đã tồn tại.';
    }

    if (
        mb_strlen(
            $data['description']
        ) > 5000
    ) {
        $errors['description'] =
            'Mô tả danh mục không được vượt quá 5000 ký tự.';
    }

    $allowedStatuses = [
        'active',
        'inactive'
    ];

    if (
        !in_array(
            $data['status'],
            $allowedStatuses,
            true
        )
    ) {
        $errors['status'] =
            'Trạng thái không hợp lệ.';
    }

    if (!empty($errors)) {
        $this->view(
            'admin/categories/create',
            [
                'title' =>
                    'Thêm danh mục - TourCompare Admin',

                'styles' => [
                    'css/admin.css',
                    'css/admin-category-form.css'
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

    $categoryId =
        $categoryModel->createCategory(
            [
                'category_name' =>
                    $data['category_name'],

                'slug' =>
                    $data['slug'],

                'description' =>
                    $data['description'] !== ''
                        ? $data['description']
                        : null,

                'status' =>
                    $data['status']
            ]
        );

    $_SESSION[
        'admin_category_success'
    ] =
        'Đã tạo danh mục #'
        . $categoryId
        . ' thành công.';

    $this->redirect(
        'admin/categories'
    );
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

    public function edit(
    string $id
): void {
    $this->requireAdmin();

    $categoryId =
        $this->positiveInt($id);

    if ($categoryId === 0) {
        $this->notFound();
    }

    $categoryModel =
        new AdminCategory();

    $category =
        $categoryModel->findById(
            $categoryId
        );

    if ($category === null) {
        $this->notFound();
    }

    $this->view(
        'admin/categories/edit',
        [
            'title' =>
                'Chỉnh sửa danh mục - TourCompare Admin',

            'styles' => [
                'css/admin.css',
                'css/admin-category-form.css'
            ],

            'categoryId' =>
                $categoryId,

            'errors' =>
                [],

            'old' =>
                $category
        ],
        'admin'
    );
    }

    public function update(
    string $id
): void {
    $this->requireAdmin();

    $categoryId =
        $this->positiveInt($id);

    if ($categoryId === 0) {
        $this->notFound();
    }

    $categoryModel =
        new AdminCategory();

    $existingCategory =
        $categoryModel->findById(
            $categoryId
        );

    if ($existingCategory === null) {
        $this->notFound();
    }

    $data = [
        'category_name' => trim(
            $_POST['category_name']
            ?? ''
        ),

        'slug' => trim(
            $_POST['slug']
            ?? ''
        ),

        'description' => trim(
            $_POST['description']
            ?? ''
        ),

        'status' => trim(
            $_POST['status']
            ?? ''
        )
    ];

    $errors = [];

    if ($data['category_name'] === '') {
        $errors['category_name'] =
            'Vui lòng nhập tên danh mục.';
    } elseif (
        mb_strlen(
            $data['category_name']
        ) < 2
    ) {
        $errors['category_name'] =
            'Tên danh mục phải có ít nhất 2 ký tự.';
    } elseif (
        mb_strlen(
            $data['category_name']
        ) > 100
    ) {
        $errors['category_name'] =
            'Tên danh mục không được vượt quá 100 ký tự.';
    } elseif (
        $categoryModel
            ->categoryNameExistsExcept(
                $data['category_name'],
                $categoryId
            )
    ) {
        $errors['category_name'] =
            'Tên danh mục này đã được danh mục khác sử dụng.';
    }

    if ($data['slug'] === '') {
        $data['slug'] =
            $this->makeSlug(
                $data['category_name']
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
        mb_strlen(
            $data['slug']
        ) > 150
    ) {
        $errors['slug'] =
            'Slug không được vượt quá 150 ký tự.';
    } elseif (
        $categoryModel
            ->slugExistsExcept(
                $data['slug'],
                $categoryId
            )
    ) {
        $errors['slug'] =
            'Slug này đã được danh mục khác sử dụng.';
    }

    if (
        mb_strlen(
            $data['description']
        ) > 5000
    ) {
        $errors['description'] =
            'Mô tả danh mục không được vượt quá 5000 ký tự.';
    }

    $allowedStatuses = [
        'active',
        'inactive'
    ];

    if (
        !in_array(
            $data['status'],
            $allowedStatuses,
            true
        )
    ) {
        $errors['status'] =
            'Trạng thái không hợp lệ.';
    }

    if (!empty($errors)) {
        $this->view(
            'admin/categories/edit',
            [
                'title' =>
                    'Chỉnh sửa danh mục - TourCompare Admin',

                'styles' => [
                    'css/admin.css',
                    'css/admin-category-form.css'
                ],

                'categoryId' =>
                    $categoryId,

                'errors' =>
                    $errors,

                'old' =>
                    $data
            ],
            'admin'
        );

        return;
    }

    try {
        $categoryModel->updateCategory(
            $categoryId,
            [
                'category_name' =>
                    $data['category_name'],

                'slug' =>
                    $data['slug'],

                'description' =>
                    $data['description'] !== ''
                        ? $data['description']
                        : null,

                'status' =>
                    $data['status']
            ]
        );

        $_SESSION[
            'admin_category_success'
        ] =
            'Đã cập nhật danh mục #'
            . $categoryId
            . ' thành công.';
    } catch (Throwable $error) {
        $_SESSION[
            'admin_category_error'
        ] =
            'Không thể cập nhật danh mục.';
    }

    $this->redirect(
        'admin/categories'
    );
    }

    public function delete(
    string $id
): void {
    $this->requireAdmin();

    $categoryId =
        $this->positiveInt($id);

    if ($categoryId === 0) {
        $this->notFound();
    }

    $categoryModel =
        new AdminCategory();

    $category =
        $categoryModel->findById(
            $categoryId
        );

    if ($category === null) {
        $this->notFound();
    }

    $tourCount =
        $categoryModel
            ->countToursByCategory(
                $categoryId
            );

    if ($tourCount > 0) {
        $_SESSION[
            'admin_category_error'
        ] =
            'Không thể xóa danh mục "'
            . $category['category_name']
            . '" vì đang có '
            . $tourCount
            . ' Tour sử dụng. '
            . 'Bạn có thể vô hiệu hóa danh mục này.';

        $this->redirect(
            'admin/categories'
        );
    }

    try {
        $categoryModel->deleteCategory(
            $categoryId
        );

        $_SESSION[
            'admin_category_success'
        ] =
            'Đã xóa danh mục #'
            . $categoryId
            . ' thành công.';
    } catch (Throwable $error) {
        $_SESSION[
            'admin_category_error'
        ] =
            'Không thể xóa danh mục.';
    }

    $this->redirect(
        'admin/categories'
    );
    }

    public function disable(
    string $id
): void {
    $this->requireAdmin();

    $categoryId =
        $this->positiveInt($id);

    if ($categoryId === 0) {
        $this->notFound();
    }

    $categoryModel =
        new AdminCategory();

    $category =
        $categoryModel->findById(
            $categoryId
        );

    if ($category === null) {
        $this->notFound();
    }

    if (
        $category['status']
        === 'inactive'
    ) {
        $_SESSION[
            'admin_category_error'
        ] =
            'Danh mục này đã ở trạng thái tạm ẩn.';

        $this->redirect(
            'admin/categories'
        );
    }

    try {
        $categoryModel->disableCategory(
            $categoryId
        );

        $_SESSION[
            'admin_category_success'
        ] =
            'Đã vô hiệu hóa danh mục "'
            . $category['category_name']
            . '".';
    } catch (Throwable $error) {
        $_SESSION[
            'admin_category_error'
        ] =
            'Không thể vô hiệu hóa danh mục.';
    }

    $this->redirect(
        'admin/categories'
    );
    }

    private function notFound(): never
{
    http_response_code(404);

    echo '<h1>404 - Không tìm thấy danh mục.</h1>';

    exit;
    }


}