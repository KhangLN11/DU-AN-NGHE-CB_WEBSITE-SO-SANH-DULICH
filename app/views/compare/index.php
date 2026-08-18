<section class="compare-hero">

    <div class="page-container">

        <span class="compare-label">
            So sánh hành trình
        </span>

        <h1>
            So sánh Tour
        </h1>

        <p>
            Chọn từ hai đến ba Tour để đặt các thông tin
            quan trọng cạnh nhau và dễ dàng đưa ra lựa chọn.
        </p>

    </div>

</section>


<section class="compare-section">

    <div class="page-container">

        <div class="compare-toolbar">

            <div>

                <strong>
                    <?= (int) $selectedCount ?>/3
                </strong>

                <span>
                    Tour đã chọn
                </span>

            </div>


            <?php if ($selectedCount > 0): ?>

                <form
                    action="<?= base_url('compare/clear') ?>"
                    method="POST"
                >

                    <button
                        class="compare-clear-button"
                        type="submit"
                    >
                        Xóa tất cả
                    </button>

                </form>

            <?php endif; ?>

        </div>


        <?php if ($selectedCount === 0): ?>

            <div class="compare-empty">

                <div class="compare-empty-icon">
                    T
                </div>

                <h2>
                    Chưa có Tour nào được chọn
                </h2>

                <p>
                    Hãy chọn từ hai đến ba Tour
                    để bắt đầu so sánh.
                </p>

                <a
                    class="compare-primary-button"
                    href="<?= base_url('tours') ?>"
                >
                    Khám phá Tour
                </a>

            </div>


        <?php else: ?>

            <div class="compare-selected-grid">

                <?php foreach ($tours as $tour): ?>

                    <article class="compare-selected-card">

                        <div class="compare-selected-image">

                            <div class="compare-image-placeholder">
                                <?= e(
                                    mb_substr(
                                        $tour['tour_name'],
                                        0,
                                        1
                                    )
                                ) ?>
                            </div>


                            <?php if (!empty($tour['image_url'])): ?>

                                <img
                                    src="<?= asset(
                                        ltrim(
                                            $tour['image_url'],
                                            '/'
                                        )
                                    ) ?>"
                                    alt="<?= e($tour['tour_name']) ?>"
                                    onerror="this.style.display='none'"
                                >

                            <?php endif; ?>

                        </div>


                        <div class="compare-selected-content">

                            <span>
                                <?= e($tour['company_name']) ?>
                            </span>

                            <h2>
                                <?= e($tour['tour_name']) ?>
                            </h2>


                            <form
                                action="<?= base_url('compare/remove') ?>"
                                method="POST"
                            >

                                <input
                                    type="hidden"
                                    name="tour_id"
                                    value="<?= (int) $tour['tour_id'] ?>"
                                >

                                <button
                                    class="compare-remove-button"
                                    type="submit"
                                >
                                    Xóa khỏi so sánh
                                </button>

                            </form>

                        </div>

                    </article>

                <?php endforeach; ?>


                <?php if ($selectedCount < 3): ?>

                    <a
                        class="compare-add-card"
                        href="<?= base_url('tours') ?>"
                    >

                        <span>
                            +
                        </span>

                        <strong>
                            Thêm Tour
                        </strong>

                        <small>
                            Tối đa 3 Tour
                        </small>

                    </a>

                <?php endif; ?>

            </div>


            <?php if ($selectedCount < 2): ?>

                <div class="compare-notice">

                    <strong>
                        Cần thêm một Tour nữa
                    </strong>

                    <p>
                        Bạn cần chọn ít nhất hai Tour
                        để hiển thị bảng so sánh.
                    </p>

                    <a href="<?= base_url('tours') ?>">
                        Chọn thêm Tour →
                    </a>

                </div>


            <?php else: ?>

                <div class="comparison-table-wrapper">

                    <table class="comparison-table">

                        <thead>

                            <tr>

                                <th class="criteria-column">
                                    Tiêu chí
                                </th>

                                <?php foreach ($tours as $tour): ?>

                                    <th>

                                        <a
                                            href="<?= base_url(
                                                'tours/' . $tour['tour_id']
                                            ) ?>"
                                        >
                                            <?= e($tour['tour_name']) ?>
                                        </a>

                                    </th>

                                <?php endforeach; ?>

                            </tr>

                        </thead>


                        <tbody>

                            <tr>

                                <th>
                                    Giá tham khảo
                                </th>

                                <?php foreach ($tours as $tour): ?>

                                    <td class="comparison-price">

                                        <?= number_format(
                                            (float) $tour['price'],
                                            0,
                                            ',',
                                            '.'
                                        ) ?>

                                        ₫

                                    </td>

                                <?php endforeach; ?>

                            </tr>


                            <tr>

                                <th>
                                    Thời gian
                                </th>

                                <?php foreach ($tours as $tour): ?>

                                    <td>

                                        <?= (int) $tour['duration_days'] ?>
                                        ngày

                                        <?= (int) $tour['duration_nights'] ?>
                                        đêm

                                    </td>

                                <?php endforeach; ?>

                            </tr>


                            <tr>

                                <th>
                                    Loại hình
                                </th>

                                <?php foreach ($tours as $tour): ?>

                                    <td>
                                        <?= e($tour['category_name']) ?>
                                    </td>

                                <?php endforeach; ?>

                            </tr>


                            <tr>

                                <th>
                                    Đơn vị tổ chức
                                </th>

                                <?php foreach ($tours as $tour): ?>

                                    <td>
                                        <?= e($tour['company_name']) ?>
                                    </td>

                                <?php endforeach; ?>

                            </tr>


                            <tr>

                                <th>
                                    Khởi hành
                                </th>

                                <?php foreach ($tours as $tour): ?>

                                    <td>

                                        <?= e(
                                            $tour['departure_name']
                                                ?? 'Đang cập nhật'
                                        ) ?>

                                    </td>

                                <?php endforeach; ?>

                            </tr>


                            <tr>

                                <th>
                                    Điểm đến
                                </th>

                                <?php foreach ($tours as $tour): ?>

                                    <td>

                                        <?= e(
                                            $tour['destinations']
                                                ?? 'Đang cập nhật'
                                        ) ?>

                                    </td>

                                <?php endforeach; ?>

                            </tr>


                            <tr>

                                <th>
                                    Chi tiết
                                </th>

                                <?php foreach ($tours as $tour): ?>

                                    <td>

                                        <a
                                            class="comparison-detail-button"
                                            href="<?= base_url(
                                                'tours/' . $tour['tour_id']
                                            ) ?>"
                                        >
                                            Xem Tour
                                        </a>

                                    </td>

                                <?php endforeach; ?>

                            </tr>

                        </tbody>

                    </table>

                </div>

            <?php endif; ?>

        <?php endif; ?>

    </div>

</section>