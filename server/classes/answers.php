<?php
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
        $query = "INSERT INTO " . $this->table_name . " (question_id, answer_text, is_correct) VALUES (:question_id, :answer_text, :is_correct)";

        $stmt = $this->conn->prepare($query);

        $this->question_id = htmlspecialchars(strip_tags($this->question_id));
        $this->answer_text = htmlspecialchars(strip_tags($this->answer_text));
        $this->is_correct = htmlspecialchars(strip_tags($this->is_correct));

        $stmt->bindParam(':question_id', $this->question_id);
        $stmt->bindParam(':answer_text', $this->answer_text);
        $stmt->bindParam(':is_correct', $this->is_correct);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>
