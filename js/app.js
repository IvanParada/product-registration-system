import {
    getWarehouses,
    getCurrencies,
    getBranches,
    getMaterials,
    saveProduct
} from './api.js';

import { validators } from './validation.js';

const materialsContainer = document.getElementById('materialsContainer');
const materialsError = document.getElementById('materialsContainerError');
const productForm = document.getElementById('productForm');
const submitButton = productForm.querySelector('button[type="submit"]');

function bindValidation({
    fieldId,
    errorId,
    event = 'blur',
    validator,
    transform = (value) => value.trim()
}) {
    const field = document.getElementById(fieldId);
    const error = document.getElementById(errorId);

    field.addEventListener(event, () => {
        const value = transform(field.value);
        error.textContent = validator(value);
    });

    return { field, error };
}

bindValidation({
    fieldId: 'code',
    errorId: 'codeError',
    validator: validators.validateCode
});

bindValidation({
    fieldId: 'name',
    errorId: 'nameError',
    validator: validators.validateName
});

bindValidation({
    fieldId: 'price',
    errorId: 'priceError',
    validator: validators.validatePrice
});

bindValidation({
    fieldId: 'description',
    errorId: 'descriptionError',
    validator: validators.validateDescription
});

const { field: warehouseSelect } = bindValidation({
    fieldId: 'warehouse',
    errorId: 'warehouseError',
    event: 'change',
    validator: validators.validateWarehouse,
    transform: (value) => value
});

const { field: branchSelect } = bindValidation({
    fieldId: 'branch',
    errorId: 'branchError',
    event: 'change',
    validator: validators.validateBranch,
    transform: (value) => value
});

const { field: currencySelect } = bindValidation({
    fieldId: 'currency',
    errorId: 'currencyError',
    event: 'change',
    validator: validators.validateCurrency,
    transform: (value) => value
});

async function loadWarehouses() {
    try {
        const warehouses = await getWarehouses();

        warehouses.forEach((warehouse) => {
            const option = document.createElement('option');

            option.value = warehouse.id;
            option.textContent = warehouse.name;

            warehouseSelect.appendChild(option);
        });
    } catch (error) {
        console.error('Error loading warehouses:', error);
    }
}

async function loadCurrencies() {
    try {
        const currencies = await getCurrencies();

        currencies.forEach((currency) => {
            const option = document.createElement('option');

            option.value = currency.id;
            option.textContent = currency.name;

            currencySelect.appendChild(option);
        });
    } catch (error) {
        console.error('Error loading currencies:', error);
    }
}

async function loadMaterials() {
    try {
        const materials = await getMaterials();

        materialsContainer.innerHTML = '';

        materials.forEach((material) => {
            const label = document.createElement('label');
            label.className = 'checkbox-item';

            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.name = 'materials[]';
            checkbox.value = material.id;

            const span = document.createElement('span');
            span.textContent = material.name;

            label.appendChild(checkbox);
            label.appendChild(span);

            materialsContainer.appendChild(label);
        });
    } catch (error) {
        console.error('Error loading materials:', error);
    }
}

warehouseSelect.addEventListener('change', async () => {
    const warehouseId = warehouseSelect.value;

    branchSelect.innerHTML = '<option value=""></option>';

    if (!warehouseId) {
        return;
    }

    try {
        const branches = await getBranches(warehouseId);

        branches.forEach((branch) => {
            const option = document.createElement('option');

            option.value = branch.id;
            option.textContent = branch.name;

            branchSelect.appendChild(option);
        });
    } catch (error) {
        console.error('Error loading branches:', error);
    }
});

materialsContainer.addEventListener('change', () => {
    const selectedMaterials = Array.from(
        materialsContainer.querySelectorAll(
            'input[type="checkbox"]:checked'
        )
    ).map((checkbox) => checkbox.value);

    materialsError.textContent = validators.validateMaterials(selectedMaterials);
});

productForm.addEventListener('submit', async (event) => {
    event.preventDefault();

    const selectedMaterials = Array.from(
        materialsContainer.querySelectorAll(
            'input[type="checkbox"]:checked'
        )
    ).map((checkbox) => checkbox.value);

    const product = {
        code: document.getElementById('code').value.trim(),
        name: document.getElementById('name').value.trim(),
        warehouse_id: warehouseSelect.value,
        branch_id: branchSelect.value,
        currency_id: currencySelect.value,
        price: document.getElementById('price').value.trim(),
        materials: selectedMaterials,
        description: document.getElementById('description').value.trim()
    };

    const errors = {
        code: validators.validateCode(product.code),
        name: validators.validateName(product.name),
        warehouse: validators.validateWarehouse(product.warehouse_id),
        branch: validators.validateBranch(product.branch_id),
        currency: validators.validateCurrency(product.currency_id),
        price: validators.validatePrice(product.price),
        materials: validators.validateMaterials(product.materials),
        description: validators.validateDescription(product.description)
    };

    document.getElementById('codeError').textContent = errors.code;
    document.getElementById('nameError').textContent = errors.name;
    document.getElementById('warehouseError').textContent = errors.warehouse;
    document.getElementById('branchError').textContent = errors.branch;
    document.getElementById('currencyError').textContent = errors.currency;
    document.getElementById('priceError').textContent = errors.price;
    materialsError.textContent = errors.materials;
    document.getElementById('descriptionError').textContent = errors.description;

    const hasErrors = Object.values(errors).some((error) => error !== '');

    if (hasErrors) {
        return;
    }

    try {
        submitButton.disabled = true;
        submitButton.textContent = 'Guardando...';
        const response = await saveProduct(product);

        console.log(response);

        alert('Producto guardado correctamente.');

        productForm.reset();
        branchSelect.innerHTML = '<option value=""></option>';
        document.querySelectorAll('.field-error').forEach((error) => {
            error.textContent = '';
        });

    } catch (error) {
        console.error('Error saving product:', error);

        if (error.error === 'Product code already exists.') {
            document.getElementById('codeError').textContent =
                'El código del producto ya está registrado.';
        }

    } finally {
        submitButton.disabled = false;
        submitButton.textContent = 'Guardar Producto';
    }
});

loadWarehouses();
loadCurrencies();
loadMaterials();