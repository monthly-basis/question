# Changelog

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
