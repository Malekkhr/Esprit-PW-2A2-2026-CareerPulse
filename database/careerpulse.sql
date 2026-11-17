-- CareerPulse : base de données (importer via phpMyAdmin > Importer)
CREATE DATABASE IF NOT EXISTS careerpulse CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE careerpulse;

CREATE TABLE IF NOT EXISTS users (
    id            INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(120) NOT NULL,
    email         VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    study         VARCHAR(160) NOT NULL DEFAULT '',
    skills        TEXT NULL,
    goal          TEXT NULL,
    status        ENUM('active','suspended') NOT NULL DEFAULT 'active',
    created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- Offres ----------
CREATE TABLE IF NOT EXISTS offers (
    id          VARCHAR(20)  NOT NULL PRIMARY KEY,
    title       VARCHAR(200) NOT NULL,
    company     VARCHAR(160) NOT NULL,
    type        VARCHAR(20)  NOT NULL,          -- Stage / Emploi / Cours
    category    VARCHAR(100) NOT NULL,
    location    VARCHAR(100) NOT NULL,
    skills      VARCHAR(255) NOT NULL DEFAULT '',
    match_score TINYINT UNSIGNED NOT NULL DEFAULT 0,
    status      VARCHAR(20)  NOT NULL DEFAULT 'À examiner',  -- Validée / À examiner / Rejetée
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- Formations ----------
CREATE TABLE IF NOT EXISTS courses (
    id       INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title    VARCHAR(200) NOT NULL,
    category VARCHAR(100) NOT NULL,
    duration VARCHAR(60)  NOT NULL,
    level    VARCHAR(60)  NOT NULL,
    image    VARCHAR(120) NOT NULL DEFAULT 'course-1.jpg',
    skills   VARCHAR(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- Candidatures ----------
CREATE TABLE IF NOT EXISTS applications (
    id         INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id    INT UNSIGNED NOT NULL,
    offer_id   VARCHAR(20)  NOT NULL,
    status     VARCHAR(30)  NOT NULL DEFAULT 'À examiner',  -- À examiner / Acceptée / Refusée
    applied_at DATE NOT NULL,
    UNIQUE KEY uq_user_offer (user_id, offer_id),
    CONSTRAINT fk_app_user  FOREIGN KEY (user_id)  REFERENCES users(id)  ON DELETE CASCADE,
    CONSTRAINT fk_app_offer FOREIGN KEY (offer_id) REFERENCES offers(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- Signalements ----------
CREATE TABLE IF NOT EXISTS reports (
    id         VARCHAR(20)  NOT NULL PRIMARY KEY,
    subject    VARCHAR(200) NOT NULL,
    reason     VARCHAR(255) NOT NULL,
    status     VARCHAR(20)  NOT NULL DEFAULT 'Ouvert',   -- Ouvert / Traité
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- Notifications ----------
CREATE TABLE IF NOT EXISTS notifications (
    id         INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title      VARCHAR(200) NOT NULL,
    body       VARCHAR(500) NOT NULL,
    is_read    TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- Données de départ (sans doublons si on réimporte) ----------
INSERT IGNORE INTO offers (id, title, company, type, category, location, skills, match_score, status) VALUES
('CP-101', 'Stagiaire développeur web', 'Northstar Lab', 'Stage', 'Technologie', 'Tunis', 'PHP · HTML · CSS', 92, 'Validée'),
('CP-102', 'Assistant analyste données', 'Atlas Numérique', 'Stage', 'Data', 'Ariana', 'SQL · Excel · Analyse', 84, 'Validée'),
('CP-103', 'Junior UX researcher', 'Studio Mosaïque', 'Emploi', 'Design', 'Hybride', 'Recherche · Figma · UX', 76, 'À examiner'),
('CP-104', 'Formation : bases de la cybersécurité', 'Campus Numérique', 'Cours', 'Formation', 'En ligne', 'Réseaux · Sécurité', 88, 'Validée');

INSERT INTO courses (title, category, duration, level, image, skills)
SELECT * FROM (
  SELECT 'Développement web avec PHP' AS title, 'Développement' AS category, '6 semaines' AS duration, 'Débutant' AS level, 'course-1.jpg' AS image, 'PHP · HTML · CSS' AS skills
  UNION ALL SELECT 'Découvrir l’analyse de données', 'Data', '4 semaines', 'Intermédiaire', 'course-2.jpg', 'SQL · Visualisation'
  UNION ALL SELECT 'Concevoir une expérience utilisateur', 'Design', '3 semaines', 'Débutant', 'course-3.jpg', 'UX · Recherche · Figma'
) AS seed
WHERE NOT EXISTS (SELECT 1 FROM courses);

INSERT IGNORE INTO reports (id, subject, reason, status) VALUES
('R-301', 'Offre CP-102', 'Informations à vérifier', 'Ouvert'),
('R-302', 'Profil étudiant', 'Demande de vérification', 'Ouvert');

INSERT INTO notifications (title, body, is_read)
SELECT * FROM (
  SELECT 'Votre profil est prêt' AS title, 'Complétez vos compétences pour affiner les recommandations.' AS body, 0 AS is_read
  UNION ALL SELECT 'Nouvelle offre correspondante', 'Une opportunité de stage correspond à votre profil.', 0
  UNION ALL SELECT 'Suivi de candidature', 'Retrouvez le statut de vos demandes dans votre espace.', 0
) AS seed
WHERE NOT EXISTS (SELECT 1 FROM notifications);
