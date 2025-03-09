<?php
include 'configue.php';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modifier'])) {
    $idCmd = $_POST['idCmd'];
    $nouveauStatut = $_POST['statut'];
    
    $sql = "UPDATE commande SET Statut = ? WHERE idCmd = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nouveauStatut, $idCmd]);
    header("Location: admincommand.php");
    exit();
}

$commandes = $pdo->query("SELECT * FROM commande")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Commandes</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #3aafaf;
            color: white;
        }
        select, button {
            padding: 5px 10px;
            border-radius: 5px;
            border: 1px solid #3aafaf;
        }
        button {
            background-color: #3aafaf;
            color: white;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background-color: #2d8a8a;
        }
    </style>
</head>
<body>
    <h1>Gestion des Commandes</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Statut</th>
            <th>ID Client</th>
        </tr>
        <?php foreach ($commandes as $commande): ?>
        <tr>
            <td><?= htmlspecialchars($commande['idCmd']) ?></td>
            <td><?= htmlspecialchars($commande['dateCmd']) ?></td>
            <td>
                <form method="post" action="">
                    <input type="hidden" name="idCmd" value="<?= $commande['idCmd'] ?>">
                    <select name="statut">
                        <option value="En attente" <?= ($commande['Statut'] == 'En attente') ? 'selected' : '' ?>>En attente</option>
                        <option value="En cours" <?= ($commande['Statut'] == 'En cours') ? 'selected' : '' ?>>En cours</option>
                        <option value="Livrée" <?= ($commande['Statut'] == 'Livrée') ? 'selected' : '' ?>>Livrée</option>
                        <option value="Annulée" <?= ($commande['Statut'] == 'Annulée') ? 'selected' : '' ?>>Annulée</option>
                    </select>
                    <button type="submit" name="modifier">Modifier</button>
                </form>
            </td>
            <td><?= htmlspecialchars($commande['idClient']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
