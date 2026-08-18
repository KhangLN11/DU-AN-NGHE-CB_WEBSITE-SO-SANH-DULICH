<section class="admin-page">

    <div class="admin-page-heading">

        <div>

            <span>
                Dashboard
            </span>

            <h1>
                Tổng quan hệ thống
            </h1>

            <p>
                Theo dõi nhanh dữ liệu
                và hoạt động của TourCompare.
            </p>

        </div>

    </div>


    <div class="admin-welcome-card">

        <div>

            <span>
                Khu vực quản trị
            </span>

            <h2>
                Xin chào,
                <?= e(
                    $_SESSION['user_name']
                    ?? 'Quản trị viên'
                ) ?>
            </h2>

            <p>
                Dưới đây là tình hình hiện tại
                của hệ thống TourCompare.
            </p>

        </div>


        <div class="admin-role-card">

            <span>
                Vai trò
            </span>

            <strong>
                <?= e(
                    $_SESSION['role_name']
                    ?? 'ADMIN'
                ) ?>
            </strong>

        </div>

    </div>


    <div class="dashboard-stat-grid">

        <article class="dashboard-stat-card">

            <div class="stat-card-heading">

                <span>
                    Tour
                </span>

                <strong>
                    01
                </strong>

            </div>

            <div class="stat-card-value">
                <?= (int)
                $statistics['total_tours'] ?>
            </div>

            <p>
                Tổng số Tour
            </p>

        </article>


        <article class="dashboard-stat-card">

            <div class="stat-card-heading">

                <span>
                    Hoạt động
                </span>

                <strong>
                    02
                </strong>

            </div>

            <div class="stat-card-value">
                <?= (int)
                $statistics['active_tours'] ?>
            </div>

            <p>
                Tour đang hoạt động
            </p>

        </article>


        <article class="dashboard-stat-card">

            <div class="stat-card-heading">

                <span>
                    Người dùng
                </span>

                <strong>
                    03
                </strong>

            </div>

            <div class="stat-card-value">
                <?= (int)
                $statistics['total_users'] ?>
            </div>

            <p>
                Tài khoản hệ thống
            </p>

        </article>


        <article class="dashboard-stat-card">

            <div class="stat-card-heading">

                <span>
                    Công ty
                </span>

                <strong>
                    04
                </strong>

            </div>

            <div class="stat-card-value">
                <?= (int)
                $statistics['total_companies'] ?>
            </div>

            <p>
                Đơn vị lữ hành
            </p>

        </article>


        <article class="dashboard-stat-card">

            <div class="stat-card-heading">

                <span>
                    Địa điểm
                </span>

                <strong>
                    05
                </strong>

            </div>

            <div class="stat-card-value">
                <?= (int)
                $statistics['total_locations'] ?>
            </div>

            <p>
                Địa điểm trong hệ thống
            </p>

        </article>


        <article class="dashboard-stat-card">

            <div class="stat-card-heading">

                <span>
                    Danh mục
                </span>

                <strong>
                    06
                </strong>

            </div>

            <div class="stat-card-value">
                <?= (int)
                $statistics['total_categories'] ?>
            </div>

            <p>
                Loại hình Tour
            </p>

        </article>


        <article class="dashboard-stat-card">

            <div class="stat-card-heading">

                <span>
                    Yêu thích
                </span>

                <strong>
                    07
                </strong>

            </div>

            <div class="stat-card-value">
                <?= (int)
                $statistics['total_favorites'] ?>
            </div>

            <p>
                Lượt lưu Tour
            </p>

        </article>


        <article
            class="dashboard-stat-card
            <?= (int)
            $statistics['new_contacts'] > 0
                ? 'attention'
                : '' ?>"
        >

            <div class="stat-card-heading">

                <span>
                    Liên hệ mới
                </span>

                <strong>
                    08
                </strong>

            </div>

            <div class="stat-card-value">
                <?= (int)
                $statistics['new_contacts'] ?>
            </div>

            <p>
                Chưa xử lý
            </p>

        </article>

    </div>


    <div class="dashboard-content-grid">

        <section class="dashboard-panel">

            <div class="dashboard-panel-header">

                <div>

                    <span>
                        Tour
                    </span>

                    <h2>
                        Tour mới nhất
                    </h2>

                </div>

                <span class="dashboard-panel-note">
                    5 bản ghi gần nhất
                </span>

            </div>


            <?php if (!empty($latestTours)): ?>

                <div class="admin-table-wrapper">

                    <table class="admin-table">

                        <thead>

                            <tr>

                                <th>
                                    Tour
                                </th>

                                <th>
                                    Công ty
                                </th>

                                <th>
                                    Giá
                                </th>

                                <th>
                                    Trạng thái
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php foreach (
                                $latestTours
                                as $tour
                            ): ?>

                                <tr>

                                    <td>

                                        <div
                                            class="admin-table-primary"
                                        >
                                            <?= e(
                                                $tour[
                                                    'tour_name'
                                                ]
                                            ) ?>
                                        </div>

                                        <span
                                            class="admin-table-secondary"
                                        >
                                            <?= e(
                                                $tour[
                                                    'category_name'
                                                ]
                                            ) ?>
                                        </span>

                                    </td>

                                    <td>
                                        <?= e(
                                            $tour[
                                                'company_name'
                                            ]
                                        ) ?>
                                    </td>

                                    <td
                                        class="admin-price"
                                    >
                                        <?= number_format(
                                            (float)
                                            $tour['price'],
                                            0,
                                            ',',
                                            '.'
                                        ) ?>
                                        ₫
                                    </td>

                                    <td>

                                        <?php if (
                                            $tour['status']
                                            === 'active'
                                        ): ?>

                                            <span
                                                class="admin-status active"
                                            >
                                                Active
                                            </span>

                                        <?php else: ?>

                                            <span
                                                class="admin-status inactive"
                                            >
                                                <?= e(
                                                    $tour[
                                                        'status'
                                                    ]
                                                ) ?>
                                            </span>

                                        <?php endif; ?>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php else: ?>

                <div class="dashboard-empty">
                    Chưa có dữ liệu Tour.
                </div>

            <?php endif; ?>

        </section>


        <section class="dashboard-panel">

            <div class="dashboard-panel-header">

                <div>

                    <span>
                        Liên hệ
                    </span>

                    <h2>
                        Liên hệ mới nhất
                    </h2>

                </div>

                <span class="dashboard-panel-note">
                    5 bản ghi gần nhất
                </span>

            </div>


            <?php if (
                !empty($latestContacts)
            ): ?>

                <div
                    class="dashboard-contact-list"
                >

                    <?php foreach (
                        $latestContacts
                        as $contact
                    ): ?>

                        <article
                            class="dashboard-contact-item"
                        >

                            <div
                                class="dashboard-contact-main"
                            >

                                <div
                                    class="dashboard-contact-avatar"
                                >
                                    <?= e(
                                        mb_substr(
                                            $contact[
                                                'full_name'
                                            ],
                                            0,
                                            1
                                        )
                                    ) ?>
                                </div>


                                <div>

                                    <strong>
                                        <?= e(
                                            $contact[
                                                'full_name'
                                            ]
                                        ) ?>
                                    </strong>

                                    <span>
                                        <?= e(
                                            $contact[
                                                'email'
                                            ]
                                        ) ?>
                                    </span>

                                    <p>
                                        <?= e(
                                            $contact[
                                                'subject'
                                            ]
                                        ) ?>
                                    </p>

                                </div>

                            </div>


                            <span
                                class="admin-contact-status
                                <?= $contact[
                                    'status'
                                ] === 'new'
                                    ? 'new'
                                    : '' ?>"
                            >
                                <?= e(
                                    $contact['status']
                                ) ?>
                            </span>

                        </article>

                    <?php endforeach; ?>

                </div>

            <?php else: ?>

                <div class="dashboard-empty">
                    Chưa có liên hệ nào.
                </div>

            <?php endif; ?>

        </section>

    </div>

</section>