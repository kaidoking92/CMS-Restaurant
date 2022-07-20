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
                <?php
                if($pages > 1){
                ?>
                    <nav>
                        <ul class="pagination">
                            <?php
                            if($currentPage != 1){
                            ?>
                                <li><a href="/products?page=<?= $currentPage - 1 ?>">Précédent</a></li>
                            <?php
                            }
                            for($i = 1;$i<=$pages;$i++):
                            ?>
                                <li><a href="./products?page=<?= $i ?>"><?= $i ?></a></li>
                            <?php
                            endfor;
                            if($currentPage != $pages){
                            ?>
                                <li><a href="./products?page=<?= $currentPage + 1 ?>">Suivant</a></li>
                            <?php
                            }
                            ?>
                        </ul>
                    </nav>
                <?php
                }
                ?>
            </div>
        </div>
    </body>
</html>