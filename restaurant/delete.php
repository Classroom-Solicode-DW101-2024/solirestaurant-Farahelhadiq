<?php
// Connexion à la base de données
include 'p.php';

// Vérification si un ID est passé
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Suppression du plat
    $sql = "DELETE FROM plat WHERE idPlat = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
}

// Redirection vers la page principale
header("Location: index.php");
exit();
?>
