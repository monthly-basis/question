CREATE TABLE `category_question` (
  `category_question_id` int unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int unsigned NOT NULL,
  `question_id` int unsigned NOT NULL,
  `order` int unsigned NOT NULL,
  PRIMARY KEY (`category_question_id`),
  UNIQUE KEY `category_id_question_id` (`category_id`, `question_id`),
  UNIQUE KEY `question_id_order` (`question_id`, `order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
