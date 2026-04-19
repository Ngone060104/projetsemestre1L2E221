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