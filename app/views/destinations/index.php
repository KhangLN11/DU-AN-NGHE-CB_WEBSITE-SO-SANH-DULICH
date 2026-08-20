<section class="destination-hero">

    <div class="page-container">

        <div class="destination-breadcrumb">

            <a href="<?= base_url() ?>">
                Trang chủ
            </a>

            <span>/</span>

            <strong>
                Điểm đến
            </strong>

        </div>


        <div class="destination-hero-content">

            <span>
                Khám phá Việt Nam
            </span>

            <h1>
                Điểm đến nổi bật
            </h1>

            <p>
                Khám phá các địa điểm đang xuất hiện
                trong những hành trình trên TourCompare.
            </p>

        </div>

    </div>

</section>


<section class="destination-main">

    <div class="page-container">

        <div class="destination-toolbar">

            <form
                action="<?= base_url(
                    'destinations'
                ) ?>"
                method="GET"
            >

                <div class="destination-search">

                    <label for="destinationKeyword">
                        Tìm kiếm
                    </label>

                    <input
                        id="destinationKeyword"
                        type="text"
                        name="keyword"
                        value="<?= e(
                            $filters['keyword']
                        ) ?>"
                        placeholder="Tên địa điểm, tỉnh thành..."
                    >

                </div>


                <div class="destination-filter">

                    <label for="destinationProvince">
                        Tỉnh / Thành phố
                    </label>

                    <select
                        id="destinationProvince"
                        name="province"
                    >

                        <option value="">
                            Tất cả
                        </option>

                        <?php foreach (
                            $provinces
                            as $province
                        ): ?>

                            <option
                                value="<?= e(
                                    $province
                                ) ?>"
                                <?= $filters[
                                    'province'
                                ] === $province
                                    ? 'selected'
                                    : '' ?>
                            >
                                <?= e(
                                    $province
                                ) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <button type="submit">
                    Tìm điểm đến
                </button>


                <?php if (
                    $filters['keyword'] !== ''
                    || $filters['province'] !== ''
                ): ?>

                    <a
                        class="destination-reset"
                        href="<?= base_url(
                            'destinations'
                        ) ?>"
                    >
                        Đặt lại
                    </a>

                <?php endif; ?>

            </form>

        </div>


        <div class="destination-result-heading">

            <div>

                <span>
                    Khám phá
                </span>

                <h2>
                    <?= (int)
                    $totalDestinations ?>
                    điểm đến
                </h2>

            </div>

        </div>


        <?php if (
            !empty(
                $destinations
            )
        ): ?>

            <div class="destination-grid">

                <?php foreach (
                    $destinations
                    as $destination
                ): ?>

                    <article class="destination-card">

                        <a
                            class="destination-card-image"
                            href="<?= base_url(
                                'destinations/'
                                . $destination['slug']
                            ) ?>"
                        >

                            <div class="destination-placeholder">

                                <?= e(
                                    mb_substr(
                                        $destination[
                                            'location_name'
                                        ],
                                        0,
                                        1
                                    )
                                ) ?>

                            </div>


                            <?php if (
                                !empty(
                                    $destination[
                                        'image'
                                    ]
                                )
                            ): ?>

                                <img
                                    src="<?= asset(
                                        ltrim(
                                            $destination[
                                                'image'
                                            ],
                                            '/'
                                        )
                                    ) ?>"
                                    alt="<?= e(
                                        $destination[
                                            'location_name'
                                        ]
                                    ) ?>"
                                    loading="lazy"
                                    onerror="this.style.display='none'"
                                >

                            <?php endif; ?>


                            <div class="destination-card-overlay"></div>


                            <div class="destination-card-content">

                                <span>
                                    <?= e(
                                        $destination[
                                            'province_city'
                                        ]
                                        ?? $destination[
                                            'country'
                                        ]
                                    ) ?>
                                </span>

                                <h2>
                                    <?= e(
                                        $destination[
                                            'location_name'
                                        ]
                                    ) ?>
                                </h2>

                                <div class="destination-card-footer">

                                    <strong>
                                        <?= (int)
                                        $destination[
                                            'tour_count'
                                        ] ?>
                                        tour
                                    </strong>

                                    <span>
                                        Khám phá →
                                    </span>

                                </div>

                            </div>

                        </a>

                    </article>

                <?php endforeach; ?>

            </div>


            <?php if (
                $totalPages > 1
            ): ?>

                <nav class="destination-pagination">

                    <?php

                    $query = [
                        'keyword' =>
                            $filters[
                                'keyword'
                            ],

                        'province' =>
                            $filters[
                                'province'
                            ]
                    ];

                    ?>


                    <?php if (
                        $currentPage > 1
                    ): ?>

                        <?php

                        $query['page'] =
                            $currentPage - 1;

                        ?>

                        <a
                            href="<?= base_url(
                                'destinations?'
                                . http_build_query(
                                    $query
                                )
                            ) ?>"
                        >
                            ←
                        </a>

                    <?php endif; ?>


                    <?php for (
                        $page = 1;
                        $page <= $totalPages;
                        $page++
                    ): ?>

                        <?php

                        $query['page'] =
                            $page;

                        ?>

                        <a
                            class="<?= $page
                                === $currentPage
                                    ? 'active'
                                    : '' ?>"
                            href="<?= base_url(
                                'destinations?'
                                . http_build_query(
                                    $query
                                )
                            ) ?>"
                        >
                            <?= $page ?>
                        </a>

                    <?php endfor; ?>


                    <?php if (
                        $currentPage
                        < $totalPages
                    ): ?>

                        <?php

                        $query['page'] =
                            $currentPage + 1;

                        ?>

                        <a
                            href="<?= base_url(
                                'destinations?'
                                . http_build_query(
                                    $query
                                )
                            ) ?>"
                        >
                            →
                        </a>

                    <?php endif; ?>

                </nav>

            <?php endif; ?>

        <?php else: ?>

            <div class="destination-empty">

                <div>
                    MAP
                </div>

                <h3>
                    Không tìm thấy điểm đến
                </h3>

                <p>
                    Hãy thử thay đổi từ khóa
                    hoặc bộ lọc.
                </p>

            </div>

        <?php endif; ?>

    </div>

</section>