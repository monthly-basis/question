# Changelog

All notable changes to this project will be documented in this file.

## v2.5.0

- ALTER TABLE `answer` ADD KEY `user_id_deleted_datetime_created_datetime_answer_id` (`user_id`, `deleted_datetime`, `created_datetime`, `answer_id`);
- ALTER TABLE `question` ADD KEY `user_id_deleted_datetime_created_datetime_question_id` (`user_id`, `deleted_datetime`, `created_datetime`, `question_id`);

## v2.3.0

### Deprecated 

- In UserTable\QuestionSearchMessage, we deprecated ::selectCountWhereMatchAgainst() in favor of ::selectCountWhereMatchMessageAgainst()
