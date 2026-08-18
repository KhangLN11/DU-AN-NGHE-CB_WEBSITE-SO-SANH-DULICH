<?php

$router->get(
    '/',
    ['HomeController', 'index']
);

$router->get(
    '/tours',
    ['TourController', 'index']
);

$router->get(
    '/tours/{id}',
    ['TourController', 'show']
);

$router->get(
    '/compare',
    ['CompareController', 'index']
);

$router->post(
    '/compare/add',
    ['CompareController', 'add']
);

$router->post(
    '/compare/remove',
    ['CompareController', 'remove']
);

$router->post(
    '/compare/clear',
    ['CompareController', 'clear']
);

$router->get(
    '/register',
    ['AuthController', 'register']
);

$router->post(
    '/register',
    ['AuthController', 'storeRegister']
);

$router->get(
    '/login',
    ['AuthController', 'login']
);

$router->post(
    '/login',
    ['AuthController', 'authenticate']
);

$router->post(
    '/logout',
    ['AuthController', 'logout']
);

$router->get(
    '/account',
    ['AccountController', 'profile']
);

$router->post(
    '/account/update',
    ['AccountController', 'updateProfile']
);

$router->get(
    '/favorites',
    ['FavoriteController', 'index']
);

$router->post(
    '/favorites/add',
    ['FavoriteController', 'add']
);

$router->post(
    '/favorites/remove',
    ['FavoriteController', 'remove']
);

$router->get(
    '/contact',
    ['ContactController', 'index']
);

$router->post(
    '/contact',
    ['ContactController', 'store']
);