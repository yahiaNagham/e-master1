<?php
session_start(); // بدء الجلسة لتخزين معرّف المستخدم

if (!isset($_SESSION['user_id'])) {
  header("Location: conx.php");
  exit;
}

require_once '../config/db.php';

$user_id = $_SESSION['user_id'];

// جلب بيانات المستخدم من جدول userr
try {
    $sql = "SELECT * FROM userr WHERE id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

if (!$user) {
  die("Utilisateur non trouvé.");
}

// جلب بيانات الطالب من جدول etudiant باستخدام id_utilisateur
try {
  $sql = "SELECT * FROM etudiant WHERE id_utilisateur = :user_id";
  $stmt = $conn->prepare($sql);
  $stmt->execute([':user_id' => $user_id]);
  $etudiant = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Erreur : " . $e->getMessage());
}

if (!$etudiant) {
  die("Aucune information d'étudiant trouvée.");
}

?>

<!DOCTYPE HTML>
<html>
<head>
  <title>E-Master</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
  <link rel="stylesheet" href="./profile.css" />
  <link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="is-preload">
  <div id="wrapper">
    <div id="main">
      <div class="inner">
        <nav class="navbar">
          <div class="navbar-left">
            <img src="../assets/images/pic12.jpg" alt="Logo" class="logo">
            <span class="brand-name"><a href="./etudiant.php">E-Master</a></span>
          </div>
          <div class="navbar-right">
            <div class="icon-container">
              <a href=""><i class="fa-solid fa-bell" id="icon"></i></a>
              <a href=""><i class="fa-solid fa-message" id="icon"></i></a>
            </div>
            <div class="profile-menu">
              <img src="../assets/images/10.jpg" alt="Profile" class="profile-pic">
              <div class="dropdown-menu">
                <ul>
                  <li><a href="./profile.php"><i class="fa-regular fa-user"></i>  Profile</a></li>
                  <li><a href="./modifier.php"><i class="fa-regular fa-pen-to-square"></i>  Modifier profile</a></li>
                  <li><a href="#logout"><i class="fa-solid fa-right-to-bracket"></i> Deconnexion</a></li>
                </ul>
              </div>
            </div>
          </div>
        </nav>
        <header style="border-bottom: solid 5px #3a7e3e;"></header>

        <section class="profile-container">
          <div class="profile-header">
            <img src="../assets/images/10.jpg" alt="Photo de Profil" class="profile-photo">
            <h1><?php echo $user['prenom'] . ' ' . $user['nom']; ?></h1>
            <p class="bio">Université Constantine 2-abdelhamid mehri</p>
          </div>

          <div class="profile-details">
            <h2>Informations Personnelles</h2>
            <ul>
              <li><strong>Nom :</strong> <?php echo $user['nom']; ?></li>
              <li><strong>Prénom :</strong> <?php echo $user['prenom']; ?></li>
              <li><strong>Email :</strong> <?php echo $user['email']; ?></li>
              <li><strong>Université :</strong> Université Abdelhamid Mehri</li>
              <li><strong>Faculté :</strong> Ntic</li>
              <li><strong>Spécialité :</strong> RSD</li>
            </ul>
          </div>

          <div class="profile-courses">
            <h2>Domaines et compétences :</h2>
            <ul>
              <li><strong>Domain :</strong> <?php echo $etudiant['domaine']; ?></li>
              <li><strong>Compétences :</strong> <?php echo $etudiant['competences']; ?></li>
              <li><strong>Langues :</strong> <?php echo $etudiant['languages']; ?></li>
            </ul>
          </div>
          
          <div class="inputbox">
            <button><a href="./modifier.php">Modifier Mon Profile</a></button>
          </div>
        </section>
      </div>
    </div>

    <div id="sidebar">
      <div class="inner">
        <section id="search" class="alt">
          <form method="post" action="#">
            <input type="text" name="query" id="query" placeholder="Search" />
          </form>
        </section>

        
        <nav id="menu">
                <header class="major">
                  <h2>Gestion des Projet</h2>
                </header>
                <ul>
                  <li><a href="./etudiant.php">Accueil</a></li>
                  <li>
                    <span class="opener">Exploration et candidature</span>
                    <ul>
                      <li class="liste"><a href="./consulter.php">Consulter les projets</a></li>
                      <li class="liste"><a href="./selection.php"> la sélection de projets et équipes</a></li>
                    </ul>
                  </li>
                  <li><a href="./condidateur.php"> Mes candidatures</a></li>
                  
                  <li>
                    <span class="opener">Communication et collaboration</span>
                    <ul>
                      <li class="liste"><a href="./message.php"> Message</a></li>
                      <li class="liste"><a href="./historique.php">Historique des échanges</a></li>
                    </ul>
                  </li>
                  <li><a href="./satistique.php">Statistiques et tableau de bord</a></li>
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
  </script>
</body>
</html>
