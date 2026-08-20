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

$router->get(
    '/admin',
    ['AdminController', 'dashboard']
);

$router->get(
    '/admin/tours',
    ['AdminTourController', 'index']
);

$router->get(
    '/admin/tours/create',
    ['AdminTourController', 'create']
);

$router->post(
    '/admin/tours/create',
    ['AdminTourController', 'store']
);

$router->get(
    '/admin/tours/{id}/edit',
    ['AdminTourController', 'edit']
);

$router->post(
    '/admin/tours/{id}/edit',
    ['AdminTourController', 'update']
);

$router->post(
    '/admin/tours/{id}/delete',
    ['AdminTourController', 'delete']
);

$router->get(
    '/admin/tours/{id}/locations',
    ['AdminTourController', 'locations']
);

$router->post(
    '/admin/tours/{id}/locations',
    ['AdminTourController', 'updateLocations']
);

$router->get(
    '/admin/tours/{id}/images',
    ['AdminTourController', 'images']
);

$router->post(
    '/admin/tours/{id}/images/upload',
    ['AdminTourController', 'uploadImages']
);

$router->post(
    '/admin/tours/{id}/images/update',
    ['AdminTourController', 'updateImages']
);

$router->post(
    '/admin/tours/{id}/images/{imageId}/delete',
    ['AdminTourController', 'deleteImage']
);

$router->get(
    '/admin/tours/{id}/schedules',
    ['AdminTourController', 'schedules']
);

$router->post(
    '/admin/tours/{id}/schedules',
    ['AdminTourController', 'updateSchedules']
);

$router->get(
    '/admin/categories',
    ['AdminCategoryController', 'index']
);

$router->get(
    '/admin/categories/create',
    ['AdminCategoryController', 'create']
);

$router->post(
    '/admin/categories/create',
    ['AdminCategoryController', 'store']
);

$router->get(
    '/admin/categories/{id}/edit',
    ['AdminCategoryController', 'edit']
);

$router->post(
    '/admin/categories/{id}/edit',
    ['AdminCategoryController', 'update']
);

$router->post(
    '/admin/categories/{id}/delete',
    ['AdminCategoryController', 'delete']
);

$router->post(
    '/admin/categories/{id}/disable',
    ['AdminCategoryController', 'disable']
);

$router->get(
    '/admin/companies',
    ['AdminCompanyController', 'index']
);

$router->get(
    '/admin/companies/create',
    ['AdminCompanyController', 'create']
);

$router->post(
    '/admin/companies/create',
    ['AdminCompanyController', 'store']
);

$router->get(
    '/admin/companies/{id}/edit',
    ['AdminCompanyController', 'edit']
);

$router->post(
    '/admin/companies/{id}/edit',
    ['AdminCompanyController', 'update']
);

$router->post(
    '/admin/companies/{id}/disable',
    ['AdminCompanyController', 'disable']
);

$router->post(
    '/admin/companies/{id}/delete',
    ['AdminCompanyController', 'delete']
);

$router->get(
    '/admin/locations',
    ['AdminLocationController', 'index']
);

$router->get(
    '/admin/locations/create',
    ['AdminLocationController', 'create']
);

$router->post(
    '/admin/locations/create',
    ['AdminLocationController', 'store']
);

$router->get(
    '/admin/locations/{id}/edit',
    ['AdminLocationController', 'edit']
);

$router->post(
    '/admin/locations/{id}/edit',
    ['AdminLocationController', 'update']
);

$router->post(
    '/admin/locations/{id}/disable',
    ['AdminLocationController', 'disable']
);

$router->post(
    '/admin/locations/{id}/delete',
    ['AdminLocationController', 'delete']
);

$router->get(
    '/admin/users',
    ['AdminUserController', 'index']
);

$router->get(
    '/admin/users/{id}',
    ['AdminUserController', 'detail']
);

$router->post(
    '/admin/users/{id}/status',
    ['AdminUserController', 'changeStatus']
);

$router->post(
    '/admin/users/{id}/delete',
    ['AdminUserController', 'delete']
);

$router->get(
    '/admin/contacts',
    ['AdminContactController', 'index']
);

$router->get(
    '/admin/contacts/{id}',
    ['AdminContactController', 'detail']
);

$router->post(
    '/admin/contacts/{id}/manage',
    ['AdminContactController', 'updateManagement']
);

$router->post(
    '/admin/contacts/{id}/delete',
    ['AdminContactController', 'delete']
);

$router->get(
    '/destinations',
    ['DestinationController', 'index']
);

$router->get(
    '/destinations/{slug}',
    ['DestinationController', 'show']
);