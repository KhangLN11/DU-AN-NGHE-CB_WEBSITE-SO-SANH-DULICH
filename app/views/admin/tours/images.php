<section class="admin-page">

    <div class="admin-page-heading admin-image-heading">

        <div>

            <span>
                Tour Management
            </span>

            <h1>
                Hình ảnh Tour
            </h1>

            <p>
                Quản lý hình ảnh của
                <?= e($tour['tour_name']) ?>.
            </p>

        </div>

        <div class="admin-image-heading-actions">

            <a
                href="<?= base_url(
                    'admin/tours/'
                    . $tourId
                    . '/locations'
                ) ?>"
            >
                Điểm đến
            </a>

            <a
                href="<?= base_url(
                    'admin/tours/'
                    . $tourId
                    . '/edit'
                ) ?>"
            >
                Sửa Tour
            </a>

            <a
                href="<?= base_url(
                    'admin/tours'
                ) ?>"
            >
                ← Danh sách
            </a>

        </div>

    </div>


    <?php if (!empty($successMessage)): ?>

        <div class="admin-image-alert success">
            <?= e($successMessage) ?>
        </div>

    <?php endif; ?>


    <?php if (!empty($errorMessage)): ?>

        <div class="admin-image-alert error">
            <?= e($errorMessage) ?>
        </div>

    <?php endif; ?>


    <section class="admin-image-upload-card">

        <div class="admin-image-section-heading">

            <div>

                <span>
                    Upload
                </span>

                <h2>
                    Thêm hình ảnh
                </h2>

            </div>

            <p>
                JPG, PNG hoặc WEBP.
                Tối đa 5MB mỗi ảnh,
                tối đa 10 ảnh mỗi lần.
            </p>

        </div>


        <form
            action="<?= base_url(
                'admin/tours/'
                . $tourId
                . '/images/upload'
            ) ?>"
            method="POST"
            enctype="multipart/form-data"
        >

            <label
                class="admin-image-upload-box"
                for="tourImages"
            >

                <strong>
                    Chọn hình ảnh
                </strong>

                <span>
                    Có thể chọn nhiều ảnh cùng lúc
                </span>

                <input
                    id="tourImages"
                    type="file"
                    name="images[]"
                    accept="image/jpeg,image/png,image/webp"
                    multiple
                >

            </label>


            <div
                id="imagePreviewList"
                class="admin-image-preview-list"
            ></div>


            <div class="admin-image-upload-actions">

                <span id="selectedImageCount">
                    Chưa chọn ảnh
                </span>

                <button type="submit">
                    Tải ảnh lên
                </button>

            </div>

        </form>

    </section>


    <section class="admin-image-list-card">

        <div class="admin-image-section-heading">

            <div>

                <span>
                    Gallery
                </span>

                <h2>
                    Ảnh hiện tại
                </h2>

            </div>

            <strong>
                <?= count($images) ?>
                ảnh
            </strong>

        </div>


        <?php if (!empty($images)): ?>

            <form
                action="<?= base_url(
                    'admin/tours/'
                    . $tourId
                    . '/images/update'
                ) ?>"
                method="POST"
            >

                <div class="admin-image-grid">

                    <?php foreach (
                        $images as $image
                    ): ?>

                        <article class="admin-image-card">

                            <div class="admin-image-photo">

                                <img
                                    src="<?= asset(
                                        ltrim(
                                            $image[
                                                'image_url'
                                            ],
                                            '/'
                                        )
                                    ) ?>"
                                    alt="<?= e(
                                        $image[
                                            'alt_text'
                                        ]
                                        ?: $tour[
                                            'tour_name'
                                        ]
                                    ) ?>"
                                >

                                <?php if (
                                    (int)
                                    $image[
                                        'is_thumbnail'
                                    ] === 1
                                ): ?>

                                    <span
                                        class="admin-image-thumbnail-badge"
                                    >
                                        Thumbnail
                                    </span>

                                <?php endif; ?>

                            </div>


                            <div class="admin-image-card-content">

                                <label
                                    class="admin-thumbnail-option"
                                >

                                    <input
                                        type="radio"
                                        name="thumbnail_id"
                                        value="<?= (int)
                                        $image[
                                            'image_id'
                                        ] ?>"
                                        <?= (int)
                                        $image[
                                            'is_thumbnail'
                                        ] === 1
                                            ? 'checked'
                                            : '' ?>
                                    >

                                    <span>
                                        Ảnh đại diện
                                    </span>

                                </label>


                                <div class="admin-image-field">

                                    <label>
                                        Alt text
                                    </label>

                                    <input
                                        type="text"
                                        name="alt_text[<?= (int)
                                        $image[
                                            'image_id'
                                        ] ?>]"
                                        maxlength="255"
                                        value="<?= e(
                                            $image[
                                                'alt_text'
                                            ]
                                            ?? ''
                                        ) ?>"
                                    >

                                </div>


                                <div class="admin-image-field">

                                    <label>
                                        Thứ tự
                                    </label>

                                    <input
                                        type="number"
                                        name="sort_order[<?= (int)
                                        $image[
                                            'image_id'
                                        ] ?>]"
                                        min="1"
                                        value="<?= (int)
                                        $image[
                                            'sort_order'
                                        ] ?>"
                                    >

                                </div>


                                <span class="admin-image-path">
                                    <?= e(
                                        $image[
                                            'image_url'
                                        ]
                                    ) ?>
                                </span>

                            </div>

                        </article>

                    <?php endforeach; ?>

                </div>


                <div class="admin-image-save-actions">

                    <button type="submit">
                        Lưu thay đổi ảnh
                    </button>

                </div>

            </form>


            <div class="admin-image-delete-section">

                <h3>
                    Xóa ảnh
                </h3>

                <p>
                    Chọn từng ảnh bên dưới nếu cần xóa.
                </p>


                <div class="admin-image-delete-list">

                    <?php foreach (
                        $images as $image
                    ): ?>

                        <form
                            action="<?= base_url(
                                'admin/tours/'
                                . $tourId
                                . '/images/'
                                . $image[
                                    'image_id'
                                ]
                                . '/delete'
                            ) ?>"
                            method="POST"
                            onsubmit="return confirm(
                                'Bạn có chắc muốn xóa ảnh này?'
                            );"
                        >

                            <span>
                                #<?= (int)
                                $image[
                                    'image_id'
                                ] ?>
                            </span>

                            <strong>
                                <?= e(
                                    $image[
                                        'alt_text'
                                    ]
                                    ?: 'Ảnh Tour'
                                ) ?>
                            </strong>

                            <button type="submit">
                                Xóa
                            </button>

                        </form>

                    <?php endforeach; ?>

                </div>

            </div>

        <?php else: ?>

            <div class="admin-image-empty">

                <div>
                    IMG
                </div>

                <h3>
                    Tour chưa có hình ảnh
                </h3>

                <p>
                    Hãy tải ảnh lên bằng khu vực phía trên.
                </p>

            </div>

        <?php endif; ?>

    </section>

</section>