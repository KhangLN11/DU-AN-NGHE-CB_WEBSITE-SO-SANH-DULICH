<?php

require_once __DIR__ . '/AdminBaseController.php';
require_once __DIR__ . '/../models/AdminTour.php';

class AdminTourController extends AdminBaseController
{
    public function index(): void
    {
        $this->requireAdmin();

        $tourModel = new AdminTour();

        $filters = [
            'keyword' => trim(
                $_GET['keyword'] ?? ''
            ),
            'status' => trim(
                $_GET['status'] ?? ''
            ),
            'category' => $this->positiveInt(
                $_GET['category'] ?? null
            ),
            'company' => $this->positiveInt(
                $_GET['company'] ?? null
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

        $page = $this->positiveInt(
            $_GET['page'] ?? 1
        );

        if ($page < 1) {
            $page = 1;
        }

        $totalTours = $tourModel->countTours(
            $filters
        );

        $totalPages = max(
            1,
            (int) ceil(
                $totalTours / $perPage
            )
        );

        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $offset = ($page - 1) * $perPage;

        $tours = $tourModel->getPaginatedTours(
            $filters,
            $perPage,
            $offset
        );

        $categories = $tourModel->getCategories();
        $companies = $tourModel->getCompanies();

        $successMessage =
            $_SESSION['admin_tour_success']
            ?? null;
        unset($_SESSION['admin_tour_success']);

        $errorMessage =
            $_SESSION['admin_tour_error']
            ?? null;
        unset($_SESSION['admin_tour_error']);

        $this->view(
            'admin/tours/index',
            [
                'title' =>
                    'Quản lý Tour - TourCompare Admin',
                'styles' => [
                    'css/admin.css',
                    'css/admin-tours.css'
                ],
                'tours' => $tours,
                'categories' => $categories,
                'companies' => $companies,
                'filters' => $filters,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'totalTours' => $totalTours,
                'errorMessage' => $errorMessage,
                'successMessage' => $successMessage
            ],
            'admin'
        );
    }

    public function create(): void
    {
        $this->requireAdmin();

        $tourModel = new AdminTour();

        $this->view(
            'admin/tours/create',
            [
                'title' =>
                    'Thêm Tour - TourCompare Admin',
                'styles' => [
                    'css/admin.css',
                    'css/admin-tour-form.css'
                ],
                'categories' =>
                    $tourModel->getActiveCategories(),
                'companies' =>
                    $tourModel->getActiveCompanies(),
                'locations' =>
                    $tourModel->getActiveLocations(),
                'errors' => [],
                'old' => $this->emptyTourForm()
            ],
            'admin'
        );
    }

    public function store(): void
    {
        $this->requireAdmin();

        $tourModel = new AdminTour();

        $data = [
            'tour_name' => trim(
                $_POST['tour_name'] ?? ''
            ),
            'slug' => trim(
                $_POST['slug'] ?? ''
            ),
            'category_id' => $this->positiveInt(
                $_POST['category_id'] ?? null
            ),
            'company_id' => $this->positiveInt(
                $_POST['company_id'] ?? null
            ),
            'departure_location_id' =>
                $this->positiveInt(
                    $_POST['departure_location_id']
                    ?? null
                ),
            'price' => $this->priceValue(
                $_POST['price'] ?? null
            ),
            'duration_days' => $this->positiveInt(
                $_POST['duration_days'] ?? null
            ),
            'duration_nights' => $this->nonNegativeInt(
                $_POST['duration_nights'] ?? null
            ),
            'short_description' => trim(
                $_POST['short_description'] ?? ''
            ),
            'description' => trim(
                $_POST['description'] ?? ''
            ),
            'source_url' => trim(
                $_POST['source_url'] ?? ''
            ),
            'featured' =>
                isset($_POST['featured'])
                    ? 1
                    : 0,
            'status' => trim(
                $_POST['status'] ?? ''
            )
        ];

        $errors = [];

        if ($data['tour_name'] === '') {
            $errors['tour_name'] =
                'Vui lòng nhập tên Tour.';
        } elseif (
            mb_strlen($data['tour_name']) < 3
        ) {
            $errors['tour_name'] =
                'Tên Tour phải có ít nhất 3 ký tự.';
        } elseif (
            mb_strlen($data['tour_name']) > 200
        ) {
            $errors['tour_name'] =
                'Tên Tour không được vượt quá 200 ký tự.';
        }

        if ($data['slug'] === '') {
            $data['slug'] = $this->makeSlug(
                $data['tour_name']
            );
        } else {
            $data['slug'] = $this->makeSlug(
                $data['slug']
            );
        }

        if ($data['slug'] === '') {
            $errors['slug'] =
                'Không thể tạo slug hợp lệ.';
        } elseif (
            $tourModel->slugExists(
                $data['slug']
            )
        ) {
            $errors['slug'] =
                'Slug này đã tồn tại.';
        }

        if (
            $data['category_id'] === 0
            || !$tourModel->categoryExists(
                $data['category_id']
            )
        ) {
            $errors['category_id'] =
                'Vui lòng chọn danh mục hợp lệ.';
        }

        if (
            $data['company_id'] === 0
            || !$tourModel->companyExists(
                $data['company_id']
            )
        ) {
            $errors['company_id'] =
                'Vui lòng chọn công ty hợp lệ.';
        }

        if (
            $data['departure_location_id'] === 0
            || !$tourModel->locationExists(
                $data['departure_location_id']
            )
        ) {
            $errors['departure_location_id'] =
                'Vui lòng chọn điểm khởi hành hợp lệ.';
        }

        if ($data['price'] === null) {
            $errors['price'] =
                'Vui lòng nhập giá Tour hợp lệ.';
        }

        if ($data['duration_days'] < 1) {
            $errors['duration_days'] =
                'Số ngày phải lớn hơn 0.';
        }

        if ($data['duration_nights'] < 0) {
            $errors['duration_nights'] =
                'Số đêm không hợp lệ.';
        }

        if (
            mb_strlen(
                $data['short_description']
            ) > 500
        ) {
            $errors['short_description'] =
                'Mô tả ngắn không được vượt quá 500 ký tự.';
        }

        if (
            mb_strlen(
                $data['description']
            ) > 10000
        ) {
            $errors['description'] =
                'Mô tả chi tiết quá dài.';
        }

        if ($data['source_url'] !== '') {
            if (
                !filter_var(
                    $data['source_url'],
                    FILTER_VALIDATE_URL
                )
            ) {
                $errors['source_url'] =
                    'URL nguồn không hợp lệ.';
            }
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
                'admin/tours/create',
                [
                    'title' =>
                        'Thêm Tour - TourCompare Admin',
                    'styles' => [
                        'css/admin.css',
                        'css/admin-tour-form.css'
                    ],
                    'categories' =>
                        $tourModel->getActiveCategories(),
                    'companies' =>
                        $tourModel->getActiveCompanies(),
                    'locations' =>
                        $tourModel->getActiveLocations(),
                    'errors' => $errors,
                    'old' => $data
                ],
                'admin'
            );

            return;
        }

        $tourId = $tourModel->createTour([
            'category_id' =>
                $data['category_id'],
            'company_id' =>
                $data['company_id'],
            'departure_location_id' =>
                $data['departure_location_id'],
            'tour_name' =>
                $data['tour_name'],
            'slug' =>
                $data['slug'],
            'short_description' =>
                $data['short_description'] !== ''
                    ? $data['short_description']
                    : null,
            'description' =>
                $data['description'] !== ''
                    ? $data['description']
                    : null,
            'price' =>
                $data['price'],
            'duration_days' =>
                $data['duration_days'],
            'duration_nights' =>
                $data['duration_nights'],
            'source_url' =>
                $data['source_url'] !== ''
                    ? $data['source_url']
                    : null,
            'featured' =>
                $data['featured'],
            'status' =>
                $data['status']
        ]);

        $_SESSION['admin_tour_success'] =
            'Đã tạo Tour #' . $tourId . ' thành công.';

        $this->redirect('admin/tours');
    }

    public function edit(string $id): void
{
    $this->requireAdmin();

    $tourId = $this->positiveInt($id);

    if ($tourId === 0) {
        $this->notFound();
    }

    $tourModel = new AdminTour();

    $tour = $tourModel->findById(
        $tourId
    );

    if ($tour === null) {
        $this->notFound();
    }

    $this->view(
        'admin/tours/edit',
        [
            'title' =>
                'Chỉnh sửa Tour - TourCompare Admin',

            'styles' => [
                'css/admin.css',
                'css/admin-tour-form.css'
            ],

            'tourId' => $tourId,

            'categories' =>
                $tourModel->getActiveCategories(),

            'companies' =>
                $tourModel->getActiveCompanies(),

            'locations' =>
                $tourModel->getActiveLocations(),

            'errors' => [],

            'old' => $tour
        ],
        'admin'
    );
    }

    public function update(string $id): void
{
    $this->requireAdmin();

    $tourId = $this->positiveInt($id);

    if ($tourId === 0) {
        $this->notFound();
    }

    $tourModel = new AdminTour();

    $existingTour = $tourModel->findById(
        $tourId
    );

    if ($existingTour === null) {
        $this->notFound();
    }

    $data = [
        'tour_name' => trim(
            $_POST['tour_name'] ?? ''
        ),

        'slug' => trim(
            $_POST['slug'] ?? ''
        ),

        'category_id' =>
            $this->positiveInt(
                $_POST['category_id']
                ?? null
            ),

        'company_id' =>
            $this->positiveInt(
                $_POST['company_id']
                ?? null
            ),

        'departure_location_id' =>
            $this->positiveInt(
                $_POST['departure_location_id']
                ?? null
            ),

        'price' =>
            $this->priceValue(
                $_POST['price']
                ?? null
            ),

        'duration_days' =>
            $this->positiveInt(
                $_POST['duration_days']
                ?? null
            ),

        'duration_nights' =>
            $this->nonNegativeInt(
                $_POST['duration_nights']
                ?? null
            ),

        'short_description' => trim(
            $_POST['short_description']
            ?? ''
        ),

        'description' => trim(
            $_POST['description']
            ?? ''
        ),

        'source_url' => trim(
            $_POST['source_url']
            ?? ''
        ),

        'featured' =>
            isset($_POST['featured'])
                ? 1
                : 0,

        'status' => trim(
            $_POST['status']
            ?? ''
        )
    ];

    $errors = [];

    if ($data['tour_name'] === '') {
        $errors['tour_name'] =
            'Vui lòng nhập tên Tour.';
    } elseif (
        mb_strlen(
            $data['tour_name']
        ) < 3
    ) {
        $errors['tour_name'] =
            'Tên Tour phải có ít nhất 3 ký tự.';
    } elseif (
        mb_strlen(
            $data['tour_name']
        ) > 200
    ) {
        $errors['tour_name'] =
            'Tên Tour không được vượt quá 200 ký tự.';
    }

    if ($data['slug'] === '') {
        $data['slug'] =
            $this->makeSlug(
                $data['tour_name']
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
        $tourModel->slugExistsExcept(
            $data['slug'],
            $tourId
        )
    ) {
        $errors['slug'] =
            'Slug này đã được Tour khác sử dụng.';
    }

    if (
        $data['category_id'] === 0
        || !$tourModel->categoryExists(
            $data['category_id']
        )
    ) {
        $errors['category_id'] =
            'Vui lòng chọn danh mục hợp lệ.';
    }

    if (
        $data['company_id'] === 0
        || !$tourModel->companyExists(
            $data['company_id']
        )
    ) {
        $errors['company_id'] =
            'Vui lòng chọn công ty hợp lệ.';
    }

    if (
        $data['departure_location_id']
            === 0
        || !$tourModel->locationExists(
            $data[
                'departure_location_id'
            ]
        )
    ) {
        $errors[
            'departure_location_id'
        ] =
            'Vui lòng chọn điểm khởi hành hợp lệ.';
    }

    if ($data['price'] === null) {
        $errors['price'] =
            'Vui lòng nhập giá Tour hợp lệ.';
    }

    if (
        $data['duration_days'] < 1
    ) {
        $errors['duration_days'] =
            'Số ngày phải lớn hơn 0.';
    }

    if (
        $data['duration_nights'] < 0
    ) {
        $errors['duration_nights'] =
            'Số đêm không hợp lệ.';
    }

    if (
        mb_strlen(
            $data['short_description']
        ) > 500
    ) {
        $errors['short_description'] =
            'Mô tả ngắn không được vượt quá 500 ký tự.';
    }

    if (
        mb_strlen(
            $data['description']
        ) > 10000
    ) {
        $errors['description'] =
            'Mô tả chi tiết quá dài.';
    }

    if (
        $data['source_url'] !== ''
        && !filter_var(
            $data['source_url'],
            FILTER_VALIDATE_URL
        )
    ) {
        $errors['source_url'] =
            'URL nguồn không hợp lệ.';
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
            'admin/tours/edit',
            [
                'title' =>
                    'Chỉnh sửa Tour - TourCompare Admin',

                'styles' => [
                    'css/admin.css',
                    'css/admin-tour-form.css'
                ],

                'tourId' => $tourId,

                'categories' =>
                    $tourModel
                        ->getActiveCategories(),

                'companies' =>
                    $tourModel
                        ->getActiveCompanies(),

                'locations' =>
                    $tourModel
                        ->getActiveLocations(),

                'errors' => $errors,

                'old' => $data
            ],
            'admin'
        );

        return;
    }

    $tourModel->updateTour(
        $tourId,
        [
            'category_id' =>
                $data['category_id'],

            'company_id' =>
                $data['company_id'],

            'departure_location_id' =>
                $data[
                    'departure_location_id'
                ],

            'tour_name' =>
                $data['tour_name'],

            'slug' =>
                $data['slug'],

            'short_description' =>
                $data[
                    'short_description'
                ] !== ''
                    ? $data[
                        'short_description'
                    ]
                    : null,

            'description' =>
                $data['description'] !== ''
                    ? $data['description']
                    : null,

            'price' =>
                $data['price'],

            'duration_days' =>
                $data['duration_days'],

            'duration_nights' =>
                $data[
                    'duration_nights'
                ],

            'source_url' =>
                $data['source_url'] !== ''
                    ? $data['source_url']
                    : null,

            'featured' =>
                $data['featured'],

            'status' =>
                $data['status']
        ]
    );

    $_SESSION['admin_tour_success'] =
        'Đã cập nhật Tour #'
        . $tourId
        . ' thành công.';

    $this->redirect(
        'admin/tours'
    );
    }

    private function emptyTourForm(): array
    {
        return [
            'tour_name' => '',
            'slug' => '',
            'category_id' => 0,
            'company_id' => 0,
            'departure_location_id' => 0,
            'price' => null,
            'duration_days' => 1,
            'duration_nights' => 0,
            'short_description' => '',
            'description' => '',
            'source_url' => '',
            'featured' => 0,
            'status' => 'active'
        ];
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

    private function nonNegativeInt(
        mixed $value
    ): int {
        $number = filter_var(
            $value,
            FILTER_VALIDATE_INT,
            [
                'options' => [
                    'min_range' => 0
                ]
            ]
        );

        return $number === false
            ? -1
            : $number;
    }

    private function priceValue(
        mixed $value
    ): ?float {
        if (
            $value === null
            || trim((string) $value) === ''
        ) {
            return null;
        }

        $value = str_replace(
            ['.', ',', ' '],
            '',
            (string) $value
        );

        if (!is_numeric($value)) {
            return null;
        }

        $price = (float) $value;

        return $price >= 0
            ? $price
            : null;
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

        echo '<h1>404 - Không tìm thấy Tour.</h1>';

    exit;
    }

    public function delete(string $id): void
{
    $this->requireAdmin();

    $tourId = $this->positiveInt(
        $id
    );

    if ($tourId === 0) {
        $this->notFound();
    }

    $tourModel = new AdminTour();

    $tour = $tourModel->findById(
        $tourId
    );

    if ($tour === null) {
        $this->notFound();
    }

    try {
        $tourModel->deleteTour(
            $tourId
        );

        $_SESSION['admin_tour_success'] =
            'Đã xóa Tour #'
            . $tourId
            . ' thành công.';
    } catch (Throwable $error) {
        $_SESSION['admin_tour_error'] =
            'Không thể xóa Tour. Vui lòng thử lại.';
    }

    $this->redirect(
        'admin/tours'
    );
    }

    public function locations(string $id): void
{
    $this->requireAdmin();

    $tourId = $this->positiveInt($id);

    if ($tourId === 0) {
        $this->notFound();
    }

    $tourModel = new AdminTour();

    $tour = $tourModel->findById(
        $tourId
    );

    if ($tour === null) {
        $this->notFound();
    }

    $availableLocations =
        $tourModel->getAvailableLocations();

    $tourLocations =
        $tourModel->getTourLocations(
            $tourId
        );

    $successMessage =
        $_SESSION['admin_tour_location_success']
        ?? null;

    unset(
        $_SESSION['admin_tour_location_success']
    );

    $errorMessage =
    $_SESSION['admin_tour_location_error']
    ?? null;
    unset( $_SESSION['admin_tour_location_error']);

    $this->view(
        'admin/tours/locations',
        [
            'title' =>
                'Điểm đến Tour - TourCompare Admin',

            'styles' => [
                'css/admin.css',
                'css/admin-tour-locations.css'
            ],

            'tourId' => $tourId,

            'tour' => $tour,

            'availableLocations' =>
                $availableLocations,

            'tourLocations' =>
                $tourLocations,

            'errors' => [],

            'successMessage' =>
                $successMessage,

            'errorMessage' =>
                $errorMessage
        ],
        'admin'
    );
    }

    public function updateLocations(
    string $id
): void {
    $this->requireAdmin();

    $tourId = $this->positiveInt($id);

    if ($tourId === 0) {
        $this->notFound();
    }

    $tourModel = new AdminTour();

    $tour = $tourModel->findById(
        $tourId
    );

    if ($tour === null) {
        $this->notFound();
    }

    $locationIds =
        $_POST['location_id']
        ?? [];

    $sortOrders =
        $_POST['sort_order']
        ?? [];

    $notes =
        $_POST['note']
        ?? [];

    if (!is_array($locationIds)) {
        $locationIds = [];
    }

    if (!is_array($sortOrders)) {
        $sortOrders = [];
    }

    if (!is_array($notes)) {
        $notes = [];
    }

    $errors = [];
    $locations = [];
    $usedLocationIds = [];

    foreach (
        $locationIds
        as $index => $rawLocationId
    ) {
        $locationId =
            $this->positiveInt(
                $rawLocationId
            );

        $sortOrder =
            $this->positiveInt(
                $sortOrders[$index]
                ?? null
            );

        $note = trim(
            $notes[$index]
            ?? ''
        );

        if ($locationId === 0) {
            continue;
        }

        if (
            !$tourModel->locationExists(
                $locationId
            )
        ) {
            $errors[] =
                'Có địa điểm không hợp lệ.';

            continue;
        }

        if (
            in_array(
                $locationId,
                $usedLocationIds,
                true
            )
        ) {
            $errors[] =
                'Một địa điểm không được chọn hai lần.';

            continue;
        }

        if ($sortOrder === 0) {
            $errors[] =
                'Thứ tự điểm đến phải lớn hơn 0.';

            continue;
        }

        if (
            mb_strlen($note) > 500
        ) {
            $errors[] =
                'Ghi chú điểm đến không được vượt quá 500 ký tự.';

            continue;
        }

        $usedLocationIds[] =
            $locationId;

        $locations[] = [
            'location_id' =>
                $locationId,

            'sort_order' =>
                $sortOrder,

            'note' =>
                $note !== ''
                    ? $note
                    : null
        ];
    }

    if (!empty($errors)) {
        $this->view(
            'admin/tours/locations',
            [
                'title' =>
                    'Điểm đến Tour - TourCompare Admin',

                'styles' => [
                    'css/admin.css',
                    'css/admin-tour-locations.css'
                ],

                'tourId' =>
                    $tourId,

                'tour' =>
                    $tour,

                'availableLocations' =>
                    $tourModel
                        ->getAvailableLocations(),

                'tourLocations' =>
                    $locations,

                'errors' =>
                    array_values(
                        array_unique(
                            $errors
                        )
                    ),

                'successMessage' =>
                    null
            ],
            'admin'
        );

        return;
    }

    usort(
        $locations,
        function (
            array $a,
            array $b
        ) {
            return
                $a['sort_order']
                <=>
                $b['sort_order'];
        }
    );

    foreach (
        $locations
        as $index => &$location
    ) {
        $location['sort_order'] =
            $index + 1;
    }

    unset($location);

    try {
        $tourModel->updateTourLocations(
            $tourId,
            $locations
        );

        $_SESSION[
            'admin_tour_location_success'
        ] =
            'Đã cập nhật điểm đến cho Tour #'
            . $tourId
            . ' thành công.';
    } catch (Throwable $error) {
        $_SESSION[
            'admin_tour_location_error'
        ] =
            'Không thể cập nhật điểm đến.';
    }

    $this->redirect(
        'admin/tours/'
        . $tourId
        . '/locations'
    );
    }
}