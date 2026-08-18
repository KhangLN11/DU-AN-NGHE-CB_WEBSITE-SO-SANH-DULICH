<section class="contact-hero">

    <div class="page-container">

        <span class="contact-label">
            TourCompare
        </span>

        <h1>
            Liên hệ với chúng tôi
        </h1>

        <p>
            Nếu bạn có câu hỏi, góp ý hoặc cần hỗ trợ,
            hãy gửi nội dung cho TourCompare.
        </p>

    </div>

</section>

<section class="contact-section">

    <div class="page-container">

        <div class="contact-layout">

            <div class="contact-information">

                <span class="contact-information-label">
                    Hỗ trợ
                </span>

                <h2>
                    Chúng tôi luôn sẵn sàng lắng nghe
                </h2>

                <p>
                    Bạn có thể gửi câu hỏi về Tour,
                    nội dung website hoặc các vấn đề
                    liên quan đến trải nghiệm sử dụng.
                </p>

                <div class="contact-info-list">

                    <div class="contact-info-item">

                        <span>
                            01
                        </span>

                        <div>

                            <strong>
                                Thông tin Tour
                            </strong>

                            <p>
                                Góp ý hoặc phản hồi về
                                dữ liệu Tour trên hệ thống.
                            </p>

                        </div>

                    </div>

                    <div class="contact-info-item">

                        <span>
                            02
                        </span>

                        <div>

                            <strong>
                                Hỗ trợ tài khoản
                            </strong>

                            <p>
                                Báo lỗi hoặc yêu cầu hỗ trợ
                                khi sử dụng tài khoản.
                            </p>

                        </div>

                    </div>

                    <div class="contact-info-item">

                        <span>
                            03
                        </span>

                        <div>

                            <strong>
                                Góp ý hệ thống
                            </strong>

                            <p>
                                Chia sẻ ý kiến để TourCompare
                                ngày càng hoàn thiện hơn.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

            <div class="contact-card">

                <div class="contact-card-header">

                    <span>
                        Gửi liên hệ
                    </span>

                    <h2>
                        Nội dung của bạn
                    </h2>

                    <p>
                        Vui lòng điền đầy đủ thông tin bên dưới.
                    </p>

                </div>

                <?php if (!empty($successMessage)): ?>

                    <div class="contact-alert success">
                        <?= e($successMessage) ?>
                    </div>

                <?php endif; ?>

                <form
                    class="contact-form"
                    action="<?= base_url('contact') ?>"
                    method="POST"
                    novalidate
                >

                    <div class="contact-field">

                        <label for="full_name">
                            Họ và tên
                        </label>

                        <input
                            id="full_name"
                            type="text"
                            name="full_name"
                            maxlength="100"
                            value="<?= e(
                                $old['full_name']
                                ?? ''
                            ) ?>"
                            placeholder="Nguyễn Văn A"
                            autocomplete="name"
                        >

                        <?php if (!empty($errors['full_name'])): ?>

                            <span class="contact-field-error">
                                <?= e($errors['full_name']) ?>
                            </span>

                        <?php endif; ?>

                    </div>

                    <div class="contact-field">

                        <label for="email">
                            Email
                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            maxlength="150"
                            value="<?= e(
                                $old['email']
                                ?? ''
                            ) ?>"
                            placeholder="example@email.com"
                            autocomplete="email"
                        >

                        <?php if (!empty($errors['email'])): ?>

                            <span class="contact-field-error">
                                <?= e($errors['email']) ?>
                            </span>

                        <?php endif; ?>

                    </div>

                    <div class="contact-field">

                        <label for="subject">
                            Chủ đề
                        </label>

                        <input
                            id="subject"
                            type="text"
                            name="subject"
                            maxlength="200"
                            value="<?= e(
                                $old['subject']
                                ?? ''
                            ) ?>"
                            placeholder="Nội dung bạn muốn liên hệ"
                        >

                        <?php if (!empty($errors['subject'])): ?>

                            <span class="contact-field-error">
                                <?= e($errors['subject']) ?>
                            </span>

                        <?php endif; ?>

                    </div>

                    <div class="contact-field">

                        <label for="message">
                            Nội dung
                        </label>

                        <textarea
                            id="message"
                            name="message"
                            maxlength="5000"
                            rows="7"
                            placeholder="Nhập nội dung liên hệ..."
                        ><?= e(
                            $old['message']
                            ?? ''
                        ) ?></textarea>

                        <?php if (!empty($errors['message'])): ?>

                            <span class="contact-field-error">
                                <?= e($errors['message']) ?>
                            </span>

                        <?php endif; ?>

                    </div>

                    <button
                        class="contact-submit"
                        type="submit"
                    >
                        Gửi liên hệ
                    </button>

                </form>

            </div>

        </div>

    </div>

</section>