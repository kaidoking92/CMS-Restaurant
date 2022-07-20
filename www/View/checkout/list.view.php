<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Liste des commandes</title>
        <meta name="description" content="Description de ma page">
    </head>
    <body>

    <h1 class="title">Commandes</h1>

    <div class="table">
        <div class="table-head">
          <div class="tab">
            <p>Toutes les commandes</p>
          </div>
        </div>
      
        <div class="table-body" style="list-style: none;">
            
            <?php foreach ($checkouts as $checkout): ?>
                <?php  $user = $user->findById($checkout->getIdUser()); ?>
                <li>
                    <h1>Id commande : <?= $checkout->getId()?> - <?= $user->getFirstname() ?> <?= $user->getLastname() ?> - <?= $user->getEmail() ?> </h1>
                    <p>
                        <?php 
                            $emptyCheckout_Products = $checkout_products;
                            $checkout_products = $checkout_products->getProductsOfCheckoutById($checkout->getId()) ; ?>

                        <?php    foreach($checkout_products as $checkout_product):

                                $product = $product->findById($checkout_product->getIdProduct());
                        ?>
                               <div style="margin-left: 30px;">Produit : <?= $product->getName(); ?> <img style="width:80px;height:80px" src="Public/img/product/<?= $product->getPicture(); ?>" alt="<?= $product->getName(); ?>"> </div>

                        <?php   endforeach;
                                $checkout_products = $emptyCheckout_Products;
                        ?>
                        
                    </p>
                        
                </li>
                <br>
            <?php endforeach; ?>
            <?php
            if($pages > 1){
            ?>
                <nav>
                    <ul class="pagination">
                        <?php
                        if($currentPage != 1){
                        ?>
                            <li><a href="/checkouts?page=<?= $currentPage - 1 ?>">Précédent</a></li>
                        <?php
                        }
                        for($i = 1;$i<=$pages;$i++):
                        ?>
                            <li><a href="./checkouts?page=<?= $i ?>"><?= $i ?></a></li>
                        <?php
                        endfor;
                        if($currentPage != $pages){
                        ?>
                            <li><a href="./checkouts?page=<?= $currentPage + 1 ?>">Suivant</a></li>
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