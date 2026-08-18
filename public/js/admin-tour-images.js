document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('tourImages');
    const previewList = document.getElementById('imagePreviewList');
    const countText = document.getElementById('selectedImageCount');

    if (!input || !previewList || !countText) {
        return;
    }

    input.addEventListener('change', function () {
        previewList.innerHTML = '';

        const files = Array.from(
            input.files || []
        );

        if (files.length === 0) {
            countText.textContent = 'Chưa chọn ảnh';
            return;
        }

        countText.textContent =
            files.length + ' ảnh đã chọn';

        files.forEach(function (file) {
            const item = document.createElement('div');

            item.className =
                'admin-image-preview-item';

            const name = document.createElement('span');

            name.textContent = file.name;

            item.appendChild(name);

            if (file.type.startsWith('image/')) {
                const image =
                    document.createElement('img');

                image.alt = file.name;

                item.insertBefore(
                    image,
                    name
                );

                const reader =
                    new FileReader();

                reader.addEventListener(
                    'load',
                    function () {
                        image.src =
                            reader.result;
                    }
                );

                reader.readAsDataURL(file);
            }

            previewList.appendChild(item);
        });
    });
});