<section class="admin-page">

    <div class="admin-page-heading admin-company-form-heading">

        <div>

            <span>
                Company Management
            </span>

            <h1>
                Chỉnh sửa công ty
            </h1>

            <p>
                Cập nhật thông tin công ty
                #<?= (int) $companyId ?>.
            </p>

        </div>

        <a
            class="admin-company-form-back"
            href="<?= base_url(
                'admin/companies'
            ) ?>"
        >
            ← Danh sách
        </a>

    </div>


    <?php if (!empty($errors)): ?>

        <div class="admin-company-form-alert">

            <strong>
                Vui lòng kiểm tra lại thông tin.
            </strong>

            <?php foreach (
                $errors as $error
            ): ?>

                <div>
                    <?= e($error) ?>
                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>


    <form
        class="admin-company-form"
        action="<?= base_url(
            'admin/companies/'
            . $companyId
            . '/edit'
        ) ?>"
        method="POST"
        enctype="multipart/form-data"
    >

        <section class="admin-company-form-card">

            <div class="admin-company-form-card-heading">

                <span>
                    Thông tin cơ bản
                </span>

                <h2>
                    Công ty
                </h2>

            </div>


            <div class="admin-company-form-grid">

                <div class="admin-company-form-field">

                    <label for="companyName">
                        Tên công ty
                    </label>

                    <input
                        id="companyName"
                        type="text"
                        name="company_name"
                        maxlength="150"
                        value="<?= e(
                            $old[
                                'company_name'
                            ]
                            ?? ''
                        ) ?>"
                        placeholder="Ví dụ: Vietravel"
                    >

                    <?php if (
                        !empty(
                            $errors[
                                'company_name'
                            ]
                        )
                    ): ?>

                        <span class="field-error">
                            <?= e(
                                $errors[
                                    'company_name'
                                ]
                            ) ?>
                        </span>

                    <?php endif; ?>

                </div>


                <div class="admin-company-form-field">

                    <label for="companySlug">
                        Slug
                    </label>

                    <input
                        id="companySlug"
                        type="text"
                        name="slug"
                        maxlength="180"
                        value="<?= e(
                            $old['slug']
                            ?? ''
                        ) ?>"
                        placeholder="Để trống để tự sinh"
                    >

                    <?php if (
                        !empty(
                            $errors['slug']
                        )
                    ): ?>

                        <span class="field-error">
                            <?= e(
                                $errors['slug']
                            ) ?>
                        </span>

                    <?php endif; ?>

                </div>


                <?php if (
                    !empty(
                        $old['logo']
                    )
                ): ?>

                    <div class="admin-company-form-field full">

                        <label>
                            Logo hiện tại
                        </label>

                        <div class="company-current-logo">

                            <div class="company-current-logo-image">

                                <span>
                                    Logo
                                </span>

                                <img
                                    src="<?= asset(
                                        ltrim(
                                            $old['logo'],
                                            '/'
                                        )
                                    ) ?>"
                                    alt="<?= e(
                                        $old[
                                            'company_name'
                                        ]
                                        ?? 'Logo công ty'
                                    ) ?>"
                                    onerror="this.style.display='none'"
                                >

                            </div>


                            <div class="company-current-logo-content">

                                <strong>
                                    Logo đang sử dụng
                                </strong>

                                <label class="company-current-logo-remove">

                                    <input
                                        type="checkbox"
                                        name="remove_logo"
                                        value="1"
                                    >

                                    Xóa logo hiện tại

                                </label>

                            </div>

                        </div>

                    </div>

                <?php endif; ?>


                <div class="admin-company-form-field full">

                    <label for="companyLogo">
                        <?= !empty(
                            $old['logo']
                        )
                            ? 'Thay logo mới'
                            : 'Logo' ?>
                    </label>

                    <div class="company-logo-upload">

                        <div
                            class="company-logo-preview"
                            id="companyLogoPreview"
                        >

                            <span>
                                Logo
                            </span>

                            <img
                                id="companyLogoPreviewImage"
                                alt="Logo preview"
                            >

                        </div>


                        <div class="company-logo-upload-content">

                            <input
                                id="companyLogo"
                                type="file"
                                name="logo"
                                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            >

                            <p>
                                JPG, PNG hoặc WEBP.
                                Dung lượng tối đa 3MB.
                                Nếu không chọn ảnh mới,
                                logo hiện tại sẽ được giữ nguyên.
                            </p>

                        </div>

                    </div>

                </div>


                <div class="admin-company-form-field full">

                    <label for="companyDescription">
                        Mô tả
                    </label>

                    <textarea
                        id="companyDescription"
                        name="description"
                        rows="7"
                        maxlength="10000"
                        placeholder="Giới thiệu về công ty..."
                    ><?= e(
                        $old[
                            'description'
                        ]
                        ?? ''
                    ) ?></textarea>

                    <?php if (
                        !empty(
                            $errors[
                                'description'
                            ]
                        )
                    ): ?>

                        <span class="field-error">
                            <?= e(
                                $errors[
                                    'description'
                                ]
                            ) ?>
                        </span>

                    <?php endif; ?>

                </div>


                <div class="admin-company-form-field full">

                    <label for="companyAddress">
                        Địa chỉ
                    </label>

                    <input
                        id="companyAddress"
                        type="text"
                        name="address"
                        maxlength="255"
                        value="<?= e(
                            $old[
                                'address'
                            ]
                            ?? ''
                        ) ?>"
                        placeholder="Địa chỉ công ty"
                    >

                    <?php if (
                        !empty(
                            $errors['address']
                        )
                    ): ?>

                        <span class="field-error">
                            <?= e(
                                $errors['address']
                            ) ?>
                        </span>

                    <?php endif; ?>

                </div>


                <div class="admin-company-form-field">

                    <label for="companyPhone">
                        Điện thoại
                    </label>

                    <input
                        id="companyPhone"
                        type="text"
                        name="phone"
                        maxlength="20"
                        value="<?= e(
                            $old['phone']
                            ?? ''
                        ) ?>"
                        placeholder="Ví dụ: 028 1234 5678"
                    >

                    <?php if (
                        !empty(
                            $errors['phone']
                        )
                    ): ?>

                        <span class="field-error">
                            <?= e(
                                $errors['phone']
                            ) ?>
                        </span>

                    <?php endif; ?>

                </div>


                <div class="admin-company-form-field">

                    <label for="companyEmail">
                        Email
                    </label>

                    <input
                        id="companyEmail"
                        type="email"
                        name="email"
                        maxlength="150"
                        value="<?= e(
                            $old['email']
                            ?? ''
                        ) ?>"
                        placeholder="contact@example.com"
                    >

                    <?php if (
                        !empty(
                            $errors['email']
                        )
                    ): ?>

                        <span class="field-error">
                            <?= e(
                                $errors['email']
                            ) ?>
                        </span>

                    <?php endif; ?>

                </div>


                <div class="admin-company-form-field">

                    <label for="companyWebsite">
                        Website
                    </label>

                    <input
                        id="companyWebsite"
                        type="url"
                        name="website"
                        maxlength="255"
                        value="<?= e(
                            $old['website']
                            ?? ''
                        ) ?>"
                        placeholder="https://example.com"
                    >

                    <?php if (
                        !empty(
                            $errors['website']
                        )
                    ): ?>

                        <span class="field-error">
                            <?= e(
                                $errors['website']
                            ) ?>
                        </span>

                    <?php endif; ?>

                </div>


                <div class="admin-company-form-field">

                    <label for="companyStatus">
                        Trạng thái
                    </label>

                    <select
                        id="companyStatus"
                        name="status"
                    >

                        <option
                            value="active"
                            <?= (
                                $old['status']
                                ?? 'active'
                            ) === 'active'
                                ? 'selected'
                                : '' ?>
                        >
                            Hoạt động
                        </option>

                        <option
                            value="inactive"
                            <?= (
                                $old['status']
                                ?? ''
                            ) === 'inactive'
                                ? 'selected'
                                : '' ?>
                        >
                            Tạm ẩn
                        </option>

                    </select>

                    <?php if (
                        !empty(
                            $errors['status']
                        )
                    ): ?>

                        <span class="field-error">
                            <?= e(
                                $errors['status']
                            ) ?>
                        </span>

                    <?php endif; ?>

                </div>


                <?php if (
                    !empty(
                        $old['created_at']
                    )
                    || !empty(
                        $old['updated_at']
                    )
                ): ?>

                    <div class="admin-company-form-field full">

                        <label>
                            Thông tin hệ thống
                        </label>

                        <div class="company-system-info">

                            <?php if (
                                !empty(
                                    $old[
                                        'created_at'
                                    ]
                                )
                            ): ?>

                                <span>
                                    Ngày tạo:
                                    <?= e(
                                        date(
                                            'd/m/Y H:i',
                                            strtotime(
                                                $old[
                                                    'created_at'
                                                ]
                                            )
                                        )
                                    ) ?>
                                </span>

                            <?php endif; ?>


                            <?php if (
                                !empty(
                                    $old[
                                        'updated_at'
                                    ]
                                )
                            ): ?>

                                <span>
                                    Cập nhật:
                                    <?= e(
                                        date(
                                            'd/m/Y H:i',
                                            strtotime(
                                                $old[
                                                    'updated_at'
                                                ]
                                            )
                                        )
                                    ) ?>
                                </span>

                            <?php endif; ?>

                        </div>

                    </div>

                <?php endif; ?>

            </div>

        </section>


        <div class="admin-company-form-actions">

            <a
                href="<?= base_url(
                    'admin/companies'
                ) ?>"
            >
                Hủy
            </a>

            <button type="submit">
                Lưu thay đổi
            </button>

        </div>

    </form>

</section>


<script>
document.addEventListener(
    'DOMContentLoaded',
    function () {
        const input =
            document.getElementById(
                'companyLogo'
            );

        const previewImage =
            document.getElementById(
                'companyLogoPreviewImage'
            );

        if (
            !input
            || !previewImage
        ) {
            return;
        }

        input.addEventListener(
            'change',
            function () {
                const file =
                    input.files
                    && input.files[0];

                if (!file) {
                    previewImage.removeAttribute(
                        'src'
                    );

                    previewImage.classList.remove(
                        'visible'
                    );

                    return;
                }

                if (
                    !file.type.startsWith(
                        'image/'
                    )
                ) {
                    previewImage.removeAttribute(
                        'src'
                    );

                    previewImage.classList.remove(
                        'visible'
                    );

                    return;
                }

                const reader =
                    new FileReader();

                reader.onload =
                    function (event) {
                        previewImage.src =
                            event.target.result;

                        previewImage.classList.add(
                            'visible'
                        );
                    };

                reader.readAsDataURL(
                    file
                );
            }
        );
    }
);
</script>