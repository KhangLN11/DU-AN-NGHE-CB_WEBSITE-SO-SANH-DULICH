<?php

require_once __DIR__ . '/../models/Tour.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Location.php';
require_once __DIR__ . '/../models/Company.php';
require_once __DIR__ . '/../models/Favorite.php';

class TourController extends Controller
{
    public function index(): void
    {
        $tourModel = new Tour();
        $categoryModel = new Category();
        $locationModel = new Location();
        $companyModel = new Company();

        $filters = [
            'keyword' => trim($_GET['keyword'] ?? ''),
            'category' => $this->positiveInt($_GET['category'] ?? null),
            'location' => $this->positiveInt($_GET['location'] ?? null),
            'company' => $this->positiveInt($_GET['company'] ?? null),
            'min_price' => $this->priceValue($_GET['min_price'] ?? null),
            'max_price' => $this->priceValue($_GET['max_price'] ?? null)
        ];

        if (
            $filters['min_price'] !== null
            && $filters['max_price'] !== null
            && $filters['min_price'] > $filters['max_price']
        ) {
            [
                $filters['min_price'],
                $filters['max_price']
            ] = [
                $filters['max_price'],
                $filters['min_price']
            ];
        }

        $perPage = 6;

        $page = $this->positiveInt($_GET['page'] ?? 1);

        if ($page < 1) {
            $page = 1;
        }

        $totalTours = $tourModel->countFilteredTours($filters);

        $totalPages = max(
            1,
            (int) ceil($totalTours / $perPage)
        );

        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $offset = ($page - 1) * $perPage;

        $tours = $tourModel->getPaginatedTours(
            $filters,
            $perPage,
            $offset
        );

        $categories = $categoryModel->getActiveCategories();
        $locations = $locationModel->getActiveLocations();
        $companies = $companyModel->getActiveCompanies();

        $favoriteTourIds = [];

if (!empty($_SESSION['user_id'])) {
    $favoriteModel = new Favorite();

    $favoriteTourIds =
        $favoriteModel->getFavoriteTourIds(
            (int) $_SESSION['user_id']
        );
}
        
        $this->view('tours/index', [
            'title' => 'Tour du lịch - TourCompare',
            'description' => 'Tìm kiếm và lọc các tour du lịch từ nhiều đơn vị lữ hành.',
            'styles' => [
                'css/tours.css'
            ],
            'tours' => $tours,
            'categories' => $categories,
            'locations' => $locations,
            'companies' => $companies,
            'filters' => $filters,
            'favoriteTourIds' => $favoriteTourIds,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalTours' => $totalTours
        ]);
    }

    public function show(string $id): void
    {
        $tourId = filter_var(
            $id,
            FILTER_VALIDATE_INT,
            [
                'options' => [
                    'min_range' => 1
                ]
            ]
        );

        if ($tourId === false) {
            http_response_code(404);

            echo '<h1>404 - Không tìm thấy Tour</h1>';
            return;
        }

        $tourModel = new Tour();

        $tour = $tourModel->findById($tourId);

        if ($tour === null) {
            http_response_code(404);

            echo '<h1>404 - Không tìm thấy Tour</h1>';
            return;
        }

        $images = $tourModel->getTourImages($tourId);
        $schedules = $tourModel->getTourSchedules($tourId);
        $locations = $tourModel->getTourLocations($tourId);

        $relatedTours = $tourModel->getRelatedTours(
            $tourId,
            (int) $tour['category_id'],
            3
        );

        $isFavorite = false;

if (!empty($_SESSION['user_id'])) {
    $favoriteModel = new Favorite();

    $isFavorite = $favoriteModel->exists(
        (int) $_SESSION['user_id'],
        $tourId
    );
}

        $this->view('tours/show', [
            'title' => $tour['tour_name'] . ' - TourCompare',
            'description' => $tour['short_description']
                ?? 'Thông tin chi tiết Tour du lịch.',
            'externalStyles' => [
                'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css'
            ],
            'externalScripts' => [
                'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js'
            ],
            'styles' => [
                'css/tour-details.css'
            ],
            'scripts' => [
                'js/tour-details.js'
            ],
            'tour' => $tour,
            'images' => $images,
            'schedules' => $schedules,
            'locations' => $locations,
            'isFavorite' => $isFavorite,
            'relatedTours' => $relatedTours
        ]);
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

    private function priceValue(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        $value = str_replace(
            ['.', ',', ' '],
            '',
            (string) $value
        );

        if (!is_numeric($value)) {
            return null;
        }

        $price = (float) $value;

        return $price >= 0 ? $price : null;
    }
}