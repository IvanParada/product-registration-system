<?php

require_once __DIR__ . '/config/database.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $stmt = $pdo->query("SELECT id, name FROM warehouses ORDER BY name");
    $warehouses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($warehouses);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error fetching warehouses: ' . $e->getMessage()]);
}
