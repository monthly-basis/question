CREATE TABLE `answer` (
    `answer_id` int(10) unsigned auto_increment,
    `question_id` int(10) unsigned not null,
    `user_id` int(10) unsigned default null,
    `message` text,
    `ip` varchar(45) default null,
    `created_datetime` datetime NOT NULL,
    `created_name` varchar(255) default null,
    `created_ip` varchar(45) default null,
    `modified_datetime` DATETIME DEFAULT NULL,
    `modified_user_id` INT(10) UNSIGNED DEFAULT NULL,
    `deleted` datetime default null,
    `deleted_datetime` datetime default null,
    `deleted_user_id` int(10) default null,
    `deleted_reason` varchar(255) default null,
    PRIMARY KEY (`answer_id`),
    KEY `question_id_deleted_created_datetime` (`question_id`, `deleted`, `created_datetime`),
    KEY `question_id_deleted_datetime_created_datetime` (`question_id`, `deleted_datetime`, `created_datetime`),
    KEY `user_id` (`user_id`),
    KEY `ip` (`ip`),
    KEY `created_name_deleted_created_datetime` (`created_name`, `deleted`, `created_datetime`),
    KEY `created_name_deleted_datetime_created_datetime` (`created_name`, `deleted_datetime`, `created_datetime`),
    KEY `created_ip_created_datetime` (`created_ip`, `created_datetime`),
    KEY `deleted_created_datetime` (`deleted`, `created_datetime`),
    KEY `deleted_datetime_created_datetime` (`deleted_datetime`, `created_datetime`),
    KEY `deleted_user_id_deleted` (`deleted_user_id`, `deleted`),
    KEY `deleted_user_id_deleted_datetime` (`deleted_user_id`, `deleted_datetime`)
) CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
