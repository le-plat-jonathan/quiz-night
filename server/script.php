<?php

require_once 'classes\database.php';
require_once 'classes\quiz.php';
require_once 'classes\question.php';
require_once 'classes\user.php';

$database = new Database();
$db = $database->connect();

$quiz = new Quiz($db);
$quiz->title = 'Sample Quiz';
if($quiz->create()) {
    echo 'Quiz Created';
}

$question = new Question($db);
$question->quiz_id = $db->lastInsertId();
$question->question_text = 'What is the capital of France?';
if($question->create()) {
    echo 'Question Created';
}

?>
