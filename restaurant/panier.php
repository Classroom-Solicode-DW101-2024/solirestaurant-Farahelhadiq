<?php
session_start();
require("configue.php");

if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];//initialise comme un tableau vide.
}

// Add item to cart
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM plat WHERE idPlat = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $plat = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($plat) {
        if (isset($_SESSION['panier'][$id])) {
            $_SESSION['panier'][$id]['quantite']++;
        } else {
            $_SESSION['panier'][$id] = [
                'nom' => $plat['nomPlat'],
                'prix' => $plat['prix'],
                'quantite' => 1,
                'image' => $plat['image']
            ];
        }
    }
    header("Location: index.php");
    exit;
}

// Update quantity
if (isset($_POST['update'])) {
    foreach ($_POST['quantite'] as $id => $qte) {
        if ($qte <= 0) {
            unset($_SESSION['panier'][$id]);
        } else {
            $_SESSION['panier'][$id]['quantite'] = $qte;
        }
    }
}

// Remove item
if (isset($_GET['remove'])) { //paramètre
    unset($_SESSION['panier'][$_GET['remove']]);
    header("Location: panier.php");
    exit;
}

// Calculate total
$total = 0;
foreach ($_SESSION['panier'] as $item) {
    $total += $item['prix'] * $item['quantite'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Panier</title>
    <link rel="stylesheet" href="panie.css?v=4">
</head>
<body>
    <header>
        <nav>
            <!-- Same navigation as index.php -->
        </nav>
    </header>
    
    <main>
        <h1>Votre Panier</h1>
        <?php if (empty($_SESSION['panier'])): ?>
            <p>Votre panier est vide</p>
        <?php else: ?>
            <form method="post">
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Plat</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['panier'] as $id => $item): ?>
                            <tr>
                                <td><img src="<?= $item['image'] ?>" width="50"></td>
                                <td><?= $item['nom'] ?></td>
                                <td><?= $item['prix'] ?>$</td>
                                <td>
                                    <input type="number" name="quantite[<?= $id ?>]" 
                                           value="<?= $item['quantite'] ?>" min="0">
                                </td>
                                <td><?= $item['prix'] * $item['quantite'] ?>$</td>
                                <td>
                                    <a href="panier.php?remove=<?= $id ?>" 
                                       class="btn btn-danger">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <p>Total: <?= $total ?>$</p>
                <button><a href="inscription.php" class="btn btn-primary">Confirmer la commande</a></button>
            </form>
        <?php endif; ?>
        <button><a href="index.php" class="btn">Continuer vos achats</a></button>
    </main>
</body>
</html> 