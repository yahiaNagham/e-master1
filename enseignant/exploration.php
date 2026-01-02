<?php
// Connexion à la base de données
session_start();

require_once '../config/db.php';


// Fonction pour récupérer les étudiants inscrits
function getStudents($pdo) {
  $query = "SELECT u.nom, u.prenom, e.competences FROM user u
            JOIN etudiant e ON u.id_user = e.id_user
            WHERE u.role = 'etudiant'"; 
  $stmt = $pdo->prepare($query);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour récupérer les candidatures reçues pour un projet
function getCandidatures($pdo) {
  $query = "SELECT c.id_candidature, g.nom_grp, p.titre AS projet, c.status 
            FROM candidature c
            JOIN projet p ON c.id_projet = p.id_projet
            JOIN etudiant e ON c.id_etudiant = e.id_etudiant
            JOIN groupe g ON e.id_grp = g.id_grp"; 
  $stmt = $pdo->prepare($query);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Traitement des actions d'acceptation ou de rejet
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['action'], $_POST['groupe'], $_POST['projet'])) {
    $action = $_POST['action'];  
    $groupe = $_POST['groupe'];
    $projet = $_POST['projet'];

    if ($action === 'accept') {
      $query = "UPDATE candidature SET status = 'acceptee' WHERE id_projet = :projet AND id_etudiant IN (SELECT id_etudiant FROM etudiant WHERE id_grp = :groupe)";
    } elseif ($action === 'reject') {
      $query = "UPDATE candidature SET status = 'rejettee' WHERE id_projet = :projet AND id_etudiant IN (SELECT id_etudiant FROM etudiant WHERE id_grp = :groupe)";
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute(['projet' => $projet, 'groupe' => $groupe]);

    echo json_encode(['status' => 'success', 'message' => 'Action effectuée avec succès.']);
    exit;
  }
}

// Récupération des données pour la page
$etudiant = getStudents($pdo);
$candidature = getCandidatures($pdo);

// Optionally, return data in JSON for AJAX requests (or use below for HTML rendering)
header('Content-Type: application/json');
echo json_encode([
  'students' => $etudiant,  // Correct variable name here
  'candidature' => $candidature
]);
?>

<!DOCTYPE HTML>
<html>

<head>
  <title>E-Master</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />

  <link rel="stylesheet" href="./exploration.css" />

  <link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body class="is-preload">
  <form action="exploation.php" method="post"></form>
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

          <header>
<h2>Exploration et Gestion des Candidatures</h2>
          </header>
          <div class="etudiants-list">
            <h2>Étudiants Inscrits</h2>
            <table>
              <thead>
                <tr>
                  <th>Nom</th>
                  <th>Prenom</th>
                  <th>Competences</th>
                  <th>Profil</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Bensaad </td>
                  <td>Soundous</td>
                  <td>JavaScript, HTML, CSS</td>
                  <td><button class="btn"><a href="voirprofile.php">Voir Profil</a></button></td>
                </tr>
                <tr>
                  <td>Manaa</td>
                  <td>Assala</td>
                  <td>Python, Data Science</td>
                  <td><button class="btn"><a href="#">Voir Profil</a></button></td>
                </tr>
                <!-- Autres étudiants peuvent être ajoutés ici -->
              </tbody>
            </table>
          </div>

          <!-- Candidatures Reçues -->
          <div class="candidatures-reçues">
            <h2>Candidatures Reçues pour Projet</h2>
            <table>
              <thead>
                <tr>
                  <th>Groupe</th>
                  <th>Projet</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Yahia Naghem et Douas Souha</td>
                  <td>Développement Web</td>
                  <td>
                    <button class="accept">Accepter</button>
                    <button class="reject">Refuser</button>
                  </td>
                </tr>
                <tr>
                  <td>Manaa Assala, Marmi Anfel , Yaici Anfel</td>
                  <td>Data Science</td>
                  <td>
                    <button class="accept">Accepter</button>
                    <button class="reject">Refuser</button>
                  </td>
                </tr>
                <!-- Autres candidatures peuvent être ajoutées ici -->
              </tbody>
            </table>
          </div>

          <div class="confirmation">
            <p>Une fois que vous avez accepté un binôme, vous recevrez une confirmation de l'attribution du projet.</p>
          </div>



        </section>




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
    </script>



  </div>
</body>

</html>        