-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 29, 2022 at 07:48 PM
-- Server version: 10.9.2-MariaDB
-- PHP Version: 8.1.6

--
-- CREATE USER 'bag33188'@'%' IDENTIFIED VIA mysql_native_password USING '***';GRANT ALL PRIVILEGES ON *.* TO 'bag33188'@'%' REQUIRE NONE WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;CREATE DATABASE IF NOT EXISTS `bag33188`;GRANT ALL PRIVILEGES ON `bag33188`.* TO 'bag33188'@'%';GRANT ALL PRIVILEGES ON `bag33188\_%`.* TO 'bag33188'@'%';
-- set autocommit = {0|1}
-- auto increment `roms` + `games` = 43,  auto increment `users` = 3, auto increment `personal_access_tokens` = 2, auto increment `migrations` = 10
-- ALTER TABLE table_name AUTO_INCREMENT = value;
--
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pokerom_db`
--
CREATE DATABASE IF NOT EXISTS `pokerom_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `pokerom_db`;

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `spSelectRomsWithNoGame`$$
CREATE DEFINER=`bag33188`@`%` PROCEDURE `spSelectRomsWithNoGame` ()  READS SQL DATA BEGIN
    SELECT
        `id`, `rom_name`, `rom_type`,
        `has_game`, `game_id`
    FROM `roms`
    WHERE `has_game` = FALSE OR `game_id` IS NULL
    ORDER BY CHAR_LENGTH(`rom_name`) DESC;
END$$

DROP PROCEDURE IF EXISTS `spUpdateRomFromRomFileData`$$
CREATE DEFINER=`bag33188`@`%` PROCEDURE `spUpdateRomFromRomFileData` (IN `ROM_FILE_ID` CHAR(24), IN `ROM_FILE_SIZE` BIGINT UNSIGNED, IN `ROM_ID` BIGINT UNSIGNED)   BEGIN
    DECLARE `base_bytes_unit` INTEGER(4) UNSIGNED DEFAULT POW(32, 2); -- 1024
    DECLARE `_rollback` BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET `_rollback` = TRUE;
    START TRANSACTION;
    UPDATE `roms`
    SET `file_id` = `ROM_FILE_ID`,
        `rom_size` = CEIL(`ROM_FILE_SIZE` / `base_bytes_unit`), -- get Kibibytes value from bytes
        `has_file` = TRUE
    WHERE `id` = `ROM_ID`;
    IF `_rollback` = TRUE THEN
        ROLLBACK;
    ELSE
        COMMIT;
    END IF;
/* !important
rom size is stored as Kibibytes (base 1024)
mongodb stored as bytes
*/
END$$

--
-- Functions
--
DROP FUNCTION IF EXISTS `FORMAT_GAME_TYPE`$$
CREATE DEFINER=`bag33188`@`%` FUNCTION `FORMAT_GAME_TYPE` (`GAME_TYPE` ENUM('core','hack','spin-off')) RETURNS VARCHAR(21) CHARSET utf8mb4 SQL SECURITY INVOKER BEGIN
    SET @`eacute` = CAST(CONVERT(x'E9' USING ucs2) AS char(1));
    CASE `GAME_TYPE`
        WHEN 'core' THEN RETURN CONCAT('Core Pok', @`eacute`, 'mon Game'); -- Core Pokemon Game
        WHEN 'hack' THEN RETURN CONCAT('Pok', @`eacute`, 'mon ROM Hack'); -- Pokemon ROM Hack
        WHEN 'spin-off' THEN RETURN CONCAT('Spin-Off Pok', @`eacute`, 'mon Game'); -- Spin-Off Pokemon Game
        ELSE RETURN 'N/A';
        END CASE;
/* !important
return value length = 21;
'Spin-Off Pokemon Game'.length = 21;
MAX_GAME_TYPE_LENGTH = 21;
*/
END$$

DROP FUNCTION IF EXISTS `FORMAT_ROM_SIZE`$$
CREATE DEFINER=`bag33188`@`%` FUNCTION `FORMAT_ROM_SIZE` (`ROM_SIZE` BIGINT UNSIGNED) RETURNS VARCHAR(9) CHARSET utf8mb4 SQL SECURITY INVOKER COMMENT 'conversion issues get fixed in this function' BEGIN
    -- size entity values
    DECLARE `size_num` FLOAT UNSIGNED;
    DECLARE `size_unit` CHAR(2);
    DECLARE `size_str` VARCHAR(6);
    -- size calculation values
    DECLARE `one_kibibyte` SMALLINT UNSIGNED DEFAULT 1024;
    DECLARE `one_kilobyte` SMALLINT UNSIGNED DEFAULT 1000;
    DECLARE `one_gigabyte` MEDIUMINT UNSIGNED DEFAULT POWER(`one_kilobyte`, 2);

    -- MEGABYTES
    IF `ROM_SIZE` > `one_kibibyte` AND `ROM_SIZE` < `one_gigabyte` THEN
        SET `size_unit` = 'MB';
        SET `size_num` = ROUND(`ROM_SIZE` / `one_kilobyte`, 2);
        -- GIGABYTES
    ELSEIF `ROM_SIZE` >= `one_gigabyte` THEN
        SET `size_unit` = 'GB';
        SET `size_num`= ROUND(`ROM_SIZE` / `one_gigabyte`, 2);
        -- KILOBYTES
    ELSEIF `ROM_SIZE` > 1020 AND `ROM_SIZE` <= `one_kibibyte` THEN
        SET `size_unit` = 'KB';
        SET `size_num` = CAST(`ROM_SIZE` AS FLOAT);
        -- BYTES
    ELSE
        SET `size_unit` = 'B ';
        SET `size_num` = CAST((`ROM_SIZE` * `one_kibibyte`) AS FLOAT);
    END IF;
    SET `size_str` = CONVERT(`size_num`, VARCHAR(6));
    RETURN CONCAT(`size_str`, ' ', `size_unit`);
/* !important
return value length = 9;
'262.14 MB'.length = 9;
MAX_ROM_SIZE_LENGTH = 9; // ex. '164.28 MB'
*/
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--
-- Creation: Aug 27, 2022 at 03:45 PM
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `failed_jobs`:
--

--
-- Truncate table before insert `failed_jobs`
--

TRUNCATE TABLE `failed_jobs`;
-- --------------------------------------------------------

--
-- Table structure for table `games`
--
-- Creation: Aug 27, 2022 at 03:59 PM
--

DROP TABLE IF EXISTS `games`;
CREATE TABLE `games` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rom_id` bigint(20) UNSIGNED NOT NULL,
  `game_name` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `game_type` enum('core','spin-off','hack') COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_released` date NOT NULL,
  `generation` tinyint(3) UNSIGNED NOT NULL,
  `region` enum('kanto','johto','hoenn','sinnoh','unova','kalos','alola','galar','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(42) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `games`:
--   `rom_id`
--       `roms` -> `id`
--

--
-- Truncate table before insert `games`
--

TRUNCATE TABLE `games`;
--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `rom_id`, `game_name`, `game_type`, `date_released`, `generation`, `region`, `slug`, `created_at`, `updated_at`) VALUES
(1, 1, 'Pokemon Red', 'core', '2022-01-01', 1, 'kanto', 'pokemon-red', '2022-07-04 10:31:25', '2022-07-24 19:28:27'),
(2, 2, 'Pokemon Blue', 'core', '1998-09-28', 1, 'kanto', 'pokemon-blue', '2022-07-04 10:31:32', '2022-07-04 10:31:32'),
(3, 3, 'Pokemon Green (JP)', 'core', '1996-02-27', 1, 'kanto', 'pokemon-green-jp', '2022-07-04 10:31:52', '2022-07-04 10:31:52'),
(4, 4, 'Pokemon Yellow', 'core', '1999-10-18', 1, 'kanto', 'pokemon-yellow', '2022-07-04 10:32:14', '2022-07-04 10:32:14'),
(5, 5, 'Pokemon Gold', 'core', '2000-10-15', 2, 'johto', 'pokemon-gold', '2022-07-04 10:32:40', '2022-07-04 10:32:40'),
(6, 6, 'Pokemon Silver', 'core', '2000-10-15', 2, 'johto', 'pokemon-silver', '2022-07-04 10:32:49', '2022-07-04 10:32:49'),
(7, 7, 'Pokemon Crystal', 'core', '2001-08-29', 2, 'johto', 'pokemon-crystal', '2022-07-04 10:33:03', '2022-07-04 10:33:03'),
(8, 8, 'Pokemon Ruby', 'core', '2003-03-19', 3, 'hoenn', 'pokemon-ruby', '2022-07-04 10:34:07', '2022-07-04 10:34:07'),
(9, 9, 'Pokemon Sapphire', 'core', '2003-03-19', 3, 'hoenn', 'pokemon-sapphire', '2022-07-04 10:34:23', '2022-07-04 10:34:23'),
(10, 10, 'Pokemon Emerald', 'core', '2005-05-01', 3, 'hoenn', 'pokemon-emerald', '2022-07-04 10:34:51', '2022-07-04 10:34:51'),
(11, 11, 'Pokemon FireRed', 'core', '2004-09-09', 3, 'kanto', 'pokemon-firered', '2022-07-04 10:35:20', '2022-07-04 10:35:20'),
(12, 12, 'Pokemon LeafGreen', 'core', '2004-09-09', 3, 'kanto', 'pokemon-leafgreen', '2022-07-04 10:35:32', '2022-07-04 10:35:32'),
(13, 13, 'Pokemon Diamond', 'core', '2007-04-22', 4, 'sinnoh', 'pokemon-diamond', '2022-07-04 10:36:11', '2022-07-04 10:36:11'),
(14, 14, 'Pokemon Pearl', 'core', '2007-04-22', 4, 'sinnoh', 'pokemon-pearl', '2022-07-04 10:36:17', '2022-07-04 10:36:17'),
(15, 15, 'Pokemon Platinum', 'core', '2009-03-22', 4, 'sinnoh', 'pokemon-platinum', '2022-07-04 10:36:44', '2022-07-04 10:36:44'),
(16, 16, 'Pokemon HeartGold', 'core', '2010-03-14', 4, 'johto', 'pokemon-heartgold', '2022-07-04 10:37:11', '2022-07-04 10:37:11'),
(17, 17, 'Pokemon SoulSilver', 'core', '2010-03-14', 4, 'johto', 'pokemon-soulsilver', '2022-07-04 10:37:19', '2022-07-04 10:37:19'),
(18, 18, 'Pokemon Black', 'core', '2011-03-06', 5, 'unova', 'pokemon-black', '2022-07-04 10:37:53', '2022-07-04 10:37:53'),
(19, 19, 'Pokemon White', 'core', '2011-03-06', 5, 'unova', 'pokemon-white', '2022-07-04 10:38:01', '2022-07-04 10:38:01'),
(20, 20, 'Pokemon Black 2', 'core', '2012-10-07', 5, 'unova', 'pokemon-black-2', '2022-07-04 10:38:27', '2022-07-04 10:38:27'),
(21, 21, 'Pokemon White 2', 'core', '2012-10-07', 5, 'unova', 'pokemon-white-2', '2022-07-04 10:38:36', '2022-07-04 10:38:36'),
(22, 22, 'Pokemon X', 'core', '2013-10-12', 6, 'kalos', 'pokemon-x', '2022-07-04 10:39:02', '2022-07-04 10:39:02'),
(23, 23, 'Pokemon Y', 'core', '2013-10-12', 6, 'kalos', 'pokemon-y', '2022-07-04 10:39:08', '2022-07-04 10:39:08'),
(24, 24, 'Pokemon Omega Ruby', 'core', '2014-11-21', 6, 'hoenn', 'pokemon-omega-ruby', '2022-07-04 10:39:36', '2022-07-04 10:39:36'),
(25, 25, 'Pokemon Alpha Sapphire', 'core', '2014-11-21', 6, 'hoenn', 'pokemon-alpha-sapphire', '2022-07-04 10:40:04', '2022-07-04 10:40:04'),
(26, 26, 'Pokemon Sun', 'core', '2016-11-18', 7, 'alola', 'pokemon-sun', '2022-07-04 10:40:40', '2022-07-04 10:40:40'),
(27, 27, 'Pokemon Moon', 'core', '2016-11-18', 7, 'alola', 'pokemon-moon', '2022-07-04 10:40:51', '2022-07-04 10:40:51'),
(28, 28, 'Pokemon Ultra Sun', 'core', '2017-11-17', 7, 'alola', 'pokemon-ultra-sun', '2022-07-04 10:41:14', '2022-07-04 10:41:14'),
(29, 29, 'Pokemon Ultra Moon', 'core', '2017-11-17', 7, 'alola', 'pokemon-ultra-moon', '2022-07-04 10:41:20', '2022-07-04 10:41:20'),
(30, 30, 'Pokemon Sword', 'core', '2019-11-15', 8, 'galar', 'pokemon-sword', '2022-07-04 10:42:01', '2022-07-04 10:42:01'),
(31, 31, 'Pokemon Shield', 'core', '2019-11-15', 8, 'galar', 'pokemon-shield', '2022-07-04 10:42:13', '2022-07-04 10:42:13'),
(32, 32, 'Pokemon Brilliant Diamond', 'core', '2021-11-19', 8, 'sinnoh', 'pokemon-brilliant-diamond', '2022-07-04 10:42:46', '2022-07-04 10:42:46'),
(33, 33, 'Pokemon Shining Pearl', 'core', '2021-11-19', 8, 'sinnoh', 'pokemon-shining-pearl', '2022-07-04 10:42:58', '2022-07-04 10:42:58'),
(34, 34, 'Pokemon Let\'s Go Pikachu', 'spin-off', '2018-11-16', 7, 'kanto', 'pokemon-lets-go-pikachu', '2022-07-04 10:43:32', '2022-07-04 10:43:32'),
(35, 35, 'Pokemon Let\'s Go Eevee', 'spin-off', '2018-11-16', 7, 'kanto', 'pokemon-lets-go-eevee', '2022-07-04 10:43:38', '2022-07-04 10:43:38'),
(36, 36, 'Pokemon Prism', 'hack', '2016-12-25', 0, 'other', 'pokemon-prism', '2022-07-04 10:44:25', '2022-07-04 10:44:25'),
(37, 37, 'Pokemon Brown', 'hack', '2012-06-15', 0, 'other', 'pokemon-brown', '2022-07-04 10:44:50', '2022-07-04 10:44:50'),
(38, 38, 'Pokemon Genesis', 'hack', '2019-08-23', 0, 'other', 'pokemon-genesis', '2022-07-04 10:47:31', '2022-07-04 10:47:31'),
(39, 39, 'Pokemon Ash Gray', 'hack', '2009-05-31', 1, 'kanto', 'pokemon-ash-gray', '2022-07-04 10:48:07', '2022-07-04 10:48:07'),
(40, 40, 'Pokemon Renegade Platinum', 'hack', '2019-04-16', 4, 'sinnoh', 'pokemon-renegade-platinum', '2022-07-04 10:48:37', '2022-07-04 10:48:37'),
(41, 41, 'Pokemon Legends: Arceus', 'spin-off', '2022-12-08', 8, 'sinnoh', 'pokemon-legends-arceus', '2022-07-20 19:50:03', '2022-07-20 19:50:03'),
(42, 42, 'Pokemon Super Mystery Dungeon', 'spin-off', '2015-09-17', 6, 'other', 'pokemon-super-mystery-dungeon', '2022-08-26 09:33:25', '2022-08-26 09:33:25');

--
-- Triggers `games`
--
DROP TRIGGER IF EXISTS `games_after_delete`;
DELIMITER $$
CREATE TRIGGER `games_after_delete` AFTER DELETE ON `games` FOR EACH ROW BEGIN
  UPDATE `roms`
  SET `roms`.`has_game` = FALSE, `roms`.`game_id` = NULL
  WHERE `roms`.`id` = OLD.`rom_id`;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `games_after_insert`;
DELIMITER $$
CREATE TRIGGER `games_after_insert` AFTER INSERT ON `games` FOR EACH ROW BEGIN
  DECLARE `rom_already_has_game` BOOL;
  SELECT `has_game`
  INTO `rom_already_has_game`
  FROM `roms`
  WHERE `roms`.`id` = NEW.`rom_id`;
  IF `rom_already_has_game` = FALSE
  THEN
    UPDATE `roms`
    SET `roms`.`has_game` = TRUE, `roms`.`game_id` = NEW.`id`
    WHERE `roms`.`id` = NEW.`rom_id`;
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--
-- Creation: Aug 27, 2022 at 03:45 PM
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `migrations`:
--

--
-- Truncate table before insert `migrations`
--

TRUNCATE TABLE `migrations`;
--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2014_10_12_200000_add_two_factor_columns_to_users_table', 1),
(4, '2019_08_19_000000_create_failed_jobs_table', 1),
(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(6, '2022_08_27_154509_create_sessions_table', 1),
(7, '2022_08_27_154850_create_roms_table', 1),
(8, '2022_08_27_155425_create_games_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--
-- Creation: Aug 27, 2022 at 03:45 PM
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `password_resets`:
--

--
-- Truncate table before insert `password_resets`
--

TRUNCATE TABLE `password_resets`;
-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--
-- Creation: Aug 27, 2022 at 03:45 PM
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `personal_access_tokens`:
--

--
-- Truncate table before insert `personal_access_tokens`
--

TRUNCATE TABLE `personal_access_tokens`;
--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(4, 'App\\Models\\User', 1, 'auth_token', '07702c824a257a49b4e559f194734bb698e18d9c3cb2c556abdeae4cfaba3a9e', '[\"*\"]', '2022-08-29 01:51:17', NULL, '2022-08-29 01:49:41', '2022-08-29 01:51:17');

-- --------------------------------------------------------

--
-- Table structure for table `roms`
--
-- Creation: Aug 27, 2022 at 05:10 PM
--

DROP TABLE IF EXISTS `roms`;
CREATE TABLE `roms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rom_name` varchar(28) COLLATE utf8mb4_unicode_ci NOT NULL,
  `game_id` bigint(20) UNSIGNED DEFAULT NULL,
  `file_id` char(24) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rom_size` int(10) UNSIGNED NOT NULL DEFAULT 1020,
  `rom_type` enum('gb','gbc','gba','nds','3ds','xci') COLLATE utf8mb4_unicode_ci NOT NULL,
  `has_game` tinyint(1) NOT NULL DEFAULT 0,
  `has_file` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `roms`:
--

--
-- Truncate table before insert `roms`
--

TRUNCATE TABLE `roms`;
--
-- Dumping data for table `roms`
--

INSERT INTO `roms` (`id`, `rom_name`, `game_id`, `file_id`, `rom_size`, `rom_type`, `has_game`, `has_file`, `created_at`, `updated_at`) VALUES
(1, 'POKEMON_RED01', 1, '6305c27b4bc3e0c5dc0c46ef', 1024, 'gb', 1, 1, '2022-07-04 10:15:17', '2022-08-24 20:17:31'),
(2, 'POKEMON_BLUE01', 2, '6305c2924bc3e0c5dc0c46f3', 1024, 'gb', 1, 1, '2022-07-04 10:15:28', '2022-08-24 20:17:54'),
(3, 'POKEMON_GREEN01', 3, '6305c29a4bc3e0c5dc0c46f7', 1024, 'gb', 1, 1, '2022-07-04 10:15:39', '2022-08-24 20:18:02'),
(4, 'POKEMON_YELLOW01', 4, '6305c2a04bc3e0c5dc0c46fa', 1024, 'gb', 1, 1, '2022-07-04 10:15:44', '2022-08-24 20:18:08'),
(5, 'POKEMON_GLDAAUE01', 5, '6305c2ab4bc3e0c5dc0c46fd', 2048, 'gbc', 1, 1, '2022-07-04 10:15:56', '2022-08-24 20:18:19'),
(6, 'POKEMON_SLVAAXE01', 6, '6305c2b04bc3e0c5dc0c4701', 2048, 'gbc', 1, 1, '2022-07-04 10:16:03', '2022-08-24 20:18:24'),
(7, 'PM_CRYSTAL_BYTE01', 7, '6305c2c94bc3e0c5dc0c4705', 2048, 'gbc', 1, 1, '2022-07-04 10:16:10', '2022-08-24 20:18:49'),
(8, 'POKEMON_RUBYAXVE01', 8, '6305c2cf4bc3e0c5dc0c4709', 16384, 'gba', 1, 1, '2022-07-04 10:16:22', '2022-08-24 20:18:55'),
(9, 'POKEMON_SAPPAXPE01', 9, '6305c2e14bc3e0c5dc0c471b', 16384, 'gba', 1, 1, '2022-07-04 10:16:27', '2022-08-24 20:19:13'),
(10, 'POKEMON_EMERBPEE01', 10, '6305c2e64bc3e0c5dc0c472d', 16384, 'gba', 1, 1, '2022-07-04 10:16:32', '2022-08-24 20:19:18'),
(11, 'POKEMON_FIREBPRE01', 11, '6305c2ec4bc3e0c5dc0c473f', 16384, 'gba', 1, 1, '2022-07-04 10:16:38', '2022-08-24 20:19:24'),
(12, 'POKEMON_LEAFBPGE01', 12, '6305c2f14bc3e0c5dc0c4751', 16384, 'gba', 1, 1, '2022-07-04 10:16:44', '2022-08-24 20:19:29'),
(13, 'POKEMON_D_ADAE01', 13, '6305c2f94bc3e0c5dc0c4763', 65536, 'nds', 1, 1, '2022-07-04 10:16:55', '2022-08-24 20:19:38'),
(14, 'POKEMON_P_APAE', 14, '6305c3024bc3e0c5dc0c47a5', 65536, 'nds', 1, 1, '2022-07-04 10:17:01', '2022-08-24 20:19:47'),
(15, 'POKEMON_PL_CPUE01', 15, '6305c3204bc3e0c5dc0c47e7', 131072, 'nds', 1, 1, '2022-07-04 10:17:09', '2022-08-24 20:20:18'),
(16, 'POKEMON_HG_IPKE01', 16, '6305c32a4bc3e0c5dc0c4869', 131072, 'nds', 1, 1, '2022-07-04 10:17:15', '2022-08-24 20:20:27'),
(17, 'POKEMON_SS_IPGE01', 17, '6305c3374bc3e0c5dc0c48eb', 131072, 'nds', 1, 1, '2022-07-04 10:17:19', '2022-08-24 20:20:44'),
(18, 'POKEMON_B_IRBO01', 18, '6305c3414bc3e0c5dc0c496d', 262144, 'nds', 1, 1, '2022-07-04 10:17:24', '2022-08-24 20:20:55'),
(19, 'POKEMON_W_IRAO01', 19, '6305c34d4bc3e0c5dc0c4a70', 262144, 'nds', 1, 1, '2022-07-04 10:17:28', '2022-08-24 20:21:03'),
(20, 'POKEMON_B2_IREO01', 20, '6305c3544bc3e0c5dc0c4b73', 524288, 'nds', 1, 1, '2022-07-04 10:17:34', '2022-08-24 20:21:12'),
(21, 'POKEMON_W2_IRDO01', 21, '6305c35f4bc3e0c5dc0c4d77', 524288, 'nds', 1, 1, '2022-07-04 10:17:38', '2022-08-24 20:21:23'),
(22, '0004000000055D00_v00', 22, '6305c36e4bc3e0c5dc0c4f7b', 2097152, '3ds', 1, 1, '2022-07-04 10:18:06', '2022-08-24 20:21:48'),
(23, '0004000000055E00_v00', 23, '6305c3974bc3e0c5dc0c5785', 2097152, '3ds', 1, 1, '2022-07-04 10:18:15', '2022-08-24 20:22:28'),
(24, '000400000011C400_v00', 24, '6305c3b34bc3e0c5dc0c5f8f', 2097152, '3ds', 1, 1, '2022-07-04 10:18:24', '2022-08-24 20:22:58'),
(25, '000400000011C500_v00', 25, '6305c3cc4bc3e0c5dc0c6799', 2097152, '3ds', 1, 1, '2022-07-04 10:18:29', '2022-08-24 20:23:22'),
(26, '0004000000164800_v00', 26, '6305c3e54bc3e0c5dc0c6fa3', 4194304, '3ds', 1, 1, '2022-07-04 10:18:40', '2022-08-24 20:24:00'),
(27, '0004000000175E00_v00', 27, '6305c4124bc3e0c5dc0c7fb5', 4194304, '3ds', 1, 1, '2022-07-04 10:18:48', '2022-08-24 20:25:25'),
(28, '00040000001B5000_v00', 28, '6305c45f4bc3e0c5dc0c8fc7', 4194304, '3ds', 1, 1, '2022-07-04 10:18:54', '2022-08-24 20:26:56'),
(29, '00040000001B5100_v00', 29, '6305c4c24bc3e0c5dc0c9fd9', 4194304, '3ds', 1, 1, '2022-07-04 10:18:59', '2022-08-24 20:28:09'),
(30, '0100ABF008968000', 30, '6305c5344bc3e0c5dc0cafec', 15597568, 'xci', 1, 1, '2022-07-04 10:19:32', '2022-08-24 20:32:32'),
(31, '01008DB008C2C000', 31, '6305c6184bc3e0c5dc0cebac', 15597568, 'xci', 1, 1, '2022-07-04 10:19:38', '2022-08-24 20:36:02'),
(32, '0100000011D90000', 32, '6305c6fa4bc3e0c5dc0d2769', 4880601, 'xci', 1, 1, '2022-07-04 10:19:59', '2022-08-24 20:38:11'),
(33, '010018E011D92000', 33, '6305c7694bc3e0c5dc0d3a1b', 7798784, 'xci', 1, 1, '2022-07-04 10:20:06', '2022-08-24 20:41:05'),
(34, '010003F003A34000', 34, '6305c81e4bc3e0c5dc0d57fa', 4737727, 'xci', 1, 1, '2022-07-04 10:20:28', '2022-08-24 20:43:28'),
(35, '0100187003A36000', 35, '6305c8a14bc3e0c5dc0d6a20', 4363761, 'xci', 1, 1, '2022-07-04 10:20:38', '2022-08-24 20:45:48'),
(36, 'pokeprism', 36, '6305c95b4bc3e0c5dc0d7ad8', 2048, 'gbc', 1, 1, '2022-07-04 10:21:13', '2022-08-24 20:46:51'),
(37, 'pokemon_brown_2014-red_hack', 37, '6305c9634bc3e0c5dc0d7adc', 2048, 'gb', 1, 1, '2022-07-04 10:21:42', '2022-08-24 20:47:00'),
(38, 'genesis-final-2019-08-23', 38, '6305c9684bc3e0c5dc0d7ae0', 16384, 'gba', 1, 1, '2022-07-04 10:21:58', '2022-08-24 20:47:04'),
(39, 'Pokemon_Ash_Gray_4-5-3', 39, '6305c96e4bc3e0c5dc0d7af2', 16384, 'gba', 1, 1, '2022-07-04 10:22:16', '2022-08-24 20:47:10'),
(40, 'RenegadePlatinum', 40, '6305c9744bc3e0c5dc0d7b04', 102464, 'nds', 1, 1, '2022-07-04 10:22:28', '2022-08-24 20:47:17'),
(41, '01001F5010DFA000', 41, '6305c9a94bc3e0c5dc0d7b6a', 7798784, 'xci', 1, 1, '2022-07-20 19:48:00', '2022-08-24 20:51:23'),
(42, '0004000000174600', 42, '6307ce21b12790f2b2083cb8', 2097152, '3ds', 1, 1, '2022-08-26 09:32:25', '2022-08-26 09:32:25');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--
-- Creation: Aug 27, 2022 at 03:45 PM
-- Last update: Aug 29, 2022 at 05:29 PM
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `sessions`:
--

--
-- Truncate table before insert `sessions`
--

TRUNCATE TABLE `sessions`;
--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('8ccW72yR0fv4yM5XGtJYfP0LOGnTZtkohplq6oIp', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiSnp1eFdLREZ0UkJyQ0xFUUJYbmZSSWxBWVE5ZEVMOVhBWVBuOFliQiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI0OiJodHRwOi8vcG9rZXJvbS50ZXN0L3JvbXMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MjE6InBhc3N3b3JkX2hhc2hfc2FuY3R1bSI7czo2MDoiJDJ5JDEwJGNSOVRFMHZsV01NSFIzQ0trT3hxYk90TC45a0ZhVXZIemNsYWlOYjQ5dmQxajhVOU5URW5TIjt9', 1661794154),
('b66REVnLG3clwNKyFhHAeo8cGTJJh3BdDTaOl4DV', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiWDdRSDRmVFc5cUFMdDBPTXZFY1BFQ2ZacGdPMmM1RXZkM2tKeUE1dCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjQ6Imh0dHA6Ly9wb2tlcm9tLnRlc3Qvcm9tcyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoyMToicGFzc3dvcmRfaGFzaF9zYW5jdHVtIjtzOjYwOiIkMnkkMTAkY1I5VEUwdmxXTU1IUjNDS2tPeHFiT3RMLjlrRmFVdkh6Y2xhaU5iNDl2ZDFqOFU5TlRFblMiO30=', 1661737605),
('yQe0lsANVebbpQKkFDpuau07sFfcY2lIC6sFlo58', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiWnJaSjF2WlFxRHExNGREZXA1dTlhYTQyTnBTa293ZkpzVDBnWU91YyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI0OiJodHRwOi8vcG9rZXJvbS50ZXN0L3JvbXMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MjE6InBhc3N3b3JkX2hhc2hfc2FuY3R1bSI7czo2MDoiJDJ5JDEwJGNSOVRFMHZsV01NSFIzQ0trT3hxYk90TC45a0ZhVXZIemNsYWlOYjQ5dmQxajhVOU5URW5TIjt9', 1661774646);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--
-- Creation: Aug 27, 2022 at 03:45 PM
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `role` enum('admin','user','guest') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_team_id` bigint(20) UNSIGNED DEFAULT NULL,
  `profile_photo_path` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `users`:
--

--
-- Truncate table before insert `users`
--

TRUNCATE TABLE `users`;
--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `role`, `remember_token`, `current_team_id`, `profile_photo_path`, `created_at`, `updated_at`) VALUES
(1, 'Brock Glatman', 'bglatman@outlook.com', NULL, '$2y$10$cR9TE0vlWMMHR3CKkOxqbOtL.9kFaUvHzclaiNb49vd1j8U9NTEnS', NULL, NULL, NULL, 'admin', NULL, NULL, NULL, '2022-08-27 23:17:24', '2022-08-27 23:17:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `games_rom_id_unique` (`rom_id`),
  ADD UNIQUE KEY `games_slug_unique` (`slug`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `roms`
--
ALTER TABLE `roms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roms_rom_name_unique` (`rom_name`),
  ADD UNIQUE KEY `roms_game_id_unique` (`game_id`),
  ADD UNIQUE KEY `roms_file_id_unique` (`file_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roms`
--
ALTER TABLE `roms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `games_rom_id_foreign` FOREIGN KEY (`rom_id`) REFERENCES `roms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;


--
-- Metadata
--
USE `phpmyadmin`;

--
-- Metadata for table failed_jobs
--

--
-- Truncate table before insert `pma__column_info`
--

TRUNCATE TABLE `pma__column_info`;
--
-- Truncate table before insert `pma__table_uiprefs`
--

TRUNCATE TABLE `pma__table_uiprefs`;
--
-- Truncate table before insert `pma__tracking`
--

TRUNCATE TABLE `pma__tracking`;
--
-- Metadata for table games
--

--
-- Truncate table before insert `pma__column_info`
--

TRUNCATE TABLE `pma__column_info`;
--
-- Truncate table before insert `pma__table_uiprefs`
--

TRUNCATE TABLE `pma__table_uiprefs`;
--
-- Truncate table before insert `pma__tracking`
--

TRUNCATE TABLE `pma__tracking`;
--
-- Metadata for table migrations
--

--
-- Truncate table before insert `pma__column_info`
--

TRUNCATE TABLE `pma__column_info`;
--
-- Truncate table before insert `pma__table_uiprefs`
--

TRUNCATE TABLE `pma__table_uiprefs`;
--
-- Truncate table before insert `pma__tracking`
--

TRUNCATE TABLE `pma__tracking`;
--
-- Metadata for table password_resets
--

--
-- Truncate table before insert `pma__column_info`
--

TRUNCATE TABLE `pma__column_info`;
--
-- Truncate table before insert `pma__table_uiprefs`
--

TRUNCATE TABLE `pma__table_uiprefs`;
--
-- Truncate table before insert `pma__tracking`
--

TRUNCATE TABLE `pma__tracking`;
--
-- Metadata for table personal_access_tokens
--

--
-- Truncate table before insert `pma__column_info`
--

TRUNCATE TABLE `pma__column_info`;
--
-- Truncate table before insert `pma__table_uiprefs`
--

TRUNCATE TABLE `pma__table_uiprefs`;
--
-- Truncate table before insert `pma__tracking`
--

TRUNCATE TABLE `pma__tracking`;
--
-- Metadata for table roms
--

--
-- Truncate table before insert `pma__column_info`
--

TRUNCATE TABLE `pma__column_info`;
--
-- Truncate table before insert `pma__table_uiprefs`
--

TRUNCATE TABLE `pma__table_uiprefs`;
--
-- Truncate table before insert `pma__tracking`
--

TRUNCATE TABLE `pma__tracking`;
--
-- Metadata for table sessions
--

--
-- Truncate table before insert `pma__column_info`
--

TRUNCATE TABLE `pma__column_info`;
--
-- Truncate table before insert `pma__table_uiprefs`
--

TRUNCATE TABLE `pma__table_uiprefs`;
--
-- Truncate table before insert `pma__tracking`
--

TRUNCATE TABLE `pma__tracking`;
--
-- Metadata for table users
--

--
-- Truncate table before insert `pma__column_info`
--

TRUNCATE TABLE `pma__column_info`;
--
-- Truncate table before insert `pma__table_uiprefs`
--

TRUNCATE TABLE `pma__table_uiprefs`;
--
-- Truncate table before insert `pma__tracking`
--

TRUNCATE TABLE `pma__tracking`;
--
-- Metadata for database pokerom_db
--

--
-- Truncate table before insert `pma__bookmark`
--

TRUNCATE TABLE `pma__bookmark`;
--
-- Truncate table before insert `pma__relation`
--

TRUNCATE TABLE `pma__relation`;
--
-- Truncate table before insert `pma__savedsearches`
--

TRUNCATE TABLE `pma__savedsearches`;
--
-- Truncate table before insert `pma__central_columns`
--

TRUNCATE TABLE `pma__central_columns`;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
