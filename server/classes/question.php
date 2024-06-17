<?php
// Inclusion du fichier de connexion à la base de données
include_once 'db.php';

class Question {
    private $conn;
    private $table_name = "questions"; // Nom de la table des questions

    public $id;
    public $quiz_id;
    public $question_text;

    // Constructeur pour initialiser la connexion à la base de données
    public function __construct($db) {
        $this->conn = $db;
    }

    // Méthode pour créer une nouvelle question
    public function create() {
        // Requête SQL pour insérer une nouvelle question
        $query = "INSERT INTO " . $this->table_name . " SET quiz_id=:quiz_id, question_text=:question_text";

        // Préparation de la requête
        $stmt = $this->conn->prepare($query);

        // Nettoyage des données
        $this->quiz_id = htmlspecialchars(strip_tags($this->quiz_id));
        $this->question_text = htmlspecialchars(strip_tags($this->question_text));

        // Liaison des paramètres
        $stmt->bindParam(":quiz_id", $this->quiz_id);
        $stmt->bindParam(":question_text", $this->question_text);

        // Exécution de la requête
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Méthode pour lire les questions d'un quiz
    public function read() {
        // Requête SQL pour sélectionner les questions d'un quiz
        $query = "SELECT * FROM " . $this->table_name . " WHERE quiz_id = ?";

        // Préparation de la requête
        $stmt = $this->conn->prepare($query);

        // Liaison du paramètre quiz_id
        $stmt->bindParam(1, $this->quiz_id);

        // Exécution de la requête
        $stmt->execute();

        return $stmt;
    }

    // Méthode pour mettre à jour une question
    public function update() {
        // Requête SQL pour mettre à jour une question
        $query = "UPDATE " . $this->table_name . " SET question_text = :question_text WHERE id = :id";

        // Préparation de la requête
        $stmt = $this->conn->prepare($query);

        // Nettoyage des données
        $this->question_text = htmlspecialchars(strip_tags($this->question_text));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Liaison des paramètres
        $stmt->bindParam(":question_text", $this->question_text);
        $stmt->bindParam(":id", $this->id);

        // Exécution de la requête
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Méthode pour supprimer une question
    public function delete() {
        // Requête SQL pour supprimer une question
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        // Préparation de la requête
        $stmt = $this->conn->prepare($query);

        // Nettoyage des données
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Liaison du paramètre id
        $stmt->bindParam(":id", $this->id);

        // Exécution de la requête
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>
