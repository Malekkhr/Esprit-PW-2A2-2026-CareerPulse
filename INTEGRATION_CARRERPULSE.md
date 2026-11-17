# CareerPulse template integration

This project now contains an integrated PHP MVC demonstration using shared Front Office includes and a shared Back Office layout. No pre-existing CareerPulse source repository was supplied, so the pages and demo controllers were assembled from the project presentation and the course workshop instructions.

## Front Office

The student-facing pages use the eLearning template's Bootstrap 5 stylesheet and visual assets. Reusable pieces live in `app/Views/front/partials/`:

- `head.php`: metadata and shared CSS
- `spinner.php`: loading indicator
- `navbar.php`: shared navigation with a dynamic active link
- `footer.php`: shared footer and template attribution
- `scripts.php`: shared JavaScript entry point

Each page-specific view lives in `app/Views/front/pages/`. `front/layout.php` includes the common parts around the current view. The navbar adapts to mobile widths, and the active link is selected from `$currentPage`.

## Back Office

The admin interface uses the TailAdmin compiled stylesheet and JavaScript bundle in `app/Views/assets/admin/`. Reusable components live in `app/Views/admin/partials/`:

- `head.php` and `preloader.php`
- `sidebar.php`, with the active route highlighted dynamically
- `header.php`
- `footer.php`
- `scripts.php`

`admin/layout.php` assembles these components and receives the page title, current route, and rendered page content from the controller. Admin views are in `app/Views/admin/pages/`.

## CareerPulse routes included

| Route | Purpose |
|---|---|
| `?page=home` | Project landing page and suggested opportunities |
| `?page=offers` | Sample opportunities and session-based apply action |
| `?page=courses` | Sample training catalogue |
| `?page=profile` | Session-based profile editor |
| `?page=applications` | Application status list for the current session |
| `?page=admin/dashboard` | Demo administration summary |
| `?page=admin/offers` | Demo offer moderation |
| `?page=admin/users` | Demo user status toggles |
| `?page=admin/reports` | Demo report resolution |
| `?page=admin/notifications` | Demo notifications |

## Local run

From the project root, run `php -S localhost:8000`, then open `http://localhost:8000`.

## Limits

The prototype is not connected to a database, real authentication, CV storage, push provider, company catalogue, or analytics service. Sample records are fictional, and session changes disappear when the PHP session ends. The admin interface is deliberately accessible for coursework demonstration. It must not be deployed as a production system without authentication, authorization, persistent storage, and the required security review.

## Source assets and attribution

The front-end is adapted from the eLearning preview by HTML Codex, distributed by ThemeWagon under CC BY 4.0. The admin bundle is built from TailAdmin's free dashboard template, under its included MIT license. See [THIRD_PARTY_NOTICES.md](THIRD_PARTY_NOTICES.md).
