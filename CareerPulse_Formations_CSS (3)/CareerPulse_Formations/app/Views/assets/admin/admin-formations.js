document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('admin-course-search');
    const table = document.getElementById('admin-courses-table');

    if (searchInput && table) {
        const rows = Array.from(table.querySelectorAll('tbody tr'));
        searchInput.addEventListener('input', (event) => {
            const term = event.target.value.trim().toLowerCase();
            rows.forEach((row) => {
                const match = row.textContent.toLowerCase().includes(term);
                row.style.display = match ? '' : 'none';
            });
        });
    }

    document.querySelectorAll('.js-confirm-delete').forEach((form) => {
        form.addEventListener('submit', function (event) {
            const label = this.dataset.label || 'cet élément';
            if (!window.confirm(`Confirmer la suppression de « ${label} » ?`)) {
                event.preventDefault();
            }
        });
    });

    document.querySelectorAll('form[data-validate]').forEach((form) => {
        const password = form.querySelector('[name="password"]');
        const confirmation = form.querySelector('[name="password_confirm"]');
        const validateConfirmation = () => {
            if (!password || !confirmation) return;
            confirmation.setCustomValidity(
                confirmation.value !== '' && password.value !== confirmation.value
                    ? 'Les deux mots de passe ne correspondent pas.'
                    : ''
            );
        };
        if (password && confirmation) {
            password.addEventListener('input', validateConfirmation);
            confirmation.addEventListener('input', validateConfirmation);
        }
        form.addEventListener('submit', (event) => {
            validateConfirmation();
            if (!form.checkValidity()) {
                event.preventDefault();
                form.reportValidity();
            }
        });
    });
});
