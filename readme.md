## Blog d'Emilie Forteroche

Ce projet est une application web de blog permettant à l'administratrice Emilie Forteroche de publier des articles, de gérer les commentaires, et de surveiller les statistiques du blog.

---

## 🚀 Fonctionnalités développées

### Gestion des données
- **Suivi des statistiques :** La base de données a été mise à jour pour inclure le nombre de vues par page.
- **Sécurité :** Toutes les requêtes SQL sont préparées afin d'éviter les injections SQL.

### Fonctionnalités d'administration

- **Monitoring :** Toutes les informations importantes (articles, vues, commentaires, date de publications) sont accessibles sur une page de monitoring.

- **Tri des données :**
  - Les colonnes suivantes peuvent être triées en ordre croissant ou décroissant :
    - Titre
    - Date de publication
    - Nombre de vues
    - Nombre de commentaires

- **Gestion des commentaires :**
  - Suppression des commentaires associés à chaque article.
  - Recherche rapide dans les commentaires grâce à une barre de recherche intégrée.
  - Pagination: Les commentaires sont paginés pour améliorer la navigation et la lisibilité.

---


## ⚙️ Installation et utilisation

### Étape 1 : Cloner le projet
Clonez ce dépôt Git dans un dossier accessible par votre serveur local (MAMP, WAMP, LAMP, etc.).

### Étape 2 : Configurer la base de données
1. Créez une base de données vide nommée `blog_forteroche`.
2. Importez le fichier `blog_forteroche.sql` inclus dans le projet pour initialiser la structure et les données.

### Étape 3 : Configurer l'application
1. Éditez le fichier `config.php`.
2. Renseignez les informations de connexion à votre base de données :
   - **Hôte** : Adresse de votre serveur de base de données.
   - **Utilisateur** : Nom d'utilisateur de la base de données.
   - **Mot de passe** : Mot de passe de l'utilisateur.
   - **Nom de la base** : `blog_forteroche`.

### Étape 4 : Lancer le projet
Ouvrez le projet dans votre navigateur en accédant à l'URL locale configurée, par exemple :  
`http://blog-emilie-forteroche.localhost/`.

---

## 🔐 Connexion administrateur

Pour accéder à l'interface administrateur, utilisez les identifiants suivants :  
- **Login :** `Emilie`  
- **Mot de passe :** `password`

---

## 🛠 Configuration spécifique

Si vous rencontrez des problèmes avec les dates affichées, vérifiez que la librairie PHP **`intl`** est activée :  
- Sur **WAMP**, vous pouvez activer cette extension via l'interface graphique.  
- Sur d'autres serveurs, modifiez manuellement le fichier `php.ini` et décommentez la ligne correspondante à `intl`.  

> **Note :** Ce projet a été testé avec PHP 8.2. Bien que des versions ultérieures puissent fonctionner, l'utilisation de versions antérieures n'est pas garantie.
