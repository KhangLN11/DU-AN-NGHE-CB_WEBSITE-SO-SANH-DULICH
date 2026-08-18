<section class="auth-page">

    <div class="page-container">

        <div class="auth-layout">

            <div class="auth-intro">

                <span class="auth-intro-label">
                    TourCompare
                </span>

                <h1>
                    Chào mừng bạn quay trở lại
                </h1>

                <p>
                    Đăng nhập để tiếp tục quản lý
                    thông tin và các Tour yêu thích của bạn.
                </p>

                <div class="auth-benefits">

                    <div class="auth-benefit">

                        <span>
                            01
                        </span>

                        <div>

                            <strong>
                                Tour yêu thích
                            </strong>

                            <p>
                                Truy cập nhanh những hành trình
                                bạn đã lưu.
                            </p>

                        </div>

                    </div>

                    <div class="auth-benefit">

                        <span>
                            02
                        </span>

                        <div>

                            <strong>
                                Hồ sơ cá nhân
                            </strong>

                            <p>
                                Quản lý thông tin tài khoản
                                của bạn.
                            </p>

                        </div>

                    </div>

                    <div class="auth-benefit">

                        <span>
                            03
                        </span>

                        <div>

                            <strong>
                                Trải nghiệm cá nhân
                            </strong>

                            <p>
                                Sử dụng đầy đủ các chức năng
                                dành cho thành viên.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

            <div class="auth-card">

                <div class="auth-card-header">

                    <span>
                        Tài khoản của bạn
                    </span>

                    <h2>
                        Đăng nhập
                    </h2>

                    <p>
                        Nhập email và mật khẩu
                        để tiếp tục.
                    </p>

                </div>

                <?php if (!empty($successMessage)): ?>

                    <div class="auth-alert success">
                        <?= e($successMessage) ?>
                    </div>

                <?php endif; ?>

                <?php if (!empty($errors['login'])): ?>

                    <div class="auth-alert error">
                        <?= e($errors['login']) ?>
                    </div>

                <?php endif; ?>

                <form
                    class="auth-form"
                    action="<?= base_url('login') ?>"
                    method="POST"
                    novalidate
                >

                    <div class="auth-field">

                        <label for="email">
                            Email
                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            maxlength="150"
                            value="<?= e(
                                $old['email'] ?? ''
                            ) ?>"
                            placeholder="example@email.com"
                            autocomplete="email"
                        >

                        <?php if (!empty($errors['email'])): ?>

                            <span class="field-error">
                                <?= e($errors['email']) ?>
                            </span>

                        <?php endif; ?>

                    </div>

                    <div class="auth-field">

                        <label for="password">
                            Mật khẩu
                        </label>

                        <div class="password-field">

                            <input
                                id="password"
                                type="password"
                                name="password"
                                placeholder="Nhập mật khẩu"
                                autocomplete="current-password"
                            >

                            <button
                                class="password-toggle"
                                type="button"
                                data-password-target="password"
                            >
                                Hiện
                            </button>

                        </div>

                        <?php if (!empty($errors['password'])): ?>

                            <span class="field-error">
                                <?= e($errors['password']) ?>
                            </span>

                        <?php endif; ?>

                    </div>

                    <button
                        class="auth-submit"
                        type="submit"
                    >
                        Đăng nhập
                    </button>

                </form>

                <div class="auth-footer">

                    <span>
                        Chưa có tài khoản?
                    </span>

                    <a href="<?= base_url('register') ?>">
                        Đăng ký
                    </a>

                </div>

            </div>

        </div>

    </div>

</section>