<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/styles-computer.css">
    <link rel="stylesheet" href="../css/styles-responsive.css">
    <link rel="shortcut icon" href="../img/favicon-jo-2024.ico" type="image/x-icon">
    <title>Calendrier des évènements - Jeux Olympiques 2024</title>

    <style>
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
                <li><a href="../index.php">Accueil</a></li>
                <li><a href="sports.php">Sports</a></li>
                <li><a href="events.php">Calendrier des évènements</a></li>
                <li><a href="results.php">Résultats</a></li>
                <li><a href="login.php">Accès administrateur</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Résultats</h1>
        <?php
        require_once("../database/database.php");

        try {
            // Requête pour récupérer la liste des sports depuis la base de données
            $query = "SELECT * FROM PARTICIPER ORDER BY id_athlete, id_epreuve, resultat";
            $statement = $connexion->prepare($query);
            $statement->execute();

            // Vérifier s'il y a des résultats
            if ($statement->rowCount() > 0) {
                echo "<table>";
                echo "<tr>";
                echo "<th class='couleur'>id_athlete</td>";
                echo "<th class='couleur'>id_epreuve</td>";
                echo "<th class='couleur'>resultat</td>";
                echo "</tr>";

                // Afficher les données dans un tableau
                while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td class='color'>" . htmlspecialchars($row['id_athlete']) . "</td>"; // Utilisation de la classe 'color' pour changer la couleur
                    echo "<td class='color'>" . htmlspecialchars($row['id_epreuve']) . "</td>"; // Utilisation de la classe 'color' pour changer la couleur
                    echo "<td class='color'>" . htmlspecialchars($row['resultat']) . "</td>"; // Utilisation de la classe 'color' pour changer la couleur
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
            <a class="link-home" href="../index.php">Retour Accueil</a>
        </p>

    </main>
    <footer>
        <figure>
            <img src="../img/logo-jo-2024.png" style="width: 90%; margin: 10px auto;" alt="logo jeux olympiques 2024">
        </figure>
    </footer>
</body>

</html>