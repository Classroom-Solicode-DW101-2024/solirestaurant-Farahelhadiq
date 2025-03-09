<?php
// Connexion à la base de données
include 'p.php';

// Vérification si un ID est passé en paramètre
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM plat WHERE idPlat = ?");
    $stmt->execute([$id]);
    $plat = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$plat) {
        die("Plat non trouvé");
    }
} else {
    die("ID non fourni");
}

// Mise à jour du plat
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nomPlat'];
    $prix = $_POST['prix'];
    $categorie = $_POST['categoriePlat'];
    $image = $_POST['image'];
    $typeCuisine = $_POST['TypeCuisine'];

    $sql = "UPDATE plat SET nomPlat=?, prix=?, categoriePlat=?, image=?, TypeCuisine=? WHERE idPlat=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $prix, $categorie, $image, $typeCuisine, $id]);

    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Modifier un plat</title>
</head>
<body>
    <h2>Modifier un plat</h2>
    <form method="post">
        <input type="text" name="nomPlat" value="<?= htmlspecialchars($plat['nomPlat']) ?>" required>
        <input type="number" name="prix" value="<?= htmlspecialchars($plat['prix']) ?>" required>
        <input type="text" name="categoriePlat" value="<?= htmlspecialchars($plat['categoriePlat']) ?>" required>
        <input type="text" name="image" value="<?= htmlspecialchars($plat['image']) ?>" required>
        <input type="text" name="TypeCuisine" value="<?= htmlspecialchars($plat['TypeCuisine']) ?>" required>
        <button type="submit">Modifier</button>
    </form>
</body>
</html>
