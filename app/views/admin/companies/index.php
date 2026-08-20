<section class="admin-page">

    <div class="admin-page-heading admin-company-heading">

        <div>

            <span>
                Company Management
            </span>

            <h1>
                Công ty
            </h1>

            <p>
                Quản lý các đơn vị lữ hành
                và nhà cung cấp Tour trên VivuTourViet.
            </p>

        </div>

        <a class="admin-company-create" href="<?= base_url( 'admin/companies/create' ) ?>" >
            + Thêm công ty
        </a>

    </div>


    <?php if (!empty($successMessage)): ?>

        <div class="admin-company-alert success">
            <?= e($successMessage) ?>
        </div>

    <?php endif; ?>


    <?php if (!empty($errorMessage)): ?>

        <div class="admin-company-alert error">
            <?= e($errorMessage) ?>
        </div>

    <?php endif; ?>


    <section class="admin-company-toolbar">

        <form
            action="<?= base_url(
                'admin/companies'
            ) ?>"
            method="GET"
        >

            <div class="admin-company-search">

                <label for="companyKeyword">
                    Tìm kiếm
                </label>

                <input
                    id="companyKeyword"
                    type="text"
                    name="keyword"
                    value="<?= e(
                        $filters['keyword']
                    ) ?>"
                    placeholder="Tên, slug, email, điện thoại, địa chỉ..."
                >

            </div>


            <div class="admin-company-filter">

                <label for="companyStatus">
                    Trạng thái
                </label>

                <select
                    id="companyStatus"
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
            ): ?>

                <a
                    class="admin-company-reset"
                    href="<?= base_url(
                        'admin/companies'
                    ) ?>"
                >
                    Đặt lại
                </a>

            <?php endif; ?>

        </form>

    </section>


    <section class="admin-company-list-card">

        <div class="admin-company-list-heading">

            <div>

                <span>
                    Danh sách
                </span>

                <strong>
                    <?= (int)
                    $totalCompanies ?>
                    công ty
                </strong>

            </div>

        </div>


        <?php if (!empty($companies)): ?>

            <div class="admin-company-table-wrapper">

                <table class="admin-company-table">

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Công ty</th>

                            <th>Liên hệ</th>

                            <th>Website</th>

                            <th>Số Tour</th>

                            <th>Trạng thái</th>

                            <th>Cập nhật</th>

                            <th>Thao tác</th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php foreach (
                            $companies
                            as $company
                        ): ?>

                            <tr>

                                <td class="company-id">

                                    #<?= (int)
                                    $company[
                                        'company_id'
                                    ] ?>

                                </td>


                                <td>

                                    <div class="company-main">

                                        <div class="company-logo">

                                            <?php if (
                                                !empty(
                                                    $company[
                                                        'logo'
                                                    ]
                                                )
                                            ): ?>

                                                <img
                                                    src="<?= asset(
                                                        ltrim(
                                                            $company[
                                                                'logo'
                                                            ],
                                                            '/'
                                                        )
                                                    ) ?>"
                                                    alt="<?= e(
                                                        $company[
                                                            'company_name'
                                                        ]
                                                    ) ?>"
                                                    loading="lazy"
                                                    onerror="this.style.display='none'"
                                                >

                                            <?php endif; ?>

                                            <span>
                                                <?= e(
                                                    mb_substr(
                                                        $company[
                                                            'company_name'
                                                        ],
                                                        0,
                                                        1
                                                    )
                                                ) ?>
                                            </span>

                                        </div>


                                        <div class="company-main-content">

                                            <strong>
                                                <?= e(
                                                    $company[
                                                        'company_name'
                                                    ]
                                                ) ?>
                                            </strong>

                                            <code>
                                                <?= e(
                                                    $company[
                                                        'slug'
                                                    ]
                                                ) ?>
                                            </code>

                                        </div>

                                    </div>

                                </td>


                                <td>

                                    <div class="company-contact">

                                        <?php if (
                                            !empty(
                                                $company[
                                                    'email'
                                                ]
                                            )
                                        ): ?>

                                            <span>
                                                <?= e(
                                                    $company[
                                                        'email'
                                                    ]
                                                ) ?>
                                            </span>

                                        <?php endif; ?>


                                        <?php if (
                                            !empty(
                                                $company[
                                                    'phone'
                                                ]
                                            )
                                        ): ?>

                                            <small>
                                                <?= e(
                                                    $company[
                                                        'phone'
                                                    ]
                                                ) ?>
                                            </small>

                                        <?php endif; ?>


                                        <?php if (
                                            empty(
                                                $company[
                                                    'email'
                                                ]
                                            )
                                            && empty(
                                                $company[
                                                    'phone'
                                                ]
                                            )
                                        ): ?>

                                            <span>-</span>

                                        <?php endif; ?>

                                    </div>

                                </td>


                                <td>

                                    <?php if (
                                        !empty(
                                            $company[
                                                'website'
                                            ]
                                        )
                                    ): ?>

                                        <a
                                            class="company-website"
                                            href="<?= e(
                                                $company[
                                                    'website'
                                                ]
                                            ) ?>"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                        >
                                            Truy cập
                                        </a>

                                    <?php else: ?>

                                        <span class="company-muted">
                                            -
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <span class="company-tour-count">
                                        <?= (int)
                                        $company[
                                            'tour_count'
                                        ] ?>
                                    </span>

                                </td>


                                <td>

                                    <?php if (
                                        $company[
                                            'status'
                                        ] === 'active'
                                    ): ?>

                                        <span class="company-status active">
                                            Hoạt động
                                        </span>

                                    <?php else: ?>

                                        <span class="company-status inactive">
                                            Tạm ẩn
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <span class="company-date">

                                        <?= !empty(
                                            $company[
                                                'updated_at'
                                            ]
                                        )
                                            ? e(
                                                date(
                                                    'd/m/Y',
                                                    strtotime(
                                                        $company[
                                                            'updated_at'
                                                        ]
                                                    )
                                                )
                                            )
                                            : '-' ?>

                                    </span>

                                </td>


                                <td>

                                    <div class="admin-company-actions">

    <a
        class="company-action edit"
        href="<?= base_url(
            'admin/companies/'
            . $company[
                'company_id'
            ]
            . '/edit'
        ) ?>"
    >
        Sửa
    </a>


    <?php if (
        $company['status']
        === 'active'
    ): ?>

        <form
            action="<?= base_url(
                'admin/companies/'
                . $company[
                    'company_id'
                ]
                . '/disable'
            ) ?>"
            method="POST"
            onsubmit="return confirm(
                'Bạn có chắc muốn tạm ẩn công ty này?'
            );"
        >

            <button
                class="company-action disable"
                type="submit"
            >
                Tạm ẩn
            </button>

        </form>

    <?php endif; ?>


    <?php if (
        (int) $company[
            'tour_count'
        ] === 0
    ): ?>

        <form
            action="<?= base_url(
                'admin/companies/'
                . $company[
                    'company_id'
                ]
                . '/delete'
            ) ?>"
            method="POST"
            onsubmit="return confirm(
                'Bạn có chắc muốn xóa vĩnh viễn công ty này? Hành động này không thể hoàn tác.'
            );"
        >

            <button
                class="company-action delete"
                type="submit"
            >
                Xóa
            </button>

        </form>

    <?php else: ?>

        <span
            class="company-action delete locked"
            title="Công ty đang được Tour sử dụng"
        >
            Xóa
        </span>

    <?php endif; ?>

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

                <nav class="admin-company-pagination">

                    <?php

                    $query = [
                        'keyword' =>
                            $filters['keyword'],

                        'status' =>
                            $filters['status']
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
                                'admin/companies?'
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
                                'admin/companies?'
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
                                'admin/companies?'
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

            <div class="admin-company-empty">

                <div>
                    CO
                </div>

                <h3>
                    Không tìm thấy công ty
                </h3>

                <p>
                    Hãy thử thay đổi từ khóa
                    hoặc bộ lọc trạng thái.
                </p>

            </div>

        <?php endif; ?>

    </section>

</section>