<?php
// Connexion à la base de données
$servername = "localhost"; // Remplacez par votre serveur de base de données
$username = "root"; // Votre nom d'utilisateur
$password = ""; // Votre mot de passe
$dbname = "daw"; // Le nom de votre base de données

// Création de la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("La connexion a échoué: " . $conn->connect_error);
}

// Vérification de la soumission du formulaire pour ajouter un projet
if (isset($_POST['add_project'])) {
    $project_name = $_POST['project_name'];
    $description = $_POST['description'];
    $mots_cles = $_POST['mots_cles'] ?? ''; // Mots-clés peuvent être vide

    // Insertion du projet dans la base de données
    $stmt = $conn->prepare("INSERT INTO projets (id_enseignant, project_name, description, mots_cles) VALUES (?, ?, ?, ?)");
    $id_enseignant = 6; // Remplacez par l'ID réel de l'enseignant connecté (récupéré de la session ou autre)
    $stmt->bind_param("isss", $id_enseignant, $project_name, $description, $mots_cles);

    if ($stmt->execute()) {
        echo "Le projet a été ajouté avec succès!";
    } else {
        echo "Erreur: " . $stmt->error;
    }

    $stmt->close();
}

// Vérification de la demande de suppression
if (isset($_GET['delete_project_id'])) {
    $project_id = $_GET['delete_project_id'];
    
    // Suppression du projet
    $delete_query = "DELETE FROM projets WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $project_id);
    if ($stmt->execute()) {
        echo "Le projet a été supprimé avec succès!";
    } else {
        echo "Erreur lors de la suppression: " . $stmt->error;
    }
    $stmt->close();
}

// Récupérer les projets de la base de données
$projets_query = "SELECT * FROM projets WHERE id_enseignant = 6"; // Remplacez par l'ID réel de l'enseignant
$projets_result = $conn->query($projets_query);

// Fermer la connexion à la base de données
$conn->close();
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>E-Master</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="./gestionens.css" />
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
                            <img src="../assets/images/user.jpg"
                                alt="Profile" class="profile-pic">
                            <span class="profile-name">bouramoul Abdelkarim</span>
                            <div class="dropdown-menu">
                                <ul>
                                    <li><a href="profileens.php"><i class="fa-regular fa-user"></i> Profile</a></li>
                                    <li><a href="modifierens.php"><i class="fa-regular fa-pen-to-square"></i>
                                            Modifier profile</a></li>

                                    <li><a href="../deconx.php"><i class="fa-solid fa-right-to-bracket"></i> Deconnexion</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
                <header style="border-bottom: solid 5px #004d99;"></header>

                <!-- Content -->
                <div class="container">
                    <h1>Gestion des Projets</h1>

                    <!-- Formulaire pour ajouter un projet -->
                    <div class="section">
                        <h2>Proposer un Nouveau Projet</h2>
                        <form method="POST" action="gestionens.php">
                            <label for="project_name">Titre du Projet :</label>
                            <input type="text" id="project_name" name="project_name" placeholder="Entrez le titre du projet" required>

                            <label for="description">Description :</label>
                            <textarea id="description" name="description" rows="5"
                                placeholder="Entrez une description détaillée" required></textarea>

                            <label for="mots_cles">Mots-clés :</label>
                            <input type="text" id="mots_cles" name="mots_cles" placeholder="Exemple : IA, Web, Sécurité">

                            <button type="submit" name="add_project">Proposer le Projet</button>
                        </form>
                    </div>

                    <!-- Affichage des projets -->
                    <div class="section">
                        <h2>Projets Créés</h2>
                        <div class="projects-list">
                            <?php 
                            if ($projets_result->num_rows > 0) {
                                while ($project = $projets_result->fetch_assoc()) {
                                    echo "<div class='project'>";
                                    echo "<h3>Titre : " . htmlspecialchars($project['project_name']) . "</h3>";
                                    echo "<p>Description : " . htmlspecialchars($project['description']) . "</p>";
                                    echo "<p>Mots-clés : " . htmlspecialchars($project['mots_cles']) . "</p>";
                                    echo "<p>Statut : " . htmlspecialchars($project['etat']) . "</p>";
                                    echo "<div class='actions'>";
                                    echo "<a href='?delete_project_id=" . $project['id'] . "'>Supprimer</a>";
                                    echo "</div></div>";
                                }
                            } else {
                                echo "<p>Aucun projet créé.</p>";
                            }
                            ?>
                        </div>
                        <div class="projects-list">
                            <?php 
                            if ($projets_result->num_rows > 0) {
                                while ($project = $projets_result->fetch_assoc()) {
                                    echo "<div class='project'>";
                                    echo "<h3>Titre : " . htmlspecialchars($project['project_name']) . "</h3>";
                                    echo "<p>Description : " . htmlspecialchars($project['description']) . "</p>";
                                    echo "<p>Mots-clés : " . htmlspecialchars($project['mots_cles']) . "</p>";
                                    echo "<p>Statut : " . htmlspecialchars($project['etat']) . "</p>";
                                    echo "<a href='?delete_project_id=" . $project['id'] . "'>Supprimer</a>";
                                    echo "</div></div>";
                                }
                            } else {
                                echo "<p>Aucun projet créé.</p>";
                            }
                            ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>

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
                        <h2>Gestion des Projet</h2>
                    </header>
                    <ul>
                        <li><a href="enseignant.php">Accueil</a></li>

                        <li>
                            <span class="opener">Exploration et candidature</span>
                            <ul>
                                <li class="liste"><a href="gestionens.php">Gestion des projets</a></li>
                                <li class="liste"><a href="exploationens.php"> Exploration </a></li>
                            </ul>
                        </li>

                        <li>
                            <span class="opener">Communication et collaboration</span>
                            <ul>
                                <li class="liste"><a href="messageens.php"> Message</a></li>
                                <li class="liste"><a href="historiqueens.php">Historique des échanges</a></li>
                            </ul>
                        </li>
                        <li><a href="statistique.php">Statistiques et tableau de bord</a></li>
                    </ul>
                </nav>

                <section>
                    <header class="major">
                        <h2>Contact</h2>
                    </header>
                    <ul class="contact">
                        <li class="icon solid fa-envelope"><a href="#"> support@plateforme-projets.com</a></li>
                        <li class="icon solid fa-phone"> Téléphone : +213 555 123 456</li>
                    </ul>
                </section>

                <!-- Footer -->
                <footer id="footer">
                    <p>© 2024 Plateforme Projets - Tous droits réservés</p>
                </footer>
            </div>
        </div>

        <!-- Scripts -->
        <script src="../assets/js/jquery.min.js"></script>
        <script src="../assets/js/browser.min.js"></script>
        <script src="../assets/js/breakpoints.min.js"></script>
        <script src="../assets/js/util.js"></script>
        <script src="../assets/js/main.js"></script>

    </div>
</body>

</html>

