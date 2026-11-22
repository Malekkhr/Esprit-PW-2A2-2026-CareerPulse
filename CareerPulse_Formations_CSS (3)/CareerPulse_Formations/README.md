# CareerPulse — Module Gestion des Formations, Catégories & Comptes

Sous-projet autonome extrait de **CareerPulse** conservant :
- La même charte graphique (`#1f3d2b`, `#0f2a1d`, `#d9a531`), le même logo **CareerPulse** et la typographie `Arial, Helvetica, sans-serif`.
- La gestion complète des **Formations** et des **Catégories** (Front-office filtrable en JavaScript ES6+ / DOM Events et Back-office CRUD complet).
- La gestion des **Comptes Utilisateurs** (`Inscription`, `Connexion`, `Mon profil`, `Admin > Utilisateurs`) ainsi que l'inscription des utilisateurs aux formations, reliés à la même base de données MySQL **`formation`**.

## Installation rapide (XAMPP / WAMP)
1. Importer `database/formation.sql` dans **phpMyAdmin** (base `formation`).
   *Base déjà importée avec l'ancienne structure ?* Exécuter `database/migration_workshop.sql` **ou** supprimer la base puis réimporter.
2. Vérifier `config/database.php` (`host: localhost`, `name: formation`, `user: root`, `password: ''`).
3. Lancer `Lancer_CareerPulse.bat` ou ouvrir `http://localhost/CareerPulse_Formations/index.php`.

### Comptes de test (mots de passe hachés en base)
| Rôle | E-mail | Mot de passe | Redirection après connexion |
|---|---|---|---|
| `admin` | `admin@careerpulse.test` | `Admin@1234` | `index.php?action=admin` (**backoffice**) |
| `user` | `etudiant@careerpulse.test` | `User@1234` | `index.php?action=dashboard` (**frontoffice**) |

> ⚠ Changer ces mots de passe avant toute mise en ligne. Pour promouvoir un compte : `UPDATE users SET role='admin' WHERE email='...'` dans phpMyAdmin.

## Conformité au workshop « Sessions et Sécurité PHP »
Point d'entrée unique `index.php?action=...` (table de routage : `app/routes.php`).

| Exigence du workshop | Où dans le projet |
|---|---|
| **MVC — Modèle** : attributs privés + getters/setters + `fromArray()`, sans SQL | `app/Models/UserModel.php`, `CourseModel.php`, `CategoryModel.php` |
| **MVC — Contrôleur** : toute la logique (SQL préparé, hachage, validation, sessions, CSRF, rôles) | `app/Controllers/AuthController.php` + `ProfileController`, `CourseController`, `Admin/*` ; requêtes partagées : `UserQueries.php`, `CatalogQueries.php` |
| **MVC — Vue** : HTML + `htmlspecialchars()` (helper `e()`), aucun SQL | `app/Views/front/**` (frontoffice), `app/Views/admin/**` (backoffice) |
| Connexion PDO **Singleton** `getConnexion()` (`ERRMODE_EXCEPTION`, `EMULATE_PREPARES=false`) | `app/Database.php` + `config/database.php` |
| Table `users` (`username`, `email`, `password`, `role ENUM('user','admin')`) | `database/formation.sql` |
| Inscription : validation serveur (regex username, `filter_var`, ≥ 8 car.), `password_hash()`, rôle forcé à `user` | `AuthController::register()` |
| Connexion : `password_verify()`, message générique, `session_regenerate_id(true)` | `AuthController::login()` |
| Déconnexion : vidage `$_SESSION`, cookie supprimé, `session_destroy()` | `AuthController::logout()` |
| Session sécurisée : `use_strict_mode`, `cookie_httponly`, `cookie_samesite=Lax`, expiration 30 min | `index.php` (blocs 1 et 2) |
| `isAuthenticated()`, `requireAuth()`, `hasRole()`, `requireRole()` (403) | `AuthController` — `requireRole` relit le rôle **en base** à chaque page admin |
| **Frontoffice** (rôle `user`) : accueil, formations, inscription aux formations, `dashboard`, `profile` | routes `home`, `courses`, `register`, `login`, `dashboard`, `profile` |
| **Backoffice** (rôle `admin` uniquement) : tableau de bord, formations, catégories, utilisateurs | routes `admin`, `admin/courses`, `admin/categories`, `admin/users` (contrôle dans `Admin/AdminController`) |
| Interfaces visuellement distinctes front / back | front : CSS3 Flexbox/Grid ; back : template TailAdmin (barre latérale, étiquette BACKOFFICE) |
| Injections SQL | requêtes préparées partout (aucune concaténation de saisie) |
| XSS | `e()` sur toute sortie + validation des entrées + en-tête CSP |
| CSRF | jeton `csrf_token` par session, champ caché `csrf_input()`, `hash_equals()` |
| En-têtes HTTP | `X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy`, `Content-Security-Policy` (`index.php`) |

La validation JavaScript du formulaire d'inscription (`assets/front/js/app.js`) n'est qu'une **aide à la saisie** : les mêmes règles sont revérifiées côté serveur.

### Tests rapides
- **403 / accès refusé** : connecté en `etudiant`, ouvrir `index.php?action=admin` → page « Accès refusé » ; déconnecté → redirection vers la connexion.
- **Injection SQL** : dans la connexion, remplacer `type="email"` par `type="text"` (F12) et saisir `' OR '1'='1` → « E-mail ou mot de passe incorrect. »
- **XSS** : insérer un compte `<script>alert('test')</script>` via phpMyAdmin (mot de passe `User@1234`) → le nom s'affiche en texte brut.
- **CSRF** : supprimer le champ caché `csrf_token` (F12) puis valider → « Jeton de sécurité invalide ».
- **Expiration** : mettre `$sessionLifetime = 10;` dans `index.php`, attendre 10 s, recharger `?action=dashboard`.

## Architecture CSS du front-office (Chapitre 3 : Flexbox & Grid)
- `careerpulse.css` : CSS3 pur, **sans Bootstrap**, approche **mobile-first** (`min-width: 576 / 768 / 1024 / 1200px`).
- **Flexbox** : navbar, boutons, cartes formations (`flex: 1 0 auto`), pastilles, CTA, centrage des formulaires, footer sticky (`body` en colonne).
- **Grid** : hero `7fr 5fr`, grilles `repeat(auto-fit, minmax(250px, 1fr))`, formulaire profil, page Formations et Footer en `grid-template-areas`.
- `order`, `align-self`, `gap`, `box-sizing: border-box`, meta viewport et media queries conformément au cours.
