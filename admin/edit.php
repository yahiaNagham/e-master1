<?php
session_start();



$host = '127.0.0.1';  // أو عنوان السيرفر
$db = 'daw'; // اسم قاعدة البيانات
$user = 'root';   // اسم المستخدم
$pass = ''; // كلمة السر

try {
    // إنشاء اتصال باستخدام PDO
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    // تحديد وضع الأخطاء
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // في حالة حدوث خطأ في الاتصال
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}


// Check if ID is provided
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    
    // Fetch user data
    $sql = "SELECT * FROM userr WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $user_id]);

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        echo "User not found.";
    }
}

// Handle form submission (user update)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $type = $_POST['type'];
    $status = $_POST['status'];

    $sql = "UPDATE userr SET nom=:nom, prenom=:prenom, email=:email, type=:type, status=:status WHERE id=:id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'nom' => $nom,
        'prenom' => $prenom,
        'email' => $email,
        'type' => $type,
        'status' => $status,
        'id' => $user_id
    ]);

    if ($stmt->rowCount() > 0) {
        echo "<p>User updated successfully</p>";
    } else {
        echo "No changes made.";
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Edit User</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="assets/css/consulterProj.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        .buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .cancel-btn {
            background-color: #f44336;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .cancel-btn:hover {
            background-color: #e53935;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 10px;
            text-decoration: none;
            color: #777;
        }

        a:hover {
            color: #333;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Edit User</h1>
        <form method="POST">
            <label for="nom">First Name:</label>
            <input type="text" id="nom" name="nom" value="<?php echo $user['nom']; ?>" required>

            <label for="prenom">Last Name:</label>
            <input type="text" id="prenom" name="prenom" value="<?php echo $user['prenom']; ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>

            <label for="type">Role:</label>
            <select id="type" name="type" required>
                <option value="etudiant" <?php echo ($user['type'] == 'etudiant') ? 'selected' : ''; ?>>etudiant</option>
                <option value="enseignant" <?php echo ($user['type'] == 'enseignant') ? 'enseignant' : ''; ?>>enseignant</option>
            </select>

            <label for="status">Status:</label>
            <select id="status" name="status" required>
                <option value="active" <?php echo ($user['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                <option value="inactive" <?php echo ($user['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                <option value="suspended" <?php echo ($user['status'] == 'suspended') ? 'selected' : ''; ?>>Suspended</option>
            </select>

            <div class="buttons">
                <button type="submit" name="update">Update</button>
              
            </div>
        </form>
    </div>
</body>
</html>

  