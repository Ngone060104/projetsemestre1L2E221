# Gestion des Étudiants et Formations

## Description
Application console en PHP pour la gestion des étudiants et des formations.  
L'application permet de gérer les étudiants et les formations avec validation des données.

## Fonctionnalités

### Espace Étudiant
-  Consulter toutes les formations disponibles
- Rechercher une formation par mot-clé (titre ou description)

### Espace Administrateur
-  Gestion complète des étudiants (CRUD)
  - Ajouter un étudiant
  - Modifier un étudiant
  - Supprimer un étudiant
  - Lister tous les étudiants
- Gestion complète des formations (CRUD)
  - Ajouter une formation
  - Modifier une formation
  - Supprimer une formation
  - Lister toutes les formations

### Structure du projet
projetsemestre1L2E221/
fonction.php      # Toutes les fonctions de l'application
README.md         # Documentation du projet

### Validation des données
Etudiants
Nom : obligatoire
Prénom : obligatoire
Email : format valide (ex: nom@domaine.com)
unique (ne peut pas être utilisé par un autre étudiant)
Adresse : obligatoire
Téléphone : obligatoire
format Sénégal (77, 78, 75, 70, 76 + 7 chiffres)

### Menu de navigation

<!-- Menu Principal -->
1 - Espace Étudiant
2 - Espace Administrateur
3 - Quitter

<!-- Espace Étudiant -->
1 - Consulter toutes les formations
2 - Rechercher une formation
3 - Quitter

<!-- Espace Administrateur -->
1 - Gestion des Étudiants
2 - Gestion des Formations
3 - Quitter

<!-- Gestion des Étudiants (Admin) -->
1 - Ajouter un étudiant
2 - Modifier un étudiant
3 - Supprimer un étudiant
4 - Lister les étudiants
5 - Quitter

<!-- Gestion des Formations (Admin) -->
1 - Ajouter une formation
2 - Modifier une formation
3 - Supprimer une formation
4 - Lister les formations
5 - Quitter


## Prérequis
- PHP 7.4 ou supérieur
- Terminal / Console
- PHP (version 7.4+)
- JSON pour le stockage des données
- Expressions régulières pour les validations
- 


### La Fin . Cloner mon dépôt
git clone https://github.com/Ngone060104/projetsemestre1L2E221.git

### Différence entre break et return :
break	Sort uniquement du switch ou de la boucle, mais continue dans la fonction
return	Sort complètement de la fonction et retourne là où elle a été appelée