<?php
// Inclusion du fichier de connexion à la base de données
include_once 'db.php';

class User {
    private $conn;
    private $table_name = "users"; // Nom de la table des utilisateurs

    public $id;
    public $username;
    public $password;
    public $email;

    // Constructeur pour initialiser la connexion à la base de données
    public function __construct($db) {
        $this->conn = $db;
    }

    // Méthode pour créer un nouvel utilisateur
    public function create() {
        // Requête SQL pour insérer un nouvel utilisateur
        $query = "INSERT INTO " . $this->table_name . " SET username=:username, password=:password, email=:email";
    
        // Préparation de la requête
        $stmt = $this->conn->prepare($query);
    
        // Nettoyage des données
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->email = htmlspecialchars(strip_tags($this->email));
    
        // Hashage du mot de passe
        $hashed_password = password_hash($this->password, PASSWORD_BCRYPT);
    
        // Liaison des paramètres
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $hashed_password);
        $stmt->bindParam(":email", $this->email);
    
        // Exécution de la requête
        if ($stmt->execute()) {
            return true;
        }
    
        return false;
    }
    
    // Méthode pour authentifier un utilisateur
    public function login() {
        // Requête SQL pour sélectionner un utilisateur par email
        $query = "SELECT id, username, password FROM " . $this->table_name . " WHERE email = ? LIMIT 0,1";

        // Préparation de la requête
        $stmt = $this->conn->prepare($query);

        // Nettoyage de l'email
        $this->email = htmlspecialchars(strip_tags($this->email));

        // Liaison du paramètre email
        $stmt->bindParam(1, $this->email);

        // Exécution de la requête
        $stmt->execute();

        // Vérification du nombre de lignes retournées
        $num = $stmt->rowCount();

        // Si un utilisateur est trouvé
        if ($num > 0) {
            // Récupération de la ligne de résultats
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Assignation des valeurs aux propriétés de l'objet
            $this->id = $row['id'];
            $this->username = $row['username'];
            $password_hash = $row['password'];

            // Vérification du mot de passe
            if (password_verify($this->password, $password_hash)) {
                return true;
            }
        }

        return false;
    }
}
?>
