<?php 
$host = 'localhost';
$dbname = 'solirestaurant';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);//Cela garantit que la base de données accepte les caractères spéciaux (ex. emojis, accents...).
     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch (PDOException $e) {
    echo "erreur conenction " . $e->getMessage();
}
?>