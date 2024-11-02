CREATE TABLE `question_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `question_id` int unsigned NOT NULL,
  `category_id_1` int unsigned NOT NULL,
  `category_id_2` int unsigned DEFAULT NULL,
  `category_id_3` int unsigned DEFAULT NULL,
  `question_created_datetime_cached` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `question_id` (`question_id`),
  KEY `c1_qcdc_desc` (category_id_1, `question_created_datetime_cached` DESC),
  KEY `category_id_1_category_id_2_question_created_datetime_cached` (`category_id_1`, `category_id_2`, `question_created_datetime_cached` DESC),
  KEY `ci1_ci2_ci3_question_created_datetime_cached_desc` (`category_id_1`, `category_id_2`, `category_id_3`, `question_created_datetime_cached` DESC),
  FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`),
  FOREIGN KEY (`category_id_1`) REFERENCES `category` (`category_id`),
  FOREIGN KEY (`category_id_2`) REFERENCES `category` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
