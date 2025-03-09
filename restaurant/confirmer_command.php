<?php
session_start();
require("configue.php");

// Vérifiez si le client est connecté
if (isset($_SESSION['client_name'])) {
    // Afficher le nom complet du client
    echo "Bienvenue, " . $_SESSION['client_name'];
} else {
    // Si le client n'est pas connecté, rediriger vers la page de login
    header("Location: login.php");
    exit();
}
// Vérifiez que le panier n'est pas vide
if (empty($_SESSION['panier'])) {
    // Si le panier est vide, redirigez vers la page du panier
    header("Location: panier.php");
    exit();
}

// Vérifiez que l'ID du client est bien dans la session
if (!isset($_SESSION['client_id'])) {
    // Si l'ID du client n'est pas dans la session, redirigez vers la page de login
    header("Location: login.php");
    exit();
}

// Récupérer l'ID du client depuis la session
$idClient = $_SESSION['client_id'];  // L'ID du client est stocké dans la session après la connexion

// Générer un ID de commande unique
$idCmd = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT); // Génère un nombre aléatoire entre 1 et 9999 avec 4 chiffres

try {
    // Insérer la commande dans la base de données avec l'ID du client
    $sql = "INSERT INTO commande (idCmd, idClient, dateCmd, Statut) VALUES (:idCmd, :idClient, NOW(), 'en attente')";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idCmd', $idCmd);
    $stmt->bindParam(':idClient', $idClient);  // Lier l'ID du client à la commande
    $stmt->execute();

    // Insérer les plats dans la commande_plat
    foreach ($_SESSION['panier'] as $idPlat => $item) {
        $sql = "INSERT INTO commande_plat (idPlat, idCmd, qte) VALUES (:idPlat, :idCmd, :qte)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idPlat', $idPlat);
        $stmt->bindParam(':idCmd', $idCmd);
        $stmt->bindParam(':qte', $item['quantite']);
        $stmt->execute();
    }

    // Vider le panier après la commande
    $_SESSION['panier'] = [];
    $message = "Commande #{$idCmd} confirmée avec succès!";

} catch (PDOException $e) {
    // Si une erreur se produit, afficher le message d'erreur
    $message = "Erreur lors de la confirmation: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Confirmation</title>
    <link rel="stylesheet" href="style.css?v=1">
    <style>
        :root {
            --primary: #3aafaf;
            --primary-dark: #2d8a8a;
            --text: white;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            color: #333;
        }
        
        main {
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        h1 {
            color: var(--primary);
            font-size: 28px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        p {
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .btn {
            display: block;
            width: max-content;
            margin: 0 auto;
            padding: 12px 24px;
            background-color: var(--primary);
            color: var(--text);
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            text-align: center;
            transition: background-color 0.3s ease;
        }
        
        .btn:hover {
            background-color: var(--primary-dark);
        }
        
        .success-message {
            padding: 15px;
            background-color: #e7f7f7;
            border-left: 4px solid var(--primary);
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <main>
        <h1>Confirmation de commande</h1>
        <p><?= $message ?></p>
        <a href="index.php" class="btn">Retour à l'accueil</a>
    </main>
</body>
</html>
