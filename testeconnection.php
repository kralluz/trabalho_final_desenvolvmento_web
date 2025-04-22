<?php
$dbPath = __DIR__ . '/meu_banco.sqlite';

$pdo = new PDO("sqlite:$dbPath");

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->exec("
    CREATE TABLE IF NOT EXISTS user (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE
        password TEXT NOT NULL
    );
");

$pdo->exec("
    CREATE TABLE IF NOT EXISTS adsense (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        description TEXT NOT NULL UNIQUE
        price FLOAT NOT NULL
        user_id FOREIGN KEY 
    );
");

$pdo->exec("
    CREATE TABLE IF NOT EXISTS image (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        url TEXT NOT NULL,
        adsense_id FOREIGN KEY
    );
");

$statement = $pdo->prepare("INSERT INTO users (name, email) VALUES (:name, :email)");
$statement->execute([
    'name' => 'Kralluz',
    'email' => 'kralluz@example.com'
]);

$result = $pdo->query("SELECT * FROM users");

foreach ($result as $row) {
    echo "ID: {$row['id']} | Nome: {$row['name']} | Email: {$row['email']}\n";
}