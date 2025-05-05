CREATE DATABASE IF NOT EXISTS tourista_db;
USE tourista_db;

CREATE TABLE IF NOT EXISTS package_prices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    package_type VARCHAR(50) NOT NULL,
    base_price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tour_bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    package_type VARCHAR(50) NOT NULL,
    num_people INT NOT NULL,
    travel_date DATE NOT NULL,
    special_requirements TEXT,
    total_price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO package_prices (package_type, base_price) VALUES
('luxury', 1000.00),
('standard', 750.00),
('budget', 500.00),
('family', 850.00),
('honeymoon', 1200.00); 

