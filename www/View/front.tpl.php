<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Template FRONT</title>
    <meta name="description" content="Description de ma page">
</head>
<body>
    <a href="/"><button>Accueil</button></a>
    <a href="/showallproducts"><button>Catalogue</button></a>
    <a href='/shoppingCart'><button>Panier</button></a>
    <a href="/<?= isset($_SESSION['auth'])?'profile':'login'; ?>
    ">
        <button><?= isset($_SESSION['auth'])?'Profil':'Se Connecter'; ?></button></a>
    <?php
    if(isset($_SESSION['auth'])){
    ?>
        <a href="/logout"><button>Se Déconnecter</button></a>
    <?php
    }
    ?>

    <?php include "View/".$this->view.".view.php"; ?>

</body>
</html>