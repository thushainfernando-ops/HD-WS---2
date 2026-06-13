-- Create categories table
CREATE TABLE IF NOT EXISTS categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    slug VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default categories
INSERT INTO categories (name, slug) VALUES 
('Rice & Curry', 'rice-curry'),
('Noodles', 'noodles'),
('Pasta', 'pasta'),
('Fried Rice', 'fried-rice')
ON DUPLICATE KEY UPDATE name=name;
