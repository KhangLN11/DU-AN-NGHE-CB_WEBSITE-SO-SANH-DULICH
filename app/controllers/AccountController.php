<?php

require_once __DIR__ . '/../models/User.php';

class AccountController extends Controller
{
    public function profile(): void
    {
        $this->requireAuthentication();

        $userModel = new User();

        $user = $userModel->findById(
            (int) $_SESSION['user_id']
        );

        if ($user === null) {
            $this->destroyAuthentication();
            $this->redirect('login');
        }

        $successMessage =
            $_SESSION['profile_success']
            ?? null;

        unset($_SESSION['profile_success']);

        $this->view('account/profile', [
            'title' => 'Tài khoản của tôi - TourCompare',
            'description' => 'Quản lý thông tin tài khoản TourCompare.',
            'styles' => [
                'css/account.css'
            ],
            'scripts' => [
                'js/account.js'
            ],
            'user' => $user,
            'errors' => [],
            'successMessage' => $successMessage
        ]);
    }

    public function updateProfile(): void
    {
        $this->requireAuthentication();

        $userModel = new User();

        $user = $userModel->findById(
            (int) $_SESSION['user_id']
        );

        if ($user === null) {
            $this->destroyAuthentication();
            $this->redirect('login');
        }

        $fullName = trim(
            $_POST['full_name'] ?? ''
        );

        $phone = trim(
            $_POST['phone'] ?? ''
        );

        $errors = [];

        if ($fullName === '') {
            $errors['full_name'] =
                'Vui lòng nhập họ và tên.';
        } elseif (mb_strlen($fullName) < 2) {
            $errors['full_name'] =
                'Họ và tên phải có ít nhất 2 ký tự.';
        } elseif (mb_strlen($fullName) > 100) {
            $errors['full_name'] =
                'Họ và tên không được vượt quá 100 ký tự.';
        }

        if ($phone !== '') {
            $normalizedPhone = preg_replace(
                '/[\s.\-()]/',
                '',
                $phone
            );

            if (
                !preg_match(
                    '/^[0-9+]{8,15}$/',
                    $normalizedPhone
                )
            ) {
                $errors['phone'] =
                    'Số điện thoại không hợp lệ.';
            }
        }

        $avatar = $user['avatar'];
        $newAvatarAbsolutePath = null;

        if (
            isset($_FILES['avatar'])
            && $_FILES['avatar']['error']
                !== UPLOAD_ERR_NO_FILE
        ) {
            $avatarResult = $this->handleAvatarUpload(
                $_FILES['avatar']
            );

            if (isset($avatarResult['error'])) {
                $errors['avatar'] =
                    $avatarResult['error'];
            } else {
                $avatar =
                    $avatarResult['path'];

                $newAvatarAbsolutePath =
                    $avatarResult['absolute_path'];
            }
        }

        if (!empty($errors)) {
            if (
                $newAvatarAbsolutePath !== null
                && file_exists($newAvatarAbsolutePath)
            ) {
                unlink($newAvatarAbsolutePath);
            }

            $user['full_name'] = $fullName;
            $user['phone'] = $phone;
            $user['avatar'] = $avatar;

            $this->view('account/profile', [
                'title' => 'Tài khoản của tôi - TourCompare',
                'description' => 'Quản lý thông tin tài khoản TourCompare.',
                'styles' => [
                    'css/account.css'
                ],
                'scripts' => [
                    'js/account.js'
                ],
                'user' => $user,
                'errors' => $errors,
                'successMessage' => null
            ]);

            return;
        }

        $oldAvatar = $user['avatar'];

        $userModel->updateProfile(
            (int) $_SESSION['user_id'],
            $fullName,
            $phone !== ''
                ? $phone
                : null,
            $avatar
        );

        if (
            $newAvatarAbsolutePath !== null
            && !empty($oldAvatar)
            && $oldAvatar !== $avatar
        ) {
            $this->deleteOldAvatar(
                $oldAvatar
            );
        }

        $_SESSION['user_name'] =
            $fullName;

        $_SESSION['user_avatar'] =
            $avatar;

        $_SESSION['profile_success'] =
            'Thông tin tài khoản đã được cập nhật.';

        $this->redirect('account');
    }

    private function handleAvatarUpload(
        array $file
    ): array {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return [
                'error' => 'Không thể tải ảnh lên.'
            ];
        }

        if ($file['size'] > 2 * 1024 * 1024) {
            return [
                'error' => 'Ảnh đại diện không được vượt quá 2MB.'
            ];
        }

        $imageInfo = getimagesize(
            $file['tmp_name']
        );

        if ($imageInfo === false) {
            return [
                'error' => 'File tải lên không phải hình ảnh hợp lệ.'
            ];
        }

        $allowedTypes = [
            IMAGETYPE_JPEG => 'jpg',
            IMAGETYPE_PNG => 'png',
            IMAGETYPE_WEBP => 'webp'
        ];

        $imageType = $imageInfo[2];

        if (!isset($allowedTypes[$imageType])) {
            return [
                'error' => 'Chỉ chấp nhận ảnh JPG, PNG hoặc WEBP.'
            ];
        }

        $uploadDirectory =
            __DIR__
            . '/../../public/uploads/avatars';

        if (!is_dir($uploadDirectory)) {
            mkdir(
                $uploadDirectory,
                0755,
                true
            );
        }

        $extension =
            $allowedTypes[$imageType];

        $fileName =
            'avatar_'
            . (int) $_SESSION['user_id']
            . '_'
            . bin2hex(random_bytes(8))
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
            return [
                'error' => 'Không thể lưu ảnh đại diện.'
            ];
        }

        return [
            'path' =>
                '/uploads/avatars/'
                . $fileName,
            'absolute_path' =>
                $destination
        ];
    }

    private function deleteOldAvatar(
        string $avatar
    ): void {
        $prefix = '/uploads/avatars/';

        if (
            !str_starts_with(
                $avatar,
                $prefix
            )
        ) {
            return;
        }

        $fileName = basename($avatar);

        $filePath =
            __DIR__
            . '/../../public/uploads/avatars/'
            . $fileName;

        if (is_file($filePath)) {
            unlink($filePath);
        }
    }

    private function requireAuthentication(): void
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }
    }

    private function destroyAuthentication(): void
    {
        unset(
            $_SESSION['user_id'],
            $_SESSION['user_name'],
            $_SESSION['user_email'],
            $_SESSION['user_avatar'],
            $_SESSION['role_id'],
            $_SESSION['role_name']
        );
    }
}