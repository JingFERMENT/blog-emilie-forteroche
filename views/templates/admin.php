<?php

/** 
 * Affichage de la partie admin : liste des articles avec un bouton "modifier" pour chacun. 
 * Et un formulaire pour ajouter un article. 
 */
?>
<div>
    <a class="submit" href="index.php?action=showMonitoringPage">Page de monitoring</a>
    <a class="submit" href="index.php?action=showUpdateArticleForm">Ajouter un article</a>
</div>
<h2>Edition des articles</h2>

<div class="adminArticle">
    <div class="articleLine header">
        <div class="title">Titre</div>
        <div class="content">Contenu</div>
        <div class="actions" colspan="2">Action à faire</div>
        <div class="comment">Nombre de commentaire</div>
    </div>
    <?php foreach ($articles as $article) { ?>
        <div class="articleLine">
            <div class="title"><?= $article->getTitle() ?></div>
            <div class="content"><?= $article->getContent(200) ?></div>
            <div><a class="submit" href="index.php?action=showUpdateArticleForm&id=<?= $article->getId() ?>">Modifier</a></div>
            <div><a class="submit" href="index.php?action=deleteArticle&id=<?= $article->getId() ?>" <?= Utils::askConfirmation("Êtes-vous sûr de vouloir supprimer cet article ?") ?>>Supprimer</a></div>
            <div class="title-comments">
                <?php if ($article->getNbOfComments() === 0) {
                    echo "0";
                } else {?>
                    <?= $article->getNbOfComments() ?>
                    <div>
                        <p>Voir le détail</p>
                        <a href="index.php?action=showCommentPage&id=<?= $article->getId() ?>"><i class="fa-solid fa-magnifying-glass-plus"></i></a>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
</div>