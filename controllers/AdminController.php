<?php 
/**
 * Contrôleur de la partie admin.
 */

class AdminController {

    /**
     * Affiche la page d'administration.
     * @return void
     */
    public function showAdmin() : void
    {
        // On vérifie que l'utilisateur est connecté.
        $this->checkIfUserIsConnected();

        // On récupère les articles.
        $articleManager = new ArticleManager();
        $articles = $articleManager->getAllArticles();

        // On affiche la page d'administration.
        $view = new View("Administration");
        $view->render("admin", [
            'articles' => $articles
        ]);
    }

    /**
     * Affichage de la page monitoring.
     * @return void
     */
    public function showMonitoringPage(): void
    {
        $this->checkIfUserIsConnected();

        // on récupère le choix des tris et nettoyer les données
        $sortColumn = filter_input(INPUT_GET, 'sort', FILTER_SANITIZE_SPECIAL_CHARS);
        $sortOrder = filter_input(INPUT_GET, 'order', FILTER_SANITIZE_SPECIAL_CHARS);

        // Liste des colonnes autorisées
        if(!in_array($sortColumn, VALID_COLUMNS)){
            $sortColumn = 'date_creation'; // tri par défaut
        } 

        if(!in_array($sortOrder, VALID_ORDERS)){
            $sortOrder = 'DESC';// tri par défaut
        }

        // conditon booléenne pour savoir si le tri est sur le nombre des commentaires
        $withComments = ($sortColumn === "nbOfComments");
        
        // on récupère les articles triés
        $articleManager = new ArticleManager();
    
        $articles = $articleManager->getAllArticles($sortColumn, $sortOrder, $withComments);
        
        // Transmettre les variables à la vue
        $view = new View("Page de monitoring");
        $view->render("monitoringPage",[
            'articles' => $articles,
            'sortColumn' => $sortColumn,
            'sortOrder' => $sortOrder,
        ]);
    }

    public function showCommentPage(): void{

        $this->checkIfUserIsConnected();
        
        // Récupère l'ID de l'article depuis l'URL et le filtre pour éviter les injections
        $id = intval(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT));

         // Récupère le numéro de la page actuelle depuis l'URL et le filtre
        $page = intval(filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT));
        
         // Récupère les mots-clés de recherche éventuels depuis l'URL
        $keywords = filter_input(INPUT_GET, 'keywords', FILTER_SANITIZE_SPECIAL_CHARS);

        // Si la page est égale à 0, elle est définie par défaut sur 1
        if($page === 0) {
            $page = 1;
            $nextPage = 2;
        }

        // Calcule le numéro de la page précédente
        if ($page > 1) {
            $previousPage = $page - 1;
        } else {
            $previousPage = 1;
        }

        // Nombre de commentaires à afficher par page
        $limit = COMMENTS_PER_PAGE;

        // L'offset indique combien de commentaires ignorer avant de récupérer les résultats.
        $offset = ($page - 1) * $limit;
        
        // Récupère l'article associé à l'ID
        $articleManager = new ArticleManager();
        $article = $articleManager->getArticleById($id);

        // Si l'article n'existe pas, on déclenche une exception
        if (!$article) {
            throw new Exception("Article non trouvé: $id");
        }

        // on récupère les commentaires liés à cet article
        $commentManager = new CommentManager();
        $comments= $commentManager->getAllCommentsByArticleId($id, $limit, $offset, $keywords);
        
        // Compte le nombre total de commentaires pour cet article
        $nbOfComments = $commentManager->countCommentsForArticle($id);

        // Calcule le nombre total de pages nécessaires pour afficher tous les commentaires
        $nbOfPages = ceil($nbOfComments / COMMENTS_PER_PAGE);

        // Détermine le numéro de la page suivante
        // Si on est sur la dernière page, la page suivante reste la même
        if ($page >= $nbOfPages) {
            $nextPage = $page;
        } else {
            $nextPage = $page + 1;
        }

        // Si aucun commentaire n'est trouvé pour cette page, on lève une exception
        if($comments == []) {
            throw new Exception ("Page non trouvé : $page");
        }

         // Transmet les données nécessaires à la vue
        $view = new View("Page de commentaire");
        $view->render("commentPage",[
            'comments' => $comments,
            'article' => $article,
            'page'=> $page,
            'nbOfPages'=>$nbOfPages,
            'nextPage'=>$nextPage,
            'previousPage' =>$previousPage
        ]);
    }

    /**
     * Vérifie que l'utilisateur est connecté.
     * @return void
     */
    private function checkIfUserIsConnected() : void
    {
        // On vérifie que l'utilisateur est connecté.
        if (!isset($_SESSION['user'])) {
            Utils::redirect("connectionForm");
        }
    }

    /**
     * Affichage du formulaire de connexion.
     * @return void
     */
    public function displayConnectionForm(): void
    {
        $view = new View("Connexion");
        $view->render("connectionForm");
    }

    /**
     * Connexion de l'utilisateur.
     * @return void
     */
    public function connectUser(): void
    {
        // On récupère les données du formulaire.
        $login = Utils::request("login");
        $password = Utils::request("password");

        // On vérifie que les données sont valides.
        if (empty($login) || empty($password)) {
            throw new Exception("Tous les champs sont obligatoires. 1");
        }

        // On vérifie que l'utilisateur existe.
        $userManager = new UserManager();
        $user = $userManager->getUserByLogin($login);
        if (!$user) {
            throw new Exception("L'utilisateur demandé n'existe pas.");
        }

        // On vérifie que le mot de passe est correct.
        if (!password_verify($password, $user->getPassword())) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            throw new Exception("Le mot de passe est incorrect.");
        }

        // On connecte l'utilisateur.
        $_SESSION['user'] = $user;
        $_SESSION['idUser'] = $user->getId();

        // On redirige vers la page d'administration.
        Utils::redirect("admin");
    }

    /**
     * Déconnexion de l'utilisateur.
     * @return void
     */
    public function disconnectUser(): void
    {
        // On déconnecte l'utilisateur.
        unset($_SESSION['user']);

        // On redirige vers la page d'accueil.
        Utils::redirect("home");
    }

    /**
     * Affichage du formulaire d'ajout d'un article.
     * @return void
     */
    public function showUpdateArticleForm(): void
    {
        $this->checkIfUserIsConnected();

        // On récupère l'id de l'article s'il existe.
        $id = Utils::request("id", -1);

        // On récupère l'article associé.
        $articleManager = new ArticleManager();
        $article = $articleManager->getArticleById($id);

        // Si l'article n'existe pas, on en crée un vide. 
        if (!$article) {
            $article = new Article();
        }

        // On affiche la page de modification de l'article.
        $view = new View("Edition d'un article");
        $view->render("updateArticleForm", [
            'article' => $article
        ]);
    }

    /**
     * Ajout et modification d'un article. 
     * On sait si un article est ajouté car l'id vaut -1.
     * @return void
     */
    public function updateArticle(): void
    {
        $this->checkIfUserIsConnected();

        // On récupère les données du formulaire.
        $id = Utils::request("id", -1);
        $title = Utils::request("title");
        $content = Utils::request("content");

        // On vérifie que les données sont valides.
        if (empty($title) || empty($content)) {
            throw new Exception("Tous les champs sont obligatoires. 2");
        }

        // On crée l'objet Article.
        $article = new Article([
            'id' => $id, // Si l'id vaut -1, l'article sera ajouté. Sinon, il sera modifié.
            'title' => $title,
            'content' => $content,
            'id_user' => $_SESSION['idUser']
        ]);

        // On ajoute l'article.
        $articleManager = new ArticleManager();
        $articleManager->addOrUpdateArticle($article);

        // On redirige vers la page d'administration.
        Utils::redirect("admin");
    }


    /**
     * Suppression d'un article.
     * @return void
     */
    public function deleteArticle(): void
    {
        $this->checkIfUserIsConnected();

        $id = Utils::request("id", -1);

        // On supprime l'article.
        $articleManager = new ArticleManager();
        $articleManager->deleteArticle($id);

        // On redirige vers la page d'administration.
        Utils::redirect("admin");
    }

    /**
     * Suppression d'un commentaire.
     * @return void
     */
    public function deleteComment(): void
    {
        $this->checkIfUserIsConnected();

        $id = Utils::request("id", -1);

        // On supprime le commentaire
        $commentManager = new CommentManager();
        $comment = $commentManager->getCommentById($id);
        $commentIdArticle = $comment->getIdArticle();
        $commentManager->deleteComment($comment);

        // On redirige vers la page de commentaire
        Utils::redirect("showCommentPage", ['id' => $commentIdArticle]);
    }

    
}
