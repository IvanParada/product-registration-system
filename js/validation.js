export function validateCode(code) {
    if (!code) {
        return 'El código del producto no puede estar en blanco.';
    }

    if (code.length < 5 || code.length > 15) {
        return 'El código del producto debe tener entre 5 y 15 caracteres.';
    }

    if (!/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]+$/.test(code)) {
        return 'El código del producto debe contener letras y números.';
    }

    return '';
}

export function validateName(name) {
    if (!name) {
        return 'El nombre del producto no puede estar en blanco.';
    }

    if (name.length < 2 || name.length > 50) {
        return 'El nombre del producto debe tener entre 2 y 50 caracteres.';
    }

    return '';
}

export function validatePrice(price) {
    if (!price) {
        return 'El precio del producto no puede estar en blanco.';
    }

    if (!/^\d+(\.\d{1,2})?$/.test(price) || Number(price) <= 0) {
        return 'El precio del producto debe ser un número positivo con hasta dos decimales.';
    }

    return '';
}

export function validateDescription(description) {
    if (!description) {
        return 'La descripción del producto no puede estar en blanco.';
    }

    if (description.length < 10 || description.length > 1000) {
        return 'La descripción del producto debe tener entre 10 y 1000 caracteres.';
    }

    return '';
}

export function validateWarehouse(warehouseId) {
    if (!warehouseId) {
        return 'Debe seleccionar una bodega.';
    }

    return '';
}

export function validateBranch(branchId) {
    if (!branchId) {
        return 'Debe seleccionar una sucursal para la bodega seleccionada.';
    }

    return '';
}

export function validateCurrency(currencyId) {
    if (!currencyId) {
        return 'Debe seleccionar una moneda para el producto.';
    }

    return '';
}

export function validateMaterials(materials) {
    if (materials.length < 2) {
        return 'Debe seleccionar al menos dos materiales para el producto.';
    }

    return '';
}


export const validators = {
    validateCode,
    validateName,
    validatePrice,
    validateDescription,
    validateWarehouse,
    validateBranch,
    validateCurrency,
    validateMaterials
};