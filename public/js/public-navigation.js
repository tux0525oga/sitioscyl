document.addEventListener('DOMContentLoaded', () => {
    const button = document.querySelector(
        '.site-menu-toggle'
    );

    const navigation = document.querySelector(
        '#siteNavigation'
    );

    if (!button || !navigation) {
        return;
    }

    button.addEventListener('click', () => {
        const isOpen = navigation.classList.toggle(
            'is-open'
        );

        button.classList.toggle(
            'is-open',
            isOpen
        );

        button.setAttribute(
            'aria-expanded',
            isOpen ? 'true' : 'false'
        );
    });

    navigation
        .querySelectorAll('a')
        .forEach((link) => {
            link.addEventListener('click', () => {
                navigation.classList.remove(
                    'is-open'
                );

                button.classList.remove(
                    'is-open'
                );

                button.setAttribute(
                    'aria-expanded',
                    'false'
                );
            });
        });
});