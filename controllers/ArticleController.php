<?php
class ArticleController {
    private $db;

    public function __construct($database) {
        $this->db = $database->getConnection();
    }

    public function getLatestArticles() {
        $query = "SELECT a.*, c.nom_categorie, u.nom_utilisateur 
                  FROM articles a 
                  JOIN categories c ON a.id_categorie = c.id_categorie 
                  JOIN utilisateurs u ON a.id_auteur = u.id_utilisateur 
                  WHERE a.statut = 'approuve' 
                  ORDER BY a.date_creation DESC 
                  LIMIT 10";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createArticle() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Process form submission
            $titre = $_POST['titre'];
            $contenu = $_POST['contenu'];
            $id_categorie = $_POST['id_categorie'];
            $id_auteur = $_SESSION['user_id'];

            $query = "INSERT INTO articles (titre, contenu, id_categorie, id_auteur) VALUES (:titre, :contenu, :id_categorie, :id_auteur)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':titre', $titre);
            $stmt->bindParam(':contenu', $contenu);
            $stmt->bindParam(':id_categorie', $id_categorie);
            $stmt->bindParam(':id_auteur', $id_auteur);

            if ($stmt->execute()) {
                header('Location: index.php?page=dashboard');
            } else {
                echo "Error creating article.";
            }
        } else {
            // Display the form
            include 'views/create_article.php';
        }
    }
}

