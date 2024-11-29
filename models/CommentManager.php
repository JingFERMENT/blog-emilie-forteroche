<?php

/**
 * Cette classe sert à gérer les commentaires. 
 */
class CommentManager extends AbstractEntityManager
{
    /**
     * Récupère tous les commentaires d'un article.
     * @param int $idArticle : l'id de l'article.
     * @return array : un tableau d'objets Comment.
     */
    public function getAllCommentsByArticleId(int $idArticle) : array
    {
        $sql = "SELECT * FROM comment WHERE id_article = :idArticle";
        $result = $this->db->query($sql, ['idArticle' => $idArticle]);
        $comments = [];

        while ($comment = $result->fetch()) {
            $comments[] = new Comment($comment);
        }
        return $comments;
    }

    /**
     * Récupère un commentaire par son id.
     * @param int $id : l'id du commentaire.
     * @return Comment|null : un objet Comment ou null si le commentaire n'existe pas.
     */
    public function getCommentById(int $id) : ?Comment
    {
        $sql = "SELECT * FROM comment WHERE id = :id";
        $result = $this->db->query($sql, ['id' => $id]);
        $comment = $result->fetch();
        
        if ($comment) {
            return new Comment($comment);
        }
        return null;
    }

    /**
     * Ajoute un commentaire.
     * @param Comment $comment : l'objet Comment à ajouter.
     * @return bool : true si l'ajout a réussi, false sinon.
     */
    public function addComment(Comment $comment) : bool
    {
        $sql = "INSERT INTO comment (pseudo, content, id_article, date_creation) VALUES (:pseudo, :content, :idArticle, NOW())";
        $result = $this->db->query($sql, [
            'pseudo' => $comment->getPseudo(),
            'content' => $comment->getContent(),
            'idArticle' => $comment->getIdArticle()
        ]);
        return $result->rowCount() > 0;
    }

    /**
     * Supprime un commentaire.
     * @param Comment $comment : l'objet Comment à supprimer.
     * @return bool : true si la suppression a réussi, false sinon.
     */
    public function deleteComment(Comment $comment) : bool
    {
        $sql = "DELETE FROM comment WHERE id = :id";
        $result = $this->db->query($sql, ['id' => $comment->getId()]);
        return $result->rowCount() > 0;
    }

    /**
     * Méthode pour compter des commentaires par article 
     * 
     * @param int $idArticle
     * 
     * @return int
     */
    public function countCommentsForArticle(int $idArticle):int{

        $sql = "SELECT COUNT(*) as `comment_count` FROM `comment` WHERE `id_article` = :id_article;";

        // execution de la requête + récupérer les résultats
        // fetch() retourne un tableau associatif 
        $result = $this->db->query($sql, ['id_article' => $idArticle])->fetch();

        // retourner le résultat ou 0 s'il y a aucun commentaire
        return $result['comment_count'] ?? 0;
    }
}