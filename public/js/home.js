document.addEventListener('DOMContentLoaded', function () {
    const searchForm = document.querySelector('.hero-search');
    const searchInput = searchForm?.querySelector('input[name="keyword"]');

    if (!searchForm || !searchInput) {
        return;
    }

    searchForm.addEventListener('submit', function (event) {
        const keyword = searchInput.value.trim();

        if (keyword === '') {
            event.preventDefault();
            searchInput.focus();
        }
    });
});