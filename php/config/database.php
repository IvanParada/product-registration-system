<?php
$host = 'localhost';
$port = 5432;
$user = 'postgres';
$password = 'YOUR_PASSWORD';
$dbname = 'prueba_productos';

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error connecting to the database: " . $e->getMessage());
}
