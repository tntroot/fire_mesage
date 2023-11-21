-- --------------------------------------------------------
-- 主機:                           localhost
-- 伺服器版本:                        10.4.25-MariaDB - mariadb.org binary distribution
-- 伺服器作業系統:                      Win64
-- HeidiSQL 版本:                  11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- 傾印 fire_management 的資料庫結構
CREATE DATABASE IF NOT EXISTS `fire_management` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `fire_management`;

-- 傾印  資料表 fire_management.box_data 結構
CREATE TABLE IF NOT EXISTS `box_data` (
  `Num` char(6) NOT NULL,
  `Location_ID` bigint(20) DEFAULT NULL,
  `Pair` enum('上架','下架') NOT NULL DEFAULT '下架',
  `Status` enum('正常','廢棄') NOT NULL DEFAULT '正常',
  PRIMARY KEY (`Num`),
  KEY `Place_number` (`Location_ID`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在傾印表格  fire_management.box_data 的資料：~25 rows (近似值)
/*!40000 ALTER TABLE `box_data` DISABLE KEYS */;
INSERT INTO `box_data` (`Num`, `Location_ID`, `Pair`, `Status`) VALUES
	('B00001', 1, '上架', '正常'),
	('B00002', 2, '上架', '正常'),
	('B00003', 3, '上架', '正常'),
	('B00004', 4, '上架', '正常'),
	('B00005', 5, '下架', '廢棄'),
	('B00006', 6, '下架', '正常'),
	('B00007', 7, '下架', '正常'),
	('B00008', 8, '下架', '正常'),
	('B00009', 9, '下架', '正常'),
	('B00010', 10, '上架', '正常'),
	('B00011', 11, '上架', '正常'),
	('B00012', 12, '下架', '正常'),
	('B00013', 13, '上架', '正常'),
	('B00014', 21, '下架', '廢棄'),
	('B00015', 14, '下架', '正常'),
	('B00016', 15, '下架', '正常'),
	('B00017', 22, '下架', '廢棄'),
	('B00018', 16, '下架', '正常'),
	('B00019', 17, '下架', '正常'),
	('B00020', 18, '下架', '正常'),
	('B00021', 19, '下架', '正常'),
	('B00022', 20, '下架', '正常'),
	('B00023', 23, '下架', '廢棄'),
	('B00024', 24, '下架', '廢棄'),
	('B00025', 25, '下架', '正常'),
	('B00026', 26, '下架', '正常'),
	('B00027', 27, '下架', '正常'),
	('B00028', 28, '下架', '正常'),
	('B00029', 29, '下架', '廢棄'),
	('B00030', 30, '下架', '正常'),
	('B00031', 31, '下架', '正常'),
	('B00032', 32, '下架', '正常'),
	('B00033', 33, '下架', '正常'),
	('B00034', 34, '下架', '正常'),
	('B00035', 35, '下架', '正常'),
	('B00036', 36, '下架', '正常'),
	('B00037', 37, '下架', '正常'),
	('B00038', 38, '下架', '正常'),
	('B00039', 39, '下架', '正常'),
	('B00040', 40, '下架', '正常'),
	('B00041', 41, '下架', '正常'),
	('B00042', 42, '下架', '正常');
/*!40000 ALTER TABLE `box_data` ENABLE KEYS */;

-- 傾印  資料表 fire_management.box_hist 結構
CREATE TABLE IF NOT EXISTS `box_hist` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Num` char(6) NOT NULL,
  `Pair` enum('上架','下架') NOT NULL DEFAULT '下架',
  `Status` enum('正常','廢棄') NOT NULL DEFAULT '正常',
  `Fire_Past` char(6) DEFAULT NULL,
  `Time` date NOT NULL DEFAULT (curdate()),
  PRIMARY KEY (`ID`),
  KEY `Fire_past` (`Fire_Past`) USING BTREE,
  KEY `Box_number` (`Num`) USING BTREE,
  CONSTRAINT `FK_box_history_box_data` FOREIGN KEY (`Num`) REFERENCES `box_data` (`Num`) ON UPDATE CASCADE,
  CONSTRAINT `FK_box_history_fire_data` FOREIGN KEY (`Fire_Past`) REFERENCES `fire_data` (`Num`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

-- 正在傾印表格  fire_management.box_hist 的資料：~35 rows (近似值)
/*!40000 ALTER TABLE `box_hist` DISABLE KEYS */;
INSERT INTO `box_hist` (`ID`, `Num`, `Pair`, `Status`, `Fire_Past`, `Time`) VALUES
	(1, 'B00010', '上架', '正常', 'F00014', '2012-12-19'),
	(2, 'B00013', '上架', '正常', 'F00004', '2021-11-05'),
	(3, 'B00011', '上架', '正常', 'F00001', '2001-01-19'),
	(4, 'B00001', '上架', '正常', 'F00002', '2002-02-28'),
	(5, 'B00004', '上架', '正常', 'F00012', '2022-04-05'),
	(6, 'B00003', '上架', '正常', 'F00011', '2022-02-24'),
	(7, 'B00002', '上架', '正常', 'F00010', '2022-01-05'),
	(8, 'B00011', '下架', '正常', 'F00001', '2003-05-05'),
	(9, 'B00001', '下架', '正常', 'F00002', '2005-05-05'),
	(10, 'B00017', '上架', '正常', 'F00002', '2010-05-05'),
	(11, 'B00014', '上架', '正常', 'F00001', '2007-05-05'),
	(12, 'B00014', '下架', '正常', 'F00001', '2010-05-05'),
	(13, 'B00017', '下架', '正常', 'F00002', '2014-05-05'),
	(14, 'B00014', '上架', '正常', 'F00001', '2015-04-08'),
	(15, 'B00014', '下架', '正常', 'F00001', '2020-05-05'),
	(16, 'B00017', '下架', '廢棄', 'F00002', '2014-05-05'),
	(17, 'B00014', '下架', '廢棄', 'F00001', '2020-08-20'),
	(18, 'B00011', '上架', '正常', 'F00002', '2014-11-08'),
	(19, 'B00011', '下架', '正常', 'F00002', '2019-03-19'),
	(20, 'B00001', '上架', '正常', 'F00001', '2020-12-10'),
	(21, 'B00001', '下架', '正常', 'F00001', '2021-02-28'),
	(22, 'B00001', '上架', '正常', 'F00002', '2021-12-10'),
	(23, 'B00011', '上架', '正常', 'F00001', '2022-04-08'),
	(24, 'B00002', '下架', '正常', 'F00010', '2022-02-05'),
	(25, 'B00002', '上架', '正常', 'F00013', '2022-03-07'),
	(26, 'B00004', '下架', '正常', 'F00012', '2022-04-09'),
	(27, 'B00004', '上架', '正常', 'F00016', '2022-04-13'),
	(28, 'B00003', '下架', '正常', 'F00011', '2022-03-08'),
	(30, 'B00010', '下架', '正常', 'F00014', '2014-05-05'),
	(31, 'B00010', '上架', '正常', 'F00011', '2022-03-19'),
	(32, 'B00010', '下架', '正常', 'F00011', '2022-04-19'),
	(33, 'B00010', '上架', '正常', 'F00003', '2022-05-01'),
	(35, 'B00003', '上架', '正常', 'F00014', '2022-03-11'),
	(36, 'B00003', '下架', '正常', 'F00014', '2022-03-14'),
	(37, 'B00003', '上架', '正常', 'F00008', '2022-04-04');
/*!40000 ALTER TABLE `box_hist` ENABLE KEYS */;

-- 傾印  資料表 fire_management.building 結構
CREATE TABLE IF NOT EXISTS `building` (
  `Short` char(5) NOT NULL,
  `Building` char(15) DEFAULT NULL,
  `Floor` char(10) DEFAULT NULL,
  PRIMARY KEY (`Short`) USING BTREE,
  KEY `Building_name` (`Building`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在傾印表格  fire_management.building 的資料：~20 rows (近似值)
/*!40000 ALTER TABLE `building` DISABLE KEYS */;
INSERT INTO `building` (`Short`, `Building`, `Floor`) VALUES
	('A', '美術系館', '5樓'),
	('B', '商學大樓', '6樓,1樓'),
	('BY', '碧苑', '4樓'),
	('C', '中正大樓', '8樓'),
	('CC', '中央文創工廠', '2樓'),
	('D', '設計大樓', '10樓,2樓'),
	('E', '教育大樓', '3樓'),
	('F', '服設館', '6樓,1樓'),
	('H', '家政館', '4樓'),
	('HC', '漢家幼兒園', '4樓'),
	('HL', '紅樓', '5樓'),
	('L', '圖書資訊大樓', '10樓,1樓'),
	('LT', '生活科技大樓', '5樓'),
	('M', '音樂館', '4樓'),
	('MF', '多功能展演中心', '4樓,1樓'),
	('N', '乃建堂', '3樓'),
	('S', '學生活動中心', '5樓,1樓'),
	('SY', '馨園學生宿舍', '5樓'),
	('T', '教學大樓', '5樓'),
	('Y', '育樂館', '4樓');
/*!40000 ALTER TABLE `building` ENABLE KEYS */;

-- 傾印  資料表 fire_management.fire_data 結構
CREATE TABLE IF NOT EXISTS `fire_data` (
  `Num` char(6) NOT NULL,
  `Type` enum('乾粉','強化液','二氧化碳','泡沫','金屬火災') NOT NULL DEFAULT '乾粉',
  `Pair` enum('上架','下架') NOT NULL DEFAULT '下架',
  `Status` enum('正常','換藥中','已廢棄','需維修','維修中') NOT NULL DEFAULT '正常',
  `MFG` date NOT NULL DEFAULT (curdate()),
  `Supplier` char(50) NOT NULL,
  PRIMARY KEY (`Num`),
  KEY `Fire_supplier` (`Supplier`) USING BTREE,
  CONSTRAINT `FK_fire_data_supplier_data` FOREIGN KEY (`Supplier`) REFERENCES `supplier_data` (`Name`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在傾印表格  fire_management.fire_data 的資料：~17 rows (近似值)
/*!40000 ALTER TABLE `fire_data` DISABLE KEYS */;
INSERT INTO `fire_data` (`Num`, `Type`, `Pair`, `Status`, `MFG`, `Supplier`) VALUES
	('F00001', '乾粉', '上架', '正常', '2000-12-31', '巴泰'),
	('F00002', '泡沫', '上架', '正常', '2001-12-31', '十太'),
	('F00003', '強化液', '上架', '正常', '2002-12-31', '巴泰'),
	('F00004', '二氧化碳', '上架', '正常', '2003-12-31', '七太'),
	('F00005', '強化液', '下架', '已廢棄', '2021-12-13', '四太'),
	('F00006', '二氧化碳', '下架', '正常', '2021-12-13', '四太'),
	('F00007', '強化液', '下架', '正常', '2021-12-13', '七太'),
	('F00008', '乾粉', '上架', '需維修', '2001-12-10', '三太'),
	('F00009', '乾粉', '下架', '正常', '2020-02-19', '巴泰'),
	('F00010', '乾粉', '下架', '換藥中', '2019-02-19', '七太'),
	('F00011', '乾粉', '下架', '維修中', '2022-02-20', '四太'),
	('F00012', '乾粉', '下架', '換藥中', '2022-02-20', '七太'),
	('F00013', '乾粉', '上架', '需維修', '2022-02-20', '十太'),
	('F00014', '泡沫', '下架', '維修中', '2003-06-05', '巴泰'),
	('F00015', '乾粉', '下架', '正常', '2001-03-25', '巴泰'),
	('F00016', '泡沫', '上架', '需維修', '2000-12-12', '十太'),
	('F00017', '強化液', '下架', '正常', '2023-11-15', '三太');
/*!40000 ALTER TABLE `fire_data` ENABLE KEYS */;

-- 傾印  資料表 fire_management.fire_hist 結構
CREATE TABLE IF NOT EXISTS `fire_hist` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Num` char(6) NOT NULL,
  `Pair` enum('上架','下架') NOT NULL DEFAULT '下架',
  `Status` enum('正常','維修','換藥','廢棄') NOT NULL DEFAULT '正常',
  `Date` date NOT NULL DEFAULT (curdate()),
  PRIMARY KEY (`ID`) USING BTREE,
  KEY `Number` (`Num`) USING BTREE,
  CONSTRAINT `FK_fire_history_fire_data` FOREIGN KEY (`Num`) REFERENCES `fire_data` (`Num`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8;

-- 正在傾印表格  fire_management.fire_hist 的資料：~57 rows (近似值)
/*!40000 ALTER TABLE `fire_hist` DISABLE KEYS */;
INSERT INTO `fire_hist` (`ID`, `Num`, `Pair`, `Status`, `Date`) VALUES
	(1, 'F00001', '下架', '換藥', '2000-12-31'),
	(2, 'F00002', '下架', '換藥', '2001-12-31'),
	(3, 'F00003', '下架', '換藥', '2002-12-31'),
	(4, 'F00004', '下架', '換藥', '2003-12-13'),
	(5, 'F00005', '下架', '換藥', '2021-12-13'),
	(6, 'F00006', '下架', '換藥', '2021-12-13'),
	(7, 'F00007', '下架', '換藥', '2021-12-13'),
	(8, 'F00008', '下架', '換藥', '2001-12-10'),
	(9, 'F00009', '下架', '換藥', '2020-02-19'),
	(10, 'F00010', '下架', '換藥', '2019-02-19'),
	(11, 'F00011', '下架', '換藥', '2022-02-20'),
	(12, 'F00012', '下架', '換藥', '2022-02-20'),
	(13, 'F00013', '下架', '換藥', '2022-02-20'),
	(14, 'F00014', '下架', '換藥', '2003-06-05'),
	(15, 'F00015', '下架', '換藥', '2001-03-25'),
	(16, 'F00016', '下架', '換藥', '2000-12-12'),
	(17, 'F00001', '下架', '換藥', '2001-01-04'),
	(18, 'F00002', '下架', '換藥', '2002-02-09'),
	(19, 'F00005', '下架', '廢棄', '2022-03-05'),
	(20, 'F00006', '下架', '維修', '2022-01-05'),
	(21, 'F00007', '下架', '維修', '2022-02-05'),
	(22, 'F00009', '下架', '維修', '2021-05-04'),
	(23, 'F00015', '下架', '維修', '2014-05-05'),
	(24, 'F00001', '上架', '正常', '2001-01-19'),
	(25, 'F00002', '上架', '正常', '2002-02-28'),
	(26, 'F00003', '上架', '正常', '2022-05-01'),
	(27, 'F00004', '上架', '正常', '2021-11-05'),
	(28, 'F00008', '上架', '正常', '2022-04-04'),
	(29, 'F00013', '上架', '正常', '2022-03-07'),
	(30, 'F00016', '上架', '正常', '2022-04-13'),
	(31, 'F00001', '下架', '正常', '2003-05-05'),
	(32, 'F00002', '下架', '正常', '2005-05-05'),
	(33, 'F00001', '上架', '正常', '2007-05-05'),
	(34, 'F00002', '上架', '正常', '2010-05-05'),
	(35, 'F00001', '下架', '正常', '2010-05-05'),
	(36, 'F00002', '下架', '正常', '2014-05-05'),
	(37, 'F00001', '上架', '正常', '2015-04-08'),
	(38, 'F00002', '上架', '正常', '2014-11-08'),
	(39, 'F00001', '下架', '正常', '2020-05-05'),
	(40, 'F00001', '上架', '正常', '2020-12-10'),
	(41, 'F00001', '下架', '正常', '2021-02-28'),
	(42, 'F00001', '上架', '正常', '2022-04-08'),
	(43, 'F00002', '下架', '正常', '2019-03-19'),
	(44, 'F00002', '上架', '正常', '2021-12-10'),
	(46, 'F00010', '上架', '正常', '2021-05-05'),
	(47, 'F00010', '下架', '正常', '2022-02-05'),
	(48, 'F00012', '上架', '正常', '2022-04-05'),
	(49, 'F00012', '下架', '正常', '2022-04-09'),
	(50, 'F00011', '上架', '正常', '2022-02-24'),
	(51, 'F00011', '下架', '正常', '2022-03-08'),
	(52, 'F00014', '上架', '正常', '2012-12-19'),
	(53, 'F00014', '下架', '正常', '2014-05-05'),
	(54, 'F00011', '上架', '正常', '2022-03-19'),
	(55, 'F00011', '下架', '正常', '2022-04-19'),
	(56, 'F00014', '上架', '正常', '2022-03-11'),
	(57, 'F00014', '下架', '正常', '2022-03-14'),
	(58, 'F00017', '下架', '換藥', '2023-11-15');
/*!40000 ALTER TABLE `fire_hist` ENABLE KEYS */;

-- 傾印  資料表 fire_management.location 結構
CREATE TABLE IF NOT EXISTS `location` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Building` char(15) NOT NULL,
  `Floor` int(2) NOT NULL DEFAULT 0,
  `Location` char(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`) USING BTREE,
  KEY `Building_short` (`Building`) USING BTREE,
  CONSTRAINT `FK_building_place_building_data` FOREIGN KEY (`Building`) REFERENCES `building` (`Building`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

-- 正在傾印表格  fire_management.location 的資料：~25 rows (近似值)
/*!40000 ALTER TABLE `location` DISABLE KEYS */;
INSERT INTO `location` (`ID`, `Building`, `Floor`, `Location`) VALUES
	(1, '美術系館', 5, '辦公室'),
	(2, '商學大樓', 6, '樓梯'),
	(3, '碧苑', 4, '女廁'),
	(4, '中央文創工廠', 2, '男廁'),
	(5, '中正大樓', 2, '走廊'),
	(6, '設計大樓', 10, '走廊'),
	(7, '教育大樓', 3, 'E302教室'),
	(8, '服設館', 6, 'F601教室'),
	(9, '家政館', 4, '女廁'),
	(10, '紅樓', 5, '走廊'),
	(11, '圖書資訊大樓', 7, 'L701教室'),
	(12, '圖書資訊大樓', 7, 'L702教室'),
	(13, '生活科技大樓', 5, '男廁'),
	(14, '音樂館', 4, 'M401教室'),
	(15, '多功能展演中心', 1, '女廁'),
	(16, '乃建堂', 3, '女廁'),
	(17, '學生活動中心', 1, '男廁'),
	(18, '馨園學生宿舍', 5, '男廁'),
	(19, '教學大樓', 5, '女廁'),
	(20, '育樂館', 4, '男廁'),
	(21, '設計大樓', 1, '女廁'),
	(22, '中正大樓', 1, '女廁'),
	(23, '中正大樓', 1, '12456'),
	(24, '碧苑', 1, '4646'),
	(25, '美術系館', 1, '123'),
	(26, '商學大樓', -1, '123'),
	(27, '商學大樓', -1, '456'),
	(28, '商學大樓', -1, '156464'),
	(29, '碧苑', 1, '45645'),
	(30, '商學大樓', -1, '13113'),
	(31, '商學大樓', -1, '1223'),
	(32, '商學大樓', -1, '1313'),
	(33, '商學大樓', -1, '45646'),
	(34, '美術系館', 1, '45646'),
	(35, '商學大樓', -1, '546656'),
	(36, '商學大樓', -1, '4444444444'),
	(37, '美術系館', 1, '1131'),
	(38, '商學大樓', -1, '131313'),
	(39, '商學大樓', -1, '4566'),
	(40, '商學大樓', -1, '11313'),
	(41, '商學大樓', -1, '45664'),
	(42, '商學大樓', -1, '3135416');
/*!40000 ALTER TABLE `location` ENABLE KEYS */;

-- 傾印  資料表 fire_management.pair_data 結構
CREATE TABLE IF NOT EXISTS `pair_data` (
  `Fire_num` char(6) NOT NULL,
  `Box_num` char(6) NOT NULL,
  `Pair` enum('上架','下架') NOT NULL DEFAULT '上架',
  `Date` date DEFAULT (curdate()),
  PRIMARY KEY (`Fire_num`,`Box_num`) USING BTREE,
  KEY `FK_comprehensive_data_box_data` (`Box_num`) USING BTREE,
  CONSTRAINT `FK_comprehensive_data_box_data` FOREIGN KEY (`Box_num`) REFERENCES `box_data` (`Num`) ON UPDATE CASCADE,
  CONSTRAINT `FK_comprehensive_data_fire_data` FOREIGN KEY (`Fire_num`) REFERENCES `fire_data` (`Num`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在傾印表格  fire_management.pair_data 的資料：~7 rows (近似值)
/*!40000 ALTER TABLE `pair_data` DISABLE KEYS */;
INSERT INTO `pair_data` (`Fire_num`, `Box_num`, `Pair`, `Date`) VALUES
	('F00001', 'B00011', '上架', '2022-04-08'),
	('F00002', 'B00001', '上架', '2021-12-10'),
	('F00003', 'B00010', '上架', '2022-05-01'),
	('F00004', 'B00013', '上架', '2021-11-05'),
	('F00008', 'B00003', '上架', '2022-04-04'),
	('F00013', 'B00002', '上架', '2022-03-07'),
	('F00016', 'B00004', '上架', '2022-04-13');
/*!40000 ALTER TABLE `pair_data` ENABLE KEYS */;

-- 傾印  資料表 fire_management.powder_hist 結構
CREATE TABLE IF NOT EXISTS `powder_hist` (
  `ID` bigint(20) NOT NULL,
  `PowderEXP` date NOT NULL,
  `Supplier` char(50) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE,
  KEY `FK_med_change_supplier_data` (`Supplier`) USING BTREE,
  CONSTRAINT `FK_med_change_supplier_data` FOREIGN KEY (`Supplier`) REFERENCES `supplier_data` (`Name`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在傾印表格  fire_management.powder_hist 的資料：~19 rows (近似值)
/*!40000 ALTER TABLE `powder_hist` DISABLE KEYS */;
INSERT INTO `powder_hist` (`ID`, `PowderEXP`, `Supplier`) VALUES
	(1, '2024-03-16', '一太'),
	(2, '2024-04-10', '十太'),
	(3, '2023-05-05', '巴泰'),
	(4, '2023-05-06', '七太'),
	(5, '2024-03-19', '九太'),
	(6, '2023-05-09', '四太'),
	(7, '2024-05-09', '九太'),
	(8, '2024-05-16', '二太'),
	(9, '2026-05-08', '七太'),
	(10, '2025-05-26', '四太'),
	(11, '2025-06-26', '七太'),
	(12, '2024-08-09', '巴泰'),
	(13, '2024-06-04', '十太'),
	(14, '2026-05-11', '巴泰'),
	(15, '2025-05-08', '巴泰'),
	(16, '2024-05-07', '巴泰'),
	(17, '2023-05-08', '十太'),
	(18, '2022-05-09', '三太'),
	(58, '2023-11-17', '三太');
/*!40000 ALTER TABLE `powder_hist` ENABLE KEYS */;

-- 傾印  資料表 fire_management.repair_hist 結構
CREATE TABLE IF NOT EXISTS `repair_hist` (
  `ID` bigint(20) NOT NULL,
  `Pressure` enum('過低','正常','過高') NOT NULL DEFAULT '正常',
  `Apperance` enum('正常','生鏽','破損') NOT NULL DEFAULT '正常',
  `Item` enum('正常','插銷遺失','噴管遺失','噴嘴遺失','壓力表遺失','上下壓把遺失','其他') NOT NULL DEFAULT '正常',
  `Use` enum('正常','未通報火災遭使用','已通報火災遭使用') NOT NULL DEFAULT '正常',
  `Notes` char(200) DEFAULT NULL,
  PRIMARY KEY (`ID`) USING BTREE,
  KEY `Pressure` (`Pressure`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在傾印表格  fire_management.repair_hist 的資料：~4 rows (近似值)
/*!40000 ALTER TABLE `repair_hist` DISABLE KEYS */;
INSERT INTO `repair_hist` (`ID`, `Pressure`, `Apperance`, `Item`, `Use`, `Notes`) VALUES
	(20, '過高', '生鏽', '插銷遺失', '正常', '外觀損壞'),
	(21, '過低', '正常', '正常', '已通報火災遭使用', '藥粉用光了'),
	(22, '正常', '破損', '正常', '未通報火災遭使用', '外觀損壞'),
	(23, '正常', '正常', '噴管遺失', '未通報火災遭使用', '有人在玩滅火器');
/*!40000 ALTER TABLE `repair_hist` ENABLE KEYS */;

-- 傾印  資料表 fire_management.report 結構
CREATE TABLE IF NOT EXISTS `report` (
  `Num` char(6) NOT NULL DEFAULT '',
  `Pressure` enum('過低','正常','過高') NOT NULL DEFAULT '正常',
  `Apperance` enum('正常','生鏽','破損') NOT NULL DEFAULT '正常',
  `Item` enum('正常','插銷遺失','噴管遺失','噴嘴遺失','壓力表遺失','上下壓把遺失','其他') NOT NULL DEFAULT '正常',
  `Use` enum('正常','未通報火災遭使用','已通報火災遭使用') NOT NULL DEFAULT '正常',
  `Notes` char(200) DEFAULT NULL,
  `Time` date NOT NULL DEFAULT (curdate()),
  PRIMARY KEY (`Num`) USING BTREE,
  KEY `Pressure` (`Pressure`),
  CONSTRAINT `FK_checklist_fire_data` FOREIGN KEY (`Num`) REFERENCES `fire_data` (`Num`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在傾印表格  fire_management.report 的資料：~5 rows (近似值)
/*!40000 ALTER TABLE `report` DISABLE KEYS */;
INSERT INTO `report` (`Num`, `Pressure`, `Apperance`, `Item`, `Use`, `Notes`, `Time`) VALUES
	('F00008', '過低', '生鏽', '正常', '正常', '瓶身有塗鴉過的痕跡', '2022-04-07'),
	('F00011', '過高', '破損', '上下壓把遺失', '正常', '無', '2022-04-19'),
	('F00013', '過高', '生鏽', '壓力表遺失', '未通報火災遭使用', '有人在玩滅火器', '2022-04-08'),
	('F00014', '正常', '正常', '噴嘴遺失', '正常', '有人在玩滅火器有人在玩滅火器有人在玩滅火器有人在玩滅火器有人在玩滅火器有人在玩滅火器有人在玩滅火器有人在玩滅火器有人在玩滅火器有人在玩滅火器', '2021-04-01'),
	('F00016', '正常', '破損', '插銷遺失', '已通報火災遭使用', '藥粉用光了', '2022-04-10');
/*!40000 ALTER TABLE `report` ENABLE KEYS */;

-- 傾印  資料表 fire_management.supplier_data 結構
CREATE TABLE IF NOT EXISTS `supplier_data` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Type` enum('藥粉廠商','瓶子廠商') DEFAULT NULL,
  `Name` char(50) NOT NULL,
  `Phone` char(10) DEFAULT '',
  PRIMARY KEY (`ID`) USING BTREE,
  KEY `Supplier_name` (`Name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- 正在傾印表格  fire_management.supplier_data 的資料：~11 rows (近似值)
/*!40000 ALTER TABLE `supplier_data` DISABLE KEYS */;
INSERT INTO `supplier_data` (`ID`, `Type`, `Name`, `Phone`) VALUES
	(1, '藥粉廠商', '一太', '0111111111'),
	(2, '瓶子廠商', '七太', '0777777777'),
	(3, '瓶子廠商', '三太', '0333333333'),
	(4, '藥粉廠商', '九太', '0123456789'),
	(5, '藥粉廠商', '二太', '0222222222'),
	(6, '瓶子廠商', '五太', '0555555555'),
	(7, '瓶子廠商', '六太', '0666666666'),
	(8, '瓶子廠商', '十太', '0963636363'),
	(9, '瓶子廠商', '四太', '0444444444'),
	(10, '瓶子廠商', '巴泰', '0888888888'),
	(11, '瓶子廠商', '十二泰', '0933333363');
/*!40000 ALTER TABLE `supplier_data` ENABLE KEYS */;

-- 傾印  資料表 fire_management.target_data 結構
CREATE TABLE IF NOT EXISTS `target_data` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Data` char(50) NOT NULL,
  `Target` enum('換藥','維修','上架') NOT NULL,
  `Status` enum('T','F') NOT NULL DEFAULT 'F',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- 正在傾印表格  fire_management.target_data 的資料：~6 rows (近似值)
/*!40000 ALTER TABLE `target_data` DISABLE KEYS */;
INSERT INTO `target_data` (`ID`, `Data`, `Target`, `Status`) VALUES
	(1, 'F00008', '維修', 'T'),
	(2, 'F00006', '換藥', 'F'),
	(3, 'B00011', '上架', 'F'),
	(4, 'F00008', '維修', 'F'),
	(5, 'F00001', '換藥', 'F'),
	(6, 'F00013', '維修', 'F'),
	(7, 'F00003', '換藥', 'F'),
	(8, 'F00009', '換藥', 'F'),
	(9, 'F00015', '換藥', 'F'),
	(10, 'F00002', '換藥', 'F'),
	(11, 'F00007', '換藥', 'F'),
	(12, 'F00004', '換藥', 'F'),
	(13, 'F00017', '換藥', 'F');
/*!40000 ALTER TABLE `target_data` ENABLE KEYS */;

-- 傾印  觸發器 fire_management.tr_box_number 結構
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `tr_box_number` BEFORE INSERT ON `box_data` FOR EACH ROW BEGIN
DECLARE n int;
	SELECT COUNT(`Num`) INTO n
	FROM box_data;

	SET  NEW.`Num` = CONCAT('B',
	      LPAD ('1' + n, 5,0));
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- 傾印  觸發器 fire_management.tr_Fire_number 結構
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `tr_Fire_number` BEFORE INSERT ON `fire_data` FOR EACH ROW BEGIN
DECLARE n int;
	SELECT COUNT(`Num`) INTO n
	FROM fire_data;

	SET  NEW.`Num` = CONCAT('F',
	      LPAD ('1' + n, 5,0));
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
