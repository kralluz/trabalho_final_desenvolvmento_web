<?php
declare(strict_types=1);

require_once __DIR__ . '/Connection.php';
require_once __DIR__ . '/User.php';
require_once __DIR__ . '/Image.php';

class Adsense
{
    public ?int $id;
    public int $user_id;
    public String $title, $description;
    public float $price;

    public function __construct(?int $id = null, $title = '', $description = '', $price = 0.0, $user_id = 0){
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->user_id = $user_id;
    }

    public static function all(): array{
        $pdo = Connection::getConnection();
        $stmt = $pdo->query("SELECT * FROM adsenses");
        $out = [];
        while($row = $stmt->fetch()){
            $out = new Adsense($row['id'], $row['title'], $row['description'], $row['price'], $row['user_id']);
        }
        
        return $out;
    }

    public static function find(int $id): ?Adsense{
        $pdo = Connection::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM adsenses WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        return $row ? new Adsense($row['id'], $row['title'], $row['description'], $row['price'], $row['user_id']) : null;
    }

    public static function save() : bool{
        $pdo = Connection::getConnection();
        if($this->id === null){
            $stmt = $pdo->prepare('INSERT INTO adsenses (title, description, price, user_id) VALUES (?, ?, ?, ?)');
            $ok = $stmt->execute([$this->title, $this->$description, $this->$price, $this->$user_id]);
        }
        if($ok){
            $this->id = $pdo->lastInsertId();
            return $ok;
        }else{
            $stmt = $pdo->prepare('UPDATE adsenses SET title = ?, description = ?, price = ?, user_id = ? WHERE id = ?');
            return $stmt->execute([$this->title, $this->description, $this->price, $this->user_id, $this->id]);
        }
    }

    public static function delete() : bool{
        if($this->id === null){
            return false;
        }else{
            $pdo = $pdo = Connection::getConnection();
            $stmt = $pdo->prepare('DELETE FROM adsenses WHERE id = ?');
            return $stmt->execute([$this->id]);
    }
    }

    public static function user() : ?User{
        return User::find($this->user_id);
    }

    public static function images() : array{
        $pdo = Connection::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM images WHERE adsense_id = ?');
        $stmt->execute($this->id);
        $images = [];

        while($row = $stmt->fetch()){
            $images = new Image($row['id'], $row['url'], $row['adsense_id']);
        }
        return $images;
    }
}
?>