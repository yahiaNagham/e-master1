<?php
// Database connection
require_once '../config/db.php';
session_start();

// Handle form submission for approve or reject
if (isset($_POST['action']) && isset($_POST['project_id'])) {
    $project_id = $_POST['project_id'];
    $action = $_POST['action'];

    // Determine the new status based on the action
    if ($action == 'approve') {
        $new_status = 'Acceptée';
    } elseif ($action == 'reject') {
        $new_status = 'Rejetée';
    } else {
        $new_status = 'En attente'; // Default status if something goes wrong
    }

    // Update the status in the database
    $update_sql = "UPDATE candidats SET statut = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bindParam(1, $new_status, PDO::PARAM_STR);
    $stmt->bindParam(2, $project_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<p>Status updated successfully</p>";
    } else {
        echo "<p>Error updating status: " . implode(", ", $stmt->errorInfo()) . "</p>";
    }

    $stmt->closeCursor();
}

// Fetch projects from the database
$sql = "SELECT id, name, projet_name, statut FROM candidats";
$stmt = $conn->prepare($sql);
$stmt->execute();

// Check if there are results
if ($stmt->rowCount() > 0) {
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $result = [];
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>E-Master</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="./selection.css" />
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="is-preload">

<!-- Wrapper -->
<div id="wrapper">

    <!-- Main -->
    <div id="main">
        <div class="inner">

            <!-- Header -->
            <nav class="navbar">
                <div class="navbar-left">
                    <img src="../assets/images/pic12.jpg" alt="Logo" class="logo">
                    <span class="brand-name"><a href="">E-Master</a></span>
                </div>
                <div class="navbar-right">
                    <div class="icon-container">
                        <a href=""> <i class="fa-solid fa-bell" id="icon"></i></a> 
                        <a href=""> <i class="fa-solid fa-message" id="icon"></i></a> 
                    </div>
                    <div class="profile-menu">
                        <img src="../assets/images/10.jpg" alt="Profile" class="profile-pic">
                        <div class="dropdown-menu">
                            <ul>
                                <li><a href="#logout"><i class="fa-solid fa-right-to-bracket"></i> Déconnexion</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
            <header style="border-bottom: solid 5px #3a7e3e;"></header>

            <!-- Content -->
            <section>
                <header class="main">
                    <h2>Statut des candidats</h2>
                </header>

                <!-- Table of Projects -->
                <table>
                    <thead>
                        <tr>
                            <th>Nom de l'Étudiant</th>
                            <th>Projet Soumis</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php
                    if (count($result) > 0) {
                        // Output data for each project
                        foreach ($result as $row) {
                            echo "<tr>
                                    <td>{$row['name']}</td>
                                    <td>{$row['projet_name']}</td>
                                    <td>{$row['statut']}</td>
                                    <td class='actions'>
                                        <form method='POST' action=''>
                                            <input type='hidden' name='project_id' value='{$row['id']}'>
                                            <button type='submit' name='action' value='approve'" . ($row['statut'] == 'Acceptée' ? " disabled" : "") . ">Approuver</button>
                                            <button type='submit' name='action' value='reject'" . ($row['statut'] == 'Rejetée' ? " disabled" : "") . ">Rejeter</button>
                                        </form>
                                    </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>Aucun projet trouvé</td></tr>";
                    }
                    ?>
                              
                    </tbody>
                </table>

            </section>

        </div>
    </div>

    <!-- Sidebar -->
    <div id="sidebar">
        <div class="inner">

            <!-- Search -->
            <section id="search" class="alt">
                <form method="post" action="#">
                    <input type="text" name="query" id="query" placeholder="Search" />
                </form>
            </section>

            <!-- Menu -->
            <nav id="menu">
                <header class="major">
                    <h2>Gestion des Projets</h2>
                </header>
                <ul>
                    <li><span class="opener">Supervision des Projets et des Candidatures</span>
                        <ul>
                            <li class="liste"><a href="consulterProj.php">Consulter les projets</a></li>
                            <li class="liste"><a href="selection.php">Consulter candidats</a></li>
                        </ul>
                    </li>
                    <li><span class="opener">Gestion des conflits</span>
                        <ul>
                            <li class="liste"><a href="modification.php">Modification de la Composition des Groupes</a></li>
                            <li class="liste"><a href="Réattribution.php">Réattribution des Projets</a></li>
                        </ul>
                    </li>
                    <li><a href="settings_backend.php">Paramètres Pédagogiques</a></li>
                    <li><a href="gestion des compte.php">Gestion des comptes utilisateurs</a></li>
                    <li><a href="liste.php">listes des  utilisateurs</a></li>
                    <li>
                        <span class="opener">Communication et collaboration</span>
                        <ul>
                            <li class="liste"><a href="message.html">Message</a></li>
                            <li class="liste"><a href="historique.html">Historique des échanges</a></li>
                        </ul>
                    </li>
                    <li><a href="statistique.html">Statistiques et tableau de bord</a></li>
                </ul>
            </nav>

            <!-- Contact -->
            <section>
                <header class="major">
                    <h2>Contact</h2>
                </header>
                <ul class="contact">
                    <li class="icon solid fa-envelope"><a href="#">support@plateforme-projets.com</a></li>
                    <li class="icon solid fa-phone">Téléphone : +213 555 123 456</li>
                </ul>
            </section>

            <!-- Footer -->
            <footer id="footer">
                <p>© 2024 Plateforme Projets - Tous droits réservés</p>
            </footer>

        </div>
    </div>
</div>

<!-- Scripts -->
<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/browser.min.js"></script>
<script src="../assets/js/breakpoints.min.js"></script>
<script src="../assets/js/util.js"></script>
<script src="../assets/js/main.js"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const profileMenu = document.querySelector(".profile-menu");
  

    profileMenu.addEventListener("click", () => {
        dropdownMenu.style.display =
            dropdownMenu.style.display === "block" ? "none" : "block";
    });

    window.addEventListener("click", (e) => {
        if (!profileMenu.contains(e.target)) {
            dropdownMenu.style.display = "none";
        }
    });
});
</script>

</body>
</html>
