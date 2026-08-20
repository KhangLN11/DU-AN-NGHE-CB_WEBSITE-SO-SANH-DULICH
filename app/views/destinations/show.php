<section class="destination-detail-hero">

    <div class="page-container">

        <div class="destination-detail-breadcrumb">

            <a href="<?= base_url() ?>">
                Trang chủ
            </a>

            <span>/</span>

            <a href="<?= base_url(
                'destinations'
            ) ?>">
                Điểm đến
            </a>

            <span>/</span>

            <strong>
                <?= e(
                    $destination[
                        'location_name'
                    ]
                ) ?>
            </strong>

        </div>


        <div class="destination-detail-hero-grid">

            <div class="destination-detail-heading">

                <span class="destination-detail-label">
                    Điểm đến
                </span>


                <h1>
                    <?= e(
                        $destination[
                            'location_name'
                        ]
                    ) ?>
                </h1>


                <div class="destination-detail-meta">

                    <?php if (
                        !empty(
                            $destination[
                                'province_city'
                            ]
                        )
                    ): ?>

                        <span>
                            <?= e(
                                $destination[
                                    'province_city'
                                ]
                            ) ?>
                        </span>

                    <?php endif; ?>


                    <?php if (
                        !empty(
                            $destination[
                                'country'
                            ]
                        )
                    ): ?>

                        <span>
                            <?= e(
                                $destination[
                                    'country'
                                ]
                            ) ?>
                        </span>

                    <?php endif; ?>


                    <span>
                        <?= (int)
                        $destination[
                            'tour_count'
                        ] ?>
                        tour
                    </span>

                </div>


                <?php if (
                    !empty(
                        $destination[
                            'description'
                        ]
                    )
                ): ?>

                    <p>
                        <?= e(
                            mb_strlen(
                                $destination[
                                    'description'
                                ]
                            ) > 220
                                ? mb_substr(
                                    $destination[
                                        'description'
                                    ],
                                    0,
                                    220
                                )
                                    . '...'
                                : $destination[
                                    'description'
                                ]
                        ) ?>
                    </p>

                <?php else: ?>

                    <p>
                        Khám phá
                        <?= e(
                            $destination[
                                'location_name'
                            ]
                        ) ?>
                        và các hành trình nổi bật
                        trên TourCompare.
                    </p>

                <?php endif; ?>

            </div>


            <div class="destination-detail-image">

                <div class="destination-detail-placeholder">

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
                        onerror="this.style.display='none'"
                    >

                <?php endif; ?>

            </div>

        </div>

    </div>

</section>


<section class="destination-detail-main">

    <div class="page-container">

        <div class="destination-detail-layout">

            <div class="destination-detail-content">

                <section class="destination-detail-section">

                    <div class="destination-section-heading">

                        <span>
                            Tổng quan
                        </span>

                        <h2>
                            Giới thiệu điểm đến
                        </h2>

                    </div>


                    <?php if (
                        !empty(
                            $destination[
                                'description'
                            ]
                        )
                    ): ?>

                        <div class="destination-description">

                            <?= nl2br(
                                e(
                                    $destination[
                                        'description'
                                    ]
                                )
                            ) ?>

                        </div>

                    <?php else: ?>

                        <div class="destination-detail-empty">
                            Thông tin giới thiệu
                            đang được cập nhật.
                        </div>

                    <?php endif; ?>

                </section>


                <?php if (
                    $hasCoordinates
                ): ?>

                    <section class="destination-detail-section">

                        <div class="destination-section-heading">

                            <span>
                                Bản đồ
                            </span>

                            <h2>
                                Vị trí
                            </h2>

                        </div>


                        <div class="destination-map-wrapper">

                            <div
                                id="destinationMap"
                                class="destination-map"
                            ></div>


                            <script
                                id="destinationMapData"
                                type="application/json"
                            ><?= json_encode(
                                [
                                    'name' =>
                                        $destination[
                                            'location_name'
                                        ],

                                    'province' =>
                                        $destination[
                                            'province_city'
                                        ],

                                    'country' =>
                                        $destination[
                                            'country'
                                        ],

                                    'latitude' =>
                                        (float)
                                        $destination[
                                            'latitude'
                                        ],

                                    'longitude' =>
                                        (float)
                                        $destination[
                                            'longitude'
                                        ]
                                ],
                                JSON_UNESCAPED_UNICODE
                                | JSON_UNESCAPED_SLASHES
                                | JSON_HEX_TAG
                                | JSON_HEX_AMP
                                | JSON_HEX_APOS
                                | JSON_HEX_QUOT
                            ) ?></script>

                        </div>

                    </section>

                <?php endif; ?>


                <section class="destination-detail-section destination-tour-section">

    <div class="destination-section-heading destination-tour-heading">

        <div>

            <span>
                Hành trình
            </span>

            <h2>
                Tour tại
                <?= e(
                    $destination[
                        'location_name'
                    ]
                ) ?>
            </h2>

        </div>


        <?php if (
            $totalDestinationTours > 0
        ): ?>

            <strong>
                <?= (int)
                $totalDestinationTours ?>
                tour
            </strong>

        <?php endif; ?>

    </div>


    <?php if (
        !empty(
            $destinationTours
        )
    ): ?>

        <div class="destination-tour-grid">

            <?php foreach (
                $destinationTours
                as $tour
            ): ?>

                <article class="destination-tour-card">

                    <a
                        class="destination-tour-image"
                        href="<?= base_url(
                            'tours/'
                            . $tour['tour_id']
                        ) ?>"
                    >

                        <div class="destination-tour-placeholder">

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
                                $tour[
                                    'image_url'
                                ]
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
                            (int) $tour[
                                'featured'
                            ] === 1
                        ): ?>

                            <span class="destination-tour-featured">
                                Nổi bật
                            </span>

                        <?php endif; ?>

                    </a>


                    <div class="destination-tour-content">

                        <div class="destination-tour-meta">

                            <span>
                                <?= e(
                                    $tour[
                                        'category_name'
                                    ]
                                ) ?>
                            </span>

                            <span>
                                <?= e(
                                    $tour[
                                        'company_name'
                                    ]
                                ) ?>
                            </span>

                        </div>


                        <h3>

                            <a
                                href="<?= base_url(
                                    'tours/'
                                    . $tour[
                                        'tour_id'
                                    ]
                                ) ?>"
                            >
                                <?= e(
                                    $tour[
                                        'tour_name'
                                    ]
                                ) ?>
                            </a>

                        </h3>


                        <?php if (
                            !empty(
                                $tour[
                                    'short_description'
                                ]
                            )
                        ): ?>

                            <p>
                                <?= e(
                                    mb_strlen(
                                        $tour[
                                            'short_description'
                                        ]
                                    ) > 100
                                        ? mb_substr(
                                            $tour[
                                                'short_description'
                                            ],
                                            0,
                                            100
                                        )
                                            . '...'
                                        : $tour[
                                            'short_description'
                                        ]
                                ) ?>
                            </p>

                        <?php endif; ?>


                        <div class="destination-tour-info">

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
                                Khởi hành:
                                <?= e(
                                    $tour[
                                        'departure_name'
                                    ]
                                    ?? 'Đang cập nhật'
                                ) ?>
                            </span>

                        </div>


                        <div class="destination-tour-footer">

                            <div>

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
                                href="<?= base_url(
                                    'tours/'
                                    . $tour[
                                        'tour_id'
                                    ]
                                ) ?>"
                            >
                                Xem Tour →
                            </a>

                        </div>

                    </div>

                </article>

            <?php endforeach; ?>

        </div>


        <?php if (
            $totalDestinationTours
            > count(
                $destinationTours
            )
        ): ?>

            <div class="destination-tour-more">

                <a
                    href="<?= base_url(
                        'tours?location='
                        . $destination[
                            'location_id'
                        ]
                    ) ?>"
                >
                    Xem tất cả
                    <?= (int)
                    $totalDestinationTours ?>
                    Tour tại
                    <?= e(
                        $destination[
                            'location_name'
                        ]
                    ) ?>
                    →
                </a>

            </div>

        <?php endif; ?>

    <?php else: ?>

        <div class="destination-tour-empty">

            <strong>
                Chưa có Tour
            </strong>

            <p>
                Hiện chưa có Tour đang hoạt động
                đi qua
                <?= e(
                    $destination[
                        'location_name'
                    ]
                ) ?>.
            </p>

            <a href="<?= base_url(
                'tours'
            ) ?>">
                Khám phá tất cả Tour
            </a>

        </div>

    <?php endif; ?>

</section>

            </div>


            <aside class="destination-detail-sidebar">

                <section class="destination-info-card">

                    <div class="destination-info-heading">

                        <span>
                            Thông tin
                        </span>

                        <h2>
                            <?= e(
                                $destination[
                                    'location_name'
                                ]
                            ) ?>
                        </h2>

                    </div>


                    <div class="destination-info-list">

                        <div class="destination-info-row">

                            <span>
                                Tỉnh / Thành phố
                            </span>

                            <strong>
                                <?= !empty(
                                    $destination[
                                        'province_city'
                                    ]
                                )
                                    ? e(
                                        $destination[
                                            'province_city'
                                        ]
                                    )
                                    : 'Đang cập nhật' ?>
                            </strong>

                        </div>


                        <div class="destination-info-row">

                            <span>
                                Quốc gia
                            </span>

                            <strong>
                                <?= e(
                                    $destination[
                                        'country'
                                    ]
                                ) ?>
                            </strong>

                        </div>


                        <div class="destination-info-row">

                            <span>
                                Số Tour
                            </span>

                            <strong>
                                <?= (int)
                                $destination[
                                    'tour_count'
                                ] ?>
                            </strong>

                        </div>


                        <div class="destination-info-row">

                            <span>
                                Địa chỉ
                            </span>

                            <strong>
                                <?= !empty(
                                    $destination[
                                        'address'
                                    ]
                                )
                                    ? e(
                                        $destination[
                                            'address'
                                        ]
                                    )
                                    : 'Đang cập nhật' ?>
                            </strong>

                        </div>

                    </div>


                    <?php if (
                        $hasCoordinates
                    ): ?>

                        <div class="destination-coordinate-box">

                            <span>
                                Tọa độ
                            </span>

                            <strong>
                                <?= e(
                                    $destination[
                                        'latitude'
                                    ]
                                ) ?>,
                                <?= e(
                                    $destination[
                                        'longitude'
                                    ]
                                ) ?>
                            </strong>

                        </div>

                    <?php endif; ?>


                    <a
                        class="destination-back-button"
                        href="<?= base_url(
                            'destinations'
                        ) ?>"
                    >
                        ← Tất cả điểm đến
                    </a>

                </section>

            </aside>

        </div>

    </div>

</section>