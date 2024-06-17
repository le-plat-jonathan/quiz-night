<?php
// Inclusion des fichiers de la base de données et du modèle User
include_once 'db.php';
include_once 'User.php';

// Création d'une instance de connexion à la base de données
$database = new Database();
$db = $database->getConnection();

// Création d'une instance du modèle User
$user = new User($db);

// Vérification de la méthode de la requête HTTP
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération de l'action à partir des données POST
    $action = $_POST['action'];

    // Si l'action est 'register', enregistrer un nouvel utilisateur
    if ($action == 'register') {
        // Récupération des données utilisateur à partir des données POST
        $user->username = $_POST['username'];
        $user->password = $_POST['password'];
        $user->email = $_POST['email'];
        
        // Création de l'utilisateur et envoi de la réponse JSON
        if ($user->create()) {
            echo json_encode(array("message" => "User was created."));
        } else {
            echo json_encode(array("message" => "Unable to create user."));
        }
    }

    // Si l'action est 'login', authentifier l'utilisateur
    if ($action == 'login') {
        // Récupération des données de connexion à partir des données POST
        $user->email = $_POST['email'];
        $user->password = $_POST['password'];

        // Authentification de l'utilisateur et gestion de la session
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
