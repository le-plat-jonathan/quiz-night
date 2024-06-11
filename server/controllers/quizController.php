<?php
require_once 'models/Quiz.php';

class QuizController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAll($request, $response) {
        $quiz = new Quiz($this->db);
        $stmt = $quiz->readAll();
        $quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $response->withJson($quizzes);
    }

    public function create($request, $response) {
        $data = $request->getParsedBody();
        $quiz = new Quiz($this->db);

        $quiz->title = $data['title'];
        $quiz->description = $data['description'];
        $quiz->created_by = $data['created_by'];

        if ($quiz->create()) {
            return $response->withJson(['message' => 'Quiz created successfully']);
        } else {
            return $response->withJson(['message' => 'Quiz creation failed'], 500);
        }
    }

    public function delete($request, $response, $args) {
        $quiz = new Quiz($this->db);
        $quiz->id = $args['id'];

        if ($quiz->delete()) {
            return $response->withJson(['message' => 'Quiz deleted successfully']);
        } else {
            return $response->withJson(['message' => 'Quiz deletion failed'], 500);
        }
    }
}
?>
