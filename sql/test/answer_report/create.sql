CREATE TABLE `answer_report` (
  `answer_report_id` int unsigned NOT NULL AUTO_INCREMENT,
  `answer_id` int unsigned NOT NULL,
  `user_id` int unsigned DEFAULT NULL,
  `reason` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_status_id` tinyint(1) NOT NULL DEFAULT '0',
  `modified_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`answer_report_id`),
  UNIQUE `answer_id_user_id` (`answer_id`, `user_id`),
  UNIQUE `answer_id_created_ip` (`answer_id`, `created_ip`),
  FOREIGN KEY (`answer_id`) REFERENCES `answer` (`answer_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
