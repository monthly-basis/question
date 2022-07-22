CREATE TABLE `question_history` (
    `question_history_id` int(10) unsigned auto_increment,
    `question_id` int(10) unsigned not null,
    `name` varchar(255) default null,
    `subject` varchar(255) not null,
    `message` text,
    `modified_datetime` DATETIME DEFAULT NULL,
    `modified_user_id` INT(10) UNSIGNED DEFAULT NULL,
    `modified_reason` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`question_history_id`),
    KEY `question_id_modified_datetime` (`question_id`, `modified_datetime`)
) CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
