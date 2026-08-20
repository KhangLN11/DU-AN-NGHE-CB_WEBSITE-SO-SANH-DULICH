<?php

require_once __DIR__ . '/AdminBaseController.php';
require_once __DIR__ . '/../models/AdminUser.php';

class AdminUserController extends AdminBaseController
{
    public function index(): void
    {
        $this->requireAdmin();

        $userModel =
            new AdminUser();

        $filters = [
            'keyword' => trim(
                $_GET['keyword']
                ?? ''
            ),

            'role' =>
                $this->positiveInt(
                    $_GET['role']
                    ?? null
                ),

            'status' => trim(
                $_GET['status']
                ?? ''
            )
        ];

        $allowedStatuses = [
            '',
            'active',
            'inactive',
            'blocked'
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

        $roles =
            $userModel->getRoles();

        $validRoleIds = array_map(
            function (array $role): int {
                return (int) $role[
                    'role_id'
                ];
            },
            $roles
        );

        if (
            $filters['role'] > 0
            && !in_array(
                $filters['role'],
                $validRoleIds,
                true
            )
        ) {
            $filters['role'] = 0;
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

        $totalUsers =
            $userModel->countUsers(
                $filters
            );

        $totalPages = max(
            1,
            (int) ceil(
                $totalUsers
                / $perPage
            )
        );

        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $offset =
            ($page - 1)
            * $perPage;

        $users =
            $userModel
                ->getPaginatedUsers(
                    $filters,
                    $perPage,
                    $offset
                );

        $successMessage =
            $_SESSION[
                'admin_user_success'
            ]
            ?? null;

        $errorMessage =
            $_SESSION[
                'admin_user_error'
            ]
            ?? null;

        unset(
            $_SESSION[
                'admin_user_success'
            ],
            $_SESSION[
                'admin_user_error'
            ]
        );

        $this->view(
            'admin/users/index',
            [
                'title' =>
                    'Quản lý người dùng - TourCompare Admin',

                'styles' => [
                    'css/admin.css',
                    'css/admin-users.css'
                ],

                'users' =>
                    $users,

                'roles' =>
                    $roles,

                'filters' =>
                    $filters,

                'currentPage' =>
                    $page,

                'totalPages' =>
                    $totalPages,

                'totalUsers' =>
                    $totalUsers,

                'successMessage' =>
                    $successMessage,

                'errorMessage' =>
                    $errorMessage
            ],
            'admin'
        );
    }

    public function detail(
        string $id
    ): void {
        $this->requireAdmin();

        $userId =
            $this->positiveInt($id);

        if ($userId === 0) {
            $this->notFound();
        }

        $userModel =
            new AdminUser();

        $user =
            $userModel->findById(
                $userId
            );

        if ($user === null) {
            $this->notFound();
        }

        $this->view(
            'admin/users/detail',
            [
                'title' =>
                    'Chi tiết người dùng - TourCompare Admin',

                'styles' => [
                    'css/admin.css',
                    'css/admin-user-detail.css'
                ],

                'user' =>
                    $user
            ],
            'admin'
        );
    }

    public function changeStatus(
        string $id
    ): void {
        $this->requireAdmin();

        $userId =
            $this->positiveInt($id);

        if ($userId === 0) {
            $this->notFound();
        }

        $userModel =
            new AdminUser();

        $user =
            $userModel->findById(
                $userId
            );

        if ($user === null) {
            $this->notFound();
        }

        $status = trim(
            $_POST['status']
            ?? ''
        );

        $allowedStatuses = [
            'active',
            'inactive',
            'blocked'
        ];

        if (
            !in_array(
                $status,
                $allowedStatuses,
                true
            )
        ) {
            $_SESSION[
                'admin_user_error'
            ] =
                'Trạng thái tài khoản không hợp lệ.';

            $this->redirect(
                'admin/users'
            );
        }

        try {
            $userModel->updateStatus(
                $userId,
                $status
            );

            $_SESSION[
                'admin_user_success'
            ] =
                'Đã cập nhật trạng thái tài khoản "'
                . $user['full_name']
                . '".';
        } catch (Throwable $error) {
            $_SESSION[
                'admin_user_error'
            ] =
                'Không thể cập nhật trạng thái tài khoản.';
        }

        $this->redirect(
            'admin/users'
        );
    }

    public function delete(
    string $id
): void {
    $this->requireAdmin();

    $userId =
        $this->positiveInt($id);

    if ($userId === 0) {
        $this->notFound();
    }

    $userModel =
        new AdminUser();

    $user =
        $userModel->findById(
            $userId
        );

    if ($user === null) {
        $this->notFound();
    }

    $currentUserId =
        (int) (
            $_SESSION['user_id']
            ?? 0
        );

    if (
        $userId === $currentUserId
    ) {
        $_SESSION[
            'admin_user_error'
        ] =
            'Bạn không thể tự xóa tài khoản đang đăng nhập.';

        $this->redirect(
            'admin/users'
        );
    }

    $usage =
        $userModel->getUserUsage(
            $userId
        );

    if (
        !$userModel->canDeleteUser(
            $userId
        )
    ) {
        $_SESSION[
            'admin_user_error'
        ] =
            'Không thể xóa người dùng "'
            . $user['full_name']
            . '" vì vẫn còn dữ liệu liên quan.';

        $this->redirect(
            'admin/users'
        );
    }

    try {
        $userModel->deleteUser(
            $userId
        );

        if (
            !empty(
                $user['avatar']
            )
        ) {
            $this->deleteAvatarFile(
                $user['avatar']
            );
        }

        $_SESSION[
            'admin_user_success'
        ] =
            'Đã xóa người dùng #'
            . $userId
            . ' thành công.';
    } catch (Throwable $error) {
        $_SESSION[
            'admin_user_error'
        ] =
            'Không thể xóa người dùng.';
    }

    $this->redirect(
        'admin/users'
    );
}

    private function deleteAvatarFile(
    string $avatar
): void {
    $normalized =
        str_replace(
            '\\',
            '/',
            $avatar
        );

    $prefix =
        '/uploads/avatars/';

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
        . '/../../public/uploads/avatars/'
        . basename(
            $normalized
        );

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

    private function notFound(): never
    {
        http_response_code(404);

        echo '<h1>404 - Không tìm thấy người dùng.</h1>';

        exit;
    }
}