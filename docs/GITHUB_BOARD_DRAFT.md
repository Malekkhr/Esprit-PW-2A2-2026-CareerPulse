# Brouillon des tâches pour le tableau GitHub — CareerPulse

Ce tableau reprend l'état observé dans la V4 locale. Les statuts sont proposés pour initialiser le Project GitHub; ils peuvent être ajustés par l'équipe. Le nom choisi par l'équipe est `projetwebcarreerpulse`. Le tableau doit encore être créé dans GitHub puis alimenté avec ces tâches.

| Tâche | État proposé | Éléments présents dans la V4 |
|---|---|---|
| Mettre en place l'architecture MVC et le routage des pages | Terminé | Contrôleurs, modèles, vues séparées et routes PHP |
| Préparer les pages et intégrer le template Front Office selon le workshop | Terminé | Accueil, offres, formations, profil, inscription, connexion et candidatures |
| Préparer les pages et intégrer le template Back Office selon le workshop | Terminé | Tableau de bord et pages de gestion des offres, candidatures, utilisateurs, signalements et notifications |
| Connecter l'application à MySQL avec PDO et fournir le schéma SQL | Terminé | `app/Database.php`, `config/database.php`, `database/careerpulse.sql` |
| Enregistrer les comptes, profils et candidatures en base | Terminé | Tables `users` et `applications`, formulaires d'inscription et de candidature |
| Lire et mettre à jour les données de gestion en base | Terminé | Offres, formations, utilisateurs, signalements et notifications |
| Documenter l'installation locale et la consultation SQL | Terminé | README et `GUIDE_XAMPP_SQL.md` |
| Préparer le diagramme de cas d'utilisation | À valider | Version lisible et source PlantUML; aucune remarque des tuteurs n'a été reçue |
| Préparer le diagramme de classes | À valider | Modèle préparatoire avec cardinalités et hypothèses signalées |
| Ajouter les tâches au tableau GitHub du projet | En attente du lien du dépôt ou du Project | Le compte connecté n'expose aucun dépôt CareerPulse; éviter de publier dans un dépôt sans rapport |

## Notes de statut

- « Terminé » signifie que la fonction existe dans le code V4 local; cela ne signifie pas que le prototype est prêt pour un déploiement de production.
- Le diagramme de cas d'utilisation couvre le périmètre cible. CV, questionnaire, moteur de recommandations et push réel restent à réaliser dans la V4.
- Dès que le lien exact du dépôt ou du Project GitHub est fourni, créer une issue par tâche, puis les organiser selon les colonnes de statut déjà présentes dans le Project. Ne pas créer de dépôt ni modifier `Equipe1` sans confirmation que c'est le bon projet.