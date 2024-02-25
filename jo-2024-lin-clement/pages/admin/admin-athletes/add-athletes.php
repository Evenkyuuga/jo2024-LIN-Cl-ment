<?php
session_start();
require_once("../../../database/database.php");

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['login'])) {
    header('Location: ../../../index.php');
    exit();
}

// Vérifiez si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assurez-vous d'obtenir des données sécurisées et filtrées
    $idAthletes = filter_input(INPUT_POST, 'idAthletes', FILTER_SANITIZE_STRING);
    $nomAthletes = filter_input(INPUT_POST, 'nomAthletes', FILTER_SANITIZE_STRING);
    $prenomAthlete = filter_input(INPUT_POST, 'prenomAthlete', FILTER_SANITIZE_STRING);
    $idPays = filter_input(INPUT_POST, 'idPays', FILTER_SANITIZE_STRING);
    $idGenre = filter_input(INPUT_POST, 'idGenre', FILTER_SANITIZE_STRING);

    // Vérifiez si le nom du sport est vide
    // if (empty($nomAthlete) || empty($prenomAthlete) || empty($idPays) || empty($idGenre)) {
    //     $_SESSION['error'] = "Le nom du athlete ne peut pas être vide.";
    //     header("Location: add-athletes.php");
    //     exit();
    // }


    // Vérifiez si le nom de l'athlète est vide
    if (empty($idAthlete) || empty($nomAthlete) || empty($prenomAthlete) || empty($idPays) || empty($idGenre)) {
        $_SESSION['error'] = "Tous les champs doivent être remplis.";
        header("Location: add-athletes.php");
        exit();
    }

    try {
        // Vérifiez si le sport existe déjà
        $queryCheck = "SELECT id_athletes FROM ATHLETE WHERE id_athlete = :idAthlete AND nom_athlete = :nomAthlete AND prenom_athlete = :prenomAthlete AND id_pays = :idPays AND id_genre = :idGenre";
        $statementCheck = $connexion->prepare($queryCheck);
        $statementCheck->bindParam(":idAthlete", $idAthlete, PDO::PARAM_STR);
        $statementCheck->bindParam(":nomAthlete", $nomAthlete, PDO::PARAM_STR);
        $statementCheck->bindParam(":prenomAthlete", $prenomAthlete, PDO::PARAM_STR);
        $statementCheck->bindParam(":idPays", $idPays, PDO::PARAM_STR);
        $statementCheck->bindParam(":idGenre", $idGenre, PDO::PARAM_STR);
        $statementCheck->execute();

        if ($statementCheck->rowCount() > 0) {
            $_SESSION['error'] = "Le athlete existe déjà.";
            header("Location: add-athletes.php");
            exit();
        } else {

            // Requête pour ajouter un sport
            // $query = "INSERT INTO ATHLETE (id_athlete) VALUES (:idAthlete)";
            // $query = "INSERT INTO ATHLETE (nom_athlete) VALUES (:nomAthlete)";
            // $query = "INSERT INTO ATHLETE (prenom_athlete) VALUES (:prenomAthlete)";
            // $query = "INSERT INTO ATHLETE (id_pays) VALUES (:idPays)";
            // $query = "INSERT INTO ATHLETE (id_genre) VALUES (:idGenre)";
            // $statement = $connexion->prepare($query);
            // $statement->bindParam(":idAthlete", $idAthlete, PDO::PARAM_STR);
            // $statement->bindParam(":nomAthlete", $nomAthlete, PDO::PARAM_STR);
            // $statement->bindParam(":prenomAthlete", $prenomAthlete, PDO::PARAM_STR);
            // $statement->bindParam(":idPays", $idPays, PDO::PARAM_STR);
            // $statement->bindParam(":idGenre", $idGenre, PDO::PARAM_STR);

            $query = "INSERT INTO ATHLETE (id_athlete, nom_athlete, prenom_athlete, id_pays, id_genre) 
            VALUES (:idAthlete, :nomAthlete, :prenomAthlete, :idPays, :idGenre)";
            $statement = $connexion->prepare($query);
            $statement->bindParam(":idAthlete", $idAthlete, PDO::PARAM_STR);
            $statement->bindParam(":nomAthlete", $nomAthlete, PDO::PARAM_STR);
            $statement->bindParam(":prenomAthlete", $prenomAthlete, PDO::PARAM_STR);
            $statement->bindParam(":idPays", $idPays, PDO::PARAM_STR);
            $statement->bindParam(":idGenre", $idGenre, PDO::PARAM_STR);

            // Exécutez la requête
            if ($statement->execute()) {
                $_SESSION['success'] = "Le athlete a été ajouté avec succès.";
                header("Location: manage-athletes.php");
                exit();
            } else {
                $_SESSION['error'] = "Erreur lors de l'ajout du athlete.";
                header("Location: add-athletes.php");
                exit();
            }
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur de base de données : " . $e->getMessage();
        header("Location: add-athletes.php");
        exit();
    }
}
// Afficher les erreurs en PHP
// (fonctionne à condition d’avoir activé l’option en local)
error_reporting(E_ALL);
ini_set("display_errors", 1);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/normalize.css">
    <link rel="stylesheet" href="../../../css/styles-computer.css">
    <link rel="stylesheet" href="../../../css/styles-responsive.css">
    <link rel="shortcut icon" href="../../../img/favicon-jo-2024.ico" type="image/x-icon">
    <title>Ajouter un Athletes - Jeux Olympiques 2024</title>
    <style>
        /* Ajoutez votre style CSS ici */
    </style>
</head>

<body>
    <header>
        <nav>
            <!-- Menu vers les pages sports, events, et results -->
            <ul class="menu">
                <li><a href="../admin.php">Accueil Administration</a></li>
                <li><a href="../admin-sports/manage-sports.php">Gestion Sports</a></li>
                <li><a href="../admin-places/manage-places.php">Gestion Lieux</a></li>
                <li><a href="../admin-events/manage-events.php">Gestion Calendrier</a></li>
                <li><a href="../admin-countries/manage-countries.php">Gestion Pays</a></li>
                <li><a href="../admin-gender/manage-gender.php">Gestion Genres</a></li>
                <li><a href="../admin-athletes/manage-athletes.php">Gestion Athlètes</a></li>
                <li><a href="../admin-results/manage-results.php">Gestion Résultats</a></li>
                <li><a href="../../logout.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Ajouter un Athlete</h1>
        <?php
        if (isset($_SESSION['error'])) {
            echo '<p style="color: red;">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']);
        }
        ?>
        <!-- <form action="add-athletes.php" method="post"
            onsubmit="return confirm('Êtes-vous sûr de vouloir ajouter ce athlète?')">
            <label for=" nomAthlete">Nom du Athlete :</label>
            <input type="text" name="nomAthlete" id="nomAthlete" required>
            <input type="submit" value="Ajouter le Athlete">
        </form> -->

        <form action="add-athletes.php" method="post"
            onsubmit="return confirm('Êtes-vous sûr de vouloir ajouter ce athlète?')">
            <label for="idAthlete">Id du Athlète :</label>
            <input type="text" name="idAthlete" id="idAthlete" required>

            <label for="nomAthlete">Nom du Athlète :</label>
            <input type="text" name="nomAthlete" id="nomAthlete" required>

            <label for="prenomAthlete">Prénom du Athlète :</label>
            <input type="text" name="prenomAthlete" id="prenomAthlete" required>

            <label for="idPays">Pays du Athlète :</label>
            <input type="text" name="idPays" id="idPays" required>

            <label for="idGenre">Genre du Athlète :</label>
            <input type="text" name="idGenre" id="idGenre" required>

            <input type="submit" value="Ajouter le Athlète">
        </form>

        <p class="paragraph-link">
            <a class="link-home" href="manage-athletes.php">Retour à la gestion des Atheltes</a>
        </p>
    </main>
    <footer>
        <figure>
            <img src="../../../img/logo-jo-2024.png" alt="logo jeux olympiques 2024">
        </figure>
    </footer>

</body>

</html>