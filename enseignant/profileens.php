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

// جلب بيانات الطالب من جدول etudiant
try {
  $sql = "SELECT * FROM enseignant WHERE id_enseignant = :user_id";
  $stmt = $conn->prepare($sql);
  $stmt->execute([':user_id' => $user_id]);
  $etudiant = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Erreur : " . $e->getMessage());
}


?>


<!DOCTYPE HTML>
<html>
	<head>
		<title>E-Master</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />

		<link rel="stylesheet" href="./profileens.css" />

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
									<section class="profile-container">
                    <div class="profile-header">
                        <img src="../assets/images/10.jpg" alt="Photo de Profil" class="profile-photo">
                        <h1><?php echo $user['prenom'] . ' ' . $user['nom']; ?></h1>
                        <p class="bio">Université Constantine 2-abdelhamid mehri</p>
                    </div>
            
                    <div class="profile-details">
                    <div class="profile-details">
                        <h2>Informations Personnelles</h2>
                        <ul>
                            <li><strong>Nom :</strong> <?php echo  $user['nom']; ?></h1>  </li>
                            <li><strong>Prénom :</strong> <?php echo $user['prenom']; ?></li>
                            <li><strong>Email :</strong><?php echo $user['email']; ?></li>
                            <li><strong>Université :</strong> Université Abdelhamid Mehri</li>
                            <li><strong>Faculté :</strong> Ntic</li>
                        </ul>
                    </div>
                    <div class="profile-courses">
                        <h2>Diplomes et Education :</h2>
                        <ul>
                            <li><strong>- Bacalaureat du lycée Ziadia,Constantine , Algerie</strong></li>
                            <li><strong>- Master2 de l'Université Paris 11, Orsay, France </strong></li>
                            <li><strong>- Magistère de l'Université Larbi Ben Mhidi, Oum El-Bouaghi, Algerie</strong></li>
                            <li><strong>- Doctorat de l'Université de Constantine, Algerie et CentraleSuplelèc, France</strong></li>
                        </ul>
                    </div>
                    <div class="skills">
                        <h2>Experiences :</h2>
                        <ul>
                        <li><strong>- Professeur titulaire à la faculté NTIC, Université Constantine 2 - Algérie</strong></li>
                            <li><strong>- Membre du laboratoire MISC, Université Constantine 2 - Algérie</strong></li>
                            <li><strong>- En charge de l'animation scientifique et de la communication à la Faculté NTIC, Université de Constantine 2</strong></li>
                        </ul>
                    </div>

                    <div class="inputbox">
                     <button><a href="./modifierens.php">Modifier Mon Profile</a></button>
                         
                    </div>
                </section>
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

      