<?php
require_once __DIR__ . '/Connection.php';
require_once __DIR__ . '/Adsense.php'; 

class User
{
    public int $id;
    public String $name;
    public String $email;
    public String $password;
    public String $role; // "common" ou "admin"

    public function __construct($id = null, String $name = '', String $email = '', String $password = '', String $role = 'common')
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }
    
    public static function all(){
        $pdo = Connection::getConnection();
        $stmt = $pdo->query("SELECT * FROM users");
        $out = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
            $out = new User(
                $row['id'],$row['name'], $row['email'], $row['password'], $row['role']);            
        }
        return $out;
    }
    
    public static function find($id){
        $pdo = Connection::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        if  ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            return new User(
                $row['id'],$row['name'], $row['email'], $row['password'], $row['role']);            
        }
        return null;
    }
    
    public static function save(){
        $pdo = Connection::getConnection();
        if($this->id === null){
            $stmt = $pdo->prepare('INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)');
            $ok = $stmt->execute([$this->name, $this->$email, $this->$password, $this->$role]);
        }
        if($ok){
            $this->id = $pdo->lastInsertId();
            return $ok;
        }else{
            $stmt = $pdo->prepare('UPDATE users SET name = ?, email = ?, password = ?, role = ? WHERE id = ?');
            return $stmt->execute([$this->name, $this->email, $this->password, $this->role, $this->id]);
        }
    }

    public static function delete(){
        if($this->id === null){
            return false;
        }else{
            $pdo = $pdo = Connection::getConnection();
            $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
            return $stmt->execute([$this->id]);
    }
    }

    public static function adsenses(){
        $pdo = Connection::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM adsenses WHERE user_id = ?');
        return $stmt->execute([$this->id]);

        $out = [];
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
            $out[] = new Adsense($row['id'], $row['user_id'], $row['value'], $row['user_id']);
        }return $out;
    }
}