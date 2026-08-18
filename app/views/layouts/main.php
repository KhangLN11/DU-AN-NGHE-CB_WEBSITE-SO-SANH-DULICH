<?php

$pageTitle = $title ?? 'TourCompare';

$pageDescription = $description
    ?? 'Website tổng hợp, giới thiệu và so sánh tour du lịch';

$pageStyles = $styles ?? [];
$pageScripts = $scripts ?? [];

$externalStyles = $externalStyles ?? [];
$externalScripts = $externalScripts ?? [];

?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title><?= e($pageTitle) ?></title>

    <meta
        name="description"
        content="<?= e($pageDescription) ?>"
    >

    <link
        rel="stylesheet"
        href="<?= asset('css/app.css') ?>"
    >

    <?php foreach ($externalStyles as $style): ?>

        <link
            rel="stylesheet"
            href="<?= e($style) ?>"
        >

    <?php endforeach; ?>


    <?php foreach ($pageStyles as $style): ?>

        <link
            rel="stylesheet"
            href="<?= asset($style) ?>"
        >

    <?php endforeach; ?>

</head>

<body>

    <?php require __DIR__ . '/../partials/header.php'; ?>


    <main class="site-main">

        <?= $content ?>

    </main>


    <?php require __DIR__ . '/../partials/footer.php'; ?>


    <script src="<?= asset('js/app.js') ?>"></script>


    <?php foreach ($externalScripts as $script): ?>

        <script src="<?= e($script) ?>"></script>

    <?php endforeach; ?>


    <?php foreach ($pageScripts as $script): ?>

        <script src="<?= asset($script) ?>"></script>

    <?php endforeach; ?>

</body>

</html>