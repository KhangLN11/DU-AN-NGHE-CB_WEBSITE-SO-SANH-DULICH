<section class="admin-page">

    <div class="admin-page-heading admin-company-form-heading">

        <div>

            <span>
                Company Management
            </span>

            <h1>
                Thêm công ty
            </h1>

            <p>
                Tạo mới một đơn vị lữ hành hoặc
                nhà cung cấp Tour cho TourCompare.
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
            'admin/companies/create'
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


                <div class="admin-company-form-field full">

                    <label for="companyLogo">
                        Logo
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
                Tạo công ty
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