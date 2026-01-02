<?php
// Start session for messages
session_start();

// Include database configuration
require_once '../config/db.php';

// Check database connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle Approve and Reject actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // CSRF protection - check token validity
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
        if (isset($_POST['action'], $_POST['project_id'])) {
            $project_id = intval($_POST['project_id']);
            $action = $_POST['action'];

            // Determine new status
            $etat = ($action === 'approve') ? 'Approuvé' : 'Rejeté';

            // Update project status
            $sql = "UPDATE projets SET etat = :etat WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':etat', $etat);
            $stmt->bindParam(':id', $project_id);

            if ($stmt->execute()) {
                $_SESSION['message'] = "Le statut du projet a été mis à jour avec succès.";
                $_SESSION['message_type'] = 'success'; // Success message
            } else {
                $_SESSION['message'] = "Erreur lors de la mise à jour.";
                $_SESSION['message_type'] = 'error'; // Error message
            }

            // Redirect back to the page
            header("Location: consulterProj.php");
            exit;
        }
    } else {
        $_SESSION['message'] = "Erreur de sécurité, CSRF token invalide.";
        $_SESSION['message_type'] = 'error'; // CSRF error
    }
}

// Generate a new CSRF token for the session
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Create a random CSRF token
}

// Fetch projects from the database
$sql = "SELECT id, project_name, etat FROM projets";
$stmt = $conn->prepare($sql);
$stmt->execute();
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



<!DOCTYPE HTML>
<html>
	<head>
  <title>E-Master - Consulter Projets</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />

		<link rel="stylesheet" href="./consulterProj.css" />

    <link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            color: #fff;
            background-color: #4CAF50;
            border-radius: 5px;
        }
        .message.error {
            background-color: #f44336;
        }
        .etat {
            font-weight: bold;
        }
        button {
            padding: 5px 10px;
            margin-right: 5px;
            border: none;
            cursor: pointer;
        }
        button.approve {
            background-color: #4CAF50;
            color: white;
        }
        button.reject {
            background-color: #f44336;
            color: white;
        }
        button:hover {
            opacity: 0.8;
        }
    </style>
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
                    
                    <div class="dropdown-menu">
                      <ul>
                    
                        
                        <li><a href="../deconx.php"><i class="fa-solid fa-right-to-bracket"></i>  Deconnexion</a></li>
                      </ul>
                    </div>
                  </div>
                </div>
              </nav>
            
              <header style="border-bottom: solid 5px #3a7e3e;"></header>



							<!-- Content -->
								<section>
          
                <h1>Gestion des Projets</h1>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="message <?= isset($_SESSION['message_type']) ? $_SESSION['message_type'] : 'success'; ?>">
            <?= htmlspecialchars($_SESSION['message']); ?>
        </div>
        <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>Nom du Projet</th>
                <th>État</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($projects) > 0): ?>
                <?php foreach ($projects as $project): ?>
                    <tr>
                        <td><?= htmlspecialchars($project['project_name']); ?></td>
                        <td class="etat" style="color: <?= $project['etat'] === 'Approuvé' ? 'green' : ($project['etat'] === 'Rejeté' ? 'red' : 'black'); ?>;">
                            <?= htmlspecialchars($project['etat']); ?>
                        </td>
                        <td>
                            <form method="post" action="">
                                <input type="hidden" name="project_id" value="<?= $project['id']; ?>">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                                <button type="submit" name="action" value="approve" class="approve">Approuver</button>
                                <button type="submit" name="action" value="reject" class="reject">Rejeter</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">Aucun projet trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>



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

