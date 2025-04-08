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
(1, 13, 1, 30.00, '2021-02-23', 'GSA Stores', 'SC\'s Office', 'SIV-000001', 1, 'Allocated to SC', 'Pending', NULL, NULL);


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
(1, 3, 'New request received from viewer', 1, '2025-03-18', 1);


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
(1, 13, 3, 7, 30.00, '2021-02-23', 1, 'GSA Stores');


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
(1, 13, 1, 3, '2025-03-19', 'Rejected', 'Office Use');

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
(1, 13, '2021-02-23', 7, 0, 7, 30.00, 3);


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
(1, 'Keanet Creation', '+233 54 627 5355');


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
(1, 'dan', 'Dan Gidi', '$2y$10$4Uf9iM/MZgonODwdjPH2..fZ5.Hn4I.z9AeNyhxdX6dS/lyl.EBmS', 'storekeeper', '2025-03-04 14:32:24'),
(2, 'dee', 'Dane Dee', '$2y$10$fpgp6PF8JUarQGabwX3tM.6T7w3kzJMZVbJISU71sGkqe9mohb3qi', 'viewer', '2025-03-04 14:32:58');

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
