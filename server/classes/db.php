<?php
class Database {
    // Déclaration des propriétés pour les informations de connexion à la base de données
    private $host = 'localhost';
    private $db_name = 'quiz_night';
    private $username = 'root';
    private $password = '';
    public $conn;

    // Méthode pour obtenir la connexion à la base de données
    public function getConnection() {
        $this->conn = null; // Initialisation de la connexion à null

        try {
            // Création de la connexion PDO
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            // Configuration pour utiliser l'encodage UTF-8
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            // Gestion des erreurs de connexion
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn; // Retourne l'objet de connexion
    }
}
?>
