<?php
include_once 'db.php';

class Answer {
    private $conn;
    private $table_name = "answers";

    public $id;
    public $question_id;
    public $answer_text;
    public $is_correct;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET question_id=:question_id, answer_text=:answer_text, is_correct=:is_correct";

        $stmt = $this->conn->prepare($query);

        $this->question_id=htmlspecialchars(strip_tags($this->question_id));
        $this->answer_text=htmlspecialchars(strip_tags($this->answer_text));
        $this->is_correct=htmlspecialchars(strip_tags($this->is_correct));

        $stmt->bindParam(":question_id", $this->question_id);
        $stmt->bindParam(":answer_text", $this->answer_text);
        $stmt->bindParam(":is_correct", $this->is_correct);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE question_id = ?";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->question_id);

        $stmt->execute();

        return $stmt;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET answer_text = :answer_text, is_correct = :is_correct WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->answer_text=htmlspecialchars(strip_tags($this->answer_text));
        $this->is_correct=htmlspecialchars(strip_tags($this->is_correct));
        $this->id=htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":answer_text", $this->answer_text);
        $stmt->bindParam(":is_correct", $this->is_correct);
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
}
?>
