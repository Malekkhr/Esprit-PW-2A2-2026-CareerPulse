document.addEventListener('DOMContentLoaded', () => {
    const spinner = document.getElementById('cp-spinner');
    if (spinner) {
        window.setTimeout(() => spinner.classList.add('is-hidden'), 220);
    }

    const toggle = document.querySelector('[data-cp-toggle="nav"]');
    const menu = document.getElementById('navbarCollapse');
    if (toggle && menu) {
        toggle.addEventListener('click', () => {
            const expanded = toggle.getAttribute('aria-expanded') === 'true';
            toggle.setAttribute('aria-expanded', String(!expanded));
            menu.classList.toggle('show', !expanded);
        });
    }
});
