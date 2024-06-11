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
}
?>
