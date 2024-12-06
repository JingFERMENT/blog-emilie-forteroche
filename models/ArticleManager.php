<?php



/**
 * Classe qui gère les articles.
 */
class ArticleManager extends AbstractEntityManager
{
    /**
     * Récupère tous les articles.
     * @param string|null $sortColumn
     * @param string|null $sortOrder
     * @param bool $withComments
     * 
     * @return array : un tableau d'objets Article.
     */
    public function getAllArticles(?string $sortColumn = 'date_creation', ?string $sortOrder = 'DESC', bool $withComments = false): array
    {
        // Vérifie si les articles doivent inclure le nombre de commentaires
        if ($withComments) {
            $sql = "SELECT article.*, COUNT(comment.id) AS nbOfComments
            FROM `article`
            LEFT JOIN `comment` ON comment.id_article = article.id
            GROUP BY article.id
            ORDER BY COUNT(comment.id) $sortOrder;";
        } else {
            $sql = "SELECT * FROM `article` ORDER BY `article`.`$sortColumn` $sortOrder;";
        }

        $result = $this->db->query($sql);

        $articles = [];

        $commentManager = new CommentManager();

        while ($articleData = $result->fetch()) {

            // ajouter le nombre de commentaire
            $articleId = $articleData['id'];
            $nbOfComments = $commentManager->countCommentsForArticle($articleId);
            
            // Ajoute le nombre de commentaires à l'article
            $articleData['nbOfComments'] = $nbOfComments;

            // Convertit la date de création de l'article en objet DateTime
            $dateOfPublication = new DateTime($articleData['date_creation']);

            // Ajoute la date de publication à l'article
            $articleData['date_creation'] = $dateOfPublication;
            
            // Crée un nouvel objet Article à partir des données et l'ajoute au tableau
            $articles[] = new Article($articleData);
        }
        return $articles;
    }

    /**
     * Récupère un article par son id.
     * @param int $id : l'id de l'article.
     * @return Article|null : un objet Article ou null si l'article n'existe pas.
     */
    public function getArticleById(int $id): ?Article
    {
        $sql = "SELECT * FROM article WHERE id = :id";
        $result = $this->db->query($sql, ['id' => $id]);
        $article = $result->fetch();

        if ($article) {
            return new Article($article);
        }
        return null;
    }

    /**
     * Ajoute ou modifie un article.
     * On sait si l'article est un nouvel article car son id sera -1.
     * @param Article $article : l'article à ajouter ou modifier.
     * @return void
     */
    public function addOrUpdateArticle(Article $article): void
    {
        if ($article->getId() == -1) {
            $this->addArticle($article);
        } else {
            $this->updateArticle($article);
        }
    }

    /**
     * Ajoute un article.
     * @param Article $article : l'article à ajouter.
     * @return void
     */
    public function addArticle(Article $article): void
    {
        $sql = "INSERT INTO article (id_user, title, content, date_creation) VALUES (:id_user, :title, :content, NOW())";
        $this->db->query($sql, [
            'id_user' => $article->getIdUser(),
            'title' => $article->getTitle(),
            'content' => $article->getContent()
        ]);
    }

    /**
     * Modifie un article.
     * @param Article $article : l'article à modifier.
     * @return void
     */
    public function updateArticle(Article $article): void
    {
        $sql = "UPDATE article SET title = :title, content = :content, date_update = NOW() WHERE id = :id";
        $this->db->query($sql, [
            'title' => $article->getTitle(),
            'content' => $article->getContent(),
            'id' => $article->getId()
        ]);
    }

    /**
     * Supprime un article.
     * @param int $id : l'id de l'article à supprimer.
     * @return void
     */
    public function deleteArticle(int $id): void
    {
        $sql = "DELETE FROM article WHERE id = :id";
        $this->db->query($sql, ['id' => $id]);
    }

    /**
     * Méthode pour compter le nombre des vues d'un article et enregister dans la base des données
     * @param Article $article
     * @return void
     */
    public function incrementArticleViews(Article $article): void
    {

        $article->incrementViews();

        $sql = "UPDATE article SET views = :views WHERE id = :id";

        $this->db->query($sql, [
            'views' => $article->getViews(),
            'id' => $article->getId()
        ]);
    }
}
