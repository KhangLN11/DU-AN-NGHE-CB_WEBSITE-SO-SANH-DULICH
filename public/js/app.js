document.addEventListener('DOMContentLoaded', function () {
    const menuButton = document.getElementById('mobileMenuButton');
    const navigation = document.getElementById('mainNavigation');

    if (!menuButton || !navigation) {
        return;
    }

    menuButton.addEventListener('click', function () {
        const isOpen = navigation.classList.toggle('open');

        menuButton.setAttribute(
            'aria-expanded',
            isOpen ? 'true' : 'false'
        );
    });

    navigation.querySelectorAll('.nav-link').forEach(function (link) {
        link.addEventListener('click', function () {
            navigation.classList.remove('open');
            menuButton.setAttribute('aria-expanded', 'false');
        });
    });

    document.addEventListener('click', function (event) {
        if (
            navigation.contains(event.target) ||
            menuButton.contains(event.target)
        ) {
            return;
        }

        navigation.classList.remove('open');
        menuButton.setAttribute('aria-expanded', 'false');
    });
});