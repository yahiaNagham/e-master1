<?php
// Connect to MySQL database
session_start(); // بدء الجلسة لتخزين معرّف المستخدم

require_once '../config/db.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form input values and sanitize them
    $maxProjects = isset($_POST['max-projects']) ? (int)$_POST['max-projects'] : 0;
    $groupSize = isset($_POST['group-size']) ? $_POST['group-size'] : '';
    $maxApplications = isset($_POST['max-applications']) ? (int)$_POST['max-applications'] : 0;
    $proposalDeadline = isset($_POST['proposal-deadline']) ? $_POST['proposal-deadline'] : '';
    $applicationDeadline = isset($_POST['application-deadline']) ? $_POST['application-deadline'] : '';

    // Check if all fields are filled
    if ($maxProjects > 0 && !empty($groupSize) && $maxApplications > 0 && !empty($proposalDeadline) && !empty($applicationDeadline)) {
        // Prepare an SQL statement to insert or update the settings
        $sql = "INSERT INTO pedagogical_settings (max_projects, group_size, max_applications, proposal_deadline, application_deadline) 
                VALUES (:max_projects, :group_size, :max_applications, :proposal_deadline, :application_deadline)
                ON DUPLICATE KEY UPDATE
                    max_projects = VALUES(max_projects),
                    group_size = VALUES(group_size),
                    max_applications = VALUES(max_applications),
                    proposal_deadline = VALUES(proposal_deadline),
                    application_deadline = VALUES(application_deadline)";

        // Prepare the statement
        if ($stmt = $conn->prepare($sql)) {
            // Bind parameters using bindValue() for PDO
            $stmt->bindValue(':max_projects', $maxProjects, PDO::PARAM_INT);
            $stmt->bindValue(':group_size', $groupSize, PDO::PARAM_STR);
            $stmt->bindValue(':max_applications', $maxApplications, PDO::PARAM_INT);
            $stmt->bindValue(':proposal_deadline', $proposalDeadline, PDO::PARAM_STR);
            $stmt->bindValue(':application_deadline', $applicationDeadline, PDO::PARAM_STR);

            // Execute the statement
            if ($stmt->execute()) {
                $_SESSION['message'] = "Settings saved successfully!";
            } else {
                $_SESSION['message'] = "Error: " . $stmt->errorInfo()[2];
            }

            // Close the statement
            $stmt->closeCursor();
        } else {
            $_SESSION['message'] = "Error: " . $conn->errorInfo()[2];
        }
    } else {
        $_SESSION['message'] = "Please fill in all the fields correctly.";
    }

    // Close the connection
    $conn = null; // PDO does not use close() like mysqli
    header("Location: settings_backend.php"); // Redirect to the same page to display the message
    exit();
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>E-Master - Paramètres Pédagogiques</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="./settings_backend.css" />
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .side-menu {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            box-sizing: border-box;
            z-index: 10;
        }
        .side-menu .brand-name h2 {
            font-size: 24px;
            color: #fff;
            margin-bottom: 20px;
            text-align: center;
        }
        .side-menu ul {
            list-style: none;
            padding: 0;
        }
        .side-menu ul li {
            margin: 15px 0;
        }
        .side-menu ul li a {
            color: #ecf0f1;
            text-decoration: none;
            font-size: 16px;
            display: flex;
            align-items: center;
        }
        .side-menu ul li a:hover {
            background-color: #34495e;
            padding-left: 10px;
            border-radius: 5px;
        }
        .side-menu ul li img {
            margin-right: 10px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fff;
            padding: 20px;
            border-bottom: 1px solid #ddd;
        }
        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }
        .search {
            display: flex;
            align-items: center;
            background-color: #f4f4f9;
            border-radius: 30px;
            overflow: hidden;
        }
        .search input {
            border: 1px solid #ddd;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 30px;
            width: 250px;
            outline: none;
        }
        .search input:focus {
            border-color: #3498db;
        }
        .search button {
            background-color: #3498db;
            border: none;
            padding: 10px;
            border-radius: 50%;
            cursor: pointer;
            margin-left: 10px;
        }
        .search button svg {
            width: 20px;
            height: 20px;
            fill: white;
        }
        .search button:hover {
            background-color: #2980b9;
        }
        .user {
            display: flex;
            align-items: center;
        }
        .user img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 15px;
            border: 2px solid #fff;
        }
        .img-case {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid #fff;
            overflow: hidden;
        }
        .img-case img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .container {
            margin-left: 260px;
            padding: 20px;
            flex: 1;
        }
        h1 {
            text-align: center;
            color: #333333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        .form-group {
            margin-bottom: 10px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555555;
        }
        input[type="number"], input[type="date"], select {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #cccccc;
            border-radius: 4px;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color:#3498db;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #3498db;
        }
        




    </style>
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
            
                
                <li><a href="../deconx.php"><i class="fa-solid fa-right-to-bracket"></i>  Deconnexion</a></li>
              </ul>
            </div>
          </div>
        </div>
      </nav>
    
      <header style="border-bottom: solid 5px #3a7e3e;"></header>

      
      
              
                  <section>
                <header>
                    <h1>Paramètres Pédagogiques</h1>
                </header>

                <!-- Content -->
                <div class="container">
                    <!-- Display message -->
                    <?php
                    if (isset($_SESSION['message'])) {
                        echo '<div class="message">' . $_SESSION['message'] . '</div>';
                        unset($_SESSION['message']);
                    }
                    ?>

                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="max-projects">Nombre maximum de projets par enseignant :</label>
                            <input type="number" id="max-projects" name="max-projects" min="1" placeholder="Exemple : 5" required>
                        </div>
                        <div class="form-group">
                            <label for="group-size">Taille des groupes étudiants :</label>
                            <select id="group-size" name="group-size" required>
                                <option value="1">Monôme</option>
                                <option value="2">Binôme</option>
                                <option value="3">Trinôme</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="max-applications">Nombre maximum de candidatures par étudiant :</label>
                            <input type="number" id="max-applications" name="max-applications" min="1" placeholder="Exemple : 3" required>
                        </div>
                        <div class="form-group">
                            <label for="proposal-deadline">Date limite pour les propositions de projets :</label>
                            <input type="date" id="proposal-deadline" name="proposal-deadline" required>
                        </div>
                        <div class="form-group">
                            <label for="application-deadline">Date limite pour les candidatures des étudiants :</label>
                            <input type="date" id="application-deadline" name="application-deadline" required>
                        </div>
                        <button type="submit">Enregistrer les paramètres</button>
                    </form>
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





			<script src="../assets/js/jquery.min.js"></script>
			<script src="../assets/js/browser.min.js"></script>
			<script src="../assets/js/breakpoints.min.js"></script>
			<script src="../assets/js/util.js"></script>
			<script src="../assets/js/main.js"></script>

      <script>
        document.addEventListener("DOMContentLoaded", () => {
        
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

     <script>
            function showCreateUserForm() {
                document.getElementById('user-form').style.display = 'block';
            }

            function hideForm() {
                document.getElementById('user-form').style.display = 'none';
            }
        </script>


</div>
</body>
</html>
