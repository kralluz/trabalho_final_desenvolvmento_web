<?php
$pdo = new PDO('sqlite:database/database.sqlite');
$result = $pdo->query('PRAGMA table_info(adsense)');
echo "Estrutura da tabela adsense:" . PHP_EOL;
foreach ($result as $row) {
    echo $row['name'] . " - " . $row['type'] . PHP_EOL;
}
?>
