<?php

require_once __DIR__ . '/config/database.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $stmt = $pdo->query("SELECT id, name FROM materials ORDER BY name");
    $materials = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($materials);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error fetching materials: ' . $e->getMessage()]);
}
