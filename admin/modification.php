<?php

$host = 'localhost';  // أو عنوان الخادم الخاص بك
$dbname = 'daw';      // اسم قاعدة البيانات
$username = 'root';   // اسم المستخدم لقاعدة البيانات
$password = '';       // كلمة المرور لقاعدة البيانات

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);   
    // تعيين وضع الخطأ لعرض الأخطاء
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

// Handle update request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_e'])) {
    $id = $_POST['id_e'];
    $nouveau_groupe = $_POST['nouveau_groupe'];

    // Update query for updating the 'groupe_actuel' field
    $sql = "UPDATE etudiant SET groupe_actuel = :nouveau_groupe WHERE id_e = :id_e"; // Update groupe_actuel instead of groupe
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nouveau_groupe', $nouveau_groupe);
    $stmt->bindParam(':id_e', $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
    exit();
}

// Fetch students with their names from the 'userr' table
$sql = "SELECT e.*, u.nom
        FROM etudiant e
        JOIN userr u ON e.id_utilisateur = u.id";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>E-Master</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="./modification.css" />
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="is-preload">

<div id="wrapper">

    <!-- Sidebar -->
    <div id="sidebar">
        <!-- Content of Sidebar (if any) -->
    </div>

    <!-- Main Content -->
    <div id="main">
        <div class="inner">
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

            <section>
                <header class="main">
                    <h2>Modification de la Composition des Groupes</h2>
                </header>

                <table>
                    <thead>
                    <tr>
                        <th>Nom de l'Étudiant</th>
                        <th>Groupe Actuel</th>
                        <th>Nouveau Groupe</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= htmlspecialchars($student['nom']) ?></td>
                            <td><?= htmlspecialchars($student['groupe_actuel']) ?></td>
                            <td>
                                <select class="nouveau-groupe" data-id="<?= $student['id_e'] ?>">
                                    <option value="Monôme" <?= $student['groupe_actuel'] == 'Monôme' ? 'selected' : '' ?>>Monôme</option>
                                    <option value="Binôme" <?= $student['groupe_actuel'] == 'Binôme' ? 'selected' : '' ?>>Binôme</option>
                                    <option value="Trinôme" <?= $student['groupe_actuel'] == 'Trinôme' ? 'selected' : '' ?>>Trinôme</option>
                                </select>
                            </td>
                            <td>
                                <button class="update-group-btn" data-id="<?= $student['id_e'] ?>">Mettre à jour</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </div>
    </div>

</div>

<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/browser.min.js"></script>
<script src="../assets/js/breakpoints.min.js"></script>
<script src="../assets/js/util.js"></script>
<script src="../assets/js/main.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

    $(document).ready(function () {
        $('.update-group-btn').click(function () {
            var id = $(this).data('id');  // Corrected data-id attribute
            var nouveauGroupe = $(this).closest('tr').find('.nouveau-groupe').val();

            $.ajax({
                url: '',  // Ensure the URL is correct if you're using AJAX in the same file
                method: 'POST',
                data: {
                    id_e: id,  // Correctly pass id_e
                    nouveau_groupe: nouveauGroupe
                },
                success: function (response) {
                    var result = JSON.parse(response);
                    if (result.status === 'success') {
                        alert('Groupe actuel mis à jour avec succès!');
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
