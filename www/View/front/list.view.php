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
                    <a href="/pageProduct?id=<?= $product->getId(); ?>">
                    <h3> <?= $product->getName(); ?> </h3>
                    <br>
                     <img style="width:100px;height:100px" src="public/img/product/<?= $product->getPicture(); ?>" alt="<?= $product->getName(); ?>">
                    </a>
                    <br>
                    <span> Prix : <?= $product->getPrice(); ?> €</span>
                    <br>
                    <span> <?= $product->getStock() == 0 ? 'Rupture' : 'Stock : '.$product->getStock(); ?> </span>
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
                        <li><a href="/showallproducts?page=<?= $currentPage - 1 ?>">Précédent</a></li>
                    <?php
                    }
                    for($i = 1;$i<=$pages;$i++):
                    ?>
                        <li><a href="./showallproducts?page=<?= $i ?>"><?= $i ?></a></li>
                    <?php
                    endfor;
                    if($currentPage != $pages){
                    ?>
                        <li><a href="./showallproducts?page=<?= $currentPage + 1 ?>">Suivant</a></li>
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