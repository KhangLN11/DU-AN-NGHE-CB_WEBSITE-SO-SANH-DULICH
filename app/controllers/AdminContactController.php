<?php

require_once __DIR__ . '/AdminBaseController.php';
require_once __DIR__ . '/../models/AdminContact.php';

class AdminContactController extends AdminBaseController
{
    public function index(): void
    {
        $this->requireAdmin();

        $contactModel =
            new AdminContact();

        $filters = [
            'keyword' => trim(
                $_GET['keyword']
                ?? ''
            ),

            'status' => trim(
                $_GET['status']
                ?? ''
            ),

            'source' => trim(
                $_GET['source']
                ?? ''
            )
        ];

        $allowedStatuses = [
            '',
            'pending',
            'processing',
            'resolved'
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

        $allowedSources = [
            '',
            'user',
            'guest'
        ];

        if (
            !in_array(
                $filters['source'],
                $allowedSources,
                true
            )
        ) {
            $filters['source'] = '';
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

        $totalContacts =
            $contactModel->countContacts(
                $filters
            );

        $totalPages = max(
            1,
            (int) ceil(
                $totalContacts
                / $perPage
            )
        );

        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $offset =
            ($page - 1)
            * $perPage;

        $contacts =
            $contactModel
                ->getPaginatedContacts(
                    $filters,
                    $perPage,
                    $offset
                );

        $statusCounts =
            $contactModel
                ->getStatusCounts();

        $successMessage =
            $_SESSION[
                'admin_contact_success'
            ]
            ?? null;

        $errorMessage =
            $_SESSION[
                'admin_contact_error'
            ]
            ?? null;

        unset(
            $_SESSION[
                'admin_contact_success'
            ],
            $_SESSION[
                'admin_contact_error'
            ]
        );

        $this->view(
            'admin/contacts/index',
            [
                'title' =>
                    'Quản lý liên hệ - TourCompare Admin',

                'styles' => [
                    'css/admin.css',
                    'css/admin-contacts.css'
                ],

                'contacts' =>
                    $contacts,

                'filters' =>
                    $filters,

                'statusCounts' =>
                    $statusCounts,

                'currentPage' =>
                    $page,

                'totalPages' =>
                    $totalPages,

                'totalContacts' =>
                    $totalContacts,

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

        $contactId =
            $this->positiveInt($id);

        if ($contactId === 0) {
            $this->notFound();
        }

        $contactModel =
            new AdminContact();

        $contact =
            $contactModel->findById(
                $contactId
            );

        if ($contact === null) {
            $this->notFound();
        }

        $successMessage =
            $_SESSION[
                'admin_contact_success'
            ]
            ?? null;

        $errorMessage =
            $_SESSION[
                'admin_contact_error'
            ]
            ?? null;

        unset(
            $_SESSION[
                'admin_contact_success'
            ],
            $_SESSION[
                'admin_contact_error'
            ]
        );

        $this->view(
            'admin/contacts/detail',
            [
                'title' =>
                    'Chi tiết liên hệ - TourCompare Admin',

                'styles' => [
                    'css/admin.css',
                    'css/admin-contact-detail.css'
                ],

                'contact' =>
                    $contact,

                'successMessage' =>
                    $successMessage,

                'errorMessage' =>
                    $errorMessage
            ],
            'admin'
        );
    }

    public function updateManagement(
        string $id
    ): void {
        $this->requireAdmin();

        $contactId =
            $this->positiveInt($id);

        if ($contactId === 0) {
            $this->notFound();
        }

        $contactModel =
            new AdminContact();

        $contact =
            $contactModel->findById(
                $contactId
            );

        if ($contact === null) {
            $this->notFound();
        }

        $status = trim(
            $_POST['status']
            ?? ''
        );

        $adminNote = trim(
            $_POST['admin_note']
            ?? ''
        );

        $allowedStatuses = [
            'pending',
            'processing',
            'resolved'
        ];

        if (
            !in_array(
                $status,
                $allowedStatuses,
                true
            )
        ) {
            $_SESSION[
                'admin_contact_error'
            ] =
                'Trạng thái liên hệ không hợp lệ.';

            $this->redirect(
                'admin/contacts/'
                . $contactId
            );
        }

        if (
            mb_strlen(
                $adminNote
            ) > 10000
        ) {
            $_SESSION[
                'admin_contact_error'
            ] =
                'Ghi chú quản trị quá dài.';

            $this->redirect(
                'admin/contacts/'
                . $contactId
            );
        }

        try {
            $contactModel
                ->updateContactManagement(
                    $contactId,
                    $status,
                    $adminNote !== ''
                        ? $adminNote
                        : null
                );

            $_SESSION[
                'admin_contact_success'
            ] =
                'Đã cập nhật liên hệ #'
                . $contactId
                . '.';
        } catch (Throwable $error) {
            $_SESSION[
                'admin_contact_error'
            ] =
                'Không thể cập nhật liên hệ.';
        }

        $this->redirect(
            'admin/contacts/'
            . $contactId
        );
    }

    public function delete(
        string $id
    ): void {
        $this->requireAdmin();

        $contactId =
            $this->positiveInt($id);

        if ($contactId === 0) {
            $this->notFound();
        }

        $contactModel =
            new AdminContact();

        $contact =
            $contactModel->findById(
                $contactId
            );

        if ($contact === null) {
            $this->notFound();
        }

        try {
            $contactModel
                ->deleteContact(
                    $contactId
                );

            $_SESSION[
                'admin_contact_success'
            ] =
                'Đã xóa liên hệ #'
                . $contactId
                . ' thành công.';
        } catch (Throwable $error) {
            $_SESSION[
                'admin_contact_error'
            ] =
                'Không thể xóa liên hệ.';
        }

        $this->redirect(
            'admin/contacts'
        );
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

        echo '<h1>404 - Không tìm thấy liên hệ.</h1>';

        exit;
    }
}