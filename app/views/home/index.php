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
                Khám phá, tìm kiếm và so sánh Tour
                từ nhiều đơn vị lữ hành trên cùng
                một nền tảng.
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
                    Tìm Tour
                </button>

            </form>


            <?php if (!empty($heroSuggestions)): ?>

                <div class="hero-suggestions">

                    <span>
                        Gợi ý:
                    </span>

                    <?php foreach (
                        $heroSuggestions
                        as $suggestion
                    ): ?>

                        <a
                            href="<?= base_url(
                                'destinations/'
                                . $suggestion['slug']
                            ) ?>"
                        >
                            <?= e(
                                $suggestion[
                                    'location_name'
                                ]
                            ) ?>
                        </a>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        </div>


        <div class="hero-visual">

            <?php if ($heroLocation): ?>

                <a
                    class="hero-card hero-card-main"
                    href="<?= base_url(
                        'destinations/'
                        . $heroLocation['slug']
                    ) ?>"
                >

                    <div class="hero-location-placeholder">

                        <?= e(
                            mb_substr(
                                $heroLocation[
                                    'location_name'
                                ],
                                0,
                                1
                            )
                        ) ?>

                    </div>


                    <?php if (
                        !empty(
                            $heroLocation['image']
                        )
                    ): ?>

                        <img
                            class="hero-location-image"
                            src="<?= asset(
                                ltrim(
                                    $heroLocation['image'],
                                    '/'
                                )
                            ) ?>"
                            alt="<?= e(
                                $heroLocation[
                                    'location_name'
                                ]
                            ) ?>"
                            onerror="this.style.display='none'"
                        >

                    <?php endif; ?>


                    <div class="hero-card-overlay"></div>


                    <div class="hero-card-content">

                        <span class="hero-card-label">
                            Điểm đến nổi bật
                        </span>

                        <strong>
                            <?= e(
                                $heroLocation[
                                    'location_name'
                                ]
                            ) ?>
                        </strong>

                        <span>
                            <?= e(
                                $heroLocation[
                                    'province_city'
                                ]
                                ?? $heroLocation[
                                    'country'
                                ]
                            ) ?>
                        </span>

                    </div>

                </a>

            <?php else: ?>

                <div class="hero-card hero-card-main">

                    <div class="hero-card-content">

                        <span class="hero-card-label">
                            Điểm đến nổi bật
                        </span>

                        <strong>
                            Việt Nam
                        </strong>

                        <span>
                            Hành trình đang chờ bạn
                        </span>

                    </div>

                </div>

            <?php endif; ?>


            <div class="hero-stat hero-stat-left">

                <strong>
                    <?= count(
                        $featuredTours
                    ) ?>
                </strong>

                <span>
                    Tour nổi bật
                </span>

            </div>


            <div class="hero-stat hero-stat-right">

                <strong>
                    <?= count(
                        $popularLocations
                    ) ?>
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
                    Những hành trình đáng chú ý từ
                    nhiều đơn vị tổ chức để bạn dễ dàng
                    bắt đầu chuyến đi.
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

            <div class="tour-grid home-reveal">

                <?php foreach (
                    $featuredTours
                    as $tour
                ): ?>

                    <article class="tour-card">

                        <a
                            class="tour-card-image"
                            href="<?= base_url(
                                'tours/'
                                . $tour['tour_id']
                            ) ?>"
                        >

                            <div class="image-placeholder">

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


                            <?php if (
                                !empty(
                                    $tour['image_url']
                                )
                            ): ?>

                                <img
                                    src="<?= asset(
                                        ltrim(
                                            $tour['image_url'],
                                            '/'
                                        )
                                    ) ?>"
                                    alt="<?= e(
                                        $tour['tour_name']
                                    ) ?>"
                                    loading="lazy"
                                    onerror="this.style.display='none'"
                                >

                            <?php endif; ?>


                            <span class="tour-category">
                                <?= e(
                                    $tour['category_name']
                                ) ?>
                            </span>


                            <span class="tour-featured-badge">
                                Nổi bật
                            </span>

                        </a>


                        <div class="tour-card-content">

                            <div class="tour-company">
                                <?= e(
                                    $tour['company_name']
                                ) ?>
                            </div>


                            <h3>

                                <a
                                    href="<?= base_url(
                                        'tours/'
                                        . $tour['tour_id']
                                    ) ?>"
                                >
                                    <?= e(
                                        $tour['tour_name']
                                    ) ?>
                                </a>

                            </h3>


                            <p class="tour-description">
                                <?= e(
                                    $tour[
                                        'short_description'
                                    ]
                                    ?? 'Khám phá hành trình và những trải nghiệm đáng nhớ.'
                                ) ?>
                            </p>


                            <div class="tour-meta">

                                <span>
                                    <?= (int)
                                    $tour[
                                        'duration_days'
                                    ] ?>
                                    ngày
                                    <?= (int)
                                    $tour[
                                        'duration_nights'
                                    ] ?>
                                    đêm
                                </span>

                            </div>


                            <div class="tour-card-footer">

                                <div class="tour-price">

                                    <span>
                                        Giá tham khảo
                                    </span>

                                    <strong>
                                        <?= number_format(
                                            (float)
                                            $tour['price'],
                                            0,
                                            ',',
                                            '.'
                                        ) ?>
                                        ₫
                                    </strong>

                                </div>


                                <a
                                    class="tour-detail-button"
                                    href="<?= base_url(
                                        'tours/'
                                        . $tour['tour_id']
                                    ) ?>"
                                    aria-label="Xem chi tiết <?= e(
                                        $tour['tour_name']
                                    ) ?>"
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
                Chưa có Tour nổi bật.
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
                    Khám phá những nơi xuất hiện
                    trong nhiều hành trình nổi bật
                    trên VivuTourViet.
                </p>

            </div>


            <a
                class="section-link"
                href="<?= base_url(
                    'destinations'
                ) ?>"
            >
                Khám phá thêm
                <span>→</span>
            </a>

        </div>


        <?php if (
            !empty(
                $popularLocations
            )
        ): ?>

            <div class="destination-grid home-reveal">

                <?php foreach (
                    $popularLocations
                    as $index => $location
                ): ?>

                    <a
                        class="destination-card <?= $index === 0
                            ? 'destination-card-featured'
                            : '' ?>"
                        href="<?= base_url(
                            'destinations/'
                            . $location['slug']
                        ) ?>"
                    >

                        <div class="destination-image">

                            <div class="destination-placeholder">

                                <?= e(
                                    mb_substr(
                                        $location[
                                            'location_name'
                                        ],
                                        0,
                                        1
                                    )
                                ) ?>

                            </div>


                            <?php if (
                                !empty(
                                    $location['image']
                                )
                            ): ?>

                                <img
                                    src="<?= asset(
                                        ltrim(
                                            $location['image'],
                                            '/'
                                        )
                                    ) ?>"
                                    alt="<?= e(
                                        $location[
                                            'location_name'
                                        ]
                                    ) ?>"
                                    loading="lazy"
                                    onerror="this.style.display='none'"
                                >

                            <?php endif; ?>

                        </div>


                        <div class="destination-overlay"></div>


                        <div class="destination-content">

                            <span class="destination-location">
                                <?= e(
                                    $location[
                                        'province_city'
                                    ]
                                    ?? $location[
                                        'country'
                                    ]
                                ) ?>
                            </span>


                            <h3>
                                <?= e(
                                    $location[
                                        'location_name'
                                    ]
                                ) ?>
                            </h3>


                            <div class="destination-footer">

                                <span>
                                    <?= (int)
                                    $location[
                                        'tour_count'
                                    ] ?>
                                    Tour
                                </span>

                                <strong>
                                    Khám phá →
                                </strong>

                            </div>

                        </div>

                    </a>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <div class="empty-state">
                Chưa có điểm đến nổi bật.
            </div>

        <?php endif; ?>

    </div>

</section>


<section class="home-benefits">

    <div class="page-container">

        <div class="benefit-heading">

            <span class="section-label">
                VivuTourViet
            </span>

            <h2>
                Một nơi để khám phá,
                so sánh và lựa chọn
            </h2>

        </div>


        <div class="benefit-grid home-reveal">

            <article class="benefit-card">

                <span class="benefit-number">
                    01
                </span>

                <h3>
                    Nhiều hành trình
                </h3>

                <p>
                    Khám phá Tour từ nhiều
                    đơn vị lữ hành trên cùng
                    một hệ thống.
                </p>

            </article>


            <article class="benefit-card">

                <span class="benefit-number">
                    02
                </span>

                <h3>
                    So sánh dễ dàng
                </h3>

                <p>
                    Đặt các Tour cạnh nhau để
                    xem giá, thời lượng và hành trình
                    trước khi lựa chọn.
                </p>

            </article>


            <article class="benefit-card">

                <span class="benefit-number">
                    03
                </span>

                <h3>
                    Thông tin tập trung
                </h3>

                <p>
                    Điểm đến, lịch trình,
                    công ty và giá tham khảo
                    được trình bày rõ ràng.
                </p>

            </article>

        </div>

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
                    Tìm Tour phù hợp với
                    phong cách du lịch mà
                    bạn yêu thích.
                </p>

            </div>

        </div>


        <?php if (!empty($categories)): ?>

            <div class="category-grid home-reveal">

                <?php foreach (
                    $categories
                    as $category
                ): ?>

                    <a
                        class="category-card"
                        href="<?= base_url(
                            'tours?category='
                            . $category[
                                'category_id'
                            ]
                        ) ?>"
                    >

                        <div class="category-icon">

                            <?= e(
                                mb_substr(
                                    $category[
                                        'category_name'
                                    ],
                                    0,
                                    1
                                )
                            ) ?>

                        </div>


                        <div class="category-content">

                            <h3>
                                <?= e(
                                    $category[
                                        'category_name'
                                    ]
                                ) ?>
                            </h3>


                            <p>
                                <?= e(
                                    $category[
                                        'description'
                                    ]
                                    ?? 'Khám phá các hành trình phù hợp với sở thích của bạn.'
                                ) ?>
                            </p>


                            <span>
                                <?= (int)
                                $category[
                                    'tour_count'
                                ] ?>
                                Tour
                            </span>

                        </div>


                        <span class="category-arrow">
                            →
                        </span>

                    </a>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </div>

</section>


<section class="home-cta">

    <div class="page-container">

        <div class="cta-container">

            <div class="cta-decoration"></div>


            <div class="cta-content">

                <span>
                    Chưa biết chọn Tour nào?
                </span>

                <h2>
                    So sánh để tìm hành trình
                    phù hợp nhất
                </h2>

                <p>
                    Chọn từ 2 đến 3 Tour và đặt
                    các thông tin quan trọng cạnh nhau
                    để đưa ra lựa chọn dễ dàng hơn.
                </p>

            </div>


            <a
                class="cta-button"
                href="<?= base_url(
                    'compare'
                ) ?>"
            >
                Bắt đầu so sánh
                <span>→</span>
            </a>

        </div>

    </div>

</section>