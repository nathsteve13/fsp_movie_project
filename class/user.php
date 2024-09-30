<?php

require_once("parent.php");

class User extends ParentClass { 
    public function __construct() 
    {
        parent::__construct();
    }

    public function getUser($user_id) {
        $sql = "select * from users where idusers = Like ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $res = $stmt->get_result();

        return $res->fetch_assoc();
    }

    public function authenticate($user_id, $plain_pass) {
        $user = $this->getUser($user_id);

        if($user) {
            return password_verify($plain_pass, $user['password']);
        } else { 
            false;
        }
    }

    public function addUser($username,  $plain_pass) {
        $hashed_pass = password_hash($plain_pass, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("ss", $username , $hashed_pass);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return $stmt->insert_id; 
        } else {
            return false; 
        }
    }
}

?>