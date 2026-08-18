<?php

require_once __DIR__ . '/../models/Favorite.php';
require_once __DIR__ . '/../models/Tour.php';

class FavoriteController extends Controller
{
    public function index(): void
    {
        $this->requireAuthentication();

        $favoriteModel = new Favorite();

        $favorites =
            $favoriteModel->getUserFavorites(
                (int) $_SESSION['user_id']
            );

        $this->view('favorites/index', [
            'title' =>
                'Tour yêu thích - TourCompare',
            'description' =>
                'Danh sách các Tour bạn đã lưu.',
            'styles' => [
                'css/favorites.css'
            ],
            'favorites' => $favorites,
            'favoriteCount' =>
                count($favorites)
        ]);
    }

    public function add(): void
    {
        $this->requireAuthentication();

        $tourId = $this->positiveInt(
            $_POST['tour_id'] ?? null
        );

        $returnTo = $this->safeReturnTo(
            $_POST['return_to']
                ?? 'favorites'
        );

        if ($tourId === 0) {
            $this->redirect($returnTo);
        }

        $tourModel = new Tour();

        $tour = $tourModel->findById(
            $tourId
        );

        if ($tour === null) {
            $this->redirect($returnTo);
        }

        $favoriteModel = new Favorite();

        $favoriteModel->add(
            (int) $_SESSION['user_id'],
            $tourId
        );

        $this->redirect($returnTo);
    }

    public function remove(): void
    {
        $this->requireAuthentication();

        $tourId = $this->positiveInt(
            $_POST['tour_id'] ?? null
        );

        $returnTo = $this->safeReturnTo(
            $_POST['return_to']
                ?? 'favorites'
        );

        if ($tourId > 0) {
            $favoriteModel = new Favorite();

            $favoriteModel->remove(
                (int) $_SESSION['user_id'],
                $tourId
            );
        }

        $this->redirect($returnTo);
    }

    private function requireAuthentication(): void
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }
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

    private function safeReturnTo(
        string $returnTo
    ): string {
        $returnTo = ltrim(
            trim($returnTo),
            '/'
        );

        if ($returnTo === '') {
            return 'favorites';
        }

        if (
            preg_match(
                '#^tours(?:/\d+)?(?:\?.*)?$#',
                $returnTo
            )
        ) {
            return $returnTo;
        }

        if (
            in_array(
                $returnTo,
                [
                    'favorites',
                    'account'
                ],
                true
            )
        ) {
            return $returnTo;
        }

        return 'favorites';
    }
}