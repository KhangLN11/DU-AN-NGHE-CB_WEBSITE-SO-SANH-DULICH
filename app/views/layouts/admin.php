<?php

$pageTitle =
    $title
    ?? 'Admin - TourCompare';

$pageStyles =
    $styles
    ?? [];

$pageScripts =
    $scripts
    ?? [];

$currentPath = parse_url(
    $_SERVER['REQUEST_URI'] ?? '',
    PHP_URL_PATH
);

?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= e($pageTitle) ?>
    </title>

    <link
        rel="stylesheet"
        href="<?= asset('css/app.css') ?>"
    >

    <link
        rel="stylesheet"
        href="<?= asset('css/admin.css') ?>"
    >

    <?php foreach ($pageStyles as $style): ?>

        <?php if (
            $style !== 'css/admin.css'
        ): ?>

            <link
                rel="stylesheet"
                href="<?= asset($style) ?>"
            >

        <?php endif; ?>

    <?php endforeach; ?>

</head>

<body class="admin-body">

    <div class="admin-layout">

        <aside class="admin-sidebar">

            <div class="admin-brand">

                <a href="<?= base_url('admin') ?>">
                    VivuTourViet
                </a>

                <span>
                    Admin
                </span>

            </div>

            <nav class="admin-navigation">

                <a
                    class="<?= str_ends_with(
                        $currentPath,
                        '/admin'
                    )
                        ? 'active'
                        : '' ?>"
                    href="<?= base_url('admin') ?>"
                >
                    Tổng quan
                </a>

                <a
                    class="<?= str_contains(
                        $currentPath,
                        '/admin/tours'
                    )
                        ? 'active'
                        : '' ?>"
                    href="<?= base_url(
                        'admin/tours'
                    ) ?>"
                >
                    Tour
                </a>

                <a href="<?= base_url( 'admin/categories') ?>">
                    Danh mục
                </a>

                <a href="<?= base_url('admin/companies') ?>">
                    Công ty
                </a>

                <a href="<?= base_url('admin/locations') ?>">
                    Địa điểm
                </a>

                <a href="<?= base_url('admin/users') ?>">
                    Người dùng
                </a>

                <span>
                    Liên hệ
                </span>

            </nav>

            <div class="admin-sidebar-footer">

                <a href="<?= base_url() ?>">
                    ← Về website
                </a>

            </div>

        </aside>

        <div class="admin-main">

            <header class="admin-header">

                <div>

                    <span>
                        Khu vực quản trị
                    </span>

                </div>

                <div class="admin-user">

                    <div class="admin-user-info">

                        <strong>
                            <?= e(
                                $_SESSION[
                                    'user_name'
                                ]
                                ?? 'Admin'
                            ) ?>
                        </strong>

                        <span>
                            <?= e(
                                $_SESSION[
                                    'user_email'
                                ]
                                ?? ''
                            ) ?>
                        </span>

                    </div>

                    <form
                        action="<?= base_url(
                            'logout'
                        ) ?>"
                        method="POST"
                    >

                        <button
                            type="submit"
                            class="admin-logout"
                        >
                            Đăng xuất
                        </button>

                    </form>

                </div>

            </header>

            <main class="admin-content">

                <?= $content ?>

            </main>

        </div>

    </div>

    <script
        src="<?= asset(
            'js/app.js'
        ) ?>"
    ></script>

    <?php foreach (
        $pageScripts
        as $script
    ): ?>

        <script
            src="<?= asset(
                $script
            ) ?>"
        ></script>

    <?php endforeach; ?>

</body>

</html>