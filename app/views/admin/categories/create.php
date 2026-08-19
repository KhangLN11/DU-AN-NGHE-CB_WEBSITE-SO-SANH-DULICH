<section class="admin-page">

    <div class="admin-page-heading admin-category-form-heading">

        <div>

            <span>
                Category Management
            </span>

            <h1>
                Thêm danh mục
            </h1>

            <p>
                Tạo một nhóm Tour mới cho hệ thống.
            </p>

        </div>

        <a
            class="admin-category-form-back"
            href="<?= base_url(
                'admin/categories'
            ) ?>"
        >
            ← Danh sách
        </a>

    </div>


    <?php if (!empty($errors)): ?>

        <div class="admin-category-form-alert">

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
        class="admin-category-form"
        action="<?= base_url(
            'admin/categories/create'
        ) ?>"
        method="POST"
    >

        <section class="admin-category-form-card">

            <div class="admin-category-form-card-heading">

                <span>
                    Thông tin cơ bản
                </span>

                <h2>
                    Danh mục
                </h2>

            </div>


            <div class="admin-category-form-grid">

                <div class="admin-category-form-field">

                    <label for="categoryName">
                        Tên danh mục
                    </label>

                    <input
                        id="categoryName"
                        type="text"
                        name="category_name"
                        maxlength="100"
                        value="<?= e(
                            $old[
                                'category_name'
                            ]
                        ) ?>"
                        placeholder="Ví dụ: Tour biển"
                    >

                    <?php if (
                        !empty(
                            $errors[
                                'category_name'
                            ]
                        )
                    ): ?>

                        <span class="field-error">
                            <?= e(
                                $errors[
                                    'category_name'
                                ]
                            ) ?>
                        </span>

                    <?php endif; ?>

                </div>


                <div class="admin-category-form-field">

                    <label for="categorySlug">
                        Slug
                    </label>

                    <input
                        id="categorySlug"
                        type="text"
                        name="slug"
                        maxlength="150"
                        value="<?= e(
                            $old['slug']
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
                                $errors[
                                    'slug'
                                ]
                            ) ?>
                        </span>

                    <?php endif; ?>

                </div>


                <div class="admin-category-form-field full">

                    <label for="categoryDescription">
                        Mô tả
                    </label>

                    <textarea
                        id="categoryDescription"
                        name="description"
                        rows="7"
                        maxlength="5000"
                        placeholder="Mô tả ngắn về nhóm Tour..."
                    ><?= e(
                        $old[
                            'description'
                        ]
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


                <div class="admin-category-form-field">

                    <label for="categoryStatus">
                        Trạng thái
                    </label>

                    <select
                        id="categoryStatus"
                        name="status"
                    >

                        <option
                            value="active"
                            <?= $old['status']
                                === 'active'
                                    ? 'selected'
                                    : '' ?>
                        >
                            Hoạt động
                        </option>

                        <option
                            value="inactive"
                            <?= $old['status']
                                === 'inactive'
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
                                $errors[
                                    'status'
                                ]
                            ) ?>
                        </span>

                    <?php endif; ?>

                </div>

            </div>

        </section>


        <div class="admin-category-form-actions">

            <a
                href="<?= base_url(
                    'admin/categories'
                ) ?>"
            >
                Hủy
            </a>

            <button type="submit">
                Tạo danh mục
            </button>

        </div>

    </form>

</section>