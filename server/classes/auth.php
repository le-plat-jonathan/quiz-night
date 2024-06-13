<?php
include_once 'db.php';
include_once 'User.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action == 'register') {
        $user->username = $_POST['username'];
        $user->password = $_POST['password'];
        $user->email = $_POST['email'];
        if ($user->create()) {
            echo json_encode(array("message" => "User was created."));
        } else {
            echo json_encode(array("message" => "Unable to create user."));
        }
    }

    if ($action == 'login') {
        $user->email = $_POST['email'];
        $user->password = $_POST['password'];

        if ($user->login()) {
            session_start();
            $_SESSION['user_id'] = $user->id;
            $_SESSION['username'] = $user->username;
            echo json_encode(array("message" => "Login successful."));
        } else {
            echo json_encode(array("message" => "Login failed."));
        }
    }
}
?>
