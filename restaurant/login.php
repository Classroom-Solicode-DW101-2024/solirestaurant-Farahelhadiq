<?php 
require "configue.php";
session_start(); // Démarre la session pour accéder aux variables de session

if (isset($_POST["submit"])) {
    $tel = $_POST["tel"];
    $rusult = tel_existe($tel);
    
    if (empty($rusult)) {
        // Si le téléphone n'existe pas, rediriger vers la page d'inscription
        header("Location: inscription.php");
        exit(); // Assurez-vous que l'exécution s'arrête après la redirection
    } else {
        // Si le téléphone existe, on stocke les informations dans la session
        $_SESSION['client_id'] = $rusult['idClient']; // Utilisez le nom correct pour la variable
        $_SESSION['client_name'] = $rusult['nomCl'] . " " . $rusult['prenomCl']; // Nom complet
        $_SESSION['client'] = $rusult; // Toutes les informations du client
        
        // Vérifier si l'ID du client est valide dans la session avant de rediriger
        if (!isset($_SESSION['client_id'])) {
            die("Erreur : L'ID du client n'est pas défini.");
        }

        // Vérifier si l'ID du client existe dans la base de données
        $sql = "SELECT * FROM client WHERE telCl = :tel";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':tel', $tel);
        $stmt->execute();
        $client = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($client) {
            $_SESSION['client'] = $client;
            $_SESSION["welcome_message"] = "Bienvenue, " . $client['nomCl'] . " 😊!";
        
            header("Location: index.php");
            exit();
        } else {
            echo "<p style='color: red;'>Téléphone non trouvé. Veuillez vous inscrire.</p>";
        }
    }
}
// Récupérer les informations de l'utilisateur
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylelogin.css">
    <title>Se connecter</title>
</head>
<body>
    <form method="POST">
        <label for="tel">Entrez votre téléphone :</label>
        <input type="tel" id="tel" name="tel" required>
        <button name="submit">Se connecter</button>
    </form>

    <?php
    // Afficher un message si le téléphone n'est pas trouvé
    if (isset($rusult) && empty($rusult)) {
        echo "<p style='color: red;'>Le téléphone n'est pas enregistré. Veuillez vous inscrire.</p>";
    }
    ?>
</body>
</html>
