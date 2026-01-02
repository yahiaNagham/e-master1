
<?php
session_start();
if ($_SESSION['user_type'] !== 'enseignant') {
    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>E-Master</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="./enseignant.css">

    <link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    

	</head>
  <body class="is-preload">

<!-- Wrapper -->
  <div id="wrapper">

    <!-- Main -->
      <div id="main">
        <div class="inner">
        
          <nav class="navbar">
            <div class="navbar-left">
              <img src="../assets/images/pic12.jpg" alt="Logo" class="logo">
              <span class="brand-name"><a href="./etudiant.php">E-Master</a></span>
            </div>
            <div class="navbar-right">
              <div class="icon-container">
              <a href=""> <i class="fa-solid fa-bell" id="icon"></i></a> 
              <a href=""> <i class="fa-solid fa-message" id="icon"></i></a> 
              </div>
              <div class="profile-menu">
                <img src="../assets/images/10.jpg" alt="Profile" class="profile-pic">
               <span class="profile-name">Bouamoul Abdelkarim</span>
                <div class="dropdown-menu">
                  <ul>
                    <li><a href="./profileens.php"><i class="fa-regular fa-user"></i>  Profile</a></li>
                    <li><a href="./modifierens.php"><i class="fa-regular fa-pen-to-square"></i>  Modifier profiel</a></li>
                    
                    <li><a href="../deconx.php"><i class="fa-solid fa-right-to-bracket"></i>  Deconnexion</a></li>
                  </ul>
                </div>
              </div>
            </div>
          </nav>
          <header style="border-bottom: solid 5px #004d99;"></header>


          <!-- Banner -->
            <section id="banner">
              <div class="content">
                <header>
                  <h1>Bienvenue</h1>
                   <p class="text">Platforme pour la gestion des projet    <span> "E-Master"</span></p>
                </header>
                <h3>عزيزي الأستاذ , تهانينا الحارة و مرحبا بك في الاسرة الجامعية</h3>
                <h3 class="text-h">Cher enseignant,toutes nos Felicitations et Bienvenue dans la communaute universitaire</h3>
                <h3 class="text-h">Dear Teacher,Congratulation and Welcome to the University community</h3>
              </div>
              <span class="image object">
                <img src="../assets/images/pic14.jpg" alt=""/>
              </span>
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
                  <li class="liste"><a href="./message.php"> Message</a></li>
                  <li class="liste"><a href="./historique.php">Historique des échanges</a></li>
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




