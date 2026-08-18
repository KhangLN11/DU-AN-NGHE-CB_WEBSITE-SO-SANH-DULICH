document.addEventListener('DOMContentLoaded', function () {
    const rows =
        document.getElementById('scheduleRows');

    const addButton =
        document.getElementById('addScheduleRow');

    const template =
        document.getElementById('scheduleRowTemplate');

    const count =
        document.getElementById('scheduleCount');

    const empty =
        document.getElementById('scheduleEmpty');

    if (
        !rows
        || !addButton
        || !template
    ) {
        return;
    }

    function refreshRows() {
        const items =
            rows.querySelectorAll(
                '.admin-schedule-row'
            );

        items.forEach(function (item, index) {
            const number =
                index + 1;

            const label =
                item.querySelector(
                    '.admin-schedule-day strong'
                );

            const input =
                item.querySelector(
                    '.schedule-day-input'
                );

            if (label) {
                label.textContent =
                    String(number);
            }

            if (input) {
                input.value =
                    String(number);
            }
        });

        if (count) {
            count.textContent =
                items.length
                + ' ngày';
        }

        if (empty) {
            empty.hidden =
                items.length > 0;
        }
    }

    function attachRemove(row) {
        const button =
            row.querySelector(
                '.admin-remove-schedule'
            );

        if (!button) {
            return;
        }

        button.addEventListener(
            'click',
            function () {
                row.remove();
                refreshRows();
            }
        );
    }

    rows.querySelectorAll(
        '.admin-schedule-row'
    ).forEach(function (row) {
        attachRemove(row);
    });

    addButton.addEventListener(
        'click',
        function () {
            const fragment =
                template.content.cloneNode(true);

            const row =
                fragment.querySelector(
                    '.admin-schedule-row'
                );

            if (!row) {
                return;
            }

            rows.appendChild(fragment);

            const insertedRows =
                rows.querySelectorAll(
                    '.admin-schedule-row'
                );

            const inserted =
                insertedRows[
                    insertedRows.length - 1
                ];

            attachRemove(inserted);

            refreshRows();

            const titleInput =
                inserted.querySelector(
                    'input[name="title[]"]'
                );

            if (titleInput) {
                titleInput.focus();
            }
        }
    );

    refreshRows();
});