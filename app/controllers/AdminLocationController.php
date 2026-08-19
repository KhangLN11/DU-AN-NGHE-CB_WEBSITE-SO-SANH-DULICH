<?php

require_once __DIR__ . '/AdminBaseController.php';
require_once __DIR__ . '/../models/AdminLocation.php';

class AdminLocationController extends AdminBaseController
{
    public function index(): void
    {
        $this->requireAdmin();

        $locationModel =
            new AdminLocation();

        $filters = [
            'keyword' => trim(
                $_GET['keyword']
                ?? ''
            ),

            'status' => trim(
                $_GET['status']
                ?? ''
            ),

            'country' => trim(
                $_GET['country']
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

        $totalLocations =
            $locationModel->countLocations(
                $filters
            );

        $totalPages = max(
            1,
            (int) ceil(
                $totalLocations
                / $perPage
            )
        );

        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $offset =
            ($page - 1)
            * $perPage;

        $locations =
            $locationModel
                ->getPaginatedLocations(
                    $filters,
                    $perPage,
                    $offset
                );

        $countries =
            $locationModel->getCountries();

        $successMessage =
            $_SESSION[
                'admin_location_success'
            ]
            ?? null;

        $errorMessage =
            $_SESSION[
                'admin_location_error'
            ]
            ?? null;

        unset(
            $_SESSION[
                'admin_location_success'
            ],
            $_SESSION[
                'admin_location_error'
            ]
        );

        $this->view(
            'admin/locations/index',
            [
                'title' =>
                    'Quản lý địa điểm - TourCompare Admin',

                'styles' => [
                    'css/admin.css',
                    'css/admin-locations.css'
                ],

                'locations' =>
                    $locations,

                'countries' =>
                    $countries,

                'filters' =>
                    $filters,

                'currentPage' =>
                    $page,

                'totalPages' =>
                    $totalPages,

                'totalLocations' =>
                    $totalLocations,

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
            'admin/locations/create',
            [
                'title' =>
                    'Thêm địa điểm - TourCompare Admin',

                'styles' => [
                    'css/admin.css',
                    'css/admin-location-form.css'
                ],

                'errors' =>
                    [],

                'old' =>
                    $this->emptyLocationForm()
            ],
            'admin'
        );
    }

    public function store(): void
    {
        $this->requireAdmin();

        $locationModel =
            new AdminLocation();

        $data =
            $this->collectLocationFormData();

        $errors =
            $this->validateLocationData(
                $data
            );

        if ($data['slug'] === '') {
            $data['slug'] =
                $this->makeSlug(
                    $data['location_name']
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
            ) > 180
        ) {
            $errors['slug'] =
                'Slug không được vượt quá 180 ký tự.';
        } elseif (
            $locationModel->slugExists(
                $data['slug']
            )
        ) {
            $errors['slug'] =
                'Slug này đã tồn tại.';
        }

        if (!empty($errors)) {
            $this->view(
                'admin/locations/create',
                [
                    'title' =>
                        'Thêm địa điểm - TourCompare Admin',

                    'styles' => [
                        'css/admin.css',
                        'css/admin-location-form.css'
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

        $uploadedImage = null;

        try {
            $uploadedImage =
                $this->uploadImage();

            $data['image'] =
                $uploadedImage;

            $locationId =
                $locationModel
                    ->createLocation(
                        $this->prepareLocationData(
                            $data
                        )
                    );

            $_SESSION[
                'admin_location_success'
            ] =
                'Đã tạo địa điểm #'
                . $locationId
                . ' thành công.';
        } catch (Throwable $error) {
            if ($uploadedImage !== null) {
                $this->deleteImageFile(
                    $uploadedImage
                );
            }

            $_SESSION[
                'admin_location_error'
            ] =
                $error->getMessage();
        }

        $this->redirect(
            'admin/locations'
        );
    }

    public function edit(
        string $id
    ): void {
        $this->requireAdmin();

        $locationId =
            $this->positiveInt($id);

        if ($locationId === 0) {
            $this->notFound();
        }

        $locationModel =
            new AdminLocation();

        $location =
            $locationModel->findById(
                $locationId
            );

        if ($location === null) {
            $this->notFound();
        }

        $this->view(
            'admin/locations/edit',
            [
                'title' =>
                    'Chỉnh sửa địa điểm - TourCompare Admin',

                'styles' => [
                    'css/admin.css',
                    'css/admin-location-form.css'
                ],

                'locationId' =>
                    $locationId,

                'errors' =>
                    [],

                'old' =>
                    $location
            ],
            'admin'
        );
    }

    public function update(
        string $id
    ): void {
        $this->requireAdmin();

        $locationId =
            $this->positiveInt($id);

        if ($locationId === 0) {
            $this->notFound();
        }

        $locationModel =
            new AdminLocation();

        $existingLocation =
            $locationModel->findById(
                $locationId
            );

        if ($existingLocation === null) {
            $this->notFound();
        }

        $data =
            $this->collectLocationFormData();

        $errors =
            $this->validateLocationData(
                $data
            );

        if ($data['slug'] === '') {
            $data['slug'] =
                $this->makeSlug(
                    $data['location_name']
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
            ) > 180
        ) {
            $errors['slug'] =
                'Slug không được vượt quá 180 ký tự.';
        } elseif (
            $locationModel
                ->slugExistsExcept(
                    $data['slug'],
                    $locationId
                )
        ) {
            $errors['slug'] =
                'Slug này đã được địa điểm khác sử dụng.';
        }

        $data['image'] =
            $existingLocation['image'];

        if (!empty($errors)) {
            $this->view(
                'admin/locations/edit',
                [
                    'title' =>
                        'Chỉnh sửa địa điểm - TourCompare Admin',

                    'styles' => [
                        'css/admin.css',
                        'css/admin-location-form.css'
                    ],

                    'locationId' =>
                        $locationId,

                    'errors' =>
                        $errors,

                    'old' =>
                        array_merge(
                            $existingLocation,
                            $data
                        )
                ],
                'admin'
            );

            return;
        }

        $newImage = null;
        $oldImage =
            $existingLocation['image'];

        try {
            if (
                isset($_POST['remove_image'])
            ) {
                $data['image'] = null;
            }

            $newImage =
                $this->uploadImage();

            if ($newImage !== null) {
                $data['image'] =
                    $newImage;
            }

            $locationModel->updateLocation(
                $locationId,
                $this->prepareLocationData(
                    $data
                )
            );

            if (
                $oldImage !== null
                && $oldImage !== ''
                && $oldImage !== $data['image']
            ) {
                $this->deleteImageFile(
                    $oldImage
                );
            }

            $_SESSION[
                'admin_location_success'
            ] =
                'Đã cập nhật địa điểm #'
                . $locationId
                . ' thành công.';
        } catch (Throwable $error) {
            if ($newImage !== null) {
                $this->deleteImageFile(
                    $newImage
                );
            }

            $_SESSION[
                'admin_location_error'
            ] =
                $error->getMessage();
        }

        $this->redirect(
            'admin/locations'
        );
    }

    public function disable(
        string $id
    ): void {
        $this->requireAdmin();

        $locationId =
            $this->positiveInt($id);

        if ($locationId === 0) {
            $this->notFound();
        }

        $locationModel =
            new AdminLocation();

        $location =
            $locationModel->findById(
                $locationId
            );

        if ($location === null) {
            $this->notFound();
        }

        if (
            $location['status']
            === 'inactive'
        ) {
            $_SESSION[
                'admin_location_error'
            ] =
                'Địa điểm này đã ở trạng thái tạm ẩn.';

            $this->redirect(
                'admin/locations'
            );
        }

        try {
            $locationModel
                ->disableLocation(
                    $locationId
                );

            $_SESSION[
                'admin_location_success'
            ] =
                'Đã vô hiệu hóa địa điểm "'
                . $location[
                    'location_name'
                ]
                . '".';
        } catch (Throwable $error) {
            $_SESSION[
                'admin_location_error'
            ] =
                'Không thể vô hiệu hóa địa điểm.';
        }

        $this->redirect(
            'admin/locations'
        );
    }

    public function delete(
        string $id
    ): void {
        $this->requireAdmin();

        $locationId =
            $this->positiveInt($id);

        if ($locationId === 0) {
            $this->notFound();
        }

        $locationModel =
            new AdminLocation();

        $location =
            $locationModel->findById(
                $locationId
            );

        if ($location === null) {
            $this->notFound();
        }

        $departureCount =
            $locationModel
                ->getDepartureTourCount(
                    $locationId
                );

        $destinationCount =
            $locationModel
                ->getDestinationTourCount(
                    $locationId
                );

        if (
            $departureCount > 0
            || $destinationCount > 0
        ) {
            $_SESSION[
                'admin_location_error'
            ] =
                'Không thể xóa địa điểm "'
                . $location[
                    'location_name'
                ]
                . '" vì đang được sử dụng bởi '
                . $departureCount
                . ' Tour khởi hành và '
                . $destinationCount
                . ' lượt điểm đến.';

            $this->redirect(
                'admin/locations'
            );
        }

        try {
            $locationModel
                ->deleteLocation(
                    $locationId
                );

            if (
                !empty(
                    $location['image']
                )
            ) {
                $this->deleteImageFile(
                    $location['image']
                );
            }

            $_SESSION[
                'admin_location_success'
            ] =
                'Đã xóa địa điểm #'
                . $locationId
                . ' thành công.';
        } catch (Throwable $error) {
            $_SESSION[
                'admin_location_error'
            ] =
                'Không thể xóa địa điểm.';
        }

        $this->redirect(
            'admin/locations'
        );
    }

    

    private function collectLocationFormData(): array
    {
        return [
            'location_name' => trim(
                $_POST['location_name']
                ?? ''
            ),

            'slug' => trim(
                $_POST['slug']
                ?? ''
            ),

            'province_city' => trim(
                $_POST['province_city']
                ?? ''
            ),

            'country' => trim(
                $_POST['country']
                ?? 'Việt Nam'
            ),

            'address' => trim(
                $_POST['address']
                ?? ''
            ),

            'latitude' => trim(
                $_POST['latitude']
                ?? ''
            ),

            'longitude' => trim(
                $_POST['longitude']
                ?? ''
            ),

            'description' => trim(
                $_POST['description']
                ?? ''
            ),

            'image' => null,

            'status' => trim(
                $_POST['status']
                ?? ''
            )
        ];
    }

    private function validateLocationData(
        array $data
    ): array {
        $errors = [];

        if (
            $data['location_name']
            === ''
        ) {
            $errors['location_name'] =
                'Vui lòng nhập tên địa điểm.';
        } elseif (
            mb_strlen(
                $data['location_name']
            ) < 2
        ) {
            $errors['location_name'] =
                'Tên địa điểm phải có ít nhất 2 ký tự.';
        } elseif (
            mb_strlen(
                $data['location_name']
            ) > 150
        ) {
            $errors['location_name'] =
                'Tên địa điểm không được vượt quá 150 ký tự.';
        }

        if (
            mb_strlen(
                $data['province_city']
            ) > 100
        ) {
            $errors['province_city'] =
                'Tỉnh/thành phố không được vượt quá 100 ký tự.';
        }

        if ($data['country'] === '') {
            $errors['country'] =
                'Vui lòng nhập quốc gia.';
        } elseif (
            mb_strlen(
                $data['country']
            ) > 100
        ) {
            $errors['country'] =
                'Quốc gia không được vượt quá 100 ký tự.';
        }

        if (
            mb_strlen(
                $data['address']
            ) > 255
        ) {
            $errors['address'] =
                'Địa chỉ không được vượt quá 255 ký tự.';
        }

        if ($data['latitude'] !== '') {
            if (
                !is_numeric(
                    $data['latitude']
                )
            ) {
                $errors['latitude'] =
                    'Vĩ độ phải là số.';
            } else {
                $latitude =
                    (float) $data[
                        'latitude'
                    ];

                if (
                    $latitude < -90
                    || $latitude > 90
                ) {
                    $errors['latitude'] =
                        'Vĩ độ phải nằm trong khoảng -90 đến 90.';
                }
            }
        }

        if ($data['longitude'] !== '') {
            if (
                !is_numeric(
                    $data['longitude']
                )
            ) {
                $errors['longitude'] =
                    'Kinh độ phải là số.';
            } else {
                $longitude =
                    (float) $data[
                        'longitude'
                    ];

                if (
                    $longitude < -180
                    || $longitude > 180
                ) {
                    $errors['longitude'] =
                        'Kinh độ phải nằm trong khoảng -180 đến 180.';
                }
            }
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

    private function prepareLocationData(
        array $data
    ): array {
        return [
            'location_name' =>
                $data['location_name'],

            'slug' =>
                $data['slug'],

            'province_city' =>
                $data['province_city'] !== ''
                    ? $data['province_city']
                    : null,

            'country' =>
                $data['country'],

            'address' =>
                $data['address'] !== ''
                    ? $data['address']
                    : null,

            'latitude' =>
                $data['latitude'] !== ''
                    ? (float) $data['latitude']
                    : null,

            'longitude' =>
                $data['longitude'] !== ''
                    ? (float) $data['longitude']
                    : null,

            'description' =>
                $data['description'] !== ''
                    ? $data['description']
                    : null,

            'image' =>
                !empty($data['image'])
                    ? $data['image']
                    : null,

            'status' =>
                $data['status']
        ];
    }

    private function emptyLocationForm(): array
    {
        return [
            'location_name' => '',
            'slug' => '',
            'province_city' => '',
            'country' => 'Việt Nam',
            'address' => '',
            'latitude' => '',
            'longitude' => '',
            'description' => '',
            'image' => null,
            'status' => 'active'
        ];
    }

    private function uploadImage(): ?string
    {
        if (
            !isset($_FILES['image'])
            || (
                $_FILES['image']['error']
                ?? UPLOAD_ERR_NO_FILE
            ) === UPLOAD_ERR_NO_FILE
        ) {
            return null;
        }

        $file =
            $_FILES['image'];

        if (
            $file['error']
            !== UPLOAD_ERR_OK
        ) {
            throw new RuntimeException(
                'Ảnh tải lên không thành công.'
            );
        }

        if (
            (int) $file['size']
            > 5 * 1024 * 1024
        ) {
            throw new RuntimeException(
                'Ảnh không được vượt quá 5MB.'
            );
        }

        $imageInfo =
            getimagesize(
                $file['tmp_name']
            );

        if ($imageInfo === false) {
            throw new RuntimeException(
                'File tải lên không phải hình ảnh hợp lệ.'
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
                'Chỉ chấp nhận ảnh JPG, PNG hoặc WEBP.'
            );
        }

        $uploadDirectory =
            __DIR__
            . '/../../public/uploads/locations';

        if (
            !is_dir(
                $uploadDirectory
            )
            && !mkdir(
                $uploadDirectory,
                0755,
                true
            )
            && !is_dir(
                $uploadDirectory
            )
        ) {
            throw new RuntimeException(
                'Không thể tạo thư mục lưu ảnh.'
            );
        }

        $extension =
            $allowedTypes[
                $imageType
            ];

        $fileName =
            'location_'
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
                'Không thể lưu ảnh.'
            );
        }

        return
            '/uploads/locations/'
            . $fileName;
    }

    private function deleteImageFile(
        string $image
    ): void {
        $normalized =
            str_replace(
                '\\',
                '/',
                $image
            );

        $prefix =
            '/uploads/locations/';

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
            . '/../../public/uploads/locations/'
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

        echo '<h1>404 - Không tìm thấy địa điểm.</h1>';

        exit;
    }
}