<?php
include_once 'db.php';

class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $username;
    public $password;
    public $email;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET username=:username, password=:password, email=:email";
    
        $stmt = $this->conn->prepare($query);
    
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->email = htmlspecialchars(strip_tags($this->email));
    
        $hashed_password = password_hash($this->password, PASSWORD_BCRYPT);
    
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $hashed_password);
        $stmt->bindParam(":email", $this->email);
    
        if ($stmt->execute()) {
            return true;
        }
    
        return false;
    }
    
    public function login() {
        $query = "SELECT id, username, password FROM " . $this->table_name . " WHERE email = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);

        $this->email=htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(1, $this->email);

        $stmt->execute();

        $num = $stmt->rowCount();

        if ($num > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->id = $row['id'];
            $this->username = $row['username'];
            $password_hash = $row['password'];

            if (password_verify($this->password, $password_hash)) {
                return true;
            }
        }

        return false;
    }
}
?>
