document.addEventListener('DOMContentLoaded', function () {
    initHeroSearch();
    initHomeReveal();
});

function initHeroSearch() {
    const searchForm =
        document.querySelector('.hero-search');

    const searchInput =
        searchForm?.querySelector(
            'input[name="keyword"]'
        );

    if (!searchForm || !searchInput) {
        return;
    }

    searchForm.addEventListener(
        'submit',
        function (event) {
            const keyword =
                searchInput.value.trim();

            if (keyword === '') {
                event.preventDefault();
                searchInput.focus();
            }
        }
    );
}

function initHomeReveal() {
    const elements =
        document.querySelectorAll(
            '.home-reveal'
        );

    if (elements.length === 0) {
        return;
    }

    if (
        !('IntersectionObserver' in window)
    ) {
        elements.forEach(
            function (element) {
                element.classList.add(
                    'visible'
                );
            }
        );

        return;
    }

    const observer =
        new IntersectionObserver(
            function (entries) {
                entries.forEach(
                    function (entry) {
                        if (
                            !entry.isIntersecting
                        ) {
                            return;
                        }

                        entry.target
                            .classList
                            .add('visible');

                        observer.unobserve(
                            entry.target
                        );
                    }
                );
            },
            {
                threshold: 0.12
            }
        );

    elements.forEach(
        function (element) {
            observer.observe(element);
        }
    );
}