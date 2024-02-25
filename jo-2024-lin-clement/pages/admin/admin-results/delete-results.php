<?php
session_start();
require_once("../../../database/database.php");

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['login'])) {
    header('Location: ../../../index.php');
    exit();
}

// Vérifiez si l'ID du sport est fourni dans l'URL
if (!isset($_GET['id_athlete, id_epreuve, id_resultat'])) {
    $_SESSION['error'] = "ID du results manquant.";
    header("Location: manage-results.php");
    exit();
} else {
    $id_results = filter_input(INPUT_GET, 'id_results', FILTER_VALIDATE_INT);
    // Vérifiez si l'ID du sport est un entier valide
    if (!$id_results && $id_results !== 0) {
        $_SESSION['error'] = "ID du results invalide.";
        header("Location: manage-results.php");
        exit();
    } else {
        try {
            // Récupérez l'ID de l'œuvre à supprimer depuis la requête GET
            $id_athlete = $_GET['id_athlete'];
            $id_epreuve = $_GET['id_epreuve'];
            $id_resultat = $_GET['id_resultat'];
            
            // Préparez la requête SQL pour supprimer l'œuvre
            $sql = "DELETE FROM PARTICIPER WHERE id_athlete = :id_athlete AND id_epreuve = :id_epreuve AND id_resultat = :id_resultat";
            // Exécutez la requête SQL avec le paramètre
            $statement = $connexion->prepare($sql);
            $statement->bindParam(':id_athlete', $id_athlete, PDO::PARAM_INT);
            $statement->bindParam(':id_epreuve', $id_epreuve, PDO::PARAM_INT);
            $statement->bindParam(':id_resultat', $id_resultat, PDO::PARAM_INT);
            $statement->execute();
            // Redirigez vers la page précédente après la suppression
            header('Location: manage-results.php');
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }
}
// Afficher les erreurs en PHP (fonctionne à condition d’avoir activé l’option en local)
error_reporting(E_ALL);
ini_set("display_errors", 1);
?>