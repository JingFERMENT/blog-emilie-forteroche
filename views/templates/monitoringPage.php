<?php
?>
<a class="submit" href="index.php?action=admin">Editer des articles</a>

<h2>Page de monitoring</h2>

<div class="adminArticle">
    <div class="articleLine">
        <div class="title">Titre</div>
        <div class="title">Nombre de vue</div>
        <div class="title">Commentaire</div>
        <div class="title">Date de publication</div>
    </div>
    <?php foreach ($articles as $article) { ?>
        <div class="articleLine">
            <div class="title"><?= $article->getTitle() ?></div>
            <div class="title"><?= $article->getViews() ?></div>
            <div class="title"><?= $article->getNbOfComments() ?></div>
            <div class="title"><?= Utils::convertDateToFrenchFormat($article->getDateCreation()) ?></div>
        </div>
    <?php } ?>
</div>