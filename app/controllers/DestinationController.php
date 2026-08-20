<?php

require_once __DIR__ . '/../models/Location.php';

class DestinationController extends Controller
{
    public function index(): void
    {
        $locationModel =
            new Location();

        $filters = [
            'keyword' => trim(
                $_GET['keyword']
                ?? ''
            ),

            'province' => trim(
                $_GET['province']
                ?? ''
            )
        ];

        $perPage = 8;

        $page =
            $this->positiveInt(
                $_GET['page']
                ?? 1
            );

        if ($page < 1) {
            $page = 1;
        }

        $totalDestinations =
            $locationModel
                ->countDestinations(
                    $filters
                );

        $totalPages = max(
            1,
            (int) ceil(
                $totalDestinations
                / $perPage
            )
        );

        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $offset =
            ($page - 1)
            * $perPage;

        $destinations =
            $locationModel
                ->getPaginatedDestinations(
                    $filters,
                    $perPage,
                    $offset
                );

        $provinces =
            $locationModel
                ->getDestinationProvinces();

        $this->view(
            'destinations/index',
            [
                'title' =>
                    'Điểm đến - TourCompare',

                'description' =>
                    'Khám phá các điểm đến nổi bật và những hành trình phù hợp trên TourCompare.',

                'styles' => [
                    'css/destinations.css'
                ],

                'destinations' =>
                    $destinations,

                'provinces' =>
                    $provinces,

                'filters' =>
                    $filters,

                'currentPage' =>
                    $page,

                'totalPages' =>
                    $totalPages,

                'totalDestinations' =>
                    $totalDestinations
            ]
        );
    }

    public function show(
        string $slug
    ): void {
        $slug = trim($slug);

        if ($slug === '') {
            $this->notFound();
        }

        $locationModel =
            new Location();

        $destination =
            $locationModel
                ->findActiveBySlug(
                    $slug
                );

        if ($destination === null) {
            $this->notFound();
        }

        $hasCoordinates =
            $destination['latitude']
                !== null
            && $destination['longitude']
                !== null;

        $destinationTours =
            $locationModel
                ->getDestinationTours(
                    (int) $destination[
                        'location_id'
                    ],
                    6
                );

        $totalDestinationTours =
            $locationModel
                ->countDestinationTours(
                    (int) $destination[
                        'location_id'
                    ]
                );

        $this->view(
            'destinations/show',
            [
                'title' =>
                    $destination[
                        'location_name'
                    ]
                    . ' - TourCompare',

                'description' =>
                    !empty(
                        $destination[
                            'description'
                        ]
                    )
                        ? mb_substr(
                            strip_tags(
                                $destination[
                                    'description'
                                ]
                            ),
                            0,
                            160
                        )
                        : 'Khám phá điểm đến '
                            . $destination[
                                'location_name'
                            ]
                            . ' trên TourCompare.',

                'externalStyles' =>
                    $hasCoordinates
                        ? [
                            'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css'
                        ]
                        : [],

                'styles' => [
                    'css/destination-detail.css'
                ],

                'externalScripts' =>
                    $hasCoordinates
                        ? [
                            'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js'
                        ]
                        : [],

                'scripts' =>
                    $hasCoordinates
                        ? [
                            'js/destination-detail.js'
                        ]
                        : [],

                'destination' =>
                    $destination,

                'hasCoordinates' =>
                    $hasCoordinates,

                'destinationTours' =>
                    $destinationTours,

                'totalDestinationTours' =>
                    $totalDestinationTours
            ]
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

        echo '<h1>404 - Không tìm thấy điểm đến.</h1>';

        exit;
    }
}