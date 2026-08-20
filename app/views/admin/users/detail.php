<section class="admin-page">

    <div class="admin-page-heading admin-user-detail-heading">

        <div>

            <span>
                User Management
            </span>

            <h1>
                Chi tiết người dùng
            </h1>

            <p>
                Xem thông tin tài khoản
                #<?= (int) $user['user_id'] ?>.
            </p>

        </div>

        <a
            class="admin-user-detail-back"
            href="<?= base_url(
                'admin/users'
            ) ?>"
        >
            ← Danh sách
        </a>

    </div>


    <section class="admin-user-profile-card">

        <div class="admin-user-profile-main">

            <div class="admin-user-detail-avatar">

                <?php if (
                    !empty(
                        $user['avatar']
                    )
                ): ?>

                    <img
                        src="<?= asset(
                            ltrim(
                                $user['avatar'],
                                '/'
                            )
                        ) ?>"
                        alt="<?= e(
                            $user['full_name']
                        ) ?>"
                        onerror="this.style.display='none'"
                    >

                <?php endif; ?>

                <span>
                    <?= e(
                        mb_substr(
                            $user['full_name'],
                            0,
                            1
                        )
                    ) ?>
                </span>

            </div>


            <div class="admin-user-profile-content">

                <div class="admin-user-profile-meta">

                    <span class="admin-user-id">
                        #<?= (int)
                        $user['user_id'] ?>
                    </span>


                    <span class="admin-user-role-badge">
                        <?= e(
                            $user['role_name']
                        ) ?>
                    </span>


                    <?php if (
                        $user['status']
                        === 'active'
                    ): ?>

                        <span class="admin-user-status-badge active">
                            Hoạt động
                        </span>

                    <?php elseif (
                        $user['status']
                        === 'inactive'
                    ): ?>

                        <span class="admin-user-status-badge inactive">
                            Vô hiệu hóa
                        </span>

                    <?php else: ?>

                        <span class="admin-user-status-badge blocked">
                            Đã khóa
                        </span>

                    <?php endif; ?>

                </div>


                <h2>
                    <?= e(
                        $user['full_name']
                    ) ?>
                </h2>


                <p>
                    <?= e(
                        $user['email']
                    ) ?>
                </p>

            </div>

        </div>

    </section>


    <div class="admin-user-detail-grid">

        <section class="admin-user-detail-card">

            <div class="admin-user-detail-card-heading">

                <span>
                    Account
                </span>

                <h2>
                    Thông tin tài khoản
                </h2>

            </div>


            <div class="admin-user-info-list">

                <div class="admin-user-info-row">

                    <span>
                        Họ tên
                    </span>

                    <strong>
                        <?= e(
                            $user['full_name']
                        ) ?>
                    </strong>

                </div>


                <div class="admin-user-info-row">

                    <span>
                        Email
                    </span>

                    <strong>
                        <?= e(
                            $user['email']
                        ) ?>
                    </strong>

                </div>


                <div class="admin-user-info-row">

                    <span>
                        Điện thoại
                    </span>

                    <strong>
                        <?= !empty(
                            $user['phone']
                        )
                            ? e(
                                $user['phone']
                            )
                            : 'Chưa cập nhật' ?>
                    </strong>

                </div>


                <div class="admin-user-info-row">

                    <span>
                        Vai trò
                    </span>

                    <strong>
                        <?= e(
                            $user['role_name']
                        ) ?>
                    </strong>

                </div>


                <?php if (
                    !empty(
                        $user[
                            'role_description'
                        ]
                    )
                ): ?>

                    <div class="admin-user-info-row">

                        <span>
                            Mô tả vai trò
                        </span>

                        <strong>
                            <?= e(
                                $user[
                                    'role_description'
                                ]
                            ) ?>
                        </strong>

                    </div>

                <?php endif; ?>


                <div class="admin-user-info-row">

                    <span>
                        Trạng thái
                    </span>

                    <strong>
                        <?php if (
                            $user['status']
                            === 'active'
                        ): ?>

                            Hoạt động

                        <?php elseif (
                            $user['status']
                            === 'inactive'
                        ): ?>

                            Vô hiệu hóa

                        <?php else: ?>

                            Đã khóa

                        <?php endif; ?>
                    </strong>

                </div>

            </div>

        </section>


        <section class="admin-user-detail-card">

            <div class="admin-user-detail-card-heading">

                <span>
                    Activity
                </span>

                <h2>
                    Hoạt động tài khoản
                </h2>

            </div>


            <div class="admin-user-stat-grid">

                <div class="admin-user-stat">

                    <span>
                        Yêu thích
                    </span>

                    <strong>
                        <?= (int)
                        $user[
                            'favorite_count'
                        ] ?>
                    </strong>

                </div>

            </div>


            <div class="admin-user-detail-note">

                <span>
                    Thông tin
                </span>

                <p>
                    Trang quản trị chỉ hiển thị dữ liệu
                    tài khoản và không cho phép chỉnh sửa
                    thông tin cá nhân của người dùng.
                </p>

            </div>

        </section>


        <section class="admin-user-detail-card">

            <div class="admin-user-detail-card-heading">

                <span>
                    System
                </span>

                <h2>
                    Thông tin hệ thống
                </h2>

            </div>


            <div class="admin-user-info-list">

                <div class="admin-user-info-row">

                    <span>
                        User ID
                    </span>

                    <strong>
                        #<?= (int)
                        $user['user_id'] ?>
                    </strong>

                </div>


                <div class="admin-user-info-row">

                    <span>
                        Role ID
                    </span>

                    <strong>
                        #<?= (int)
                        $user['role_id'] ?>
                    </strong>

                </div>


                <div class="admin-user-info-row">

                    <span>
                        Ngày tạo
                    </span>

                    <strong>
                        <?= !empty(
                            $user['created_at']
                        )
                            ? e(
                                date(
                                    'd/m/Y H:i',
                                    strtotime(
                                        $user[
                                            'created_at'
                                        ]
                                    )
                                )
                            )
                            : '-' ?>
                    </strong>

                </div>


                <div class="admin-user-info-row">

                    <span>
                        Cập nhật gần nhất
                    </span>

                    <strong>
                        <?= !empty(
                            $user['updated_at']
                        )
                            ? e(
                                date(
                                    'd/m/Y H:i',
                                    strtotime(
                                        $user[
                                            'updated_at'
                                        ]
                                    )
                                )
                            )
                            : '-' ?>
                    </strong>

                </div>

            </div>

        </section>


        <section class="admin-user-detail-card">

            <div class="admin-user-detail-card-heading">

                <span>
                    Security
                </span>

                <h2>
                    Bảo mật
                </h2>

            </div>


            <div class="admin-user-security-box">

                <strong>
                    Mật khẩu được bảo vệ
                </strong>

                <p>
                    Password hash không được tải,
                    hiển thị hoặc cung cấp trong
                    khu vực quản trị.
                </p>

            </div>

        </section>

    </div>


    <div class="admin-user-detail-actions">

        <a
            href="<?= base_url(
                'admin/users'
            ) ?>"
        >
            ← Quay lại danh sách
        </a>

    </div>

</section>

<section class="admin-user-status-management">

    <div class="admin-user-detail-card-heading">

        <span>
            Account Control
        </span>

        <h2>
            Quản lý trạng thái
        </h2>

    </div>


    <div class="admin-user-status-management-body">

        <p>
            Trạng thái hiện tại:
            <strong>
                <?php if (
                    $user['status']
                    === 'active'
                ): ?>

                    Hoạt động

                <?php elseif (
                    $user['status']
                    === 'inactive'
                ): ?>

                    Vô hiệu hóa

                <?php else: ?>

                    Đã khóa

                <?php endif; ?>
            </strong>
        </p>


        <div class="admin-user-status-management-actions">

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
                        class="user-status-button activate"
                        type="submit"
                    >
                        Mở lại tài khoản
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
                        class="user-status-button inactive"
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
                        class="user-status-button blocked"
                        type="submit"
                    >
                        Khóa tài khoản
                    </button>

                </form>

            <?php endif; ?>

        </div>

    </div>

</section>

<section class="admin-user-delete-management">

    <div class="admin-user-detail-card-heading">

        <span>
            Danger Zone
        </span>

        <h2>
            Xóa tài khoản
        </h2>

    </div>


    <div class="admin-user-delete-management-body">

        <?php if (
            (int) $user[
                'favorite_count'
            ] === 0
        ): ?>

            <div>

                <strong>
                    Tài khoản có thể được xóa
                </strong>

                <p>
                    Tài khoản hiện không còn dữ liệu
                    yêu thích liên quan.
                </p>

            </div>


            <form
                action="<?= base_url(
                    'admin/users/'
                    . $user['user_id']
                    . '/delete'
                ) ?>"
                method="POST"
                onsubmit="return confirm(
                    'Bạn có chắc muốn xóa vĩnh viễn tài khoản này? Hành động này không thể hoàn tác.'
                );"
            >

                <button
                    class="user-delete-button"
                    type="submit"
                >
                    Xóa tài khoản
                </button>

            </form>

        <?php else: ?>

            <div>

                <strong>
                    Không thể xóa tài khoản
                </strong>

                <p>
                    Người dùng đang có
                    <?= (int)
                    $user[
                        'favorite_count'
                    ] ?>
                    mục yêu thích liên quan.
                    Hãy sử dụng khóa hoặc vô hiệu hóa
                    thay vì hard delete.
                </p>

            </div>

        <?php endif; ?>

    </div>

</section>