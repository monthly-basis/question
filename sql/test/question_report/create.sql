CREATE TABLE `question_report` (
  `question_report_id` int unsigned NOT NULL AUTO_INCREMENT,
  `question_id` int unsigned NOT NULL,
  `user_id` int unsigned DEFAULT NULL,
  `reason` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_status_id` tinyint(1) NOT NULL DEFAULT '0',
  `created_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modified_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`question_report_id`),
  UNIQUE `question_id_user_id` (`question_id`, `user_id`),
  UNIQUE `question_id_created_ip` (`question_id`, `created_ip`),
  FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
