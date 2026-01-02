<?php
session_start(); // Démarrage de la session pour stocker l'identifiant de l'utilisateur

if (!isset($_SESSION['user_id'])) {
    header("Location: conx.php");
    exit;
}

require_once '../config/db.php';

$user_id = $_SESSION['user_id'];

// Récupération des données utilisateur depuis la table userr
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

// Récupération des données de l'étudiant depuis la table etudiant
try {
    $sql = "SELECT * FROM etudiant WHERE id_utilisateur = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);
    $etudiant = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

if (!$etudiant) {
    die("Aucune information sur l'étudiant trouvée.");
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $etudiant2_nom = isset($_POST['etudiant2_nom']) ? $_POST['etudiant2_nom'] : null;
        $etudiant2_nom = isset($_POST['etudiant2_prenom']) ? $_POST['etudiant2_prenom'] : null;
        $etudiant3_nom = isset($_POST['etudiant3_nom']) ? $_POST['etudiant3_nom'] : null;
        $etudiant3_nom = isset($_POST['etudiant3_prenom']) ? $_POST['etudiant3_nom'] : null;

        // Exemple d'enregistrement des données dans la base de données
        try {
            $sql = "INSERT INTO etudiant (nom, prenom, specialite, type_equipe, etudiant2_nom, etudiant3_nom) 
                    VALUES (:nom, :prenom, :specialite, :type_equipe, :etudiant2_nom, :etudiant3_nom)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':nom' => $_POST['nom'],
                ':prenom' => $_POST['prenom'],
                ':specialite' => $_POST['specialite'],
                ':type_equipe' => $_POST['type_equipe'],
                ':etudiant2_nom' => $etudiant2_nom,
                ':etudiant3_nom' => $etudiant3_nom,
            ]);
        } catch (PDOException $e) {
            die("Erreur : " . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE HTML>
<html lang="fr">

<head>
    <title>E-Master</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <link rel="stylesheet" href="./selection.css">
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
                        <span class="brand-name"><a href="./etudiant.php">E-Master</a></span>
                    </div>
                    <div class="navbar-right">
                        <div class="icon-container">
                            <a href="#"><i class="fa-solid fa-bell" id="icon"></i></a>
                            <a href="#"><i class="fa-solid fa-message" id="icon"></i></a>
                        </div>
                        <div class="profile-menu">
                            <img src="../assets/images/10.jpg" alt="Profile" class="profile-pic">
                            <span class="profile-name"><?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></span>
                            <div class="dropdown-menu">
                                <ul>
                                    <li><a href="./profile.php"><i class="fa-regular fa-user"></i> Profil</a></li>
                                    <li><a href="./modifier.php"><i class="fa-regular fa-pen-to-square"></i> Modifier profil</a></li>
                                    <li><a href="../deconx.php"><i class="fa-solid fa-right-to-bracket"></i> Déconnexion</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>

                <header style="border-bottom: solid 5px #3a7e3e;"></header>

                <!-- Content -->
                <section>
                    <header class="main">
                        <p>Après avoir examiné et analysé les projets disponibles, veuillez remplir le formulaire ci-dessous :</p>
                    </header>

                    <!-- Formulaire -->
                    
                    <div class="form">
    <div class="title-div">
        <h2>Formulaire</h2>
        <p>Vous pouvez utiliser cette formulaire pour choisir votre binôme ou trinôme, ainsi que le projet sur lequel vous souhaitez travailler.</p>
        <p id="required">*Requis</p>
    </div>

     
    <form action="submit_form.php" method="POST">
        <!-- حقول النموذج -->
        <div class="name-div">
            <div class="name">Nom:<span class="required">*</span></div>
            <div class="input-div"><input type="text" name="nom" placeholder="Ecrire votre nom" required></div>
        </div>

        <div class="college-div">
            <div class="name">Prenom:<span class="required">*</span></div>
            <div class="input-div"><input type="text" name="prenom" placeholder="Ecrire votre prenom" required></div>
        </div>

        <div class="gmail-div">
            <div class="name">Specialite:<span class="required">*</span></div>
            <div class="input-div"><input type="text" name="specialite" placeholder="Ecrire votre Specialite" required></div>
        </div>

        <div class="mobile-div">
            <div class="name">Choisissez le nombre de l'équipe:<span class="required">*</span></div>
            <div class="chek1">
                <input type="radio" id="solo" name="type_equipe" value="solo" onclick="showForm('solo')" required> Monôme <br>
                <input type="radio" id="pair" name="type_equipe" value="pair" onclick="showForm('pair')"> Binôme <br>
                <input type="radio" id="trio" name="type_equipe" value="trio" onclick="showForm('trio')"> Trinôme <br>
            </div>
        </div>

        <div class="mobile-div">
            <label for="name1" class="bin">Prénom de etudiant2:</label>
            <input type="text" id="name1" name="etudiant2_prenom" placeholder="Entrez votre prénom" class="input-bin"> <br>
            <label for="surname1" class="bin">Nom de etudiant2:</label>
            <input type="text" id="surname1" name="etudiant2_nom" placeholder="Entrez votre nom" class="input-bin">
        </div>

        <div class="mobile-div">
            <label for="name3" class="bin2">Prénom de etudiant3:</label>
            <input type="text" id="name3" name="etudiant3_prenom" placeholder="Entrez votre prénom" class="input-bin2"><br>
            <label for="surname3" class="bin2">Nom de etudiant3:</label>
            <input type="text" id="surname3" name="etudiant3_nom" placeholder="Entrez votre nom" class="input-bin2"><br>
        </div>

        <div class="mobile-div1">
            <div class="name">Si vous ne connaissez aucun des étudiants, cliquez ici:<span class="required">*</span>
                <button type="button"><a href="./liste.php">   Voir la liste des étudiants</a></button>
            </div>
        </div>

        <div class="mobile-div2">
            <div class="name">Veuillez classer les projets selon votre préférence, de 1 en tant que plus important à 4 en tant que <span class="required">*</span><br>
                <label>1. Projet 1:</label>
                1 <input type="radio" name="projet1_rank" value="1" required>
                2 <input type="radio" name="projet1_rank" value="2">
                3 <input type="radio" name="projet1_rank" value="3">
                4 <input type="radio" name="projet1_rank" value="4"><br>

                <label>2. Projet 2:</label>
                1 <input type="radio" name="projet2_rank" value="1" required>
                2 <input type="radio" name="projet2_rank" value="2">
                3 <input type="radio" name="projet2_rank" value="3">
                4 <input type="radio" name="projet2_rank" value="4"><br>

                <label>3. Projet 3:</label>
                1 <input type="radio" name="projet3_rank" value="1" required>
                2 <input type="radio" name="projet3_rank" value="2">
                3 <input type="radio" name="projet3_rank" value="3">
                4 <input type="radio" name="projet3_rank" value="4"><br>

                <label>4. Projet 4:</label>
                1 <input type="radio" name="projet4_rank" value="1" required>
                2 <input type="radio" name="projet4_rank" value="2">
                3 <input type="radio" name="projet4_rank" value="3">
                4 <input type="radio" name="projet4_rank" value="4"><br>
            </div>
        </div>

        <div>
            <input class="btn" type="submit" name="Submit" value="Soumettre">
        </div>
    </form>

    <div class="last-div">
        <p class="never">Ne jamais soumettre de mots de passe via Google Forms.</p>
        <p class="term">Ce contenu n'est ni créé ni approuvé par Google. Signaler un abus - Conditions d'utilisation - Politique de confidentialité.</p>
    </div>
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