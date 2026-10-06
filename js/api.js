const BASE_URL = './php';

export async function getWarehouses() {
    const response = await fetch(`${BASE_URL}/get_warehouses.php`);

    if (!response.ok) {
        throw new Error('Error fetching warehouses');
    }

    return response.json();
}


export async function getCurrencies() {
    const response = await fetch(`${BASE_URL}/get_currencies.php`);

    if (!response.ok) {
        throw new Error('Error fetching currencies');
    }

    return response.json();
}

export async function getBranches(warehouseId) {
    const response = await fetch(`${BASE_URL}/get_branches.php?warehouse_id=${warehouseId}`);

    if (!response.ok) {
        throw new Error('Error fetching branches');
    }

    return response.json();
}

export async function getMaterials() {
    const response = await fetch(`${BASE_URL}/get_materials.php`);

    if (!response.ok) {
        throw new Error('Failed to fetch materials');
    }

    return response.json();
}

export async function saveProduct(productData) {
    const response = await fetch(`${BASE_URL}/save_product.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(productData)
    });

    const data = await response.json();

    if (!response.ok) {
        throw data;
    }
    
    return data;
}