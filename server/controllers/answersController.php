<?php
require_once 'models/Answer.php';

class AnswerController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function create($request, $response) {
        $data = $request->getParsedBody();
        $answer = new Answer($this->db);

        $answer->question_id = $data['question_id'];
        $answer->answer_text = $data['answer_text'];
        $answer->is_correct = $data['is_correct'];

        if ($answer->create()) {
            return $response->withJson(['message' => 'Answer created successfully']);
        } else {
            return $response->withJson(['message' => 'Answer creation failed'], 500);
        }
    }

    public function getByQuestionId($request, $response, $args) {
        $answer = new Answer($this->db);
        $answer->question_id = $args['question_id'];
        $stmt = $answer->readByQuestionId();
        $answers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $response->withJson($answers);
    }

    public function update($request, $response, $args) {
        $data = $request->getParsedBody();
        $answer = new Answer($this->db);

        $answer->id = $args['id'];
        $answer->answer_text = $data['answer_text'];
        $answer->is_correct = $data['is_correct'];

        if ($answer->update()) {
            return $response->withJson(['message' => 'Answer updated successfully']);
        } else {
            return $response->withJson(['message' => 'Answer update failed'], 500);
        }
    }

    public function delete($request, $response, $args) {
        $answer = new Answer($this->db);
        $answer->id = $args['id'];

        if ($answer->delete()) {
            return $response->withJson(['message' => 'Answer deleted successfully']);
        } else {
            return $response->withJson(['message' => 'Answer deletion failed'], 500);
        }
    }
}
?>
