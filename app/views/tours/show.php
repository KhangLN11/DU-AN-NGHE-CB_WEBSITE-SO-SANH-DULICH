<?php

$mainImage = null;

if (!empty($images)) {
    $mainImage = $images[0];
}
?>

<section class="tour-detail-hero">

    <div class="page-container">

        <div class="tour-breadcrumb">

            <a href="<?= base_url() ?>">
                Trang chủ
            </a>

            <span>/</span>

            <a href="<?= base_url('tours') ?>">
                Tour du lịch
            </a>

            <span>/</span>

            <strong>
                <?= e($tour['tour_name']) ?>
            </strong>

        </div>

        <div class="tour-detail-heading">

            <div class="tour-detail-title">

                <div class="tour-heading-meta">

                    <span class="tour-category-badge">
                        <?= e($tour['category_name']) ?>
                    </span>

                    <?php if ((int) $tour['featured'] === 1): ?>

                        <span class="tour-featured-label">
                            Tour nổi bật
                        </span>

                    <?php endif; ?>

                </div>

                <h1>
                    <?= e($tour['tour_name']) ?>
                </h1>

                <p>
                    <?= e($tour['short_description']) ?>
                </p>

            </div>

            <div class="tour-heading-company">

                <span>
                    Đơn vị tổ chức
                </span>

                <strong>
                    <?= e($tour['company_name']) ?>
                </strong>

            </div>

        </div>

    </div>

</section>

<section class="tour-detail-main">

    <div class="page-container">

        <div class="tour-gallery">

            <div class="tour-gallery-main">

                <div class="tour-gallery-placeholder">
                    <?= e(
                        mb_substr(
                            $tour['tour_name'],
                            0,
                            1
                        )
                    ) ?>
                </div>

                <?php if ($mainImage): ?>

                    <img
                        id="mainTourImage"
                        src="<?= asset(
                            ltrim(
                                $mainImage['image_url'],
                                '/'
                            )
                        ) ?>"
                        alt="<?= e(
                            $mainImage['alt_text']
                                ?: $tour['tour_name']
                        ) ?>"
                        onerror="this.style.display='none'"
                    >

                <?php endif; ?>

            </div>

            <?php if (count($images) > 1): ?>

                <div class="tour-gallery-thumbnails">

                    <?php foreach ($images as $index => $image): ?>

                        <button
                            class="tour-thumbnail <?= $index === 0 ? 'active' : '' ?>"
                            type="button"
                            data-image="<?= asset(
                                ltrim(
                                    $image['image_url'],
                                    '/'
                                )
                            ) ?>"
                            data-alt="<?= e(
                                $image['alt_text']
                                    ?: $tour['tour_name']
                            ) ?>"
                        >

                            <span class="thumbnail-placeholder">
                                <?= $index + 1 ?>
                            </span>

                            <img
                                src="<?= asset(
                                    ltrim(
                                        $image['image_url'],
                                        '/'
                                    )
                                ) ?>"
                                alt="<?= e(
                                    $image['alt_text']
                                        ?: $tour['tour_name']
                                ) ?>"
                                loading="lazy"
                                onerror="this.style.display='none'"
                            >

                        </button>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        </div>

        <div class="tour-detail-layout">

            <div class="tour-detail-content">

                <section class="detail-section">

                    <div class="detail-section-heading">

                        <span>
                            Tổng quan
                        </span>

                        <h2>
                            Thông tin Tour
                        </h2>

                    </div>

                    <div class="tour-overview-grid">

                        <div class="overview-card">

                            <span>
                                Thời gian
                            </span>

                            <strong>
                                <?= (int) $tour['duration_days'] ?>
                                ngày
                                <?= (int) $tour['duration_nights'] ?>
                                đêm
                            </strong>

                        </div>

                        <div class="overview-card">

                            <span>
                                Khởi hành
                            </span>

                            <strong>
                                <?= e(
                                    $tour['departure_name']
                                        ?? 'Đang cập nhật'
                                ) ?>
                            </strong>

                        </div>

                        <div class="overview-card">

                            <span>
                                Loại Tour
                            </span>

                            <strong>
                                <?= e($tour['category_name']) ?>
                            </strong>

                        </div>

                        <div class="overview-card">

                            <span>
                                Đơn vị
                            </span>

                            <strong>
                                <?= e($tour['company_name']) ?>
                            </strong>

                        </div>

                    </div>

                    <?php if (!empty($tour['description'])): ?>

                        <div class="tour-description-full">

                            <?= nl2br(
                                e($tour['description'])
                            ) ?>

                        </div>

                    <?php endif; ?>

                </section>

                <section class="detail-section">

                    <div class="detail-section-heading">

                        <span>
                            Hành trình
                        </span>

                        <h2>
                            Điểm đến
                        </h2>

                    </div>

                    <?php if (!empty($locations)): ?>

                        <div class="location-route">

                            <?php foreach ($locations as $index => $location): ?>

                                <article class="location-route-item">

                                    <div class="route-number">
                                        <?= $index + 1 ?>
                                    </div>

                                    <div class="route-content">

                                        <h3>
                                            <?= e(
                                                $location['location_name']
                                            ) ?>
                                        </h3>

                                        <span>
                                            <?= e(
                                                $location['province_city']
                                                    ?? ''
                                            ) ?>

                                            <?php if (!empty($location['country'])): ?>
                                                ·
                                                <?= e($location['country']) ?>
                                            <?php endif; ?>
                                        </span>

                                        <?php if (!empty($location['note'])): ?>

                                            <p>
                                                <?= e($location['note']) ?>
                                            </p>

                                        <?php endif; ?>

                                        <?php if (
                                            $location['latitude'] !== null
                                            && $location['longitude'] !== null
                                        ): ?>

                                            <div
                                                class="location-coordinate"
                                                data-latitude="<?= e(
                                                    (string) $location['latitude']
                                                ) ?>"
                                                data-longitude="<?= e(
                                                    (string) $location['longitude']
                                                ) ?>"
                                            >
                                                Tọa độ:
                                                <?= e($location['latitude']) ?>,
                                                <?= e($location['longitude']) ?>
                                            </div>

                                        <?php endif; ?>

                                    </div>

                                </article>

                            <?php endforeach; ?>

                        </div>

                        <?php

                        $mapLocations = array_values(
                            array_filter(
                                $locations,
                                function ($location) {
                                    return
                                        $location['latitude'] !== null
                                        && $location['longitude'] !== null;
                                }
                            )
                        );

                        ?>

                        <?php if (!empty($mapLocations)): ?>

                            <div class="tour-map-wrapper">

                                <div class="tour-map-header">

                                    <div>

                                        <span>
                                            Bản đồ hành trình
                                        </span>

                                        <strong>
                                            <?= count($mapLocations) ?>
                                            điểm đến
                                        </strong>

                                    </div>

                                    <button
                                        id="fitTourMap"
                                        class="map-fit-button"
                                        type="button"
                                    >
                                        Xem toàn tuyến
                                    </button>

                                </div>

                                <div
                                    id="tourMap"
                                    class="tour-map"
                                ></div>

                                <script
                                    id="tourMapData"
                                    type="application/json"
                                >
                                    <?= json_encode(
                                        $mapLocations,
                                        JSON_UNESCAPED_UNICODE
                                        | JSON_UNESCAPED_SLASHES
                                        | JSON_HEX_TAG
                                        | JSON_HEX_AMP
                                        | JSON_HEX_APOS
                                        | JSON_HEX_QUOT
                                    ) ?>
                                </script>

                            </div>

                        <?php else: ?>

                            <div class="detail-empty">
                                Chưa có tọa độ để hiển thị bản đồ.
                            </div>

                        <?php endif; ?>

                    <?php else: ?>

                        <div class="detail-empty">
                            Chưa có thông tin điểm đến.
                        </div>

                    <?php endif; ?>

                </section>

                <section class="detail-section">

                    <div class="detail-section-heading">

                        <span>
                            Lịch trình
                        </span>

                        <h2>
                            Chương trình Tour
                        </h2>

                    </div>

                    <?php if (!empty($schedules)): ?>

                        <div class="tour-timeline">

                            <?php foreach ($schedules as $schedule): ?>

                                <article class="timeline-item">

                                    <div class="timeline-day">

                                        <span>
                                            Ngày
                                        </span>

                                        <strong>
                                            <?= (int) $schedule['day_number'] ?>
                                        </strong>

                                    </div>

                                    <div class="timeline-content">

                                        <h3>
                                            <?= e($schedule['title']) ?>
                                        </h3>

                                        <?php if (!empty($schedule['description'])): ?>

                                            <p>
                                                <?= nl2br(
                                                    e($schedule['description'])
                                                ) ?>
                                            </p>

                                        <?php endif; ?>

                                    </div>

                                </article>

                            <?php endforeach; ?>

                        </div>

                    <?php else: ?>

                        <div class="detail-empty">
                            Lịch trình đang được cập nhật.
                        </div>

                    <?php endif; ?>

                </section>

                <section class="detail-section">

                    <div class="detail-section-heading">

                        <span>
                            Đơn vị tổ chức
                        </span>

                        <h2>
                            <?= e($tour['company_name']) ?>
                        </h2>

                    </div>

                    <div class="company-detail-card">

                        <div class="company-logo-placeholder">
                            <?= e(
                                mb_substr(
                                    $tour['company_name'],
                                    0,
                                    1
                                )
                            ) ?>
                        </div>

                        <div class="company-detail-content">

                            <?php if (!empty($tour['company_description'])): ?>

                                <p>
                                    <?= e($tour['company_description']) ?>
                                </p>

                            <?php endif; ?>

                            <div class="company-contact-list">

                                <?php if (!empty($tour['company_phone'])): ?>

                                    <span>
                                        Điện thoại:
                                        <?= e($tour['company_phone']) ?>
                                    </span>

                                <?php endif; ?>

                                <?php if (!empty($tour['company_email'])): ?>

                                    <span>
                                        Email:
                                        <?= e($tour['company_email']) ?>
                                    </span>

                                <?php endif; ?>

                            </div>

                        </div>

                    </div>

                </section>

            </div>

            <aside class="tour-detail-sidebar">

                <div class="booking-summary">

                    <span class="summary-label">
                        Giá tham khảo
                    </span>

                    <div class="summary-price">

                        <?= number_format(
                            (float) $tour['price'],
                            0,
                            ',',
                            '.'
                        ) ?>

                        <span>
                            ₫
                        </span>

                    </div>

                    <div class="summary-divider"></div>

                    <div class="summary-row">

                        <span>
                            Thời gian
                        </span>

                        <strong>
                            <?= (int) $tour['duration_days'] ?>
                            ngày
                            <?= (int) $tour['duration_nights'] ?>
                            đêm
                        </strong>

                    </div>

                    <div class="summary-row">

                        <span>
                            Khởi hành
                        </span>

                        <strong>
                            <?= e(
                                $tour['departure_name']
                                    ?? 'Đang cập nhật'
                            ) ?>
                        </strong>

                    </div>

                    <div class="summary-row">

                        <span>
                            Đơn vị
                        </span>

                        <strong>
                            <?= e($tour['company_name']) ?>
                        </strong>

                    </div>

                    <?php if (!empty($_SESSION['user_id'])): ?>

    <?php if ($isFavorite): ?>

        <form
            action="<?= base_url(
                'favorites/remove'
            ) ?>"
            method="POST"
        >

            <input
                type="hidden"
                name="tour_id"
                value="<?= (int)
                $tour['tour_id'] ?>"
            >

            <input
                type="hidden"
                name="return_to"
                value="tours/<?= (int)
                $tour['tour_id'] ?>"
            >

            <button
                class="summary-favorite-button active"
                type="submit"
            >
                ♥ Đã lưu Tour yêu thích
            </button>

        </form>

    <?php else: ?>

        <form
            action="<?= base_url(
                'favorites/add'
            ) ?>"
            method="POST"
        >

            <input
                type="hidden"
                name="tour_id"
                value="<?= (int)
                $tour['tour_id'] ?>"
            >

            <input
                type="hidden"
                name="return_to"
                value="tours/<?= (int)
                $tour['tour_id'] ?>"
            >

            <button
                class="summary-favorite-button"
                type="submit"
            >
                ♡ Lưu Tour yêu thích
            </button>

        </form>

    <?php endif; ?>

<?php else: ?>

    <a
        class="summary-favorite-button"
        href="<?= base_url('login') ?>"
    >
        ♡ Đăng nhập để lưu Tour
    </a>

<?php endif; ?>
                    
                    <?php

$selectedCompareIds = $_SESSION['compare_tours'] ?? [];

$isCompared = in_array(
    (int) $tour['tour_id'],
    array_map('intval', $selectedCompareIds),
    true
);

$compareIsFull =
    count($selectedCompareIds) >= 3
    && !$isCompared;

?>

<?php if ($isCompared): ?>

    <a
        class="summary-compare-button"
        href="<?= base_url('compare') ?>"
    >
        Đã thêm vào so sánh
    </a>

<?php elseif ($compareIsFull): ?>

    <a
        class="summary-compare-button"
        href="<?= base_url('compare') ?>"
    >
        Đã chọn đủ 3 Tour
    </a>

<?php else: ?>

    <form
        action="<?= base_url('compare/add') ?>"
        method="POST"
    >

        <input
            type="hidden"
            name="tour_id"
            value="<?= (int) $tour['tour_id'] ?>"
        >

        <input
            type="hidden"
            name="return_to"
            value="tours/<?= (int) $tour['tour_id'] ?>"
        >

        <button
            class="summary-compare-button"
            type="submit"
        >
            Thêm vào so sánh
        </button>

    </form>

<?php endif; ?>

                    <?php if (!empty($tour['source_url'])): ?>

                        <a
                            class="summary-source-button"
                            href="<?= e($tour['source_url']) ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            Xem tại đơn vị tổ chức
                        </a>

                    <?php endif; ?>

                    <p class="summary-note">
                        TourCompare chỉ cung cấp thông tin
                        tham khảo và không trực tiếp bán Tour.
                    </p>

                </div>

            </aside>

        </div>

    </div>

</section>

<?php if (!empty($relatedTours)): ?>

<section class="related-tour-section">

    <div class="page-container">

        <div class="related-heading">

            <div>

                <span>
                    Có thể bạn quan tâm
                </span>

                <h2>
                    Tour tương tự
                </h2>

            </div>

            <a href="<?= base_url('tours') ?>">
                Xem tất cả →
            </a>

        </div>

        <div class="related-tour-grid">

            <?php foreach ($relatedTours as $related): ?>

                <article class="related-tour-card">

                    <a
                        class="related-tour-image"
                        href="<?= base_url(
                            'tours/' . $related['tour_id']
                        ) ?>"
                    >

                        <div class="related-placeholder">
                            <?= e(
                                mb_substr(
                                    $related['tour_name'],
                                    0,
                                    1
                                )
                            ) ?>
                        </div>

                        <?php if (!empty($related['image_url'])): ?>

                            <img
                                src="<?= asset(
                                    ltrim(
                                        $related['image_url'],
                                        '/'
                                    )
                                ) ?>"
                                alt="<?= e($related['tour_name']) ?>"
                                loading="lazy"
                                onerror="this.style.display='none'"
                            >

                        <?php endif; ?>

                    </a>

                    <div class="related-tour-content">

                        <span>
                            <?= e($related['company_name']) ?>
                        </span>

                        <h3>

                            <a href="<?= base_url(
                                'tours/' . $related['tour_id']
                            ) ?>">
                                <?= e($related['tour_name']) ?>
                            </a>

                        </h3>

                        <div class="related-tour-footer">

                            <small>
                                <?= (int) $related['duration_days'] ?>
                                ngày
                                <?= (int) $related['duration_nights'] ?>
                                đêm
                            </small>

                            <strong>
                                <?= number_format(
                                    (float) $related['price'],
                                    0,
                                    ',',
                                    '.'
                                ) ?>
                                ₫
                            </strong>

                        </div>

                    </div>

                </article>

            <?php endforeach; ?>

        </div>

    </div>

</section>

<?php endif; ?>