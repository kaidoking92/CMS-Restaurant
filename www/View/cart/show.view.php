<ul>
    <?php foreach($products as $product): ?>
     <li> <?=$product->getName() ?> </li>   
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
                <li><a href="/shoppingCart?page=<?= $currentPage - 1 ?>">Précédent</a></li>
            <?php
            }
            for($i = 1;$i<=$pages;$i++):
            ?>
                <li><a href="./shoppingCart?page=<?= $i ?>"><?= $i ?></a></li>
            <?php
            endfor;
            if($currentPage != $pages){
            ?>
                <li><a href="./shoppingCart?page=<?= $currentPage + 1 ?>">Suivant</a></li>
            <?php
            }
            ?>
        </ul>
    </nav>
<?php
}
?>

<?php 
    if(count($products)){
        $this->includePartial("form", $checkout->getCheckoutForm()) ;
    }else{
        echo "Panier Vide";
    }
?>