<?php
// db.php

/**
 * Erstellt eine sichere PDO-Verbindung zur Datenbank.
 * Nutzt die bereits von protection.php geladenen Umgebungsvariablen.
 */

function getDBConnection() {
    // Hole die Zugangsdaten aus den Umgebungsvariablen (geladen durch .env)
    $host = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?? 'localhost';
    $name = $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?? '';
    $user = $_ENV['DB_USER'] ?? getenv('DB_USER') ?? '';
    $pass = $_ENV['DB_PASS'] ?? getenv('DB_PASS') ?? '';

    // DSN-String für MySQL (ohne fehleranfälligen Unix-Socket)
    $dsn = "mysql:host=$host;dbname=$name;charset=utf8mb4";

    try {
        $pdo = new \PDO($dsn, $user, $pass, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        ]);
        return $pdo;
    } catch (\PDOException $e) {
        // Wir werfen den Fehler weiter, damit der try-catch-Block in getBikesInfos.php ihn sauber abfängt
        throw new \Exception("Datenbank-Verbindung fehlgeschlagen: " . $e->getMessage());
    }
}