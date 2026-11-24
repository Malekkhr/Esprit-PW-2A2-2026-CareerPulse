document.addEventListener('DOMContentLoaded', () => {
    // Validation côté navigateur des formulaires qui portent data-cp-validate.
    // Le contrôleur PHP répète les mêmes contrôles : le JavaScript ne remplace
    // jamais la validation serveur, car il peut être désactivé ou contourné.
    document.querySelectorAll('form[data-cp-validate]').forEach((form) => {
        const fields = Array.from(form.querySelectorAll('input, textarea, select'))
            .filter((field) => field.name && field.type !== 'hidden' && field.type !== 'submit');

        const validateField = (field) => {
            const rules = (field.dataset.v || '').split(/\s+/).filter(Boolean);
            const value = field.value.trim();
            const conditionalSelectors = (field.dataset.requiredIf || '')
                .split(',').map((selector) => selector.trim()).filter(Boolean);
            const conditionIsActive = conditionalSelectors.some((selector) => {
                const other = form.querySelector(selector);
                return other && other.value !== '';
            });
            const isRequired = field.required || rules.includes('required') || conditionIsActive;
            let message = '';

            if (isRequired && value === '') {
                message = `${field.dataset.label || 'Ce champ'} est obligatoire.`;
            } else if (value !== '' && rules.includes('username') && !/^[a-zA-Z0-9_-]{3,50}$/.test(value)) {
                message = 'Le nom doit contenir 3 à 50 caractères (lettres, chiffres, _ ou -).';
            } else if (value !== '' && (rules.includes('email') || field.type === 'email')
                && !/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(value)) {
                message = 'Saisissez une adresse e-mail valide.';
            } else if (value !== '' && rules.includes('max') && value.length > Number(field.dataset.max || field.maxLength)) {
                message = `${field.dataset.label || 'Ce champ'} est trop long (maximum ${field.dataset.max || field.maxLength} caractères).`;
            } else if (value !== '' && rules.includes('password')
                && (value.length < 8 || value.length > 72 || !/[A-Za-z]/.test(value) || !/\d/.test(value))) {
                message = 'Le mot de passe doit contenir 8 à 72 caractères, une lettre et un chiffre.';
            } else if (value !== '' && rules.includes('match')) {
                const other = form.querySelector(field.dataset.match || '');
                if (other && value !== other.value) message = 'Les deux mots de passe ne correspondent pas.';
            }

            field.setCustomValidity(message);
            field.setAttribute('aria-invalid', String(message !== ''));
            return message === '';
        };

        fields.forEach((field) => {
            field.addEventListener('input', () => {
                validateField(field);
                // Réévalue les champs dépendants (ex. les trois champs mot de passe).
                fields.filter((candidate) => candidate.dataset.requiredIf || candidate.dataset.match)
                    .forEach(validateField);
            });
            field.addEventListener('blur', () => validateField(field));
        });

        form.addEventListener('submit', (event) => {
            const valid = fields.map(validateField).every(Boolean);
            if (!valid) {
                event.preventDefault();
                form.reportValidity();
            }
        });
    });

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
            menu.classList.toggle('is-open', !expanded);
        });
    }

    const searchInput = document.getElementById('course-search');
    const categorySelect = document.getElementById('course-category-filter');
    const levelSelect = document.getElementById('course-level-filter');
    const pillsContainer = document.getElementById('category-pills');
    const cards = Array.from(document.querySelectorAll('.cp-course-wrapper'));
    const counter = document.getElementById('courses-counter');
    const emptyMsg = document.getElementById('no-courses-msg');
    const resetBtn = document.getElementById('reset-filters-btn');

    if (cards.length > 0 && searchInput && categorySelect && levelSelect) {
        const updatePills = (selectedCategory) => {
            if (!pillsContainer) return;
            pillsContainer.querySelectorAll('button').forEach((btn) => {
                const active = btn.dataset.cat === selectedCategory;
                btn.classList.toggle('is-active', active);
            });
        };

        const filterCourses = () => {
            const query = searchInput.value.trim().toLowerCase();
            const cat = categorySelect.value.trim().toLowerCase();
            const lvl = levelSelect.value.trim().toLowerCase();

            const visibleCards = cards.filter((card) => {
                const matchTitle = query === '' || (card.dataset.title || '').includes(query);
                const matchCat = cat === '' || (card.dataset.category || '') === cat;
                const matchLvl = lvl === '' || (card.dataset.level || '') === lvl;
                const isVisible = matchTitle && matchCat && matchLvl;
                card.classList.toggle('cp-hidden', !isVisible);
                return isVisible;
            });

            if (counter) {
                counter.textContent = `${visibleCards.length} formation(s) affichée(s)`;
            }
            if (emptyMsg) {
                emptyMsg.classList.toggle('cp-hidden', visibleCards.length > 0);
            }
            updatePills(cat);
        };

        searchInput.addEventListener('input', filterCourses);
        categorySelect.addEventListener('change', filterCourses);
        levelSelect.addEventListener('change', filterCourses);

        if (pillsContainer) {
            pillsContainer.addEventListener('click', (event) => {
                const btn = event.target.closest('button[data-cat]');
                if (!btn) return;
                categorySelect.value = btn.dataset.cat;
                filterCourses();
            });
        }

        if (resetBtn) {
            resetBtn.addEventListener('click', () => {
                searchInput.value = '';
                categorySelect.value = '';
                levelSelect.value = '';
                filterCourses();
            });
        }
    }

    // ---------------------------------------------------------------
    // Aide à la saisie du formulaire d'inscription (JavaScript)
    // Les MÊMES règles sont vérifiées côté serveur (AuthController::register) :
    // le JavaScript améliore l'expérience mais ne remplace jamais la validation PHP.
    // ---------------------------------------------------------------
    const registerForm = document.getElementById('register-form');
    if (registerForm) {
        const MIN_PASSWORD = 8;                                   // longueur minimale
        const MAX_PASSWORD = 72;                                  // limite de bcrypt
        const USERNAME_REGEX = /^[a-zA-Z0-9_-]{3,50}$/;           // même regex que le PHP
        const EMAIL_REGEX = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;        // format e-mail

        const field = (id) => registerForm.querySelector('#' + id);
        const errorBox = (id) => registerForm.querySelector(`[data-error-for="${id}"]`);

        const setError = (id, message) => {
            const input = field(id);
            const box = errorBox(id);
            if (box) box.textContent = message;
            if (input) {
                input.classList.toggle('is-invalid', message !== '');
                input.setAttribute('aria-invalid', String(message !== ''));
            }
            return message === '';
        };

        const rules = {
            length: (v) => v.length >= MIN_PASSWORD,
            letter: (v) => /[A-Za-zÀ-ÿ]/.test(v),
            digit: (v) => /\d/.test(v),
        };

        const updateRules = () => {
            const value = field('password').value;
            registerForm.querySelectorAll('#password-rules [data-rule]').forEach((li) => {
                li.classList.toggle('is-ok', rules[li.dataset.rule](value));
            });
        };

        const validators = {
            username() {
                const v = field('username').value.trim();
                if (v === '') return setError('username', "Le nom d'utilisateur est obligatoire.");
                if (!USERNAME_REGEX.test(v)) return setError('username', 'Nom d\'utilisateur : 3 à 50 caractères (lettres, chiffres, _ ou -).');
                return setError('username', '');
            },
            email() {
                const v = field('email').value.trim();
                if (v === '') return setError('email', "L'adresse e-mail est obligatoire.");
                if (!EMAIL_REGEX.test(v)) return setError('email', 'Adresse e-mail invalide (ex : nom@domaine.com).');
                return setError('email', '');
            },
            study() {
                if (field('study').value.trim().length > 160) return setError('study', '160 caractères maximum.');
                return setError('study', '');
            },
            password() {
                const v = field('password').value;
                updateRules();
                if (v === '') return setError('password', 'Le mot de passe est obligatoire.');
                if (!rules.length(v)) return setError('password', `Le mot de passe doit contenir au moins ${MIN_PASSWORD} caractères.`);
                if (v.length > MAX_PASSWORD) return setError('password', `Le mot de passe ne doit pas dépasser ${MAX_PASSWORD} caractères.`);
                if (!rules.letter(v)) return setError('password', 'Le mot de passe doit contenir au moins une lettre.');
                if (!rules.digit(v)) return setError('password', 'Le mot de passe doit contenir au moins un chiffre.');
                return setError('password', '');
            },
            password_confirm() {
                const v = field('password_confirm').value;
                if (v === '') return setError('password_confirm', 'Veuillez confirmer le mot de passe.');
                if (v !== field('password').value) return setError('password_confirm', 'Les deux mots de passe ne correspondent pas.');
                return setError('password_confirm', '');
            },
        };

        // Validation en direct : à la saisie, et à la sortie du champ
        Object.keys(validators).forEach((id) => {
            const input = field(id);
            input.addEventListener('blur', validators[id]);
            input.addEventListener('input', () => {
                if (input.classList.contains('is-invalid') || id === 'password') validators[id]();
                if (id === 'password' && field('password_confirm').value !== '') validators.password_confirm();
            });
        });
        updateRules();

        // Soumission : on bloque l'envoi tant qu'une règle n'est pas respectée
        registerForm.addEventListener('submit', (event) => {
            const results = Object.keys(validators).map((id) => validators[id]());
            if (results.includes(false)) {
                event.preventDefault();
                const firstInvalid = registerForm.querySelector('.is-invalid');
                if (firstInvalid) firstInvalid.focus();
            }
        });
    }
});
