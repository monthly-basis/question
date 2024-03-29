CREATE TABLE `answer` (
    `answer_id` int(10) unsigned auto_increment,
    `question_id` int(10) unsigned not null,
    `user_id` int(10) unsigned default null, #@todo Rename column to `created_user_id`
    `message` text,
    `imported` tinyint(1) unsigned NOT NULL DEFAULT '0',
    `created_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_name` varchar(255) COLLATE utf8mb4_0900_as_cs DEFAULT NULL, #@todo Rename column to `name`
    `created_ip` varchar(45) default null,
    `modified_datetime` DATETIME DEFAULT NULL,
    `modified_user_id` INT(10) UNSIGNED DEFAULT NULL,
    `modified_reason` varchar(255) DEFAULT NULL,
    `deleted_datetime` datetime default null,
    `deleted_user_id` int(10) default null,
    `deleted_reason` varchar(255) default null,
    PRIMARY KEY (`answer_id`),
    KEY `question_id_user_id_deleted_datetime` (`question_id`, `user_id`, `deleted_datetime`),
    KEY `question_id_deleted_datetime_created_datetime` (`question_id`, `deleted_datetime`, `created_datetime`),
    KEY `user_id` (`user_id`),
    KEY `user_id_deleted_datetime_created_datetime_answer_id` (`user_id`, `deleted_datetime`, `created_datetime`, `answer_id`),
    KEY `created_datetime_and_4_more_columns` (`created_datetime`,`created_ip`,`deleted_datetime`,`deleted_user_id`,`deleted_reason`),
    KEY `created_name_deleted_datetime_created_datetime` (`created_name`, `deleted_datetime`, `created_datetime`),
    KEY `created_ip_created_datetime` (`created_ip`, `created_datetime`),
    KEY `created_ip_and_3_more_columns` (`created_ip`, `deleted_datetime`, `deleted_user_id`, `deleted_reason`),
    KEY `deleted_datetime_created_datetime` (`deleted_datetime`, `created_datetime`),
    KEY `deleted_user_id_deleted_datetime` (`deleted_user_id`, `deleted_datetime`)
) CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
