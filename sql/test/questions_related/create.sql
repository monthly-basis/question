CREATE TABLE `questions_related` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `question_id` int unsigned NOT NULL,
  `related_question_id` int unsigned NOT NULL,
  `score` FLOAT(10,6) unsigned NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `question_id_related_question_id` (`question_id`, `related_question_id`),
  FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`) ON DELETE CASCADE,
  FOREIGN KEY (`related_question_id`) REFERENCES `question` (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
