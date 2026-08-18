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
                Theo dõi và quản lý dữ liệu
                TourCompare từ khu vực quản trị.
            </p>

        </div>

    </div>

    <div class="admin-welcome-card">

        <div>

            <span>
                Đăng nhập thành công
            </span>

            <h2>
                Xin chào,
                <?= e(
                    $_SESSION['user_name']
                    ?? 'Quản trị viên'
                ) ?>
            </h2>

            <p>
                Bạn đang truy cập hệ thống
                với quyền quản trị.
            </p>

        </div>

        <div class="admin-role-card">

            <span>
                Vai trò
            </span>

            <strong>
                <?= e(
                    $_SESSION['role_name']
                    ?? ''
                ) ?>
            </strong>

        </div>

    </div>

    <div class="admin-dashboard-grid">

        <article class="admin-module-card">

            <span>
                01
            </span>

            <h3>
                Quản lý Tour
            </h3>

            <p>
                Thêm, chỉnh sửa và quản lý
                Tour trong hệ thống.
            </p>

            <small>
                Bước tiếp theo
            </small>

        </article>

        <article class="admin-module-card">

            <span>
                02
            </span>

            <h3>
                Quản lý danh mục
            </h3>

            <p>
                Quản lý các loại hình Tour.
            </p>

            <small>
                Chưa triển khai
            </small>

        </article>

        <article class="admin-module-card">

            <span>
                03
            </span>

            <h3>
                Quản lý người dùng
            </h3>

            <p>
                Theo dõi tài khoản
                trên TourCompare.
            </p>

            <small>
                Chưa triển khai
            </small>

        </article>

        <article class="admin-module-card">

            <span>
                04
            </span>

            <h3>
                Quản lý liên hệ
            </h3>

            <p>
                Xem và xử lý các liên hệ
                từ người dùng.
            </p>

            <small>
                Chưa triển khai
            </small>

        </article>

    </div>

</section>