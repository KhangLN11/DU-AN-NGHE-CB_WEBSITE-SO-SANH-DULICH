<?php

require_once __DIR__ . '/../models/Contact.php';

class ContactController extends Controller
{
    public function index(): void
    {
        $successMessage =
            $_SESSION['contact_success']
            ?? null;

        unset($_SESSION['contact_success']);

        $old = [
            'full_name' => '',
            'email' => '',
            'subject' => '',
            'message' => ''
        ];

        if (!empty($_SESSION['user_id'])) {
            $old['full_name'] =
                $_SESSION['user_name']
                ?? '';

            $old['email'] =
                $_SESSION['user_email']
                ?? '';
        }

        $this->view('contact/index', [
            'title' => 'Liên hệ - TourCompare',
            'description' => 'Gửi liên hệ đến TourCompare.',
            'styles' => [
                'css/contact.css'
            ],
            'errors' => [],
            'old' => $old,
            'successMessage' =>
                $successMessage
        ]);
    }

    public function store(): void
    {
        $fullName = trim(
            $_POST['full_name'] ?? ''
        );

        $email = strtolower(
            trim(
                $_POST['email'] ?? ''
            )
        );

        $subject = trim(
            $_POST['subject'] ?? ''
        );

        $message = trim(
            $_POST['message'] ?? ''
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

        if ($subject === '') {
            $errors['subject'] =
                'Vui lòng nhập chủ đề.';
        } elseif (mb_strlen($subject) < 3) {
            $errors['subject'] =
                'Chủ đề phải có ít nhất 3 ký tự.';
        } elseif (mb_strlen($subject) > 200) {
            $errors['subject'] =
                'Chủ đề không được vượt quá 200 ký tự.';
        }

        if ($message === '') {
            $errors['message'] =
                'Vui lòng nhập nội dung liên hệ.';
        } elseif (mb_strlen($message) < 10) {
            $errors['message'] =
                'Nội dung phải có ít nhất 10 ký tự.';
        } elseif (mb_strlen($message) > 5000) {
            $errors['message'] =
                'Nội dung không được vượt quá 5000 ký tự.';
        }

        if (!empty($errors)) {
            $this->view('contact/index', [
                'title' => 'Liên hệ - TourCompare',
                'description' => 'Gửi liên hệ đến TourCompare.',
                'styles' => [
                    'css/contact.css'
                ],
                'errors' => $errors,
                'old' => [
                    'full_name' => $fullName,
                    'email' => $email,
                    'subject' => $subject,
                    'message' => $message
                ],
                'successMessage' => null
            ]);

            return;
        }

        $contactModel = new Contact();

        $contactModel->create([
            'user_id' =>
                !empty($_SESSION['user_id'])
                    ? (int) $_SESSION['user_id']
                    : null,
            'full_name' => $fullName,
            'email' => $email,
            'subject' => $subject,
            'message' => $message
        ]);

        $_SESSION['contact_success'] =
            'Cảm ơn bạn đã liên hệ. Nội dung của bạn đã được gửi thành công.';

        $this->redirect('contact');
    }
}