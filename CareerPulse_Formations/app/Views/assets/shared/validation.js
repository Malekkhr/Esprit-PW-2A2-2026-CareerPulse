/**
 * CareerPulse : validation JavaScript déclarative des formulaires (frontoffice + backoffice).
 *
 * Utilisation : <form data-cp-validate novalidate> + sur chaque champ :
 *   data-v="required username email password match pattern max"   règles à appliquer (séparées par des espaces)
 *   data-max="160"                    longueur maximale (règle « max »)
 *   data-match="#password"            champ à égaler (règle « match »)
 *   data-pattern="..." data-pattern-message="..."   expression régulière (règle « pattern »)
 *   data-required-if="#a,#b"          champ obligatoire dès qu'un des champs listés est rempli
 *   data-label="Nom"                  libellé utilisé dans les messages (sinon texte du <label>)
 *
 * Ce script améliore la saisie (retour immédiat) mais ne remplace JAMAIS la validation PHP :
 * les mêmes règles sont revérifiées côté serveur.
 */
(() => {
    'use strict';

    const USERNAME_REGEX = /^[a-zA-Z0-9_-]{3,50}$/;       // même regex que le PHP
    const EMAIL_REGEX = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
    const PASSWORD_MIN = 8;
    const PASSWORD_MAX = 72;                               // limite de bcrypt

    const labelOf = (input) => {
        if (input.dataset.label) return input.dataset.label;
        const label = input.id ? document.querySelector(`label[for="${input.id}"]`) : null;
        const text = label ? label.textContent.replace(/\*/g, '').trim() : '';
        return text || 'Ce champ';
    };

    const rules = {
        required: (value, input) => (value.trim() === '' ? `${labelOf(input)} est obligatoire.` : ''),

        username: (value) => (value.trim() === '' || USERNAME_REGEX.test(value.trim())
            ? '' : 'Nom d\u2019utilisateur : 3 à 50 caractères (lettres, chiffres, _ ou -).'),

        email: (value) => (value.trim() === '' || EMAIL_REGEX.test(value.trim())
            ? '' : 'Adresse e-mail invalide (ex : nom@domaine.com).'),

        max: (value, input) => {
            const limit = Number(input.dataset.max || 0);
            return limit > 0 && value.trim().length > limit
                ? `${labelOf(input)} : ${limit} caractères maximum.` : '';
        },

        password: (value) => {
            if (value === '') return '';
            if (value.length < PASSWORD_MIN) return `Le mot de passe doit contenir au moins ${PASSWORD_MIN} caractères.`;
            if (value.length > PASSWORD_MAX) return `Le mot de passe ne doit pas dépasser ${PASSWORD_MAX} caractères.`;
            if (!/[A-Za-zÀ-ÿ]/.test(value)) return 'Le mot de passe doit contenir au moins une lettre.';
            if (!/\d/.test(value)) return 'Le mot de passe doit contenir au moins un chiffre.';
            return '';
        },

        match: (value, input, form) => {
            const other = form.querySelector(input.dataset.match || '');
            return other && value !== other.value ? 'Les deux mots de passe ne correspondent pas.' : '';
        },

        pattern: (value, input) => {
            if (value.trim() === '') return '';
            try {
                return new RegExp(input.dataset.pattern || '', 'iu').test(value.trim())
                    ? '' : (input.dataset.patternMessage || 'Format invalide.');
            } catch (error) {
                return '';
            }
        },
    };

    const dependencies = (input) => (input.dataset.requiredIf || '')
        .split(',').map((s) => s.trim()).filter(Boolean);

    /** Retourne le message d'erreur d'un champ ('' = valide). */
    const check = (input, form) => {
        const value = input.value;

        const deps = dependencies(input);
        if (deps.length > 0 && value.trim() === ''
            && deps.some((sel) => (form.querySelector(sel)?.value || '') !== '')) {
            return `${labelOf(input)} est obligatoire.`;
        }

        for (const name of (input.dataset.v || '').split(/\s+/).filter(Boolean)) {
            if (typeof rules[name] !== 'function') continue;
            const message = rules[name](value, input, form);
            if (message !== '') return message;
        }
        return '';
    };

    const errorBox = (input, form) => {
        let box = input.id ? form.querySelector(`[data-error-for="${input.id}"]`) : null;
        if (!box) {
            box = document.createElement('span');
            box.className = 'cp-error';
            box.setAttribute('aria-live', 'polite');
            if (input.id) box.dataset.errorFor = input.id;
            (input.parentElement || form).appendChild(box);
        }
        return box;
    };

    const show = (input, form, message) => {
        errorBox(input, form).textContent = message;
        input.classList.toggle('is-invalid', message !== '');
        input.setAttribute('aria-invalid', String(message !== ''));
        return message === '';
    };

    const fields = (form) => Array.from(form.querySelectorAll('[data-v], [data-required-if]'));

    const initForm = (form) => {
        const validateField = (input) => show(input, form, check(input, form));

        fields(form).forEach((input) => {
            input.addEventListener('blur', () => validateField(input));
            input.addEventListener('input', () => {
                // Pas de message agressif pendant la frappe : on ne corrige en direct qu'un champ déjà signalé.
                if (input.classList.contains('is-invalid')) validateField(input);

                // Champs liés (confirmation du mot de passe, « obligatoire si... ») revalidés dès que celui-ci change.
                if (!input.id) return;
                fields(form).forEach((other) => {
                    if (other === input) return;
                    const linked = other.dataset.match === `#${input.id}`
                        || dependencies(other).includes(`#${input.id}`);
                    if (linked && (other.classList.contains('is-invalid') || other.value !== '')) validateField(other);
                });
            });
            input.addEventListener('change', () => validateField(input));
        });

        form.addEventListener('submit', (event) => {
            const results = fields(form).map(validateField);
            if (results.includes(false)) {
                event.preventDefault();
                const firstInvalid = form.querySelector('.is-invalid');
                if (firstInvalid) firstInvalid.focus();
            }
        });
    };

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('form[data-cp-validate]').forEach(initForm);
    });
})();
