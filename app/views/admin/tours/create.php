<section class="admin-page">

    <div class="admin-page-heading admin-form-heading">

        <div>

            <span>
                Tour Management
            </span>

            <h1>
                Thêm Tour
            </h1>

            <p>
                Tạo thông tin cơ bản cho một Tour mới.
            </p>

        </div>

        <a
            class="admin-back-button"
            href="<?= base_url('admin/tours') ?>"
        >
            ← Quay lại
        </a>

    </div>

    <form
        class="admin-tour-form"
        action="<?= base_url(
            'admin/tours/create'
        ) ?>"
        method="POST"
        novalidate
    >

        <section class="admin-form-section">

            <div class="admin-form-section-heading">

                <span>
                    01
                </span>

                <div>

                    <h2>
                        Thông tin chung
                    </h2>

                    <p>
                        Tên Tour, slug và mô tả.
                    </p>

                </div>

            </div>

            <div class="admin-form-grid">

                <div class="admin-form-field full">

                    <label for="tour_name">
                        Tên Tour
                    </label>

                    <input
                        id="tour_name"
                        type="text"
                        name="tour_name"
                        maxlength="200"
                        value="<?= e(
                            $old['tour_name']
                            ?? ''
                        ) ?>"
                        placeholder="Ví dụ: Tour Đà Lạt 3 ngày 2 đêm"
                    >

                    <?php if (
                        !empty(
                            $errors['tour_name']
                        )
                    ): ?>

                        <span class="admin-field-error">
                            <?= e(
                                $errors[
                                    'tour_name'
                                ]
                            ) ?>
                        </span>

                    <?php endif; ?>

                </div>

                <div class="admin-form-field full">

                    <label for="slug">
                        Slug
                    </label>

                    <input
                        id="slug"
                        type="text"
                        name="slug"
                        maxlength="220"
                        value="<?= e(
                            $old['slug']
                            ?? ''
                        ) ?>"
                        placeholder="Để trống để tự tạo từ tên Tour"
                    >

                    <small>
                        Ví dụ:
                        tour-da-lat-3-ngay-2-dem
                    </small>

                    <?php if (
                        !empty(
                            $errors['slug']
                        )
                    ): ?>

                        <span class="admin-field-error">
                            <?= e(
                                $errors['slug']
                            ) ?>
                        </span>

                    <?php endif; ?>

                </div>

                <div class="admin-form-field full">

                    <label for="short_description">
                        Mô tả ngắn
                    </label>

                    <textarea
                        id="short_description"
                        name="short_description"
                        maxlength="500"
                        rows="3"
                        placeholder="Mô tả ngắn về Tour..."
                    ><?= e(
                        $old[
                            'short_description'
                        ]
                        ?? ''
                    ) ?></textarea>

                    <?php if (
                        !empty(
                            $errors[
                                'short_description'
                            ]
                        )
                    ): ?>

                        <span class="admin-field-error">
                            <?= e(
                                $errors[
                                    'short_description'
                                ]
                            ) ?>
                        </span>

                    <?php endif; ?>

                </div>

                <div class="admin-form-field full">

                    <label for="description">
                        Mô tả chi tiết
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        rows="8"
                        placeholder="Thông tin chi tiết về Tour..."
                    ><?= e(
                        $old['description']
                        ?? ''
                    ) ?></textarea>

                    <?php if (
                        !empty(
                            $errors[
                                'description'
                            ]
                        )
                    ): ?>

                        <span class="admin-field-error">
                            <?= e(
                                $errors[
                                    'description'
                                ]
                            ) ?>
                        </span>

                    <?php endif; ?>

                </div>

            </div>

        </section>

        <section class="admin-form-section">

            <div class="admin-form-section-heading">

                <span>
                    02
                </span>

                <div>

                    <h2>
                        Phân loại
                    </h2>

                    <p>
                        Danh mục, công ty và điểm khởi hành.
                    </p>

                </div>

            </div>

            <div class="admin-form-grid">

                <div class="admin-form-field">

                    <label for="category_id">
                        Danh mục
                    </label>

                    <select
                        id="category_id"
                        name="category_id"
                    >

                        <option value="">
                            Chọn danh mục
                        </option>

                        <?php foreach (
                            $categories
                            as $category
                        ): ?>

                            <option
                                value="<?= (int)
                                $category[
                                    'category_id'
                                ] ?>"
                                <?= (int)
                                (
                                    $old[
                                        'category_id'
                                    ]
                                    ?? 0
                                ) ===
                                (int)
                                $category[
                                    'category_id'
                                ]
                                    ? 'selected'
                                    : '' ?>
                            >
                                <?= e(
                                    $category[
                                        'category_name'
                                    ]
                                ) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                    <?php if (
                        !empty(
                            $errors[
                                'category_id'
                            ]
                        )
                    ): ?>

                        <span class="admin-field-error">
                            <?= e(
                                $errors[
                                    'category_id'
                                ]
                            ) ?>
                        </span>

                    <?php endif; ?>

                </div>

                <div class="admin-form-field">

                    <label for="company_id">
                        Công ty
                    </label>

                    <select
                        id="company_id"
                        name="company_id"
                    >

                        <option value="">
                            Chọn công ty
                        </option>

                        <?php foreach (
                            $companies
                            as $company
                        ): ?>

                            <option
                                value="<?= (int)
                                $company[
                                    'company_id'
                                ] ?>"
                                <?= (int)
                                (
                                    $old[
                                        'company_id'
                                    ]
                                    ?? 0
                                ) ===
                                (int)
                                $company[
                                    'company_id'
                                ]
                                    ? 'selected'
                                    : '' ?>
                            >
                                <?= e(
                                    $company[
                                        'company_name'
                                    ]
                                ) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                    <?php if (
                        !empty(
                            $errors[
                                'company_id'
                            ]
                        )
                    ): ?>

                        <span class="admin-field-error">
                            <?= e(
                                $errors[
                                    'company_id'
                                ]
                            ) ?>
                        </span>

                    <?php endif; ?>

                </div>

                <div class="admin-form-field full">

                    <label for="departure_location_id">
                        Điểm khởi hành
                    </label>

                    <select
                        id="departure_location_id"
                        name="departure_location_id"
                    >

                        <option value="">
                            Chọn điểm khởi hành
                        </option>

                        <?php foreach (
                            $locations
                            as $location
                        ): ?>

                            <option
                                value="<?= (int)
                                $location[
                                    'location_id'
                                ] ?>"
                                <?= (int)
                                (
                                    $old[
                                        'departure_location_id'
                                    ]
                                    ?? 0
                                ) ===
                                (int)
                                $location[
                                    'location_id'
                                ]
                                    ? 'selected'
                                    : '' ?>
                            >
                                <?= e(
                                    $location[
                                        'location_name'
                                    ]
                                ) ?>

                                <?php if (
                                    !empty(
                                        $location[
                                            'province_city'
                                        ]
                                    )
                                ): ?>
                                    -
                                    <?= e(
                                        $location[
                                            'province_city'
                                        ]
                                    ) ?>
                                <?php endif; ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                    <?php if (
                        !empty(
                            $errors[
                                'departure_location_id'
                            ]
                        )
                    ): ?>

                        <span class="admin-field-error">
                            <?= e(
                                $errors[
                                    'departure_location_id'
                                ]
                            ) ?>
                        </span>

                    <?php endif; ?>

                </div>

            </div>

        </section>

        <section class="admin-form-section">

            <div class="admin-form-section-heading">

                <span>
                    03
                </span>

                <div>

                    <h2>
                        Giá và thời gian
                    </h2>

                    <p>
                        Giá tham khảo và thời lượng Tour.
                    </p>

                </div>

            </div>

            <div class="admin-form-grid three">

                <div class="admin-form-field">

                    <label for="price">
                        Giá Tour
                    </label>

                    <input
                        id="price"
                        type="text"
                        name="price"
                        inputmode="numeric"
                        value="<?= $old['price'] !== null
                            ? e(
                                number_format(
                                    (float)
                                    $old['price'],
                                    0,
                                    ',',
                                    '.'
                                )
                            )
                            : '' ?>"
                        placeholder="3990000"
                    >

                    <?php if (
                        !empty(
                            $errors['price']
                        )
                    ): ?>

                        <span class="admin-field-error">
                            <?= e(
                                $errors['price']
                            ) ?>
                        </span>

                    <?php endif; ?>

                </div>

                <div class="admin-form-field">

                    <label for="duration_days">
                        Số ngày
                    </label>

                    <input
                        id="duration_days"
                        type="number"
                        name="duration_days"
                        min="1"
                        value="<?= (int)
                        (
                            $old[
                                'duration_days'
                            ]
                            ?? 1
                        ) ?>"
                    >

                    <?php if (
                        !empty(
                            $errors[
                                'duration_days'
                            ]
                        )
                    ): ?>

                        <span class="admin-field-error">
                            <?= e(
                                $errors[
                                    'duration_days'
                                ]
                            ) ?>
                        </span>

                    <?php endif; ?>

                </div>

                <div class="admin-form-field">

                    <label for="duration_nights">
                        Số đêm
                    </label>

                    <input
                        id="duration_nights"
                        type="number"
                        name="duration_nights"
                        min="0"
                        value="<?= (int)
                        (
                            $old[
                                'duration_nights'
                            ]
                            ?? 0
                        ) ?>"
                    >

                    <?php if (
                        !empty(
                            $errors[
                                'duration_nights'
                            ]
                        )
                    ): ?>

                        <span class="admin-field-error">
                            <?= e(
                                $errors[
                                    'duration_nights'
                                ]
                            ) ?>
                        </span>

                    <?php endif; ?>

                </div>

            </div>

        </section>

        <section class="admin-form-section">

            <div class="admin-form-section-heading">

                <span>
                    04
                </span>

                <div>

                    <h2>
                        Nguồn và trạng thái
                    </h2>

                    <p>
                        URL nguồn, nổi bật và trạng thái hiển thị.
                    </p>

                </div>

            </div>

            <div class="admin-form-grid">

                <div class="admin-form-field full">

                    <label for="source_url">
                        URL nguồn
                    </label>

                    <input
                        id="source_url"
                        type="url"
                        name="source_url"
                        value="<?= e(
                            $old['source_url']
                            ?? ''
                        ) ?>"
                        placeholder="https://..."
                    >

                    <?php if (
                        !empty(
                            $errors[
                                'source_url'
                            ]
                        )
                    ): ?>

                        <span class="admin-field-error">
                            <?= e(
                                $errors[
                                    'source_url'
                                ]
                            ) ?>
                        </span>

                    <?php endif; ?>

                </div>

                <div class="admin-form-field">

                    <label for="status">
                        Trạng thái
                    </label>

                    <select
                        id="status"
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
                            Active
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
                            Inactive
                        </option>

                    </select>

                    <?php if (
                        !empty(
                            $errors['status']
                        )
                    ): ?>

                        <span class="admin-field-error">
                            <?= e(
                                $errors['status']
                            ) ?>
                        </span>

                    <?php endif; ?>

                </div>

                <div class="admin-form-field">

                    <label>
                        Tour nổi bật
                    </label>

                    <label class="admin-checkbox">

                        <input
                            type="checkbox"
                            name="featured"
                            value="1"
                            <?= (int)
                            (
                                $old['featured']
                                ?? 0
                            ) === 1
                                ? 'checked'
                                : '' ?>
                        >

                        <span>
                            Đánh dấu là Tour nổi bật
                        </span>

                    </label>

                </div>

            </div>

        </section>

        <div class="admin-form-actions">

            <a
                class="admin-form-cancel"
                href="<?= base_url(
                    'admin/tours'
                ) ?>"
            >
                Hủy
            </a>

            <button
                class="admin-form-submit"
                type="submit"
            >
                Tạo Tour
            </button>

        </div>

    </form>

</section>