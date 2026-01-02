<?php
require_once '../config/db.php';
session_start();

// Handle new user registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $prenom = trim($_POST['prenom']);
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $mdp = password_hash(trim($_POST['mdp']), PASSWORD_DEFAULT); // Hash the password
    $type = trim($_POST['type']);
    $status = trim($_POST['status']);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p style='color:red;'>Invalid email format!</p>";
        return;
    }

    try {
        // Insert user into the 'userr' table
        $sql = "INSERT INTO userr (prenom, nom, email, mdp, type, status) 
                VALUES (:prenom, :nom, :email, :mdp, :type, :status)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mdp', $mdp);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':status', $status);
        $stmt->execute();

        // If user type is 'etudiant', insert into the 'etudiant' table
        if ($type == 'etudiant') {
            // Get the last inserted user ID to use it in the 'etudiant' table
            $user_id = $conn->lastInsertId();

            // Insert into 'etudiant' table
            $etudiant_sql = "INSERT INTO etudiant (id_utilisateur) VALUES (:id_utilisateur)";
            $stmt_etudiant = $conn->prepare($etudiant_sql);
            $stmt_etudiant->bindParam(':id_utilisateur', $user_id);
            $stmt_etudiant->execute();
        }

        // If user type is 'enseignant', insert into the 'enseignant' table
        if ($type == 'enseignant') {
            // Get the last inserted user ID to use it in the 'enseignant' table
            $user_id = $conn->lastInsertId();

            // Additional fields for 'enseignant'
            $name = trim($_POST['name']);
            $sujet_proposer = trim($_POST['sujet_proposer']);
            $diplomes = trim($_POST['diplomes']);
            $experience = trim($_POST['experience']);
            $pedagogical_settings = trim($_POST['pedagogical_settings']);

            // Insert into 'enseignant' table
            $enseignant_sql = "INSERT INTO enseignant (name, sujet_proposer, diplomes, experience, pedagogical_settings, user_id)
                               VALUES (:name, :sujet_proposer, :diplomes, :experience, :pedagogical_settings, :user_id)";
            $stmt_enseignant = $conn->prepare($enseignant_sql);
            $stmt_enseignant->bindParam(':name', $name);
            $stmt_enseignant->bindParam(':sujet_proposer', $sujet_proposer);
            $stmt_enseignant->bindParam(':diplomes', $diplomes);
            $stmt_enseignant->bindParam(':experience', $experience);
            $stmt_enseignant->bindParam(':pedagogical_settings', $pedagogical_settings);
            $stmt_enseignant->bindParam(':user_id', $user_id);
            $stmt_enseignant->execute();
        }

        echo "<p style='color:green;'>User created successfully!</p>"; // Display success message
        header("Location: gestion des compte.php"); 
        exit();
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
    }
}

// Handle user deletion
if (isset($_POST['delete'])) {
    $delete_id = $_POST['delete_id'];
    try {
        $delete_sql = "DELETE FROM userr WHERE id = :id";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bindParam(':id', $delete_id);
        $stmt->execute();
        echo "<p style='color:green;'>User deleted successfully!</p>";
        header("Location: gestion des compte.php"); // Refresh the page after deletion
        exit();
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
    }
}

// Fetch all users
$sql = "SELECT * FROM userr";
$stmt = $conn->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll();
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>E-Master</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="./gestion des compte.css" />
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<style>
    .delete-btn {
        background-color: red;
        color: white;
        border: none;
        padding: 3px 10px;
        cursor: pointer;
    }

    .view-profile-btn, .edit-btn {
        background-color:  #3498db;
        color: white;
        border: none;
        padding: 3px 10px;
        cursor: pointer;
    }

    .view-profile-btn:hover, .edit-btn:hover {
        background-color: lightblue;
    }

    /* New styles for arranging buttons horizontally */
    .button-container {
        display: flex;
        gap: 10px; /* Adds space between buttons */
        align-items: center; /* Vertically centers buttons */
    }
</style>

<<div id="wrapper">


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
                  
                    <div class="dropdown-menu">
                        <ul>
                          
                            <li><a href="../deconx.php"><i class="fa-solid fa-right-to-bracket"></i> Deconnexion</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <header style="border-bottom: solid 5px #3a7e3e;"></header>


        <section>
                <div class="container">
                    <h1>User Management</h1>

                    <!-- Button to show Create User Form -->
                    <button onclick="showCreateUserForm()">Create New User</button>
                    <br><br>

                    <div id="user-list">
                        <h2>User List</h2>
                        <table>
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Etat</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="user-table-body">
                                <?php
                                foreach ($users as $row) {
                                    echo "<tr>
                                            <td>{$row['nom']} {$row['prenom']}</td>
                                            <td>{$row['email']}</td>
                                            <td>{$row['type']}</td>
                                            <td>{$row['status']}</td>
                                            <td>
                                                <form method='POST' action=''>
                                                    <input type='hidden' name='delete_id' value='{$row['id']}' />
                                                    <button type='submit' name='delete' class='delete-btn'>Delete</button>
                                                </form>
                                                <a href='view_profile.php?id={$row['id']}' class='view-profile-btn'>View Profile</a>
                                                <a href='edit.php?id={$row['id']}' class='edit-btn'>Edit</a>
                                            </td>
                                          </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Create User Form -->
                    <div id="user-form" >
                        <h2 id="form-title">Créer un Nouveau Utilisateur</h2>
                        <form method="POST">
                            <label for="nom">Nom:</label>
                            <input type="text" id="nom" name="nom" required>

                            <label for="prenom">Prénom:</label>
                            <input type="text" id="prenom" name="prenom" required>

                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" required>

                            <label for="mdp">Mot de passe:</label>
                            <input type="password" id="mdp" name="mdp" required>

                            <label for="type">Rôle:</label>
                            <select id="type" name="type" required>
                                <option value="etudiant">Étudiant</option>
                                <option value="enseignant">Enseignant</option>
                                <option value="admin">Admin</option>
                            </select>

                            <label for="status">Statut:</label>
                            <select id="status" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                            </select>

                            <!-- Enseignant specific fields -->
                            <div id="enseignant-fields" >
                                <label for="name">Nom complet:</label>
                                <input type="text" id="name" name="name">

                                <label for="sujet_proposer">Sujet Proposé:</label>
                                <input type="text" id="sujet_proposer" name="sujet_proposer">

                                <label for="diplomes">Diplômes:</label>
                                <input type="text" id="diplomes" name="diplomes">

                                <label for="experience">Expérience:</label>
                                <input type="text" id="experience" name="experience">

                                <label for="pedagogical_settings">Paramètres pédagogiques:</label>
                                <input type="text" id="pedagogical_settings" name="pedagogical_settings">
                            </div>

                            <button type="submit" name="submit">Créer Utilisateur</button>
                            <button type="button" onclick="hideCreateUserForm()">Annuler</button>
                        </form>
                    </div>
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