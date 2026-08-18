<section class="auth-page">

    <div class="page-container">

        <div class="auth-layout">

            <div class="auth-intro">

                <span class="auth-intro-label">
                    TourCompare
                </span>

                <h1>
                    Tạo tài khoản
                    để lưu những hành trình bạn yêu thích
                </h1>

                <p>
                    Đăng ký tài khoản để chuẩn bị sử dụng
                    các chức năng cá nhân của TourCompare.
                </p>

                <div class="auth-benefits">

                    <div class="auth-benefit">

                        <span>
                            01
                        </span>

                        <div>

                            <strong>
                                Lưu Tour yêu thích
                            </strong>

                            <p>
                                Lưu lại những hành trình
                                bạn quan tâm.
                            </p>

                        </div>

                    </div>

                    <div class="auth-benefit">

                        <span>
                            02
                        </span>

                        <div>

                            <strong>
                                Quản lý hồ sơ
                            </strong>

                            <p>
                                Cập nhật thông tin tài khoản
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
                                So sánh Tour
                            </strong>

                            <p>
                                Đưa ra lựa chọn phù hợp
                                dễ dàng hơn.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

            <div class="auth-card">

                <div class="auth-card-header">

                    <span>
                        Tạo tài khoản mới
                    </span>

                    <h2>
                        Đăng ký
                    </h2>

                    <p>
                        Điền thông tin bên dưới
                        để tạo tài khoản TourCompare.
                    </p>

                </div>

                <form
                    class="auth-form"
                    action="<?= base_url('register') ?>"
                    method="POST"
                    novalidate
                >

                    <div class="auth-field">

                        <label for="full_name">
                            Họ và tên
                        </label>

                        <input
                            id="full_name"
                            type="text"
                            name="full_name"
                            maxlength="100"
                            value="<?= e(
                                $old['full_name'] ?? ''
                            ) ?>"
                            placeholder="Nguyễn Văn A"
                            autocomplete="name"
                        >

                        <?php if (
                            !empty($errors['full_name'])
                        ): ?>

                            <span class="field-error">
                                <?= e(
                                    $errors['full_name']
                                ) ?>
                            </span>

                        <?php endif; ?>

                    </div>

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

                        <?php if (
                            !empty($errors['email'])
                        ): ?>

                            <span class="field-error">
                                <?= e(
                                    $errors['email']
                                ) ?>
                            </span>

                        <?php endif; ?>

                    </div>

                    <div class="auth-field">

                        <label for="phone">
                            Số điện thoại
                            <span>
                                Không bắt buộc
                            </span>
                        </label>

                        <input
                            id="phone"
                            type="tel"
                            name="phone"
                            maxlength="20"
                            value="<?= e(
                                $old['phone'] ?? ''
                            ) ?>"
                            placeholder="0901234567"
                            autocomplete="tel"
                        >

                        <?php if (
                            !empty($errors['phone'])
                        ): ?>

                            <span class="field-error">
                                <?= e(
                                    $errors['phone']
                                ) ?>
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
                                placeholder="Tối thiểu 6 ký tự"
                                autocomplete="new-password"
                            >

                            <button
                                class="password-toggle"
                                type="button"
                                data-password-target="password"
                            >
                                Hiện
                            </button>

                        </div>

                        <?php if (
                            !empty($errors['password'])
                        ): ?>

                            <span class="field-error">
                                <?= e(
                                    $errors['password']
                                ) ?>
                            </span>

                        <?php endif; ?>

                    </div>

                    <div class="auth-field">

                        <label for="password_confirmation">
                            Xác nhận mật khẩu
                        </label>

                        <div class="password-field">

                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                placeholder="Nhập lại mật khẩu"
                                autocomplete="new-password"
                            >

                            <button
                                class="password-toggle"
                                type="button"
                                data-password-target="password_confirmation"
                            >
                                Hiện
                            </button>

                        </div>

                        <?php if (
                            !empty(
                                $errors[
                                    'password_confirmation'
                                ]
                            )
                        ): ?>

                            <span class="field-error">
                                <?= e(
                                    $errors[
                                        'password_confirmation'
                                    ]
                                ) ?>
                            </span>

                        <?php endif; ?>

                    </div>

                    <button
                        class="auth-submit"
                        type="submit"
                    >
                        Tạo tài khoản
                    </button>

                </form>

                <div class="auth-footer">

                    <span>
                        Đã có tài khoản?
                    </span>

                    <a href="<?= base_url('login') ?>">
                        Đăng nhập
                    </a>

                </div>

            </div>

        </div>

    </div>

</section>

