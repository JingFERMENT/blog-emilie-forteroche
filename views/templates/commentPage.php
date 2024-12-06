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

<form action="/index.php" method="GET" class="form-research">
    <!--utiliser input type hidden pour chercher les paramètres après index.php -->
    <!--paramètre action=ShowCommentPage -->
    <input type="hidden" name="action" value="showCommentPage"></input>
    <!--paramètre id=id_article -->
    <input type="hidden" name="id" value="<?=$article->getId()?>"></input>
    <input type="search" name="keywords" placeholder="Saisir vos mots clés..." value=<?=$keywords ?? ''?>>
    <button type="submit" class="submit" >Rechercher</button>
</form>

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


<!-- page pagination -->
<div class="pagination">
    <!-- page précédente -->
    <a href="index.php?action=showCommentPage&page=<?= $previousPage ?>&id=<?=$article->getId()?>">&laquo;</a>
    <!-- détail des pages -->
    <?php
    for ($counter = 1; $counter <= $nbOfPages; $counter++) {
        if ($counter == $page) {
            echo "<a class=\"active\" href=\"index.php?action=showCommentPage&page=$counter&id=".$article->getId(). "\">$counter</a>";
        } else {
            echo "<a href=\"index.php?action=showCommentPage&page=$counter&id=".$article->getId(). "\">$counter</a>";
        }
    } ?>
    <!-- page suivante -->
    <a href="index.php?action=showCommentPage&page=<?= $nextPage ?>&id=<?=$article->getId()?>">&raquo;</a>
</div>