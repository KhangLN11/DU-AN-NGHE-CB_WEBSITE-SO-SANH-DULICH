<?php

require_once __DIR__ . '/../models/User.php';

class AuthController extends Controller
{
    public function register(): void
    {
        if (!empty($_SESSION['user_id'])) {
            $this->redirect('');
        }

        $this->view('auth/register', [
            'title' => 'Đăng ký - TourCompare',
            'description' => 'Tạo tài khoản TourCompare.',
            'styles' => [
                'css/auth.css'
            ],
            'scripts' => [
                'js/auth.js'
            ],
            'errors' => [],
            'old' => [
                'full_name' => '',
                'email' => '',
                'phone' => ''
            ]
        ]);
    }

    public function storeRegister(): void
    {
        if (!empty($_SESSION['user_id'])) {
            $this->redirect('');
        }

        $fullName = trim(
            $_POST['full_name'] ?? ''
        );

        $email = strtolower(
            trim(
                $_POST['email'] ?? ''
            )
        );

        $phone = trim(
            $_POST['phone'] ?? ''
        );

        $password = $_POST['password'] ?? '';

        $passwordConfirmation =
            $_POST['password_confirmation'] ?? '';

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

        if ($email === '') {
            $errors['email'] =
                'Vui lòng nhập email.';
        } elseif (
            !filter_var(
                $email,
                FILTER_VALIDATE_EMAIL
            )
        ) {
            $errors['email'] =
                'Email không hợp lệ.';
        } elseif (mb_strlen($email) > 150) {
            $errors['email'] =
                'Email không được vượt quá 150 ký tự.';
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

        if ($password === '') {
            $errors['password'] =
                'Vui lòng nhập mật khẩu.';
        } elseif (strlen($password) < 6) {
            $errors['password'] =
                'Mật khẩu phải có ít nhất 6 ký tự.';
        } elseif (strlen($password) > 72) {
            $errors['password'] =
                'Mật khẩu không được vượt quá 72 ký tự.';
        }

        if ($passwordConfirmation === '') {
            $errors['password_confirmation'] =
                'Vui lòng xác nhận mật khẩu.';
        } elseif ($password !== $passwordConfirmation) {
            $errors['password_confirmation'] =
                'Mật khẩu xác nhận không khớp.';
        }

        $userModel = new User();

        if (
            !isset($errors['email'])
            && $userModel->findByEmail($email) !== null
        ) {
            $errors['email'] =
                'Email này đã được sử dụng.';
        }

        if (!empty($errors)) {
            $this->view('auth/register', [
                'title' => 'Đăng ký - TourCompare',
                'description' => 'Tạo tài khoản TourCompare.',
                'styles' => [
                    'css/auth.css'
                ],
                'scripts' => [
                    'js/auth.js'
                ],
                'errors' => $errors,
                'old' => [
                    'full_name' => $fullName,
                    'email' => $email,
                    'phone' => $phone
                ]
            ]);

            return;
        }

        $roleId = $userModel->getUserRoleId();

        if ($roleId === null) {
            throw new Exception(
                'Không tìm thấy vai trò USER.'
            );
        }

        $passwordHash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $userModel->create([
            'role_id' => $roleId,
            'full_name' => $fullName,
            'email' => $email,
            'phone' => $phone !== ''
                ? $phone
                : null,
            'password_hash' => $passwordHash
        ]);

        $_SESSION['register_success'] =
            'Đăng ký tài khoản thành công. Vui lòng đăng nhập.';

        $this->redirect('login');
    }

    public function login(): void
    {
        if (!empty($_SESSION['user_id'])) {
            $this->redirect('');
        }

        $successMessage =
            $_SESSION['register_success']
            ?? null;

        unset($_SESSION['register_success']);

        $this->view('auth/login', [
            'title' => 'Đăng nhập - TourCompare',
            'description' => 'Đăng nhập vào tài khoản TourCompare.',
            'styles' => [
                'css/auth.css'
            ],
            'scripts' => [
                'js/auth.js'
            ],
            'errors' => [],
            'old' => [
                'email' => ''
            ],
            'successMessage' => $successMessage
        ]);
    }

    public function authenticate(): void
    {
        if (!empty($_SESSION['user_id'])) {
            $this->redirect('');
        }

        $email = strtolower(
            trim(
                $_POST['email'] ?? ''
            )
        );

        $password = $_POST['password'] ?? '';

        $errors = [];

        if ($email === '') {
            $errors['email'] =
                'Vui lòng nhập email.';
        } elseif (
            !filter_var(
                $email,
                FILTER_VALIDATE_EMAIL
            )
        ) {
            $errors['email'] =
                'Email không hợp lệ.';
        }

        if ($password === '') {
            $errors['password'] =
                'Vui lòng nhập mật khẩu.';
        }

        $user = null;

        if (empty($errors)) {
            $userModel = new User();

            $user = $userModel->findByEmail($email);

            if (
                $user === null
                || !password_verify(
                    $password,
                    $user['password_hash']
                )
            ) {
                $errors['login'] =
                    'Email hoặc mật khẩu không chính xác.';
            } elseif ($user['status'] !== 'active') {
                $errors['login'] =
                    'Tài khoản hiện không thể đăng nhập.';
            }
        }

        if (!empty($errors)) {
            $this->view('auth/login', [
                'title' => 'Đăng nhập - TourCompare',
                'description' => 'Đăng nhập vào tài khoản TourCompare.',
                'styles' => [
                    'css/auth.css'
                ],
                'scripts' => [
                    'js/auth.js'
                ],
                'errors' => $errors,
                'old' => [
                    'email' => $email
                ],
                'successMessage' => null
            ]);

            return;
        }

        session_regenerate_id(true);

        $_SESSION['user_id'] =
            (int) $user['user_id'];

        $_SESSION['user_name'] =
            $user['full_name'];

        $_SESSION['user_email'] =
            $user['email'];

        $_SESSION['user_avatar'] =
            $user['avatar'];

        $_SESSION['role_id'] =
            (int) $user['role_id'];

        $_SESSION['role_name'] =
            $user['role_name'];

        $this->redirect('');
    }

    public function logout(): void
    {
        unset(
            $_SESSION['user_id'],
            $_SESSION['user_name'],
            $_SESSION['user_email'],
            $_SESSION['user_avatar'],
            $_SESSION['role_id'],
            $_SESSION['role_name']
        );

        session_regenerate_id(true);

        $this->redirect('');
    }
}