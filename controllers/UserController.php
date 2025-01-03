<?php
class UserController {
    private $db;

    public function __construct($database) {
        $this->db = $database->getConnection();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $query = "SELECT * FROM utilisateurs WHERE email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['mot_de_passe'])) {
                $_SESSION['user_id'] = $user['id_utilisateur'];
                $_SESSION['role'] = $user['role'];
                header('Location: index.php');
            } else {
                echo "Invalid email or password.";
            }
        } else {
            include 'views/login.php';
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom_utilisateur = $_POST['nom_utilisateur'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $role = 'utilisateur';

            $query = "INSERT INTO utilisateurs (nom_utilisateur, email, mot_de_passe, role) VALUES (:nom_utilisateur, :email, :password, :role)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':nom_utilisateur', $nom_utilisateur);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':role', $role);

            if ($stmt->execute()) {
                header('Location: index.php?page=login');
            } else {
                echo "Error registering user.";
            }
        } else {
            include 'views/register.php';
        }
    }
}

