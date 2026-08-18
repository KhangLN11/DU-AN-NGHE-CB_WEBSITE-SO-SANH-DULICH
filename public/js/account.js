document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('avatar');
    const preview = document.getElementById('avatarPreview');
    const placeholder = document.getElementById('avatarPlaceholder');
    const fileName = document.getElementById('avatarFileName');

    if (!input || !preview || !fileName) {
        return;
    }

    input.addEventListener('change', function () {
        const file = input.files[0];

        if (!file) {
            return;
        }

        fileName.textContent = file.name;

        if (!file.type.startsWith('image/')) {
            return;
        }

        const reader = new FileReader();

        reader.addEventListener('load', function () {
            preview.src = reader.result;
            preview.hidden = false;

            if (placeholder) {
                placeholder.style.display = 'none';
            }
        });

        reader.readAsDataURL(file);
    });
});