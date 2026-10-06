CREATE TABLE
    warehouses (id SERIAL PRIMARY KEY, name VARCHAR(100) NOT NULL);

INSERT INTO
    warehouses (name)
VALUES
    ('Bodega Central'),
    ('Bodega Norte');

CREATE TABLE
    branches (
        id SERIAL PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        warehouse_id INTEGER NOT NULL,
        CONSTRAINT fk_branch_warehouse FOREIGN KEY (warehouse_id) REFERENCES warehouses (id)
    );

INSERT INTO
    branches (name, warehouse_id)
VALUES
    ('Sucursal Centro', 1),
    ('Sucursal Sur', 1),
    ('Sucursal Norte', 2),
    ('Sucursal Costanera', 2);

CREATE TABLE
    currencies (
        id SERIAL PRIMARY KEY,
        code VARCHAR(10) NOT NULL UNIQUE,
        name VARCHAR(50) NOT NULL
    );

INSERT INTO
    currencies (code, name)
VALUES
    ('CLP', 'Peso chileno'),
    ('USD', 'Dólar estadounidense'),
    ('EUR', 'Euro');

CREATE TABLE
    materials (
        id SERIAL PRIMARY KEY,
        name VARCHAR(50) NOT NULL UNIQUE
    );

INSERT INTO
    materials (name)
VALUES
    ('Plástico'),
    ('Metal'),
    ('Madera'),
    ('Vidrio'),
    ('Textil');

CREATE TABLE
    products (
        id SERIAL PRIMARY KEY,
        code VARCHAR(15) NOT NULL UNIQUE,
        name VARCHAR(50) NOT NULL,
        warehouse_id INTEGER NOT NULL,
        branch_id INTEGER NOT NULL,
        currency_id INTEGER NOT NULL,
        price NUMERIC(12, 2) NOT NULL,
        description VARCHAR(1000) NOT NULL,
        CONSTRAINT fk_product_warehouse FOREIGN KEY (warehouse_id) REFERENCES warehouses (id),
        CONSTRAINT fk_product_branch FOREIGN KEY (branch_id) REFERENCES branches (id),
        CONSTRAINT fk_product_currency FOREIGN KEY (currency_id) REFERENCES currencies (id)
    );

CREATE TABLE
    product_material (
        product_id INTEGER NOT NULL,
        material_id INTEGER NOT NULL,
        PRIMARY KEY (product_id, material_id),
        CONSTRAINT fk_product_material_product FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE,
        CONSTRAINT fk_product_material_material FOREIGN KEY (material_id) REFERENCES materials (id)
    );