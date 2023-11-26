# Changelog

## v2.69.0

### Added

- `question`.`top_answer_id_cached` column
```
ALTER TABLE `question` ADD COLUMN `top_answer_id_cached` int(10) unsigned DEFAULT NULL AFTER `answer_count_cached`;
```

### Removed

- `question`.`image_rru` column
```
ALTER TABLE `question` DROP COLUMN `image_rru`;
```

## v2.68.1

### Changed
- Image prompt column length
```
ALTER TABLE `question` CHANGE `image_prompt` `image_prompt` varchar(2047) COLLATE utf8mb4_unicode_ci DEFAULT NULL;
ALTER TABLE `question` CHANGE `image_prompt_revised` `image_prompt_revised` varchar(2047) COLLATE utf8mb4_unicode_ci DEFAULT NULL;
```

## v2.68.0

### Added
- Added `image_prompt` and `image_prompt_revised` columns
```
ALTER TABLE `question` ADD COLUMN `image_prompt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL AFTER `message`;
ALTER TABLE `question` ADD COLUMN `image_prompt_revised` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL AFTER `image_prompt`;
```

## v2.67.5

### Added
- `image_rru_1024x1024_png` column
```
ALTER TABLE `question` ADD COLUMN `image_rru_1024x1024_png` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL AFTER `image_rru_1024x1024_jpeg`;
```

## v2.67.2

### Changed
- `image_rru%` columns
```
ALTER TABLE `question` CHANGE `image_rru_128x128` `image_rru_128x128_webp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL;
ALTER TABLE `question` DROP COLUMN `image_rru_256x256`;
ALTER TABLE `question` CHANGE `image_rru_512x512` `image_rru_512x512_webp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL;
ALTER TABLE `question` CHANGE `image_rru_1024x1024` `image_rru_1024x1024_jpeg` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL;
```

### Removed

- `views_not_bot%` columns
```
ALTER TABLE `question` DROP COLUMN `views_not_bot_one_month`;
ALTER TABLE `question` DROP COLUMN `views_not_bot_one_week`;
ALTER TABLE `question` DROP COLUMN `views_not_bot_one_day`;
ALTER TABLE `question` DROP COLUMN `views_not_bot_one_hour`;
```

## v2.67.0

### Changed

- Renamed table `question_view_not_bot_log` to `log_question_view`
```
RENAME TABLE `question_view_not_bot_log` to `log_question_view`;
```

## v2.66.17

### Added

- `answer_count_cached_etc` index
```
ALTER TABLE `question` ADD KEY `answer_count_cached_etc` (`answer_count_cached`, `moved_datetime`, `deleted_datetime`, `views_one_month` DESC);
```

## v2.66.16

### Removed

- Remove `answer_count_cached_etc` index
```
ALTER TABLE `question` DROP KEY `answer_count_cached_etc`;
```

### Added

- `answer_count_cached` index
```
ALTER TABLE `question` ADD KEY `answer_count_cached` (`answer_count_cached`);
```

## v2.66.14

### Added

- `created_name_etc` index
```
ALTER TABLE `question` ADD KEY `created_name_etc` (`created_name`, `deleted_datetime`, `views_one_month` DESC);
```

## v2.66.10

### Added

- `created_datetime_etc_2` index
```
ALTER TABLE `question` ADD KEY `created_datetime_etc_2` (`created_datetime`, `moved_datetime`, `deleted_datetime`, `views_one_month` DESC);
```

## v2.66.9

### Added

- Index on `question`.`views_one_week` column
```
ALTER TABLE `question` ADD KEY `views_one_week` (`views_one_week` DESC);
```

## v2.66.8

### Added

- Index on `question` table
```
ALTER TABLE `question` ADD KEY `views_one_hour_etc_2` (`views_one_hour` DESC, `views_one_day` DESC, `views_one_week` DESC, `views_one_month` DESC);
```

## v2.66.7

### Changed

- Indexes on `question` table
```
ALTER TABLE `question` DROP KEY `subject_moved_datetime_deleted_datetime_views_not_bot_one_month`;
ALTER TABLE `question` DROP KEY `subject_etc`;
ALTER TABLE `question` ADD KEY `subject_etc` (`subject`,`moved_datetime`,`deleted_datetime`,`views_one_year` DESC);
```

## v2.66.3

### Added

- Index on `question`.`views_one_day` column
```
ALTER TABLE `question` ADD KEY `views_one_day` (`views_one_day` DESC);
```

## v2.66.0

### Added

- `question`.`image_rru` columns
```
ALTER TABLE `question` ADD COLUMN `image_rru_128x128` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL AFTER `image_rru`;
ALTER TABLE `question` ADD COLUMN `image_rru_256x256` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL AFTER `image_rru_128x128`;
ALTER TABLE `question` ADD COLUMN `image_rru_512x512` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL AFTER `image_rru_256x256`;
ALTER TABLE `question` ADD COLUMN `image_rru_1024x1024` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL AFTER `image_rru_512x512`;
```

## v2.65.12

### Added

- Index `category_id_question_views_one_month_cached_desc`
```
ALTER TABLE `category_question` ADD KEY `category_id_question_views_one_month_cached_desc` (`category_id`, `question_views_one_month_cached` DESC);
```

- Column `category_question`.`question_views_one_month_cached`
```
ALTER TABLE `category_question`
ADD COLUMN `question_views_one_month_cached` int unsigned DEFAULT NULL;
```

## v2.63.3

### Changed

- `question`.`headline` character set and collation
```
ALTER TABLE `question` CHANGE `headline`
`headline` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_as_cs DEFAULT NULL;
```

## v2.63.0

### Added

- `question`.`image_rru` column
```
ALTER TABLE `question` ADD COLUMN `image_rru` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL AFTER `message`;
```

## v2.58.10

### Added

- `question` views columns
```
ALTER TABLE `question` ADD COLUMN `views_one_hour` int unsigned NOT NULL DEFAULT '0' AFTER `views`;
ALTER TABLE `question` ADD COLUMN `views_one_day` int unsigned NOT NULL DEFAULT '0' AFTER `views_one_hour`;
ALTER TABLE `question` ADD COLUMN `views_one_week` int unsigned NOT NULL DEFAULT '0' AFTER `views_one_day`;
ALTER TABLE `question` ADD COLUMN `views_one_month` int unsigned NOT NULL DEFAULT '0' AFTER `views_one_week`;
```

- `question`.`views_one_year` column
```
ALTER TABLE `question` ADD COLUMN `views_one_year` int unsigned NOT NULL DEFAULT '0' AFTER `views`;
```

## v2.54.5

### Changed

- `question_history`.`subject` column
```
ALTER TABLE `question_history` CHANGE `subject` `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_as_cs DEFAULT NULL;
```

## v2.52.0

### Added

- View helper to get answer root-relative URL

## v2.51.0

### Added

- Service to get related answers
- Service to rotate `answer_search_message` table
- `answer_search_message` table
```
CREATE TABLE `answer_search_message` (
    `answer_id` int(10) unsigned NOT NULL,
    `message` text COLLATE utf8mb4_unicode_ci,
    PRIMARY KEY (`answer_id`),
    FULLTEXT KEY `message` (`message`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## v2.50.3

### Added

- Add index to sort by views

    ALTER TABLE `question` ADD KEY `views_one_hour_etc` (`views_not_bot_one_hour` DESC, `views_not_bot_one_day` DESC, `views_not_bot_one_week` DESC, `views_not_bot_one_month` DESC);

## v2.50.0

### Added

- Add service to get questions by category

## v2.49.0

### Added

- Add service to get categories belonging to a question

## v2.48.0

    ALTER TABLE `question` ADD COLUMN `did_you_know` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci AFTER `message`;

## v2.35.0

    ALTER TABLE `answer` ADD COLUMN `imported` tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER `message`;

## v2.31.2

- Alter `question` table.

    ALTER TABLE `question` ADD KEY `created_datetime_etc` (`created_datetime`, `moved_datetime`, `deleted_datetime`, `views_not_bot_one_month` DESC);

## v2.30.1

- Alter `question_search_message` table.

	ALTER TABLE `question_search_message` ADD UNIQUE KEY `question_id` (`question_id`);

	ALTER TABLE `question_search_message` DROP PRIMARY KEY;

	ALTER TABLE `question_search_message` ADD `question_search_message_id` int(10) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST;

## v2.24.25

- Alter `answer` table.

    ALTER TABLE `answer` CHANGE `created_name` `created_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_as_cs DEFAULT NULL;

## v2.24.4

- Alter `answer` table.

    ALTER TABLE `answer` CHANGE `created_datetime` `created_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP;

- Alter `question` table.

    ALTER TABLE `question` CHANGE `created_name` `created_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_as_cs DEFAULT NULL;
    ALTER TABLE `question` CHANGE `subject` `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_as_cs DEFAULT NULL;

## v2.22.0

- Update method calls.

```
    MonthlyBasis\Question\Model\Service\Question\Questions\Similar

    public function getSimilar(
        QuestionEntity\Question $questionEntity,
        int $outerLimitOffset,
        int $outerLimitRowCount,
    ): Generator {
```

```
    MonthlyBasis\Question\Model\Service\Question\Questions

    public function getRelated(
        QuestionEntity\Question $questionEntity,
        int $outerLimitOffset,
        int $outerLimitRowCount,
    ): Generator {
```

## v2.20.0

`QuestionService\Answer\Answers::getAnswers()` now returns array rather than Generator.

## v2.19.1

         ALTER
         TABLE `question`
        CHANGE `created_datetime`
               `created_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
             ;

## v2.19.0

         ALTER
         TABLE `question`
           ADD
        UNIQUE `slug` (`slug`)
             ;

## v2.18.0

         ALTER
         TABLE `question`
           ADD
        COLUMN `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
         AFTER `question_id`
             ;

## v2.17.0

         ALTER
         TABLE `question`
           ADD
        COLUMN `moved_country` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
         AFTER `moved_user_id`
             ;

## v2.14.1

- Run SQL

         ALTER
         TABLE `question_history`
           ADD
        COLUMN `modified_datetime` DATETIME DEFAULT NULL
         AFTER `message`
             ;

         ALTER
         TABLE `question_history`
           ADD
        COLUMN `modified_user_id` INT(10) UNSIGNED DEFAULT NULL
         AFTER `modified_datetime`
             ;

        UPDATE `question_history`
           SET `modified_datetime` = `created`
             ;

         ALTER
         TABLE `question_history`
           ADD KEY `question_id_modified_datetime` (`question_id`, `modified_datetime`)
             ;

         ALTER
         TABLE `question_history`
          DROP KEY `question_id_created`
             ;

         ALTER
         TABLE `question_history`
          DROP `created`
             ;

## v2.13.2

- Run SQL.

         ALTER
         TABLE `question`
           ADD
        COLUMN `headline` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
         AFTER `subject`
             ;

         ALTER
         TABLE `question`
           ADD
        COLUMN `details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
         AFTER `message`
             ;

         ALTER
         TABLE `question`
        MODIFY `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
             ;

## v2.12.18

- Run SQL.

        ALTER TABLE `question_view_not_bot_log` ADD COLUMN `server_http_accept_language` varchar(255) DEFAULT NULL AFTER `ip`;

## v2.12.14

- Run SQL.

        ALTER TABLE `question_view_not_bot_log` ADD COLUMN `server_http_referer` varchar(255) DEFAULT NULL AFTER `ip`;

## v2.12.3

- Replace calls to `updateSetReportStatusIdWhereQuestionIdAndReportStatusIdEquals0()` method with `updateWhereQuestionIdAndReportStatusIdEquals0()`

## v2.12.2

- Replace calls to `updateSetReportStatusIdWhereAnswerIdAndReportStatusIdEquals0()` method with `updateWhereAnswerIdAndReportStatusIdEquals0()`

## v2.12.1

- Run SQL.

        ALTER TABLE `answer_report` CHANGE `modified` `modified_datetime` datetime DEFAULT NULL;

## v2.8.3

- Run SQL.

        ALTER TABLE `answer_history` ADD COLUMN `modified_user_id` int unsigned DEFAULT NULL AFTER `message`;

## v2.5.0

- Run SQL.

        ALTER TABLE `answer` ADD KEY `user_id_deleted_datetime_created_datetime_answer_id` (`user_id`, `deleted_datetime`, `created_datetime`, `answer_id`);
        ALTER TABLE `question` ADD KEY `user_id_deleted_datetime_created_datetime_question_id` (`user_id`, `deleted_datetime`, `created_datetime`, `question_id`);

## v2.3.0

### Deprecated

- In UserTable\QuestionSearchMessage, we deprecated ::selectCountWhereMatchAgainst() in favor of ::selectCountWhereMatchMessageAgainst()
