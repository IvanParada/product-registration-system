<?php

require_once __DIR__ . '/config/database.php';

header('Content-Type: application/json; charset=utf-8');

$data = json_decode(file_get_contents('php://input'), true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request data.']);

    exit;
}

$code = trim($data['code'] ?? '');
$name = trim($data['name'] ?? '');
$warehouseId = $data['warehouse_id'] ?? null;
$branchId = $data['branch_id'] ?? null;
$currencyId = $data['currency_id'] ?? null;
$price = $data['price'] ?? null;
$description = trim($data['description'] ?? '');
$materials = $data['materials'] ?? [];

$errors = [];

if ($code === '') {
    $errors[] = 'Product code is required.';
} elseif (strlen($code) < 5 || strlen($code) > 15) {
    $errors[] = 'Product code must be between 5 and 15 characters.';
} elseif (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]+$/', $code)) {
    $errors[] = 'Product code must contain letters and numbers.';
}

if ($name === '') {
    $errors[] = 'Product name is required.';
} elseif (strlen($name) < 2 || strlen($name) > 50) {
    $errors[] = 'Product name must be between 2 and 50 characters.';
}

if (!filter_var($warehouseId, FILTER_VALIDATE_INT) || $warehouseId <= 0) {
    $errors[] = 'Valid warehouse ID is required.';
}

if (!filter_var($branchId, FILTER_VALIDATE_INT) || $branchId <= 0) {
    $errors[] = 'Valid branch ID is required.';
}

if (!filter_var($currencyId, FILTER_VALIDATE_INT) || $currencyId <= 0) {
    $errors[] = 'Valid currency ID is required.';
}

if ($price === null || $price === '') {
    $errors[] = 'Product price is required.';
} elseif (!preg_match('/^\d+(\.\d{1,2})?$/', (string) $price) || (float) $price <= 0) {
    $errors[] = 'Price must be a positive number with up to two decimal places.';
}

if (!is_array($materials) || count($materials) < 2) {
    $errors[] = 'At least two materials are required.';
} else {
    foreach ($materials as $materialId) {
        if (!filter_var($materialId, FILTER_VALIDATE_INT) || $materialId <= 0) {
            $errors[] = 'Invalid material ID.';
            break;
        }
    }
}

if ($description === '') {
    $errors[] = 'The product description is required.';
} elseif (strlen($description) < 10 || strlen($description) > 1000) {
    $errors[] = 'The product description must be between 10 and 1000 characters.';
}

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit;
}

try {
    $stmt = $pdo->prepare(" SELECT id FROM branches WHERE id = :branch_id AND warehouse_id = :warehouse_id");

    $stmt->execute(['branch_id' => $branchId, 'warehouse_id' => $warehouseId]);

    if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
        http_response_code(400);

        echo json_encode([
            'success' => false,
            'error' => 'Branch does not belong to the selected warehouse.'
        ]);

        exit;
    }

    $stmt = $pdo->prepare("SELECT id FROM products WHERE code = :code");
    $stmt->execute(['code' => $code]);

    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        http_response_code(409);
        echo json_encode(['success' => false, 'error' => 'Product code already exists.']);
        exit;
    }

    $pdo->beginTransaction();
    $stmt = $pdo->prepare("
        INSERT INTO products (code, name, warehouse_id, branch_id, currency_id, price, description) 
        VALUES (:code, :name, :warehouse_id, :branch_id, :currency_id, :price, :description) RETURNING id
    ");

    $stmt->execute([
        'code' => $code,
        'name' => $name,
        'warehouse_id' => $warehouseId,
        'branch_id' => $branchId,
        'currency_id' => $currencyId,
        'price' => $price,
        'description' => $description
    ]);

    $productId = $stmt->fetchColumn();

    $materialStmt = $pdo->prepare("
        INSERT INTO product_material (product_id, material_id) 
        VALUES (:product_id, :material_id)
    ");

    foreach ($materials as $materialId) {
        $materialStmt->execute([
            'product_id' => $productId,
            'material_id' => $materialId
        ]);
    }

    $pdo->commit();

    echo json_encode(['success' => true, 'message' => 'Product saved successfully.', 'product_id' => $productId]);
} catch (PDOException $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    http_response_code(500);

    error_log('Database error: ' . $e->getMessage());

    echo json_encode([
        'success' => false,
        'error' => 'An unexpected database error occurred.'
    ]);
}
