<?php
// Inclusion du fichier de connexion à la base de données
include_once 'db.php';

class Quiz {
    private $conn;
    private $table_name = "quizzes"; // Nom de la table des quizzes

    public $id;
    public $title;
    public $description;
    public $created_by;

    // Constructeur pour initialiser la connexion à la base de données
    public function __construct($db) {
        $this->conn = $db;
    }

    // Méthode pour créer un nouveau quiz
    public function create() {
        // Requête SQL pour insérer un nouveau quiz
        $query = "INSERT INTO " . $this->table_name . " SET title=:title, description=:description, created_by=:created_by";
    
        // Préparation de la requête
        $stmt = $this->conn->prepare($query);
    
        // Nettoyage des données
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->created_by = htmlspecialchars(strip_tags($this->created_by));
    
        // Liaison des paramètres
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":created_by", $this->created_by);
    
        // Exécution de la requête
        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId(); // Récupération de l'ID du quiz créé
            return true;
        }
    
        return false;
    }
    
    // Méthode pour lire tous les quizzes
    public function read() {
        // Requête SQL pour sélectionner tous les quizzes
        $query = "SELECT * FROM " . $this->table_name;

        // Préparation de la requête
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // Méthode pour mettre à jour un quiz
    public function update() {
        // Requête SQL pour mettre à jour un quiz
        $query = "UPDATE " . $this->table_name . " SET title = :title, description = :description WHERE id = :id";

        // Préparation de la requête
        $stmt = $this->conn->prepare($query);

        // Nettoyage des données
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Liaison des paramètres
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":id", $this->id);

        // Exécution de la requête
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Méthode pour supprimer un quiz
    public function delete() {
        // Requête SQL pour supprimer un quiz
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        // Préparation de la requête
        $stmt = $this->conn->prepare($query);

        // Nettoyage des données
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Liaison des paramètres
        $stmt->bindParam(":id", $this->id);

        // Exécution de la requête
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
    
    // Méthode pour lire tous les quizzes avec leurs détails (questions et réponses)
    public function readAllWithDetails() {
        // Requête SQL pour sélectionner tous les quizzes
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Pour chaque quiz, obtenir les questions et réponses associées
        foreach ($quizzes as &$quiz) {
            $quiz['questions'] = $this->getQuestionsWithAnswers($quiz['id']);
        }
    
        return $quizzes;
    }
    
    // Méthode pour lire un quiz avec ses détails (questions et réponses)
    public function readOneWithDetails($id) {
        // Requête SQL pour sélectionner un quiz par ID
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $quiz = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Si le quiz est trouvé, obtenir les questions et réponses associées
        if ($quiz) {
            $quiz['questions'] = $this->getQuestionsWithAnswers($id);
        }
    
        return $quiz;
    }
    
    // Méthode privée pour obtenir les questions avec leurs réponses pour un quiz donné
    private function getQuestionsWithAnswers($quiz_id) {
        // Requête SQL pour sélectionner les questions d'un quiz
        $query = "SELECT * FROM questions WHERE quiz_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $quiz_id);
        $stmt->execute();
        $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Pour chaque question, obtenir les réponses associées
        foreach ($questions as &$question) {
            $question['answers'] = $this->getAnswers($question['id']);
        }
    
        return $questions;
    }
    
    // Méthode privée pour obtenir les réponses pour une question donnée
    private function getAnswers($question_id) {
        // Requête SQL pour sélectionner les réponses d'une question
        $query = "SELECT * FROM answers WHERE question_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $question_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }    
}
?>
