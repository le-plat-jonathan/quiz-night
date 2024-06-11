<?php
class Question {
    private $conn;
    private $table_name = "questions";

    public $id;
    public $quiz_id;
    public $question_text;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (quiz_id, question_text) VALUES (:quiz_id, :question_text)";

        $stmt = $this->conn->prepare($query);

        $this->quiz_id = htmlspecialchars(strip_tags($this->quiz_id));
        $this->question_text = htmlspecialchars(strip_tags($this->question_text));

        $stmt->bindParam(':quiz_id', $this->quiz_id);
        $stmt->bindParam(':question_text', $this->question_text);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function readByQuizId() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE quiz_id = :quiz_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quiz_id', $this->quiz_id);
        $stmt->execute();

        return $stmt;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET question_text = :question_text WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->question_text = htmlspecialchars(strip_tags($this->question_text));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':question_text', $this->question_text);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>
