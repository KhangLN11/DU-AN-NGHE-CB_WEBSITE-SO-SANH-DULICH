<?php

require_once __DIR__ . '/../models/Tour.php';

class CompareController extends Controller
{
    public function index(): void
    {
        $selectedIds = $this->getSelectedTourIds();

        $tourModel = new Tour();

        $tours = $tourModel->getToursForCompare(
            $selectedIds
        );

        $validIds = array_map(
            function ($tour) {
                return (int) $tour['tour_id'];
            },
            $tours
        );

        $_SESSION['compare_tours'] = $validIds;

        $this->view('compare/index', [
            'title' => 'So sánh Tour - TourCompare',
            'description' => 'So sánh từ hai đến ba Tour du lịch trên cùng một bảng.',
            'styles' => [
                'css/compare.css'
            ],
            'tours' => $tours,
            'selectedCount' => count($tours)
        ]);
    }

    public function add(): void
    {
        $tourId = $this->positiveInt(
            $_POST['tour_id'] ?? null
        );

        $returnTo = $this->safeReturnTo(
            $_POST['return_to'] ?? 'compare'
        );

        if ($tourId === 0) {
            $this->redirect($returnTo);
        }

        $tourModel = new Tour();

        $tour = $tourModel->findById($tourId);

        if ($tour === null) {
            $this->redirect($returnTo);
        }

        $selectedIds = $this->getSelectedTourIds();

        if (!in_array($tourId, $selectedIds, true)) {
            if (count($selectedIds) < 3) {
                $selectedIds[] = $tourId;
            }
        }

        $_SESSION['compare_tours'] = $selectedIds;

        $this->redirect($returnTo);
    }

    public function remove(): void
    {
        $tourId = $this->positiveInt(
            $_POST['tour_id'] ?? null
        );

        $selectedIds = $this->getSelectedTourIds();

        $selectedIds = array_values(
            array_filter(
                $selectedIds,
                function ($id) use ($tourId) {
                    return $id !== $tourId;
                }
            )
        );

        $_SESSION['compare_tours'] = $selectedIds;

        $this->redirect('compare');
    }

    public function clear(): void
    {
        $_SESSION['compare_tours'] = [];

        $this->redirect('compare');
    }

    private function getSelectedTourIds(): array
    {
        $ids = $_SESSION['compare_tours'] ?? [];

        if (!is_array($ids)) {
            return [];
        }

        $ids = array_values(
            array_unique(
                array_filter(
                    array_map(
                        'intval',
                        $ids
                    ),
                    function ($id) {
                        return $id > 0;
                    }
                )
            )
        );

        return array_slice($ids, 0, 3);
    }

    private function positiveInt(mixed $value): int
    {
        $number = filter_var(
            $value,
            FILTER_VALIDATE_INT,
            [
                'options' => [
                    'min_range' => 1
                ]
            ]
        );

        return $number === false ? 0 : $number;
    }

    private function safeReturnTo(string $returnTo): string
    {
        $returnTo = ltrim(
            trim($returnTo),
            '/'
        );

        if ($returnTo === '') {
            return 'compare';
        }

        if (
            preg_match(
                '#^tours(?:/\d+)?(?:\?.*)?$#',
                $returnTo
            )
        ) {
            return $returnTo;
        }

        if ($returnTo === 'compare') {
            return 'compare';
        }

        return 'compare';
    }
}