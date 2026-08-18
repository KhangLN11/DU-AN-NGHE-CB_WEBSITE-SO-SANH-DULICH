<?php

$selectedRows = [];

foreach ($tourLocations as $location) {
    $selectedRows[] = [
        'location_id' =>
            (int) $location['location_id'],

        'sort_order' =>
            (int) $location['sort_order'],

        'note' =>
            $location['note']
            ?? ''
    ];
}

?>

<section class="admin-page">

    <div class="admin-page-heading admin-location-heading">

        <div>

            <span>
                Tour Management
            </span>

            <h1>
                Điểm đến
            </h1>

            <p>
                Quản lý hành trình của
                <?= e($tour['tour_name']) ?>.
            </p>

        </div>

        <div class="admin-location-heading-actions">

            <a
                class="admin-location-secondary"
                href="<?= base_url(
                    'admin/tours/'
                    . $tourId
                    . '/edit'
                ) ?>"
            >
                Sửa Tour
            </a>

            <a
                class="admin-location-back"
                href="<?= base_url(
                    'admin/tours'
                ) ?>"
            >
                ← Danh sách Tour
            </a>

        </div>

    </div>


    <?php if (!empty($successMessage)): ?>

        <div class="admin-location-alert success">
            <?= e($successMessage) ?>
        </div>

    <?php endif; ?>


    <?php if (!empty($errorMessage)): ?>

        <div class="admin-location-alert error">
            <?= e($errorMessage) ?>
        </div>

    <?php endif; ?>


    <?php if (!empty($errors)): ?>

        <div class="admin-location-alert error">

            <?php foreach ($errors as $error): ?>

                <div>
                    <?= e($error) ?>
                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>


    <div class="admin-location-guide">

        <div>

            <span>
                Tour
            </span>

            <strong>
                #<?= (int) $tourId ?>
                ·
                <?= e($tour['tour_name']) ?>
            </strong>

        </div>

        <p>
            Chọn các điểm đến theo đúng thứ tự
            hành trình. Dữ liệu này sẽ được dùng
            cho danh sách điểm đến và bản đồ
            Leaflet phía người dùng.
        </p>

    </div>


    <form
        class="admin-location-form"
        action="<?= base_url(
            'admin/tours/'
            . $tourId
            . '/locations'
        ) ?>"
        method="POST"
    >

        <div class="admin-location-toolbar">

            <div>

                <span>
                    Hành trình
                </span>

                <strong id="locationCount">
                    <?= count($selectedRows) ?>
                    điểm đến
                </strong>

            </div>

            <button
                id="addLocationRow"
                class="admin-add-location"
                type="button"
            >
                + Thêm điểm đến
            </button>

        </div>


        <div
            id="locationRows"
            class="admin-location-rows"
        >

            <?php foreach (
                $selectedRows
                as $index => $selected
            ): ?>

                <div class="admin-location-row">

                    <div class="admin-location-order">

                        <span>
                            Điểm
                        </span>

                        <strong>
                            <?= $index + 1 ?>
                        </strong>

                    </div>

                    <div class="admin-location-field">

                        <label>
                            Địa điểm
                        </label>

                        <select name="location_id[]">

                            <option value="">
                                Chọn địa điểm
                            </option>

                            <?php foreach (
                                $availableLocations
                                as $location
                            ): ?>

                                <option
                                    value="<?= (int)
                                    $location[
                                        'location_id'
                                    ] ?>"
                                    <?= (int)
                                    $selected[
                                        'location_id'
                                    ] ===
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

                    </div>

                    <div class="admin-location-field order-field">

                        <label>
                            Thứ tự
                        </label>

                        <input
                            type="number"
                            name="sort_order[]"
                            min="1"
                            value="<?= (int)
                            $selected[
                                'sort_order'
                            ] ?>"
                        >

                    </div>

                    <div class="admin-location-field note-field">

                        <label>
                            Ghi chú
                        </label>

                        <input
                            type="text"
                            name="note[]"
                            maxlength="500"
                            value="<?= e(
                                $selected['note']
                            ) ?>"
                            placeholder="Thông tin thêm..."
                        >

                    </div>

                    <button
                        class="admin-remove-location"
                        type="button"
                    >
                        Xóa
                    </button>

                </div>

            <?php endforeach; ?>

        </div>


        <div
            id="locationEmpty"
            class="admin-location-empty"
            <?= !empty($selectedRows)
                ? 'hidden'
                : '' ?>
        >
            Chưa có điểm đến nào.
            Nhấn “Thêm điểm đến” để bắt đầu.
        </div>


        <div class="admin-location-actions">

            <a
                href="<?= base_url(
                    'admin/tours'
                ) ?>"
            >
                Hủy
            </a>

            <button type="submit">
                Lưu hành trình
            </button>

        </div>

    </form>


    <template id="locationRowTemplate">

        <div class="admin-location-row">

            <div class="admin-location-order">

                <span>
                    Điểm
                </span>

                <strong>
                    1
                </strong>

            </div>

            <div class="admin-location-field">

                <label>
                    Địa điểm
                </label>

                <select name="location_id[]">

                    <option value="">
                        Chọn địa điểm
                    </option>

                    <?php foreach (
                        $availableLocations
                        as $location
                    ): ?>

                        <option
                            value="<?= (int)
                            $location[
                                'location_id'
                            ] ?>"
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

            </div>

            <div class="admin-location-field order-field">

                <label>
                    Thứ tự
                </label>

                <input
                    type="number"
                    name="sort_order[]"
                    min="1"
                    value="1"
                >

            </div>

            <div class="admin-location-field note-field">

                <label>
                    Ghi chú
                </label>

                <input
                    type="text"
                    name="note[]"
                    maxlength="500"
                    placeholder="Thông tin thêm..."
                >

            </div>

            <button
                class="admin-remove-location"
                type="button"
            >
                Xóa
            </button>

        </div>

    </template>

</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const rows = document.getElementById('locationRows');
    const addButton = document.getElementById('addLocationRow');
    const template = document.getElementById('locationRowTemplate');
    const count = document.getElementById('locationCount');
    const empty = document.getElementById('locationEmpty');

    if (!rows || !addButton || !template) {
        return;
    }

    function refreshRows() {
        const items = rows.querySelectorAll(
            '.admin-location-row'
        );

        items.forEach(function (item, index) {
            const orderLabel = item.querySelector(
                '.admin-location-order strong'
            );

            const orderInput = item.querySelector(
                'input[name="sort_order[]"]'
            );

            if (orderLabel) {
                orderLabel.textContent =
                    String(index + 1);
            }

            if (orderInput) {
                orderInput.value =
                    String(index + 1);
            }
        });

        if (count) {
            count.textContent =
                items.length
                + ' điểm đến';
        }

        if (empty) {
            empty.hidden =
                items.length > 0;
        }
    }

    function attachRemoveButton(row) {
        const button = row.querySelector(
            '.admin-remove-location'
        );

        if (!button) {
            return;
        }

        button.addEventListener('click', function () {
            row.remove();
            refreshRows();
        });
    }

    rows.querySelectorAll(
        '.admin-location-row'
    ).forEach(function (row) {
        attachRemoveButton(row);
    });

    addButton.addEventListener('click', function () {
        const fragment =
            template.content.cloneNode(true);

        const row = fragment.querySelector(
            '.admin-location-row'
        );

        if (!row) {
            return;
        }

        rows.appendChild(fragment);

        const insertedRows =
            rows.querySelectorAll(
                '.admin-location-row'
            );

        const inserted =
            insertedRows[
                insertedRows.length - 1
            ];

        attachRemoveButton(inserted);
        refreshRows();
    });

    refreshRows();
});
</script>