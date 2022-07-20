<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Liste des catégories</title>
        <meta name="description" content="Description de ma page">
    </head>
    <body>

        <h1>Liste des catégories</h1>
        <ul>
            <?php foreach ($categories as $category): ?>
                <li>
                    <h3>Id : <?= $category->getId()?></h3>
                    <span style="color: #<?= $category->getColor() ?>"> <?= $category->getName(); ?> </span>
                    <a href="/category?id=<?= $category->getId(); ?>"><button>Modifier</button></a>
                    <div id="delete" style="display: inline-block;">
                        <?php $this->includePartial("form", $category->getRemoveCategoryForm()) ?> 
                    </div>
                </li>
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
                        <li><a href="/categories?page=<?= $currentPage - 1 ?>">Précédent</a></li>
                    <?php
                    }
                    for($i = 1;$i<=$pages;$i++):
                    ?>
                        <li><a href="./categories?page=<?= $i ?>"><?= $i ?></a></li>
                    <?php
                    endfor;
                    if($currentPage != $pages){
                    ?>
                        <li><a href="./categories?page=<?= $currentPage + 1 ?>">Suivant</a></li>
                    <?php
                    }
                    ?>
                </ul>
            </nav>
        <?php
        }
        ?>

    </body>
</html>