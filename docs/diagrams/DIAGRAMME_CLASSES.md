# Diagramme de classes — CareerPulse V4

Ce diagramme représente les classes de données réellement persistées dans la V4, d’après `database/careerpulse.sql` et les modèles PHP. Les deux associations indiquées sont les seules relations avec clés étrangères dans ce schéma.

```mermaid
classDiagram
  direction LR

  class Utilisateur {
    +int id PK
    +varchar(120) name
    +varchar(190) email UNIQUE
    +varchar(255) password_hash
    +varchar(160) study
    +text skills
    +text goal
    +string status
    +datetime created_at
    +create(name,email,password,study)
    +findByEmail(email)
    +updateProfile(id,profile)
    +sessionProfile()
    +all()
    +toggleStatus(id)
  }

  class Offre {
    +varchar(20) id PK
    +varchar(200) title
    +varchar(160) company
    +varchar(20) type
    +varchar(100) category
    +varchar(100) location
    +varchar(255) skills
    +tinyint unsigned match_score
    +varchar(20) status
    +datetime created_at
    +all()
    +visibleToStudents(type)
    +findApplicable(id)
    +exists(id)
    +setStatus(id,status)
  }

  class Candidature {
    +int id PK
    +int user_id FK
    +varchar(20) offer_id FK
    +varchar(30) status
    +date applied_at
    +create(userId,offerId)
    +forUser(userId)
    +all()
    +countPending()
    +setStatus(id,status)
  }

  class Formation {
    +int id PK
    +varchar(200) title
    +varchar(100) category
    +varchar(60) duration
    +varchar(60) level
    +varchar(120) image
    +varchar(255) skills
    +all()
  }

  class Signalement {
    +varchar(20) id PK
    +varchar(200) subject
    +varchar(255) reason
    +varchar(20) status
    +datetime created_at
    +all()
    +countOpen()
    +resolve(id)
  }

  class Notification {
    +int id PK
    +varchar(200) title
    +varchar(500) body
    +bool is_read
    +datetime created_at
    +all()
    +markAllRead()
  }

  Utilisateur "1" --> "0..*" Candidature : dépose
  Offre "1" --> "0..*" Candidature : concerne

  note for Candidature "Contrainte SQL UNIQUE(user_id, offer_id) : une candidature par utilisateur et par offre."
```

## Lecture du modèle

- Un utilisateur peut déposer plusieurs candidatures; chaque candidature appartient à un seul utilisateur.
- Une offre peut recevoir plusieurs candidatures; chaque candidature concerne une seule offre.
- `skills`, `study` et `goal` sont des champs de `Utilisateur` : la V4 n’a pas de table `Profil` distincte.
- `Formation`, `Signalement` et `Notification` n’ont pas de clé étrangère dans le schéma V4. Les notifications sont globales dans cette version.
- `match_score` est stocké dans `Offre`; le modèle V4 ne contient pas de classe `Recommandation` ni de calcul dynamique de matching.
- `users.status` est un ENUM SQL (`active`, `suspended`); les autres statuts sont stockés sous forme de chaînes.

## Portée cible du projet

Le modèle cible présenté par CareerPulse peut être étendu avec `CV`, `Préférence`, `Recommandation`, `AlertePush` et `AdminLog`. Ces classes ne sont pas persistées dans la V4 actuelle. Une proposition conceptuelle distincte est conservée dans [DIAGRAMME_CLASSES_CIBLE.md](DIAGRAMME_CLASSES_CIBLE.md); les hypothèses et cardinalités doivent être validées par le groupe et les tuteurs.

La source PlantUML de ce modèle V4 est [DIAGRAMME_CLASSES.puml](DIAGRAMME_CLASSES.puml), et un rendu SVG est disponible dans [DIAGRAMME_CLASSES.svg](DIAGRAMME_CLASSES.svg).
