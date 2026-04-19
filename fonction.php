<?php
function jsonToArray(): array
{
    // recuperer le contenu du fichier
    $json = file_get_contents("data.json");
    // convertir le json en array
    $datas = json_decode($json, true);
    // retourner $dats
    return $datas;
}
function arrayToJson(array $datas): void
{
    // convertir array en json
    $json = json_encode($datas);
    // recherche le fichier data.json ensuite ajoute $json qui est le tableau convertit en json
    file_put_contents("data.json", $json);
}
function findAllEtudiants(): array
{
    $datas = jsonToArray();
    return $datas["etudiant"];
}
function findAllFormation(): array
{
    $datas = jsonToArray();
    return $datas["formation"];
}
// Générer un nouvel ID automatiquement
function genererNouvelId(): int
{
    $all = findAllEtudiants();
    if (empty($all)) {
        return 1;
    }
    // Trouver l'ID maximum
    $maxId = 0;
    foreach ($all as $etu) {
        if ($etu['id'] > $maxId) {
            $maxId = $etu['id'];
        }
    }

    return $maxId + 1; // Incrémenter
}
// ============ FONCTIONS DE VALIDATION (validation-donnees) ============
// Valider le téléphone avec regex 
function ValiderTelephone(string $telephone): bool
{
    $pattern = '/^(77|78|75|70|76)[0-9]{7}$/';
    if (preg_match($pattern, $telephone)) {
        return true;
    } else {
        return false;
    }
}

// Valider l'email avec regex
function validerEmail(string $email): bool
{
    $pattern = '/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
    if (preg_match($pattern, $email)) {
        return true;
    } else {
        return false;
    }
}
// Vérifier si l'email est unique 
function emailUnique(string $email): bool
{
    $all = findAllEtudiants();
    foreach ($all as $a) {
        if ($a["email"] === $email) {
            return false;
        }
    }
    return true;
}
function creerEtudiant(): array
{
    $nom = readline("Saisir le nom : ");
    $prenom = readline("Saisir le prenom : ");
    $email = readline("Saisir l' email : ");
    $adresse = readline("Saisir l'adresse : ");
    $telephone = readline("Saisir le telephone : ");

    $erreurs = [];
    // 1. Vérifier le nom n'est pas vide
    if (empty(trim($nom))) {
        $erreurs[] = "Le nom est obligatoire";
    }
    // 2. Vérifier le prénom n'est pas vide
    if (empty(trim($prenom))) {
        $erreurs[] = "Le prenom est obligatoire";
    }
    // 3. Vérifier l'email' n'est pas vide
    // 4. Vérifier l'email avec regex
    // 5. Vérifier que l'email est unique
    if (empty(trim($email))) {
        $erreurs[] = "L'email est obligatoire";
    } elseif (!validerEmail($email)) {
        $erreurs[] = "L'email n'est pas valide";
    } elseif (!emailUnique($email)) {
        $erreurs[] = "Cet email existe déja";
    }

    // 6. Vérifier l'adresse' n'est pas vide
    if (empty(trim($adresse))) {
        $erreurs[] = "L' adresse est obligatoire";
    }

    // 7. Vérifier le téléphone n'est pas vide
    // 8. Vérifier le téléphone avec regex
    if (empty(trim($telephone))) {
        $erreurs[] = "Le telephone est obligatoire";
    } elseif (!ValiderTelephone($telephone)) {
        $erreurs[] = "Ce numero n'est pas valide";
    }

    if (!empty($erreurs)) {
        return [
            "error" => true,
            "message" => implode("\n", $erreurs) // Affiche toutes les erreurs
        ];
    }

    $newEtudiant = [
        "id" => genererNouvelId(),
        "nom" => $nom,
        "prenom" => $prenom,
        "email" => $email,
        "adresse" => $adresse,
        "telephone" => $telephone,
    ];

    return [
        "error" => false,
        "message" => "Etudiant créé avec succés",
        "data" => $newEtudiant
    ];
}
function ajouterEtudiant(): void
{
    $resultat = creerEtudiant();
    if ($resultat['error']) {
        echo $resultat['message'] . "\n";
        return;
    }
    // Récupérer toutes les données
    $datas = jsonToArray();

    // Ajouter le nouvel étudiant
    $datas["etudiant"][] = $resultat['data'];

    // Sauvegarder
    arrayToJson($datas);

    echo $resultat['message'] . "\n";
}
//fonction qui affiche un etudiant
function afficheUnEtudiant(array $etudiant): void
{
    echo "=================================\n";
    echo "\tID: " . $etudiant['id'] . "\n";
    echo "\tNom: " . $etudiant['nom'] . "\n";
    echo "\tPrenom: " . $etudiant['prenom'] . "\n";
    echo "\tEmail: " . $etudiant['email'] . "\n";
    echo "\tAdresse: " . $etudiant['adresse'] . "\n";
    echo "\tTelephone: " . $etudiant['telephone'] . "\n";
    echo "=================================\n";
}

//fonction qui affiche tous les etudiants
function afficheTousLesEtudiants(array $etudiants): void
{
    foreach ($etudiants as  $etudiant) {
        if (empty($etudiants)) {
            echo "Aucun étudiant à afficher\n";
            return;
        } else {
            afficheUnEtudiant($etudiant);
        }
    }
}
function saisiEtModifierEtudiant(): array
{

    $all = findAllEtudiants();
    if (empty($all)) {
        return [
            "error" => true,
            "message" => "Aucun etudiant à modifier"
        ];
    }
    afficheTousLesEtudiants($all);
    $choix = (int)readline("\n Choisir le numéro de l'etudiant à modifier : ");
    if (!isset($all[$choix])) {
        return [
            "error" => true,
            "message" => "etudiant non trouvé"
        ];
    }
    $etudiant = $all[$choix];
    echo "\n -- Modification d'un etudiant -- \n";
    $nom = readline("Nom (" . $etudiant["nom"] . "): ");
    $prenom = readline("Prenom (" . $etudiant["prenom"] . "): ");
    $email = readline("Email (" . $etudiant["email"] . "): ");
    $adresse = readline("Adresse (" . $etudiant["adresse"] . "): ");
    $telephone = readline("Telephone (" . $etudiant["telephone"] . "): ");

    $etudiantModifier = [
        "id" => $etudiant["id"],
        "nom" => !empty($nom) ? $nom : $etudiant["nom"],
        "prenom" => !empty($prenom) ? $prenom : $etudiant["prenom"],
        "email" => !empty($email) ? $email : $etudiant["email"],
        "adresse" => !empty($adresse) ? $adresse : $etudiant["adresse"],
        "telephone" => !empty($telephone) ? $telephone : $etudiant["telephone"],
    ];
    return modifierEtudiant($etudiantModifier);
}


function modifierEtudiant($etudiantModifier): array
{
    $erreurs = [];
    // 1. VÉRIFICATIONS avant modification
    // Vérifier le nom
    if (empty($etudiantModifier["nom"])) {
        $erreurs[] = "Le nom est obligatoire";
    }

    // Vérifier le prénom
    if (empty($etudiantModifier["prenom"])) {
        $erreurs[] = "Le prenom est obligatoire";
    }

    // Vérifier l'email
    if (empty($etudiantModifier["email"])) {
        $erreurs[] = "L'email est obligatoire";
    } elseif (!validerEmail($etudiantModifier['email'])) {
        $erreurs[] = "L'email n'est pas valide";
    }
    // Vérifier l'adresse
    if (empty($etudiantModifier["adresse"])) {
        $erreurs[] = "L'adresse n'est pas valide";
    }

    // Vérifier le téléphone avec regex
    // verifier le telephone n'est pas vide
    if (empty($etudiantModifier["telephone"])) {
        $erreurs[] = "Le telephone est obligatoire";
    } elseif (!ValiderTelephone($etudiantModifier['telephone'])) {
        $erreurs[] = "Le telephone n'est pas valide";
    }
    // Si des erreurs existent
    if (!empty($erreurs)) {
        return [
            "error" => true,
            "message" => implode("\n", $erreurs)
        ];
    }

    $datas = jsonToArray();
    // Vérifier que l'email est unique (en ignorant SON email actuel)
    foreach ($datas["etudiant"] as $etu) {
        // Si c'est un autre étudiant qui a le même email
        if ($etu['id'] != $etudiantModifier['id'] && $etu['email'] === $etudiantModifier['email']) {
            return [
                "error" => true,
                "message" => "Cet email existe déjà chez un autre étudiant : "
            ];
        }
    }

    // modification 
    foreach ($datas["etudiant"] as $index => $etu) {
        if ($etu['id'] == $etudiantModifier["id"]) {
            $datas["etudiant"][$index] = $etudiantModifier;
            arrayToJson($datas);
            return [
                "error" => false,
                "message" => "Etudiant modifié avec succès"
            ];
        }
    }
    return [
        "error" => true,
        "message" => "Etudiant non trouvé"
    ];
}

// Supprimer un etudiant 
function deleteEtudiant(): void
{
    $all = findAllEtudiants();
    // Vérifier s'il y a des étudiants
    if (empty($all)) {
        echo "Aucun étudiant à supprimer\n";
        return;
    }
    // liste des etudiants
    afficheTousLesEtudiants($all);

    $choix = (int)readline("\n Choisir le numéro de l'etudiant à suuprimer : ");
    if (!isset($all[$choix])) {
        echo "Etudiant non trouvé . \n";
        return;
    }
    $etudiant = $all[$choix];
    echo "\n --  Étudiant à supprimer -- \n";
    echo "ID: " . $etudiant['id'] . "\n";
    echo "Nom: " . $etudiant['nom'] . "\n";
    echo "Prénom: " . $etudiant['prenom'] . "\n";
    echo "Email: " . $etudiant['email'] . "\n";
    echo "Adresse: " . $etudiant['adresse'] . "\n";
    echo "Téléphone: " . $etudiant['telephone'] . "\n";

    // Demander confirmation
    echo "\n Attention ! Cette action est irréversible.\n" ;
    $confirmation = readline("Confirmez-vous la suppression de cet étudiant ? (o/N) : ");

    // Vérifier la confirmation (o, O, oui, OUI)
    if (strtolower($confirmation) !== 'o' && strtolower($confirmation) !== 'oui') {
        echo "Suppression annulée.\n";
        return;
    }
    // Procéder à la suppression
    $datas = jsonToArray();
    // Supprimer l'étudiant par son indice
    unset($datas["etudiant"][$choix]);
     // Réindexer le tableau (optionnel, pour éviter les trous)
    $datas["etudiant"] = array_values($datas["etudiant"]);
    // Sauvegarder
    arrayToJson($datas);
    echo "Etudiant supprimer avec succés \n";
}
function menuAdministrateur(): void
{
    while (true) {
        echo "\n-----------------------------------------\n";
        echo "       ESPACE ADMINISTRATEUR\n";
        echo "-----------------------------------------\n";
        echo "1 - Gestion des Etudiants \n";
        echo "2 - Gestion des Formations \n";
        echo "3 - Quitter \n";

        $choix = readline("Votre choix : ");
        switch ($choix) {
            case '1':
                menuGestionEtudiant();
                break;
            case '2':
                menuGestionFormation();
                break;
            case '3':
                echo "Retour au menu principal...\n";
                return;
            default:
                echo "Choix invalide. Veuillez réessayer.\n";
        }
    }
}
function menuGestionEtudiant(): void
{
    while (true) {
        echo "\n------ Gestion des Étudiants ------\n";
        echo "1 - Ajouter un étudiant\n";
        echo "2 - Modifier un étudiant\n";
        echo "3 - Supprimer un étudiant\n";
        echo "4 - Lister les étudiants \n";
        echo "5 - Quitter (retour au menu principal)\n";
        echo "----------------------------------\n";
        $choix = readline("Votre choix : ");

        switch ($choix) {
            case '1':
                ajouterEtudiant();
                break;
            case '2':
                $resultat = saisiEtModifierEtudiant();
                if ($resultat["error"]) {
                    echo $resultat["message"];
                } else {
                    echo $resultat["message"];
                }
                break;
            case '3':
                deleteEtudiant();
                break;
            case '4':
                $all = findAllEtudiants();
                afficheTousLesEtudiants($all);
                break;
            case '5':
                echo "Retour au menu principal...\n";
                return; // Retourne au menu principal
            default:
                echo "Choix invalide. Veuillez réessayer.\n";
        }
    }
}

function menuGestionFormation(): void
{
    while (true) {
        echo "\n------ Gestion des Formations ------\n";
        echo "1 - Ajouter une formation\n";
        echo "2 - Modifier une formation\n";
        echo "3 - Supprimer une formation\n";
        echo "4 - Lister les formation\n";
        echo "5 - Quitter (retour au menu principal)\n";
        echo "------------------------------------\n";
        $choix = readline("Votre choix : ");

        switch ($choix) {
            case '1':
                ajouterFormation();
                break;
            case '2':
                $resultat = saisiEtModifierFormation();
                if ($resultat["error"]) {
                    echo $resultat["message"];
                } else {
                    echo $resultat["message"];
                }
                break;
            case '3':
                deleteFormation();
                break;
            case '4':
                $formations = findAllFormation();
                afficheTousLesFormations($formations);
                break;
            case '5':
                echo "Retour au menu principal...\n";
                return; // Retourne au menu principal
            default:
                echo "Choix invalide. Veuillez réessayer.\n";
        }
    }
}

//fonction qui affiche une formation
function afficheUneFormation(array $form): void
{
    echo "-------------------------------------\n";
    echo "\tId:" . $form["id"] . "\n";
    echo "\tTitre: " . $form['titre'] . "\n";
    echo "\tDescription: " . $form['description'] . "\n";
    echo "-------------------------------------\n";
}

//fonction qui affiche tous les formations
function afficheTousLesFormations(array $formations): void
{
    foreach ($formations as  $form) {
        afficheUneFormation($form);
    }
}
function TitreUnique(string $titre): bool
{
    $formations = findAllFormation();
    foreach ($formations as $form) {
        if ($form["titre"] === $titre) {
            return false;
        }
    }
    return true;
}
function genererNouvelIdFormation(): int
{
    $formations = findAllFormation();
    
    if (empty($formations)) {
        return 1;
    }
    
    $maxId = 0;
    foreach ($formations as $formation) {
        if ($formation['id'] > $maxId) {
            $maxId = $formation['id'];
        }
    }
    
    return $maxId + 1;
}
function creerFormation():array{
    $titre = readline("Saisir le titre : ");
    $description = readline("Saisir la description : ");
    $erreurs = [];
     if (empty(trim($titre))) {
        $erreurs[] = "Le titre est  obligatoire";
    }elseif(!TitreUnique($titre)){
       $erreurs[] = "Le titre existe deja";
    }
     if (!empty($erreurs)) {
        return [
            "error" => true,
            "message" => implode("\n", $erreurs) // Affiche toutes les erreurs
        ];
    }
     $newFormation = [
        "id" => genererNouvelIdFormation(),
        "titre" => trim($titre),
        "description" => !empty(trim($description)) ? trim($description) :"",

    ];
     return [
        "error" => false,
        "message" => "Formation créé avec succés",
        "data" => $newFormation
    ];
    
}
function ajouterFormation(): void
{
    $resultat = creerFormation();
    
    if ($resultat['error']) {
        echo $resultat['message'] . "\n";
        return;
    }
    
    // Récupérer toutes les données
    $datas = jsonToArray();
    
    // Ajouter la nouvelle formation
    $datas["formation"][] = $resultat['data'];
    
    // Sauvegarder
    arrayToJson($datas);
    
    echo $resultat['message'] . "\n";
}


function saisiEtModifierFormation(): array
{

    $formations = findAllFormation();
    if (empty($formations)) {
        return [
            "error" => true,
            "message" => "Aucune formation à modifier"
        ];
    }
    afficheTousLesFormations($formations);
    $choix = (int)readline("\n Choisir le numéro de la formation à modifier : ");
    if (!isset($formations[$choix])) {
        return [
            "error" => true,
            "message" => "formation non trouvé"
        ];
    }
    $formation = $formations[$choix];
    echo "\n -- Modification d'un etudiant -- \n";
    $titre = readline("Titre (" . $formation["titre"] . "): ");
    $description = readline("Desciption (" . $formation["description"] . "): ");

    $formationModifier = [
        "id" => $formation["id"],
        "titre" => !empty($titre) ? $titre : $formation["titre"],
        "description" => !empty($description) ? $description : $formation["description"],
    ];
    return modifierFormation($formationModifier);
}


function modifierFormation($formationModifier): array
{
    $erreurs = [];
    // 1. VÉRIFICATIONS avant modification
    // Vérifier le titre
    if (empty($formationModifier["titre"])) {
        $erreurs[] = "Le titre est obligatoire";
    }elseif (!TitreUnique($formationModifier['titre'])) {
        $erreurs[] = "Ce titre existe déjà pour une autre formation";
    }

    // Si des erreurs existent
    if (!empty($erreurs)) {
        return [
            "error" => true,
            "message" => implode("\n", $erreurs)
        ];
    }

    $datas = jsonToArray();
    // Vérifier que le titre est unique (en ignorant SON titre actuel)
    foreach ($datas["formation"] as $form) {
        // Si c'est une autre formation qui a le mêmetitre
        if ($form['id'] != $formationModifier['id'] && $form['titre'] === $formationModifier['titre']) {
            return [
                "error" => true,
                "message" => "Ce titre existe déjà dans une formation : "
            ];
        }
    }

    // modification 
    foreach ($datas["formation"] as $index => $form) {
        if ($form['id'] == $formationModifier["id"]) {
            $datas["formation"][$index] = $formationModifier;
            arrayToJson($datas);
            return [
                "error" => false,
                "message" => "Formation modifiée avec succès"
            ];
        }
    }
    return [
        "error" => true,
        "message" => "Formation non trouvée"
    ];
}

// Fonction Supprimer une formation
function deleteFormation(): void
{
    $formations = findAllFormation();
    // Vérifier s'il y a des formations
    if (empty($formations)) {
        echo "Aucune formation à supprimer\n";
        return;
    }
    // liste des formations
    afficheTousLesFormations($formations);

    $choix = (int)readline("\n Choisir le numéro de la formation à supprimer : ");
    if (!isset($formations[$choix])) {
        echo "Formation non trouvée . \n";
        return;
    }
    $formation = $formations[$choix];
    echo "\n --  Étudiant à supprimer -- \n";
    echo "ID: " . $formation['id'] . "\n";
    echo "Titre: " . $formation['titre'] . "\n";
    echo "Description: " . $formation['description'] . "\n";

    // Demander confirmation
    echo "\n Attention ! Cette action est irréversible.\n" ;
    $confirmation = readline("Confirmez-vous la suppression de cette formation ? (o/N) : ");

    // Vérifier la confirmation (o, O, oui, OUI)
    if (strtolower($confirmation) !== 'o' && strtolower($confirmation) !== 'oui') {
        echo "Suppression annulée.\n";
        return;
    }
    // Procéder à la suppression de la formation
    $datas = jsonToArray();
    // Supprimer la formation par son indice
    unset($datas["formation"][$choix]);
     // Réindexer le tableau (optionnel, pour éviter les trous)
    $datas["formation"] = array_values($datas["formation"]);
    // Sauvegarder
    arrayToJson($datas);
    echo "Formation supprimée avec succés \n";
}
function consulterToutesLesFormations() : void{
    $formations = findAllFormation();
     if (empty($formations)) {
        echo "\nAucune formation disponible pour le moment.\n";
        return;
    }
    
     echo "\n========== LISTE DES FORMATIONS ==========\n";
     afficheTousLesFormations($formations);
      echo "\nTotal: " . count($formations) . " formation(s) disponible(s)\n";
}
function rechercherFormation() : void{
    $formations = findAllFormation();
      if (empty($formations)) {
        echo "\nAucune formation disponible pour le moment.\n";
        return;
    }
    
}

function menuEtudiant(): void
{
    while (true) {
        echo "\n-----------------------------------------\n";
        echo "         ESPACE ÉTUDIANT\n";
        echo "-----------------------------------------\n";
        echo "1 - Consulter toutes les formations\n";
        echo "2 - Rechercher une formation\n";
        echo "3 - Quitter (retour au menu principal)\n";
        echo "-----------------------------------------\n";

        $choix = readline("Votre choix : ");

        switch ($choix) {
            case '1':
                consulterToutesLesFormations();
                break;
            case '2':
                // rechercherFormation();
                break;
            case '3':
                echo "Retour au menu principal...\n";
                return;
            default:
                echo "Choix invalide. Veuillez réessayer.\n";
        }
    }
}
function menuPrincipale(): void
{

    while (true) {
        echo "\n=========================================\n";
        echo "         MENU PRINCIPAL\n";
        echo "=========================================\n";
        echo "1 - Espace Etudiant\n";
        echo "2 - Espace Administrateur\n";
        echo "3 - Quitter\n";
        echo "=========================================\n";

        $choix = readline("Votre choix : ");

        switch ($choix) {
            case '1':
                menuEtudiant();
                break;
            case '2':
                menuAdministrateur();
                break;
            case '3':
                echo "Au revoir !\n";
                exit(0);
            default:
                echo "Choix invalide. Veuillez réessayer.\n";
        }
    }
}
menuPrincipale();


