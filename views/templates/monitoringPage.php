<?php
?>

<h2>Page de monitoring</h2>

<div class="adminArticle">
    <?php foreach ($articles as $article) { ?>
        <div class="articleLine">
            <div class="title"><?= $article->getTitle() ?></div>
            <div class="nbOfViews"><?= $article->getViews() ?></div>
        </div>
    <?php } ?>
</div>
