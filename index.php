<?php

require_once __DIR__ . '/components/index.php';

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Formulario de Producto</title>
</head>

<body>

    <main>
        <h1>Formulario de Producto</h1>

        <form id="productForm">
            <div class="form-grid">
                <?php renderInput('code', 'code', 'Código'); ?>
                <?php renderInput('name', 'name', 'Nombre'); ?>
                <?php renderSelect('warehouse', 'warehouse_id', 'Bodega'); ?>
                <?php renderSelect('branch', 'branch_id', 'Sucursal'); ?>
                <?php renderSelect('currency', 'currency_id', 'Moneda'); ?>
                <?php renderInput('price', 'price', 'Precio'); ?>
            </div>

            <?php renderCheckboxGroup('materialsContainer', 'Material del Producto'); ?>
            <?php renderTextarea('description', 'description', 'Descripción'); ?>
            <?php renderSubmitButton('Guardar Producto'); ?>

        </form>
    </main>

</body>

</html>
<script type="module" src="js/app.js"></script>