CREATE TABLE `question` (
    `question_id` int(10) unsigned auto_increment,
    `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `user_id` int(10) unsigned default null, #@todo Rename column to `created_user_id`
    `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_as_cs DEFAULT NULL,
    `headline` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    `did_you_know` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    `views` int(10) unsigned NOT NULL DEFAULT '0',
    `views_one_hour` int unsigned NOT NULL DEFAULT '0',
    `views_one_day` int unsigned NOT NULL DEFAULT '0',
    `views_one_week` int unsigned NOT NULL DEFAULT '0',
    `views_one_month` int unsigned NOT NULL DEFAULT '0',
    `views_one_year` int unsigned NOT NULL DEFAULT '0',
    `views_not_bot_one_month` int(10) unsigned NOT NULL DEFAULT '0',
    `views_not_bot_one_week` int(10) unsigned NOT NULL DEFAULT '0',
    `views_not_bot_one_day` int(10) unsigned NOT NULL DEFAULT '0',
    `views_not_bot_one_hour` int(10) unsigned NOT NULL DEFAULT '0',
    `answer_count_cached` int(10) unsigned NOT NULL DEFAULT '0',
    `imported` tinyint(1) unsigned NOT NULL DEFAULT '0',
    `created_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_name` varchar(255) COLLATE utf8mb4_0900_as_cs DEFAULT NULL, #@todo Rename column to `name`
    `created_ip` varchar(45) default null,
    `modified_datetime` DATETIME DEFAULT NULL,
    `modified_user_id` INT(10) UNSIGNED DEFAULT NULL,
    `modified_reason` varchar(255) DEFAULT NULL,
    `moved_datetime` datetime DEFAULT NULL,
    `moved_user_id` int unsigned DEFAULT NULL,
    `moved_country` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `moved_language` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `moved_question_id` int unsigned DEFAULT NULL,
    `deleted_datetime` datetime default null,
    `deleted_user_id` int(10) default null,
    `deleted_reason` varchar(255) default null,
    PRIMARY KEY (`question_id`),
    UNIQUE `slug` (`slug`),
    KEY `user_id` (`user_id`),
    KEY `user_id_deleted_datetime_created_datetime_question_id` (`user_id`, `deleted_datetime`, `created_datetime`, `question_id`),
    KEY `subject_moved_datetime_deleted_datetime_views_not_bot_one_month` (`subject`,`moved_datetime`,`deleted_datetime`,`views_not_bot_one_month`),
    KEY `subject_etc` (`subject`,`moved_datetime`,`deleted_datetime`,`views_not_bot_one_hour` DESC, `views_not_bot_one_day` DESC, `views_not_bot_one_week` DESC, `views_not_bot_one_month` DESC, `views` DESC, `created_datetime` DESC, `question_id` DESC),
    KEY `answer_count_cached_etc` (`answer_count_cached`, `moved_datetime`, `deleted_datetime`, `views_not_bot_one_month` DESC),
    KEY `imported_moved_datetime_deleted_datetime_created_datetime` (`imported`, `moved_datetime`, `deleted_datetime`, `created_datetime`),
    KEY `created_datetime_deleted_datetime_views_not_bot_one_month` (`created_datetime`, `deleted_datetime`, `views_not_bot_one_month`),
    KEY `created_datetime_etc` (`created_datetime`, `moved_datetime`, `deleted_datetime`, `views_not_bot_one_month` DESC),
    KEY `created_name_deleted_datetime_created_datetime` (`created_name`, `deleted_datetime`, `created_datetime`),
    KEY `created_name_deleted_datetime_views_not_bot_one_month` (`created_name`, `deleted_datetime`, `views_not_bot_one_month`),
    KEY `created_ip_created_datetime` (`created_ip`, `created_datetime`),
    KEY `created_ip_and_3_more_columns` (`created_ip`, `deleted_datetime`, `deleted_user_id`, `deleted_reason`),
    KEY `moved_datetime_deleted_datetime_created_datetime` (`moved_datetime`,`deleted_datetime`,`created_datetime`),
    KEY `deleted_datetime_created_datetime` (`deleted_datetime`, `created_datetime`),
    KEY `deleted_datetime_views_not_bot_one_month` (`deleted_datetime`,`views_not_bot_one_month`),
    KEY `deleted_datetime_views_not_bot_one_week` (`deleted_datetime`,`views_not_bot_one_week`),
    KEY `deleted_datetime_views_not_bot_one_day` (`deleted_datetime`,`views_not_bot_one_day`),
    KEY `deleted_datetime_views_not_bot_one_hour` (`deleted_datetime`,`views_not_bot_one_hour`),
    KEY `deleted_user_id_deleted_datetime` (`deleted_user_id`, `deleted_datetime`),
    KEY `message_255` (`message`(255)),
    KEY `views_one_hour_etc` (`views_not_bot_one_hour` DESC, `views_not_bot_one_day` DESC, `views_not_bot_one_week` DESC, `views_not_bot_one_month` DESC),
    KEY `views_one_month_etc` (`views_not_bot_one_month` DESC, `views_not_bot_one_week` DESC, `views_not_bot_one_day` DESC, `views_not_bot_one_hour` DESC)
) CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
