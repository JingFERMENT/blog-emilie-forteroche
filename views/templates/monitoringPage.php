<?php
?>
<a class="submit" href="index.php?action=admin">Editer des articles</a>

<h2>Page de monitoring</h2>

<div class="adminArticle">
    <div class="articleLine">
        <!-- tri titre -->
        <div class="title">
            <a href="index.php?action=showMonitoringPage&sort=title&order=asc">Titre 
                <i class="fa-solid fa-caret-up 
                    <?=$sortColumn === "title" && $sortOrder === "asc" ? "active" :"";
                    ?>">
                </i>
            </a>
            <a href="index.php?action=showMonitoringPage&sort=title&order=desc">
                <i class="fa-solid fa-caret-down 
                    <?=$sortColumn === "title" && $sortOrder === "desc" ? "active": "";
                    ?>">
                </i>
            </a>
        </div>
        <!-- tri view -->
        <div class="title">
            <a href="index.php?action=showMonitoringPage&sort=views&order=asc">Nombre de vue 
                <i class="fa-solid fa-caret-up
                 <?=$sortColumn === "views" && $sortOrder === "asc" ? "active" :"";
                    ?>">
                </i>
            </a>
            <a href="index.php?action=showMonitoringPage&sort=views&order=desc">
                <i class="fa-solid fa-caret-down
                    <?=$sortColumn === "views" && $sortOrder === "desc" ? "active": "";
                    ?>"></i>
            </a>
        </div>
        <!-- tri commentaire -->
        <div class="title">
            <a href="index.php?action=showMonitoringPage&sort=nbOfComments&order=asc">Commentaire 
                <i class="fa-solid fa-caret-up
                <?=$sortColumn === "nbOfComments" && $sortOrder === "asc" ? "active": "";
                ?>">
                </i>
            </a>
            <a href="index.php?action=showMonitoringPage&sort=nbOfComments&order=desc">
                <i class="fa-solid fa-caret-down
                <?=$sortColumn === "nbOfComments" && $sortOrder === "desc" ? "active": "";
                ?>">
                </i>
            </a>
        </div>
        <!-- tri date de publication -->
        <div class="title">
            <a href="index.php?action=showMonitoringPage&sort=date_creation&order=asc">Date publication 
                <i class="fa-solid fa-caret-up
                <?=$sortColumn === "date_creation" && $sortOrder === "asc" ? "active" :"";
                ?>"></i>
            </a>
            <a href="index.php?action=showMonitoringPage&sort=date_creation&order=desc">
                <i class="fa-solid fa-caret-down
                <?=$sortColumn === "date_creation" && $sortOrder === "desc" ? "active" :"";
                ?>"></i>
            </a>
        </div>
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