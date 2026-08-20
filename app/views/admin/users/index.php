<section class="admin-page">

    <div class="admin-page-heading admin-user-heading">

        <div>

            <span>
                User Management
            </span>

            <h1>
                Người dùng
            </h1>

            <p>
                Theo dõi tài khoản,
                vai trò và trạng thái người dùng
                trên VivuTourViet.
            </p>

        </div>

    </div>


    <?php if (!empty($successMessage)): ?>

        <div class="admin-user-alert success">
            <?= e($successMessage) ?>
        </div>

    <?php endif; ?>


    <?php if (!empty($errorMessage)): ?>

        <div class="admin-user-alert error">
            <?= e($errorMessage) ?>
        </div>

    <?php endif; ?>


    <section class="admin-user-toolbar">

        <form
            action="<?= base_url(
                'admin/users'
            ) ?>"
            method="GET"
        >

            <div class="admin-user-search">

                <label for="userKeyword">
                    Tìm kiếm
                </label>

                <input
                    id="userKeyword"
                    type="text"
                    name="keyword"
                    value="<?= e(
                        $filters['keyword']
                    ) ?>"
                    placeholder="Tên, email hoặc điện thoại..."
                >

            </div>


            <div class="admin-user-filter">

                <label for="userRole">
                    Vai trò
                </label>

                <select
                    id="userRole"
                    name="role"
                >

                    <option value="">
                        Tất cả
                    </option>

                    <?php foreach (
                        $roles as $role
                    ): ?>

                        <option
                            value="<?= (int)
                            $role[
                                'role_id'
                            ] ?>"
                            <?= (int)
                            $filters['role']
                            === (int)
                            $role['role_id']
                                ? 'selected'
                                : '' ?>
                        >
                            <?= e(
                                $role[
                                    'role_name'
                                ]
                            ) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <div class="admin-user-filter">

                <label for="userStatus">
                    Trạng thái
                </label>

                <select
                    id="userStatus"
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
                        Vô hiệu hóa
                    </option>

                    <option
                        value="blocked"
                        <?= $filters['status']
                            === 'blocked'
                                ? 'selected'
                                : '' ?>
                    >
                        Đã khóa
                    </option>

                </select>

            </div>


            <button type="submit">
                Lọc
            </button>


            <?php if (
                $filters['keyword'] !== ''
                || $filters['role'] > 0
                || $filters['status'] !== ''
            ): ?>

                <a
                    class="admin-user-reset"
                    href="<?= base_url(
                        'admin/users'
                    ) ?>"
                >
                    Đặt lại
                </a>

            <?php endif; ?>

        </form>

    </section>


    <section class="admin-user-list-card">

        <div class="admin-user-list-heading">

            <div>

                <span>
                    Danh sách tài khoản
                </span>

                <strong>
                    <?= (int)
                    $totalUsers ?>
                    người dùng
                </strong>

            </div>

        </div>


        <?php if (!empty($users)): ?>

            <div class="admin-user-table-wrapper">

                <table class="admin-user-table">

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Người dùng</th>

                            <th>Liên hệ</th>

                            <th>Vai trò</th>

                            <th>Yêu thích</th>

                            <th>Trạng thái</th>

                            <th>Ngày tạo</th>

                            <th>Thao tác</th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php foreach (
                            $users as $user
                        ): ?>

                            <tr>

                                <td class="user-id">
                                    #<?= (int)
                                    $user[
                                        'user_id'
                                    ] ?>
                                </td>


                                <td>

                                    <div class="user-main">

                                        <div class="user-avatar">

                                            <?php if (
                                                !empty(
                                                    $user[
                                                        'avatar'
                                                    ]
                                                )
                                            ): ?>

                                                <img
                                                    src="<?= asset(
                                                        ltrim(
                                                            $user[
                                                                'avatar'
                                                            ],
                                                            '/'
                                                        )
                                                    ) ?>"
                                                    alt="<?= e(
                                                        $user[
                                                            'full_name'
                                                        ]
                                                    ) ?>"
                                                    loading="lazy"
                                                    onerror="this.style.display='none'"
                                                >

                                            <?php endif; ?>

                                            <span>
                                                <?= e(
                                                    mb_substr(
                                                        $user[
                                                            'full_name'
                                                        ],
                                                        0,
                                                        1
                                                    )
                                                ) ?>
                                            </span>

                                        </div>


                                        <div class="user-main-content">

                                            <strong>
                                                <?= e(
                                                    $user[
                                                        'full_name'
                                                    ]
                                                ) ?>
                                            </strong>

                                            <span>
                                                <?= e(
                                                    $user[
                                                        'email'
                                                    ]
                                                ) ?>
                                            </span>

                                        </div>

                                    </div>

                                </td>


                                <td>

                                    <div class="user-contact">

                                        <?php if (
                                            !empty(
                                                $user['phone']
                                            )
                                        ): ?>

                                            <span>
                                                <?= e(
                                                    $user[
                                                        'phone'
                                                    ]
                                                ) ?>
                                            </span>

                                        <?php else: ?>

                                            <span class="user-muted">
                                                Chưa cập nhật
                                            </span>

                                        <?php endif; ?>

                                    </div>

                                </td>


                                <td>

                                    <span class="user-role">
                                        <?= e(
                                            $user[
                                                'role_name'
                                            ]
                                        ) ?>
                                    </span>

                                </td>


                                <td>

                                    <span class="user-favorite-count">
                                        <?= (int)
                                        $user[
                                            'favorite_count'
                                        ] ?>
                                    </span>

                                </td>


                                <td>

                                    <?php if (
                                        $user['status']
                                        === 'active'
                                    ): ?>

                                        <span class="user-status active">
                                            Hoạt động
                                        </span>

                                    <?php elseif (
                                        $user['status']
                                        === 'inactive'
                                    ): ?>

                                        <span class="user-status inactive">
                                            Vô hiệu hóa
                                        </span>

                                    <?php else: ?>

                                        <span class="user-status blocked">
                                            Đã khóa
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <span class="user-date">

                                        <?= !empty(
                                            $user[
                                                'created_at'
                                            ]
                                        )
                                            ? e(
                                                date(
                                                    'd/m/Y',
                                                    strtotime(
                                                        $user[
                                                            'created_at'
                                                        ]
                                                    )
                                                )
                                            )
                                            : '-' ?>

                                    </span>

                                </td>


                                <td>

                                    <div class="admin-user-actions">

                                        <a class="user-action view" href="<?= base_url( 'admin/users/' . $user['user_id'] ) ?>" >
                                            Xem
                                        </a>

                                        <div class="user-status-actions">

    <?php if (
        $user['status']
        !== 'active'
    ): ?>

        <form
            action="<?= base_url(
                'admin/users/'
                . $user['user_id']
                . '/status'
            ) ?>"
            method="POST"
            onsubmit="return confirm(
                'Bạn có chắc muốn mở lại tài khoản này?'
            );"
        >

            <input
                type="hidden"
                name="status"
                value="active"
            >

            <button
                class="user-action activate"
                type="submit"
            >
                Mở lại
            </button>

        </form>

    <?php endif; ?>


    <?php if (
        $user['status']
        !== 'inactive'
    ): ?>

        <form
            action="<?= base_url(
                'admin/users/'
                . $user['user_id']
                . '/status'
            ) ?>"
            method="POST"
            onsubmit="return confirm(
                'Bạn có chắc muốn vô hiệu hóa tài khoản này?'
            );"
        >

            <input
                type="hidden"
                name="status"
                value="inactive"
            >

            <button
                class="user-action inactive-action"
                type="submit"
            >
                Vô hiệu hóa
            </button>

        </form>

    <?php endif; ?>


    <?php if (
        $user['status']
        !== 'blocked'
    ): ?>

        <form
            action="<?= base_url(
                'admin/users/'
                . $user['user_id']
                . '/status'
            ) ?>"
            method="POST"
            onsubmit="return confirm(
                'Bạn có chắc muốn khóa tài khoản này?'
            );"
        >

            <input
                type="hidden"
                name="status"
                value="blocked"
            >

            <button
                class="user-action block"
                type="submit"
            >
                Khóa
            </button>

        </form>

    <?php endif; ?>

</div>

                                        <?php if (
    (int) $user[
        'favorite_count'
    ] === 0
): ?>

    <form
        action="<?= base_url(
            'admin/users/'
            . $user['user_id']
            . '/delete'
        ) ?>"
        method="POST"
        onsubmit="return confirm(
            'Bạn có chắc muốn xóa vĩnh viễn người dùng này? Hành động này không thể hoàn tác.'
        );"
    >

        <button
            class="user-action delete"
            type="submit"
        >
            Xóa
        </button>

    </form>

<?php else: ?>

    <span
        class="user-action delete locked"
        title="Người dùng vẫn còn dữ liệu liên quan"
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

                <nav class="admin-user-pagination">

                    <?php

                    $query = [
                        'keyword' =>
                            $filters['keyword'],

                        'role' =>
                            $filters['role'],

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
                                'admin/users?'
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
                                'admin/users?'
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
                                'admin/users?'
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

            <div class="admin-user-empty">

                <div>
                    USER
                </div>

                <h3>
                    Không tìm thấy người dùng
                </h3>

                <p>
                    Hãy thử thay đổi từ khóa
                    hoặc bộ lọc.
                </p>

            </div>

        <?php endif; ?>

    </section>

</section>