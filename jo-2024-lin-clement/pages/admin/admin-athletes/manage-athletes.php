<?php
session_start();

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['login'])) {
    header('Location: ../../../index.php');
    exit();
}

$login = $_SESSION['login'];
$nom_utilisateur = $_SESSION['prenom_utilisateur'];
$prenom_utilisateur = $_SESSION['nom_utilisateur'];
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
    <title>Liste des Sports - Jeux Olympiques 2024</title>
    <style>
        /* Ajoutez votre style CSS ici */
        .action-buttons {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .action-buttons button {
            background-color: #1b1b1b;
            color: #d7c378;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .action-buttons button:hover {
            background-color: #d7c378;
            color: #1b1b1b;
        }

        .color {
            color: white;
        }
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
        <h1>Liste des Athlètes</h1>
        <div class="action-buttons">
            <button onclick="openAddAthleteForm()">Ajouter un Athlètes</button>
            <!-- Autres boutons... -->
        </div>
        <!-- Tableau des sports -->
        <?php
        require_once("../../../database/database.php");

        try {
            // Requête pour récupérer la liste des sports depuis la base de données
            $query = "SELECT * FROM ATHLETE ORDER BY id_athlete, nom_athlete, prenom_athlete, id_pays, id_genre";
            $statement = $connexion->prepare($query);
            $statement->execute();

            // Vérifier s'il y a des résultats
            if ($statement->rowCount() > 0) {
                echo "<table>";
                echo "<tr>";
                echo "<th class='couleur'>id_athlete</td>";
                echo "<th class='couleur'>nom_athlete</td>";
                echo "<th class='couleur'>prenom_athlete</td>";
                echo "<th class='couleur'>id_pays</td>";
                echo "<th class='couleur'>id_genre</td>";
                echo "<th class='couleur'>Modifier</td>";
                echo "<th class='couleur'>Supprimer</td>";
                echo "</tr>";

                // Afficher les données dans un tableau
                while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td class='color'>" . htmlspecialchars($row['id_athlete']) . "</td>"; // Utilisation de la classe 'color' pour changer la couleur
                    echo "<td class='color'>" . htmlspecialchars($row['nom_athlete']) . "</td>"; // Utilisation de la classe 'color' pour changer la couleur
                    echo "<td class='color'>" . htmlspecialchars($row['prenom_athlete']) . "</td>"; // Utilisation de la classe 'color' pour changer la couleur
                    echo "<td class='color'>" . htmlspecialchars($row['id_pays']) . "</td>"; // Utilisation de la classe 'color' pour changer la couleur
                    echo "<td class='color'>" . htmlspecialchars($row['id_genre']) . "</td>"; // Utilisation de la classe 'color' pour changer la couleur
                    echo "<td><button onclick='openModifyPlacesForm(\"{$row['id_athlete']}\", \"{$row['nom_athlete']}\", \"{$row['prenom_athlete']}, \"{$row['id_pays']}, \"{$row['id_genre']}\")'>Modifier</button></td>";
                    echo "<td><button onclick='deletePlacesConfirmation(\"{$row['id_athlete']}\", \"{$row['nom_athlete']}\", \"{$row['prenom_athlete']}, {$row['id_pays']}, \"{$row['id_genre']}\")'>Supprimer</button></td>";
                    echo "</tr>";
                }

                echo "</table>";
            } else {
                echo "<p>Aucun sport trouvé.</p>";
            }
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
        // Afficher les erreurs en PHP
// (fonctionne à condition d’avoir activé l’option en local)
        error_reporting(E_ALL);
        ini_set("display_errors", 1);
        ?>
        <p class="paragraph-link">
            <a class="link-home" href="../admin.php">Retour administration</a>
        </p>

    </main>

    <footer>
        <figure>
            <img src="../../../img/logo-jo-2024.png" alt="logo jeux olympiques 2024">
        </figure>
    </footer>
    <script>
        function openAddAthleteForm() {
            // Ouvrir une fenêtre pop-up avec le formulaire de modification
            // L'URL contien un paramètre "id"
            window.location.href = 'add-athletes.php';
        }

        function openModifyAthleteForm(id_athlete) {
            // Ajoutez ici le code pour afficher un formulaire stylisé pour modifier un sport
            // alert(id_sport);
            window.location.href = 'modify-athletes.php?id_athlete=' + id_athlete;
        }

        function deleteAthleteConfirmation(id_athlete) {
            // Ajoutez ici le code pour afficher une fenêtre de confirmation pour supprimer un sport
            if (confirm("Êtes-vous sûr de vouloir supprimer ce sport?")) {
                // Ajoutez ici le code pour la suppression du sport
                // alert(id_sport);
                window.location.href = 'delete-athletes.php?id_athlete=' + id_athlete;
            }
        }
    </script>
</body>

</html>