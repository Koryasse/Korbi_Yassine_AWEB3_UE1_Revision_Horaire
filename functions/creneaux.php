<?php

require_once "../connexion/db.php";
require_once "../functions/classes.php";

/**
 * Lire tous les créneaux
 *
 * @return array Tableau des créneaux
 */
function getAllCreneaux(): array
{
    $query = "SELECT creneaux.*,
                     classes.nom AS classe_nom,
                     cours.code AS cours_code,
                     cours.nom AS cours_nom,
                     TIME_FORMAT(creneaux.heure_debut, '%H:%i') AS heure_debut,
                     TIME_FORMAT(creneaux.heure_fin, '%H:%i') AS heure_fin
              FROM creneaux
              JOIN classes ON creneaux.classe_id = classes.id
              JOIN cours ON creneaux.cours_id = cours.id
              ORDER BY classes.nom,
                       FIELD(creneaux.jour, 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi'),
                       creneaux.heure_debut";
    return dbRun($query)->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Ajouter un créneau dans la base de données
 *
 * @param integer $classeId
 * @param integer $coursId
 * @param string $jour
 * @param string $heureDebut
 * @param string $heureFin
 * @param string $salle
 * @return integer L'id du créneau créé
 */
function insertCreneau(int $classeId, int $coursId, string $jour, string $heureDebut, string $heureFin, string $salle): int
{
    $query = "INSERT INTO creneaux (classe_id, cours_id, jour, heure_debut, heure_fin, salle)
            VALUES (:classe_id, :cours_id, :jour, :heure_debut, :heure_fin, :salle)";
    dbRun($query, [':classe_id' => $classeId, ':cours_id' => $coursId, ':jour' => $jour, ':heure_debut' => $heureDebut, ':heure_fin' => $heureFin, ':salle' => $salle]);
    return (int) db()->lastInsertId();
}

/**
 * Effacer un créneau
 *
 * @param integer $id
 * @return void
 */
function deleteCreneau(int $id): void
{
    dbRun("DELETE FROM creneaux WHERE id = :id", [':id' => $id]);
}

?>