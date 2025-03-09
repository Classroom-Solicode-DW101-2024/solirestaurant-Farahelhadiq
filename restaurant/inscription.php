<?php
require 'configue.php';
session_start();
$erreurs = [];

if (isset($_POST["btnSubmit"])) {
    $nom = trim($_POST["nom"]);
    $prenom = trim($_POST["prenom"]);
    $tel = trim($_POST["tel"]);
    $tel_is_exist = tel_existe($tel);

    if (!empty($nom) && !empty($prenom) && !empty($tel) && empty($tel_is_exist)) {
        $sql_insert_client = "INSERT INTO CLIENT (nomCl, prenomCl, telCl) VALUES (:nom, :prenom, :tel)";
        $stmt_insert_client = $pdo->prepare($sql_insert_client);
        $stmt_insert_client->bindParam(':nom', $nom);
        $stmt_insert_client->bindParam(':prenom', $prenom);
        $stmt_insert_client->bindParam(':tel', $tel);
        $stmt_insert_client->execute();

        $clientId = $pdo->lastInsertId() +1;

    $sexe = $_POST["sexe"];

    $sexe = $_POST["sexe"];

    if (!empty($nom) && !empty($prenom) && !empty($tel) && empty($tel_is_exist)) {
        $sql_insert_client = "INSERT INTO CLIENT (nomCl, prenomCl, telCl, sexe) VALUES (:nom, :prenom, :tel, :sexe)";
        $stmt_insert_client = $pdo->prepare($sql_insert_client);
        $stmt_insert_client->bindParam(':nom', $nom);
        $stmt_insert_client->bindParam(':prenom', $prenom);
        $stmt_insert_client->bindParam(':tel', $tel);
        $stmt_insert_client->bindParam(':sexe', $sexe);
        $stmt_insert_client->execute();
    
        $clientId = $pdo->lastInsertId();
    
        $_SESSION["client"] = [
            "idClient" => $clientId,
            "nomCl" => $nom,
            "prenomCl" => $prenom,
            "telCl" => $tel,
            "sexe" => $sexe
        ];
    
        $_SESSION["welcome_message"] = "Bienvenue, $nom 😊!";
    
        header("Location: index.php");
        exit();
    }
    

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    <form method="POST">
        <label for="nom">Nom:</label>
        <input type="text" name="nom" id="nom">
        <label for="prenom">Prénom:</label>
        <input type="text" name="prenom" id="prenom">
        <label for="tel">Téléphone:</label>
        <input type="tel" name="tel" id="tel">
        <label for="sexe">Sexe:</label>
        <select name="sexe" id="sexe">
        <option value="Homme">Homme</option>
        <option value="Femme">Femme</option>
        </select>
        <button name="btnSubmit">Je m'inscris!</button>
    </form>
    <a href="login.php">Déjà inscrit ? Connectez-vous ici</a>

    <?php
    foreach ($erreurs as $erreur) {
        echo "<span class='erreur'>" . htmlspecialchars($erreur) . "</span><br>";
    }
    ?>
</body>
</html>
