<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Liste des catégories</title>
        <meta name="description" content="Description de ma page">
    </head>
    <body>

        <h1>Liste des catégories</h1>
        <a href="/category-register">Ajouter une catégorie</a>
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

    </body>
</html>