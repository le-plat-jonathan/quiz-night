<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once 'classes/db.php';
include_once 'classes/User.php';
include_once 'classes/Quiz.php';
include_once 'classes/Question.php';
include_once 'classes/Answer.php';

$database = new Database();
$db = $database->getConnection();

$request_method = $_SERVER['REQUEST_METHOD'];
$request_uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

$endpoint = isset($request_uri[1]) ? $request_uri[1] : '';
$id = isset($request_uri[2]) ? $request_uri[2] : null;

switch ($request_method) {
    case 'POST':
        if ($endpoint == 'logout') {
            handleLogoutRequest();
        } else {
            handlePostRequest($endpoint);
        }
        break;
    case 'GET':
        handleGetRequest($endpoint, $id);
        break;
    case 'PUT':
        handlePutRequest($endpoint, $id);
        break;
    case 'DELETE':
        handleDeleteRequest($endpoint, $id);
        break;
    default:
        echo json_encode(array("message" => "Invalid request method."));
        break;
}

function handlePostRequest($endpoint) {
    global $db;
    $input = json_decode(file_get_contents('php://input'), true);

    switch ($endpoint) {
        case 'register':
            $username = isset($input["username"]) ? $input["username"] : null;
            $password = isset($input["password"]) ? $input["password"] : null;
            $email = isset($input["email"]) ? $input["email"] : null;

            if (!$username || !$password || !$email) {
                echo json_encode(array("message" => "Invalid input data."));
                return;
            }

            $user = new User($db);
            $user->username = $username;
            $user->password = $password;
            $user->email = $email;

            if ($user->create()) {
                echo json_encode(array("message" => "User has been created."));
            } else {
                echo json_encode(array("message" => "Unable to create user."));
            }
            break;

        case 'login':
            $email = isset($input['email']) ? filter_var($input['email'], FILTER_VALIDATE_EMAIL) : null;
            $password = isset($input['password']) ? $input['password'] : null;

            if (!$email || !$password) {
                echo json_encode(array("message" => "Invalid input data."));
                return;
            }

            $user = new User($db);
            $user->email = $email;
            $user->password = $password;

            if ($user->login()) {
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                    $_SESSION['user_id'] = $user->id;
                    $_SESSION['username'] = $user->username;
                    echo json_encode(array("message" => "Login successful."));
                } else {
                    echo json_encode(array("message" => "Session already exists."));
                }
            } else {
                echo json_encode(array("message" => "Login failed."));
            }
            break;

        case 'quizzes':
            handleCreateQuizRequest($input);
            break;

        case 'questions':
            $quiz_id = isset($input["quiz_id"]) ? $input["quiz_id"] : null;
            $question_text = isset($input["question_text"]) ? $input["question_text"] : null;

            if (!$quiz_id || !$question_text) {
                echo json_encode(array("message" => "Invalid input data."));
                return;
            }

            $question = new Question($db);
            $question->quiz_id = $quiz_id;
            $question->question_text = $question_text;

            if ($question->create()) {
                echo json_encode(array("message" => "Question created successfully."));
            } else {
                echo json_encode(array("message" => "Unable to create question."));
            }
            break;

        case 'answers':
            $question_id = isset($input["question_id"]) ? $input["question_id"] : null;
            $answer_text = isset($input["answer_text"]) ? $input["answer_text"] : null;
            $is_correct = isset($input["is_correct"]) ? $input["is_correct"] : null;

            error_log("question_id: " . $question_id);
            error_log("answer_text: " . $answer_text);
            error_log("is_correct: " . $is_correct);

            if (!$question_id || !$answer_text || $is_correct === null) {
                echo json_encode(array("message" => "Invalid input data."));
                return;
            }

            $answer = new Answer($db);
            $answer->question_id = $question_id;
            $answer->answer_text = $answer_text;
            $answer->is_correct = $is_correct;

            if ($answer->create()) {
                echo json_encode(array("message" => "Answer created successfully."));
            } else {
                echo json_encode(array("message" => "Unable to create answer."));
            }
            break;

        default:
            echo json_encode(array("message" => "Invalid POST action."));
            break;
    }
}

function handleCreateQuizRequest($input) {
    global $db;
    
    $title = isset($input["title"]) ? $input["title"] : null;
    $description = isset($input["description"]) ? $input["description"] : null;
    $created_by = isset($input["created_by"]) ? $input["created_by"] : null;
    $questions = isset($input["questions"]) ? $input["questions"] : [];

    if (!$title || !$description || !$created_by || empty($questions)) {
        echo json_encode(array("message" => "Invalid input data."));
        return;
    }

    try {
        $db->beginTransaction();

        $quiz = new Quiz($db);
        $quiz->title = $title;
        $quiz->description = $description;
        $quiz->created_by = $created_by;

        if (!$quiz->create()) {
            throw new Exception("Unable to create quiz.");
        }

        $quiz_id = $db->lastInsertId();

        foreach ($questions as $question_data) {
            $question_text = isset($question_data["question_text"]) ? $question_data["question_text"] : null;
            $answers = isset($question_data["answers"]) ? $question_data["answers"] : [];

            if (!$question_text || empty($answers)) {
                throw new Exception("Invalid question input data.");
            }

            $question = new Question($db);
            $question->quiz_id = $quiz_id;
            $question->question_text = $question_text;

            if (!$question->create()) {
                throw new Exception("Unable to create question.");
            }

            $question_id = $db->lastInsertId();

            foreach ($answers as $answer_data) {
                $answer_text = isset($answer_data["answer_text"]) ? $answer_data["answer_text"] : null;
                $is_correct = isset($answer_data["is_correct"]) ? $answer_data["is_correct"] : null;

                if (!$answer_text || $is_correct === null) {
                    throw new Exception("Invalid answer input data.");
                }

                $answer = new Answer($db);
                $answer->question_id = $question_id;
                $answer->answer_text = $answer_text;
                $answer->is_correct = $is_correct;

                if (!$answer->create()) {
                    throw new Exception("Unable to create answer.");
                }
            }
        }

        $db->commit();
        echo json_encode(array("message" => "Quiz, questions and answers created successfully."));

    } catch (Exception $e) {
        $db->rollBack();
        echo json_encode(array("message" => $e->getMessage()));
    }
}

function handleLogoutRequest() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    $_SESSION = array();
    
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    session_destroy();
    
    echo json_encode(array("message" => "Logout successful."));
}


function handleGetRequest($endpoint, $id) {
    global $db;
    switch ($endpoint) {
        case 'quizzes':
            $quiz = new Quiz($db);
            $stmt = $quiz->read();
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'questions':
            $question = new Question($db);
            $question->quiz_id = $id ?: die(json_encode(array("message" => "quiz_id missing.")));
            $stmt = $question->read();
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'answers':
            $answer = new Answer($db);
            $answer->question_id = $id ?: die(json_encode(array("message" => "question_id missing.")));
            $stmt = $answer->read();
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        default:
            echo json_encode(array("message" => "Invalid GET action."));
            break;
    }
}

function handlePutRequest($endpoint, $id) {
    global $db;
    $input = json_decode(file_get_contents("php://input"), true);

    switch ($endpoint) {
        case 'quizzes':
            if (!isset($input['title']) || !isset($input['description'])) {
                echo json_encode(array("message" => "Invalid input data."));
                return;
            }

            $quiz = new Quiz($db);
            $quiz->id = $id;
            $quiz->title = $input['title'];
            $quiz->description = $input['description'];

            echo json_encode($quiz->update() ? array("message" => "Quiz updated successfully.") : array("message" => "Unable to update quiz."));
            break;

        case 'questions':
            if (!isset($input['question_text'])) {
                echo json_encode(array("message" => "Invalid input data."));
                return;
            }

            $question = new Question($db);
            $question->id = $id;
            $question->question_text = $input['question_text'];

            echo json_encode($question->update() ? array("message" => "Question updated successfully.") : array("message" => "Unable to update question."));
            break;

        case 'answers':
            if (!isset($input['answer_text']) || !isset($input['is_correct'])) {
                echo json_encode(array("message" => "Invalid input data."));
                return;
            }

            $answer = new Answer($db);
            $answer->id = $id;
            $answer->answer_text = $input['answer_text'];
            $answer->is_correct = $input['is_correct'];

            echo json_encode($answer->update() ? array("message" => "Answer updated successfully.") : array("message" => "Unable to update answer."));
            break;

        default:
            echo json_encode(array("message" => "Invalid PUT action."));
            break;
    }
}

function handleDeleteRequest($endpoint, $id) {
    global $db;
    switch ($endpoint) {
        case 'quizzes':
            $quiz = new Quiz($db);
            $quiz->id = $id;

            echo json_encode($quiz->delete() ? array("message" => "Quiz deleted successfully.") : array("message" => "Unable to delete quiz."));
            break;

        case 'questions':
            $question = new Question($db);
            $question->id = $id;

            echo json_encode($question->delete() ? array("message" => "Question deleted successfully.") : array("message" => "Unable to delete question."));
            break;

        case 'answers':
            $answer = new Answer($db);
            $answer->id = $id;

            echo json_encode($answer->delete() ? array("message" => "Answer deleted successfully.") : array("message" => "Unable to delete answer."));
            break;

        default:
            echo json_encode(array("message" => "Invalid DELETE action."));
            break;
    }
}
?>
