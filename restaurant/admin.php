<?php 
include 'p.php';  

// Ajouter un plat
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajouter'])) {     
    $nomPlat = $_POST['nomPlat'];     
    $prix = $_POST['prix'];     
    $categoriePlat = $_POST['categoriePlat'];     
    $image = $_POST['image'];     
    $typeCuisine = $_POST['TypeCuisine'];      

    $sql = "INSERT INTO plat (nomPlat, prix, categoriePlat, image, TypeCuisine) VALUES (?, ?, ?, ?, ?)";     
    $stmt = $pdo->prepare($sql);     
    $stmt->execute([$nomPlat, $prix, $categoriePlat, $image, $typeCuisine]);     

    header("Location: index.php");     
    exit(); 
}  

// Récupérer tous les plats 
$plats = $pdo->query("SELECT * FROM plat")->fetchAll(PDO::FETCH_ASSOC); 
?> 

<!DOCTYPE html> 
<html lang="fr"> 
<head>     
    <meta charset="UTF-8">     
    <meta name="viewport" content="width=device-width, initial-scale=1.0">     
    <title>Admin - Plats</title>     
    <link rel="stylesheet" href="style1.css"> 
</head> 
<body>     
    <h1>Gestion des Plats</h1>     
    <button onclick="document.getElementById('form-ajout').style.display='block'">Ajouter un plat</button>     

    <div id="form-ajout" style="display:none;">         
        <form method="post" action="">             
            <input type="text" name="nomPlat" placeholder="Nom du plat" required>             
            <input type="number" name="prix" placeholder="Prix" required>             
            <input type="text" name="categoriePlat" placeholder="Catégorie" required>             
            <input type="text" name="image" placeholder="URL de l'image" required>             
            <input type="text" name="TypeCuisine" placeholder="Type de cuisine" required>             
            <button type="submit" name="ajouter">Ajouter</button>         
        </form>     
    </div>     

    <table>         
        <tr>             
            <th>Nom</th><th>Prix</th><th>Catégorie</th><th>Image</th><th>TypeCuisine</th><th>Actions</th>         
        </tr>         
        <?php foreach ($plats as $plat): ?>         
        <tr>             
            <td><?= htmlspecialchars($plat['nomPlat']) ?></td>             
            <td><?= htmlspecialchars($plat['prix']) ?> $</td>             
            <td><?= htmlspecialchars($plat['categoriePlat']) ?></td>             
            <td><img src="<?= htmlspecialchars($plat['image']) ?>" width="50"></td>             
            <td><?= htmlspecialchars($plat['TypeCuisine']) ?></td>             
            <td>                 
                <a href="update.php?id=<?= $plat['idPlat'] ?>">Modifier</a> |                 
                <a href="delete.php?id=<?= $plat['idPlat'] ?>" onclick="return confirm('Supprimer ce plat?')">Supprimer</a>             
            </td>         
        </tr>         
        <?php endforeach; ?>     
    </table> 
</body> 
</html>
