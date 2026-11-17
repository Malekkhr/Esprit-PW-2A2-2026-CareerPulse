# Diagramme de classes — CareerPulse

**Version de travail préparatoire.** Le modèle ci-dessous reprend les modules présentés pour CareerPulse. Les attributs, opérations et cardinalités qui ne sont pas explicitement décrits dans le support sont des propositions à valider par le groupe et les tuteurs avant la conception de la base de données.

```mermaid
classDiagram
  direction LR

  class Utilisateur {
    +int id
    +string nom
    +string prenom
    +string email
    +string motDePasseHash
    +RoleUtilisateur role
    +bool actif
    +sAuthentifier()
    +modifierProfil()
  }
  class Administrateur {
    +validerOffre()
    +modererUtilisateur()
    +traiterSignalement()
    +consulterStatistiques()
  }
  class Profil {
    +int id
    +string biographie
    +string localisation
    +string competences
    +mettreAJour()
  }
  class CV {
    +int id
    +string nomFichier
    +string cheminStockage
    +datetime dateImport
  }
  class Service {
    +int id
    +string titre
    +string description
    +TypeService type
    +datetime datePublication
    +datetime dateLimite
    +bool actif
  }
  class Categorie {
    +int id
    +string nom
    +string description
  }
  class Preference {
    +int id
    +string domaines
    +string localisationSouhaitee
    +string typeOpportunite
    +enregistrer()
  }
  class Recommandation {
    +int id
    +float scoreCorrespondance
    +datetime dateCalcul
    +string justification
  }
  class Candidature {
    +int id
    +datetime dateSoumission
    +string lettreMotivation
    +suivreStatut()
    +retirer()
  }
  class StatutOffre {
    +int id
    +string libelle
    +datetime dateMiseAJour
  }
  class Notification {
    +int id
    +string titre
    +string message
    +datetime dateCreation
    +datetime dateLecture
    +marquerCommeLue()
  }
  class AlertePush {
    +int id
    +string jetonAppareil
    +string plateforme
    +bool active
    +datetime dateEnregistrement
  }
  class Signalement {
    +int id
    +string motif
    +string description
    +datetime dateCreation
    +string etat
  }
  class AdminLog {
    +int id
    +string action
    +string details
    +datetime dateAction
  }
  class RoleUtilisateur {
    <<enumeration>>
    ETUDIANT
    ADMINISTRATEUR
  }
  class TypeService {
    <<enumeration>>
    STAGE
    EMPLOI
    FORMATION
  }

  Administrateur --|> Utilisateur
  Utilisateur "1" *-- "1" Profil : possède
  Profil "1" *-- "0..1" CV : contient
  Categorie "1" <-- "0..*" Service : classe
  Utilisateur "1" --> "0..*" Preference : définit
  Preference "1" --> "0..*" Recommandation : produit
  Recommandation "0..*" --> "1" Service : suggère
  Utilisateur "1" --> "0..*" Candidature : dépose
  Candidature "0..*" --> "1" Service : concerne
  Candidature "0..*" --> "1" StatutOffre : possède le statut
  Utilisateur "1" --> "0..*" Notification : reçoit
  Utilisateur "1" --> "0..*" AlertePush : enregistre
  Notification "0..*" --> "0..*" AlertePush : est envoyée sur
  Administrateur "1" --> "0..*" AdminLog : produit
  Signalement "1" --> "0..*" AdminLog : traitement journalisé
```

## Hypothèses à confirmer

- `Service` désigne une opportunité du catalogue (stage, emploi ou formation), selon les modules présentés.
- `StatutOffre` est le nom présent dans les éléments préparatoires; l'équipe peut retenir `StatutCandidature` si les statuts décrivent plutôt l'avancement d'une candidature.
- Le CV, les préférences, les notifications push et les journaux d'administration sont modélisés comme classes distinctes pour couvrir les fonctions annoncées. Leurs champs et cardinalités sont des propositions de conception.
- L'association entre `Signalement` et `AdminLog` représente une traçabilité possible du traitement; les tuteurs peuvent demander une autre structure.
- Les types, attributs et opérations sont à ajuster après validation métier et choix de la base de données.