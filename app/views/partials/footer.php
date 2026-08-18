<footer class="site-footer">

    <div class="footer-container">

        <div class="footer-column footer-brand">

            <a class="site-logo footer-logo" href="<?= base_url() ?>">
                <span class="logo-icon">T</span>

                <span class="logo-text">
                    TourCompare
                </span>
            </a>

            <p>
                Nền tảng tổng hợp, giới thiệu và hỗ trợ so sánh
                các tour du lịch từ nhiều đơn vị lữ hành.
            </p>

        </div>

        <div class="footer-column">

            <h3>Khám phá</h3>

            <a href="<?= base_url('tours') ?>">
                Tour du lịch
            </a>

            <a href="<?= base_url('destinations') ?>">
                Điểm đến
            </a>

            <a href="<?= base_url('compare') ?>">
                So sánh tour
            </a>

        </div>

        <div class="footer-column">

            <h3>Tài khoản</h3>

            <a href="<?= base_url('login') ?>">
                Đăng nhập
            </a>

            <a href="<?= base_url('register') ?>">
                Đăng ký
            </a>

            <a href="<?= base_url('favorites') ?>">
                Tour yêu thích
            </a>

        </div>

        <div class="footer-column">

            <h3>Hỗ trợ</h3>

            <a href="<?= base_url('contact') ?>">
                Liên hệ
            </a>

            <span>
                Website phục vụ mục đích tham khảo tour.
            </span>

        </div>

    </div>

    <div class="footer-bottom">

        <p>
            © <?= date('Y') ?> TourCompare. All rights reserved.
        </p>

    </div>

</footer>