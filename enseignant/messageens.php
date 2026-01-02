
<?php
session_start();

// Include the database connection
require_once '../config/db.php';

// Vérification si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_expediteur = 1; // Récupérer l'id de l'expéditeur (doit être dynamique en fonction de l'utilisateur connecté)
    $recipient = $_POST['recipient'];
    $specific_recipient = $_POST['specific-recipient'];
    $message_content = $_POST['message'];

    // Si un destinataire spécifique est sélectionné
    if ($recipient == "etudiant" || $recipient == "enseignant") {
        // Requête pour récupérer l'id du destinataire spécifique
        $stmt = $pdo->prepare("SELECT id FROM userr WHERE nom = :nom and type=:type");
       
        $stmt->execute([
            ':nom' => $specific_recipient,
            ':type' => $recipient
        ]);
        $recipient_data = $stmt->fetch();

        if ($recipient_data) {
            $id_destinataire = $recipient_data['id'];

            // Insertion du message dans la base de données
            $stmt = $pdo->prepare("INSERT INTO message (id_expediteur, id_destinataire, contenu) VALUES (:id_expediteur, :id_destinataire, :contenu)");
            $stmt->execute([
                ':id_expediteur' => $id_expediteur,
                ':id_destinataire' => $id_destinataire,
                ':contenu' => $message_content
            ]);

            echo "Message envoyé avec succès.";
        } else {
            echo "Destinataire non trouvé.";
        }
    } else {
        echo "Veuillez sélectionner un destinataire valide.";
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>E-Master</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />

    <link rel="stylesheet" href="./messageens.css" />
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
              <img src="../assets/images/10.jpg" alt="Profile"
                class="profile-pic">
              <span class="profile-name">Bouramoul Abdelkarim</span>
              <div class="dropdown-menu">
                <ul>
                  <li><a href="./profileens.php"><i class="fa-regular fa-user"></i> Profile</a></li>
                  <li><a href="./modifierens.php"><i class="fa-regular fa-pen-to-square"></i> Modifier profiel</a></li>

                  <li><a href="../deconx.php"><i class="fa-solid fa-right-to-bracket"></i> Deconnexion</a></li>
                </ul>
              </div>
            </div>
          </div>
        </nav>
            <header style="border-bottom: solid 5px #004d99;"></header>

            <!-- Content -->
            <section>
                <section class="communication">
                    <div class="message-form">
                        <h2>Envoyer un message</h2>
                        <form action="messageens.php" method="POST">
                            <label for="recipient">Destinataire:</label>
                            <select id="recipient" name="recipient">
                                <option value="" disabled selected>Choisir le type de destinataire</option>
                                <option value="etudiant">Étudiant</option>
                                <option value="enseignant">Enseignant</option>
                                <option value="administrateur">Administrateur</option>
                            </select>

                            <div id="additional-field" style="display: none;">
                                <label for="specific-recipient">Nom du destinataire:</label>
                                <input type="text" id="specific-recipient" name="specific-recipient" placeholder="Entrez le nom...">
                            </div>

                            <label for="message">Message:</label>
                            <textarea id="message" name="message" rows="4" placeholder="Écrivez votre message ici..."></textarea>
                            <button type="submit">Envoyer</button>
                        </form>
                    </div>
                </section>
            </section>
        </div>
    </div>

    <!-- Sidebar -->
    <div id="sidebar">
        <div class="inner">
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
              <li><a href="./enseignant.php">Accueil</a></li>
              <li>
                <span class="opener">Exploration et Gestion des projets</span>
                <ul>
                  <li class="liste"><a href="./gestionens.php">Gestion des projets</a></li>
                  <li class="liste"><a href="./exploration.php"> Exploration</a></li>
                </ul>
              </li>
              <li><a href="./condidateur.php"> Mes candidatures</a></li>
              
              <li>
                <span class="opener">Communication et collaboration</span>
                <ul>
                  <li class="liste"><a href="./messageens.php"> Message</a></li>
                  <li class="liste"><a href="./historiqueens.php">Historique des échanges</a></li>
                </ul>
              </li>
              <li><a href="./statistiqueens.php">Statistiques et tableau de bord</a></li>
            </ul>
          </nav>


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
        const dropdownMenu = document.querySelector(".dropdown-menu");

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

    document.addEventListener("DOMContentLoaded", () => {
        const recipientSelect = document.getElementById("recipient");
        const additionalField = document.getElementById("additional-field");

        recipientSelect.addEventListener("change", () => {
            if (recipientSelect.value === "etudiant" || recipientSelect.value === "enseignant") {
                additionalField.style.display = "block";
            } else {
                additionalField.style.display = "none";
            }
        });
    });
</script>

</body>
</html>
