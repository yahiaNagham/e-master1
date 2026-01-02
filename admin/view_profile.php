<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "daw";  // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user details
$user_id = $_GET['id'];
$sql = "SELECT * FROM userr WHERE id = $user_id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>

<!DOCTYPE HTML>
<html>
    <head>
        <title>View Profile</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
            }

            h1 {
                text-align: center;
                color: #333;
            }

            .profile-container {
                width: 50%;
                margin: 30px auto;
                background-color: white;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            .profile-container p {
                font-size: 18px;
                margin: 10px 0;
            }

            .profile-container strong {
                color: #333;
            }

            .back-btn {
                display: block;
                text-align: center;
                margin-top: 20px;
                padding: 10px 20px;
                background-color: #3498db;
                color: white;
                text-decoration: none;
                border-radius: 5px;
                width: 150px;
                margin: 20px auto;
            }

            .back-btn:hover {
                background-color: #45a049;
            }
        </style>
    </head>
    <body>
        <div class="profile-container">
            <h1>User Profile</h1>
            <p><strong>Name:</strong> <?php echo $user['nom'] . " " . $user['prenom']; ?></p>
            <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
            <p><strong>type:</strong> <?php echo $user['type']; ?></p>
            <p><strong>Status:</strong> <?php echo $user['status']; ?></p>
            <a href="gestion des compte.php" class="back-btn">Back to User List</a>
        </div>
    </body>
</html>

<?php
$conn->close();
?>
