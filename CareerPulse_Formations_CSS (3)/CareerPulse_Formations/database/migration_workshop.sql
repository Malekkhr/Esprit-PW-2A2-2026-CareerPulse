-- Migration d'une base `formation` DÉJÀ importée (ancienne table users : name / password_hash)
-- vers la structure du workshop (username / password / role). À exécuter UNE SEULE FOIS dans phpMyAdmin > SQL.
-- (Alternative plus simple : supprimer la base `formation` puis réimporter database/formation.sql.)
USE formation;

ALTER TABLE users
    CHANGE COLUMN name username VARCHAR(50) NOT NULL,
    CHANGE COLUMN password_hash password VARCHAR(255) NOT NULL,
    MODIFY COLUMN email VARCHAR(150) NOT NULL,
    ADD COLUMN role ENUM('user','admin') NOT NULL DEFAULT 'user' AFTER password;

-- Les anciens « noms complets » deviennent des identifiants valides et uniques (espaces -> _, suffixe -id).
UPDATE users SET username = LEFT(CONCAT(REPLACE(username, ' ', '_'), '_', id), 50);
ALTER TABLE users ADD UNIQUE KEY uq_users_username (username);

-- Comptes de test (voir formation.sql) :
INSERT IGNORE INTO users (username, email, password, role, study) VALUES
('admin',    'admin@careerpulse.test',    '$2y$10$Ln2QqQXfW/aOh6HXxi9yZeE9TQyc2x5xRsUP9aZmLRHaFlkhDHA7S', 'admin', 'Administration CareerPulse'),
('etudiant', 'etudiant@careerpulse.test', '$2y$10$8Q8RbTz.9OGHlaXJ6FM2KeZSaV4cBIMsGk6HCPcM1ywOaHdpY4aeq', 'user',  'Technologies Web 2A');

-- Pour promouvoir manuellement un compte existant en administrateur :
-- UPDATE users SET role = 'admin' WHERE email = 'votre@email.com';
