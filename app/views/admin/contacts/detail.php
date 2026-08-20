<section class="admin-page">

    <div class="admin-page-heading admin-contact-detail-heading">

        <div>

            <span>
                Contact Management
            </span>

            <h1>
                Chi tiết liên hệ
            </h1>

            <p>
                Xem nội dung yêu cầu liên hệ
                #<?= (int) $contact['contact_id'] ?>.
            </p>

        </div>

        <a
            class="admin-contact-detail-back"
            href="<?= base_url(
                'admin/contacts'
            ) ?>"
        >
            ← Danh sách
        </a>

    </div>


    <?php if (!empty($successMessage)): ?>

        <div class="admin-contact-detail-alert success">
            <?= e($successMessage) ?>
        </div>

    <?php endif; ?>


    <?php if (!empty($errorMessage)): ?>

        <div class="admin-contact-detail-alert error">
            <?= e($errorMessage) ?>
        </div>

    <?php endif; ?>


    <section class="admin-contact-summary">

        <div>

            <span class="contact-summary-id">
                #<?= (int)
                $contact['contact_id'] ?>
            </span>


            <?php if (
                $contact['status']
                === 'pending'
            ): ?>

                <span class="contact-detail-status pending">
                    Chờ xử lý
                </span>

            <?php elseif (
                $contact['status']
                === 'processing'
            ): ?>

                <span class="contact-detail-status processing">
                    Đang xử lý
                </span>

            <?php else: ?>

                <span class="contact-detail-status resolved">
                    Đã xử lý
                </span>

            <?php endif; ?>


            <?php if (
                $contact['user_id']
                !== null
            ): ?>

                <span class="contact-detail-source user">
                    Thành viên
                </span>

            <?php else: ?>

                <span class="contact-detail-source guest">
                    Khách
                </span>

            <?php endif; ?>

        </div>


        <h2>
            <?= !empty(
                $contact['subject']
            )
                ? e(
                    $contact['subject']
                )
                : 'Không có chủ đề' ?>
        </h2>


        <p>
            Gửi lúc
            <?= e(
                date(
                    'd/m/Y H:i',
                    strtotime(
                        $contact['created_at']
                    )
                )
            ) ?>
        </p>

    </section>


    <div class="admin-contact-detail-grid">

        <section class="admin-contact-detail-card">

            <div class="admin-contact-detail-card-heading">

                <span>
                    Sender
                </span>

                <h2>
                    Thông tin người gửi
                </h2>

            </div>


            <div class="admin-contact-info-list">

                <div class="admin-contact-info-row">

                    <span>
                        Họ tên
                    </span>

                    <strong>
                        <?= e(
                            $contact['full_name']
                        ) ?>
                    </strong>

                </div>


                <div class="admin-contact-info-row">

                    <span>
                        Email
                    </span>

                    <strong>
                        <?= e(
                            $contact['email']
                        ) ?>
                    </strong>

                </div>


                <div class="admin-contact-info-row">

                    <span>
                        Điện thoại
                    </span>

                    <strong>
                        <?= !empty(
                            $contact['phone']
                        )
                            ? e(
                                $contact['phone']
                            )
                            : 'Chưa cung cấp' ?>
                    </strong>

                </div>


                <div class="admin-contact-info-row">

                    <span>
                        Nguồn gửi
                    </span>

                    <strong>
                        <?= $contact['user_id']
                            !== null
                                ? 'Thành viên'
                                : 'Khách' ?>
                    </strong>

                </div>

            </div>

        </section>


        <section class="admin-contact-detail-card">

            <div class="admin-contact-detail-card-heading">

                <span>
                    Account
                </span>

                <h2>
                    Tài khoản liên kết
                </h2>

            </div>


            <?php if (
                $contact['user_id']
                !== null
                && !empty(
                    $contact[
                        'user_full_name'
                    ]
                )
            ): ?>

                <div class="admin-contact-info-list">

                    <div class="admin-contact-info-row">

                        <span>
                            User ID
                        </span>

                        <strong>
                            #<?= (int)
                            $contact['user_id'] ?>
                        </strong>

                    </div>


                    <div class="admin-contact-info-row">

                        <span>
                            Tên tài khoản
                        </span>

                        <strong>
                            <?= e(
                                $contact[
                                    'user_full_name'
                                ]
                            ) ?>
                        </strong>

                    </div>


                    <div class="admin-contact-info-row">

                        <span>
                            Email tài khoản
                        </span>

                        <strong>
                            <?= e(
                                $contact[
                                    'user_email'
                                ]
                            ) ?>
                        </strong>

                    </div>


                    <div class="admin-contact-info-row">

                        <span>
                            Trạng thái tài khoản
                        </span>

                        <strong>

                            <?php if (
                                $contact[
                                    'user_status'
                                ]
                                === 'active'
                            ): ?>

                                Hoạt động

                            <?php elseif (
                                $contact[
                                    'user_status'
                                ]
                                === 'inactive'
                            ): ?>

                                Vô hiệu hóa

                            <?php else: ?>

                                Đã khóa

                            <?php endif; ?>

                        </strong>

                    </div>

                </div>

            <?php elseif (
                $contact['user_id']
                !== null
            ): ?>

                <div class="admin-contact-linked-empty">

                    <strong>
                        Không còn tài khoản liên kết
                    </strong>

                    <p>
                        Contact vẫn lưu thông tin người gửi,
                        nhưng tài khoản User tương ứng
                        hiện không còn tồn tại.
                    </p>

                </div>

            <?php else: ?>

                <div class="admin-contact-linked-empty">

                    <strong>
                        Liên hệ từ khách
                    </strong>

                    <p>
                        Yêu cầu này được gửi mà không
                        liên kết với tài khoản người dùng.
                    </p>

                </div>

            <?php endif; ?>

        </section>

    </div>


    <section class="admin-contact-message-card">

        <div class="admin-contact-detail-card-heading">

            <span>
                Message
            </span>

            <h2>
                Nội dung liên hệ
            </h2>

        </div>


        <div class="admin-contact-message">

            <?= nl2br(
                e(
                    $contact['message']
                )
            ) ?>

        </div>

    </section>


    <div class="admin-contact-detail-grid lower">

        <section class="admin-contact-detail-card">

            <div class="admin-contact-detail-card-heading">

                <span>
                    System
                </span>

                <h2>
                    Thông tin hệ thống
                </h2>

            </div>


            <div class="admin-contact-info-list">

                <div class="admin-contact-info-row">

                    <span>
                        Contact ID
                    </span>

                    <strong>
                        #<?= (int)
                        $contact['contact_id'] ?>
                    </strong>

                </div>


                <div class="admin-contact-info-row">

                    <span>
                        User ID
                    </span>

                    <strong>
                        <?= $contact['user_id']
                            !== null
                                ? '#'
                                    . (int)
                                    $contact[
                                        'user_id'
                                    ]
                                : 'Không có' ?>
                    </strong>

                </div>


                <div class="admin-contact-info-row">

                    <span>
                        Ngày gửi
                    </span>

                    <strong>
                        <?= e(
                            date(
                                'd/m/Y H:i',
                                strtotime(
                                    $contact[
                                        'created_at'
                                    ]
                                )
                            )
                        ) ?>
                    </strong>

                </div>


                <div class="admin-contact-info-row">

                    <span>
                        Cập nhật gần nhất
                    </span>

                    <strong>
                        <?= e(
                            date(
                                'd/m/Y H:i',
                                strtotime(
                                    $contact[
                                        'updated_at'
                                    ]
                                )
                            )
                        ) ?>
                    </strong>

                </div>

            </div>

        </section>


        <section class="admin-contact-detail-card">

            <div class="admin-contact-detail-card-heading">

                <span>
                    Admin
                </span>

                <h2>
                    Ghi chú quản trị
                </h2>

            </div>


            <?php if (
                !empty(
                    $contact['admin_note']
                )
            ): ?>

                <div class="admin-contact-note">

                    <?= nl2br(
                        e(
                            $contact[
                                'admin_note'
                            ]
                        )
                    ) ?>

                </div>

            <?php else: ?>

                <div class="admin-contact-note-empty">

                    <strong>
                        Chưa có ghi chú
                    </strong>

                    <p>
                        Ghi chú xử lý sẽ được quản lý
                        ở bước tiếp theo.
                    </p>

                </div>

            <?php endif; ?>

        </section>

    </div>

    <section class="admin-contact-management-card">

    <div class="admin-contact-detail-card-heading">

        <span>
            Management
        </span>

        <h2>
            Xử lý liên hệ
        </h2>

    </div>


    <form
        class="admin-contact-management-form"
        action="<?= base_url(
            'admin/contacts/'
            . $contact['contact_id']
            . '/manage'
        ) ?>"
        method="POST"
    >

        <div class="admin-contact-management-field">

            <label for="contactStatus">
                Trạng thái
            </label>

            <select
                id="contactStatus"
                name="status"
            >

                <option
                    value="pending"
                    <?= $contact['status']
                        === 'pending'
                            ? 'selected'
                            : '' ?>
                >
                    Chờ xử lý
                </option>

                <option
                    value="processing"
                    <?= $contact['status']
                        === 'processing'
                            ? 'selected'
                            : '' ?>
                >
                    Đang xử lý
                </option>

                <option
                    value="resolved"
                    <?= $contact['status']
                        === 'resolved'
                            ? 'selected'
                            : '' ?>
                >
                    Đã xử lý
                </option>

            </select>

        </div>


        <div class="admin-contact-management-field">

            <label for="contactAdminNote">
                Ghi chú quản trị
            </label>

            <textarea
                id="contactAdminNote"
                name="admin_note"
                rows="6"
                maxlength="10000"
                placeholder="Nhập ghi chú nội bộ về quá trình xử lý..."
            ><?= e(
                $contact['admin_note']
                ?? ''
            ) ?></textarea>

            <span class="admin-contact-management-help">
                Ghi chú này chỉ dành cho khu vực quản trị.
            </span>

        </div>


        <div class="admin-contact-management-actions">

            <a
                href="<?= base_url(
                    'admin/contacts'
                ) ?>"
            >
                Hủy
            </a>

            <button type="submit">
                Lưu xử lý
            </button>

        </div>

    </form>

</section>

<section class="admin-contact-delete-card">

    <div class="admin-contact-detail-card-heading">

        <span>
            Danger Zone
        </span>

        <h2>
            Xóa liên hệ
        </h2>

    </div>


    <div class="admin-contact-delete-body">

        <div>

            <strong>
                Xóa vĩnh viễn liên hệ
            </strong>

            <p>
                Thao tác này sẽ xóa bản ghi liên hệ
                khỏi hệ thống và không thể hoàn tác.
            </p>

        </div>


        <form
            action="<?= base_url(
                'admin/contacts/'
                . $contact[
                    'contact_id'
                ]
                . '/delete'
            ) ?>"
            method="POST"
            onsubmit="return confirm(
                'Bạn có chắc muốn xóa vĩnh viễn liên hệ này? Hành động này không thể hoàn tác.'
            );"
        >

            <button
                class="admin-contact-delete-button"
                type="submit"
            >
                Xóa liên hệ
            </button>

        </form>

    </div>

</section>
    

<div class="admin-contact-detail-actions">

        <a
            href="<?= base_url(
                'admin/contacts'
            ) ?>"
        >
            ← Quay lại danh sách
        </a>

    </div>

</section>