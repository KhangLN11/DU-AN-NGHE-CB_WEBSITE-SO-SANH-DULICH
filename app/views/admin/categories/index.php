<section class="admin-page">

    <div class="admin-page-heading admin-category-heading">

        <div>

            <span>
                Category Management
            </span>

            <h1>
                Danh mục
            </h1>

            <p>
                Quản lý các nhóm Tour đang được sử dụng
                trên hệ thống VivuTourViet.
            </p>

        </div>

        <a
            class="admin-category-create"
            href="<?= base_url('admin/categories/create') ?>"
        >
            + Thêm danh mục
        </a>

    </div>


    <?php if (!empty($successMessage)): ?>

        <div class="admin-category-alert success">
            <?= e($successMessage) ?>
        </div>

    <?php endif; ?>


    <?php if (!empty($errorMessage)): ?>

        <div class="admin-category-alert error">
            <?= e($errorMessage) ?>
        </div>

    <?php endif; ?>


    <section class="admin-category-toolbar">

        <form
            action="<?= base_url(
                'admin/categories'
            ) ?>"
            method="GET"
        >

            <div class="admin-category-search">

                <label for="categoryKeyword">
                    Tìm kiếm
                </label>

                <input
                    id="categoryKeyword"
                    type="text"
                    name="keyword"
                    value="<?= e(
                        $filters['keyword']
                    ) ?>"
                    placeholder="Tên danh mục hoặc slug..."
                >

            </div>


            <div class="admin-category-filter">

                <label for="categoryStatus">
                    Trạng thái
                </label>

                <select
                    id="categoryStatus"
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
                    class="admin-category-reset"
                    href="<?= base_url(
                        'admin/categories'
                    ) ?>"
                >
                    Đặt lại
                </a>

            <?php endif; ?>

        </form>

    </section>


    <section class="admin-category-list-card">

        <div class="admin-category-list-heading">

            <div>

                <span>
                    Danh sách
                </span>

                <strong>
                    <?= (int)
                    $totalCategories ?>
                    danh mục
                </strong>

            </div>

        </div>


        <?php if (!empty($categories)): ?>

            <div class="admin-category-table-wrapper">

                <table class="admin-category-table">

                    <thead>

                        <tr>

                            <th>
                                ID
                            </th>

                            <th>
                                Danh mục
                            </th>

                            <th>
                                Slug
                            </th>

                            <th>
                                Số Tour
                            </th>

                            <th>
                                Trạng thái
                            </th>

                            <th>
                                Cập nhật
                            </th>

                            <th>
                                Thao tác
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php foreach (
                            $categories
                            as $category
                        ): ?>

                            <tr>

                                <td class="category-id">
                                    #<?= (int)
                                    $category[
                                        'category_id'
                                    ] ?>
                                </td>


                                <td>

                                    <div class="category-main">

                                        <strong>
                                            <?= e(
                                                $category[
                                                    'category_name'
                                                ]
                                            ) ?>
                                        </strong>

                                        <?php if (
                                            !empty(
                                                $category[
                                                    'description'
                                                ]
                                            )
                                        ): ?>

                                            <span>
                                                <?= e(
                                                    mb_strimwidth(
                                                        $category[
                                                            'description'
                                                        ],
                                                        0,
                                                        90,
                                                        '...'
                                                    )
                                                ) ?>
                                            </span>

                                        <?php endif; ?>

                                    </div>

                                </td>


                                <td>

                                    <code>
                                        <?= e(
                                            $category[
                                                'slug'
                                            ]
                                        ) ?>
                                    </code>

                                </td>


                                <td>

                                    <span class="category-tour-count">
                                        <?= (int)
                                        $category[
                                            'tour_count'
                                        ] ?>
                                    </span>

                                </td>


                                <td>

                                    <?php if (
                                        $category[
                                            'status'
                                        ] === 'active'
                                    ): ?>

                                        <span class="category-status active">
                                            Hoạt động
                                        </span>

                                    <?php else: ?>

                                        <span class="category-status inactive">
                                            Tạm ẩn
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <span class="category-date">

                                        <?= !empty(
                                            $category[
                                                'updated_at'
                                            ]
                                        )
                                            ? e(
                                                date(
                                                    'd/m/Y',
                                                    strtotime(
                                                        $category[
                                                            'updated_at'
                                                        ]
                                                    )
                                                )
                                            )
                                            : '-' ?>

                                    </span>

                                </td>


                                <td>

                                                                    <div class="admin-category-actions">

                                                                        <a
                                                                            class="category-action edit"
                                                                            href="<?= base_url(
                                                                                'admin/categories/'
                                                                                . $category['category_id']
                                                                                . '/edit'
                                                                            ) ?>"
                                                                        >
                                                                            Sửa
                                                                        </a>

                                                                        <?php if (
                                                                            $category['status'] === 'active'
                                                                        ): ?>

                                                                            <form
                                                                                action="<?= base_url(
                                                                                    'admin/categories/'
                                                                                    . $category['category_id']
                                                                                    . '/disable'
                                                                                ) ?>"
                                                                                method="POST"
                                                                                onsubmit="return confirm(
                                                                                    'Bạn có chắc muốn vô hiệu hóa danh mục này?'
                                                                                );"
                                                                            >

                                                                                <button
                                                                                    class="category-action disable"
                                                                                    type="submit"
                                                                                >
                                                                                    Tạm ẩn
                                                                                </button>

                                                                            </form>

                                                                        <?php endif; ?>

                                                                        <?php if (
                                                                            (int) $category['tour_count'] === 0
                                                                        ): ?>

                                                                            <form
                                                                                action="<?= base_url(
                                                                                    'admin/categories/'
                                                                                    . $category['category_id']
                                                                                    . '/delete'
                                                                                ) ?>"
                                                                                method="POST"
                                                                                onsubmit="return confirm(
                                                                                    'Bạn có chắc muốn xóa vĩnh viễn danh mục này? Hành động này không thể hoàn tác.'
                                                                                );"
                                                                            >

                                                                                <button
                                                                                    class="category-action delete"
                                                                                    type="submit"
                                                                                >
                                                                                    Xóa
                                                                                </button>

                                                                            </form>

                                                                        <?php else: ?>

                                                                            <span
                                                                                class="category-action delete locked"
                                                                                title="Danh mục đang được Tour sử dụng"
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

                <nav class="admin-category-pagination">

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
                                'admin/categories?'
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
                                'admin/categories?'
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
                                'admin/categories?'
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

            <div class="admin-category-empty">

                <div>
                    CAT
                </div>

                <h3>
                    Không tìm thấy danh mục
                </h3>

                <p>
                    Hãy thử thay đổi từ khóa
                    hoặc bộ lọc trạng thái.
                </p>

            </div>

        <?php endif; ?>

    </section>

</section>