
<?php
session_start();


require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));
  

    try {
        // التحقق من وجود المستخدم في قاعدة البيانات
        $sql = "SELECT * FROM userr WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['mdp'])) {
            // تخزين بيانات المستخدم في الجلسة
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['prenom'] . ' ' . $user['nom'];
            $_SESSION['user_type'] = $user['type'];

            // توجيه المستخدم حسب نوعه
            switch ($user['type']) {
                case 'etudiant':
                    header("Location: etudiant/etudiant.php");
                    break;
                case 'enseignant':
                    header("Location: enseignant/enseignant.php");
                    break;
                case 'admin':
                    header("Location: admin/gestion des compte.php");
                    break;
                default:
                    die("Type d'utilisateur inconnu.");
            }
            exit;
        } else {
            echo "Email ou mot de passe incorrect.";
        }
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Plateforme Projets</title>
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/conx.css">
</head>
<body>
    <div class="navbar-left">
        <img src="assets/images/pic12.jpg" alt="Logo" class="logo">
        <span class="brand-name"><a href="">E-Master</a></span>
    </div>
    
    <div class="login-container">
        <h1>Connexion</h1>
        <form id="loginForm" action="conx.php" method="POST">
            <div class="form-group">
                <label for="email">Adresse Email</label>
                <input type="email" id="email" name="email" placeholder="Entrez votre email" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe" required>
            </div>
            <button type="submit" class="btn">Se connecter</button>
      
        </form>
        
    </div>
</body>
</html>