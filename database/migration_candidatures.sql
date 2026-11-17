-- À importer UNE FOIS si vous avez déjà la base careerpulse (phpMyAdmin > base careerpulse > Importer)
USE careerpulse;
ALTER TABLE applications MODIFY status VARCHAR(30) NOT NULL DEFAULT 'À examiner';
UPDATE applications SET status = 'À examiner' WHERE status = 'Soumise';
