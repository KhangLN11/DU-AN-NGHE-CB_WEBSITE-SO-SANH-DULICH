<section class="home-hero">

    <div class="hero-background"></div>

    <div class="page-container hero-container">

        <div class="hero-content">

            <span class="hero-badge">
                Khám phá Việt Nam theo cách của bạn
            </span>

            <h1>
                Tìm hành trình phù hợp
                <span>cho chuyến đi tiếp theo</span>
            </h1>

            <p>
                Khám phá, tìm kiếm và so sánh các tour du lịch
                từ nhiều đơn vị lữ hành trên cùng một nền tảng.
            </p>

            <form
                class="hero-search"
                action="<?= base_url('tours') ?>"
                method="GET"
            >

                <div class="search-field">

                    <span class="search-icon">
                        ⌕
                    </span>

                    <input
                        type="text"
                        name="keyword"
                        placeholder="Bạn muốn đi đâu?"
                        autocomplete="off"
                    >

                </div>

                <button type="submit">
                    Tìm tour
                </button>

            </form>

            <div class="hero-suggestions">

                <span>Gợi ý:</span>

                <a href="<?= base_url('tours?keyword=Đà Lạt') ?>">
                    Đà Lạt
                </a>

                <a href="<?= base_url('tours?keyword=Đà Nẵng') ?>">
                    Đà Nẵng
                </a>

                <a href="<?= base_url('tours?keyword=Phú Quốc') ?>">
                    Phú Quốc
                </a>

            </div>

        </div>

        <div class="hero-visual">

            <div class="hero-card hero-card-main">

                <span class="hero-card-label">
                    Điểm đến nổi bật
                </span>

                <strong>
                    Đà Lạt
                </strong>

                <span>
                    Thành phố ngàn hoa
                </span>

            </div>

            <div class="hero-stat hero-stat-left">

                <strong>
                    <?= count($featuredTours) ?>
                </strong>

                <span>
                    Tour nổi bật
                </span>

            </div>

            <div class="hero-stat hero-stat-right">

                <strong>
                    <?= count($popularLocations) ?>
                </strong>

                <span>
                    Điểm đến
                </span>

            </div>

        </div>

    </div>

</section>


<section class="home-section featured-section">

    <div class="page-container">

        <div class="section-heading">

            <div>

                <span class="section-label">
                    Gợi ý cho bạn
                </span>

                <h2>
                    Tour nổi bật
                </h2>

                <p>
                    Những hành trình được lựa chọn để bạn
                    dễ dàng bắt đầu chuyến đi.
                </p>

            </div>

            <a
                class="section-link"
                href="<?= base_url('tours') ?>"
            >
                Xem tất cả
                <span>→</span>
            </a>

        </div>


        <?php if (!empty($featuredTours)): ?>

            <div class="tour-grid">

                <?php foreach ($featuredTours as $tour): ?>

                    <article class="tour-card">

                        <a
                            class="tour-card-image"
                            href="<?= base_url('tours/' . $tour['tour_id']) ?>"
                        >

                            <div class="image-placeholder">
                                <span>
                                    <?= e(mb_substr($tour['tour_name'], 0, 1)) ?>
                                </span>
                            </div>

                            <?php if (!empty($tour['image_url'])): ?>

                                <img
                                    src="<?= asset(ltrim($tour['image_url'], '/')) ?>"                                    
                                    alt="<?= e($tour['tour_name']) ?>"
                                    loading="lazy"
                                    onerror="this.style.display='none'"
                                >

                            <?php endif; ?>

                            <span class="tour-category">
                                <?= e($tour['category_name']) ?>
                            </span>

                        </a>

                        <div class="tour-card-content">

                            <div class="tour-company">
                                <?= e($tour['company_name']) ?>
                            </div>

                            <h3>

                                <a href="<?= base_url('tours/' . $tour['tour_id']) ?>">
                                    <?= e($tour['tour_name']) ?>
                                </a>

                            </h3>

                            <p class="tour-description">
                                <?= e($tour['short_description']) ?>
                            </p>

                            <div class="tour-meta">

                                <span>
                                    <?= (int) $tour['duration_days'] ?>
                                    ngày
                                    <?= (int) $tour['duration_nights'] ?>
                                    đêm
                                </span>

                            </div>

                            <div class="tour-card-footer">

                                <div class="tour-price">

                                    <span>
                                        Từ
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
                                    class="tour-detail-button"
                                    href="<?= base_url('tours/' . $tour['tour_id']) ?>"
                                    aria-label="Xem chi tiết <?= e($tour['tour_name']) ?>"
                                >
                                    →
                                </a>

                            </div>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <div class="empty-state">
                Chưa có tour nổi bật.
            </div>

        <?php endif; ?>

    </div>

</section>


<section class="home-section destinations-section">

    <div class="page-container">

        <div class="section-heading">

            <div>

                <span class="section-label">
                    Đi đâu tiếp theo?
                </span>

                <h2>
                    Điểm đến phổ biến
                </h2>

                <p>
                    Khám phá những điểm đến đang xuất hiện
                    trong nhiều hành trình của hệ thống.
                </p>

            </div>

            <a
                class="section-link"
                href="<?= base_url('destinations') ?>"
            >
                Khám phá thêm
                <span>→</span>
            </a>

        </div>


        <?php if (!empty($popularLocations)): ?>

            <div class="destination-grid">

                <?php foreach ($popularLocations as $location): ?>

                    <a
                        class="destination-card"
                        href="<?= base_url(
                            'tours?location=' . $location['location_id']
                        ) ?>"
                    >

                        <div class="destination-image">

                            <div class="destination-placeholder">
                                <?= e(
                                    mb_substr(
                                        $location['location_name'],
                                        0,
                                        1
                                    )
                                ) ?>
                            </div>

                            <?php if (!empty($location['image'])): ?>

                                <img
                                    src="<?= asset(
                                        ltrim($location['image'], '/')
                                    ) ?>"
                                    alt="<?= e($location['location_name']) ?>"
                                    loading="lazy"
                                    onerror="this.style.display='none'"
                                >

                            <?php endif; ?>

                        </div>

                        <div class="destination-overlay"></div>

                        <div class="destination-content">

                            <h3>
                                <?= e($location['location_name']) ?>
                            </h3>

                            <span>
                                <?= (int) $location['tour_count'] ?>
                                tour
                            </span>

                        </div>

                    </a>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </div>

</section>


<section class="home-section categories-section">

    <div class="page-container">

        <div class="section-heading section-heading-center">

            <div>

                <span class="section-label">
                    Chọn theo sở thích
                </span>

                <h2>
                    Khám phá theo loại hình
                </h2>

                <p>
                    Tìm tour phù hợp với phong cách
                    du lịch mà bạn yêu thích.
                </p>

            </div>

        </div>


        <div class="category-grid">

            <?php foreach ($categories as $category): ?>

                <a
                    class="category-card"
                    href="<?= base_url(
                        'tours?category=' . $category['category_id']
                    ) ?>"
                >

                    <div class="category-icon">
                        <?= e(
                            mb_substr(
                                $category['category_name'],
                                0,
                                1
                            )
                        ) ?>
                    </div>

                    <div class="category-content">

                        <h3>
                            <?= e($category['category_name']) ?>
                        </h3>

                        <p>
                            <?= e($category['description']) ?>
                        </p>

                        <span>
                            <?= (int) $category['tour_count'] ?>
                            tour
                        </span>

                    </div>

                </a>

            <?php endforeach; ?>

        </div>

    </div>

</section>


<section class="home-cta">

    <div class="page-container">

        <div class="cta-container">

            <div class="cta-content">

                <span>
                    Chưa biết chọn tour nào?
                </span>

                <h2>
                    So sánh để tìm hành trình phù hợp nhất
                </h2>

                <p>
                    Chọn từ 2 đến 3 tour và đặt các thông tin
                    quan trọng cạnh nhau để đưa ra lựa chọn dễ dàng hơn.
                </p>

            </div>

            <a
                class="cta-button"
                href="<?= base_url('compare') ?>"
            >
                Bắt đầu so sánh
                <span>→</span>
            </a>

        </div>

    </div>

</section>