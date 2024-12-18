<?php 

class ArticleController 
{
    /**
     * Affiche la page d'accueil.
     * @return void
     */
    public function showHome() : void
    {
        $articleManager = new ArticleManager();
        $articles = $articleManager->getAllArticles();

        $view = new View("Accueil");
        $view->render("home", ['articles' => $articles]);
    }

    /**
     * Affiche le détail d'un article.
     * @return void
     */
    public function showArticle() : void
    {
        // Récupération de l'id de l'article demandé.
        $id = Utils::request("id", -1);
        
        $articleManager = new ArticleManager();
        
        // récupérer l'article en question 
        $article = $articleManager->getArticleById($id);
        
        // incrémenter les vues de l'article en question
        $articleManager->incrementArticleViews($article);
        
        if (!$article) {
            throw new Exception("L'article demandé n'existe pas.");
        }

        $commentManager = new CommentManager();
        // récupéer tous les commentaires liés à cet article 
        $comments = $commentManager->getAllCommentsByArticleId($id);
       
        $view = new View($article->getTitle());
        $view->render("detailArticle", ['article' => $article, 'comments' => $comments]);
    }

    /**
     * Affiche le formulaire d'ajout d'un article.
     * @return void
     */
    public function addArticle() : void
    {
        $view = new View("Ajouter un article");
        $view->render("addArticle");
    }

    /**
     * Affiche la page "à propos".
     * @return void
     */
    public function showApropos() {
        $view = new View("A propos");
        $view->render("apropos");
    }
}