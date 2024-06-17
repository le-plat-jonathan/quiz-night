<?php
// Inclusion du fichier de connexion à la base de données
include_once 'db.php';

class Answer {
    private $conn;
    private $table_name = "answers"; // Nom de la table des réponses

    public $id;
    public $question_id;
    public $answer_text;
    public $is_correct;

    // Constructeur pour initialiser la connexion à la base de données
    public function __construct($db) {
        $this->conn = $db;
    }

    // Méthode pour créer une nouvelle réponse
    public function create() {
        // Requête SQL pour insérer une nouvelle réponse
        $query = "INSERT INTO " . $this->table_name . " SET question_id=:question_id, answer_text=:answer_text, is_correct=:is_correct";

        // Préparation de la requête
        $stmt = $this->conn->prepare($query);

        // Nettoyage des données
        $this->question_id = htmlspecialchars(strip_tags($this->question_id));
        $this->answer_text = htmlspecialchars(strip_tags($this->answer_text));
        $this->is_correct = htmlspecialchars(strip_tags($this->is_correct));

        // Liaison des paramètres
        $stmt->bindParam(":question_id", $this->question_id);
        $stmt->bindParam(":answer_text", $this->answer_text);
        $stmt->bindParam(":is_correct", $this->is_correct);

        // Exécution de la requête
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Méthode pour lire les réponses d'une question
    public function read() {
        // Requête SQL pour sélectionner les réponses d'une question
        $query = "SELECT * FROM " . $this->table_name . " WHERE question_id = ?";

        // Préparation de la requête
        $stmt = $this->conn->prepare($query);

        // Liaison du paramètre question_id
        $stmt->bindParam(1, $this->question_id);

        // Exécution de la requête
        $stmt->execute();

        return $stmt;
    }

    // Méthode pour mettre à jour une réponse
    public function update() {
        // Requête SQL pour mettre à jour une réponse
        $query = "UPDATE " . $this->table_name . " SET answer_text = :answer_text, is_correct = :is_correct WHERE id = :id";

        // Préparation de la requête
        $stmt = $this->conn->prepare($query);

        // Nettoyage des données
        $this->answer_text = htmlspecialchars(strip_tags($this->answer_text));
        $this->is_correct = htmlspecialchars(strip_tags($this->is_correct));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Liaison des paramètres
        $stmt->bindParam(":answer_text", $this->answer_text);
        $stmt->bindParam(":is_correct", $this->is_correct);
        $stmt->bindParam(":id", $this->id);

        // Exécution de la requête
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Méthode pour supprimer une réponse
    public function delete() {
        // Requête SQL pour supprimer une réponse
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

    // Méthode pour supprimer toutes les réponses d'une question
    public function deleteByQuestionId() {
        // Requête SQL pour supprimer les réponses d'une question
        $query = "DELETE FROM " . $this->table_name . " WHERE question_id = :question_id";
    
        // Préparation de la requête
        $stmt = $this->conn->prepare($query);

        // Liaison du paramètre question_id
        $stmt->bindParam(":question_id", $this->question_id);
    
        // Exécution de la requête
        if ($stmt->execute()) {
            return true;
        }
    
        return false;
    }
}
?>
