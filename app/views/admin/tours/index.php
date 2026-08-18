<?php

$paginationQuery = $_GET;

unset(
    $paginationQuery['page']
);

$hasFilters =
    $filters['keyword'] !== ''
    || $filters['status'] !== ''
    || $filters['category'] > 0
    || $filters['company'] > 0;

?>

<section class="admin-page">

    <div class="admin-page-heading admin-tour-heading">

        <div>

            <span>
                Tour Management
            </span>

            <h1>
                Quản lý Tour
            </h1>

            <p>
                Xem và quản lý toàn bộ Tour
                trong hệ thống TourCompare.
            </p>

        </div>

        <?php if (!empty($successMessage)): ?>

            <div class="admin-tour-alert success">
                <?= e($successMessage) ?>
            </div>

        <?php endif; ?>

                <?php if (!empty($errorMessage)): ?>

                    <div class="admin-tour-alert error">
                        <?= e($errorMessage) ?>
                    </div>

                <?php endif; ?>

        <a
            class="admin-primary-button"
            href="<?= base_url(
                'admin/tours/create'
            ) ?>"
        >
            + Thêm Tour
        </a>

    </div>


    <div class="admin-tour-filter-card">

        <form
            class="admin-tour-filter"
            action="<?= base_url(
                'admin/tours'
            ) ?>"
            method="GET"
        >

            <div class="admin-filter-search">

                <label for="keyword">
                    Tìm kiếm
                </label>

                <input
                    id="keyword"
                    type="text"
                    name="keyword"
                    value="<?= e(
                        $filters['keyword']
                    ) ?>"
                    placeholder="Tên Tour, công ty, danh mục..."
                >

            </div>


            <div class="admin-filter-field">

                <label for="status">
                    Trạng thái
                </label>

                <select
                    id="status"
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
                        Active
                    </option>

                    <option
                        value="inactive"
                        <?= $filters['status']
                            === 'inactive'
                            ? 'selected'
                            : '' ?>
                    >
                        Inactive
                    </option>

                </select>

            </div>


            <div class="admin-filter-field">

                <label for="category">
                    Danh mục
                </label>

                <select
                    id="category"
                    name="category"
                >

                    <option value="">
                        Tất cả
                    </option>

                    <?php foreach (
                        $categories
                        as $category
                    ): ?>

                        <option
                            value="<?= (int)
                            $category[
                                'category_id'
                            ] ?>"
                            <?= $filters[
                                'category'
                            ] ===
                            (int)
                            $category[
                                'category_id'
                            ]
                                ? 'selected'
                                : '' ?>
                        >
                            <?= e(
                                $category[
                                    'category_name'
                                ]
                            ) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <div class="admin-filter-field">

                <label for="company">
                    Công ty
                </label>

                <select
                    id="company"
                    name="company"
                >

                    <option value="">
                        Tất cả
                    </option>

                    <?php foreach (
                        $companies
                        as $company
                    ): ?>

                        <option
                            value="<?= (int)
                            $company[
                                'company_id'
                            ] ?>"
                            <?= $filters[
                                'company'
                            ] ===
                            (int)
                            $company[
                                'company_id'
                            ]
                                ? 'selected'
                                : '' ?>
                        >
                            <?= e(
                                $company[
                                    'company_name'
                                ]
                            ) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <button
                class="admin-filter-button"
                type="submit"
            >
                Lọc
            </button>


            <?php if ($hasFilters): ?>

                <a
                    class="admin-filter-reset"
                    href="<?= base_url(
                        'admin/tours'
                    ) ?>"
                >
                    Xóa lọc
                </a>

            <?php endif; ?>

        </form>

    </div>


    <div class="admin-tour-summary">

        <div>

            <strong>
                <?= (int) $totalTours ?>
            </strong>

            <span>
                Tour
            </span>

        </div>

        <?php if ($hasFilters): ?>

            <span class="admin-filter-active">
                Đang áp dụng bộ lọc
            </span>

        <?php endif; ?>

    </div>


    <div class="admin-tour-table-card">

        <?php if (!empty($tours)): ?>

            <div class="admin-tour-table-wrapper">

                <table class="admin-tour-table">

                    <thead>

                        <tr>

                            <th>
                                Tour
                            </th>

                            <th>
                                Danh mục
                            </th>

                            <th>
                                Công ty
                            </th>

                            <th>
                                Thời gian
                            </th>

                            <th>
                                Giá
                            </th>

                            <th>
                                Điểm đến
                            </th>

                            <th>
                                Featured
                            </th>

                            <th>
                                Trạng thái
                            </th>

                            <th>
                                Thao tác
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php foreach (
                            $tours
                            as $tour
                        ): ?>

                            <tr>

                                <td>

                                    <div class="admin-tour-info">

                                        <div
                                            class="admin-tour-thumbnail"
                                        >

                                            <div
                                                class="admin-tour-placeholder"
                                            >
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
                                                    onerror="this.style.display='none'"
                                                >

                                            <?php endif; ?>

                                        </div>


                                        <div>

                                            <a
                                                class="admin-tour-name"
                                                href="<?= base_url(
                                                    'tours/'
                                                    . $tour[
                                                        'tour_id'
                                                    ]
                                                ) ?>"
                                                target="_blank"
                                            >
                                                <?= e(
                                                    $tour[
                                                        'tour_name'
                                                    ]
                                                ) ?>
                                            </a>

                                            <span>
                                                ID:
                                                <?= (int)
                                                $tour[
                                                    'tour_id'
                                                ] ?>
                                            </span>

                                        </div>

                                    </div>

                                </td>


                                <td>
                                    <?= e(
                                        $tour[
                                            'category_name'
                                        ]
                                    ) ?>
                                </td>


                                <td>
                                    <?= e(
                                        $tour[
                                            'company_name'
                                        ]
                                    ) ?>
                                </td>


                                <td>

                                    <span
                                        class="admin-tour-duration"
                                    >
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

                                </td>


                                <td>

                                    <strong
                                        class="admin-tour-price"
                                    >
                                        <?= number_format(
                                            (float)
                                            $tour['price'],
                                            0,
                                            ',',
                                            '.'
                                        ) ?>
                                        ₫
                                    </strong>

                                </td>


                                <td>

                                    <div
                                        class="admin-tour-destinations"
                                    >
                                        <?= e(
                                            $tour[
                                                'destinations'
                                            ]
                                            ?? 'Chưa cập nhật'
                                        ) ?>
                                    </div>

                                </td>


                                <td>

                                    <?php if (
                                        (int)
                                        $tour[
                                            'featured'
                                        ] === 1
                                    ): ?>

                                        <span
                                            class="admin-featured yes"
                                        >
                                            Có
                                        </span>

                                    <?php else: ?>

                                        <span
                                            class="admin-featured no"
                                        >
                                            Không
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <span
                                        class="admin-tour-status
                                        <?= e(
                                            $tour[
                                                'status'
                                            ]
                                        ) ?>"
                                    >
                                        <?= e(
                                            $tour[
                                                'status'
                                            ]
                                        ) ?>
                                    </span>

                                </td>


                                <td>

                                    <div
                                        class="admin-tour-actions"
                                    >

                                        <a
                                            class="admin-action-button view"
                                            href="<?= base_url(
                                                'tours/'
                                                . $tour[
                                                    'tour_id'
                                                ]
                                            ) ?>"
                                            target="_blank"
                                            title="Xem"
                                        >
                                            Xem
                                        </a>

                                        <a
                                            class="admin-action-button edit"
                                            href="<?= base_url(
                                                'admin/tours/'
                                                . $tour[
                                                    'tour_id'
                                                ]
                                                . '/edit'
                                            ) ?>"
                                            title="Chỉnh sửa"
                                        >
                                            Sửa
                                        </a>

                                        <form
                                            class="admin-delete-form"
                                            action="<?= base_url(
                                                'admin/tours/'
                                                . $tour['tour_id']
                                                . '/delete'
                                            ) ?>"
                                            method="POST"
                                            onsubmit="return confirm(
                                                'Bạn có chắc muốn xóa Tour này? Hành động này không thể hoàn tác.'
                                            );"
                                        >

                                            <button
                                                class="admin-action-button delete"
                                                type="submit"
                                            >
                                                Xóa
                                            </button>

                                        </form>

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

                <nav
                    class="admin-pagination"
                    aria-label="Phân trang Tour Admin"
                >

                    <?php if (
                        $currentPage > 1
                    ): ?>

                        <?php

                        $previousQuery =
                            $paginationQuery;

                        $previousQuery['page'] =
                            $currentPage - 1;

                        ?>

                        <a
                            href="<?= base_url(
                                'admin/tours?'
                                . http_build_query(
                                    $previousQuery
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

                        $pageQuery =
                            $paginationQuery;

                        $pageQuery['page'] =
                            $page;

                        ?>

                        <a
                            class="<?= $page
                                === $currentPage
                                ? 'active'
                                : '' ?>"
                            href="<?= base_url(
                                'admin/tours?'
                                . http_build_query(
                                    $pageQuery
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

                        $nextQuery =
                            $paginationQuery;

                        $nextQuery['page'] =
                            $currentPage + 1;

                        ?>

                        <a
                            href="<?= base_url(
                                'admin/tours?'
                                . http_build_query(
                                    $nextQuery
                                )
                            ) ?>"
                        >
                            →
                        </a>

                    <?php endif; ?>

                </nav>

            <?php endif; ?>


        <?php else: ?>

            <div class="admin-tour-empty">

                <div>
                    T
                </div>

                <h2>
                    Không tìm thấy Tour
                </h2>

                <p>
                    Không có Tour nào phù hợp
                    với điều kiện hiện tại.
                </p>

                <?php if ($hasFilters): ?>

                    <a
                        href="<?= base_url(
                            'admin/tours'
                        ) ?>"
                    >
                        Xóa bộ lọc
                    </a>

                <?php endif; ?>

            </div>

        <?php endif; ?>

    </div>

</section>