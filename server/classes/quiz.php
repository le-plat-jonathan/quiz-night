<?php
include_once 'db.php';

class Quiz {
    private $conn;
    private $table_name = "quizzes";

    public $id;
    public $title;
    public $description;
    public $created_by;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET title=:title, description=:description, created_by=:created_by";
    
        $stmt = $this->conn->prepare($query);
    
        $this->title=htmlspecialchars(strip_tags($this->title));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->created_by=htmlspecialchars(strip_tags($this->created_by));
    
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":created_by", $this->created_by);
    
        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
    
        return false;
    }
    
    public function read() {
        $query = "SELECT * FROM " . $this->table_name;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET title = :title, description = :description WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->title=htmlspecialchars(strip_tags($this->title));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->id=htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->id=htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
    public function readAllWithDetails() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        foreach ($quizzes as &$quiz) {
            $quiz['questions'] = $this->getQuestionsWithAnswers($quiz['id']);
        }
    
        return $quizzes;
    }
    
    public function readOneWithDetails($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $quiz = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($quiz) {
            $quiz['questions'] = $this->getQuestionsWithAnswers($id);
        }
    
        return $quiz;
    }
    
    private function getQuestionsWithAnswers($quiz_id) {
        $query = "SELECT * FROM questions WHERE quiz_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $quiz_id);
        $stmt->execute();
        $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        foreach ($questions as &$question) {
            $question['answers'] = $this->getAnswers($question['id']);
        }
    
        return $questions;
    }
    
    private function getAnswers($question_id) {
        $query = "SELECT * FROM answers WHERE question_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $question_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }    
}
?>
