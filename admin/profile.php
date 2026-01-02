<?php
// Start session and check if admin is logged in
session_start();

// Database connection
$host = 'localhost';
$dbname = 'laravel'; // Change this to your actual database name
$user = 'root';
$pass = ''; // As you mentioned, no password for 'root'

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}

// Fetch admin info from database based on session user ID
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    $stmt = $pdo->prepare("SELECT * FROM userr WHERE id = :id");
    $stmt->bindParam(':id', $admin_id);
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    // If no admin logged in, redirect to login page (replace with your login page)
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>E-Master - Admin Profile</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/css/profile.css" />
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="is-preload">
<div id="wrapper">
    <div id="main">
        <div class="inner">
            <nav class="navbar">
                <div class="navbar-left">
                    <img src="images/pic12.jpg" alt="Logo" class="logo">
                    <span class="brand-name"><a href="">E-Master</a></span>
                </div>
                <div class="navbar-right">
                    <div class="icon-container">
                        <a href=""><i class="fa-solid fa-bell" id="icon"></i></a>
                        <a href=""><i class="fa-solid fa-message" id="icon"></i></a>
                    </div>
                    <div class="profile-menu">
                        <img src="images/user.png" alt="Profile" class="profile-pic">
                        <span class="profile-name"><?php echo htmlspecialchars($admin['nom'] . ' ' . $admin['prenom']); ?></span>
                        <div class="dropdown-menu">
                            <ul>
                                <li><a href="profile.php"><i class="fa-regular fa-user"></i> Profile</a></li>
                                <li><a href="modifier.php"><i class="fa-regular fa-pen-to-square"></i> Modifier profil</a></li>
                                <li><a href="logout.php"><i class="fa-solid fa-right-to-bracket"></i> Déconnexion</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <header style="border-bottom: solid 5px #3a7e3e;"></header>

            <section class="profile-container">
                <div class="profile-header">
                    <img src="images/user.png" alt="Photo de Profil" class="profile-photo">
                    <h1><?php echo htmlspecialchars($admin['nom'] . ' ' . $admin['prenom']); ?></h1>
                    <p class="bio">Université Constantine 2-Abdelhamid Mehri</p>
                </div>

                <div class="profile-details">
                    <h2>Informations Personnelles</h2>
                    <ul>
                        <li><strong>Nom :</strong> <?php echo htmlspecialchars($admin['nom']); ?></li>
                        <li><strong>Prénom :</strong> <?php echo htmlspecialchars($admin['prenom']); ?></li>
                        <li><strong>Email :</strong> <?php echo htmlspecialchars($admin['email']); ?></li>
                        <li><strong>Type :</strong> <?php echo htmlspecialchars($admin['type']); ?></li>
                        <li><strong>Status :</strong> <?php echo htmlspecialchars($admin['status']); ?></li>
                    </ul>
                </div>
            </section>
        </div>
    </div>

    <div id="sidebar">
        <div class="inner">
            <nav id="menu">
                <header class="major">
                    <h2>Gestion des Projets</h2>
                </header>
                <ul>
                    <li><a href="index.html">Accueil</a></li>
                    <li><a href="consulterProj.html">Consulter les projets</a></li>
                    <li><a href="selection.html">Sélection des projets</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/browser.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
