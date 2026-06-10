CREATE DATABASE kitap_dunyasi;
USE kitap_dunyasi;

-- Users table
CREATE TABLE kullanicilar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kullanici_adi VARCHAR(50) NOT NULL UNIQUE,
    sifre VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    ad_soyad VARCHAR(100) NOT NULL,
    adres TEXT,
    telefon VARCHAR(20),
    kayit_tarihi DATETIME DEFAULT CURRENT_TIMESTAMP,
    admin BOOLEAN DEFAULT FALSE
);

-- Books table
CREATE TABLE kitaplar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    baslik VARCHAR(255) NOT NULL,
    yazar VARCHAR(255) NOT NULL,
    aciklama TEXT,
    fiyat DECIMAL(10,2) NOT NULL,
    stok INT NOT NULL,
    kategori VARCHAR(100),
    kapak_resmi VARCHAR(255),
    eklenme_tarihi DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Orders table
CREATE TABLE siparisler (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kullanici_id INT NOT NULL,
    siparis_tarihi DATETIME DEFAULT CURRENT_TIMESTAMP,
    toplam_fiyat DECIMAL(10,2) NOT NULL,
    durum ENUM('beklemede', 'hazırlanıyor', 'kargoda', 'teslim edildi') DEFAULT 'beklemede',
    adres TEXT NOT NULL,
    FOREIGN KEY (kullanici_id) REFERENCES kullanicilar(id)
);

-- Order items table
CREATE TABLE siparis_urunleri (
    id INT AUTO_INCREMENT PRIMARY KEY,
    siparis_id INT NOT NULL,
    kitap_id INT NOT NULL,
    miktar INT NOT NULL,
    birim_fiyat DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (siparis_id) REFERENCES siparisler(id),
    FOREIGN KEY (kitap_id) REFERENCES kitaplar(id)
);