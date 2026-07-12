-- Inisialisasi Database Swift Ride
CREATE DATABASE IF NOT EXISTS swift_ride CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE swift_ride;

-- Buat Tabel Mobil (cars)
CREATE TABLE IF NOT EXISTS cars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    brand VARCHAR(50) NOT NULL,
    model VARCHAR(50) NOT NULL,
    type VARCHAR(30) NOT NULL,
    daily_rate DECIMAL(12, 2) NOT NULL,
    image VARCHAR(512) NOT NULL DEFAULT '',
    status ENUM('available', 'rented', 'maintenance') NOT NULL DEFAULT 'available',
    transmission VARCHAR(20) NOT NULL DEFAULT 'Automatic',
    fuel VARCHAR(20) NOT NULL DEFAULT 'Petrol',
    seats TINYINT NOT NULL DEFAULT 5,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Buat Tabel Admin (admins)
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Masukkan Admin Default jika belum ada (Password: password123)
-- Hash di-generate menggunakan password_hash() PHP: PASSWORD_DEFAULT
INSERT IGNORE INTO admins (id, name, email, password) VALUES
(1, 'Super Admin', 'admin@swiftride.id', '$2y$10$tZ26zF5mO31fJ24d6tGfUOuWnK7mC1n3Y8i4XgP8.x1t2d3e4f5g6');

-- Masukkan Data Sampel Mobil Premium-Minimalis
-- Catatan: Menggunakan INSERT IGNORE untuk menghindari duplikasi jika dijalankan ulang
INSERT IGNORE INTO cars (id, brand, model, type, daily_rate, image, status, transmission, fuel, seats, description) VALUES
(1, 'Tesla', 'Model Y', 'Electric', 2200000.00, 'https://images.unsplash.com/photo-1619767886558-efdc259cde1a?auto=format&fit=crop&q=80&w=800', 'available', 'Automatic', 'Electric', 5, 'Mobil listrik premium dengan kenyamanan maksimal dan jangkauan jarak jauh.'),
(2, 'BMW', '3 Series Sedan', 'Luxury', 1900000.00, 'https://images.unsplash.com/photo-1555215695-3004980ad54e?auto=format&fit=crop&q=80&w=800', 'available', 'Automatic', 'Petrol', 5, 'Sedan sport premium dengan performa dinamis dan kenyamanan luar biasa.'),
(3, 'Mercedes-Benz', 'C-Class', 'Luxury', 2100000.00, 'https://images.unsplash.com/photo-1617814076367-b759c7d7e738?auto=format&fit=crop&q=80&w=800', 'available', 'Automatic', 'Petrol', 5, 'Elegansi berpadu dengan performa modern dalam kelas sedan mewah.'),
(4, 'Hyundai', 'Ioniq 5', 'Electric', 1700000.00, 'https://images.unsplash.com/photo-1669062335191-118e9527ec3a?auto=format&fit=crop&q=80&w=800', 'available', 'Automatic', 'Electric', 5, 'Crossover listrik masa depan dengan kabin luas dan teknologi futuristik.'),
(5, 'Porsche', 'Macan', 'Sports SUV', 3500000.00, 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&q=80&w=800', 'available', 'Automatic', 'Petrol', 5, 'SUV performa tinggi dengan DNA mobil sport Porsche yang kental.'),
(6, 'Toyota', 'Alphard HEV', 'Premium MPV', 2800000.00, 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&q=80&w=800', 'available', 'Automatic', 'Hybrid', 7, 'MPV premium dengan kabin termewah, sangat cocok untuk keluarga dan eksekutif.');

