<?php

require_once __DIR__ . '/../models/Tour.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Location.php';

class HomeController extends Controller
{
    public function index(): void
    {
        $tourModel = new Tour();
        $categoryModel = new Category();
        $locationModel = new Location();

        $featuredTours =
            $tourModel->getFeaturedTours(6);

        $categories =
            $categoryModel->getActiveCategories();

        $popularLocations =
            $locationModel->getPopularLocations(6);

        $heroLocation =
            !empty($popularLocations)
                ? $popularLocations[0]
                : null;

        $heroSuggestions =
            array_slice(
                $popularLocations,
                0,
                3
            );

        $this->view(
            'home/index',
            [
                'title' =>
                    'VivuTourViet - Khám phá hành trình của bạn',

                'description' =>
                    'Tìm kiếm, khám phá và so sánh các tour du lịch từ nhiều đơn vị lữ hành.',

                'featuredTours' =>
                    $featuredTours,

                'categories' =>
                    $categories,

                'popularLocations' =>
                    $popularLocations,

                'heroLocation' =>
                    $heroLocation,

                'heroSuggestions' =>
                    $heroSuggestions,

                'styles' => [
                    'css/home.css'
                ],

                'scripts' => [
                    'js/home.js'
                ]
            ]
        );
    }
}