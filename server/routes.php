<?php
require 'vendor/autoload.php';

use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

$app = AppFactory::create();

require_once 'config/Database.php';
require_once 'controllers/UserController.php';
require_once 'controllers/QuizController.php';
require_once 'controllers/QuestionController.php';
require_once 'controllers/AnswerController.php';

$authMiddleware = function ($request, $handler) {
    if (!isset($_SESSION['user_id'])) {
        $response = new \Slim\Psr7\Response();
        return $response->withStatus(401)->withJson(['message' => 'Not authenticated']);
    }
    return $handler->handle($request);
};

$app->post('/api/register', \UserController::class . ':register');
$app->post('/api/login', \UserController::class . ':login');
$app->post('/api/logout', \UserController::class . ':logout');
$app->get('/api/current-user', \UserController::class . ':getCurrentUser');

$app->group('/api', function (RouteCollectorProxy $group) {
    $group->get('/quizzes', \QuizController::class . ':getAll');
    $group->post('/quizzes', \QuizController::class . ':create');
    $group->delete('/quizzes/{id}', \QuizController::class . ':delete');

    $group->get('/quizzes/{quiz_id}/questions', \QuestionController::class . ':getByQuizId');
    $group->post('/questions', \QuestionController::class . ':create');
    $group->put('/questions/{id}', \QuestionController::class . ':update');
    $group->delete('/questions/{id}', \QuestionController::class . ':delete');

    $group->get('/questions/{question_id}/answers', \AnswerController::class . ':getByQuestionId');
    $group->post('/answers', \AnswerController::class . ':create');
    $group->put('/answers/{id}', \AnswerController::class . ':update');
    $group->delete('/answers/{id}', \AnswerController::class . ':delete');
})->add($authMiddleware);

$app->run();
?>
