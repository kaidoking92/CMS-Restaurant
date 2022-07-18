<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Liste des produits</title>
        <meta name="description" content="Description de ma page">
    </head>
    <body>

    <h1 class="title">Produits</h1>

        <div class="table">
            <div class="table-head">
                <div class="tab">
                    <p>Tous les produits</p>
                </div>
            </div>
            <a href="/product-register">Ajouter un produit</a>
            <div>
                <ul>
                    <?php foreach ($products as $product): ?>
                        <li>
                            <h3>Id : <?= $product->getId()?></h3>
                            <span> <?= $product->getName(); ?> </span>
                            <br>
                             <img style="width:100px;height:100px" src="Public/img/product/<?= $product->getPicture(); ?>" alt="<?= $product->getName(); ?>"> 
                            <p> <?= $product->getDescription(); ?> </p>
                            <span> Prix : <?= $product->getPrice(); ?> €</span>
                            <br>
                            <span> <?= $product->getStock() == 0 ? 'Rupture' : 'Stock : '.$product->getStock(); ?> </span>
                            <a href="/product?id=<?= $product->getId(); ?>"><button>Modifier</button></a>
                            <div id="delete" style="display: inline-block;">
                                <?php $this->includePartial("form", $product->getRemoveProductForm()) ?> 
                            </div>
                        </li>
                        <br>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </body>
</html>