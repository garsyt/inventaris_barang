-- Create Database
CREATE DATABASE IF NOT EXISTS `db_inventaris` 
DEFAULT CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `db_inventaris`;

-- 1. Tabel Users (Satu akun admin default: username 'admin', password 'admin123')
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `nama_lengkap` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`username`, `password`, `nama_lengkap`) VALUES
('admin', '$2y$10$e.xK6qW9z1BwJ7OqO9N8u.R0b4Gz8z1BwJ7OqO9N8u.R0b4Gz8z1B', 'Administrator Sistem');

-- 2. Tabel Barang
DROP TABLE IF EXISTS `barang`;
CREATE TABLE `barang` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `kode_barang` VARCHAR(20) NOT NULL UNIQUE,
  `nama_barang` VARCHAR(100) NOT NULL,
  `kategori` VARCHAR(50) NOT NULL,
  `jumlah` INT NOT NULL DEFAULT 0,
  `harga_satuan` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `tanggal_masuk` DATE NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Data Contoh (5 Data Barang)
INSERT INTO `barang` (`kode_barang`, `nama_barang`, `kategori`, `jumlah`, `harga_satuan`, `tanggal_masuk`) VALUES
('BRG-001', 'Laptop Asus Zenbook 14', 'Elektronik', 12, 14500000.00, '2026-01-15'),
('BRG-002', 'Kursi Kerja Ergonomis', 'Furnitur', 25, 1850000.00, '2026-02-10'),
('BRG-003', 'Printer Canon Pixma G3020', 'Elektronik', 8, 2300000.00, '2026-03-01'),
('BRG-004', 'Meja Rapat Kayu Jati', 'Furnitur', 4, 5500000.00, '2026-03-12'),
('BRG-005', 'Proyektor Epson EB-E500', 'Elektronik', 6, 6200000.00, '2026-04-05');