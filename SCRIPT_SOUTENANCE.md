# CareerPulse — script de soutenance

Script aligné sur les **14 diapositives de contenu** du guide oral. Il reprend les diapositives 1, 2, 3 et 5 à 15 du PowerPoint fourni. La diapositive 4 du PowerPoint répète la diapositive 3 : la retirer avant la soutenance, ou la laisser comme transition sans commentaire. Garder la diapositive 16 (« Image Sources ») en annexe de crédits, sans la compter dans les 14 diapositives orales.

Durée visée : **12 à 15 minutes**, avec un débit posé et les transitions entre intervenants. Le script présente les fonctionnalités comme la conception du projet; les documents ne confirment pas leur état d'implémentation. La statistique « 3 à 4 heures par semaine » du guide n'est pas reprise, car aucune source n'est jointe.

## 1. Couverture — PowerPoint, diapositive 1

**Intervenant : Mohamed Malek Tanazefti**

Bonjour à toutes et à tous, membres du jury. Nous sommes l'équipe MindPrint : Youssef Salem, Mouhanned Khammar, Yacine Bedoui, Malek Khadhraoui, Youssef Naouar et moi-même, Mohamed Malek Tanazefti. Nous allons vous présenter CareerPulse, notre projet d'application mobile et web consacré à l'orientation et à l'insertion professionnelle des étudiants. Pour l'année universitaire 2026–2027, nous avons travaillé sur une question concrète : comment relier dans un même parcours le profil de l'étudiant, ses besoins de formation et les opportunités professionnelles qui lui correspondent ?

## 2. Introduction — PowerPoint, diapositive 2

**Intervenant : Mohamed Malek Tanazefti**

Un étudiant peut avoir besoin de plusieurs outils différents pour s'orienter, suivre une formation, préparer son CV et rechercher un stage. Notre projet part de cette expérience fragmentée. Notre problématique est la suivante : comment faciliter l'orientation et l'insertion professionnelle depuis un smartphone ? CareerPulse vise à réunir ces démarches, à proposer des opportunités adaptées au profil et aux préférences de l'étudiant, puis à lui permettre de suivre ses candidatures. Le back-office complète ce parcours en donnant à l'équipe d'administration des outils de modération et de suivi.

## 3. Idée du projet — PowerPoint, diapositive 3

**Intervenant : Mohamed Malek Tanazefti**

Le concept repose sur trois piliers. Le premier est un profil étudiant que l'on peut consulter depuis le mobile, avec le CV et les compétences. Le deuxième est un moteur de matching qui croise ces informations avec les préférences exprimées. Le troisième est le suivi par notifications, afin que l'étudiant puisse retrouver les évolutions de ses candidatures. Le nom CareerPulse évoque l'idée d'une carrière qui progresse et reste active. L'objectif est de rendre le parcours plus cohérent, du choix d'une formation jusqu'à la candidature.

## 4. Étude de l'existant — PowerPoint, diapositive 5

**Intervenant : Youssef Salem**

Nous avons retenu trois références pour situer notre projet. LinkedIn met l'accent sur le réseau professionnel et les profils. Indeed facilite la recherche d'offres d'emploi. Coursera donne accès à des cours et à des parcours de formation. Chacun répond à un besoin utile, mais l'étudiant doit passer d'un service à l'autre pour relier formation, compétences et recherche d'opportunités. Notre étude ne cherche pas à remplacer ces plateformes dans tous leurs usages. Elle nous aide à préciser le rôle de CareerPulse : organiser le parcours étudiant autour de ces besoins complémentaires.

## 5. Critique et valeur ajoutée — PowerPoint, diapositive 6

**Intervenant : Youssef Salem**

Dans la comparaison présentée, nous avons relevé des limites différentes : l'orientation académique n'est pas au centre de LinkedIn, Indeed ne guide pas directement l'apprentissage, et Coursera ne relie pas ses cours aux opportunités locales dans le parcours décrit par notre projet. CareerPulse propose donc une expérience unifiée : un questionnaire de positionnement, des cours et des offres, des recommandations adaptées, puis des alertes et un suivi. Si une compétence manque pour une opportunité visée, le principe est de pouvoir orienter l'étudiant vers une formation correspondante. C'est cette boucle entre apprendre et postuler qui distingue notre proposition.

## 6. Solution et ODD — PowerPoint, diapositive 7

**Intervenant : Mouhanned Khammar**

CareerPulse est conçu comme une application mobile et web destinée aux étudiants, accompagnée d'un back-office d'administration. Nous relions cette proposition à trois Objectifs de développement durable. L'ODD 4 concerne l'accès à une éducation de qualité, auquel répond la mise en avant de formations adaptées. L'ODD 8 concerne le travail décent, auquel se rattache l'accès aux stages et aux emplois. Enfin, l'ODD 10 vise la réduction des inégalités : notre intention est de recommander des opportunités à partir des compétences et des préférences, pour faciliter l'accès sans dépendre uniquement d'un réseau personnel.

## 7. Cas d'utilisation général — PowerPoint, diapositive 8

**Intervenant : Mouhanned Khammar**

Le diagramme présente deux acteurs principaux. L'étudiant s'authentifie, gère son profil et son CV, consulte des recommandations, postule et suit ses candidatures. L'administrateur s'authentifie également, puis gère les offres, modère les utilisateurs, traite les signalements et consulte les statistiques. Cette séparation distingue les opérations de candidature des opérations de gouvernance. Elle permet aussi de clarifier les droits associés à chaque rôle. Les cas d'utilisation détaillés qui suivent décomposent ces responsabilités en six modules.

## 8. Modules 1 et 2 — PowerPoint, diapositive 9

**Intervenants : Mohamed Malek Tanazefti et Youssef Salem**

Le premier module couvre l'authentification et les profils : inscription, connexion, modification du profil et import du CV. La conception sépare l'utilisateur, qui porte les informations de compte, du profil, qui porte les informations du CV. Le deuxième module est le catalogue de services. Il permet de parcourir les cours et opportunités, de filtrer par catégorie, de consulter les détails et d'enregistrer des éléments. Dans le modèle présenté, une catégorie peut regrouper plusieurs services. Ces deux modules fournissent les informations de base dont les autres parties de CareerPulse ont besoin.

## 9. Modules 3 et 4 — PowerPoint, diapositive 10

**Intervenants : Mouhanned Khammar et Yacine Bedoui**

Le troisième module est le moteur de matching. Le questionnaire et les préférences de l'étudiant alimentent des recommandations qui tiennent compte du profil et des vœux exprimés. Le quatrième module porte sur les candidatures : postuler, consulter l'historique et suivre le statut du dossier. Les statuts décrits dans le guide comprennent notamment la soumission, la revue, l'entretien et la décision. L'intérêt de relier ces modules est de donner à l'étudiant une continuité entre la découverte d'une opportunité et le suivi de sa candidature.

## 10. Modules 5 et 6 — PowerPoint, diapositive 11

**Intervenants : Malek Khadhraoui et Youssef Naouar**

Le cinquième module est l'administration. Il comprend la validation ou le rejet des offres, la modération des utilisateurs, le traitement des signalements et la consultation de statistiques. Les actions d'administration doivent pouvoir être retracées afin de faciliter le suivi. Le sixième module concerne les notifications : recevoir une alerte, consulter l'historique, configurer les alertes et marquer un message comme lu. La présentation prévoit des notifications push. Le choix du fournisseur et l'intégration technique devront être confirmés dans l'implémentation.

## 11. Répartition des modules — PowerPoint, diapositive 12

**Intervenant : Yacine Bedoui**

La répartition associe un module et deux entités liées à chaque membre : Mohamed prend l'authentification et les profils; Youssef, le catalogue; Mouhanned, le matching; je prends les candidatures; Malek Khadhraoui, l'administration; et Youssef Naouar, les notifications. Cette organisation donne à chaque membre un périmètre identifiable et rend visibles les principales relations de données. Elle facilite le travail en parallèle, tout en gardant des points de raccordement entre les modules : le profil alimente le matching, le catalogue fournit les opportunités et les candidatures déclenchent le suivi.

## 12. Charte graphique — PowerPoint, diapositive 13

**Intervenant : Malek Khadhraoui**

Notre identité visuelle s'appuie sur quatre couleurs : le vert forêt `#0F2A1D`, le vert sauge `#1F3D2B`, le doré `#D9A531` et le crème `#FAFAF9`. Le vert évoque la stabilité et la progression; le doré apporte un accent plus énergique et rappelle la pulsation intégrée au logo. Nous avons retenu Arial pour sa lisibilité sur les interfaces web et mobiles. Ces choix doivent rester cohérents entre l'espace étudiant et le back-office, même si leurs usages et leur densité d'information sont différents.

## 13. Choix des templates — PowerPoint, diapositive 14

**Intervenant : Youssef Naouar**

Nous distinguons deux espaces. Le Front Office est conçu pour l'étudiant : fil d'opportunités, matching, profil et CV, suivi des candidatures et alertes. Le Back Office est destiné à l'administration : validation d'offres, modération et statistiques. L'interface étudiant doit rester pratique sur mobile, tandis que l'administration doit rendre les opérations de contrôle faciles à repérer. L'atelier d'intégration fourni décrit une méthode PHP pour extraire les éléments communs des deux templates et les réutiliser entre les pages, tout en gardant le contenu propre à chaque écran.

## 14. Conclusion et perspectives — PowerPoint, diapositive 15

**Intervenant : Mohamed Malek Tanazefti**

Pour conclure, CareerPulse propose de rapprocher l'orientation, les formations et les opportunités professionnelles dans un parcours unique, complété par un espace d'administration. La valeur centrale du projet est de relier le profil et les préférences de l'étudiant à des recommandations, puis de garder un suivi des candidatures. Pour la suite, nous envisageons un coaching vocal assisté par l'IA générative et des partenariats avec des entreprises. Ce sont des perspectives de développement. Merci pour votre attention; nous sommes prêts à répondre à vos questions.

## Repères pour la présentation

- Garder la diapositive 16 du PowerPoint comme annexe de crédits d'images; ne pas la lire comme une diapositive de contenu.
- Supprimer ou passer rapidement la diapositive 4 du PowerPoint, qui répète l'idée de la diapositive 3.
- Ne pas présenter une fonctionnalité comme déjà développée si elle n'est pas vérifiée dans l'application.
- La statistique de 3 à 4 heures par semaine figurant dans le guide oral n'a pas de source dans les pièces fournies; ne pas l'annoncer comme un fait sans référence vérifiable.
