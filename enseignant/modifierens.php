


<?php
session_start();

require_once '../config/db.php';

// التحقق من إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['name'];
    $prenom = $_POST['prenom'];
    $diplomes = $_POST['diplomes'];
    $experience = $_POST['experience'];

    // الحصول على معرف المستخدم من الجلسة
    $user_id = $_SESSION['user_id'];

    try {
        // تحديث البيانات في قاعدة البيانات
        $sql = "UPDATE userr SET nom = :nom, prenom = :prenom WHERE id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':user_id' => $user_id,
        ]);

        $sql = "UPDATE enseignant SET diplomes = :diplomes, experience = :experience WHERE id_enseignant = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':diplomes' => $diplomes,
            ':experience' => $experience,
            ':user_id' => $user_id,
        ]);

        echo "تم تحديث البيانات بنجاح!";
    } catch (PDOException $e) {
        echo "حدث خطأ: " . $e->getMessage();
    }
}

// جلب البيانات الحالية لعرضها في الحقول
$user_id = $_SESSION['user_id'];
try {
    $sql = "SELECT userr.nom, userr.prenom, enseignant.diplomes, enseignant.experience
            FROM userr 
            JOIN enseignant ON userr.id = enseignant.id_enseignant
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
    <link rel="stylesheet" href="./modifierens.css" />
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

                        <section class="skills">
                            <h3>Diplômes</h3>
                            <textarea name="diplomes" rows="5" placeholder="Décrivez vos diplômes..." required>
                                <?php echo htmlspecialchars($user_data['diplomes'] ?? ''); ?>
                            </textarea>
                        </section>

                        <section class="skills">
                            <h3>Expériences</h3>
                            <textarea name="experience" rows="5" placeholder="Décrivez vos expériences..." required>
                                <?php echo htmlspecialchars($user_data['experience'] ?? ''); ?>
                            </textarea>
                        </section>

                        <button type="submit" class="save-button">Enregistrer les modifications</button>
                    </form>
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
                    <li class="icon solid fa-envelope"><a href="#">support@plateforme-projets.com</a></li>
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
