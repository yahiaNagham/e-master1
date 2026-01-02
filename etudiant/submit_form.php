<?php
// submit_form.php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: conx.php");
    exit;
}

require_once '../config/db.php'; // استيراد ملف الاتصال بقاعدة البيانات

$user_id = $_SESSION['user_id'];

// جمع البيانات من النموذج
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$specialite = $_POST['specialite'];
$type_equipe = $_POST['type_equipe'];
$etudiant2_nom = $_POST['etudiant2_nom'] ?? null;
$etudiant2_prenom = $_POST['etudiant2_prenom'] ?? null;
$etudiant3_nom = $_POST['etudiant3_nom'] ?? null;
$etudiant3_prenom = $_POST['etudiant3_prenom'] ?? null;
$projet1_rank = $_POST['projet1_rank'];
$projet2_rank = $_POST['projet2_rank'];
$projet3_rank = $_POST['projet3_rank'];
$projet4_rank = $_POST['projet4_rank'];

// إدخال البيانات في قاعدة البيانات
try {
    $sql = "INSERT INTO formulaire (nom, prenom, specialite, type_equipe, etudiant2_nom, etudiant2_prenom, etudiant3_nom, etudiant3_prenom, projet1_rank, projet2_rank, projet3_rank, projet4_rank) 
            VALUES (:nom, :prenom, :specialite, :type_equipe, :etudiant2_nom, :etudiant2_prenom, :etudiant3_nom, :etudiant3_prenom, :projet1_rank, :projet2_rank, :projet3_rank, :projet4_rank)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':nom' => $nom,
        ':prenom' => $prenom,
        ':specialite' => $specialite,
        ':type_equipe' => $type_equipe,
        ':etudiant2_nom' => $etudiant2_nom,
        ':etudiant2_prenom' => $etudiant2_prenom,
        ':etudiant3_nom' => $etudiant3_nom,
        ':etudiant3_prenom' => $etudiant3_prenom,
        ':projet1_rank' => $projet1_rank,
        ':projet2_rank' => $projet2_rank,
        ':projet3_rank' => $projet3_rank,
        ':projet4_rank' => $projet4_rank,
    ]);

    // إعادة التوجيه إلى صفحة النجاح
    header("Location: success.php");
    exit;
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>