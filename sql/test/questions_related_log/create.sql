CREATE TABLE `questions_related_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `question_id` int unsigned NOT NULL,
  `misses` int unsigned DEFAULT NULL,
  `retrieved_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `question_id` (`question_id`),
  KEY `misses_retrieved_at` (`misses`, `retrieved_at`),
  FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
