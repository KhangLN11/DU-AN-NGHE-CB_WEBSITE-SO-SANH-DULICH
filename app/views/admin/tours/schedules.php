<section class="admin-page">

    <div class="admin-page-heading admin-schedule-heading">

        <div>

            <span>
                Tour Management
            </span>

            <h1>
                Lịch trình Tour
            </h1>

            <p>
                Quản lý chương trình từng ngày của
                <?= e($tour['tour_name']) ?>.
            </p>

        </div>

        <div class="admin-schedule-heading-actions">

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
                    . '/images'
                ) ?>"
            >
                Hình ảnh
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

        <div class="admin-schedule-alert success">
            <?= e($successMessage) ?>
        </div>

    <?php endif; ?>


    <?php if (!empty($errorMessage)): ?>

        <div class="admin-schedule-alert error">
            <?= e($errorMessage) ?>
        </div>

    <?php endif; ?>


    <?php if (!empty($errors)): ?>

        <div class="admin-schedule-alert error">

            <?php foreach ($errors as $error): ?>

                <div>
                    <?= e($error) ?>
                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>


    <div class="admin-schedule-guide">

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

        <div class="admin-schedule-duration">

            <span>
                Thời lượng
            </span>

            <strong>
                <?= (int)
                $tour['duration_days'] ?>
                ngày
                <?= (int)
                $tour['duration_nights'] ?>
                đêm
            </strong>

        </div>

    </div>


    <form
        class="admin-schedule-form"
        action="<?= base_url(
            'admin/tours/'
            . $tourId
            . '/schedules'
        ) ?>"
        method="POST"
    >

        <div class="admin-schedule-toolbar">

            <div>

                <span>
                    Chương trình
                </span>

                <strong id="scheduleCount">
                    <?= count($schedules) ?>
                    ngày
                </strong>

            </div>

            <button
                id="addScheduleRow"
                class="admin-add-schedule"
                type="button"
            >
                + Thêm ngày
            </button>

        </div>


        <div
            id="scheduleRows"
            class="admin-schedule-rows"
        >

            <?php foreach (
                $schedules
                as $index => $schedule
            ): ?>

                <article class="admin-schedule-row">

                    <div class="admin-schedule-day">

                        <span>
                            Ngày
                        </span>

                        <strong>
                            <?= $index + 1 ?>
                        </strong>

                    </div>


                    <div class="admin-schedule-fields">

                        <input
                            class="schedule-day-input"
                            type="hidden"
                            name="day_number[]"
                            value="<?= (int)
                            $schedule[
                                'day_number'
                            ] ?>"
                        >


                        <div class="admin-schedule-field">

                            <label>
                                Tiêu đề
                            </label>

                            <input
                                type="text"
                                name="title[]"
                                maxlength="200"
                                value="<?= e(
                                    $schedule[
                                        'title'
                                    ]
                                ) ?>"
                                placeholder="Ví dụ: TP.HCM - Đà Lạt"
                            >

                        </div>


                        <div class="admin-schedule-field">

                            <label>
                                Mô tả
                            </label>

                            <textarea
                                name="description[]"
                                rows="5"
                                maxlength="5000"
                                placeholder="Nội dung chương trình trong ngày..."
                            ><?= e(
                                $schedule[
                                    'description'
                                ]
                                ?? ''
                            ) ?></textarea>

                        </div>

                    </div>


                    <button
                        class="admin-remove-schedule"
                        type="button"
                    >
                        Xóa ngày
                    </button>

                </article>

            <?php endforeach; ?>

        </div>


        <div
            id="scheduleEmpty"
            class="admin-schedule-empty"
            <?= !empty($schedules)
                ? 'hidden'
                : '' ?>
        >
            Chưa có lịch trình.
            Nhấn “Thêm ngày” để bắt đầu.
        </div>


        <div class="admin-schedule-actions">

            <a href="<?= base_url('admin/tours') ?>">
                Hủy
            </a>

            <button type="submit">
                Lưu lịch trình
            </button>

        </div>

    </form>


    <template id="scheduleRowTemplate">

        <article class="admin-schedule-row">

            <div class="admin-schedule-day">

                <span>
                    Ngày
                </span>

                <strong>
                    1
                </strong>

            </div>


            <div class="admin-schedule-fields">

                <input
                    class="schedule-day-input"
                    type="hidden"
                    name="day_number[]"
                    value="1"
                >


                <div class="admin-schedule-field">

                    <label>
                        Tiêu đề
                    </label>

                    <input
                        type="text"
                        name="title[]"
                        maxlength="200"
                        placeholder="Ví dụ: TP.HCM - Đà Lạt"
                    >

                </div>


                <div class="admin-schedule-field">

                    <label>
                        Mô tả
                    </label>

                    <textarea
                        name="description[]"
                        rows="5"
                        maxlength="5000"
                        placeholder="Nội dung chương trình trong ngày..."
                    ></textarea>

                </div>

            </div>


            <button
                class="admin-remove-schedule"
                type="button"
            >
                Xóa ngày
            </button>

        </article>

    </template>

</section>