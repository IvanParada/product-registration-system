<?php

require_once __DIR__ . '/config/database.php';

header('Content-Type: application/json; charset=utf-8');

$code = $_GET['code'] ?? null;

if (!$code) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing code parameter']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id FROM products WHERE code = :code");
    $stmt->execute(['code' => $code]);

    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode(['exists' => $product !== false]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error checking product code: ' . $e->getMessage()]);
}
