CREATE TABLE `question_search_message` (
    `question_search_message_id` int(10) NOT NULL AUTO_INCREMENT,
    `question_id` int(10) unsigned NOT NULL,
    `message` text COLLATE utf8mb4_unicode_ci,
    PRIMARY KEY (`question_search_message_id`),
    UNIQUE (`question_id`),
    FULLTEXT KEY `message` (`message`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
