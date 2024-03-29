CREATE TABLE `log_question_view` (
    `question_view_not_bot_log_id` int(10) unsigned auto_increment,
    `question_id` int(10) unsigned NOT NULL,
    `ip` varchar(45) NOT NULL,
    `server_http_accept_language` varchar(255) DEFAULT NULL,
    `server_http_referer` varchar(255) DEFAULT NULL,
    `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`question_view_not_bot_log_id`),
    KEY `created_question_id_ip` (`created`, `question_id`, `ip`),
    KEY `question_id` (`question_id`),
    KEY `server_http_referer` (`server_http_referer`),
    KEY `ip` (`ip`),
    CONSTRAINT FOREIGN KEY (`question_id`)
        REFERENCES `question` (`question_id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
