
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


try {
  $sql = "SELECT e.id_utilisateur, u.nom, u.prenom, e.domaine 
          FROM etudiant e
          JOIN userr u ON e.id_utilisateur = u.id";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <link rel="stylesheet" href="list.css" />
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
                            <a href=""> <i class="fa-solid fa-bell" id="icon"></i></a>
                            <a href=""> <i class="fa-solid fa-message" id="icon"></i></a>
                        </div>
                        <div class="profile-menu">
                            <img src="../assets/images/10.jpg" alt="Profile" class="profile-pic">
                            <span class="profile-name"><?php echo $user['prenom'] . ' ' . $user['nom']; ?></span>
                            <div class="dropdown-menu">
                                <ul>
                                    <li><a href="./profile.php"><i class="fa-regular fa-user"></i> Profile</a></li>
                                    <li><a href="./modifier.php"><i class="fa-regular fa-pen-to-square"></i> Modifier profiel</a></li>
                                    <li><a href="../deconx.php"><i class="fa-solid fa-right-to-bracket"></i> Deconnexion</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
                <header style="border-bottom: solid 5px #3a7e3e;"></header>

                <!-- Content -->
                <section>
                    <header class="main">
                        <h2>La Liste Des étudiants</h2>
                    </header>

                    <!-- Tableau des étudiants -->
                    <table>
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Spécialité</th>
                                <th>Groupe</th>
                                <th>Choisir</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($etudiants as $etudiant): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($etudiant['nom']); ?></td>
                                    <td><?php echo htmlspecialchars($etudiant['prenom']); ?></td>
                                    <td><?php echo htmlspecialchars($etudiant['domaine']); ?></td>
                                    <td>G3</td> <!-- Vous pouvez remplacer "G3" par une valeur dynamique si disponible -->
                                    <td><button>Choisir</button></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="buttons">
                        <button type="button" class="btn3"><a href="./selection.php">Retour</a></button>
                        <input type="submit" value="Envoyer" class="btn2">
                    </div>
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

                <!-- Contact -->
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