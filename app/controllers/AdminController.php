<?php

require_once __DIR__ . '/../models/AdminDashboard.php';

class AdminController extends Controller
{
    public function dashboard(): void
    {
        $this->requireAdmin();

        $dashboardModel =
            new AdminDashboard();

        $statistics =
            $dashboardModel->getStatistics();

        $latestTours =
            $dashboardModel->getLatestTours(5);

        $latestContacts =
            $dashboardModel->getLatestContacts(5);

        $this->view(
            'admin/dashboard',
            [
                'title' =>
                    'Dashboard - TourCompare Admin',

                'styles' => [
                    'css/admin.css'
                ],

                'statistics' =>
                    $statistics,

                'latestTours' =>
                    $latestTours,

                'latestContacts' =>
                    $latestContacts
            ],
            'admin'
        );
    }

    private function requireAdmin(): void
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