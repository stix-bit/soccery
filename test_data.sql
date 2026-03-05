INSERT INTO brands(name, description, created_at, updated_at) VALUES
('Soccery Pro', 'Premium soccer gear designed for performance and durability.', NOW(), NOW()),
('Soccery Elite', 'High-end soccer equipment for elite players.', NOW(), NOW()),
('Soccery Basic', 'Affordable soccer products for beginners and casual players.', NOW(), NOW());

INSERT INTO categories(name, description, created_at, updated_at) VALUES
('Jerseys', 'Official team jerseys and training tops.', NOW(), NOW()),
('Shorts', 'Comfortable and durable soccer shorts.', NOW(), NOW()),
('Socks', 'High-quality soccer socks with extra cushioning.', NOW(), NOW()),
('Cleats', 'Lightweight soccer cleats for optimal traction.', NOW(), NOW()),
('Balls', 'Official size and weight soccer balls for training and matches.', NOW(), NOW());

INSERT INTO products (name, category_id, brand_id, description, price, stock, created_at, updated_at) VALUES
('Soccery Pro Jersey', 1, 1, 'High-quality soccer jersey with breathable fabric.', 49.99, 100, NOW(), NOW()),
('Soccery Pro Shorts', 2, 1, 'Comfortable soccer shorts designed for performance.', 29.99, 150, NOW(), NOW()),
('Soccery Pro Socks', 3, 1, 'Durable soccer socks with extra cushioning.', 9.99, 200, NOW(), NOW()),
('Soccery Pro Cleats', 4, 1, 'Lightweight soccer cleats for optimal traction.', 89.99, 50, NOW(), NOW()),
('Soccery Pro Ball', 5, 1, 'Official size and weight soccer ball for training and matches.', 19.99, 300, NOW(), NOW());