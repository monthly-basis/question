CREATE TABLE `answer_queue` (
    `answer_queue_id` int(10) unsigned auto_increment,
    `question_id` int(10) unsigned not null,
    `name` varchar(255) default null,
    `message` text,
    `created_datetime` datetime NOT NULL,
    `created_user_id` int(10) unsigned default null,
    `created_ip` varchar(45) default null,
    `status_id` tinyint(1) signed default 0,
    `modified_datetime` DATETIME DEFAULT NULL,
    `modified_user_id` INT(10) UNSIGNED DEFAULT NULL,
    `modified_reason` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`answer_queue_id`)
) CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
