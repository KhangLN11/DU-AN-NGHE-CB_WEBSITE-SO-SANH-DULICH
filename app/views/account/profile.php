<section class="account-page">

    <div class="page-container">

        <div class="account-heading">

            <div>

                <span class="account-label">
                    Tài khoản
                </span>

                <h1>
                    Hồ sơ cá nhân
                </h1>

                <p>
                    Quản lý thông tin cá nhân
                    và ảnh đại diện của bạn.
                </p>

            </div>

        </div>

        <?php if (!empty($successMessage)): ?>

            <div class="account-alert success">
                <?= e($successMessage) ?>
            </div>

        <?php endif; ?>

        <div class="account-layout">

            <aside class="account-sidebar">

                <div class="profile-summary">

                    <div
                        class="profile-avatar"
                        id="profileAvatar"
                    >

                        <div
                            class="profile-avatar-placeholder"
                            id="avatarPlaceholder"
                        >
                            <?= e(
                                mb_substr(
                                    $user['full_name'],
                                    0,
                                    1
                                )
                            ) ?>
                        </div>

                        <?php if (!empty($user['avatar'])): ?>

                            <img
                                id="avatarPreview"
                                src="<?= asset(
                                    ltrim(
                                        $user['avatar'],
                                        '/'
                                    )
                                ) ?>"
                                alt="<?= e(
                                    $user['full_name']
                                ) ?>"
                            >

                        <?php else: ?>

                            <img
                                id="avatarPreview"
                                src=""
                                alt=""
                                hidden
                            >

                        <?php endif; ?>

                    </div>

                    <h2>
                        <?= e($user['full_name']) ?>
                    </h2>

                    <span>
                        <?= e($user['email']) ?>
                    </span>

                    <div class="profile-role">
                        <?= e($user['role_name']) ?>
                    </div>

                </div>

                <nav class="account-navigation">

                    <a
                        class="active"
                        href="<?= base_url('account') ?>"
                    >
                        Hồ sơ cá nhân
                    </a>

                    <a href="<?= base_url('favorites') ?>">
                        Tour yêu thích
                    </a>

                </nav>

            </aside>

            <div class="account-content">

                <form
                    class="profile-form"
                    action="<?= base_url(
                        'account/update'
                    ) ?>"
                    method="POST"
                    enctype="multipart/form-data"
                >

                    <div class="profile-form-section">

                        <div class="profile-section-heading">

                            <span>
                                Ảnh đại diện
                            </span>

                            <h2>
                                Avatar
                            </h2>

                            <p>
                                JPG, PNG hoặc WEBP.
                                Kích thước tối đa 2MB.
                            </p>

                        </div>

                        <div class="avatar-upload">

                            <label
                                class="avatar-upload-button"
                                for="avatar"
                            >
                                Chọn ảnh
                            </label>

                            <input
                                id="avatar"
                                type="file"
                                name="avatar"
                                accept="image/jpeg,image/png,image/webp"
                            >

                            <span id="avatarFileName">
                                Chưa chọn file mới
                            </span>

                        </div>

                        <?php if (
                            !empty($errors['avatar'])
                        ): ?>

                            <span class="account-field-error">
                                <?= e(
                                    $errors['avatar']
                                ) ?>
                            </span>

                        <?php endif; ?>

                    </div>

                    <div class="profile-form-section">

                        <div class="profile-section-heading">

                            <span>
                                Thông tin
                            </span>

                            <h2>
                                Thông tin cá nhân
                            </h2>

                        </div>

                        <div class="profile-fields">

                            <div class="account-field">

                                <label for="full_name">
                                    Họ và tên
                                </label>

                                <input
                                    id="full_name"
                                    type="text"
                                    name="full_name"
                                    maxlength="100"
                                    value="<?= e(
                                        $user['full_name']
                                    ) ?>"
                                >

                                <?php if (
                                    !empty(
                                        $errors['full_name']
                                    )
                                ): ?>

                                    <span class="account-field-error">
                                        <?= e(
                                            $errors[
                                                'full_name'
                                            ]
                                        ) ?>
                                    </span>

                                <?php endif; ?>

                            </div>

                            <div class="account-field">

                                <label for="email">
                                    Email
                                </label>

                                <input
                                    id="email"
                                    type="email"
                                    value="<?= e(
                                        $user['email']
                                    ) ?>"
                                    disabled
                                >

                                <small>
                                    Email chưa thể thay đổi
                                    trong phiên bản hiện tại.
                                </small>

                            </div>

                            <div class="account-field">

                                <label for="phone">
                                    Số điện thoại
                                </label>

                                <input
                                    id="phone"
                                    type="tel"
                                    name="phone"
                                    maxlength="20"
                                    value="<?= e(
                                        $user['phone']
                                        ?? ''
                                    ) ?>"
                                    placeholder="0901234567"
                                >

                                <?php if (
                                    !empty(
                                        $errors['phone']
                                    )
                                ): ?>

                                    <span class="account-field-error">
                                        <?= e(
                                            $errors['phone']
                                        ) ?>
                                    </span>

                                <?php endif; ?>

                            </div>

                            <div class="account-field">

                                <label>
                                    Vai trò
                                </label>

                                <input
                                    type="text"
                                    value="<?= e(
                                        $user['role_name']
                                    ) ?>"
                                    disabled
                                >

                            </div>

                        </div>

                    </div>

                    <div class="profile-form-actions">

                        <a
                            class="profile-cancel-button"
                            href="<?= base_url() ?>"
                        >
                            Hủy
                        </a>

                        <button
                            class="profile-save-button"
                            type="submit"
                        >
                            Lưu thay đổi
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</section>