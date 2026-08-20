<section class="admin-page">

    <div class="admin-page-heading admin-location-form-heading">

        <div>

            <span>
                Location Management
            </span>

            <h1>
                Thêm địa điểm
            </h1>

            <p>
                Tạo mới điểm khởi hành hoặc điểm đến
                cho hệ thống TourCompare.
            </p>

        </div>

        <a
            class="admin-location-form-back"
            href="<?= base_url(
                'admin/locations'
            ) ?>"
        >
            ← Danh sách
        </a>

    </div>


    <?php if (!empty($errors)): ?>

        <div class="admin-location-form-alert">

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
        class="admin-location-form"
        action="<?= base_url(
            'admin/locations/create'
        ) ?>"
        method="POST"
        enctype="multipart/form-data"
    >

        <section class="admin-location-form-card">

            <div class="admin-location-form-card-heading">

                <span>
                    Thông tin cơ bản
                </span>

                <h2>
                    Địa điểm
                </h2>

            </div>


            <div class="admin-location-form-grid">

                <div class="admin-location-form-field">

                    <label for="locationName">
                        Tên địa điểm
                    </label>

                    <input
                        id="locationName"
                        type="text"
                        name="location_name"
                        maxlength="150"
                        value="<?= e(
                            $old[
                                'location_name'
                            ]
                            ?? ''
                        ) ?>"
                        placeholder="Ví dụ: Đà Lạt"
                    >

                    <?php if (
                        !empty(
                            $errors[
                                'location_name'
                            ]
                        )
                    ): ?>

                        <span class="field-error">
                            <?= e(
                                $errors[
                                    'location_name'
                                ]
                            ) ?>
                        </span>

                    <?php endif; ?>

                </div>


                <div class="admin-location-form-field">

                    <label for="locationSlug">
                        Slug
                    </label>

                    <input
                        id="locationSlug"
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


                <div class="admin-location-form-field">

                    <label for="locationProvince">
                        Tỉnh / Thành phố
                    </label>

                    <input
                        id="locationProvince"
                        type="text"
                        name="province_city"
                        maxlength="100"
                        value="<?= e(
                            $old[
                                'province_city'
                            ]
                            ?? ''
                        ) ?>"
                        placeholder="Ví dụ: Lâm Đồng"
                    >

                    <?php if (
                        !empty(
                            $errors[
                                'province_city'
                            ]
                        )
                    ): ?>

                        <span class="field-error">
                            <?= e(
                                $errors[
                                    'province_city'
                                ]
                            ) ?>
                        </span>

                    <?php endif; ?>

                </div>


                <div class="admin-location-form-field">

                    <label for="locationCountry">
                        Quốc gia
                    </label>

                    <input
                        id="locationCountry"
                        type="text"
                        name="country"
                        maxlength="100"
                        value="<?= e(
                            $old['country']
                            ?? 'Việt Nam'
                        ) ?>"
                        placeholder="Việt Nam"
                    >

                    <?php if (
                        !empty(
                            $errors['country']
                        )
                    ): ?>

                        <span class="field-error">
                            <?= e(
                                $errors['country']
                            ) ?>
                        </span>

                    <?php endif; ?>

                </div>


                <div class="admin-location-form-field full">

                    <label for="locationAddress">
                        Địa chỉ
                    </label>

                    <input
                        id="locationAddress"
                        type="text"
                        name="address"
                        maxlength="255"
                        value="<?= e(
                            $old['address']
                            ?? ''
                        ) ?>"
                        placeholder="Địa chỉ cụ thể nếu có"
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

            </div>

        </section>


        <section class="admin-location-form-card">

            <div class="admin-location-form-card-heading">

                <span>
                    Bản đồ
                </span>

                <h2>
                    Tọa độ địa lý
                </h2>

                <p>
                    Tọa độ được sử dụng để hiển thị
                    địa điểm trên bản đồ Leaflet.
                </p>

            </div>


            <div class="admin-location-form-grid">

                <div class="admin-location-form-field">

                    <label for="locationLatitude">
                        Vĩ độ
                    </label>

                    <input
                        id="locationLatitude"
                        type="number"
                        name="latitude"
                        step="0.0000001"
                        min="-90"
                        max="90"
                        value="<?= e(
                            (string) (
                                $old['latitude']
                                ?? ''
                            )
                        ) ?>"
                        placeholder="Ví dụ: 11.9404192"
                    >

                    <?php if (
                        !empty(
                            $errors['latitude']
                        )
                    ): ?>

                        <span class="field-error">
                            <?= e(
                                $errors['latitude']
                            ) ?>
                        </span>

                    <?php endif; ?>

                    <span class="field-help">
                        Giá trị hợp lệ từ -90 đến 90.
                    </span>

                </div>


                <div class="admin-location-form-field">

                    <label for="locationLongitude">
                        Kinh độ
                    </label>

                    <input
                        id="locationLongitude"
                        type="number"
                        name="longitude"
                        step="0.0000001"
                        min="-180"
                        max="180"
                        value="<?= e(
                            (string) (
                                $old['longitude']
                                ?? ''
                            )
                        ) ?>"
                        placeholder="Ví dụ: 108.4583132"
                    >

                    <?php if (
                        !empty(
                            $errors['longitude']
                        )
                    ): ?>

                        <span class="field-error">
                            <?= e(
                                $errors['longitude']
                            ) ?>
                        </span>

                    <?php endif; ?>

                    <span class="field-help">
                        Giá trị hợp lệ từ -180 đến 180.
                    </span>

                </div>


                <div class="admin-location-coordinate-preview full">

                    <div>

                        <span>
                            Tọa độ hiện tại
                        </span>

                        <strong id="locationCoordinatePreview">
                            Chưa nhập tọa độ
                        </strong>

                    </div>

                    <a
                        id="locationMapPreviewLink"
                        href="#"
                        target="_blank"
                        rel="noopener noreferrer"
                        hidden
                    >
                        Kiểm tra vị trí
                    </a>

                </div>

            </div>

        </section>


        <section class="admin-location-form-card">

            <div class="admin-location-form-card-heading">

                <span>
                    Hình ảnh
                </span>

                <h2>
                    Ảnh địa điểm
                </h2>

            </div>


            <div class="admin-location-form-grid">

                <div class="admin-location-form-field full">

                    <label for="locationImage">
                        Ảnh
                    </label>

                    <div class="location-image-upload">

                        <div
                            class="location-image-preview"
                            id="locationImagePreview"
                        >

                            <span>
                                Ảnh
                            </span>

                            <img
                                id="locationImagePreviewImage"
                                alt="Ảnh xem trước"
                            >

                        </div>


                        <div class="location-image-upload-content">

                            <input
                                id="locationImage"
                                type="file"
                                name="image"
                                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            >

                            <p>
                                JPG, PNG hoặc WEBP.
                                Dung lượng tối đa 5MB.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </section>


        <section class="admin-location-form-card">

            <div class="admin-location-form-card-heading">

                <span>
                    Nội dung
                </span>

                <h2>
                    Mô tả và trạng thái
                </h2>

            </div>


            <div class="admin-location-form-grid">

                <div class="admin-location-form-field full">

                    <label for="locationDescription">
                        Mô tả
                    </label>

                    <textarea
                        id="locationDescription"
                        name="description"
                        rows="7"
                        maxlength="10000"
                        placeholder="Thông tin giới thiệu về địa điểm..."
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


                <div class="admin-location-form-field">

                    <label for="locationStatus">
                        Trạng thái
                    </label>

                    <select
                        id="locationStatus"
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


        <div class="admin-location-form-actions">

            <a
                href="<?= base_url(
                    'admin/locations'
                ) ?>"
            >
                Hủy
            </a>

            <button type="submit">
                Tạo địa điểm
            </button>

        </div>

    </form>

</section>


<script>
document.addEventListener(
    'DOMContentLoaded',
    function () {
        const imageInput =
            document.getElementById(
                'locationImage'
            );

        const imagePreview =
            document.getElementById(
                'locationImagePreviewImage'
            );

        const latitudeInput =
            document.getElementById(
                'locationLatitude'
            );

        const longitudeInput =
            document.getElementById(
                'locationLongitude'
            );

        const coordinatePreview =
            document.getElementById(
                'locationCoordinatePreview'
            );

        const mapPreviewLink =
            document.getElementById(
                'locationMapPreviewLink'
            );

        if (
            imageInput
            && imagePreview
        ) {
            imageInput.addEventListener(
                'change',
                function () {
                    const file =
                        imageInput.files
                        && imageInput.files[0];

                    if (!file) {
                        imagePreview.removeAttribute(
                            'src'
                        );

                        imagePreview.classList.remove(
                            'visible'
                        );

                        return;
                    }

                    if (
                        !file.type.startsWith(
                            'image/'
                        )
                    ) {
                        imagePreview.removeAttribute(
                            'src'
                        );

                        imagePreview.classList.remove(
                            'visible'
                        );

                        return;
                    }

                    const reader =
                        new FileReader();

                    reader.onload =
                        function (event) {
                            imagePreview.src =
                                event.target.result;

                            imagePreview.classList.add(
                                'visible'
                            );
                        };

                    reader.readAsDataURL(
                        file
                    );
                }
            );
        }

        function updateCoordinatePreview() {
            if (
                !latitudeInput
                || !longitudeInput
                || !coordinatePreview
                || !mapPreviewLink
            ) {
                return;
            }

            const latitude =
                latitudeInput.value.trim();

            const longitude =
                longitudeInput.value.trim();

            if (
                latitude === ''
                || longitude === ''
            ) {
                coordinatePreview.textContent =
                    'Chưa nhập đầy đủ tọa độ';

                mapPreviewLink.hidden =
                    true;

                mapPreviewLink.removeAttribute(
                    'href'
                );

                return;
            }

            const latitudeNumber =
                Number(latitude);

            const longitudeNumber =
                Number(longitude);

            if (
                !Number.isFinite(
                    latitudeNumber
                )
                || !Number.isFinite(
                    longitudeNumber
                )
            ) {
                coordinatePreview.textContent =
                    'Tọa độ chưa hợp lệ';

                mapPreviewLink.hidden =
                    true;

                return;
            }

            coordinatePreview.textContent =
                latitude
                + ', '
                + longitude;

            mapPreviewLink.href =
                'https://www.openstreetmap.org/'
                + '?mlat='
                + encodeURIComponent(
                    latitude
                )
                + '&mlon='
                + encodeURIComponent(
                    longitude
                )
                + '#map=14/'
                + encodeURIComponent(
                    latitude
                )
                + '/'
                + encodeURIComponent(
                    longitude
                );

            mapPreviewLink.hidden =
                false;
        }

        if (
            latitudeInput
            && longitudeInput
        ) {
            latitudeInput.addEventListener(
                'input',
                updateCoordinatePreview
            );

            longitudeInput.addEventListener(
                'input',
                updateCoordinatePreview
            );

            updateCoordinatePreview();
        }
    }
);
</script>