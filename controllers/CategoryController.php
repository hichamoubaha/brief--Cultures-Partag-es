<?php
class CategoryController {
    private $db;

    public function __construct($database) {
        $this->db = $database->getConnection();
    }

    public function getAllCategories() {
        $query = "SELECT * FROM categories ORDER BY nom_categorie";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createCategory($nom_categorie) {
        $query = "INSERT INTO categories (nom_categorie) VALUES (:nom_categorie)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nom_categorie', $nom_categorie);
        return $stmt->execute();
    }
}

