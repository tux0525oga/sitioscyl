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



document.addEventListener('DOMContentLoaded', () => {
    const galleryTriggers = Array.from(
        document.querySelectorAll(
            '.public-gallery-trigger'
        )
    );

    if (galleryTriggers.length === 0) {
        return;
    }

    const lightbox = document.createElement('div');

    lightbox.className = 'public-lightbox';
    lightbox.setAttribute('role', 'dialog');
    lightbox.setAttribute('aria-modal', 'true');
    lightbox.setAttribute('aria-hidden', 'true');
    lightbox.setAttribute(
        'aria-label',
        'Visor de imágenes'
    );

    lightbox.innerHTML = `
        <div class="public-lightbox__stage">
            <button
                class="public-lightbox__close"
                type="button"
                aria-label="Cerrar imagen"
            >×</button>

            <button
                class="
                    public-lightbox__nav
                    public-lightbox__nav--prev
                "
                type="button"
                aria-label="Imagen anterior"
            >‹</button>

            <figure class="public-lightbox__figure">
                <img
                    class="public-lightbox__image"
                    src=""
                    alt=""
                >
                <figcaption
                    class="public-lightbox__caption"
                ></figcaption>
            </figure>

            <button
                class="
                    public-lightbox__nav
                    public-lightbox__nav--next
                "
                type="button"
                aria-label="Imagen siguiente"
            >›</button>
        </div>
    `;

    document.body.appendChild(lightbox);

    const image = lightbox.querySelector(
        '.public-lightbox__image'
    );

    const caption = lightbox.querySelector(
        '.public-lightbox__caption'
    );

    const closeButton = lightbox.querySelector(
        '.public-lightbox__close'
    );

    const previousButton = lightbox.querySelector(
        '.public-lightbox__nav--prev'
    );

    const nextButton = lightbox.querySelector(
        '.public-lightbox__nav--next'
    );

    let currentIndex = 0;
    let previousFocus = null;

    const renderImage = () => {
        const trigger = galleryTriggers[currentIndex];

        image.src =
            trigger.dataset.gallerySrc || '';

        image.alt =
            trigger.dataset.galleryAlt || '';

        caption.textContent =
            trigger.dataset.galleryCaption || '';

        const hasMultiple =
            galleryTriggers.length > 1;

        previousButton.hidden = !hasMultiple;
        nextButton.hidden = !hasMultiple;
    };

    const openLightbox = (index) => {
        currentIndex = index;
        previousFocus = document.activeElement;

        renderImage();

        lightbox.classList.add('is-open');
        lightbox.setAttribute(
            'aria-hidden',
            'false'
        );

        document.body.classList.add(
            'public-lightbox-open'
        );

        closeButton.focus();
    };

    const closeLightbox = () => {
        lightbox.classList.remove('is-open');
        lightbox.setAttribute(
            'aria-hidden',
            'true'
        );

        document.body.classList.remove(
            'public-lightbox-open'
        );

        image.src = '';

        if (
            previousFocus
            && typeof previousFocus.focus === 'function'
        ) {
            previousFocus.focus();
        }
    };

    const showPrevious = () => {
        currentIndex =
            (
                currentIndex
                - 1
                + galleryTriggers.length
            )
            % galleryTriggers.length;

        renderImage();
    };

    const showNext = () => {
        currentIndex =
            (
                currentIndex
                + 1
            )
            % galleryTriggers.length;

        renderImage();
    };

    galleryTriggers.forEach(
        (trigger, index) => {
            trigger.addEventListener(
                'click',
                () => openLightbox(index)
            );
        }
    );

    closeButton.addEventListener(
        'click',
        closeLightbox
    );

    previousButton.addEventListener(
        'click',
        showPrevious
    );

    nextButton.addEventListener(
        'click',
        showNext
    );

    lightbox.addEventListener(
        'click',
        (event) => {
            if (event.target === lightbox) {
                closeLightbox();
            }
        }
    );

    document.addEventListener(
        'keydown',
        (event) => {
            if (
                !lightbox.classList.contains(
                    'is-open'
                )
            ) {
                return;
            }

            if (event.key === 'Escape') {
                closeLightbox();
            } else if (event.key === 'ArrowLeft') {
                showPrevious();
            } else if (event.key === 'ArrowRight') {
                showNext();
            }
        }
    );
});
