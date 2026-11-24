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
});
