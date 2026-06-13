-- Database Creation
DROP DATABASE IF EXISTS hot_dish_db;
CREATE DATABASE hot_dish_db;
USE hot_dish_db;

-- 1. Users Table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Plain text for assignment/sample, hashed in production
    phone VARCHAR(20),
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Menu Items Table
CREATE TABLE menu_items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    category VARCHAR(50) NOT NULL, -- rice-curry, noodles, pasta, fried-rice
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. Cart Items Table
CREATE TABLE cart_items (
    cart_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    item_id INT NOT NULL,
    quantity INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES menu_items(item_id) ON DELETE CASCADE
);

-- 4. Orders Table
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('Pending', 'Completed', 'Cancelled') DEFAULT 'Pending',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- 5. Order Items Table
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    item_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL, -- Price at time of order
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES menu_items(item_id)
);

-- 6. Payments Table
CREATE TABLE payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    payment_method VARCHAR(50) NOT NULL, -- card, cash
    amount DECIMAL(10, 2) NOT NULL,
    status ENUM('Pending', 'Completed', 'Failed') DEFAULT 'Pending',
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE
);

-- SAMPLE DATA INSERTION

-- Users
INSERT INTO users (full_name, email, password, phone, role) VALUES 
('John Doe', 'john@example.com', 'password123', '0771234567', 'user'),
('Admin User', 'admin@hotdish.com', 'admin123', '0777654321', 'admin');

-- Menu Items
INSERT INTO menu_items (name, description, price, category, image) VALUES 
('Traditional Rice & Curry', 'Authentic Sri Lankan rice with 5 curries', 850.00, 'rice-curry', 'assets/vegetable-rice-curry.jpg'),
('Chicken Rice & Curry', 'Spicy chicken curry with rice and sides', 950.00, 'rice-curry', 'assets/rice-and-curry.jpg'),
('Seafood Rice & Curry', 'Ocean fresh seafood with red rice', 1200.00, 'rice-curry', 'assets/seafood-rice-curry.jpg'),

('Seafood Noodles', 'Fresh seafood stir-fried with noodles', 1250.00, 'noodles', 'assets/seafood-noodles-new.jpg'),
('Mixed Noodles', 'Chicken, egg, and vegetable mix noodles', 1350.00, 'noodles', 'assets/mixed-noodles.jpg'),

('Mixed Fried Rice', 'Special fried rice with mixed meats', 1400.00, 'fried-rice', 'assets/mixed-fried-rice.jpg'),
('Special Fried Rice', 'Special fried rice with exotic spices', 1450.00, 'fried-rice', 'assets/chicken-biryani.jpg'),

('Creamy Chicken Pasta', 'Creamy pasta with grilled chicken', 1150.00, 'pasta', 'assets/prawn-curry.jpg');
