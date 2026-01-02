<?php
session_start();
require_once '../config/db.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['name'];
    $prenom = $_POST['prenom'];
    $languages = isset($_POST['languages']) ? implode(", ", $_POST['languages']) : "";
    $competences = $_POST['competences'];

    
    $user_id = $_SESSION['user_id'];

    try {
        
        $sql = "UPDATE userr SET nom = :nom, prenom = :prenom WHERE id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':user_id' => $user_id,
        ]);

        $sql = "UPDATE etudiant SET languages = :languages, competences = :competences WHERE id_utilisateur = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':languages' => $languages,
            ':competences' => $competences,
            ':user_id' => $user_id,
        ]);

        // إعادة التوجيه إلى صفحة البروفيل بعد الحفظ
        header("Location: profile.php");
        exit(); // تأكد من توقف تنفيذ الكود بعد إعادة التوجيه

    } catch (PDOException $e) {
        echo "حدث خطأ: " . $e->getMessage();
    }
}

// جلب البيانات الحالية لعرضها في الحقول
$user_id = $_SESSION['user_id'];
try {
    $sql = "SELECT userr.nom, userr.prenom, etudiant.languages, etudiant.competences 
            FROM userr 
            JOIN etudiant ON userr.id = etudiant.id_utilisateur 
            WHERE userr.id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user_data) {
        die("خطأ: لم يتم العثور على بيانات المستخدم.");
    }
} catch (PDOException $e) {
    die("خطأ في جلب البيانات: " . $e->getMessage());
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>E-Master</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="./modifier.css" />
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="is-preload">

<div id="wrapper">

    <div id="main">
        <div class="inner">

            <!-- Header & Navbar -->
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
                        <span class="profile-name"><?php echo $user_data['prenom'] . ' ' . $user_data['nom']; ?></span>
                        <div class="dropdown-menu">
                            <ul>
                                <li><a href="./profile.php"><i class="fa-regular fa-user"></i>  Profile</a></li>
                                <li><a href="./modifier.php"><i class="fa-regular fa-pen-to-square"></i>  Modifier profiel</a></li>
                                <li><a href="#logout"><i class="fa-solid fa-right-to-bracket"></i> Deconnexion</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
            <header style="border-bottom: solid 5px #3a7e3e;"></header>

            <!-- Content Section -->
            <section>
                <section class="modify-profile-container">
                    <div class="title">
                        <h1>Modifier Mon Profil</h1>
                    </div>

                    <form action="#" method="POST" enctype="multipart/form-data">

                        <div class="form-group">
                            <label for="name">Nom Complet :</label>
                            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user_data['nom'] ?? ''); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="prenom">Prenom :</label>
                            <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($user_data['prenom'] ?? ''); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="languages"><h3>Langages de Programmation maitrisé:</h3></label><br>
                            <?php
                            $languages = explode(", ", $user_data['languages']);
                            $all_languages = ['Python', 'JavaScript', 'Java', 'C++', 'HTML/CSS'];
                            foreach ($all_languages as $language) {
                                $checked = in_array($language, $languages) ? "checked" : "";
                                echo "<label><input type='checkbox' name='languages[]' value='$language' $checked> $language</label><br>";
                            }
                            ?>
                        </div>

                        <section class="skills">
                            <h3>Compétences</h3>
                            <textarea name="competences" rows="5" placeholder="Décrivez vos compétences (ex: Développement Web, Gestion de projet)..." required>
                                <?php echo htmlspecialchars($user_data['competences'] ?? ''); ?>
                            </textarea>
                        </section>

                        <button type="submit" class="save-button">Enregistrer les modifications</button>
                    </form>
                </section>
            </section>
        </div>
    </div>

    <!-- Sidebar Section -->
    <div id="sidebar">
        <div class="inner">

            <!-- Search Section -->
            <section id="search" class="alt">
                <form method="post" action="#">
                    <input type="text" name="query" id="query" placeholder="Search" />
                </form>
            </section>

            
            <!-- Menu Section -->
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

            <!-- Contact Section -->
            <section>
                <header class="major">
                    <h2>Contact</h2>
                </header>
                <ul class="contact">
                    <li class="icon solid fa-envelope"><a href="#">support@plateforme-projets.com</a></li>
                    <li class="icon solid fa-phone">Téléphone : +213 555 123 456</li>
                </ul>
            </section>

            <!-- Footer Section -->
            <footer id="footer">
                <p>© 2024 Plateforme Projets - Tous droits réservés</p>
            </footer>

        </div>
    </div>

</div>

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
