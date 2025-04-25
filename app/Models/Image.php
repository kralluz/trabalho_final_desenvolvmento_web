<?php
declare(strict_types=1);

require_once __DIR__ . '\Connection.php';
require_once __DIR__ . '\Adsense.php';

class Image{
    public int $adsense_id;
    public ?int $id;
    public String $url;

    public function __construct(int $adsense_id = 0, ?int $id = null, String $url = ''){
        $this->adsense_id = $adsense_id;
        $this->id=$id;
        $this->url=$url;
    }

    public static function all() : array{
        $pdo = Connection::getConnection();
        $stmt = $pdo->query("SELECT * FROM images");
        $out = [];

        while($row = $stmt->fetch()){
            $out = new Image($row['adsense_id'], $row['id'], $row['url']);
        }
        return $out;
    }

    public static function find(int $id) : ?Image{
        $pdo = Connection::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM images WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        return $row ? new Image($row['adsense_id'], $row['id'], $row['url']) : null;
    }

    public static function save() : bool{
        $pdo = Connection::getConnection();
        if($this->id === null){
            $stmt = $pdo->prepare('INSERT INTO images (url, adsense_id) VALUES (?, ?)');
            $ok = $stmt->execute([$this->url, $this->$adsense_id]);
        }
        if($ok){
            $this->id = $pdo->lastInsertId();
            return $ok;
        }else{
            $stmt = $pdo->prepare('UPDATE images SET url = ?, adsense_id = ?');
            return $stmt->execute([$this->url, $this->adsense_id]);
        }
    }

    public static function delete() : bool{
        if($this->id === null){
            return false;
        }else{
            $pdo = $pdo = Connection::getConnection();
            $stmt = $pdo->prepare('DELETE FROM images WHERE id = ?');
            return $stmt->execute([$this->id]);
        }
    }

    public static function adsense() : ?Adsense{
        return Image::find($this->adsense_id);
    }
}

?>