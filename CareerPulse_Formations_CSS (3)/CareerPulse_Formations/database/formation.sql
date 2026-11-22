-- CareerPulse : Module Gestion des Formations, Catégories & Comptes Utilisateurs
-- Importer via phpMyAdmin > Importer (même base de données `formation`)
CREATE DATABASE IF NOT EXISTS formation CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE formation;

-- ---------- Comptes Utilisateurs (Inscription, Connexion, Rôles) ----------
-- Structure conforme au workshop « Sessions et Sécurité PHP » (+ champs de profil CareerPulse).
CREATE TABLE IF NOT EXISTS users (
    id         INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(50)  NOT NULL UNIQUE,
    email      VARCHAR(150) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,                       -- empreinte bcrypt (password_hash), jamais en clair
    role       ENUM('user','admin') NOT NULL DEFAULT 'user', -- user = frontoffice, admin = backoffice
    study      VARCHAR(160) NOT NULL DEFAULT '',
    skills     TEXT NULL,
    goal       TEXT NULL,
    status     ENUM('active','suspended') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- Catégories de Formations ----------
CREATE TABLE IF NOT EXISTS categories (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL UNIQUE,
    description VARCHAR(255) NOT NULL DEFAULT '',
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- Formations ----------
CREATE TABLE IF NOT EXISTS courses (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    category_id INT UNSIGNED NULL,
    title       VARCHAR(200) NOT NULL,
    category    VARCHAR(100) NOT NULL,
    duration    VARCHAR(60)  NOT NULL,
    level       VARCHAR(60)  NOT NULL,
    image       VARCHAR(120) NOT NULL DEFAULT 'course-1.png',
    skills      VARCHAR(255) NOT NULL DEFAULT '',
    description TEXT NULL,
    status      ENUM('Publiée','Brouillon') NOT NULL DEFAULT 'Publiée',
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_course_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- Inscriptions des Utilisateurs aux Formations ----------
CREATE TABLE IF NOT EXISTS course_enrollments (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id     INT UNSIGNED NOT NULL,
    course_id   INT UNSIGNED NOT NULL,
    enrolled_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_user_course (user_id, course_id),
    CONSTRAINT fk_enroll_user   FOREIGN KEY (user_id)   REFERENCES users(id)   ON DELETE CASCADE,
    CONSTRAINT fk_enroll_course FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- Données initiales : Catégories ----------
INSERT IGNORE INTO categories (id, name, description) VALUES
(1, 'Développement', 'Programmation web, génie logiciel, architectures front-end et back-end.'),
(2, 'Data', 'Bases de données SQL, analyse statistique, visualisation et intelligence artificielle.'),
(3, 'Design', 'Conception centrée utilisateur (UX/UI), prototypage Figma et ergonomie web.'),
(4, 'Cybersécurité', 'Sécurité des réseaux, protection des applications web et bonnes pratiques.');

-- ---------- Données initiales : Formations ----------
INSERT INTO courses (category_id, title, category, duration, level, image, skills, description, status)
SELECT * FROM (
  SELECT 1 AS category_id, 'Développement web avec PHP & MVC' AS title, 'Développement' AS category, '6 semaines' AS duration, 'Débutant' AS level, 'course-1.png' AS image, 'PHP · HTML · CSS · PDO' AS skills, 'Maîtrisez la création d’applications web dynamiques structurées en architecture MVC.' AS description, 'Publiée' AS status
  UNION ALL SELECT 2, 'Découvrir l’analyse de données & SQL', 'Data', '4 semaines', 'Intermédiaire', 'course-2.png', 'SQL · Modélisation · Visualisation', 'Apprenez à interroger des bases relationnelles et à extraire des indicateurs clés.', 'Publiée'
  UNION ALL SELECT 3, 'Concevoir une expérience utilisateur (UX/UI)', 'Design', '3 semaines', 'Débutant', 'course-3.png', 'UX · Recherche · Figma', 'Concevez des interfaces claires, accessibles et adaptées aux besoins des utilisateurs.', 'Publiée'
  UNION ALL SELECT 1, 'JavaScript Moderne (ES6+) & Événements DOM', 'Développement', '5 semaines', 'Intermédiaire', 'course-1.png', 'JavaScript · ES6 · DOM · Events', 'Manipulez le DOM, gérez les événements utilisateurs et structurez votre code JS.', 'Publiée'
) AS seed
WHERE NOT EXISTS (SELECT 1 FROM courses);

-- ---------- Comptes de test (mots de passe hachés avec password_hash / bcrypt) ----------
--   admin    : admin@careerpulse.test    / Admin@1234  (rôle admin  => BACKOFFICE)
--   etudiant : etudiant@careerpulse.test / User@1234   (rôle user   => FRONTOFFICE)
-- ⚠ Changez ces mots de passe avant toute mise en ligne.
INSERT IGNORE INTO users (username, email, password, role, study) VALUES
('admin',    'admin@careerpulse.test',    '$2y$10$Ln2QqQXfW/aOh6HXxi9yZeE9TQyc2x5xRsUP9aZmLRHaFlkhDHA7S', 'admin', 'Administration CareerPulse'),
('etudiant', 'etudiant@careerpulse.test', '$2y$10$8Q8RbTz.9OGHlaXJ6FM2KeZSaV4cBIMsGk6HCPcM1ywOaHdpY4aeq', 'user',  'Technologies Web 2A');
