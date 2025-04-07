-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 07, 2025 at 06:26 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gsa_store_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `issued_stock`
--

CREATE TABLE `issued_stock` (
  `issue_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `issued_quantity` int(11) NOT NULL CHECK (`issued_quantity` > 0),
  `price` decimal(10,2) NOT NULL,
  `date_issued` date DEFAULT current_timestamp(),
  `transferred_from` varchar(255) NOT NULL,
  `transferred_to` varchar(255) NOT NULL,
  `store_issue_voucher` varchar(10) DEFAULT NULL,
  `issued_by` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `remarks` text DEFAULT NULL,
  `request_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `issued_stock`
--

INSERT INTO `issued_stock` (`issue_id`, `item_id`, `issued_quantity`, `price`, `date_issued`, `transferred_from`, `transferred_to`, `store_issue_voucher`, `issued_by`, `description`, `status`, `remarks`, `request_id`) VALUES
(1, 13, 1, 30.00, '2021-02-23', 'GSA Stores', 'SC\'s Office', 'SIV-000001', 1, 'Allocated to SC', 'Pending', NULL, NULL),
(2, 13, 1, 30.00, '2021-04-13', 'GSA Stores', 'Accounts', 'SIV-000002', 1, 'Allocated to Accounting Assistant', 'Pending', NULL, NULL),
(3, 13, 1, 30.00, '2024-04-10', 'GSA Stores', 'Administration', 'SIV-000003', 1, 'Allocated to Admin Officer', 'Pending', NULL, NULL),
(4, 9, 1, 40.00, '2021-10-01', 'GSA Stores', 'Administration', 'SIV-000004', 1, 'Admin/Accounts Office Use', 'Pending', NULL, NULL),
(5, 9, 1, 40.00, '2022-04-06', 'GSA Stores', 'Administration', 'SIV-000005', 1, 'Admin/Accounts Office Use', 'Pending', NULL, NULL),
(6, 9, 2, 40.00, '2022-06-07', 'GSA Stores', 'Administration', 'SIV-000006', 1, 'Admin/Accounts Office Use', 'Pending', NULL, NULL),
(7, 9, 1, 40.00, '2022-10-03', 'GSA Stores', 'Administration', 'SIV-000007', 1, 'Admin/Accounts Office Use', 'Pending', NULL, NULL),
(8, 9, 1, 40.00, '2023-03-22', 'GSA Stores', 'Administration', 'SIV-000008', 1, 'Admin/Accounts Office Use', 'Pending', NULL, NULL),
(9, 9, 1, 40.00, '2023-06-06', 'GSA Stores', 'Administration', 'SIV-000009', 1, 'Admin/Accounts Office Use', 'Pending', NULL, NULL),
(10, 9, 1, 50.00, '2023-09-04', 'GSA Stores', 'Administration', 'SIV-000010', 1, 'Admin/Accounts Office Use', 'Pending', NULL, NULL),
(11, 9, 1, 50.00, '2023-10-03', 'GSA Stores', 'Administration', 'SIV-000011', 1, 'Admin/Accounts Office Use', 'Pending', NULL, NULL),
(12, 9, 1, 50.00, '2024-04-10', 'GSA Stores', 'Administration', 'SIV-000012', 1, 'Admin/Accounts Office Use', 'Pending', NULL, NULL),
(13, 9, 1, 50.00, '2024-05-10', 'GSA Stores', 'Administration', 'SIV-000013', 1, 'Admin/Accounts Office Use', 'Pending', NULL, NULL),
(14, 9, 1, 50.00, '2024-05-27', 'GSA Stores', 'Administration', 'SIV-000014', 1, 'Admin/Accounts Office Use', 'Pending', NULL, NULL),
(15, 9, 1, 90.00, '2024-09-20', 'GSA Stores', 'Administration', 'SIV-000015', 1, 'Admin/Accounts Office Use', 'Pending', NULL, NULL),
(16, 9, 1, 90.00, '2024-10-07', 'GSA Stores', 'Administration', 'SIV-000016', 1, 'Admin/Accounts Office Use', 'Pending', NULL, NULL),
(17, 4, 1, 60.00, '2019-11-07', 'GSA Stores', 'SC\'s Office', 'SIV-000017', 1, 'Allocated to SC\'s Office', 'Pending', NULL, NULL),
(18, 4, 1, 60.00, '2019-11-07', 'GSA Stores', 'Administration', 'SIV-000018', 1, 'Adminstrative Use', 'Pending', NULL, NULL),
(19, 4, 1, 60.00, '2019-11-07', 'GSA Stores', 'Accounts', 'SIV-000019', 1, 'Accounts Office use', 'Pending', NULL, NULL),
(20, 4, 1, 80.00, '2020-08-06', 'GSA Stores', 'Administration', 'SIV-000020', 1, 'General Office use', 'Pending', NULL, NULL),
(21, 4, 1, 80.00, '2020-10-23', 'GSA Stores', 'Administration', 'SIV-000021', 1, 'General Office use', 'Pending', NULL, NULL),
(22, 4, 1, 80.00, '2021-02-08', 'GSA Stores', 'Administration', 'SIV-000022', 1, 'General Office use', 'Pending', NULL, NULL),
(23, 4, 1, 130.00, '2021-02-24', 'GSA Stores', 'Administration', 'SIV-000023', 1, 'General Office use', 'Pending', NULL, NULL),
(24, 4, 1, 130.00, '2021-03-24', 'GSA Stores', 'Administration', 'SIV-000024', 1, 'General Office use', 'Pending', NULL, NULL),
(25, 4, 1, 130.00, '2021-07-30', 'GSA Stores', 'Administration', 'SIV-000025', 1, 'General Office use', 'Pending', NULL, NULL),
(26, 4, 1, 130.00, '2021-09-20', 'GSA Stores', 'Administration', 'SIV-000026', 1, 'General Office use', 'Pending', NULL, NULL),
(27, 4, 1, 130.00, '2021-12-16', 'GSA Stores', 'Administration', 'SIV-000027', 1, 'General Office use', 'Pending', NULL, NULL),
(28, 4, 2, 130.00, '2022-03-09', 'GSA Stores', 'Administration', 'SIV-000028', 1, 'General Office use', 'Pending', NULL, NULL),
(29, 4, 1, 130.00, '2022-04-06', 'GSA Stores', 'Administration', 'SIV-000029', 1, 'General Office use', 'Pending', NULL, NULL),
(30, 4, 1, 130.00, '2022-04-22', 'GSA Stores', 'Administration', 'SIV-000030', 1, 'General Office use', 'Pending', NULL, NULL),
(31, 4, 1, 130.00, '2022-06-07', 'GSA Stores', 'Administration', 'SIV-000031', 1, 'General Office use', 'Pending', NULL, NULL),
(32, 4, 1, 130.00, '2022-08-11', 'GSA Stores', 'Administration', 'SIV-000032', 1, 'General Office use', 'Pending', NULL, NULL),
(33, 4, 1, 130.00, '2022-10-03', 'GSA Stores', 'Administration', 'SIV-000033', 1, 'General Office use', 'Pending', NULL, NULL),
(34, 4, 1, 130.00, '2023-03-22', 'GSA Stores', 'Administration', 'SIV-000034', 1, 'General Office use', 'Pending', NULL, NULL),
(35, 4, 1, 130.00, '2023-05-09', 'GSA Stores', 'Administration', 'SIV-000035', 1, 'General Office use', 'Pending', NULL, NULL),
(36, 4, 1, 130.00, '2023-06-07', 'GSA Stores', 'Administration', 'SIV-000036', 1, 'General Office use', 'Pending', NULL, NULL),
(37, 4, 1, 130.00, '2023-09-04', 'GSA Stores', 'Administration', 'SIV-000037', 1, 'General Office use', 'Pending', NULL, NULL),
(38, 4, 1, 130.00, '2023-10-25', 'GSA Stores', 'Administration', 'SIV-000038', 1, 'General Office use', 'Pending', NULL, NULL),
(39, 4, 1, 130.00, '2024-01-10', 'GSA Stores', 'Administration', 'SIV-000039', 1, 'General Office use', 'Pending', NULL, NULL),
(40, 4, 1, 130.00, '2024-04-16', 'GSA Stores', 'Administration', 'SIV-000040', 1, 'General Office use', 'Pending', NULL, NULL),
(41, 4, 1, 130.00, '2024-05-27', 'GSA Stores', 'Administration', 'SIV-000041', 1, 'General Office use', 'Pending', NULL, NULL),
(42, 4, 1, 130.00, '2024-09-10', 'GSA Stores', 'Administration', 'SIV-000042', 1, 'General Office use', 'Pending', NULL, NULL),
(43, 4, 1, 130.00, '2024-10-16', 'GSA Stores', 'Administration', 'SIV-000043', 1, 'General Office use', 'Pending', NULL, NULL),
(44, 4, 1, 130.00, '2025-01-08', 'GSA Stores', 'Administration', 'SIV-000044', 1, 'General Office use', 'Pending', NULL, NULL),
(45, 10, 2, 48.00, '2021-09-20', 'GSA Stores', 'Administration', 'SIV-000045', 1, 'General Office use', 'Pending', NULL, NULL),
(46, 10, 1, 50.00, '2023-03-22', 'GSA Stores', 'Administration', 'SIV-000046', 1, 'General Office use', 'Pending', NULL, NULL),
(47, 10, 1, 50.00, '2024-08-12', 'GSA Stores', 'Administration', 'SIV-000047', 1, 'General Office use', 'Pending', NULL, NULL),
(48, 5, 1, 68.00, '2022-04-07', 'GSA Stores', 'Administration', 'SIV-000048', 1, 'Shared to Staff', 'Pending', NULL, NULL),
(49, 6, 1, 68.00, '2022-04-07', 'GSA Stores', 'Administration', 'SIV-000049', 1, 'Shared to Staff', 'Pending', NULL, NULL),
(50, 6, 1, 68.00, '2022-04-28', 'GSA Stores', 'Administration', 'SIV-000050', 1, 'For Collaborators meeting', 'Pending', NULL, NULL),
(51, 6, 1, 68.00, '2023-04-13', 'GSA Stores', 'Administration', 'SIV-000051', 1, 'For Collaborators meeting', 'Pending', NULL, NULL),
(52, 6, 1, 68.00, '2023-06-08', 'GSA Stores', 'Administration', 'SIV-000052', 1, 'Shared to Staff', 'Pending', NULL, NULL),
(53, 6, 1, 68.00, '2024-04-16', 'GSA Stores', 'Administration', 'SIV-000053', 1, 'For Collaborators meeting', 'Pending', NULL, NULL),
(54, 6, 1, 68.00, '2024-09-10', 'GSA Stores', 'Administration', 'SIV-000054', 1, 'Shared to Staff', 'Pending', NULL, NULL),
(55, 7, 1, 68.00, '2021-03-16', 'GSA Stores', 'Administration', 'SIV-000055', 1, 'Office use', 'Pending', NULL, NULL),
(56, 12, 1, 120.00, '2021-02-23', 'GSA Stores', 'Administration', 'SIV-000056', 1, 'Allocated to SC', 'Pending', NULL, NULL),
(57, 12, 1, 120.00, '2021-04-21', 'GSA Stores', 'Administration', 'SIV-000057', 1, 'Allocated to Accounting Assistant', 'Pending', NULL, NULL),
(58, 12, 1, 120.00, '2021-06-07', 'GSA Stores', 'Administration', 'SIV-000058', 1, 'Allocated to Admin Officer', 'Pending', NULL, NULL),
(59, 11, 3, 18.00, '2021-05-26', 'GSA Stores', 'Administration', 'SIV-000059', 1, 'General Office Use', 'Pending', NULL, NULL),
(60, 11, 1, 18.00, '2021-05-26', 'GSA Stores', 'Accounts', 'SIV-000060', 1, 'Eric', 'Pending', NULL, NULL),
(61, 11, 1, 18.00, '2021-08-03', 'GSA Stores', 'Administration', 'SIV-000061', 1, 'General Office Use', 'Pending', NULL, NULL),
(62, 11, 1, 18.00, '2021-08-02', 'GSA Stores', 'Administration', 'SIV-000062', 1, 'General Office Use', 'Pending', NULL, NULL),
(63, 11, 1, 18.00, '2021-08-16', 'GSA Stores', 'Administration', 'SIV-000063', 1, 'General Office Use', 'Pending', NULL, NULL),
(64, 11, 1, 18.00, '2021-09-20', 'GSA Stores', 'Accounts', 'SIV-000064', 1, 'Eric', 'Pending', NULL, NULL),
(65, 11, 1, 30.00, '2022-08-31', 'GSA Stores', 'Administration', 'SIV-000065', 1, 'General Office Use', 'Pending', NULL, NULL),
(66, 11, 1, 30.00, '2022-10-06', 'GSA Stores', 'Accounts', 'SIV-000066', 1, 'Eric', 'Pending', NULL, NULL),
(67, 11, 1, 30.00, '2023-04-04', 'GSA Stores', 'Accounts', 'SIV-000067', 1, 'Eric', 'Pending', NULL, NULL),
(68, 11, 1, 30.00, '2023-09-04', 'GSA Stores', 'Administration', 'SIV-000068', 1, '33BC', 'Pending', NULL, NULL),
(69, 11, 1, 30.00, '2024-04-10', 'GSA Stores', 'Administration', 'SIV-000069', 1, 'General Office Use', 'Pending', NULL, NULL),
(70, 11, 1, 30.00, '2024-04-24', 'GSA Stores', 'Administration', 'SIV-000070', 1, 'General Office Use', 'Pending', NULL, NULL),
(71, 11, 1, 30.00, '2024-08-12', 'GSA Stores', 'Administration', 'SIV-000071', 1, 'General Office Use', 'Pending', NULL, NULL),
(72, 11, 1, 30.00, '2024-09-10', 'GSA Stores', 'Administration', 'SIV-000072', 1, 'General Office Use', 'Pending', NULL, NULL),
(73, 11, 1, 30.00, '2024-10-16', 'GSA Stores', 'Administration', 'SIV-000073', 1, 'General Office Use', 'Pending', NULL, NULL),
(74, 14, 2, 480.00, '2021-02-23', 'GSA Stores', 'Administration', 'SIV-000074', 1, 'Allocated to IT/Publication & Admin Officers', 'Pending', NULL, NULL),
(75, 15, 1, 580.00, '2021-08-06', 'GSA Stores', 'SC\'s Office', 'SIV-000075', 1, 'SC\'s Office Printer', 'Pending', NULL, NULL),
(76, 15, 1, 580.00, '2021-08-18', 'GSA Stores', 'SC\'s Office', 'SIV-000076', 1, 'SC\'s Office Printer', 'Pending', NULL, NULL),
(77, 15, 1, 580.00, '2024-03-20', 'GSA Stores', 'SC\'s Office', 'SIV-000077', 1, 'SC\'s Office Printer', 'Pending', NULL, NULL),
(78, 15, 1, 580.00, '2024-05-10', 'GSA Stores', 'SC\'s Office', 'SIV-000078', 1, 'SC\'s Office Printer', 'Pending', NULL, NULL),
(79, 16, 1, 120.00, '2022-07-12', 'GSA Stores', 'Administration', 'SIV-000079', 1, 'Office Use for Book Binding', 'Pending', NULL, NULL),
(80, 17, 1, 20.00, '2022-07-12', 'GSA Stores', 'Administration', 'SIV-000080', 1, 'For Office Use', 'Pending', NULL, NULL),
(81, 20, 2, 80.00, '2021-11-25', 'GSA Stores', 'Administration', 'SIV-000081', 1, 'For Office Use', 'Pending', NULL, NULL),
(82, 20, 2, 80.00, '2022-06-07', 'GSA Stores', 'Administration', 'SIV-000082', 1, 'For Office Use', 'Pending', NULL, NULL),
(83, 21, 5, 5.00, '2020-03-12', 'GSA Stores', 'Administration', 'SIV-000083', 1, 'Shared to Staff', 'Pending', NULL, NULL),
(84, 21, 1, 5.00, '2020-03-12', 'GSA Stores', 'Administration', 'SIV-000084', 1, 'For Office Use', 'Pending', NULL, NULL),
(85, 21, 1, 5.00, '2020-03-24', 'GSA Stores', 'Administration', 'SIV-000085', 1, 'For Office Use', 'Pending', NULL, NULL),
(86, 21, 1, 5.00, '2020-03-24', 'GSA Stores', 'Administration', 'SIV-000086', 1, 'For Office Use', 'Pending', NULL, NULL),
(87, 21, 1, 5.00, '2020-03-24', 'GSA Stores', 'Administration', 'SIV-000087', 1, 'For Office Use', 'Pending', NULL, NULL),
(88, 21, 1, 5.00, '2020-03-24', 'GSA Stores', 'Administration', 'SIV-000088', 1, 'For Office Use', 'Pending', NULL, NULL),
(89, 21, 1, 5.00, '2022-03-08', 'GSA Stores', 'Administration', 'SIV-000089', 1, 'For Office Use', 'Pending', NULL, NULL),
(90, 21, 1, 5.00, '2022-03-24', 'GSA Stores', 'Administration', 'SIV-000090', 1, 'For Office Use', 'Pending', NULL, NULL),
(91, 22, 1, 75.00, '2019-11-01', 'GSA Stores', 'Administration', 'SIV-000091', 1, 'For Admin/Office Use', 'Pending', NULL, NULL),
(92, 22, 3, 75.00, '2019-11-01', 'GSA Stores', 'Administration', 'SIV-000092', 1, 'For Admin/Office Use', 'Pending', NULL, NULL),
(93, 22, 1, 100.00, '2020-03-24', 'GSA Stores', 'SC\'s Office', 'SIV-000093', 1, 'Allocated to SC\'s Office', 'Pending', NULL, NULL),
(94, 22, 1, 100.00, '2020-03-24', 'GSA Stores', 'Administration', 'SIV-000094', 1, 'Allocated to Accounts', 'Pending', NULL, NULL),
(95, 22, 1, 100.00, '2020-03-24', 'GSA Stores', 'Administration', 'SIV-000095', 1, 'Allocated to Admin', 'Pending', NULL, NULL),
(96, 23, 1, 40.00, '2023-04-17', 'GSA Stores', 'Administration', 'SIV-000096', 1, 'For Admin/Office Use', 'Pending', NULL, NULL),
(97, 24, 20, 25.00, '2023-09-04', 'GSA Stores', 'Administration', 'SIV-000097', 1, 'For Office Use', 'Pending', NULL, NULL),
(98, 25, 2, 10.00, '2019-11-07', 'GSA Stores', 'Administration', 'SIV-000098', 1, 'Administrative Use', 'Pending', NULL, NULL),
(99, 25, 1, 10.00, '2019-11-07', 'GSA Stores', 'Administration', 'SIV-000099', 1, 'Administrative Use', 'Pending', NULL, NULL),
(100, 25, 1, 10.00, '2019-11-07', 'GSA Stores', 'Administration', 'SIV-000100', 1, 'Administrative Use', 'Pending', NULL, NULL),
(101, 25, 1, 10.00, '2019-11-29', 'GSA Stores', 'Administration', 'SIV-000101', 1, 'Administrative Use', 'Pending', NULL, NULL),
(102, 25, 1, 10.00, '2019-11-29', 'GSA Stores', 'Administration', 'SIV-000102', 1, 'Administrative Use', 'Pending', NULL, NULL),
(103, 25, 1, 10.00, '2019-11-29', 'GSA Stores', 'Administration', 'SIV-000103', 1, 'Administrative Use', 'Pending', NULL, NULL),
(104, 25, 1, 10.00, '2019-11-29', 'GSA Stores', 'Administration', 'SIV-000104', 1, 'Administrative Use', 'Pending', NULL, NULL),
(105, 25, 1, 10.00, '2019-12-03', 'GSA Stores', 'Administration', 'SIV-000105', 1, 'Administrative Use', 'Pending', NULL, NULL),
(106, 25, 2, 10.00, '2019-12-12', 'GSA Stores', 'Administration', 'SIV-000106', 1, 'Administrative Use', 'Pending', NULL, NULL),
(107, 25, 1, 10.00, '2020-01-08', 'GSA Stores', 'Administration', 'SIV-000107', 1, 'Administrative Use', 'Pending', NULL, NULL),
(108, 25, 1, 10.00, '2020-01-20', 'GSA Stores', 'Administration', 'SIV-000108', 1, 'Administrative Use', 'Pending', NULL, NULL),
(109, 25, 1, 10.00, '2020-01-22', 'GSA Stores', 'Administration', 'SIV-000109', 1, 'Administrative Use', 'Pending', NULL, NULL),
(110, 25, 6, 10.00, '2020-02-19', 'GSA Stores', 'Administration', 'SIV-000110', 1, 'Administrative Use', 'Pending', NULL, NULL),
(111, 25, 2, 10.00, '2020-02-19', 'GSA Stores', 'Administration', 'SIV-000111', 1, 'Administrative Use', 'Pending', NULL, NULL),
(112, 25, 1, 10.00, '2020-02-19', 'GSA Stores', 'Administration', 'SIV-000112', 1, 'Administrative Use', 'Pending', NULL, NULL),
(113, 25, 1, 10.00, '2020-02-19', 'GSA Stores', 'Administration', 'SIV-000113', 1, 'Administrative Use', 'Pending', NULL, NULL),
(114, 25, 1, 10.00, '2020-10-20', 'GSA Stores', 'Administration', 'SIV-000114', 1, 'Administrative Use', 'Pending', NULL, NULL),
(115, 25, 1, 10.00, '2020-10-20', 'GSA Stores', 'Administration', 'SIV-000115', 1, 'Administrative Use', 'Pending', NULL, NULL),
(116, 25, 1, 10.00, '2020-10-20', 'GSA Stores', 'Administration', 'SIV-000116', 1, 'Administrative Use', 'Pending', NULL, NULL),
(117, 25, 1, 10.00, '2020-11-02', 'GSA Stores', 'Administration', 'SIV-000117', 1, 'Administrative Use', 'Pending', NULL, NULL),
(118, 25, 1, 10.00, '2021-02-08', 'GSA Stores', 'Administration', 'SIV-000118', 1, 'Administrative Use', 'Pending', NULL, NULL),
(119, 25, 16, 10.00, '2021-02-08', 'GSA Stores', 'Administration', 'SIV-000119', 1, 'Administrative Use', 'Pending', NULL, NULL),
(120, 25, 5, 10.00, '2021-02-08', 'GSA Stores', 'Administration', 'SIV-000120', 1, 'Administrative Use', 'Pending', NULL, NULL),
(121, 25, 1, 10.00, '2021-02-08', 'GSA Stores', 'Administration', 'SIV-000121', 1, 'Administrative Use', 'Pending', NULL, NULL),
(122, 25, 1, 10.00, '2021-04-16', 'GSA Stores', 'Administration', 'SIV-000122', 1, 'Administrative Use', 'Pending', NULL, NULL),
(123, 25, 1, 10.00, '2021-06-22', 'GSA Stores', 'Administration', 'SIV-000123', 1, 'Administrative Use', 'Pending', NULL, NULL),
(124, 25, 2, 10.00, '2021-07-12', 'GSA Stores', 'Administration', 'SIV-000124', 1, 'Administrative Use', 'Pending', NULL, NULL),
(125, 25, 2, 10.00, '2021-09-16', 'GSA Stores', 'Administration', 'SIV-000125', 1, 'Administrative Use', 'Pending', NULL, NULL),
(126, 25, 1, 10.00, '2021-10-25', 'GSA Stores', 'Administration', 'SIV-000126', 1, 'Administrative Use', 'Pending', NULL, NULL),
(127, 25, 1, 10.00, '2021-11-01', 'GSA Stores', 'Administration', 'SIV-000127', 1, 'Administrative Use', 'Pending', NULL, NULL),
(128, 25, 4, 10.00, '2021-11-01', 'GSA Stores', 'Administration', 'SIV-000128', 1, 'Administrative Use', 'Pending', NULL, NULL),
(129, 25, 1, 10.00, '2021-11-20', 'GSA Stores', 'Administration', 'SIV-000129', 1, 'Administrative Use', 'Pending', NULL, NULL),
(130, 25, 2, 10.00, '2022-12-06', 'GSA Stores', 'Administration', 'SIV-000130', 1, 'Administrative Use', 'Pending', NULL, NULL),
(131, 25, 2, 10.00, '2022-04-03', 'GSA Stores', 'Administration', 'SIV-000131', 1, 'Administrative Use', 'Pending', NULL, NULL),
(132, 25, 2, 10.00, '2022-05-05', 'GSA Stores', 'Administration', 'SIV-000132', 1, 'Administrative Use', 'Pending', NULL, NULL),
(133, 25, 1, 10.00, '2022-07-20', 'GSA Stores', 'Administration', 'SIV-000133', 1, 'Administrative Use', 'Pending', NULL, NULL),
(134, 25, 3, 10.00, '2022-08-31', 'GSA Stores', 'Administration', 'SIV-000134', 1, 'Administrative Use', 'Pending', NULL, NULL),
(135, 25, 2, 10.00, '2023-06-26', 'GSA Stores', 'Administration', 'SIV-000135', 1, 'Administrative Use', 'Pending', NULL, NULL),
(136, 25, 1, 10.00, '2023-08-06', 'GSA Stores', 'Administration', 'SIV-000136', 1, 'Administrative Use', 'Pending', NULL, NULL),
(137, 25, 1, 10.00, '2023-10-27', 'GSA Stores', 'Administration', 'SIV-000137', 1, 'Administrative Use', 'Pending', NULL, NULL),
(138, 25, 3, 10.00, '2023-10-27', 'GSA Stores', 'Administration', 'SIV-000138', 1, 'Administrative Use', 'Pending', NULL, NULL),
(139, 25, 3, 10.00, '2024-02-26', 'GSA Stores', 'Administration', 'SIV-000139', 1, 'Administrative Use', 'Pending', NULL, NULL),
(140, 25, 2, 10.00, '2024-03-04', 'GSA Stores', 'Administration', 'SIV-000140', 1, 'Administrative Use', 'Pending', NULL, NULL),
(141, 25, 1, 10.00, '2024-03-20', 'GSA Stores', 'Administration', 'SIV-000141', 1, 'Administrative Use', 'Pending', NULL, NULL),
(142, 25, 2, 10.00, '2024-05-27', 'GSA Stores', 'Administration', 'SIV-000142', 1, 'Administrative Use', 'Pending', NULL, NULL),
(143, 25, 2, 10.00, '2024-06-15', 'GSA Stores', 'Administration', 'SIV-000143', 1, 'Administrative Use', 'Pending', NULL, NULL),
(144, 25, 1, 10.00, '2024-06-20', 'GSA Stores', 'Administration', 'SIV-000144', 1, 'Administrative Use', 'Pending', NULL, NULL),
(145, 31, 2, 750.00, '2023-06-27', 'GSA Stores', 'Administration', 'SIV-000145', 1, 'Allocated to IT/Publication Officer for Desk and Computer Network', 'Pending', NULL, NULL),
(146, 31, 1, 750.00, '2023-11-24', 'GSA Stores', 'SC\'s Office', 'SIV-000146', 1, 'Allocated to SC\'s Office', 'Pending', NULL, NULL),
(147, 31, 1, 750.00, '2024-03-01', 'GSA Stores', 'Accounts', 'SIV-000147', 1, 'Allocated to Accountant', 'Pending', NULL, NULL),
(148, 31, 1, 750.00, '2024-04-24', 'GSA Stores', 'Administration', 'SIV-000148', 1, 'Allocated to Admin Officer', 'Pending', NULL, NULL),
(149, 31, 1, 750.00, '2024-09-30', 'GSA Stores', 'Accounts', 'SIV-000149', 1, 'Allocated to Accounting Assistant', 'Pending', NULL, NULL),
(150, 31, 1, 750.00, '2024-11-11', 'GSA Stores', 'Accounts', 'SIV-000150', 1, 'Allocated to PM Officer', 'Pending', NULL, NULL),
(151, 32, 120, 20.00, '2021-09-16', 'GSA Stores', 'Administration', 'SIV-000151', 1, 'Kumasi Branch', 'Pending', NULL, NULL),
(152, 32, 80, 20.00, '2021-09-22', 'GSA Stores', 'Administration', 'SIV-000152', 1, 'Kumasi Branch', 'Pending', NULL, NULL),
(153, 32, 56, 0.00, '2021-09-28', 'GSA Stores', 'Individual Saff & NOs', 'SIV-000153', 1, 'Allocation of Cloth to Staff and National Officers', 'Pending', NULL, NULL),
(154, 32, 12, 0.00, '2021-10-21', 'GSA Stores', 'KNUST', 'SIV-000154', 1, 'Protocol to the Vice Chancellor and Provost of KNUST', 'Pending', NULL, NULL),
(155, 32, 5, 20.00, '2021-11-10', 'GSA Stores', 'Administration', 'SIV-000155', 1, 'Sale of Cloth - (Eric to Prof. Regina)', 'Pending', NULL, NULL),
(156, 32, 120, 20.00, '2021-11-17', 'GSA Stores', 'Administration', 'SIV-000156', 1, 'Asante-Mampong', 'Pending', NULL, NULL),
(157, 32, 3, 0.00, '2021-12-01', 'GSA Stores', 'Administration', 'SIV-000157', 1, 'Prof. Peter Twumasi - National Sports Authority', 'Pending', NULL, NULL),
(158, 32, 3, 20.00, '2021-12-07', 'GSA Stores', 'Administration', 'SIV-000158', 1, 'Sale of Cloth - (Raymond Charles Ehiem)', 'Pending', NULL, NULL),
(159, 32, 24, 20.00, '2021-12-08', 'GSA Stores', 'Administration', 'SIV-000159', 1, 'Tamale Branch President', 'Pending', NULL, NULL),
(160, 32, 6, 20.00, '2021-12-08', 'GSA Stores', 'Administration', 'SIV-000160', 1, 'Dr. Michael Osae - Hon. National President', 'Pending', NULL, NULL),
(161, 32, 3, 20.00, '2021-12-08', 'GSA Stores', 'Administration', 'SIV-000161', 1, 'Dr. Esther Marfo-Ahenkora - Hon. National Treasurer', 'Pending', NULL, NULL),
(162, 32, 3, 20.00, '2021-12-08', 'GSA Stores', 'Administration', 'SIV-000162', 1, 'Dr. Esther Marfo-Ahenkora - Hon. National Treasurer', 'Pending', NULL, NULL),
(163, 32, 3, 20.00, '2021-12-21', 'GSA Stores', 'Administration', 'SIV-000163', 1, 'Prof. Oteng Yeboah Alfred', 'Pending', NULL, NULL),
(164, 32, 3, 0.00, '2022-01-06', 'GSA Stores', 'Administration', 'SIV-000164', 1, 'Kwabena Kankam Kuffour', 'Pending', NULL, NULL),
(165, 32, 36, 20.00, '2022-01-14', 'GSA Stores', 'Administration', 'SIV-000165', 1, 'Kumasi Branch', 'Pending', NULL, NULL),
(166, 32, 15, 20.00, '2022-02-23', 'GSA Stores', 'Administration', 'SIV-000166', 1, 'Accra Branch', 'Pending', NULL, NULL),
(167, 32, 120, 20.00, '2022-02-25', 'GSA Stores', 'Administration', 'SIV-000167', 1, 'Kumasi Branch', 'Pending', NULL, NULL),
(168, 32, 4, 20.00, '2022-03-02', 'GSA Stores', 'Administration', 'SIV-000168', 1, 'Koforidua Branch President', 'Pending', NULL, NULL),
(169, 32, 60, 20.00, '2022-03-10', 'GSA Stores', 'Administration', 'SIV-000169', 1, 'Tamale Branch President', 'Pending', NULL, NULL),
(170, 32, 4, 20.00, '2022-03-21', 'GSA Stores', 'Administration', 'SIV-000170', 1, 'Dr. Esther Marfo-Ahenkora - Hon. National Treasurer', 'Pending', NULL, NULL),
(171, 32, 24, 20.00, '2022-03-25', 'GSA Stores', 'Administration', 'SIV-000171', 1, 'Dr. Nii Klotey Kotei - Ho Branch President', 'Pending', NULL, NULL),
(172, 32, 200, 20.00, '2022-04-27', 'GSA Stores', 'Administration', 'SIV-000172', 1, 'Kumasi Branch', 'Pending', NULL, NULL),
(173, 32, 3, 20.00, '2022-04-28', 'GSA Stores', 'Administration', 'SIV-000173', 1, 'Dr. Irene Opoku-Ntim - Hon. National Secretary', 'Pending', NULL, NULL),
(174, 32, 3, 20.00, '2022-04-28', 'GSA Stores', 'Administration', 'SIV-000174', 1, 'Dr. Irene Opoku-Ntim - Hon. National Secretary', 'Pending', NULL, NULL),
(175, 32, 30, 20.00, '2022-05-10', 'GSA Stores', 'Administration', 'SIV-000175', 1, 'Cape Coast Branch', 'Pending', NULL, NULL),
(176, 32, 100, 20.00, '2022-06-02', 'GSA Stores', 'Administration', 'SIV-000176', 1, 'Kumasi Branch', 'Pending', NULL, NULL),
(177, 32, 12, 0.00, '2022-06-02', 'GSA Stores', 'Administration', 'SIV-000177', 1, 'Protocol Presentation - Kumasi Branch', 'Pending', NULL, NULL),
(178, 32, 5, 20.00, '2022-06-03', 'GSA Stores', 'Administration', 'SIV-000178', 1, 'Christopher Bruce Konu - Kumasi Branch', 'Pending', NULL, NULL),
(179, 32, 3, 0.00, '2022-07-14', 'GSA Stores', 'Administration', 'SIV-000179', 1, 'Protocol Presentation - Deputy Minister of Trade', 'Pending', NULL, NULL),
(180, 32, 36, 20.00, '2022-07-28', 'GSA Stores', 'Administration', 'SIV-000180', 1, 'Koforidua Branch President', 'Pending', NULL, NULL),
(181, 32, 22, 20.00, '2022-08-16', 'GSA Stores', 'Administration', 'SIV-000181', 1, 'Winneba Branch', 'Pending', NULL, NULL),
(182, 32, 24, 20.00, '2022-08-16', 'GSA Stores', 'Administration', 'SIV-000182', 1, 'Winneba Branch', 'Pending', NULL, NULL),
(183, 32, 24, 20.00, '2022-09-13', 'GSA Stores', 'Administration', 'SIV-000183', 1, 'Ho Branch - Dr. Korley Kortei', 'Pending', NULL, NULL),
(184, 32, 24, 20.00, '2022-09-13', 'GSA Stores', 'Administration', 'SIV-000184', 1, 'Ho Branch - Dr. Korley Kortei', 'Pending', NULL, NULL),
(185, 32, 24, 20.00, '2022-09-13', 'GSA Stores', 'Administration', 'SIV-000185', 1, 'Ho Branch - Dr. Korley Kortei', 'Pending', NULL, NULL),
(186, 32, 36, 20.00, '2022-09-27', 'GSA Stores', 'Administration', 'SIV-000186', 1, 'Koforidua Branch President', 'Pending', NULL, NULL),
(187, 32, 72, 20.00, '2022-09-27', 'GSA Stores', 'Administration', 'SIV-000187', 1, 'Asante-Mampong', 'Pending', NULL, NULL),
(188, 32, 25, 20.00, '2022-09-29', 'GSA Stores', 'Administration', 'SIV-000188', 1, 'Ho Branch - Dr. Korley Kortei', 'Pending', NULL, NULL),
(189, 32, 8, 20.00, '2022-09-30', 'GSA Stores', 'Administration', 'SIV-000189', 1, 'Mr. Forson Dzotor', 'Pending', NULL, NULL),
(190, 32, 17, 20.00, '2022-10-03', 'GSA Stores', 'Administration', 'SIV-000190', 1, 'Koforidua Branch President', 'Pending', NULL, NULL),
(191, 32, 99, 20.00, '2022-10-12', 'GSA Stores', 'Administration', 'SIV-000191', 1, '18BW Sales', 'Pending', NULL, NULL),
(192, 32, 3, 0.00, '2022-10-12', 'GSA Stores', 'Administration', 'SIV-000192', 1, 'Protocol Presentation - Koforidua VC', 'Pending', NULL, NULL),
(193, 32, 8, 20.00, '2022-11-02', 'GSA Stores', 'Administration', 'SIV-000193', 1, 'Accra Branch', 'Pending', NULL, NULL),
(194, 32, 6, 20.00, '2023-01-12', 'GSA Stores', 'Administration', 'SIV-000194', 1, 'Sheila - GAEC', 'Pending', NULL, NULL),
(195, 32, 4, 0.00, '2023-01-31', 'GSA Stores', 'Administration', 'SIV-000195', 1, 'Protocol Presentation - Director General of CPMR', 'Pending', NULL, NULL),
(196, 32, 50, 20.00, '2023-02-01', 'GSA Stores', 'Administration', 'SIV-000196', 1, 'Kumasi Branch', 'Pending', NULL, NULL),
(197, 32, 6, 20.00, '2023-02-20', 'GSA Stores', 'Administration', 'SIV-000197', 1, 'Prof. Salia', 'Pending', NULL, NULL),
(198, 32, 30, 30.00, '2023-03-10', 'GSA Stores', 'Administration', 'SIV-000198', 1, 'Cape Coast Branch', 'Pending', NULL, NULL),
(199, 32, 10, 30.00, '2023-03-10', 'GSA Stores', 'Administration', 'SIV-000199', 1, 'Kumasi Branch', 'Pending', NULL, NULL),
(200, 32, 2, 30.00, '2023-03-15', 'GSA Stores', 'Administration', 'SIV-000200', 1, 'Dr. Edem Kwabla Sosu', 'Pending', NULL, NULL),
(201, 32, 4, 30.00, '2023-04-04', 'GSA Stores', 'Administration', 'SIV-000201', 1, 'Edem Adawu Robert', 'Pending', NULL, NULL),
(202, 32, 74, 30.00, '2023-09-08', 'GSA Stores', 'Administration', 'SIV-000202', 1, '33BC Sales (Accra)', 'Pending', NULL, NULL),
(203, 32, 4, 30.00, '2023-11-27', 'GSA Stores', 'Administration', 'SIV-000203', 1, 'Dr. Irene Opoku-Ntim - Fmr. Hon. National Secretary', 'Pending', NULL, NULL),
(204, 32, 3, 30.00, '2023-11-27', 'GSA Stores', 'Administration', 'SIV-000204', 1, 'Dr. Michael Osae - Fmr. Hon. National President', 'Pending', NULL, NULL),
(205, 32, 4, 30.00, '2024-05-10', 'GSA Stores', 'Administration', 'SIV-000205', 1, 'Holy Zanu', 'Pending', NULL, NULL),
(206, 32, 1, 30.00, '2024-05-10', 'GSA Stores', 'Administration', 'SIV-000206', 1, 'Eric Ayisi Essel', 'Pending', NULL, NULL),
(207, 33, 70, 50.00, '2022-09-13', 'GSA Stores', 'Administration', 'SIV-000207', 1, 'Sunyani Branch - Dr. Selina Saah', 'Pending', NULL, NULL),
(208, 33, 15, 50.00, '2022-09-13', 'GSA Stores', 'Administration', 'SIV-000208', 1, 'Winneba Branch', 'Pending', NULL, NULL),
(209, 33, 5, 50.00, '2022-09-28', 'GSA Stores', 'Administration', 'SIV-000209', 1, 'Asante-Mampong Branch - Rev. Kwame Nkrumah Hope', 'Pending', NULL, NULL),
(210, 33, 2, 50.00, '2022-09-29', 'GSA Stores', 'Administration', 'SIV-000210', 1, 'Accra Branch President and Secretary', 'Pending', NULL, NULL),
(211, 33, 5, 50.00, '2022-09-29', 'GSA Stores', 'Administration', 'SIV-000211', 1, 'Ho Branch', 'Pending', NULL, NULL),
(212, 33, 15, 50.00, '2022-10-05', 'GSA Stores', 'Administration', 'SIV-000212', 1, 'GSA Staff and National Officers', 'Pending', NULL, NULL),
(213, 33, 5, 50.00, '2022-10-06', 'GSA Stores', 'Administration', 'SIV-000213', 1, 'Staff Purchase', 'Pending', NULL, NULL),
(214, 33, 1, 50.00, '2022-10-10', 'GSA Stores', 'Administration', 'SIV-000214', 1, 'Staff Purchase - Kenneth Amuh Akrebeto', 'Pending', NULL, NULL),
(215, 33, 20, 50.00, '2022-10-12', 'GSA Stores', 'Administration', 'SIV-000215', 1, '18BW Sales (Koforidua)', 'Pending', NULL, NULL),
(216, 33, 2, 50.00, '2022-10-12', 'GSA Stores', 'Administration', 'SIV-000216', 1, 'Damaged Polo Shirt', 'Pending', NULL, NULL),
(217, 33, 1, 50.00, '2022-10-12', 'GSA Stores', 'Administration', 'SIV-000217', 1, 'Mr. Forson Dzotor', 'Pending', NULL, NULL),
(218, 33, 1, 50.00, '2022-10-12', 'GSA Stores', 'Administration', 'SIV-000218', 1, 'Mr. Forson Dzotor', 'Pending', NULL, NULL),
(219, 33, 2, 50.00, '2022-11-02', 'GSA Stores', 'Administration', 'SIV-000219', 1, 'Accra Branch', 'Pending', NULL, NULL),
(220, 33, 1, 50.00, '2023-01-12', 'GSA Stores', 'Administration', 'SIV-000220', 1, 'Sheila - GAEC', 'Pending', NULL, NULL),
(221, 33, 20, 50.00, '2023-02-01', 'GSA Stores', 'Administration', 'SIV-000221', 1, 'Kumasi Branch', 'Pending', NULL, NULL),
(222, 33, 28, 50.00, '2023-02-01', 'GSA Stores', 'Administration', 'SIV-000222', 1, 'Kumasi Branch', 'Pending', NULL, NULL),
(223, 33, 2, 50.00, '2023-02-06', 'GSA Stores', 'Administration', 'SIV-000223', 1, 'Eric Ayisi Essel', 'Pending', NULL, NULL),
(224, 33, 3, 70.00, '2023-03-10', 'GSA Stores', 'Administration', 'SIV-000224', 1, 'Kumasi Branch', 'Pending', NULL, NULL),
(225, 33, 20, 70.00, '2023-03-10', 'GSA Stores', 'Administration', 'SIV-000225', 1, 'Cape Coast Branch', 'Pending', NULL, NULL),
(226, 33, 1, 70.00, '2023-03-15', 'GSA Stores', 'Administration', 'SIV-000226', 1, 'Dr. Edem Kwabla Sosu', 'Pending', NULL, NULL),
(227, 33, 1, 70.00, '2023-03-29', 'GSA Stores', 'Administration', 'SIV-000227', 1, 'Edem Adawu Robert', 'Pending', NULL, NULL),
(228, 33, 1, 70.00, '2023-04-04', 'GSA Stores', 'Administration', 'SIV-000228', 1, 'Edem Adawu Robert', 'Pending', NULL, NULL),
(229, 33, 1, 70.00, '2023-06-08', 'GSA Stores', 'Administration', 'SIV-000229', 1, 'Dr. Micahel Osae', 'Pending', NULL, NULL),
(230, 33, 7, 70.00, '2023-09-02', 'GSA Stores', 'Administration', 'SIV-000230', 1, 'Registration Assistants', 'Pending', NULL, NULL),
(231, 33, 29, 70.00, '2023-09-08', 'GSA Stores', 'Administration', 'SIV-000231', 1, '33BC Sales (Accra)', 'Pending', NULL, NULL),
(232, 33, 2, 70.00, '2024-05-10', 'GSA Stores', 'Administration', 'SIV-000232', 1, 'Holy Zanu', 'Pending', NULL, NULL),
(233, 33, 1, 70.00, '2024-05-10', 'GSA Stores', 'Administration', 'SIV-000233', 1, 'William Akoto-Danso', 'Pending', NULL, NULL),
(234, 33, 4, 0.00, '2024-09-10', 'GSA Stores', 'Administration', 'SIV-000234', 1, 'Protocol Presentation - Auditor', 'Pending', NULL, NULL),
(235, 33, 3, 90.00, '2024-10-10', 'GSA Stores', 'Administration', 'SIV-000235', 1, '19BW Sales (Sunyani)', 'Pending', NULL, NULL),
(236, 33, 1, 100.00, '2024-10-10', 'GSA Stores', 'Administration', 'SIV-000236', 1, '19BW Sales (Sunyani)', 'Pending', NULL, NULL),
(237, 33, 5, 0.00, '2025-01-27', 'GSA Stores', 'Individual Staff', 'SIV-000237', 1, 'Allocation to GSA Staff', 'Pending', NULL, NULL),
(238, 34, 1, 25.00, '2025-03-28', 'GSA Stores', 'Administration', 'SIV-000238', 1, 'Requester: 4 | Authorizer: 4', 'Pending', NULL, NULL),
(239, 20, 1, 80.00, '2025-03-28', 'GSA Stores', 'Administration', 'SIV-000239', 1, 'Requester: 4 | Authorizer: 4', 'Pending', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `unit` varchar(50) NOT NULL,
  `current_price` decimal(10,2) NOT NULL,
  `quantity` int(11) DEFAULT 0 CHECK (`quantity` >= 0),
  `supplier_id` int(11) DEFAULT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `item_name`, `description`, `unit`, `current_price`, `quantity`, `supplier_id`, `created_at`) VALUES
(4, 'A4 Paper', '', 'Box', 370.00, 19, 3, '2025-03-07'),
(5, 'BIC Black Pens', '', 'Box', 68.00, 9, 3, '2025-03-10'),
(6, 'BIC Blue Pens', '', 'Box', 68.00, 14, 3, '2025-03-10'),
(7, 'BIC Red Pens', '', 'Box', 68.00, 3, 2, '2025-03-10'),
(9, 'A4 Brown Envelopes', '', 'Packs', 90.00, 0, 2, '2025-03-10'),
(10, 'A5 Brown Envelopes', '', 'Packs', 50.00, 16, 3, '2025-03-10'),
(11, 'DL White Envelopes', '', 'Packs', 30.00, 18, 2, '2025-03-10'),
(12, 'Computer Keyboard', '', 'PCS', 120.00, 2, 3, '2025-03-10'),
(13, 'Computer Mouse', '', 'PCS', 30.00, 4, 3, '2025-03-10'),
(14, 'External Hard Drive', '', 'PCS', 480.00, 1, 3, '2025-03-11'),
(15, 'HP 85a Toner', '', 'PCS', 580.00, 7, 3, '2025-03-11'),
(16, 'PVC Cover', '', 'Packs', 120.00, 10, 2, '2025-03-11'),
(17, 'Correction Fluid', '', 'PCS', 20.00, 7, 2, '2025-03-11'),
(18, 'Perforator', '', 'PCS', 100.00, 2, 2, '2025-03-11'),
(19, 'Staples Pin B/S', '', 'Packs', 96.00, 2, 2, '2025-03-11'),
(20, 'Staple Pin S/S', '', 'Packs', 80.00, 5, 2, '2025-03-11'),
(21, 'Ruler', '', 'PCS', 5.00, 18, 4, '2025-03-11'),
(22, 'Stapler', '', 'PCS', 100.00, 3, 4, '2025-03-11'),
(23, 'Ink Pad', '', 'PCS', 40.00, 1, 2, '2025-03-11'),
(24, 'Maker', '', 'PCS', 25.00, 0, 2, '2025-03-11'),
(25, 'Flat Files', '', 'PCS', 10.00, 8, 4, '2025-03-11'),
(26, 'A3 Sheet', '', 'Ream', 157.00, 3, 2, '2025-03-11'),
(27, 'A3 Brown Envelopes', '', 'Packs', 70.00, 13, 2, '2025-03-11'),
(28, 'Network Kit', '', 'PCS', 450.00, 1, 3, '2025-03-11'),
(29, 'Paper Glue', '', 'PCS', 35.00, 1, 2, '2025-03-11'),
(30, 'Board Clips', '', 'Packs', 27.00, 2, 2, '2025-03-11'),
(31, 'UPS', '', 'PCS', 750.00, 0, 5, '2025-03-11'),
(32, 'GSA Cloth', '', 'Yard', 30.00, 0, 6, '2025-03-11'),
(33, 'GSA Polo Shirts', '', 'PCS', 100.00, 26, 7, '2025-03-11'),
(34, 'Paper Clips', '', 'Packs', 25.00, 16, 2, '2025-03-11');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `dismissed_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `is_read`, `created_at`, `dismissed_by`) VALUES
(1, 3, 'New request received from viewer', 1, '2025-03-18', 1),
(2, 3, 'New stock issuance request by dee.', 1, '2025-03-19', NULL),
(3, NULL, 'A new store request has been made!', 0, '2025-03-19', NULL),
(4, NULL, 'A new store request has been made!', 0, '2025-03-19', NULL),
(5, NULL, 'A new store request has been made!', 0, '2025-03-19', NULL),
(6, NULL, 'A new store request has been made!', 0, '2025-03-19', NULL),
(7, NULL, 'A new store request has been made!', 0, '2025-03-19', NULL),
(8, NULL, 'A new store request has been made!', 0, '2025-03-19', NULL),
(9, NULL, 'A new store request has been made!', 0, '2025-03-19', NULL),
(10, NULL, 'A new store request has been made!', 0, '2025-03-19', NULL),
(11, NULL, 'A new store request has been made!', 0, '2025-03-19', NULL),
(12, NULL, 'A new store request has been made!', 0, '2025-03-19', NULL),
(13, 3, 'New stock issuance request by dee.', 1, '2025-03-19', NULL),
(14, NULL, 'A new store request has been made!', 0, '2025-03-19', NULL),
(15, NULL, 'New stock requisition request by dee.', 0, '2025-03-19', NULL),
(16, 1, 'New stock requisition request by dee.', 1, '2025-03-19', NULL),
(17, 2, 'New stock requisition request by dee.', 0, '2025-03-19', NULL),
(18, 1, 'New stock requisition request by dee.', 1, '2025-03-19', NULL),
(19, 2, 'New stock requisition request by dee.', 0, '2025-03-19', NULL),
(20, NULL, 'A new store request has been made!', 0, '2025-03-20', NULL),
(21, NULL, 'A new store request has been made!', 0, '2025-03-20', NULL),
(22, NULL, 'A new store request has been made!', 0, '2025-03-20', NULL),
(23, NULL, 'A new store request has been made!', 0, '2025-03-20', NULL),
(24, NULL, 'A new store request has been made!', 0, '2025-03-20', NULL),
(25, NULL, 'A new store request has been made!', 0, '2025-03-20', NULL),
(26, NULL, 'A new store request has been made!', 0, '2025-03-20', NULL),
(27, NULL, 'A new store request has been made!', 0, '2025-03-20', NULL),
(28, NULL, 'A new store request has been made!', 0, '2025-03-20', NULL),
(29, NULL, 'A new store request has been made!', 0, '2025-03-20', NULL),
(30, NULL, 'A new store request has been made!', 0, '2025-03-20', NULL),
(31, 1, 'New stock requisition request by dee.', 1, '2025-03-21', NULL),
(32, 2, 'New stock requisition request by dee.', 0, '2025-03-21', NULL),
(33, 1, 'New stock requisition request by dee.', 1, '2025-03-21', NULL),
(34, 2, 'New stock requisition request by dee.', 0, '2025-03-21', NULL),
(35, NULL, 'A new store request has been made!', 0, '2025-03-21', NULL),
(36, NULL, 'A new store request has been made!', 0, '2025-03-21', NULL),
(37, 1, 'New stock requisition request by dee.', 1, '2025-03-21', NULL),
(38, 2, 'New stock requisition request by dee.', 0, '2025-03-21', NULL),
(39, NULL, 'A new store request has been made!', 0, '2025-03-24', NULL),
(40, NULL, 'A new store request has been made!', 0, '2025-03-24', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `received_stock`
--

CREATE TABLE `received_stock` (
  `received_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `received_quantity` int(11) NOT NULL CHECK (`received_quantity` > 0),
  `cost` decimal(10,2) NOT NULL,
  `date_received` date DEFAULT current_timestamp(),
  `received_by` int(11) NOT NULL,
  `stored_at` varchar(255) NOT NULL DEFAULT 'GSA Stores'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `received_stock`
--

INSERT INTO `received_stock` (`received_id`, `item_id`, `supplier_id`, `received_quantity`, `cost`, `date_received`, `received_by`, `stored_at`) VALUES
(1, 13, 3, 7, 30.00, '2021-02-23', 1, 'GSA Stores'),
(2, 9, 2, 5, 50.00, '2023-09-04', 1, 'GSA Stores'),
(3, 9, 2, 2, 90.00, '2024-09-20', 1, 'GSA Stores'),
(4, 4, 2, 3, 60.00, '2019-11-07', 1, 'GSA Stores'),
(5, 4, 2, 5, 80.00, '2020-07-27', 1, 'GSA Stores'),
(6, 4, 3, 40, 130.00, '2021-02-23', 1, 'GSA Stores'),
(7, 10, 2, 10, 48.00, '2021-02-23', 1, 'GSA Stores'),
(8, 10, 3, 10, 50.00, '2022-09-20', 1, 'GSA Stores'),
(9, 5, 3, 10, 68.00, '2021-02-23', 1, 'GSA Stores'),
(10, 6, 3, 20, 68.00, '2021-02-23', 1, 'GSA Stores'),
(11, 7, 2, 4, 68.00, '2021-02-23', 1, 'GSA Stores'),
(12, 12, 3, 5, 120.00, '2021-02-23', 1, 'GSA Stores'),
(13, 11, 3, 10, 18.00, '2021-05-26', 1, 'GSA Stores'),
(14, 11, 2, 25, 30.00, '2022-01-01', 1, 'GSA Stores'),
(15, 14, 3, 3, 480.00, '2021-02-23', 1, 'GSA Stores'),
(16, 15, 3, 10, 580.00, '2021-02-23', 1, 'GSA Stores'),
(17, 15, 2, 1, 580.00, '2021-08-18', 1, 'GSA Stores'),
(18, 16, 2, 11, 120.00, '2021-06-24', 1, 'GSA Stores'),
(19, 17, 2, 8, 20.00, '2021-06-24', 1, 'GSA Stores'),
(20, 18, 2, 2, 100.00, '2021-06-24', 1, 'GSA Stores'),
(21, 19, 2, 2, 96.00, '2021-06-24', 1, 'GSA Stores'),
(22, 20, 2, 10, 80.00, '2020-03-12', 1, 'GSA Stores'),
(23, 21, 4, 30, 5.00, '2020-03-12', 1, 'GSA Stores'),
(24, 22, 2, 4, 75.00, '2019-11-01', 1, 'GSA Stores'),
(25, 22, 4, 6, 100.00, '2020-03-12', 1, 'GSA Stores'),
(26, 23, 2, 2, 40.00, '2021-06-24', 1, 'GSA Stores'),
(27, 24, 2, 20, 25.00, '2021-06-24', 1, 'GSA Stores'),
(28, 25, 4, 100, 10.00, '2019-11-07', 1, 'GSA Stores'),
(29, 26, 2, 3, 157.00, '2021-06-24', 1, 'GSA Stores'),
(30, 27, 2, 13, 70.00, '2021-06-24', 1, 'GSA Stores'),
(31, 28, 3, 1, 450.00, '2021-02-23', 1, 'GSA Stores'),
(32, 29, 2, 1, 70.00, '2023-09-04', 1, 'GSA Stores'),
(33, 30, 2, 2, 27.00, '2023-09-04', 1, 'GSA Stores'),
(34, 31, 5, 7, 750.00, '2023-06-27', 1, 'GSA Stores'),
(35, 32, 6, 1680, 20.00, '2021-08-18', 1, 'GSA Stores'),
(36, 33, 7, 300, 50.00, '2022-09-13', 1, 'GSA Stores');

-- --------------------------------------------------------

--
-- Table structure for table `requisition_requests`
--

CREATE TABLE `requisition_requests` (
  `request_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `requested_quantity` int(11) NOT NULL,
  `requested_by` int(11) NOT NULL,
  `date_requested` date DEFAULT current_timestamp(),
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requisition_requests`
--

INSERT INTO `requisition_requests` (`request_id`, `item_id`, `requested_quantity`, `requested_by`, `date_requested`, `status`, `remarks`) VALUES
(1, 13, 1, 3, '2025-03-19', 'Rejected', 'Office Use'),
(2, 10, 1, 3, '2025-03-19', 'Rejected', 'Office Use'),
(3, 4, 1, 3, '2025-03-19', 'Approved', 'Offfice'),
(4, 7, 1, 3, '2025-03-21', 'Rejected', 'For Use'),
(5, 7, 1, 3, '2025-03-21', 'Rejected', 'Office Use'),
(6, 4, 1, 3, '2025-03-21', 'Pending', 'Office Use');

-- --------------------------------------------------------

--
-- Table structure for table `store_records`
--

CREATE TABLE `store_records` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `receipt` int(11) DEFAULT NULL,
  `issue` int(11) DEFAULT NULL,
  `balance` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `amount` decimal(10,2) GENERATED ALWAYS AS (coalesce(`receipt`,0) * `unit_price`) STORED,
  `supplier_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `store_records`
--

INSERT INTO `store_records` (`id`, `item_id`, `date`, `receipt`, `issue`, `balance`, `unit_price`, `supplier_id`) VALUES
(1, 13, '2021-02-23', 7, 0, 7, 30.00, 3),
(2, 13, '2021-02-23', 0, 1, 6, 30.00, 0),
(3, 13, '2021-04-13', 0, 1, 5, 30.00, 0),
(4, 13, '2024-04-10', 0, 1, 4, 30.00, 0),
(5, 9, '2021-10-01', 0, 1, 6, 40.00, 0),
(6, 9, '2022-04-06', 0, 1, 5, 40.00, 0),
(7, 9, '2022-06-07', 0, 2, 3, 40.00, 0),
(8, 9, '2022-10-03', 0, 1, 2, 40.00, 0),
(9, 9, '2023-03-22', 0, 1, 1, 40.00, 0),
(10, 9, '2023-06-06', 0, 1, 0, 40.00, 0),
(11, 9, '2023-09-04', 5, 1, 4, 50.00, 2),
(12, 9, '2023-10-03', 0, 1, 3, 50.00, 0),
(13, 9, '2024-04-10', 0, 1, 2, 50.00, 0),
(14, 9, '2024-05-10', 0, 1, 1, 50.00, 0),
(15, 9, '2024-05-27', 0, 1, 0, 50.00, 0),
(16, 9, '2024-09-20', 2, 0, 2, 90.00, 2),
(17, 9, '2024-09-20', 0, 1, 1, 90.00, 0),
(18, 9, '2024-10-07', 0, 1, 0, 90.00, 0),
(19, 4, '2019-11-07', 3, 0, 3, 60.00, 2),
(20, 4, '2019-11-07', 0, 1, 2, 60.00, 0),
(21, 4, '2019-11-07', 0, 1, 1, 60.00, 0),
(22, 4, '2019-11-07', 0, 1, 0, 60.00, 0),
(23, 4, '2020-07-27', 5, 0, 5, 80.00, 2),
(24, 4, '2020-08-06', 0, 1, 4, 80.00, 0),
(25, 4, '2020-10-23', 0, 1, 3, 80.00, 0),
(26, 4, '2021-02-08', 0, 1, 2, 80.00, 0),
(27, 4, '2021-02-23', 40, 0, 42, 130.00, 3),
(28, 4, '2021-02-24', 0, 1, 41, 130.00, 0),
(29, 4, '2021-03-24', 0, 1, 40, 130.00, 0),
(30, 4, '2021-07-30', 0, 1, 39, 130.00, 0),
(31, 4, '2021-09-20', 0, 1, 38, 130.00, 0),
(32, 4, '2021-12-16', 0, 1, 37, 130.00, 0),
(33, 4, '2022-03-09', 0, 2, 35, 130.00, 0),
(34, 4, '2022-04-06', 0, 1, 34, 130.00, 0),
(35, 4, '2022-04-22', 0, 1, 33, 130.00, 0),
(36, 4, '2022-06-07', 0, 1, 32, 130.00, 0),
(37, 4, '2022-08-11', 0, 1, 31, 130.00, 0),
(38, 4, '2022-10-03', 0, 1, 30, 130.00, 0),
(39, 4, '2023-03-22', 0, 1, 29, 130.00, 0),
(40, 4, '2023-05-09', 0, 1, 28, 130.00, 0),
(41, 4, '2023-06-07', 0, 1, 27, 130.00, 0),
(42, 4, '2023-09-04', 0, 1, 26, 130.00, 0),
(43, 4, '2023-10-25', 0, 1, 25, 130.00, 0),
(44, 4, '2024-01-10', 0, 1, 24, 130.00, 0),
(45, 4, '2024-04-16', 0, 1, 23, 130.00, 0),
(46, 4, '2024-05-27', 0, 1, 22, 130.00, 0),
(47, 4, '2024-09-10', 0, 1, 21, 130.00, 0),
(48, 4, '2024-10-16', 0, 1, 20, 130.00, 0),
(49, 4, '2025-01-08', 0, 1, 19, 130.00, 0),
(50, 10, '2021-02-23', 10, 0, 10, 48.00, 2),
(51, 10, '2021-09-20', 0, 2, 8, 48.00, 0),
(52, 10, '2022-09-20', 10, 0, 18, 50.00, 3),
(53, 10, '2023-03-22', 0, 1, 17, 50.00, 0),
(54, 10, '2024-08-12', 0, 1, 16, 50.00, 0),
(55, 5, '2021-02-23', 10, 0, 10, 68.00, 3),
(56, 5, '2022-04-07', 0, 1, 9, 68.00, 0),
(57, 6, '2021-02-23', 20, 0, 20, 68.00, 3),
(58, 6, '2022-04-07', 0, 1, 19, 68.00, 0),
(59, 6, '2022-04-28', 0, 1, 18, 68.00, 0),
(60, 6, '2023-04-13', 0, 1, 17, 68.00, 0),
(61, 6, '2023-06-08', 0, 1, 16, 68.00, 0),
(62, 6, '2024-04-16', 0, 1, 15, 68.00, 0),
(63, 6, '2024-09-10', 0, 1, 14, 68.00, 0),
(64, 7, '2021-02-23', 4, 0, 4, 68.00, 2),
(65, 7, '2021-03-16', 0, 1, 3, 68.00, 0),
(66, 12, '2021-02-23', 5, 1, 4, 120.00, 3),
(67, 12, '2021-04-21', 0, 1, 3, 120.00, 0),
(68, 12, '2021-06-07', 0, 1, 2, 120.00, 0),
(69, 11, '2021-05-26', 10, 3, 7, 18.00, 3),
(70, 11, '2021-05-26', 0, 1, 6, 18.00, 0),
(71, 11, '2021-08-03', 0, 1, 5, 18.00, 0),
(72, 11, '2021-08-02', 0, 1, 4, 18.00, 0),
(73, 11, '2021-08-16', 0, 1, 3, 18.00, 0),
(74, 11, '2021-09-20', 0, 1, 2, 18.00, 0),
(75, 11, '2022-01-01', 25, 0, 27, 30.00, 2),
(76, 11, '2022-08-31', 0, 1, 26, 30.00, 0),
(77, 11, '2022-10-06', 0, 1, 25, 30.00, 0),
(78, 11, '2023-04-04', 0, 1, 24, 30.00, 0),
(79, 11, '2023-09-04', 0, 1, 23, 30.00, 0),
(80, 11, '2024-04-10', 0, 1, 22, 30.00, 0),
(81, 11, '2024-04-24', 0, 1, 21, 30.00, 0),
(82, 11, '2024-08-12', 0, 1, 20, 30.00, 0),
(83, 11, '2024-09-10', 0, 1, 19, 30.00, 0),
(84, 11, '2024-10-16', 0, 1, 18, 30.00, 0),
(85, 14, '2021-02-23', 3, 0, 3, 480.00, 3),
(86, 14, '2021-02-23', 0, 2, 1, 480.00, 0),
(87, 15, '2021-02-23', 10, 0, 10, 580.00, 3),
(88, 15, '2021-08-06', 0, 1, 9, 580.00, 0),
(89, 15, '2021-08-18', 1, 1, 9, 580.00, 2),
(90, 15, '2024-03-20', 0, 1, 8, 580.00, 0),
(91, 15, '2024-05-10', 0, 1, 7, 580.00, 0),
(92, 16, '2021-06-24', 11, 0, 11, 120.00, 2),
(93, 16, '2022-07-12', 0, 1, 10, 120.00, 0),
(94, 17, '2021-06-24', 8, 0, 8, 20.00, 2),
(95, 17, '2022-07-12', 0, 1, 7, 20.00, 0),
(96, 18, '2021-06-24', 2, 0, 2, 100.00, 2),
(97, 19, '2021-06-24', 2, 0, 2, 96.00, 0),
(98, 20, '2020-03-12', 10, 0, 10, 80.00, 2),
(99, 20, '2021-11-25', 0, 2, 8, 80.00, 0),
(100, 20, '2022-06-07', 0, 2, 6, 80.00, 0),
(101, 21, '2020-03-12', 30, 0, 30, 5.00, 4),
(102, 21, '2020-03-12', 0, 5, 25, 5.00, 0),
(103, 21, '2020-03-12', 0, 1, 24, 5.00, 0),
(104, 21, '2020-03-24', 0, 1, 23, 5.00, 0),
(105, 21, '2020-03-24', 0, 1, 22, 5.00, 0),
(106, 21, '2020-03-24', 0, 1, 21, 5.00, 0),
(107, 21, '2020-03-24', 0, 1, 20, 5.00, 0),
(108, 21, '2022-03-08', 0, 1, 19, 5.00, 0),
(109, 21, '2022-03-24', 0, 1, 18, 5.00, 0),
(110, 22, '2019-11-01', 4, 0, 4, 75.00, 2),
(111, 22, '2019-11-01', 0, 1, 3, 75.00, 0),
(112, 22, '2019-11-01', 0, 3, 0, 75.00, 0),
(113, 22, '2020-03-12', 6, 0, 6, 100.00, 4),
(114, 22, '2020-03-24', 0, 1, 5, 100.00, 0),
(115, 22, '2020-03-24', 0, 1, 4, 100.00, 0),
(116, 22, '2020-03-24', 0, 1, 3, 100.00, 0),
(117, 23, '2021-06-24', 2, 0, 2, 40.00, 2),
(118, 23, '2023-04-17', 0, 1, 1, 40.00, 0),
(119, 24, '2021-06-24', 20, 0, 20, 25.00, 2),
(120, 24, '2023-09-04', 0, 20, 0, 25.00, 0),
(121, 25, '2019-11-07', 100, 0, 100, 10.00, 4),
(122, 25, '2019-11-07', 0, 2, 98, 10.00, 0),
(123, 25, '2019-11-07', 0, 1, 97, 10.00, 0),
(124, 25, '2019-11-07', 0, 1, 96, 10.00, 0),
(125, 25, '2019-11-29', 0, 1, 95, 10.00, 0),
(126, 25, '2019-11-29', 0, 1, 94, 10.00, 0),
(127, 25, '2019-11-29', 0, 1, 93, 10.00, 0),
(128, 25, '2019-11-29', 0, 1, 92, 10.00, 0),
(129, 25, '2019-12-03', 0, 1, 91, 10.00, 0),
(130, 25, '2019-12-12', 0, 2, 89, 10.00, 0),
(131, 25, '2020-01-08', 0, 1, 88, 10.00, 0),
(132, 25, '2020-01-20', 0, 1, 87, 10.00, 0),
(133, 25, '2020-01-22', 0, 1, 86, 10.00, 0),
(134, 25, '2020-02-19', 0, 6, 80, 10.00, 0),
(135, 25, '2020-02-19', 0, 2, 78, 10.00, 0),
(136, 25, '2020-02-19', 0, 1, 77, 10.00, 0),
(137, 25, '2020-02-19', 0, 1, 76, 10.00, 0),
(138, 25, '2020-10-20', 0, 1, 75, 10.00, 0),
(139, 25, '2020-10-20', 0, 1, 74, 10.00, 0),
(140, 25, '2020-10-20', 0, 1, 73, 10.00, 0),
(141, 25, '2020-11-02', 0, 1, 72, 10.00, 0),
(142, 25, '2021-02-08', 0, 1, 71, 10.00, 0),
(143, 25, '2021-02-08', 0, 16, 55, 10.00, 0),
(144, 25, '2021-02-08', 0, 5, 50, 10.00, 0),
(145, 25, '2021-02-08', 0, 1, 49, 10.00, 0),
(146, 25, '2021-04-16', 0, 1, 48, 10.00, 0),
(147, 25, '2021-06-22', 0, 1, 47, 10.00, 0),
(148, 25, '2021-07-12', 0, 2, 45, 10.00, 0),
(149, 25, '2021-09-16', 0, 2, 43, 10.00, 0),
(150, 25, '2021-10-25', 0, 1, 42, 10.00, 0),
(151, 25, '2021-11-01', 0, 1, 41, 10.00, 0),
(152, 25, '2021-11-01', 0, 4, 37, 10.00, 0),
(153, 25, '2021-11-20', 0, 1, 36, 10.00, 0),
(154, 25, '2022-12-06', 0, 2, 34, 10.00, 0),
(155, 25, '2022-04-03', 0, 2, 32, 10.00, 0),
(156, 25, '2022-05-05', 0, 2, 30, 10.00, 0),
(157, 25, '2022-07-20', 0, 1, 29, 10.00, 0),
(158, 25, '2022-08-31', 0, 3, 26, 10.00, 0),
(159, 25, '2023-06-26', 0, 2, 24, 10.00, 0),
(160, 25, '2023-08-06', 0, 1, 23, 10.00, 0),
(161, 25, '2023-10-27', 0, 1, 22, 10.00, 0),
(162, 25, '2023-10-27', 0, 3, 19, 10.00, 0),
(163, 25, '2024-02-26', 0, 3, 16, 10.00, 0),
(164, 25, '2024-03-04', 0, 2, 14, 10.00, 0),
(165, 25, '2024-03-20', 0, 1, 13, 10.00, 0),
(166, 25, '2024-05-27', 0, 2, 11, 10.00, 0),
(167, 25, '2024-06-15', 0, 2, 9, 10.00, 0),
(168, 25, '2024-06-20', 0, 1, 8, 10.00, 0),
(169, 26, '2021-06-24', 3, 0, 3, 157.00, 2),
(170, 27, '2021-06-24', 13, 0, 13, 70.00, 2),
(171, 28, '2021-02-23', 1, 0, 1, 450.00, 3),
(172, 29, '2023-09-04', 1, 0, 1, 70.00, 2),
(173, 30, '2023-09-04', 2, 0, 2, 27.00, 2),
(174, 31, '2023-06-27', 7, 0, 7, 750.00, 5),
(175, 31, '2023-06-27', 0, 2, 5, 750.00, 0),
(176, 31, '2023-11-24', 0, 1, 4, 750.00, 0),
(177, 31, '2024-03-01', 0, 1, 3, 750.00, 0),
(178, 31, '2024-04-24', 0, 1, 2, 750.00, 0),
(179, 31, '2024-09-30', 0, 1, 1, 750.00, 0),
(180, 31, '2024-11-11', 0, 1, 0, 750.00, 0),
(181, 32, '2021-08-18', 1680, 0, 1680, 20.00, 6),
(182, 32, '2021-09-16', 0, 120, 1560, 20.00, 0),
(183, 32, '2021-09-22', 0, 80, 1480, 20.00, 0),
(184, 32, '2021-09-28', 0, 56, 1424, 0.00, 0),
(185, 32, '2021-10-21', 0, 12, 1412, 0.00, 0),
(186, 32, '2021-11-10', 0, 5, 1407, 20.00, 0),
(187, 32, '2021-11-17', 0, 120, 1287, 20.00, 0),
(188, 32, '2021-12-01', 0, 3, 1284, 0.00, 0),
(189, 32, '2021-12-07', 0, 3, 1281, 20.00, 0),
(190, 32, '2021-12-08', 0, 24, 1257, 20.00, 0),
(191, 32, '2021-12-08', 0, 6, 1251, 20.00, 0),
(192, 32, '2021-12-08', 0, 3, 1248, 20.00, 0),
(193, 32, '2021-12-08', 0, 3, 1245, 20.00, 0),
(194, 32, '2021-12-21', 0, 3, 1242, 20.00, 0),
(195, 32, '2022-01-06', 0, 3, 1239, 0.00, 0),
(196, 32, '2022-01-14', 0, 36, 1203, 20.00, 0),
(197, 32, '2022-02-23', 0, 15, 1188, 20.00, 0),
(198, 32, '2022-02-25', 0, 120, 1068, 20.00, 0),
(199, 32, '2022-03-02', 0, 4, 1064, 20.00, 0),
(200, 32, '2022-03-10', 0, 60, 1004, 20.00, 0),
(201, 32, '2022-03-21', 0, 4, 1000, 20.00, 0),
(202, 32, '2022-03-25', 0, 24, 976, 20.00, 0),
(203, 32, '2022-04-27', 0, 200, 776, 20.00, 0),
(204, 32, '2022-04-28', 0, 3, 773, 20.00, 0),
(205, 32, '2022-04-28', 0, 3, 770, 20.00, 0),
(206, 32, '2022-05-10', 0, 30, 740, 20.00, 0),
(207, 32, '2022-06-02', 0, 100, 640, 20.00, 0),
(208, 32, '2022-06-02', 0, 12, 628, 0.00, 0),
(209, 32, '2022-06-03', 0, 5, 623, 20.00, 0),
(210, 32, '2022-07-14', 0, 3, 620, 0.00, 0),
(211, 32, '2022-07-28', 0, 36, 584, 20.00, 0),
(212, 32, '2022-08-16', 0, 22, 562, 20.00, 0),
(213, 32, '2022-08-16', 0, 24, 538, 20.00, 0),
(214, 32, '2022-09-13', 0, 24, 514, 20.00, 0),
(215, 32, '2022-09-13', 0, 24, 490, 20.00, 0),
(216, 32, '2022-09-13', 0, 24, 466, 20.00, 0),
(217, 32, '2022-09-27', 0, 36, 430, 20.00, 0),
(218, 32, '2022-09-27', 0, 72, 358, 20.00, 0),
(219, 32, '2022-09-29', 0, 25, 333, 20.00, 0),
(220, 32, '2022-09-30', 0, 8, 325, 20.00, 0),
(221, 32, '2022-10-03', 0, 17, 308, 20.00, 0),
(222, 32, '2022-10-12', 0, 99, 209, 20.00, 0),
(223, 32, '2022-10-12', 0, 3, 206, 0.00, 0),
(224, 32, '2022-11-02', 0, 8, 198, 20.00, 0),
(225, 32, '2023-01-12', 0, 6, 192, 20.00, 0),
(226, 32, '2023-01-31', 0, 4, 188, 0.00, 0),
(227, 32, '2023-02-01', 0, 50, 138, 20.00, 0),
(228, 32, '2023-02-20', 0, 6, 132, 20.00, 0),
(229, 32, '2023-03-10', 0, 30, 102, 30.00, 0),
(230, 32, '2023-03-10', 0, 10, 92, 30.00, 0),
(231, 32, '2023-03-15', 0, 2, 90, 30.00, 0),
(232, 32, '2023-04-04', 0, 4, 86, 30.00, 0),
(233, 32, '2023-09-08', 0, 74, 12, 30.00, 0),
(234, 32, '2023-11-27', 0, 4, 8, 30.00, 0),
(235, 32, '2023-11-27', 0, 3, 5, 30.00, 0),
(236, 32, '2024-05-10', 0, 4, 1, 30.00, 0),
(237, 32, '2024-05-10', 0, 1, 0, 30.00, 0),
(238, 33, '2022-09-13', 300, 0, 300, 50.00, 7),
(239, 33, '2022-09-13', 0, 70, 230, 50.00, 0),
(240, 33, '2022-09-13', 0, 15, 215, 50.00, 0),
(241, 33, '2022-09-28', 0, 5, 210, 50.00, 0),
(242, 33, '2022-09-29', 0, 2, 208, 50.00, 0),
(243, 33, '2022-09-29', 0, 5, 203, 50.00, 0),
(244, 33, '2022-10-05', 0, 15, 188, 50.00, 0),
(245, 33, '2022-10-06', 0, 5, 183, 50.00, 0),
(246, 33, '2022-10-10', 0, 1, 182, 50.00, 0),
(247, 33, '2022-10-12', 0, 20, 162, 50.00, 0),
(248, 33, '2022-10-12', 0, 2, 160, 50.00, 0),
(249, 33, '2022-10-12', 0, 1, 159, 50.00, 0),
(250, 33, '2022-10-12', 0, 1, 158, 50.00, 0),
(251, 33, '2022-11-02', 0, 2, 156, 50.00, 0),
(252, 33, '2023-01-12', 0, 1, 155, 50.00, 0),
(253, 33, '2023-02-01', 0, 20, 135, 50.00, 0),
(254, 33, '2023-02-01', 0, 28, 107, 50.00, 0),
(255, 33, '2023-02-06', 0, 2, 105, 50.00, 0),
(256, 33, '2023-03-10', 0, 3, 102, 70.00, 0),
(257, 33, '2023-03-10', 0, 20, 82, 70.00, 0),
(258, 33, '2023-03-15', 0, 1, 81, 70.00, 0),
(259, 33, '2023-03-29', 0, 1, 80, 70.00, 0),
(260, 33, '2023-04-04', 0, 1, 79, 70.00, 0),
(261, 33, '2023-06-08', 0, 1, 78, 70.00, 0),
(262, 33, '2023-09-02', 0, 7, 71, 70.00, 0),
(263, 33, '2023-09-08', 0, 29, 42, 70.00, 0),
(264, 33, '2024-05-10', 0, 2, 40, 70.00, 0),
(265, 33, '2024-05-10', 0, 1, 39, 70.00, 0),
(266, 33, '2024-09-10', 0, 4, 35, 70.00, 0),
(267, 33, '2024-10-10', 0, 3, 32, 90.00, 0),
(268, 33, '2024-10-10', 0, 1, 31, 100.00, 0),
(269, 33, '2025-01-27', 0, 5, 26, 100.00, 0);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `supplier_id` int(11) NOT NULL,
  `supplier_name` varchar(255) NOT NULL,
  `contact_info` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`supplier_id`, `supplier_name`, `contact_info`) VALUES
(1, 'Keanet Creation', '+233 54 627 5355'),
(2, 'GSA Cash Purchase', '+233 30 273 2605'),
(3, 'Omega Compu System', '+233 54 827 3779'),
(4, 'State Link', '+233 20 871 8617'),
(5, 'Hub Computers', '+233 24 352 2383'),
(6, 'GTP', '+233 20 438 0021'),
(7, 'Fonts Printing', '+233 24 466 7157');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','storekeeper','viewer') DEFAULT 'viewer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `full_name`, `password`, `role`, `created_at`) VALUES
(1, 'daniel', 'Daniel Kojo Gidi', '$2y$10$JSLxF/WVG3IbhdFroe/YN.Gsemvj5baq1ah48Xot8j/mfvYbI7Fiu', 'admin', '2025-03-04 14:31:02'),
(2, 'dan', 'Dan Gidi', '$2y$10$4Uf9iM/MZgonODwdjPH2..fZ5.Hn4I.z9AeNyhxdX6dS/lyl.EBmS', 'storekeeper', '2025-03-04 14:32:24'),
(3, 'dee', 'Dane Dee', '$2y$10$fpgp6PF8JUarQGabwX3tM.6T7w3kzJMZVbJISU71sGkqe9mohb3qi', 'viewer', '2025-03-04 14:32:58'),
(4, 'rama', 'Ramatu Balah', '$2y$10$qIvqATO6pE2p5hOXHG3pMOdU8hBVwjtwapgK1TJyd289GxWIJ5M0G', 'admin', '2025-03-28 15:13:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `issued_stock`
--
ALTER TABLE `issued_stock`
  ADD PRIMARY KEY (`issue_id`),
  ADD UNIQUE KEY `store_issue_voucher` (`store_issue_voucher`),
  ADD UNIQUE KEY `store_issue_voucher_2` (`store_issue_voucher`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `issued_by` (`issued_by`),
  ADD KEY `fk_request_id` (`request_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`),
  ADD UNIQUE KEY `item_name` (`item_name`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `dismissed_by` (`dismissed_by`);

--
-- Indexes for table `received_stock`
--
ALTER TABLE `received_stock`
  ADD PRIMARY KEY (`received_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `received_by` (`received_by`);

--
-- Indexes for table `requisition_requests`
--
ALTER TABLE `requisition_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `requested_by` (`requested_by`);

--
-- Indexes for table `store_records`
--
ALTER TABLE `store_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supplier_id`),
  ADD UNIQUE KEY `supplier_name` (`supplier_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `issued_stock`
--
ALTER TABLE `issued_stock`
  MODIFY `issue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=240;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `received_stock`
--
ALTER TABLE `received_stock`
  MODIFY `received_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT for table `requisition_requests`
--
ALTER TABLE `requisition_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `store_records`
--
ALTER TABLE `store_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=270;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `issued_stock`
--
ALTER TABLE `issued_stock`
  ADD CONSTRAINT `fk_request_id` FOREIGN KEY (`request_id`) REFERENCES `requisition_requests` (`request_id`),
  ADD CONSTRAINT `issued_stock_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `issued_stock_ibfk_2` FOREIGN KEY (`issued_by`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`) ON DELETE SET NULL;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`dismissed_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `received_stock`
--
ALTER TABLE `received_stock`
  ADD CONSTRAINT `received_stock_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `received_stock_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `received_stock_ibfk_3` FOREIGN KEY (`received_by`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `requisition_requests`
--
ALTER TABLE `requisition_requests`
  ADD CONSTRAINT `requisition_requests_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`),
  ADD CONSTRAINT `requisition_requests_ibfk_2` FOREIGN KEY (`requested_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `store_records`
--
ALTER TABLE `store_records`
  ADD CONSTRAINT `store_records_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
