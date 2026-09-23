<?php

require_once "../connexion/db.php";

/**
 * Lire tous les cours
 *
 * @return array Tableau des cours
 */
function getAllCours(): array
{
    return dbRun("SELECT * FROM cours ORDER BY code")->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Ajouter un cours dans la base de données
 *
 * @param string $code
 * @param string $nom
 * @return integer L'id du cours créé
 */
function insertCours(string $code, string $nom): int
{
    dbRun("INSERT INTO cours (code, nom) VALUES (:code, :nom)", [':code' => $code, ':nom' => $nom]);
    return (int) db()->lastInsertId();
}

/**
 * Effacer un cours
 *
 * @param integer $id
 * @return void
 */
function deleteCours(int $id): void
{
    dbRun("DELETE FROM cours WHERE id = :id", [':id' => $id]);
}

?>
