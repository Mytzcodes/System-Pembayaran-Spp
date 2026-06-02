-- Database: spp_sekolah
-- Sistem Pembayaran SPP Sekolah
-- Created: 2026-01-25

DROP DATABASE IF EXISTS spp_sekolah;
CREATE DATABASE spp_sekolah CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE spp_sekolah;

-- Table: users (untuk login semua role)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'petugas', 'siswa') NOT NULL,
    email VARCHAR(100),
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_role (role)
) ENGINE=InnoDB;

-- Table: kelas
CREATE TABLE kelas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kelas VARCHAR(20) NOT NULL,
    kompetensi_keahlian VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_nama (nama_kelas)
) ENGINE=InnoDB;

-- Table: spp (tarif SPP)
CREATE TABLE spp (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tahun INT NOT NULL,
    nominal INT NOT NULL,
    keterangan VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_tahun (tahun)
) ENGINE=InnoDB;

-- Table: siswa
CREATE TABLE siswa (
    nisn VARCHAR(10) PRIMARY KEY,
    nis VARCHAR(8) UNIQUE NOT NULL,
    nama VARCHAR(100) NOT NULL,
    id_kelas INT NOT NULL,
    alamat TEXT,
    no_telp VARCHAR(15),
    id_spp INT NOT NULL,
    user_id INT UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_kelas) REFERENCES kelas(id) ON DELETE RESTRICT,
    FOREIGN KEY (id_spp) REFERENCES spp(id) ON DELETE RESTRICT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_nama (nama),
    INDEX idx_kelas (id_kelas)
) ENGINE=InnoDB;

-- Table: petugas
CREATE TABLE petugas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    nama_petugas VARCHAR(100) NOT NULL,
    no_telp VARCHAR(15),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_nama (nama_petugas)
) ENGINE=InnoDB;

-- Table: pembayaran
CREATE TABLE pembayaran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nisn VARCHAR(10) NOT NULL,
    id_petugas INT NOT NULL,
    id_spp INT NOT NULL,
    bulan_dibayar TINYINT NOT NULL,
    tahun_dibayar INT NOT NULL,
    jumlah_bayar INT NOT NULL,
    tgl_bayar TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (nisn) REFERENCES siswa(nisn) ON DELETE CASCADE,
    FOREIGN KEY (id_petugas) REFERENCES petugas(id) ON DELETE RESTRICT,
    FOREIGN KEY (id_spp) REFERENCES spp(id) ON DELETE RESTRICT,
    UNIQUE KEY unique_payment (nisn, bulan_dibayar, tahun_dibayar),
    INDEX idx_nisn (nisn),
    INDEX idx_tgl (tgl_bayar),
    INDEX idx_tahun_bulan (tahun_dibayar, bulan_dibayar)
) ENGINE=InnoDB;

-- Table: audit_log
CREATE TABLE audit_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    action VARCHAR(50) NOT NULL,
    actor_id INT,
    actor_role VARCHAR(20),
    target_table VARCHAR(50),
    target_id VARCHAR(50),
    payload JSON,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_action (action),
    INDEX idx_actor (actor_id),
    INDEX idx_created (created_at)
) ENGINE=InnoDB;

-- Stored Procedure: sp_tambah_pembayaran
DELIMITER $$
CREATE PROCEDURE sp_tambah_pembayaran(
    IN p_nisn VARCHAR(10),
    IN p_petugas_id INT,
    IN p_spp_id INT,
    IN p_bulan TINYINT,
    IN p_tahun INT,
    IN p_jumlah INT,
    IN p_keterangan TEXT,
    OUT p_result INT,
    OUT p_message VARCHAR(255),
    OUT p_payment_id INT
)
BEGIN
    DECLARE v_count INT;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SET p_result = 0;
        SET p_message = 'Error: Transaksi gagal';
        SET p_payment_id = NULL;
    END;
    
    START TRANSACTION;
    
    -- Cek duplikasi pembayaran
    SELECT COUNT(*) INTO v_count 
    FROM pembayaran 
    WHERE nisn = p_nisn 
      AND bulan_dibayar = p_bulan 
      AND tahun_dibayar = p_tahun;
    
    IF v_count > 0 THEN
        ROLLBACK;
        SET p_result = 0;
        SET p_message = 'Error: Pembayaran untuk bulan dan tahun ini sudah ada';
        SET p_payment_id = NULL;
    ELSE
        -- Insert pembayaran
        INSERT INTO pembayaran (nisn, id_petugas, id_spp, bulan_dibayar, tahun_dibayar, jumlah_bayar, keterangan)
        VALUES (p_nisn, p_petugas_id, p_spp_id, p_bulan, p_tahun, p_jumlah, p_keterangan);
        
        SET p_payment_id = LAST_INSERT_ID();
        
        -- Insert audit log
        INSERT INTO audit_log (action, actor_id, actor_role, target_table, target_id, payload)
        VALUES (
            'create_pembayaran',
            p_petugas_id,
            'petugas',
            'pembayaran',
            p_payment_id,
            JSON_OBJECT(
                'nisn', p_nisn,
                'bulan', p_bulan,
                'tahun', p_tahun,
                'jumlah', p_jumlah
            )
        );
        
        COMMIT;
        SET p_result = 1;
        SET p_message = 'Success: Pembayaran berhasil ditambahkan';
    END IF;
END$$
DELIMITER ;

-- Stored Procedure: sp_get_history_siswa
DELIMITER $$
CREATE PROCEDURE sp_get_history_siswa(IN p_nisn VARCHAR(10))
BEGIN
    SELECT 
        p.id,
        p.bulan_dibayar,
        p.tahun_dibayar,
        p.jumlah_bayar,
        p.tgl_bayar,
        p.keterangan,
        pt.nama_petugas,
        s.nominal as nominal_spp
    FROM pembayaran p
    JOIN petugas pt ON p.id_petugas = pt.id
    JOIN spp s ON p.id_spp = s.id
    WHERE p.nisn = p_nisn
    ORDER BY p.tahun_dibayar DESC, p.bulan_dibayar DESC;
END$$
DELIMITER ;

-- Trigger: after_insert_pembayaran
DELIMITER $$
CREATE TRIGGER after_insert_pembayaran
AFTER INSERT ON pembayaran
FOR EACH ROW
BEGIN
    -- Backup audit log (jika stored proc gagal)
    IF NOT EXISTS (
        SELECT 1 FROM audit_log 
        WHERE target_table = 'pembayaran' 
          AND target_id = NEW.id 
          AND action = 'create_pembayaran'
    ) THEN
        INSERT INTO audit_log (action, target_table, target_id, payload)
        VALUES (
            'insert_pembayaran_trigger',
            'pembayaran',
            NEW.id,
            JSON_OBJECT(
                'nisn', NEW.nisn,
                'bulan', NEW.bulan_dibayar,
                'tahun', NEW.tahun_dibayar,
                'jumlah', NEW.jumlah_bayar
            )
        );
    END IF;
END$$
DELIMITER ;

-- Seed Data
-- Admin user (password: Admin123!)
INSERT INTO users (username, password, role, email) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'admin@spp.sch.id');

-- Petugas users (password: Petugas123!)
INSERT INTO users (username, password, role, email) VALUES
('petugas', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'petugas', 'petugas@spp.sch.id'),
('petugas2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'petugas', 'petugas2@spp.sch.id');

-- Kelas
INSERT INTO kelas (nama_kelas, kompetensi_keahlian) VALUES
('X RPL 1', 'Rekayasa Perangkat Lunak'),
('XI RPL 1', 'Rekayasa Perangkat Lunak'),
('XII RPL 1', 'Rekayasa Perangkat Lunak');

-- SPP Tarif
INSERT INTO spp (tahun, nominal, keterangan) VALUES
(2024, 300000, 'SPP Tahun Ajaran 2024/2025'),
(2025, 350000, 'SPP Tahun Ajaran 2025/2026'),
(2026, 350000, 'SPP Tahun Ajaran 2026/2027');

-- Petugas
INSERT INTO petugas (user_id, nama_petugas, no_telp) VALUES
(2, 'Budi Santoso', '081234567890'),
(3, 'Siti Nurhaliza', '081234567891');

-- Siswa users (password: Siswa123!)
INSERT INTO users (username, password, role, email) VALUES
('siswa1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siswa', 'siswa1@spp.sch.id'),
('siswa2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siswa', 'siswa2@spp.sch.id'),
('siswa3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siswa', 'siswa3@spp.sch.id'),
('siswa4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siswa', 'siswa4@spp.sch.id'),
('siswa5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siswa', 'siswa5@spp.sch.id'),
('siswa6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siswa', 'siswa6@spp.sch.id'),
('siswa7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siswa', 'siswa7@spp.sch.id'),
('siswa8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siswa', 'siswa8@spp.sch.id'),
('siswa9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siswa', 'siswa9@spp.sch.id'),
('siswa10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siswa', 'siswa10@spp.sch.id');

-- Siswa data
INSERT INTO siswa (nisn, nis, nama, id_kelas, alamat, no_telp, id_spp, user_id) VALUES
('0051234567', '20240001', 'Ahmad Fauzi', 1, 'Jl. Merdeka No. 10, Jakarta', '081234560001', 1, 4),
('0051234568', '20240002', 'Siti Aminah', 1, 'Jl. Sudirman No. 20, Jakarta', '081234560002', 1, 5),
('0051234569', '20240003', 'Budi Hartono', 2, 'Jl. Gatot Subroto No. 30, Jakarta', '081234560003', 1, 6),
('0051234570', '20240004', 'Dewi Lestari', 2, 'Jl. Thamrin No. 40, Jakarta', '081234560004', 1, 7),
('0051234571', '20240005', 'Eko Prasetyo', 3, 'Jl. Kuningan No. 50, Jakarta', '081234560005', 2, 8),
('0051234572', '20240006', 'Fitri Handayani', 3, 'Jl. Senayan No. 60, Jakarta', '081234560006', 2, 9),
('0051234573', '20240007', 'Gilang Ramadhan', 1, 'Jl. Pancoran No. 70, Jakarta', '081234560007', 1, 10),
('0051234574', '20240008', 'Hana Pertiwi', 2, 'Jl. Cikini No. 80, Jakarta', '081234560008', 1, 11),
('0051234575', '20240009', 'Indra Gunawan', 3, 'Jl. Menteng No. 90, Jakarta', '081234560009', 2, 12),
('0051234576', '20240010', 'Joko Widodo', 1, 'Jl. Kemang No. 100, Jakarta', '081234560010', 1, 13);

-- Sample pembayaran
INSERT INTO pembayaran (nisn, id_petugas, id_spp, bulan_dibayar, tahun_dibayar, jumlah_bayar, keterangan) VALUES
('0051234567', 1, 1, 1, 2024, 300000, 'Pembayaran Januari 2024'),
('0051234567', 1, 1, 2, 2024, 300000, 'Pembayaran Februari 2024'),
('0051234567', 1, 1, 3, 2024, 300000, 'Pembayaran Maret 2024'),
('0051234568', 1, 1, 1, 2024, 300000, 'Pembayaran Januari 2024'),
('0051234568', 2, 1, 2, 2024, 300000, 'Pembayaran Februari 2024'),
('0051234569', 1, 1, 1, 2024, 300000, 'Pembayaran Januari 2024'),
('0051234570', 2, 1, 1, 2024, 300000, 'Pembayaran Januari 2024'),
('0051234571', 1, 2, 1, 2025, 350000, 'Pembayaran Januari 2025');
