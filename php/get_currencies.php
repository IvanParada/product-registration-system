<?php

require_once __DIR__ . '/config/database.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $stmt = $pdo->query("SELECT id, code, name FROM currencies ORDER BY name");
    $currencies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($currencies);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error fetching currencies: ' . $e->getMessage()]);
}
