document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('#publicQuoteForm');

    if (!form) {
        return;
    }

    const steps = Array.from(
        form.querySelectorAll('[data-quote-step]')
    );

    const indicators = Array.from(
        document.querySelectorAll(
            '[data-step-indicator]'
        )
    );

    const serviceInputs = Array.from(
        form.querySelectorAll(
            'input[name="serviceIds[]"]'
        )
    );

    function showStep(stepNumber) {
        steps.forEach((step) => {
            const active =
                Number(step.dataset.quoteStep)
                === stepNumber;

            step.classList.toggle(
                'is-active',
                active
            );
        });

        indicators.forEach((indicator) => {
            const indicatorStep = Number(
                indicator.dataset.stepIndicator
            );

            indicator.classList.toggle(
                'is-active',
                indicatorStep === stepNumber
            );

            indicator.classList.toggle(
                'is-complete',
                indicatorStep < stepNumber
            );
        });

        window.scrollTo({
            top: Math.max(
                form.offsetTop - 120,
                0
            ),
            behavior: 'smooth',
        });
    }

    function validateStep(stepNumber) {
        const currentStep = steps.find(
            (step) =>
                Number(step.dataset.quoteStep)
                === stepNumber
        );

        if (!currentStep) {
            return true;
        }

        if (stepNumber === 1) {
            const hasService = serviceInputs.some(
                (input) => input.checked
            );

            if (!hasService) {
                alert(
                    'Selecciona al menos un servicio para continuar.'
                );

                return false;
            }
        }

        if (stepNumber === 3) {
            const contactNames = [
                'whatsAppNumber',
                'phoneNumber',
                'email',
            ];

            const hasContactChannel =
                contactNames.some((name) => {
                    const control = form.querySelector(
                        `[name="${name}"]`
                    );

                    return (
                        control
                        && control.value.trim() !== ''
                    );
                });

            if (!hasContactChannel) {
                alert(
                    'Escribe al menos WhatsApp, teléfono o correo electrónico.'
                );

                return false;
            }
        }

        const controls = Array.from(
            currentStep.querySelectorAll(
                'input, textarea, select'
            )
        ).filter(
            (control) =>
                control.type !== 'hidden'
                && control.offsetParent !== null
        );

        for (const control of controls) {
            if (!control.checkValidity()) {
                control.reportValidity();

                return false;
            }
        }

        return true;
    }

    form.addEventListener('click', (event) => {
        const nextButton = event.target.closest(
            '[data-next-step]'
        );

        if (nextButton) {
            const currentStep = Number(
                nextButton
                    .closest('[data-quote-step]')
                    .dataset.quoteStep
            );

            if (!validateStep(currentStep)) {
                return;
            }

            showStep(
                Number(
                    nextButton.dataset.nextStep
                )
            );

            return;
        }

        const previousButton =
            event.target.closest(
                '[data-prev-step]'
            );

        if (previousButton) {
            showStep(
                Number(
                    previousButton.dataset.prevStep
                )
            );
        }
    });

    form.addEventListener('submit', (event) => {
        for (const stepNumber of [1, 2, 3]) {
            if (!validateStep(stepNumber)) {
                event.preventDefault();
                showStep(stepNumber);

                return;
            }
        }
    });

    const hasServerErrors =
        document.querySelector(
            '.quote-alert--error'
        ) !== null;

    if (hasServerErrors) {
        showStep(1);
    }
});
