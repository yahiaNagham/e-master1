<?php
// Database connection
require_once '../config/db.php'; // تأكد من أن ملف db.php يحتوي على تهيئة اتصال PDO

// Fetch student list (table: userr)
$students_sql = "SELECT nom, prenom, status FROM userr WHERE type = 'etudiant'";
$students_result = $conn->query($students_sql);

// Fetch teacher list (table: userr)
$teachers_sql = "SELECT nom, prenom, email FROM userr WHERE type = 'enseignant'";
$teachers_result = $conn->query($teachers_sql);

// Fetch projects list (table: projets)
$projects_sql = "SELECT project_name, etat FROM projets";
$projects_result = $conn->query($projects_sql);

// Fetch candidatures list (table: candidats)
$candidatures_sql = "SELECT name, statut FROM candidats";
$candidatures_result = $conn->query($candidatures_sql);

// Fetch students by group (assuming group data is part of userr)
$groupes_sql = "SELECT nom, prenom, status FROM userr WHERE type = 'etudiant'"; // Update if you have a separate table for groups
$groupes_result = $conn->query($groupes_sql);
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Gestion des listes</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="./liste.css" />
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="is-preload">
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
                        <img src="../assets/images/10.jpg" alt="Profile" class="profile-pic">
                        
                        <div class="dropdown-menu">
                            <ul>
                                <li><a href="#logout"><i class="fa-solid fa-right-to-bracket"></i> Déconnexion</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
            <header style="border-bottom: solid 5px #3a7e3e;"></header>

<!-- Wrapper -->
    <div id="main">
        

            <!-- Student List -->
            <h1>Liste des étudiants</h1>
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($students_result->rowCount() > 0): ?>
                        <?php while($row = $students_result->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr>
                                <td><?php echo $row["nom"]; ?></td>
                                <td><?php echo $row["prenom"]; ?></td>
                                <td><?php echo $row["status"]; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="3">No students found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Teacher List -->
            <h1>Liste des enseignants inscrits</h1>
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($teachers_result->rowCount() > 0): ?>
                        <?php while($row = $teachers_result->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr>
                                <td><?php echo $row["nom"]; ?></td>
                                <td><?php echo $row["prenom"]; ?></td>
                                <td><?php echo $row["email"]; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="3">No teachers found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Project List -->
            <h1>Liste des projets proposés</h1>
            <table>
                <thead>
                    <tr>
                        <th>Projet Name</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($projects_result->rowCount() > 0): ?>
                        <?php while($row = $projects_result->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr>
                                <td><?php echo $row["project_name"]; ?></td>
                                <td><?php echo $row["etat"]; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="2">No projects found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Candidature List -->
            <h1>Liste des candidatures</h1>
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>État</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($candidatures_result->rowCount() > 0): ?>
                        <?php while($row = $candidatures_result->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr>
                                <td><?php echo $row["name"]; ?></td>
                                <td><?php echo $row["statut"]; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="2">No candidatures found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Students by Group List -->
            <h1>Liste des étudiants par groupe</h1>
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($groupes_result->rowCount() > 0): ?>
                        <?php while($row = $groupes_result->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr>
                                <td><?php echo $row["nom"]; ?></td>
                                <td><?php echo $row["prenom"]; ?></td>
                                <td><?php echo $row["status"]; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="3">No groups found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </div>
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
                    <h2>Gestion des Projets</h2>
                </header>
                <ul>
                    <li><span class="opener">Supervision des Projets et des Candidatures</span>
                        <ul>
                            <li class="liste"><a href="./consulterProj.php">Consulter les projets</a></li>
                            <li class="liste"><a href="./selection.php">Consulter candidats</a></li>
                        </ul>
                    </li>
                    <li><span class="opener">Gestion des conflits</span>
                        <ul>
                            <li class="liste"><a href="./modification.php">Modification de la Composition des Groupes</a></li>
                            <li class="liste"><a href="./Réattribution.php">Réattribution des Projets</a></li>
                        </ul>
                    </li>
                    <li><a href="./settings_backend.php">Paramètres Pédagogiques</a></li>
                    <li><a href="./gestion des compte.php">Gestion des comptes utilisateurs</a></li>
                    <li><a href="./liste.php">listes des  utilisateurs</a></li>
                    <li>
                        <span class="opener">Communication et collaboration</span>
                        <ul>
                            <li class="liste"><a href="./message.php">Message</a></li>
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
</script>
</body>
</html>

<?php
$conn = null; // إغلاق الاتصال
?>