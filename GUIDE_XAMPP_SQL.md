# Démarrer CareerPulse V4 avec XAMPP et vérifier MySQL

## Installation déjà faite sur cet ordinateur

Le projet est installé dans `C:\xampp\htdocs\CareerPulse`, et la base `careerpulse` a été importée. Apache et MySQL doivent rester démarrés dans XAMPP.

Ouvre l'application ici : [http://localhost/CareerPulse/](http://localhost/CareerPulse/).

Pour consulter les données : ouvre [http://localhost/phpmyadmin](http://localhost/phpmyadmin), clique sur la base `careerpulse`, puis ouvre les tables :

- `offers` : offres du catalogue (4 lignes de démonstration au départ)
- `courses` : formations (3 lignes au départ)
- `reports` : signalements (2 lignes au départ)
- `notifications` : notifications (3 lignes au départ)
- `users` : comptes créés depuis la page d'inscription
- `applications` : candidatures déposées par un compte connecté

Dans phpMyAdmin, utilise **Actualiser** après une action dans CareerPulse pour voir les changements.

## Vérifier les changements avec SQL

Dans phpMyAdmin, sélectionne `careerpulse`, ouvre l'onglet **SQL** et exécute par exemple :

```sql
SELECT id, name, email, created_at FROM users ORDER BY id DESC;
SELECT user_id, offer_id, status, applied_at FROM applications ORDER BY id DESC;
SELECT id, title, status FROM offers ORDER BY id;
```

## Quelles actions sont enregistrées ?

- Créer un compte ajoute une ligne dans `users`.
- Modifier le profil met à jour cette ligne `users`.
- Postuler à une offre ajoute une ligne dans `applications`.
- Valider ou refuser une offre met à jour `offers.status`.
- Modérer un compte met à jour `users.status`.
- Traiter un signalement met à jour `reports.status`.
- Marquer les notifications comme lues met à jour `notifications.is_read`.

La base enregistre les actions qui ont été programmées pour écrire en SQL. Elle ne journalise pas automatiquement chaque visite ou chaque clic.

## Valeurs de connexion XAMPP

Le fichier `config/database.php` contient les réglages XAMPP habituels : hôte `localhost`, port `3306`, base `careerpulse`, utilisateur `root`, mot de passe vide. Si tu as défini un mot de passe MySQL, modifie `password` dans ce fichier.

Pour réinstaller la base : dans phpMyAdmin, ouvre **Importer** et sélectionne `database/careerpulse.sql`. Le script crée `careerpulse` et ses tables, puis ajoute les données d'exemple sans supprimer les tables existantes.

## Adresse à utiliser

Utilise `http://localhost/CareerPulse/` pour cette version reliée à MySQL. L'ancienne démo sur le port `8001` stocke certaines données en session PHP et n'affiche pas ces données SQL.