<?php

require_once __DIR__ . '/AdminBaseController.php';
require_once __DIR__ . '/../models/AdminDashboard.php';

class AdminController extends AdminBaseController
{
    public function dashboard(): void
    {
        $this->requireAdmin();

        $dashboardModel = new AdminDashboard();

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
}