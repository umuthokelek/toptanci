-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Anamakine: localhost
-- Üretim Zamanı: 30 Nis 2019, 20:02:11
-- Sunucu sürümü: 10.3.13-MariaDB
-- PHP Sürümü: 7.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `toptanci`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `siparisler`
--

CREATE TABLE `siparisler` (
  `id` int(11) NOT NULL,
  `sube` int(11) NOT NULL,
  `urun` int(11) NOT NULL,
  `adet` int(11) NOT NULL,
  `ucret` decimal(13,2) NOT NULL,
  `tarih` date NOT NULL,
  `odeme` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `siparisler`
--

INSERT INTO `siparisler` (`id`, `sube`, `urun`, `adet`, `ucret`, `tarih`, `odeme`) VALUES
(1, 1, 4, 20, '239.80', '2019-02-05', 'Ödendi'),
(2, 1, 1, 50, '195.00', '2019-02-23', 'Ödendi'),
(3, 1, 2, 25, '36.25', '2019-02-23', 'Ödenmedi'),
(4, 1, 4, 15, '179.85', '2019-02-24', 'Ödenmedi'),
(5, 1, 5, 15, '22.50', '2019-02-24', 'Ödenmedi'),
(6, 1, 7, 15, '103.50', '2019-02-24', 'Ödendi'),
(7, 2, 5, 20, '30.00', '2019-02-24', 'Ödendi'),
(8, 2, 2, 50, '72.50', '2019-02-24', 'Ödenmedi'),
(9, 1, 8, 50, '99.50', '2019-02-28', 'Ödenmedi'),
(10, 1, 6, 50, '99.50', '2019-03-14', 'Ödendi'),
(11, 1, 1, 15, '58.50', '2019-04-28', 'Ödenmedi'),
(12, 2, 5, 20, '30.00', '2019-04-28', 'Ödendi');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `subeler`
--

CREATE TABLE `subeler` (
  `id` int(11) NOT NULL,
  `ad` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sifre` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `subeler`
--

INSERT INTO `subeler` (`id`, `ad`, `sifre`) VALUES
(1, 'BAHCELIEVLER', '123'),
(2, 'MEZITLI', '456'),
(3, 'POZCU', '789'),
(4, 'TOROSLAR', '012'),
(5, 'YENISEHIR', '345'),
(6, 'ERDEMLI', '678'),
(7, 'CARSI', '901'),
(8, 'KIZKALESI', '234'),
(9, 'SOLI', '567'),
(11, 'TEST', '455'),
(12, 'TEST2', '789');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `toptancilar`
--

CREATE TABLE `toptancilar` (
  `id` int(11) NOT NULL,
  `ad` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sifre` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `toptancilar`
--

INSERT INTO `toptancilar` (`id`, `ad`, `sifre`) VALUES
(1, 'UMUT', '123'),
(2, 'VOLKAN', '456'),
(3, 'ATAKAN', '789'),
(4, 'ADMIN', '1234');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `urunler`
--

CREATE TABLE `urunler` (
  `id` int(11) NOT NULL,
  `ad` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `stok` int(11) NOT NULL,
  `fiyat` decimal(13,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `urunler`
--

INSERT INTO `urunler` (`id`, `ad`, `stok`, `fiyat`) VALUES
(1, 'Coca Cola 2.5 Lt', 475, '3.90'),
(2, 'Eti Karam', 295, '1.45'),
(3, 'Erikli Su 0.5 Lt', 1121, '0.75'),
(4, 'Yudum Ayçiçek Yağı 2.5 Lt', 1760, '11.99'),
(5, 'Nescafe 3\'ü 1 Arada', 7455, '1.50'),
(6, 'Dimes Karışık Meyve Suyu 200 Ml', 5535, '1.99'),
(7, 'Perwoll Siyah Sihir 3 Lt', 266, '6.90'),
(8, 'Dimes Vişneli Meyve Suyu 200 Ml', 2850, '1.99'),
(9, 'Coca Cola 1 Lt', 650, '3.00');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `siparisler`
--
ALTER TABLE `siparisler`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `subeler`
--
ALTER TABLE `subeler`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `toptancilar`
--
ALTER TABLE `toptancilar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `urunler`
--
ALTER TABLE `urunler`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `siparisler`
--
ALTER TABLE `siparisler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Tablo için AUTO_INCREMENT değeri `subeler`
--
ALTER TABLE `subeler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Tablo için AUTO_INCREMENT değeri `toptancilar`
--
ALTER TABLE `toptancilar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `urunler`
--
ALTER TABLE `urunler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
