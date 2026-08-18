<?php

class AdminBaseController extends Controller
{
    protected function requireAdmin(): void
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        if (
            ($_SESSION['role_name'] ?? '')
            !== 'ADMIN'
        ) {
            http_response_code(403);

            echo '<h1>403 - Bạn không có quyền truy cập khu vực quản trị.</h1>';

            exit;
        }
    }
}