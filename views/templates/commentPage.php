<?php
?>
<div>
    <a class="submit" href="index.php?action=admin">Editeur des articles</a>
    <a class="submit" href="index.php?action=admin">Page monitoring</a>
</div>

<h2>Rappel de l'article</h2>
<div>
    <div class="rappel-article"><strong>Titre: </strong><?= $article->getTitle() ?></div>
    <div><strong>Contenue: </strong> <?= $article->getContent(500) ?></div>
</div>

<h2>Liste des commentaires</h2>
<div class="adminArticle">
    <div class="articleLine header">
        <!-- Pseudo-->
        <div class="title">Pseudo</div>
        <!-- Contenu-->
        <div class="content">Contenue</div>
        <!-- tri date de publication -->
        <div class="title">
           Date de publication
        </div>
        <div class="title">
           Action à faire
        </div>
    </div>
    <?php foreach ($comments as $comment) { ?>
        <div class="articleLine">
            <div class="title"><?= $comment->getPseudo() ?></div>
            <div class="content">
                <?= $comment->getContent() ?>
            </div>
            <div class="title">
                <?= Utils::convertDateToFrenchFormat($comment->getDateCreation()) ?>
            </div>
            <div class="title">
                <a class="submit" href="index.php?action=deleteComment&id=<?= $comment->getId() ?>" <?= Utils::askConfirmation("Êtes-vous sûr de vouloir supprimer ce commentaire ?") ?>>Supprimer</a>
            </div>
        </div>
    <?php } ?>
</div>