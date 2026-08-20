<section class="admin-page">

    <div class="admin-page-heading admin-contact-heading">

        <div>

            <span>
                Contact Management
            </span>

            <h1>
                Liên hệ
            </h1>

            <p>
                Theo dõi và xử lý các yêu cầu
                liên hệ gửi đến VivuTourViet.
            </p>

        </div>

    </div>


    <?php if (!empty($successMessage)): ?>

        <div class="admin-contact-alert success">
            <?= e($successMessage) ?>
        </div>

    <?php endif; ?>


    <?php if (!empty($errorMessage)): ?>

        <div class="admin-contact-alert error">
            <?= e($errorMessage) ?>
        </div>

    <?php endif; ?>


    <div class="admin-contact-stats">

        <div class="contact-stat pending">

            <span>
                Chờ xử lý
            </span>

            <strong>
                <?= (int)
                $statusCounts[
                    'pending'
                ] ?>
            </strong>

        </div>


        <div class="contact-stat processing">

            <span>
                Đang xử lý
            </span>

            <strong>
                <?= (int)
                $statusCounts[
                    'processing'
                ] ?>
            </strong>

        </div>


        <div class="contact-stat resolved">

            <span>
                Đã xử lý
            </span>

            <strong>
                <?= (int)
                $statusCounts[
                    'resolved'
                ] ?>
            </strong>

        </div>

    </div>


    <section class="admin-contact-toolbar">

        <form
            action="<?= base_url(
                'admin/contacts'
            ) ?>"
            method="GET"
        >

            <div class="admin-contact-search">

                <label for="contactKeyword">
                    Tìm kiếm
                </label>

                <input
                    id="contactKeyword"
                    type="text"
                    name="keyword"
                    value="<?= e(
                        $filters['keyword']
                    ) ?>"
                    placeholder="Tên, email, chủ đề, nội dung..."
                >

            </div>


            <div class="admin-contact-filter">

                <label for="contactStatus">
                    Trạng thái
                </label>

                <select
                    id="contactStatus"
                    name="status"
                >

                    <option value="">
                        Tất cả
                    </option>

                    <option
                        value="pending"
                        <?= $filters['status']
                            === 'pending'
                                ? 'selected'
                                : '' ?>
                    >
                        Chờ xử lý
                    </option>

                    <option
                        value="processing"
                        <?= $filters['status']
                            === 'processing'
                                ? 'selected'
                                : '' ?>
                    >
                        Đang xử lý
                    </option>

                    <option
                        value="resolved"
                        <?= $filters['status']
                            === 'resolved'
                                ? 'selected'
                                : '' ?>
                    >
                        Đã xử lý
                    </option>

                </select>

            </div>


            <div class="admin-contact-filter">

                <label for="contactSource">
                    Nguồn gửi
                </label>

                <select
                    id="contactSource"
                    name="source"
                >

                    <option value="">
                        Tất cả
                    </option>

                    <option
                        value="user"
                        <?= $filters['source']
                            === 'user'
                                ? 'selected'
                                : '' ?>
                    >
                        Thành viên
                    </option>

                    <option
                        value="guest"
                        <?= $filters['source']
                            === 'guest'
                                ? 'selected'
                                : '' ?>
                    >
                        Khách
                    </option>

                </select>

            </div>


            <button type="submit">
                Lọc
            </button>


            <?php if (
                $filters['keyword'] !== ''
                || $filters['status'] !== ''
                || $filters['source'] !== ''
            ): ?>

                <a
                    class="admin-contact-reset"
                    href="<?= base_url(
                        'admin/contacts'
                    ) ?>"
                >
                    Đặt lại
                </a>

            <?php endif; ?>

        </form>

    </section>


    <section class="admin-contact-list-card">

        <div class="admin-contact-list-heading">

            <div>

                <span>
                    Danh sách liên hệ
                </span>

                <strong>
                    <?= (int)
                    $totalContacts ?>
                    yêu cầu
                </strong>

            </div>

        </div>


        <?php if (!empty($contacts)): ?>

            <div class="admin-contact-table-wrapper">

                <table class="admin-contact-table">

                    <thead>

                        <tr>
                            <th>ID</th>
                            <th>Người gửi</th>
                            <th>Chủ đề</th>
                            <th>Nội dung</th>
                            <th>Nguồn</th>
                            <th>Trạng thái</th>
                            <th>Ngày gửi</th>
                            <th>Thao tác</th>
                        </tr>

                    </thead>


                    <tbody>

                        <?php foreach (
                            $contacts as $contact
                        ): ?>

                            <tr>

                                <td class="contact-id">
                                    #<?= (int)
                                    $contact[
                                        'contact_id'
                                    ] ?>
                                </td>


                                <td>

                                    <div class="contact-sender">

                                        <strong>
                                            <?= e(
                                                $contact[
                                                    'full_name'
                                                ]
                                            ) ?>
                                        </strong>

                                        <span>
                                            <?= e(
                                                $contact[
                                                    'email'
                                                ]
                                            ) ?>
                                        </span>

                                        <?php if (
                                            !empty(
                                                $contact[
                                                    'phone'
                                                ]
                                            )
                                        ): ?>

                                            <small>
                                                <?= e(
                                                    $contact[
                                                        'phone'
                                                    ]
                                                ) ?>
                                            </small>

                                        <?php endif; ?>

                                    </div>

                                </td>


                                <td>

                                    <strong class="contact-subject">

                                        <?= !empty(
                                            $contact[
                                                'subject'
                                            ]
                                        )
                                            ? e(
                                                $contact[
                                                    'subject'
                                                ]
                                            )
                                            : 'Không có chủ đề' ?>

                                    </strong>

                                </td>


                                <td>

                                    <p class="contact-message-preview">

                                        <?= e(
                                            mb_strlen(
                                                $contact[
                                                    'message'
                                                ]
                                            ) > 90
                                                ? mb_substr(
                                                    $contact[
                                                        'message'
                                                    ],
                                                    0,
                                                    90
                                                )
                                                    . '...'
                                                : $contact[
                                                    'message'
                                                ]
                                        ) ?>

                                    </p>

                                </td>


                                <td>

                                    <?php if (
                                        $contact[
                                            'user_id'
                                        ] !== null
                                    ): ?>

                                        <span class="contact-source user">
                                            Thành viên
                                        </span>

                                    <?php else: ?>

                                        <span class="contact-source guest">
                                            Khách
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <?php if (
                                        $contact[
                                            'status'
                                        ] === 'pending'
                                    ): ?>

                                        <span class="contact-status pending">
                                            Chờ xử lý
                                        </span>

                                    <?php elseif (
                                        $contact[
                                            'status'
                                        ] === 'processing'
                                    ): ?>

                                        <span class="contact-status processing">
                                            Đang xử lý
                                        </span>

                                    <?php else: ?>

                                        <span class="contact-status resolved">
                                            Đã xử lý
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <span class="contact-date">

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

                                    </span>

                                </td>


                                <td>

                                    <div class="admin-contact-actions">

                                        <a class="contact-action view" href="<?= base_url( 'admin/contacts/' . $contact[ 'contact_id' ] ) ?>" >
                                            Xem
                                        </a>

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
        class="contact-action delete"
        type="submit"
    >
        Xóa
    </button>

</form>

                                    </div>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>


            <?php if (
                $totalPages > 1
            ): ?>

                <nav class="admin-contact-pagination">

                    <?php

                    $query = [
                        'keyword' =>
                            $filters['keyword'],

                        'status' =>
                            $filters['status'],

                        'source' =>
                            $filters['source']
                    ];

                    ?>


                    <?php if (
                        $currentPage > 1
                    ): ?>

                        <?php

                        $query['page'] =
                            $currentPage - 1;

                        ?>

                        <a
                            href="<?= base_url(
                                'admin/contacts?'
                                . http_build_query(
                                    $query
                                )
                            ) ?>"
                        >
                            ←
                        </a>

                    <?php endif; ?>


                    <?php for (
                        $page = 1;
                        $page <= $totalPages;
                        $page++
                    ): ?>

                        <?php

                        $query['page'] =
                            $page;

                        ?>

                        <a
                            class="<?= $page
                                === $currentPage
                                    ? 'active'
                                    : '' ?>"
                            href="<?= base_url(
                                'admin/contacts?'
                                . http_build_query(
                                    $query
                                )
                            ) ?>"
                        >
                            <?= $page ?>
                        </a>

                    <?php endfor; ?>


                    <?php if (
                        $currentPage
                        < $totalPages
                    ): ?>

                        <?php

                        $query['page'] =
                            $currentPage + 1;

                        ?>

                        <a
                            href="<?= base_url(
                                'admin/contacts?'
                                . http_build_query(
                                    $query
                                )
                            ) ?>"
                        >
                            →
                        </a>

                    <?php endif; ?>

                </nav>

            <?php endif; ?>

        <?php else: ?>

            <div class="admin-contact-empty">

                <div>
                    MSG
                </div>

                <h3>
                    Không tìm thấy liên hệ
                </h3>

                <p>
                    Hãy thử thay đổi từ khóa
                    hoặc bộ lọc.
                </p>

            </div>

        <?php endif; ?>

    </section>

</section>