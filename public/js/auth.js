document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll(
        '.password-toggle'
    );

    buttons.forEach(function (button) {
        button.addEventListener('click', function () {
            const targetId =
                button.dataset.passwordTarget;

            const input =
                document.getElementById(targetId);

            if (!input) {
                return;
            }

            const isPassword =
                input.type === 'password';

            input.type =
                isPassword
                    ? 'text'
                    : 'password';

            button.textContent =
                isPassword
                    ? 'Ẩn'
                    : 'Hiện';
        });
    });
});