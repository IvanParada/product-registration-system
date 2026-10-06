<?php

require_once __DIR__ . '/config/database.php';

header('Content-Type: application/json; charset=utf-8');

$warehouseId = $_GET['warehouse_id'] ?? null;

if (!$warehouseId || $warehouseId <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid warehouse_id parameter']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id, name FROM branches WHERE warehouse_id = :warehouse_id ORDER BY name");
    $stmt->execute(['warehouse_id' => $warehouseId]);
    $branches = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($branches);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error fetching branches: ' . $e->getMessage()]);
}
