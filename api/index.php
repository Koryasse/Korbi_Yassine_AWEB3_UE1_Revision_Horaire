<?php

require_once "../config/config.php";
require_once "../functions/classes.php";
require_once "../functions/cours.php";
require_once "../functions/creneaux.php";

// Methode de requête
$methode = $_SERVER['REQUEST_METHOD'];

// Ressource demandée
$ressource = filter_input(INPUT_GET, "resource", FILTER_SANITIZE_STRING) ?? '';

// Vérifie id
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

// Données JSON envoyées
$donnees = lireDonneeBody();

// Selon la ressource demandée
switch ($ressource) {
    case "classes":
        $reponse = traiterClasses($methode, $id, $donnees);
        break;
    case "cours":
        $reponse = traiterCours($methode, $id, $donnees);
        break;
    case "creneaux":
        $reponse = traiterCreneaux($methode, $id, $donnees);
        break;
    default:
        $reponse = [
            "code" => HTTP_NOT_FOUND,
            "data" => "Ressource inconnue."
        ];
}

// Envoie la réponse
envoyerReponse($reponse);

/**
 * Traiter une requête sur la ressource "classes"
 *
 * @param string $methode
 * @param integer|false|null $id
 * @param array $donnees
 * @return array
 */
function traiterClasses(string $methode, int|false|null $id, array $donnees): array
{
    switch ($methode) {
        case "GET":
            return ["code" => HTTP_OK, "data" => getAllClasses()];

        case "POST":
            $nouvelId = insertClasse($donnees['nom'] ?? '', $donnees['annee_scolaire'] ?? '');

        case "DELETE":
            if (!$id) {
                return ["code" => HTTP_BAD_REQUEST, "data" => "id requis"];
            }
            deleteClasse($id);
            return ["code" => HTTP_NO_CONTENT, "data" => null];

        default:
            return ["code" => HTTP_METHOD_NOT_ALLOWED, "data" => "La méthode $methode n'est pas supportée."];
    }
}

/**
 * Traiter une requête sur la ressource "cours"
 *
 * @param string $methode
 * @param integer|false|null $id
 * @param array $donnees
 * @return array
 */
function traiterCours(string $methode, int|false|null $id, array $donnees): array
{
    $nomClasse = filter_input(INPUT_GET, "classe", FILTER_SANITIZE_STRING) ?? null;

    switch ($methode) {
        case "GET":
            return ["code" => HTTP_OK, "data" => getAllCours()];

        case "POST":
            $nouvelId = insertCours($donnees['code'] ?? '', $donnees['nom'] ?? '');

        case "DELETE":
            if (!$id) {
                return ["code" => HTTP_BAD_REQUEST, "data" => "id requis"];
            }
            deleteCours($id);
            return ["code" => HTTP_NO_CONTENT, "data" => null];

        default:
            return ["code" => HTTP_METHOD_NOT_ALLOWED, "data" => "La méthode $methode n'est pas supportée."];
    }
}

/**
 * Traiter une requête sur la ressource "creneaux"
 *
 * @param string $methode
 * @param integer|false|null $id
 * @param array $donnees
 * @return array
 */
function traiterCreneaux(string $methode, int|false|null $id, array $donnees): array
{
    switch ($methode) {
        case "GET":
                return ["code" => HTTP_OK, "data" => getAllCreneaux()];

        case "POST":
            $nouvelId = insertCreneau(
                (int) ($donnees['classe_id'] ?? 0),
                (int) ($donnees['cours_id'] ?? 0),
                $donnees['jour'] ?? '',
                $donnees['heure_debut'] ?? '',
                $donnees['heure_fin'] ?? '',
                $donnees['salle'] ?? ''
            );

        case "DELETE":
            if (!$id) {
                return ["code" => HTTP_BAD_REQUEST, "data" => "id requis"];
            }
            deleteCreneau($id);
            return ["code" => HTTP_NO_CONTENT, "data" => null];

        default:
            return ["code" => HTTP_METHOD_NOT_ALLOWED, "data" => "La méthode $methode n'est pas supportée."];
    }
}

?>
