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
            'keyword' => trim($_GET['keyword'] ?? ''),
            'status' => trim($_GET['status'] ?? ''),
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

        $categories =
            $tourModel->getCategories();

        $companies =
            $tourModel->getCompanies();

        $successMessage =
            $_SESSION['admin_tour_success']
            ?? null;

        $errorMessage =
            $_SESSION['admin_tour_error']
            ?? null;

        unset(
            $_SESSION['admin_tour_success'],
            $_SESSION['admin_tour_error']
        );

        $this->view(
            'admin/tours/index',
            [
                'title' =>
                    'Quản lý Tour - TourCompare Admin',

                'styles' => [
                    'css/admin.css',
                    'css/admin-tours.css'
                ],

                'tours' =>
                    $tours,

                'categories' =>
                    $categories,

                'companies' =>
                    $companies,

                'filters' =>
                    $filters,

                'currentPage' =>
                    $page,

                'totalPages' =>
                    $totalPages,

                'totalTours' =>
                    $totalTours,

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

                'errors' =>
                    [],

                'old' =>
                    $this->emptyTourForm()
            ],
            'admin'
        );
    }

    public function store(): void
    {
        $this->requireAdmin();

        $tourModel = new AdminTour();

        $data = $this->collectTourFormData();

        $errors = $this->validateTourData(
            $tourModel,
            $data
        );

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
            $tourModel->slugExists(
                $data['slug']
            )
        ) {
            $errors['slug'] =
                'Slug này đã tồn tại.';
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

                    'errors' =>
                        $errors,

                    'old' =>
                        $data
                ],
                'admin'
            );

            return;
        }

        $tourId = $tourModel->createTour(
            $this->prepareTourDataForSave(
                $data
            )
        );

        $_SESSION['admin_tour_success'] =
            'Đã tạo Tour #'
            . $tourId
            . ' thành công.';

        $this->redirect(
            'admin/tours'
        );
    }

    public function edit(string $id): void
    {
        $this->requireAdmin();

        $tourId =
            $this->positiveInt($id);

        if ($tourId === 0) {
            $this->notFound();
        }

        $tourModel =
            new AdminTour();

        $tour =
            $tourModel->findById(
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

                'tourId' =>
                    $tourId,

                'categories' =>
                    $tourModel->getActiveCategories(),

                'companies' =>
                    $tourModel->getActiveCompanies(),

                'locations' =>
                    $tourModel->getActiveLocations(),

                'errors' =>
                    [],

                'old' =>
                    $tour
            ],
            'admin'
        );
    }

    public function update(string $id): void
    {
        $this->requireAdmin();

        $tourId =
            $this->positiveInt($id);

        if ($tourId === 0) {
            $this->notFound();
        }

        $tourModel =
            new AdminTour();

        $existingTour =
            $tourModel->findById(
                $tourId
            );

        if ($existingTour === null) {
            $this->notFound();
        }

        $data =
            $this->collectTourFormData();

        $errors =
            $this->validateTourData(
                $tourModel,
                $data
            );

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

                    'tourId' =>
                        $tourId,

                    'categories' =>
                        $tourModel->getActiveCategories(),

                    'companies' =>
                        $tourModel->getActiveCompanies(),

                    'locations' =>
                        $tourModel->getActiveLocations(),

                    'errors' =>
                        $errors,

                    'old' =>
                        $data
                ],
                'admin'
            );

            return;
        }

        $tourModel->updateTour(
            $tourId,
            $this->prepareTourDataForSave(
                $data
            )
        );

        $_SESSION['admin_tour_success'] =
            'Đã cập nhật Tour #'
            . $tourId
            . ' thành công.';

        $this->redirect(
            'admin/tours'
        );
    }

    public function delete(string $id): void
    {
        $this->requireAdmin();

        $tourId =
            $this->positiveInt($id);

        if ($tourId === 0) {
            $this->notFound();
        }

        $tourModel =
            new AdminTour();

        $tour =
            $tourModel->findById(
                $tourId
            );

        if ($tour === null) {
            $this->notFound();
        }

        try {
            $tourImagePaths =
                $tourModel->getTourImagePaths(
                    $tourId
                );

            $tourModel->deleteTour(
                $tourId
            );

            foreach (
                $tourImagePaths
                as $imagePath
            ) {
                $this->deleteUploadedTourImageFile(
                    $imagePath
                );
            }

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

        $tourId =
            $this->positiveInt($id);

        if ($tourId === 0) {
            $this->notFound();
        }

        $tourModel =
            new AdminTour();

        $tour =
            $tourModel->findById(
                $tourId
            );

        if ($tour === null) {
            $this->notFound();
        }

        $successMessage =
            $_SESSION['admin_tour_location_success']
            ?? null;

        $errorMessage =
            $_SESSION['admin_tour_location_error']
            ?? null;

        unset(
            $_SESSION['admin_tour_location_success'],
            $_SESSION['admin_tour_location_error']
        );

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
                    $tourModel->getAvailableLocations(),

                'tourLocations' =>
                    $tourModel->getTourLocations(
                        $tourId
                    ),

                'errors' =>
                    [],

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

        $tourId =
            $this->positiveInt($id);

        if ($tourId === 0) {
            $this->notFound();
        }

        $tourModel =
            new AdminTour();

        $tour =
            $tourModel->findById(
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
                        $tourModel->getAvailableLocations(),

                    'tourLocations' =>
                        $locations,

                    'errors' =>
                        array_values(
                            array_unique(
                                $errors
                            )
                        ),

                    'successMessage' =>
                        null,

                    'errorMessage' =>
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

    public function images(string $id): void
    {
        $this->requireAdmin();

        $tourId =
            $this->positiveInt($id);

        if ($tourId === 0) {
            $this->notFound();
        }

        $tourModel =
            new AdminTour();

        $tour =
            $tourModel->findById(
                $tourId
            );

        if ($tour === null) {
            $this->notFound();
        }

        $successMessage =
            $_SESSION['admin_tour_image_success']
            ?? null;

        $errorMessage =
            $_SESSION['admin_tour_image_error']
            ?? null;

        unset(
            $_SESSION['admin_tour_image_success'],
            $_SESSION['admin_tour_image_error']
        );

        $this->view(
            'admin/tours/images',
            [
                'title' =>
                    'Ảnh Tour - TourCompare Admin',

                'styles' => [
                    'css/admin.css',
                    'css/admin-tour-images.css'
                ],

                'scripts' => [
                    'js/admin-tour-images.js'
                ],

                'tourId' =>
                    $tourId,

                'tour' =>
                    $tour,

                'images' =>
                    $tourModel->getTourImagesAdmin(
                        $tourId
                    ),

                'successMessage' =>
                    $successMessage,

                'errorMessage' =>
                    $errorMessage
            ],
            'admin'
        );
    }

    public function uploadImages(
        string $id
    ): void {
        $this->requireAdmin();

        $tourId =
            $this->positiveInt($id);

        if ($tourId === 0) {
            $this->notFound();
        }

        $tourModel =
            new AdminTour();

        $tour =
            $tourModel->findById(
                $tourId
            );

        if ($tour === null) {
            $this->notFound();
        }

        if (
            !isset($_FILES['images'])
            || !is_array(
                $_FILES['images']['name']
                ?? null
            )
        ) {
            $_SESSION['admin_tour_image_error'] =
                'Vui lòng chọn ít nhất một ảnh.';

            $this->redirect(
                'admin/tours/'
                . $tourId
                . '/images'
            );
        }

        $files =
            $this->normalizeUploadedFiles(
                $_FILES['images']
            );

        if (empty($files)) {
            $_SESSION['admin_tour_image_error'] =
                'Vui lòng chọn ít nhất một ảnh.';

            $this->redirect(
                'admin/tours/'
                . $tourId
                . '/images'
            );
        }

        if (count($files) > 10) {
            $_SESSION['admin_tour_image_error'] =
                'Mỗi lần chỉ được tải tối đa 10 ảnh.';

            $this->redirect(
                'admin/tours/'
                . $tourId
                . '/images'
            );
        }

        $uploadDirectory =
            __DIR__
            . '/../../public/uploads/tours';

        if (
    !is_dir($uploadDirectory)
    && !mkdir(
        $uploadDirectory,
        0755,
        true
    )
    && !is_dir($uploadDirectory)
) {
    $_SESSION['admin_tour_image_error'] =
        'Không thể tạo thư mục lưu ảnh.';

    $this->redirect(
        'admin/tours/'
        . $tourId
        . '/images'
    );
}

if (!is_writable($uploadDirectory)) {
    $_SESSION['admin_tour_image_error'] =
        'Thư mục lưu ảnh không có quyền ghi.';

    $this->redirect(
        'admin/tours/'
        . $tourId
        . '/images'
    );
}

        $allowedTypes = [
            IMAGETYPE_JPEG => 'jpg',
            IMAGETYPE_PNG => 'png',
            IMAGETYPE_WEBP => 'webp'
        ];

        $preparedImages = [];
        $movedFiles = [];

        $nextSortOrder =
            $tourModel->getNextImageSortOrder(
                $tourId
            );

        $hasThumbnail =
            $tourModel->hasTourThumbnail(
                $tourId
            );

        try {
            foreach (
                $files
                as $index => $file
            ) {
                if (
                    $file['error']
                    !== UPLOAD_ERR_OK
                ) {
                    throw new RuntimeException(
                        'Có ảnh tải lên không thành công.'
                    );
                }

                if (
                    $file['size']
                    > 5 * 1024 * 1024
                ) {
                    throw new RuntimeException(
                        'Mỗi ảnh không được vượt quá 5MB.'
                    );
                }

                $imageInfo =
                    getimagesize(
                        $file['tmp_name']
                    );

                $imageWidth =
    (int) $imageInfo[0];

$imageHeight =
    (int) $imageInfo[1];

if (
    $imageWidth > 8000
    || $imageHeight > 8000
) {
    throw new RuntimeException(
        'Kích thước ảnh quá lớn.'
    );
}

                if ($imageInfo === false) {
                    throw new RuntimeException(
                        'Có file không phải hình ảnh hợp lệ.'
                    );
                }

                $imageType =
                    $imageInfo[2];

                if (
                    !isset(
                        $allowedTypes[
                            $imageType
                        ]
                    )
                ) {
                    throw new RuntimeException(
                        'Chỉ chấp nhận ảnh JPG, PNG hoặc WEBP.'
                    );
                }

                $extension =
                    $allowedTypes[
                        $imageType
                    ];

                $fileName =
                    'tour_'
                    . $tourId
                    . '_'
                    . bin2hex(
                        random_bytes(10)
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
                        'Không thể lưu ảnh lên máy chủ.'
                    );
                }

                $movedFiles[] =
                    $destination;

                $preparedImages[] = [
                    'image_url' =>
                        '/uploads/tours/'
                        . $fileName,

                    'alt_text' =>
                        $tour['tour_name'],

                    'is_thumbnail' =>
                        !$hasThumbnail
                        && $index === 0
                            ? 1
                            : 0,

                    'sort_order' =>
                        $nextSortOrder
                        + $index
                ];
            }

            $tourModel->addTourImages(
                $tourId,
                $preparedImages
            );

            $_SESSION[
                'admin_tour_image_success'
            ] =
                'Đã tải lên '
                . count($preparedImages)
                . ' ảnh thành công.';
        } catch (Throwable $error) {
            foreach (
                $movedFiles
                as $filePath
            ) {
                if (is_file($filePath)) {
                    unlink($filePath);
                }
            }

            $_SESSION[
                'admin_tour_image_error'
            ] =
                $error->getMessage();
        }

        $this->redirect(
            'admin/tours/'
            . $tourId
            . '/images'
        );
    }

    public function updateImages(
        string $id
    ): void {
        $this->requireAdmin();

        $tourId =
            $this->positiveInt($id);

        if ($tourId === 0) {
            $this->notFound();
        }

        $tourModel =
            new AdminTour();

        $tour =
            $tourModel->findById(
                $tourId
            );

        if ($tour === null) {
            $this->notFound();
        }

        $existingImages =
            $tourModel->getTourImagesAdmin(
                $tourId
            );

        if (empty($existingImages)) {
            $this->redirect(
                'admin/tours/'
                . $tourId
                . '/images'
            );
        }

        $altTexts =
            $_POST['alt_text']
            ?? [];

        $sortOrders =
            $_POST['sort_order']
            ?? [];

        $thumbnailId =
            $this->positiveInt(
                $_POST['thumbnail_id']
                ?? null
            );

        if (!is_array($altTexts)) {
            $altTexts = [];
        }

        if (!is_array($sortOrders)) {
            $sortOrders = [];
        }

        $images = [];
        $errors = [];
        $validImageIds = [];

        foreach (
            $existingImages
            as $image
        ) {
            $imageId =
                (int) $image['image_id'];

            $validImageIds[] =
                $imageId;

            $altText = trim(
                $altTexts[$imageId]
                ?? ''
            );

            $sortOrder =
                $this->positiveInt(
                    $sortOrders[$imageId]
                    ?? null
                );

            if (
                mb_strlen($altText)
                > 255
            ) {
                $errors[] =
                    'Alt text không được vượt quá 255 ký tự.';
            }

            if ($sortOrder === 0) {
                $sortOrder = 1;
            }

            $images[] = [
                'image_id' =>
                    $imageId,

                'alt_text' =>
                    $altText !== ''
                        ? $altText
                        : null,

                'sort_order' =>
                    $sortOrder
            ];
        }

        if (
            $thumbnailId === 0
            || !in_array(
                $thumbnailId,
                $validImageIds,
                true
            )
        ) {
            $errors[] =
                'Vui lòng chọn một ảnh đại diện hợp lệ.';
        }

        if (!empty($errors)) {
            $_SESSION[
                'admin_tour_image_error'
            ] =
                implode(
                    ' ',
                    array_unique(
                        $errors
                    )
                );

            $this->redirect(
                'admin/tours/'
                . $tourId
                . '/images'
            );
        }

        usort(
            $images,
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
            $images
            as $index => &$image
        ) {
            $image['sort_order'] =
                $index + 1;
        }

        unset($image);

        try {
            $tourModel->updateTourImages(
                $tourId,
                $images,
                $thumbnailId
            );

            $_SESSION[
                'admin_tour_image_success'
            ] =
                'Thông tin ảnh đã được cập nhật.';
        } catch (Throwable $error) {
            $_SESSION[
                'admin_tour_image_error'
            ] =
                'Không thể cập nhật thông tin ảnh.';
        }

        $this->redirect(
            'admin/tours/'
            . $tourId
            . '/images'
        );
    }

    public function deleteImage(
        string $id,
        string $imageId
    ): void {
        $this->requireAdmin();

        $tourId =
            $this->positiveInt($id);

        $imageId =
            $this->positiveInt(
                $imageId
            );

        if (
            $tourId === 0
            || $imageId === 0
        ) {
            $this->notFound();
        }

        $tourModel =
            new AdminTour();

        $tour =
            $tourModel->findById(
                $tourId
            );

        if ($tour === null) {
            $this->notFound();
        }

        $image =
            $tourModel->findTourImage(
                $tourId,
                $imageId
            );

        if ($image === null) {
            $this->notFound();
        }

        try {
            $tourModel->deleteTourImage(
                $tourId,
                $imageId
            );

            $this->deleteUploadedTourImageFile(
                $image['image_url']
            );

            $_SESSION[
                'admin_tour_image_success'
            ] =
                'Đã xóa ảnh thành công.';
        } catch (Throwable $error) {
            $_SESSION[
                'admin_tour_image_error'
            ] =
                'Không thể xóa ảnh.';
        }

        $this->redirect(
            'admin/tours/'
            . $tourId
            . '/images'
        );
    }

    public function schedules(string $id): void
    {
        $this->requireAdmin();

        $tourId =
            $this->positiveInt($id);

        if ($tourId === 0) {
            $this->notFound();
        }

        $tourModel =
            new AdminTour();


        $tour =
            $tourModel->findById(
                $tourId
            );

        if ($tour === null) {
            $this->notFound();
        }

        $schedules =
            $tourModel->getTourSchedulesAdmin(
                $tourId
            );

        $successMessage =
            $_SESSION['admin_tour_schedule_success']
            ?? null;

        $errorMessage =
            $_SESSION['admin_tour_schedule_error']
            ?? null;

        unset(
            $_SESSION['admin_tour_schedule_success'],
            $_SESSION['admin_tour_schedule_error']
        );

        $this->view(
            'admin/tours/schedules',
            [
                'title' =>
                    'Lịch trình Tour - TourCompare Admin',

                'styles' => [
                    'css/admin.css',
                    'css/admin-tour-schedules.css'
                ],

                'scripts' => [
                    'js/admin-tour-schedules.js'
                ],

                'tourId' =>
                    $tourId,

                'tour' =>
                    $tour,

                'schedules' =>
                    $schedules,

                'errors' =>
                    [],

                'successMessage' =>
                    $successMessage,

                'errorMessage' =>
                    $errorMessage
            ],
            'admin'
        );
    }

    public function updateSchedules(
        string $id
    ): void {
        $this->requireAdmin();

        $tourId =
            $this->positiveInt($id);

        if ($tourId === 0) {
            $this->notFound();
        }

        $tourModel =
            new AdminTour();

        $tour =
            $tourModel->findById(
                $tourId
            );

        if ($tour === null) {
            $this->notFound();
        }

        $dayNumbers =
            $_POST['day_number']
            ?? [];

        $titles =
            $_POST['title']
            ?? [];

        $descriptions =
            $_POST['description']
            ?? [];

        if (!is_array($dayNumbers)) {
            $dayNumbers = [];
        }

        if (!is_array($titles)) {
            $titles = [];
        }

        if (!is_array($descriptions)) {
            $descriptions = [];
        }

        $errors = [];
        $schedules = [];

        foreach (
            $dayNumbers
            as $index => $rawDayNumber
        ) {
            $dayNumber =
                $this->positiveInt(
                    $rawDayNumber
                );

            $title = trim(
                $titles[$index]
                ?? ''
            );

            $description = trim(
                $descriptions[$index]
                ?? ''
            );

            if (
                $dayNumber === 0
                && $title === ''
                && $description === ''
            ) {
                continue;
            }

            if ($dayNumber === 0) {
                $errors[] =
                    'Số ngày phải lớn hơn 0.';
            }

            if ($title === '') {
                $errors[] =
                    'Tiêu đề lịch trình không được để trống.';
            } elseif (
                mb_strlen($title)
                > 200
            ) {
                $errors[] =
                    'Tiêu đề lịch trình không được vượt quá 200 ký tự.';
            }

            if (
                mb_strlen(
                    $description
                ) > 5000
            ) {
                $errors[] =
                    'Mô tả lịch trình không được vượt quá 5000 ký tự.';
            }

            if (
                $dayNumber > 0
                && $title !== ''
            ) {
                $schedules[] = [
                    'day_number' =>
                        $dayNumber,

                    'title' =>
                        $title,

                    'description' =>
                        $description !== ''
                            ? $description
                            : null
                ];
            }
        }

        if (
            !empty($schedules)
            && count($schedules)
                !== (int)
                $tour['duration_days']
        ) {
            $errors[] =
                'Số ngày lịch trình phải bằng thời lượng Tour: '
                . (int)
                $tour['duration_days']
                . ' ngày.';
        }

        if (!empty($errors)) {
            $this->view(
                'admin/tours/schedules',
                [
                    'title' =>
                        'Lịch trình Tour - TourCompare Admin',

                    'styles' => [
                        'css/admin.css',
                        'css/admin-tour-schedules.css'
                    ],

                    'scripts' => [
                        'js/admin-tour-schedules.js'
                    ],

                    'tourId' =>
                        $tourId,

                    'tour' =>
                        $tour,

                    'schedules' =>
                        $schedules,

                    'errors' =>
                        array_values(
                            array_unique(
                                $errors
                            )
                        ),

                    'successMessage' =>
                        null,

                    'errorMessage' =>
                        null
                ],
                'admin'
            );

            return;
        }

        usort(
            $schedules,
            function (
                array $a,
                array $b
            ) {
                return
                    $a['day_number']
                    <=>
                    $b['day_number'];
            }
        );

        foreach (
            $schedules
            as $index => &$schedule
        ) {
            $schedule['day_number'] =
                $index + 1;
        }

        unset($schedule);

        try {
            $tourModel->updateTourSchedules(
                $tourId,
                $schedules
            );

            $_SESSION[
                'admin_tour_schedule_success'
            ] =
                'Đã cập nhật lịch trình cho Tour #'
                . $tourId
                . ' thành công.';
        } catch (Throwable $error) {
            $_SESSION[
                'admin_tour_schedule_error'
            ] =
                $error->getMessage();
        }

        $this->redirect(
            'admin/tours/'
            . $tourId
            . '/schedules'
        );
    }

    private function collectTourFormData(): array
    {
        return [
            'tour_name' =>
                trim(
                    $_POST['tour_name']
                    ?? ''
                ),

            'slug' =>
                trim(
                    $_POST['slug']
                    ?? ''
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
                    $_POST[
                        'departure_location_id'
                    ]
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

            'short_description' =>
                trim(
                    $_POST[
                        'short_description'
                    ]
                    ?? ''
                ),

            'description' =>
                trim(
                    $_POST['description']
                    ?? ''
                ),

            'source_url' =>
                trim(
                    $_POST['source_url']
                    ?? ''
                ),

            'featured' =>
                isset($_POST['featured'])
                    ? 1
                    : 0,

            'status' =>
                trim(
                    $_POST['status']
                    ?? ''
                )
        ];
    }

    private function validateTourData(
        AdminTour $tourModel,
        array $data
    ): array {
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
            $data[
                'departure_location_id'
            ] === 0
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
                $data[
                    'short_description'
                ]
            ) > 500
        ) {
            $errors[
                'short_description'
            ] =
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

        return $errors;
    }

    private function prepareTourDataForSave(
        array $data
    ): array {
        return [
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
        ];
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
            || trim(
                (string) $value
            ) === ''
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

        $price =
            (float) $value;

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

    private function normalizeUploadedFiles(
        array $files
    ): array {
        $normalized = [];

        $count = count(
            $files['name']
            ?? []
        );

        for (
            $index = 0;
            $index < $count;
            $index++
        ) {
            $error =
                $files['error'][$index]
                ?? UPLOAD_ERR_NO_FILE;

            if (
                $error
                === UPLOAD_ERR_NO_FILE
            ) {
                continue;
            }

            $normalized[] = [
                'name' =>
                    $files['name'][$index]
                    ?? '',

                'type' =>
                    $files['type'][$index]
                    ?? '',

                'tmp_name' =>
                    $files['tmp_name'][$index]
                    ?? '',

                'error' =>
                    $error,

                'size' =>
                    (int) (
                        $files['size'][$index]
                        ?? 0
                    )
            ];
        }

        return $normalized;
    }

    private function deleteUploadedTourImageFile(
        string $imageUrl
    ): void {
        $normalized = str_replace(
            '\\',
            '/',
            $imageUrl
        );

        $prefix =
            '/uploads/tours/';

        if (
            !str_starts_with(
                $normalized,
                $prefix
            )
        ) {
            return;
        }

        $fileName =
            basename(
                $normalized
            );

        $filePath =
            __DIR__
            . '/../../public/uploads/tours/'
            . $fileName;

        if (is_file($filePath)) {
            unlink($filePath);
        }
    }

    private function notFound(): never
    {
        http_response_code(404);

        echo '<h1>404 - Không tìm thấy Tour.</h1>';

        exit;
    }
}