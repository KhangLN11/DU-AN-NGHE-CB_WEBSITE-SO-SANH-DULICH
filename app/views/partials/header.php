<header class="site-header">
    <div class="header-container">

        <a class="site-logo" href="<?= base_url() ?>">
            <span class="logo-icon">T</span>

            <span class="logo-text">
                VivuTourViet
            </span>
        </a>

        <button
            class="mobile-menu-button"
            id="mobileMenuButton"
            type="button"
            aria-label="Mở menu"
            aria-expanded="false"
        >
            <span></span>
            <span></span>
            <span></span>
        </button>

        <nav class="main-navigation" id="mainNavigation">

            <a
                class="nav-link <?= is_active('/') ? 'active' : '' ?>"
                href="<?= base_url() ?>"
            >
                Trang chủ
            </a>

            <a
                class="nav-link <?= is_active('/tours') ? 'active' : '' ?>"
                href="<?= base_url('tours') ?>"
            >
                Tour du lịch
            </a>

            <a
                class="nav-link <?= is_active('/destinations') ? 'active' : '' ?>"
                href="<?= base_url('destinations') ?>"
            >
                Điểm đến
            </a>

            <?php

$compareCount = count(
    $_SESSION['compare_tours'] ?? []
);

?>

<a
    class="nav-link <?= is_active('/compare') ? 'active' : '' ?>"
    href="<?= base_url('compare') ?>"
>
    So sánh

    <?php if ($compareCount > 0): ?>

        <span class="compare-nav-count">
            <?= $compareCount ?>
        </span>

    <?php endif; ?>
</a>

            <a
                class="nav-link <?= is_active('/favorites') ? 'active' : '' ?>"
                href="<?= base_url('favorites') ?>"
            >
                Yêu thích
            </a>

            <a
                class="nav-link <?= is_active('/contact') ? 'active' : '' ?>"
                href="<?= base_url('contact') ?>"
            >
                Liên hệ
            </a>

        </nav>

        <div class="header-actions">

    <?php if (!empty($_SESSION['user_id'])): ?>

        <div class="logged-user">

            <a
    class="logged-user-profile"
    href="<?= base_url('account') ?>"
>

    <span class="header-user-avatar">

        <?php if (
            !empty(
                $_SESSION['user_avatar']
            )
        ): ?>

            <img
                src="<?= asset(
                    ltrim(
                        $_SESSION['user_avatar'],
                        '/'
                    )
                ) ?>"
                alt="<?= e(
                    $_SESSION['user_name']
                    ?? 'Tài khoản'
                ) ?>"
            >

        <?php else: ?>

            <?= e(
                mb_substr(
                    $_SESSION['user_name']
                    ?? 'U',
                    0,
                    1
                )
            ) ?>

        <?php endif; ?>

    </span>

    <span class="logged-user-name">
        <?= e(
            $_SESSION['user_name']
            ?? 'Tài khoản'
        ) ?>
    </span>

</a>

            <?php if (
                ($_SESSION['role_name'] ?? '')
                === 'ADMIN'
            ): ?>

                <span class="user-role-badge">
                    Admin
                </span>

            <?php endif; ?>

            <form
                action="<?= base_url('logout') ?>"
                method="POST"
            >

            <a class="admin-access-button"href="<?= base_url('admin') ?>">
                    Quản trị
            </a>
                <button
                    class="logout-button"
                    type="submit"
                >
                    Đăng xuất
                </button>

            </form>

        </div>

    <?php else: ?>

        <a
            class="login-button"
            href="<?= base_url('login') ?>"
        >
            Đăng nhập
        </a>

    <?php endif; ?>

</div>

    </div>
</header>