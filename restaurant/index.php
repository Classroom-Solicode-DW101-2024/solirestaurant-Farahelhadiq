   <?php
   
   if (isset($_SESSION['client'])) {
       $client = $_SESSION['client'];
       $sexe = $client['sexe'];
       $emoji = ($sexe == "Homme") ? "👨" : "👩";
       echo "<h1>Bienvenue, {$client['nomCl']} {$emoji}</h1>";
   }
   
  session_start();
  require("configue.php");
if(isset($_POST["searchBtn"]) && (!empty($_POST["TypeCuisine"]) || !empty($_POST["Catégorie"]))){
    $type_s = $_POST["TypeCuisine"];
    $categorie_s = $_POST["Catégorie"];
    if(!empty($type_s) && !empty($categorie_s)){
        $sql = "SELECT * FROM plat WHERE TypeCuisine = :type_s AND categoriePlat = :categorie_s";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':type_s', $type_s);
        $stmt->bindParam(':categorie_s', $categorie_s);
    }else if(!empty($type_s)){
        $sql = "SELECT * FROM plat WHERE TypeCuisine = :type_s";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':type_s', $type_s);
    }else{
        $sql = "SELECT * FROM plat WHERE categoriePlat = :categorie_s";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':categorie_s', $categorie_s);
    }
    $stmt->execute();
    }else{
        $sql = "SELECT * FROM plat";
        $stmt = $pdo->query($sql);
    }


    $elements = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $elementstypec=[];
    $categories='';
    if (count( $elements)>0){//count=lenght
    foreach($elements as $element){
      $elementstypec[$element["TypeCuisine"]][]=$element;
    }
    foreach($elementstypec as $typecuisine => $elementss){
        $categories.="<section class='cuisine-section'>";
        $categories .= "<h1 class='cuisine-title'>".$typecuisine."</h1>";
        $categories .= "<div class='ko cuisine-container'>";
        foreach($elementss as $element){
            $categories .= "<div class='categorie dish-card'>";
            $categories .= "<img src='{$element['image']}' class='dish-image'>";
            $categories .= "<h2 class='dish-name'>".$element['nomPlat']."</h2>";
            $categories .= "<h2 class='cuisine-type'>".$element['TypeCuisine']."</h2>";
            $categories .= "<h2 class='dish-price'>".$element['prix']."$</h2>";
            $categories .= '<button class="btn btn-primary order-button" 
            onclick="window.location.href=\'panier.php?id=' . $element["idPlat"] . '\'">Commander</button>';

// Modified header:
            $categories.= "</div>";
        }
        $categories.= "</div>";
        $categories.="</section>";
    }

    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css?v=7">
    <script src="https://kit.fontawesome.com/your-kit-id.js" crossorigin="anonymous"></script>
</head>
<body>
    <header>
       <nav>
        <div class="image">
            <img src="logo.png">
        </div>
        <div class="links">
        <div class="link"><a href="home">Home</a></div>
        <div class="link"><a href="menu">Menu</a></div>
        <div class="link"><a href="inscription.php">inscription</a></div>
        <div class="link"><a href="contact">Contact</a></div>
        <div class="link">
                <a href="panier.php">
                    <i class="fa-solid fa-cart-shopping"></i> Panier 
                    <?php if (!empty($_SESSION['panier'])): ?>
                        (<?= count($_SESSION['panier']) ?>)
                    <?php endif; ?>
                </a>
            </div>
        <div class="rechercher">
        <div class="search-container">
            <form action="" method="post">
            <select id="searchCriteria" name="Catégorie">
            <option value="">tous Catégorie</option>
                <option value="entrée">entrée</option>
                <option value="Plat principal">Plat principal </option>
                <option value="dessert">dessert </option>
            </select>
            <select id="searchCriteria" name="TypeCuisine">
            <option value=""> tous TypeCuisine</option>
                <option value="Italienne"> Italienne</option>
                <option value="Française"> Française </option>
                <option value="Marocaine"> Marocaine </option>
                <option value="Chinoise"> Chinoise </option>
                <option value="Espagnole"> Espagnole </option>
            </select>
            <button id="searchBtn" name="searchBtn"><i class="fa-solid fa-magnifying-glass" name="searchBtn"></i> Search</button>
            <button id="clearBtn"><i class="fa-regular fa-circle-xmark" name="Clear"></i> Clear</button>
            </form>
        </div>

       </nav>
    </header>
    <section class="introduction">
    <div class="intro-content">
        <h1>Bienvenue au Voyage des Saveurs</h1>
        <p>Découvrez des plats authentiques de toutes les cultures du monde, préparés avec passion et créativité.</p>
        <button class="btn-reservation">Réservez votre table</button>
    </div>
</section>

    <main>
        <div class="plats">
            <?= $categories ?>
        </div>
    </main>
</body>
</html> 