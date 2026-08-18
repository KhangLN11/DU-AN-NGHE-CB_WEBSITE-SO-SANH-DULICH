<?php

$paginationQuery = $_GET;
unset($paginationQuery['page']);

$hasFilters =
    $filters['keyword'] !== ''
    || $filters['category'] > 0
    || $filters['location'] > 0
    || $filters['company'] > 0
    || $filters['min_price'] !== null
    || $filters['max_price'] !== null;
?>

<section class="tour-list-hero">

    <div class="page-container">

        <div class="tour-list-hero-content">

            <span class="tour-list-label">
                Khám phá hành trình
            </span>

            <h1>
                Tour du lịch
            </h1>

            <p>
                Tìm kiếm, lọc và khám phá các hành trình
                phù hợp từ nhiều đơn vị lữ hành.
            </p>

        </div>

    </div>

</section>


<section class="tour-list-section">

    <div class="page-container">

        <div class="tour-page-layout">

            <aside class="tour-filter">

                <div class="filter-header">

                    <div>

                        <span>
                            Bộ lọc
                        </span>

                        <h2>
                            Tìm Tour
                        </h2>

                    </div>

                    <?php if ($hasFilters): ?>

                        <a
                            class="filter-reset"
                            href="<?= base_url('tours') ?>"
                        >
                            Xóa lọc
                        </a>

                    <?php endif; ?>

                </div>


                <form
                    class="tour-filter-form"
                    action="<?= base_url('tours') ?>"
                    method="GET"
                >

                    <div class="filter-group">

                        <label for="keyword">
                            Từ khóa
                        </label>

                        <input
                            id="keyword"
                            type="text"
                            name="keyword"
                            value="<?= e($filters['keyword']) ?>"
                            placeholder="Tên tour, địa điểm..."
                        >

                    </div>


                    <div class="filter-group">

                        <label for="category">
                            Loại hình
                        </label>

                        <select
                            id="category"
                            name="category"
                        >

                            <option value="">
                                Tất cả loại hình
                            </option>

                            <?php foreach ($categories as $category): ?>

                                <option
                                    value="<?= (int) $category['category_id'] ?>"
                                    <?= $filters['category']
                                        === (int) $category['category_id']
                                        ? 'selected'
                                        : '' ?>
                                >
                                    <?= e($category['category_name']) ?>
                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                    <div class="filter-group">

                        <label for="location">
                            Điểm đến
                        </label>

                        <select
                            id="location"
                            name="location"
                        >

                            <option value="">
                                Tất cả điểm đến
                            </option>

                            <?php foreach ($locations as $location): ?>

                                <option
                                    value="<?= (int) $location['location_id'] ?>"
                                    <?= $filters['location']
                                        === (int) $location['location_id']
                                        ? 'selected'
                                        : '' ?>
                                >
                                    <?= e($location['location_name']) ?>
                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                    <div class="filter-group">

                        <label for="company">
                            Đơn vị tổ chức
                        </label>

                        <select
                            id="company"
                            name="company"
                        >

                            <option value="">
                                Tất cả đơn vị
                            </option>

                            <?php foreach ($companies as $company): ?>

                                <option
                                    value="<?= (int) $company['company_id'] ?>"
                                    <?= $filters['company']
                                        === (int) $company['company_id']
                                        ? 'selected'
                                        : '' ?>
                                >
                                    <?= e($company['company_name']) ?>
                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                    <div class="filter-group">

                        <label>
                            Khoảng giá
                        </label>

                        <div class="price-filter">

                            <input
                                type="number"
                                name="min_price"
                                min="0"
                                step="100000"
                                value="<?= $filters['min_price'] !== null
                                    ? e((string) $filters['min_price'])
                                    : '' ?>"
                                placeholder="Giá từ"
                            >

                            <span>
                                –
                            </span>

                            <input
                                type="number"
                                name="max_price"
                                min="0"
                                step="100000"
                                value="<?= $filters['max_price'] !== null
                                    ? e((string) $filters['max_price'])
                                    : '' ?>"
                                placeholder="Giá đến"
                            >

                        </div>

                    </div>


                    <button
                        class="filter-submit"
                        type="submit"
                    >
                        Áp dụng bộ lọc
                    </button>

                </form>

            </aside>


            <div class="tour-results">

                <div class="tour-list-toolbar">

                    <div class="tour-result-count">

                        <strong>
                            <?= (int) $totalTours ?>
                        </strong>

                        <span>
                            tour phù hợp
                        </span>

                    </div>


                    <?php if ($hasFilters): ?>

                        <span class="filtered-label">
                            Đang áp dụng bộ lọc
                        </span>

                    <?php endif; ?>

                </div>


                <?php if (!empty($tours)): ?>

                    <div class="tour-list-grid">

                        <?php foreach ($tours as $tour): ?>

                            <?php

$selectedCompareIds = array_map(
    'intval',
    $_SESSION['compare_tours'] ?? []
);

$isCompared = in_array(
    (int) $tour['tour_id'],
    $selectedCompareIds,
    true
);

$compareIsFull =
    count($selectedCompareIds) >= 3
    && !$isCompared;

$isFavorite = in_array(
    (int) $tour['tour_id'],
    $favoriteTourIds ?? [],
    true
);

$returnQuery = $_GET;

$returnTo = 'tours';

if (!empty($returnQuery)) {
    $returnTo .= '?'
        . http_build_query(
            $returnQuery
        );
}

?>

                            <article class="tour-list-card">

                                <a
                                    class="tour-list-image"
                                    href="<?= base_url(
                                        'tours/' . $tour['tour_id']
                                    ) ?>"
                                >

                                    <div class="tour-image-placeholder">

                                        <span>
                                            <?= e(
                                                mb_substr(
                                                    $tour['tour_name'],
                                                    0,
                                                    1
                                                )
                                            ) ?>
                                        </span>

                                    </div>


                                    <?php if (!empty($tour['image_url'])): ?>

                                        <img
                                            src="<?= asset(
                                                ltrim(
                                                    $tour['image_url'],
                                                    '/'
                                                )
                                            ) ?>"
                                            alt="<?= e($tour['tour_name']) ?>"
                                            loading="lazy"
                                            onerror="this.style.display='none'"
                                        >

                                    <?php endif; ?>


                                    <span class="tour-list-category">
                                        <?= e($tour['category_name']) ?>
                                    </span>


                                    <?php if ((int) $tour['featured'] === 1): ?>

                                        <span class="tour-featured-badge">
                                            Nổi bật
                                        </span>

                                    <?php endif; ?>

                                </a>
                                


                                <div class="tour-list-content">

                                    <div class="tour-list-company">
                                        <?= e($tour['company_name']) ?>
                                    </div>


                                    <h2>

                                        <a
                                            href="<?= base_url(
                                                'tours/' . $tour['tour_id']
                                            ) ?>"
                                        >
                                            <?= e($tour['tour_name']) ?>
                                        </a>

                                    </h2>


                                    <p class="tour-list-description">
                                        <?= e($tour['short_description']) ?>
                                    </p>


                                    <div class="tour-info-list">

                                        <div class="tour-info-item">

                                            <span class="tour-info-label">
                                                Thời gian
                                            </span>

                                            <strong>
                                                <?= (int) $tour['duration_days'] ?>
                                                ngày
                                                <?= (int) $tour['duration_nights'] ?>
                                                đêm
                                            </strong>

                                        </div>


                                        <div class="tour-info-item">

                                            <span class="tour-info-label">
                                                Khởi hành
                                            </span>

                                            <strong>
                                                <?= e(
                                                    $tour['departure_name']
                                                        ?? 'Đang cập nhật'
                                                ) ?>
                                            </strong>

                                        </div>


                                        <div class="tour-info-item tour-info-destination">

                                            <span class="tour-info-label">
                                                Điểm đến
                                            </span>

                                            <strong>
                                                <?= e(
                                                    $tour['destinations']
                                                        ?? 'Đang cập nhật'
                                                ) ?>
                                            </strong>

                                        </div>

                                    </div>


                                    <div class="tour-list-footer">

                                        <div class="tour-list-price">

                                            <span>
                                                Giá tham khảo
                                            </span>

                                            <strong>
                                                <?= number_format(
                                                    (float) $tour['price'],
                                                    0,
                                                    ',',
                                                    '.'
                                                ) ?>
                                                ₫
                                            </strong>

                                        </div>


                                        <a
                                            class="tour-view-button"
                                            href="<?= base_url(
                                                'tours/' . $tour['tour_id']
                                            ) ?>"
                                        >
                                            Xem chi tiết
                                            <span>→</span>
                                        </a>

                                        <?php if (!empty($_SESSION['user_id'])): ?>

    <?php if ($isFavorite): ?>

        <form
            class="favorite-card-form"
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
                value="<?= e($returnTo) ?>"
            >

            <button
                class="favorite-card-button active"
                type="submit"
                title="Xóa khỏi yêu thích"
            >
                ♥
            </button>

        </form>

    <?php else: ?>

        <form
            class="favorite-card-form"
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
                value="<?= e($returnTo) ?>"
            >

            <button
                class="favorite-card-button"
                type="submit"
                title="Thêm vào yêu thích"
            >
                ♡
            </button>

        </form>

    <?php endif; ?>

<?php else: ?>

    <a
        class="favorite-card-button"
        href="<?= base_url('login') ?>"
        title="Đăng nhập để lưu Tour"
    >
        ♡
    </a>

<?php endif; ?>

                                        <?php if ($isCompared): ?>

    <a
        class="tour-compare-button selected"
        href="<?= base_url('compare') ?>"
    >
        Đã chọn
    </a>

<?php elseif ($compareIsFull): ?>

    <a
        class="tour-compare-button disabled"
        href="<?= base_url('compare') ?>"
    >
        Đủ 3 Tour
    </a>

<?php else: ?>

    <?php

    $returnQuery = $_GET;

    $returnTo = 'tours';

    if (!empty($returnQuery)) {
        $returnTo .= '?'
            . http_build_query($returnQuery);
    }

    ?>

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
            value="<?= e($returnTo) ?>"
        >

        <button
            class="tour-compare-button"
            type="submit"
        >
            So sánh
        </button>

    </form>

<?php endif; ?>

                                    </div>

                                </div>

                            </article>

                        <?php endforeach; ?>

                    </div>


                    <?php if ($totalPages > 1): ?>

                        <nav
                            class="pagination"
                            aria-label="Phân trang Tour"
                        >

                            <?php if ($currentPage > 1): ?>

                                <?php
                                $previousQuery = $paginationQuery;
                                $previousQuery['page'] = $currentPage - 1;
                                ?>

                                <a
                                    class="pagination-item"
                                    href="<?= base_url(
                                        'tours?'
                                        . http_build_query($previousQuery)
                                    ) ?>"
                                >
                                    ← Trước
                                </a>

                            <?php endif; ?>


                            <div class="pagination-pages">

                                <?php for (
                                    $page = 1;
                                    $page <= $totalPages;
                                    $page++
                                ): ?>

                                    <?php
                                    $pageQuery = $paginationQuery;
                                    $pageQuery['page'] = $page;
                                    ?>

                                    <a
                                        class="pagination-item
                                        <?= $page === $currentPage
                                            ? 'active'
                                            : '' ?>"
                                        href="<?= base_url(
                                            'tours?'
                                            . http_build_query($pageQuery)
                                        ) ?>"
                                    >
                                        <?= $page ?>
                                    </a>

                                <?php endfor; ?>

                            </div>


                            <?php if ($currentPage < $totalPages): ?>

                                <?php
                                $nextQuery = $paginationQuery;
                                $nextQuery['page'] = $currentPage + 1;
                                ?>

                                <a
                                    class="pagination-item"
                                    href="<?= base_url(
                                        'tours?'
                                        . http_build_query($nextQuery)
                                    ) ?>"
                                >
                                    Sau →
                                </a>

                            <?php endif; ?>

                        </nav>

                    <?php endif; ?>


                <?php else: ?>

                    <div class="tour-empty-state">

                        <div class="tour-empty-icon">
                            T
                        </div>

                        <h2>
                            Không tìm thấy Tour phù hợp
                        </h2>

                        <p>
                            Hãy thử thay đổi từ khóa hoặc
                            các điều kiện lọc.
                        </p>

                        <a
                            class="empty-reset-button"
                            href="<?= base_url('tours') ?>"
                        >
                            Xem tất cả Tour
                        </a>

                    </div>

                <?php endif; ?>

            </div>

        </div>

    </div>

</section>