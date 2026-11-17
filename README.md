# CareerPulse

**Orientation and early-career opportunities for students**

CareerPulse is a PHP MVC demonstration of the mobile and web concept described by the MindPrint project materials. It brings together a student profile, a catalogue of courses and opportunities, a matching score, application tracking, and an administration back office.

> **Database status:** This V4 workshop project uses MySQL through PDO. Accounts, profiles, offers, courses, applications, reports, and notifications are stored in the local `careerpulse` database. The seed records are fictional. This remains a coursework prototype, not a production service.

## Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Architecture](#architecture)
- [Contributors](#contributors)
- [Academic Context](#academic-context)
- [Getting Started](#getting-started)
- [XAMPP and MySQL setup](GUIDE_XAMPP_SQL.md)
- [Usage](#usage)
- [Contribution](#contribution)
- [Acknowledgments](#acknowledgments)
- [License](#license)

## Overview

Students often use separate services to explore career paths, learn new skills, and find internships or jobs. CareerPulse proposes one connected experience: students maintain a profile, discover relevant learning and career opportunities, and follow applications from one place. Administrators use a back office to review offers, moderate users, handle reports, and consult activity indicators.

The V4 workshop build connects the Front Office and Back Office to MySQL. Actions implemented in the models write to the `careerpulse` database; opening a page does not automatically create an activity log.

## Features

- Browse offers and courses loaded from MySQL
- View opportunity match scores and filter the catalogue
- Create an account, sign in, and save profile changes in MySQL
- Submit and track applications for the signed-in account
- Update offer and user moderation state, process reports, and mark notifications as read through the admin pages
- Start with fictional sample offers, courses, reports, and notifications from the SQL seed file

CV file upload, real push delivery, and third-party recommendation integrations are not implemented.

## Tech Stack

### Frontend

- HTML5, CSS3, vanilla JavaScript
- Bootstrap 5 styles and the eLearning theme assets
- TailAdmin compiled Tailwind CSS and Alpine.js bundle for the administration interface

### Backend

- PHP 8.1 or later, using the supplied MVC structure
- MySQL persistence through PDO (`pdo_mysql` must be enabled)
- PHP sessions hold the signed-in user's ID; account and profile data are stored in MySQL
- No Composer installation is required

## Architecture

```text
index.php
app/
├── bootstrap.php   # session + autoloader
├── helpers.php
├── routes.php      # ?page=... => [Controller, method]
├── Controllers/    # one controller per page (Admin/ for the back office)
├── Models/         # Offer, Application, Course, Report, Notification, User
└── Views/
    ├── front/
    │   ├── layout.php
    │   ├── includes/
    │   └── pages/
    └── admin/
        ├── layout.php
        ├── partials/
        └── pages/
app/Views/assets/
├── front/   # eLearning styles, scripts, and images
└── admin/   # TailAdmin compiled styles, scripts, and images
```

Front Office and Back Office layouts assemble shared partials with `require`. Controllers choose the page view, and the Models read and write the MySQL database. The admin menu marks the active page from the current route.

## GitHub Task Board Draft

See [docs/GITHUB_BOARD_DRAFT.md](docs/GITHUB_BOARD_DRAFT.md) for the task list and proposed progress states to add to the team GitHub Project.

## UML Diagrams

- [Use case diagram preview](docs/diagrams/CAS_UTILISATION.md) (Mermaid) and [PlantUML source](docs/diagrams/CAS_UTILISATION.puml)
- [Class diagram](docs/diagrams/DIAGRAMME_CLASSES.md) (Mermaid preview with proposed modeling assumptions)
- [Diagram notes and pending items](docs/diagrams/README.md)

## Contributors

| Contributor | Module responsibility in the project presentation |
|---|---|
| Mohamed Malek Tanazefti | Authentication and profiles |
| Youssef Salem | Service catalogue |
| Mouhanned Khammar | Matching engine |
| Yacine Bedoui | Applications |
| Malek Khadhraoui | Administration |
| Youssef Naouar | Push notifications |

## Academic Context

Developed at **Esprit School of Engineering – Tunisia** for the Technologies Web 2A project context, Academic Year **2026–2027**. The suggested repository PI label `PW` should be confirmed with the course supervisor before publication.

## Getting Started

### Requirements

- XAMPP with Apache, MySQL, and PHP's `pdo_mysql` extension

### Installation with XAMPP

1. Put the project folder under `C:\xampp\htdocs\CareerPulse` so that `index.php` is at `C:\xampp\htdocs\CareerPulse\index.php`.
2. Start **Apache** and **MySQL** in the XAMPP Control Panel.
3. In phpMyAdmin, import `database/careerpulse.sql` once. The script creates the `careerpulse` database, tables, and fictional starter rows.
4. Check `config/database.php`. The defaults are XAMPP's usual `localhost:3306`, database `careerpulse`, user `root`, and blank password.
5. Open [http://localhost/CareerPulse/](http://localhost/CareerPulse/).

For the current local installation and example SQL queries, follow [GUIDE_XAMPP_SQL.md](GUIDE_XAMPP_SQL.md). It explains how to view the rows in phpMyAdmin and which actions update each table.

A local PHP development server can also run the project, but MySQL must still be running and `config/database.php` must point to it.
## Usage

- Create an account from the registration page, then sign in.
- Browse the seeded offers and courses; profile edits are stored in MySQL.
- Submit an application while signed in, then check the Applications page and the `applications` table in phpMyAdmin.
- Use the admin pages to change offer or user status, process reports, and mark notifications as read. Those actions update their MySQL tables.
- Page visits and arbitrary clicks are not automatically logged. Only the actions implemented in the PHP models write to the database.

The admin routes are intentionally open for coursework demonstration. Do not deploy this prototype publicly as an authenticated application. Sample employers, offers, reports, and notifications are fictional.
## Contribution

1. Open an issue with the change or bug description.
2. Create a focused branch from the agreed development branch.
3. Make the change and run the checks defined by the team.
4. Open a pull request with a short summary and screenshots for interface changes.

Before extending the prototype, replace session-only demo state with the persistence and authorization design approved by the team.

## Acknowledgments

- **Esprit School of Engineering – Tunisia** and the Technologies Web 2A course
- The course guides on README quality, GitHub standardization, and PHP template integration
- The United Nations Sustainable Development Goals referenced by the project: SDG 4, SDG 8, and SDG 10
- Front-office design assets from eLearning by HTML Codex, distributed by ThemeWagon; attribution and license details are in [THIRD_PARTY_NOTICES.md](THIRD_PARTY_NOTICES.md)
- Administration assets from TailAdmin; the MIT license is included at `app/Views/assets/admin/TAILADMIN_LICENSE.txt`

## License

No license was selected for the CareerPulse project itself. Third-party components retain their own licenses as described in [THIRD_PARTY_NOTICES.md](THIRD_PARTY_NOTICES.md).
