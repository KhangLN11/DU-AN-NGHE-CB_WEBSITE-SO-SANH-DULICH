<section class="favorites-hero">

    <div class="page-container">

        <span class="favorites-label">
            Tài khoản
        </span>

        <h1>
            Tour yêu thích
        </h1>

        <p>
            Những hành trình bạn đã lưu
            để xem lại sau.
        </p>

    </div>

</section>

<section class="favorites-section">

    <div class="page-container">

        <div class="favorites-toolbar">

            <div>

                <strong>
                    <?= (int) $favoriteCount ?>
                </strong>

                <span>
                    Tour đã lưu
                </span>

            </div>

            <a href="<?= base_url('tours') ?>">
                Khám phá thêm Tour →
            </a>

        </div>

        <?php if (!empty($favorites)): ?>

            <div class="favorites-grid">

                <?php foreach (
                    $favorites as $tour
                ): ?>

                    <article class="favorite-tour-card">

                        <a
                            class="favorite-tour-image"
                            href="<?= base_url(
                                'tours/'
                                . $tour['tour_id']
                            ) ?>"
                        >

                            <div class="favorite-placeholder">

                                <?= e(
                                    mb_substr(
                                        $tour[
                                            'tour_name'
                                        ],
                                        0,
                                        1
                                    )
                                ) ?>

                            </div>

                            <?php if (
                                !empty(
                                    $tour['image_url']
                                )
                            ): ?>

                                <img
                                    src="<?= asset(
                                        ltrim(
                                            $tour[
                                                'image_url'
                                            ],
                                            '/'
                                        )
                                    ) ?>"
                                    alt="<?= e(
                                        $tour[
                                            'tour_name'
                                        ]
                                    ) ?>"
                                    loading="lazy"
                                    onerror="this.style.display='none'"
                                >

                            <?php endif; ?>

                            <?php if (
                                (int)
                                $tour['featured']
                                === 1
                            ): ?>

                                <span
                                    class="favorite-featured"
                                >
                                    Nổi bật
                                </span>

                            <?php endif; ?>

                        </a>

                        <div class="favorite-tour-content">

                            <span class="favorite-company">

                                <?= e(
                                    $tour[
                                        'company_name'
                                    ]
                                ) ?>

                            </span>

                            <h2>

                                <a href="<?= base_url(
                                    'tours/'
                                    . $tour['tour_id']
                                ) ?>">
                                    <?= e(
                                        $tour[
                                            'tour_name'
                                        ]
                                    ) ?>
                                </a>

                            </h2>

                            <p>
                                <?= e(
                                    $tour[
                                        'short_description'
                                    ]
                                ) ?>
                            </p>

                            <div class="favorite-meta">

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

                                <span>
                                    <?= e(
                                        $tour[
                                            'departure_name'
                                        ]
                                        ?? 'Đang cập nhật'
                                    ) ?>
                                </span>

                            </div>

                            <div
                                class="favorite-destinations"
                            >

                                <span>
                                    Điểm đến
                                </span>

                                <strong>
                                    <?= e(
                                        $tour[
                                            'destinations'
                                        ]
                                        ?? 'Đang cập nhật'
                                    ) ?>
                                </strong>

                            </div>

                            <div class="favorite-footer">

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

                                <div class="favorite-actions">

                                    <a
                                        href="<?= base_url(
                                            'tours/'
                                            . $tour[
                                                'tour_id'
                                            ]
                                        ) ?>"
                                    >
                                        Xem chi tiết
                                    </a>

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
                                            $tour[
                                                'tour_id'
                                            ] ?>"
                                        >

                                        <input
                                            type="hidden"
                                            name="return_to"
                                            value="favorites"
                                        >

                                        <button
                                            type="submit"
                                        >
                                            Xóa
                                        </button>

                                    </form>

                                </div>

                            </div>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <div class="favorites-empty">

                <div class="favorites-empty-icon">
                    ♡
                </div>

                <h2>
                    Bạn chưa lưu Tour nào
                </h2>

                <p>
                    Hãy khám phá danh sách Tour
                    và lưu những hành trình
                    bạn quan tâm.
                </p>

                <a href="<?= base_url('tours') ?>">
                    Khám phá Tour
                </a>

            </div>

        <?php endif; ?>

    </div>

</section>