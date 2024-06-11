<?php
require_once 'models/User.php';

class UserController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function register($request, $response) {
        $data = $request->getParsedBody();
        $user = new User($this->db);

        $user->username = $data['username'];
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->role = 'user';

        if ($user->create()) {
            return $response->withJson(['message' => 'User registered successfully']);
        } else {
            return $response->withJson(['message' => 'User registration failed'], 500);
        }
    }

    public function login($request, $response) {
        $data = $request->getParsedBody();
        $user = new User($this->db);

        $user->email = $data['email'];
        $user->password = $data['password'];

        if ($user->login()) {
            $_SESSION['user_id'] = $user->id;
            $_SESSION['username'] = $user->username;
            $_SESSION['role'] = $user->role;
            return $response->withJson([
                'message' => 'Login successful',
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'role' => $user->role
                ]
            ]);
        } else {
            return $response->withJson(['message' => 'Login failed'], 401);
        }
    }

    public function logout($request, $response) {
        session_unset();
        session_destroy();
        return $response->withJson(['message' => 'Logout successful']);
    }

    public function getCurrentUser($request, $response) {
        if (isset($_SESSION['user_id'])) {
            return $response->withJson([
                'user' => [
                    'id' => $_SESSION['user_id'],
                    'username' => $_SESSION['username'],
                    'role' => $_SESSION['role']
                ]
            ]);
        } else {
            return $response->withJson(['message' => 'Not authenticated'], 401);
        }
    }
}
?>
