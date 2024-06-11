<?php
require_once 'models/Question.php';

class QuestionController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function create($request, $response) {
        $data = $request->getParsedBody();
        $question = new Question($this->db);

        $question->quiz_id = $data['quiz_id'];
        $question->question_text = $data['question_text'];

        if ($question->create()) {
            return $response->withJson(['message' => 'Question created successfully']);
        } else {
            return $response->withJson(['message' => 'Question creation failed'], 500);
        }
    }

    public function getByQuizId($request, $response, $args) {
        $question = new Question($this->db);
        $question->quiz_id = $args['quiz_id'];
        $stmt = $question->readByQuizId();
        $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $response->withJson($questions);
    }

    public function update($request, $response, $args) {
        $data = $request->getParsedBody();
        $question = new Question($this->db);

        $question->id = $args['id'];
        $question->question_text = $data['question_text'];

        if ($question->update()) {
            return $response->withJson(['message' => 'Question updated successfully']);
        } else {
            return $response->withJson(['message' => 'Question update failed'], 500);
        }
    }

    public function delete($request, $response, $args) {
        $question = new Question($this->db);
        $question->id = $args['id'];

        if ($question->delete()) {
            return $response->withJson(['message' => 'Question deleted successfully']);
        } else {
            return $response->withJson(['message' => 'Question deletion failed'], 500);
        }
    }
}
?>
