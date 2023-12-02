-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 01, 2023 at 08:37 PM
-- Server version: 10.6.12-MariaDB-0ubuntu0.22.04.1
-- PHP Version: 8.1.2-1ubuntu2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mvw6sfb_c`
--

-- --------------------------------------------------------

--
-- Table structure for table `created_by`
--

CREATE TABLE `created_by` (
  `recipe_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `created_by`
--

INSERT INTO `created_by` (`recipe_id`, `user_id`) VALUES
(1, 12),
(2, 12),
(3, 12),
(4, 12),
(5, 12),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10),
(11, 1),
(12, 2),
(13, 3),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(29, 12);

-- --------------------------------------------------------

--
-- Table structure for table `ingredients_amounts`
--

CREATE TABLE `ingredients_amounts` (
  `recipe_id` int(11) DEFAULT NULL,
  `ingredient_id` int(11) DEFAULT NULL,
  `amount_id` int(11) NOT NULL,
  `unit` varchar(20) DEFAULT NULL,
  `value` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ingredients_amounts`
--

INSERT INTO `ingredients_amounts` (`recipe_id`, `ingredient_id`, `amount_id`, `unit`, `value`) VALUES
(1, 1, 1, 'cup', 3),
(1, 2, 2, 'cup', 1),
(1, 3, 3, 'unit', 3),
(2, 4, 4, 'lb', 1),
(2, 5, 5, 'can', 1),
(3, 7, 6, 'unit', 4),
(3, 8, 7, 'cup', 1),
(4, 10, 8, 'block', 1),
(4, 11, 9, 'pcs', 2),
(4, 12, 10, 'tbsp', 3),
(5, 13, 11, 'cups', 2),
(5, 14, 12, 'cups', 2),
(5, 15, 13, 'units', 2),
(6, 16, 14, 'cups', 2),
(6, 17, 15, 'sheets', 2),
(6, 18, 16, 'pcs', 3),
(7, 19, 17, 'head', 1),
(7, 20, 18, 'cups', 2),
(7, 21, 19, 'grams', 50),
(8, 22, 20, 'pcs', 2),
(8, 23, 21, 'tbsp', 4),
(8, 24, 22, 'tsp', 1),
(9, 25, 23, 'cans', 2),
(9, 26, 24, 'cups', 2),
(9, 27, 25, 'cup', 1),
(10, 28, 26, 'pcs', 4),
(10, 29, 27, 'tbsp', 3),
(10, 30, 28, 'can', 1),
(11, 31, 29, 'balls', 1),
(11, 32, 30, 'cups', 1),
(11, 33, 31, 'cups', 1),
(12, 34, 32, 'lbs', 1),
(12, 35, 33, 'cups', 2),
(12, 36, 34, 'cup', 1),
(13, 37, 35, 'pack', 1),
(13, 38, 36, 'cups', 1),
(13, 39, 37, 'cups', 2),
(29, 55, 53, 'whole', 2),
(29, 56, 54, 'ounces', 10),
(29, 57, 55, 'teaspoons', 3);

--
-- Triggers `ingredients_amounts`
--
DELIMITER $$
CREATE TRIGGER `log_ingredient_amounts_changes` BEFORE UPDATE ON `ingredients_amounts` FOR EACH ROW BEGIN
    IF NEW.unit != OLD.unit OR NEW.value != OLD.value THEN
        INSERT INTO ingredients_amounts_audit (
            ingredient_amount_id,
            unit_before,
            value_before,
            unit_after,
            value_after,
            event_timestamp,
            username
        )
        VALUES (
            OLD.ingredient_id,
            OLD.unit,
            OLD.value,
            NEW.unit,
            NEW.value,
            NOW(),
            USER()
        );
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `ingredients_amounts_audit`
--

CREATE TABLE `ingredients_amounts_audit` (
  `id` int(11) NOT NULL,
  `ingredient_amount_id` int(11) DEFAULT NULL,
  `unit_before` varchar(20) DEFAULT NULL,
  `value_before` varchar(10) DEFAULT NULL,
  `unit_after` varchar(20) DEFAULT NULL,
  `value_after` varchar(10) DEFAULT NULL,
  `event_timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `username` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ingredients_amounts_audit`
--

INSERT INTO `ingredients_amounts_audit` (`id`, `ingredient_amount_id`, `unit_before`, `value_before`, `unit_after`, `value_after`, `event_timestamp`, `username`) VALUES
(1, 1, 'cup', '2', 'cup', '3', '2023-11-02 01:28:58', 'ft9kr@localhost');

-- --------------------------------------------------------

--
-- Table structure for table `recipe`
--

CREATE TABLE `recipe` (
  `recipe_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipe`
--

INSERT INTO `recipe` (`recipe_id`, `title`, `description`) VALUES
(1, 'Chocolate Cake', 'A simple and delicious chocolate cake recipe'),
(2, 'Spaghetti Bolognese', 'A classic Italian dish with ground beef and tomato sauce'),
(3, 'Tacos', 'Spicy and tangy Mexican Tacos'),
(4, 'Vegan Stir Fry', 'A quick and easy vegan stir-fry'),
(5, 'Pancakes', 'Fluffy American pancakes'),
(6, 'Sushi Rolls', 'Homemade sushi rolls with various fillings'),
(7, 'Caesar Salad', 'Classic Caesar salad with croutons and parmesan'),
(8, 'BBQ Chicken', 'Grilled chicken with BBQ sauce'),
(9, 'Clam Chowder', 'Creamy New England clam chowder'),
(10, 'Chicken Curry', 'Spicy chicken curry with vegetables'),
(11, 'Pizza', 'Homemade pizza with your choice of toppings'),
(12, 'Lobster Bisque', 'Rich and creamy lobster soup'),
(13, 'Cheesecake', 'New York-style cheesecake'),
(14, 'Pasta and Red Sauce', 'Delicious pasta'),
(15, 'Pasta and Red Sauce', 'Delicious pasta'),
(16, 'Pasta and Red Sauce', 'Delicious pasta'),
(17, 'kklk', 'klk;lk;lkl;kl;k'),
(18, 'wer', 'powetoo'),
(19, 'test table name recipe_directions', 'werwerwerwe'),
(20, 'test table name recipe_directions', 'werwer'),
(21, 'test for video', 'wewer'),
(23, 'Chicken Adobo', 'test'),
(29, 'Fries', 'Crispy, Salty Fries');

-- --------------------------------------------------------

--
-- Table structure for table `recipe_directions`
--

CREATE TABLE `recipe_directions` (
  `recipe_id` int(11) DEFAULT NULL,
  `direction_id` int(11) NOT NULL,
  `instruction` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipe_directions`
--

INSERT INTO `recipe_directions` (`recipe_id`, `direction_id`, `instruction`) VALUES
(1, 1, 'Mix flour and cocoa'),
(1, 2, 'Add eggs'),
(1, 3, 'Bake for 30 minutes'),
(2, 4, 'Cook spaghetti'),
(2, 5, 'Prepare beef'),
(2, 6, 'Mix with sauce'),
(3, 7, 'Prepare tortillas'),
(3, 8, 'Cook beef'),
(3, 9, 'Add salsa and serve'),
(4, 10, 'Fry tofu until golden'),
(4, 11, 'Add bell pepper and stir-fry'),
(4, 12, 'Add soy sauce and mix'),
(5, 13, 'Mix dry ingredients'),
(5, 14, 'Add milk and eggs'),
(5, 15, 'Fry on a pan'),
(6, 16, 'Prepare rice'),
(6, 17, 'Roll with seaweed and fish'),
(6, 18, 'Slice and serve'),
(7, 19, 'Mix lettuce and croutons'),
(7, 20, 'Add parmesan and dressing'),
(7, 21, 'Toss and serve'),
(8, 22, 'Marinate chicken in BBQ sauce'),
(8, 23, 'Grill until cooked'),
(8, 24, 'Season and serve'),
(9, 25, 'Cook clam and potatoes'),
(9, 26, 'Add cream and simmer'),
(9, 27, 'Serve hot'),
(10, 28, 'Fry chicken pieces'),
(10, 29, 'Add curry paste and coconut milk'),
(10, 30, 'Simmer until done'),
(11, 31, 'Roll out dough'),
(11, 32, 'Add cheese and sauce'),
(11, 33, 'Bake in oven'),
(12, 34, 'Cook lobster'),
(12, 35, 'Add cream and wine'),
(12, 36, 'Simmer and blend'),
(13, 37, 'Mix cream cheese and sugar'),
(13, 38, 'Prepare crust with graham crackers'),
(13, 39, 'Bake and cool'),
(19, 40, 'werwerwer'),
(20, 41, 'werwerwer'),
(21, 42, 'qweqwe'),
(29, 53, 'Cut and peel potatoes. '),
(29, 54, 'Heat up oil to 100 degrees celcius. Fry potatoes.'),
(29, 55, 'Take potatoes out of oil and sprinkle salt.');

-- --------------------------------------------------------

--
-- Table structure for table `recipe_ingredients`
--

CREATE TABLE `recipe_ingredients` (
  `recipe_id` int(11) DEFAULT NULL,
  `ingredient_id` int(11) NOT NULL,
  `ingredient_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipe_ingredients`
--

INSERT INTO `recipe_ingredients` (`recipe_id`, `ingredient_id`, `ingredient_name`) VALUES
(1, 1, 'Flour'),
(1, 2, 'Cocoa powder'),
(1, 3, 'Egg'),
(2, 4, 'Spaghetti'),
(2, 5, 'Ground beef'),
(2, 6, 'Tomato sauce'),
(3, 7, 'Tortilla'),
(3, 8, 'Beef'),
(3, 9, 'Salsa'),
(4, 10, 'Tofu'),
(4, 11, 'Bell Pepper'),
(4, 12, 'Soy Sauce'),
(5, 13, 'Flour'),
(5, 14, 'Milk'),
(5, 15, 'Egg'),
(6, 16, 'Rice'),
(6, 17, 'Seaweed'),
(6, 18, 'Fish'),
(7, 19, 'Lettuce'),
(7, 20, 'Croutons'),
(7, 21, 'Parmesan'),
(8, 22, 'Chicken'),
(8, 23, 'BBQ Sauce'),
(8, 24, 'Salt'),
(9, 25, 'Clam'),
(9, 26, 'Potato'),
(9, 27, 'Cream'),
(10, 28, 'Chicken'),
(10, 29, 'Curry Paste'),
(10, 30, 'Coconut Milk'),
(11, 31, 'Dough'),
(11, 32, 'Cheese'),
(11, 33, 'Tomato Sauce'),
(12, 34, 'Lobster'),
(12, 35, 'Cream'),
(12, 36, 'Wine'),
(13, 37, 'Cream Cheese'),
(13, 38, 'Sugar'),
(13, 39, 'Graham Cracker'),
(20, 40, 'werkwe'),
(21, 41, 'werkwe'),
(23, 45, 'Chicken'),
(29, 55, 'Potato'),
(29, 56, 'Oil'),
(29, 57, 'Salt');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `recipe_id` int(11) DEFAULT NULL,
  `tag_id` int(11) NOT NULL,
  `tag_name` varchar(50) DEFAULT NULL,
  `type` enum('dietary restrictions','country of origin','category') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`recipe_id`, `tag_id`, `tag_name`, `type`) VALUES
(1, 1, 'Dessert', 'category'),
(1, 2, 'Vegetarian', 'dietary restrictions'),
(2, 3, 'Italian', 'country of origin'),
(2, 4, 'Main Course', 'category'),
(3, 5, 'Mexican', 'country of origin'),
(3, 6, 'Main Course', 'category'),
(4, 7, 'Vegan', 'dietary restrictions'),
(4, 8, 'Asian', 'country of origin'),
(5, 9, 'Breakfast', 'category'),
(5, 10, 'American', 'country of origin'),
(6, 11, 'Japanese', 'country of origin'),
(6, 12, 'Main Course', 'category'),
(7, 13, 'Salad', 'category'),
(7, 14, 'Italian', 'country of origin'),
(8, 15, 'Grilled', 'category'),
(8, 16, 'American', 'country of origin'),
(9, 17, 'Soup', 'category'),
(9, 18, 'American', 'country of origin'),
(10, 19, 'Spicy', 'category'),
(10, 20, 'Indian', 'country of origin'),
(11, 21, 'Main Course', 'category'),
(11, 22, 'Italian', 'country of origin'),
(12, 23, 'Soup', 'category'),
(12, 24, 'French', 'country of origin'),
(13, 25, 'Dessert', 'category'),
(13, 26, 'American', 'country of origin'),
(20, 27, 'wer', 'country of origin'),
(21, 28, 'wer', 'country of origin');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `password`, `email`) VALUES
(1, 'johnDoe123', '*A0F874BC7F54EE086FCE60A37CE7887D8B31086B', 'john.doe@example.com'),
(2, 'jane_smith', '*0D17129868A298398E203371D36A04475D455E13', 'jane.smith@email.com'),
(3, 'coolChef', '*BC9672A8EF90853A3DC466FCAFC3281FC4B46FD6', 'coolchef@foodmail.com'),
(4, 'baker_ella', '*87B6312211823BA427C0C321A019988C659E9814', 'baker.ella@baking.com'),
(5, 'spicyFoodie', '*DF5811B3AD2989F5CB82D1E1103732AD723B9D3D', 'spicy@foodieblog.com'),
(6, 'masterCook', '*9700C47EA89327FFD7A330A83621ACE9B3D923ED', 'master@cookbook.com'),
(7, 'veganVibes', '*A36F76E95B82E382A1A1B364791C82BB52F7D7A3', 'vegan@lifestyle.com'),
(8, 'seafoodLover', '*778EBEA96962CE0BD4587C6F9F8E1BEFBAB73C51', 'seafood@oceanmail.com'),
(9, 'sweetTooth', '*DA3117A34ADB3DF917AF94F888486599BAA4BC6D', 'sweet@toothcandy.com'),
(10, 'fitnessMeals', '*E0BA2C03B03B467363A94933DF4A3073CDAFE606', 'fitness@mealsplan.com'),
(12, 'marywalton', '$2y$10$lgkfsW0iVmbOf/8QhhsBT.KzYwU49yg6PZz9TSx83UGrQpn5/cvPa', 'bob@pancakes.com'),
(14, 'mvw6sfb', '$2y$10$2aCZW1rKZEh5LSXW/qqXveKlYwwHNKRc8RTRsTsXszBpIV2URjlnK', 'spanishtutoring@virginia.edu'),
(15, 'mary', '$2y$10$bKqGtgCsUgn44E1ZWe2sZ.jDDB5Hps0FIbhjC8EaIlQ7R4xYE4g1m', 'ninjawafflez516@yahoo.com');

-- --------------------------------------------------------

--
-- Table structure for table `user_stats`
--

CREATE TABLE `user_stats` (
  `user_id` int(11) DEFAULT NULL,
  `stat_id` int(11) NOT NULL,
  `averageRating` float DEFAULT NULL,
  `recipesCreated` int(11) DEFAULT NULL,
  `accountCreationTime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_stats`
--

INSERT INTO `user_stats` (`user_id`, `stat_id`, `averageRating`, `recipesCreated`, `accountCreationTime`) VALUES
(1, 1, 4.5, 10, '2022-01-01 05:00:00'),
(2, 2, 3.9, 7, '2022-02-15 17:34:56'),
(3, 3, 4.8, 15, '2022-05-20 14:20:30'),
(4, 4, 4.2, 8, '2022-03-10 19:00:00'),
(5, 5, 3.6, 5, '2022-07-22 13:18:00'),
(6, 6, 4.7, 12, '2022-08-31 00:45:00'),
(7, 7, 4, 9, '2022-09-15 21:30:00'),
(8, 8, 3.8, 6, '2022-10-05 11:15:00'),
(9, 9, 4.1, 7, '2022-11-22 02:50:00'),
(10, 10, 4.3, 11, '2022-12-15 18:30:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `created_by`
--
ALTER TABLE `created_by`
  ADD KEY `recipe_id` (`recipe_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `ingredients_amounts`
--
ALTER TABLE `ingredients_amounts`
  ADD PRIMARY KEY (`amount_id`);

--
-- Indexes for table `ingredients_amounts_audit`
--
ALTER TABLE `ingredients_amounts_audit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recipe`
--
ALTER TABLE `recipe`
  ADD PRIMARY KEY (`recipe_id`);

--
-- Indexes for table `recipe_directions`
--
ALTER TABLE `recipe_directions`
  ADD PRIMARY KEY (`direction_id`);

--
-- Indexes for table `recipe_ingredients`
--
ALTER TABLE `recipe_ingredients`
  ADD PRIMARY KEY (`ingredient_id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`tag_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_stats`
--
ALTER TABLE `user_stats`
  ADD PRIMARY KEY (`stat_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ingredients_amounts`
--
ALTER TABLE `ingredients_amounts`
  MODIFY `amount_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `ingredients_amounts_audit`
--
ALTER TABLE `ingredients_amounts_audit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `recipe`
--
ALTER TABLE `recipe`
  MODIFY `recipe_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `recipe_directions`
--
ALTER TABLE `recipe_directions`
  MODIFY `direction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `recipe_ingredients`
--
ALTER TABLE `recipe_ingredients`
  MODIFY `ingredient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `tag_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user_stats`
--
ALTER TABLE `user_stats`
  MODIFY `stat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `created_by`
--
ALTER TABLE `created_by`
  ADD CONSTRAINT `created_by_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipe` (`recipe_id`),
  ADD CONSTRAINT `created_by_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
