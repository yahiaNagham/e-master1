<?php

$host = 'localhost'; // اسم الخادم
$dbname = 'daw'; // اسم قاعدة البيانات
$username = 'root'; // اسم المستخدم
$password = ''; // كلمة المرور

try {
    // الاتصال بقاعدة البيانات
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // تعيين إعدادات PDO لعرض الأخطاء
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("خطأ في الاتصال بقاعدة البيانات: " . $e->getMessage());
}



// دالة لجلب المشاريع والمسؤولين
function getProjects($pdo) {
    $stmt = $pdo->prepare("SELECT p.id, p.project_name, p.etat, r.name as responsible_name, r.id_enseignant as responsible_id 
                           FROM projets p
                           LEFT JOIN enseignant r ON p.id_enseignant = r.id_enseignant");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// دالة لجلب المسؤولين المتاحين
function getResponsables($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM enseignant");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// دالة لتحديث المسؤول عن المشروع
function updateProjectResponsible($pdo, $projectId, $newResponsibleId) {
    $stmt = $pdo->prepare("UPDATE projets SET id_enseignant = :responsable_id WHERE id = :project_id");
    $stmt->execute(['responsable_id' => $newResponsibleId, 'project_id' => $projectId]);
}

// دالة لإعادة فتح الترشيحات للمشاريع
function reopenCandidatures($pdo, $projectId) {
    $stmt = $pdo->prepare("UPDATE projets SET etat = 'open' WHERE id = :project_id");
    $stmt->execute(['project_id' => $projectId]);
}

// التعامل مع إرسال النموذج لإعادة التعيين
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reassign'])) {
    $projectId = $_POST['project_id'];
    $newResponsibleId = $_POST['responsible_id'];
    updateProjectResponsible($pdo, $projectId, $newResponsibleId);
    // عرض إشعار النجاح
    echo "<script>alert('Réattribution réussie!');</script>";
}

// التعامل مع إرسال النموذج لإعادة فتح الترشيحات
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reopen'])) {
    $projectId = $_POST['project_id'];
    reopenCandidatures($pdo, $projectId);
    // عرض إشعار النجاح
    echo "<script>alert('Candidatures réouvertes!');</script>";
}

$projects = getProjects($pdo);
$responsables = getResponsables($pdo);
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>E-Master - Consulter Projets</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="./Réattribution.css" />
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="is-preload">
    <div id="wrapper">
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
                    <li>
                        <span class="opener">Supervision des Projets et des Candidatures</span>
                        <ul>
                            <li class="liste"><a href="consulterProj.php">Consulter les projets</a></li>
                            <li class="liste"><a href="selection.php">Consulter candidats</a></li>
                        </ul>
                    </li>
                    <li>
                        <span class="opener">Gestion des conflits</span>
                        <ul>
                            <li class="liste"><a href="modification.php">Modification de la Composition des Groupes</a></li>
                            <li class="liste"><a href="Réattribution.php">Réattribution des Projets</a></li>
                        </ul>
                    </li>
                    <li><a href="settings_backend.php">Paramètres Pédagogiques</a></li>
                    <li><a href="gestion des compte.php">Gestion des comptes utilisateurs</a></li>
                    <li><a href="liste.php">listes des utilisateurs</a></li>
                    <li>
                        <span class="opener">Communication et collaboration</span>
                        <ul>
                            <li class="liste"><a href="message.html">Message</a></li>
                            <li class="liste"><a href="historique.html">Historique des échanges</a></li>
                        </ul>
                    </li>
                    <li><a href="satistique.html">Statistiques et tableau de bord</a></li>
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

    <!-- Main Content -->
    <div id="main">
        <div class="inner">

            <!-- Header -->
            <nav class="navbar">
                <div class="navbar-left">
                    <img src="images/pic12.jpg" alt="Logo" class="logo">
                    <span class="brand-name"><a href="#">E-Master</a></span>
                </div>
                <div class="navbar-right">
                    <div class="icon-container">
                        <a href="#"><i class="fa-solid fa-bell" id="icon"></i></a>
                        <a href="#"><i class="fa-solid fa-message" id="icon"></i></a>
                    </div>
                    <div class="profile-menu">
                        <img src="images/user.png" alt="Profile" class="profile-pic">
                        <span class="profile-name">yahia louai</span>
                        <div class="dropdown-menu">
                            <ul>
                                <li><a href="profile.html"><i class="fa-regular fa-user"></i> Profile</a></li>
                                <li><a href="modifier.html"><i class="fa-regular fa-pen-to-square"></i> Modifier profil</a></li>
                                <li><a href="#logout"><i class="fa-solid fa-right-to-bracket"></i> Deconnexion</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
            <header style="border-bottom: solid 5px #3a7e3e;"></header>

            <!-- Section: Reattribution des Projets -->
            <section>
                <header class="main">
                    <h2>Réattribution des Projets</h2>
                </header>
                
                <table>
                    <thead>
                        <tr>
                            <th>Nom du Projet</th>
                            <th>État</th>
                            <th>Nouveau Responsable</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($projects as $project): ?>
                            <tr>
                                <td><?= htmlspecialchars($project['project_name']) ?></td>
                                <td><?= htmlspecialchars($project['etat']) ?></td>
                                <td>
                                    <form method="POST" action="">
                                        <select name="responsible_id">
                                            <?php foreach ($responsables as $responsible): ?>
                                                <option value="<?= $responsible['id_enseignant'] ?>" <?= $project['responsible_id'] == $responsible['id_enseignant'] ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($responsible['name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <input type="hidden" name="project_id" value="<?= $project['id'] ?>" />
                                </td>
                                <td class="actions">
                                    <button type="submit" name="reassign" class="reassign-project-btn">Réattribuer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>

            <!-- Section: Réouverture des Candidatures -->
            

        </div>
    </div>

</div>

<!-- Scripts -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/browser.min.js"></script>
<script src="assets/js/breakpoints.min.js"></script>
<script src="assets/js/util.js"></script>
<script src="assets/js/main.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('.update-group-btn').click(function () {
            var id = $(this).data('id');
            var nouveauGroupe = $(this).closest('tr').find('.nouveau-groupe').val();

            $.ajax({
                url: '',
                method: 'POST',
                data: {
                    id: id,
                    nouveau_groupe: nouveauGroupe
                },
                success: function (response) {
                    var result = JSON.parse(response);
                    if (result.status === 'success') {
                        alert('Groupe mis à jour avec succès!');
                    } else {
                        alert('Erreur lors de la mise à jour du groupe.');
                    }
                }
            });
        });
    });
</script>

</body>
</html>
