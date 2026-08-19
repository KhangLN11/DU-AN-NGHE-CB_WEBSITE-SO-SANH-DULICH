<section class="admin-page">

    <div class="admin-page-heading admin-location-heading">

        <div>

            <span>
                Location Management
            </span>

            <h1>
                Địa điểm
            </h1>

            <p>
                Quản lý điểm khởi hành,
                điểm đến và dữ liệu bản đồ
                của TourCompare.
            </p>

        </div>

        <span class="admin-location-create disabled">
            + Thêm địa điểm
        </span>

    </div>


    <?php if (!empty($successMessage)): ?>

        <div class="admin-location-alert success">
            <?= e($successMessage) ?>
        </div>

    <?php endif; ?>


    <?php if (!empty($errorMessage)): ?>

        <div class="admin-location-alert error">
            <?= e($errorMessage) ?>
        </div>

    <?php endif; ?>


    <section class="admin-location-toolbar">

        <form
            action="<?= base_url(
                'admin/locations'
            ) ?>"
            method="GET"
        >

            <div class="admin-location-search">

                <label for="locationKeyword">
                    Tìm kiếm
                </label>

                <input
                    id="locationKeyword"
                    type="text"
                    name="keyword"
                    value="<?= e(
                        $filters['keyword']
                    ) ?>"
                    placeholder="Tên, tỉnh/thành, địa chỉ..."
                >

            </div>


            <div class="admin-location-filter">

                <label for="locationCountry">
                    Quốc gia
                </label>

                <select
                    id="locationCountry"
                    name="country"
                >

                    <option value="">
                        Tất cả
                    </option>

                    <?php foreach (
                        $countries
                        as $country
                    ): ?>

                        <option
                            value="<?= e($country) ?>"
                            <?= $filters['country']
                                === $country
                                    ? 'selected'
                                    : '' ?>
                        >
                            <?= e($country) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <div class="admin-location-filter">

                <label for="locationStatus">
                    Trạng thái
                </label>

                <select
                    id="locationStatus"
                    name="status"
                >

                    <option value="">
                        Tất cả
                    </option>

                    <option
                        value="active"
                        <?= $filters['status']
                            === 'active'
                                ? 'selected'
                                : '' ?>
                    >
                        Hoạt động
                    </option>

                    <option
                        value="inactive"
                        <?= $filters['status']
                            === 'inactive'
                                ? 'selected'
                                : '' ?>
                    >
                        Tạm ẩn
                    </option>

                </select>

            </div>


            <button type="submit">
                Lọc
            </button>


            <?php if (
                $filters['keyword'] !== ''
                || $filters['status'] !== ''
                || $filters['country'] !== ''
            ): ?>

                <a
                    class="admin-location-reset"
                    href="<?= base_url(
                        'admin/locations'
                    ) ?>"
                >
                    Đặt lại
                </a>

            <?php endif; ?>

        </form>

    </section>


    <section class="admin-location-list-card">

        <div class="admin-location-list-heading">

            <div>

                <span>
                    Danh sách
                </span>

                <strong>
                    <?= (int)
                    $totalLocations ?>
                    địa điểm
                </strong>

            </div>

        </div>


        <?php if (!empty($locations)): ?>

            <div class="admin-location-table-wrapper">

                <table class="admin-location-table">

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Địa điểm</th>

                            <th>Khu vực</th>

                            <th>Tọa độ</th>

                            <th>Khởi hành</th>

                            <th>Điểm đến</th>

                            <th>Trạng thái</th>

                            <th>Cập nhật</th>

                            <th>Thao tác</th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php foreach (
                            $locations
                            as $location
                        ): ?>

                            <tr>

                                <td class="location-id">

                                    #<?= (int)
                                    $location[
                                        'location_id'
                                    ] ?>

                                </td>


                                <td>

                                    <div class="location-main">

                                        <div class="location-image">

                                            <?php if (
                                                !empty(
                                                    $location[
                                                        'image'
                                                    ]
                                                )
                                            ): ?>

                                                <img
                                                    src="<?= asset(
                                                        ltrim(
                                                            $location[
                                                                'image'
                                                            ],
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

                                            <span>
                                                <?= e(
                                                    mb_substr(
                                                        $location[
                                                            'location_name'
                                                        ],
                                                        0,
                                                        1
                                                    )
                                                ) ?>
                                            </span>

                                        </div>


                                        <div class="location-main-content">

                                            <strong>
                                                <?= e(
                                                    $location[
                                                        'location_name'
                                                    ]
                                                ) ?>
                                            </strong>

                                            <code>
                                                <?= e(
                                                    $location[
                                                        'slug'
                                                    ]
                                                ) ?>
                                            </code>

                                        </div>

                                    </div>

                                </td>


                                <td>

                                    <div class="location-region">

                                        <?php if (
                                            !empty(
                                                $location[
                                                    'province_city'
                                                ]
                                            )
                                        ): ?>

                                            <strong>
                                                <?= e(
                                                    $location[
                                                        'province_city'
                                                    ]
                                                ) ?>
                                            </strong>

                                        <?php endif; ?>

                                        <span>
                                            <?= e(
                                                $location[
                                                    'country'
                                                ]
                                            ) ?>
                                        </span>

                                    </div>

                                </td>


                                <td>

                                    <?php if (
                                        $location['latitude']
                                            !== null
                                        && $location['longitude']
                                            !== null
                                    ): ?>

                                        <div class="location-coordinate">

                                            <span>
                                                <?= e(
                                                    $location[
                                                        'latitude'
                                                    ]
                                                ) ?>
                                            </span>

                                            <small>
                                                <?= e(
                                                    $location[
                                                        'longitude'
                                                    ]
                                                ) ?>
                                            </small>

                                        </div>

                                    <?php else: ?>

                                        <span class="location-muted">
                                            Chưa có
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <span class="location-use-count departure">
                                        <?= (int)
                                        $location[
                                            'departure_tour_count'
                                        ] ?>
                                    </span>

                                </td>


                                <td>

                                    <span class="location-use-count destination">
                                        <?= (int)
                                        $location[
                                            'destination_tour_count'
                                        ] ?>
                                    </span>

                                </td>


                                <td>

                                    <?php if (
                                        $location[
                                            'status'
                                        ] === 'active'
                                    ): ?>

                                        <span class="location-status active">
                                            Hoạt động
                                        </span>

                                    <?php else: ?>

                                        <span class="location-status inactive">
                                            Tạm ẩn
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <span class="location-date">

                                        <?= !empty(
                                            $location[
                                                'updated_at'
                                            ]
                                        )
                                            ? e(
                                                date(
                                                    'd/m/Y',
                                                    strtotime(
                                                        $location[
                                                            'updated_at'
                                                        ]
                                                    )
                                                )
                                            )
                                            : '-' ?>

                                    </span>

                                </td>


                                <td>

                                    <div class="admin-location-actions">

                                        <span
                                            class="location-action edit disabled"
                                        >
                                            Sửa
                                        </span>

                                        <span
                                            class="location-action delete disabled"
                                        >
                                            Xóa
                                        </span>

                                    </div>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>


            <?php if (
                $totalPages > 1
            ): ?>

                <nav class="admin-location-pagination">

                    <?php

                    $query = [
                        'keyword' =>
                            $filters['keyword'],

                        'status' =>
                            $filters['status'],

                        'country' =>
                            $filters['country']
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
                                'admin/locations?'
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
                                'admin/locations?'
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
                                'admin/locations?'
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

            <div class="admin-location-empty">

                <div>
                    LOC
                </div>

                <h3>
                    Không tìm thấy địa điểm
                </h3>

                <p>
                    Hãy thử thay đổi từ khóa
                    hoặc bộ lọc.
                </p>

            </div>

        <?php endif; ?>

    </section>

</section>