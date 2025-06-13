<?php
function getPDO(){
    static $pdo;
    if (!$pdo) {
        $host   = '127.0.0.1';
        $db     = 'my_db';
        $user   = 'root';
        $pass   = '';
        $charset= 'utf8mb4';
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        $pdo = new PDO($dsn, $user, $pass, $opt);
    }
    return $pdo;
}
