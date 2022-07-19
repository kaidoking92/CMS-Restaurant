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
    </div>
</div>