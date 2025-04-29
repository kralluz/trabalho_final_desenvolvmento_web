<?php
// Connection.php
class Connection {
    public static function getConnection(): \PDO {
        $dbFile = __DIR__ . '/database/database.sqlite';

        if (!file_exists(dirname($dbFile))) {
            mkdir(dirname($dbFile), 0755, true);
        }

        if (!file_exists($dbFile)) {
            touch($dbFile);
        }

        $dsn = 'sqlite:' . $dbFile;

        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        ];

        return new \PDO($dsn, null, null, $options);
    }
}
