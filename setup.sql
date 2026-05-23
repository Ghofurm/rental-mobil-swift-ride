-- Inisialisasi Database Swift Ride
CREATE DATABASE IF NOT EXISTS swift_ride;
USE swift_ride;

-- Buat Tabel Mobil (cars)
CREATE TABLE IF NOT EXISTS cars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    brand VARCHAR(50) NOT NULL,
    model VARCHAR(50) NOT NULL,
    type VARCHAR(30) NOT NULL,
    daily_rate DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    status ENUM('available', 'rented', 'maintenance') DEFAULT 'available',
    transmission VARCHAR(20) DEFAULT 'Automatic',
    fuel VARCHAR(20) DEFAULT 'Petrol',
    seats INT DEFAULT 5,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Hapus data sampel lama jika ada
TRUNCATE TABLE cars;

-- Masukkan Data Sampel Mobil Premium-Minimalis
INSERT INTO cars (brand, model, type, daily_rate, image, status, transmission, fuel, seats) VALUES
('Tesla', 'Model Y', 'Electric', 2200000.00, 'https://images.unsplash.com/photo-1619767886558-efdc259cde1a?auto=format&fit=crop&q=80&w=800', 'available', 'Automatic', 'Electric', 5),
('BMW', '3 Series Sedan', 'Luxury', 1900000.00, 'https://images.unsplash.com/photo-1555215695-3004980ad54e?auto=format&fit=crop&q=80&w=800', 'available', 'Automatic', 'Petrol', 5),
('Mercedes-Benz', 'C-Class', 'Luxury', 2100000.00, 'https://images.unsplash.com/photo-1617814076367-b759c7d7e738?auto=format&fit=crop&q=80&w=800', 'available', 'Automatic', 'Petrol', 5),
('Hyundai', 'Ioniq 5', 'Electric', 1700000.00, 'https://images.unsplash.com/photo-1669062335191-118e9527ec3a?auto=format&fit=crop&q=80&w=800', 'available', 'Automatic', 'Electric', 5),
('Porsche', 'Macan', 'Sports SUV', 3500000.00, 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&q=80&w=800', 'available', 'Automatic', 'Petrol', 5),
('Toyota', 'Alphard HEV', 'Premium MPV', 2800000.00, 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&q=80&w=800', 'available', 'Automatic', 'Hybrid', 7);
