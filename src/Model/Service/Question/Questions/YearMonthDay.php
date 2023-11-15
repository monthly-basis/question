<?php
namespace MonthlyBasis\Question\Model\Service\Question\Questions;

use DateInterval;
use DateTime;
use DateTimeZone;
use Generator;
use Laminas\Db as LaminasDb;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class YearMonthDay
{
    public function __construct(
        LaminasDb\Sql\Sql $sql,
        QuestionFactory\Question $questionFactory,
        QuestionTable\Question $questionTable
    ) {
        $this->sql             = $sql;
        $this->questionFactory = $questionFactory;
        $this->questionTable   = $questionTable;
    }

    public function getQuestions(
        int $year,
        int $month,
        int $day
    ): Generator {
        $monthPadded = sprintf('%02d', $month);
        $dayPadded   = sprintf('%02d', $day);

        $dateTimeMin = new DateTime("$year-$monthPadded-$dayPadded");
        $dateTimeMax = clone($dateTimeMin);
        $dateTimeMax->add(new DateInterval('P1D'))
            ->sub(new DateInterval('PT1S'))
            ;

        $sql = "
            SELECT `question`.`question_id` AS `question_id`, `question`.`user_id` AS `user_id`
                 , `question`.`subject` AS `subject`
                 , `question`.`headline` AS `headline`
                 , `question`.`message` AS `message`
                 , `question`.`views` AS `views`
                 , `question`.`views_not_bot_one_month` AS `views_not_bot_one_month`
                 , `question`.`answer_count_cached` AS `answer_count_cached`
                 , `question`.`created_datetime` AS `created_datetime`, `question`.`created_name` AS `created_name`, `question`.`created_ip` AS `created_ip`, `question`.`modified_user_id` AS `modified_user_id`, `question`.`modified_datetime` AS `modified_datetime`, `question`.`modified_reason` AS `modified_reason`, `question`.`deleted_datetime` AS `deleted_datetime`, `question`.`deleted_user_id` AS `deleted_user_id`, `question`.`deleted_reason` AS `deleted_reason`

              FROM `question`

             FORCE
             INDEX (`created_datetime_deleted_datetime_views_not_bot_one_month`)

             WHERE `created_datetime` BETWEEN ? AND ?
               AND `moved_datetime` IS NULL
               AND `deleted_datetime` IS NULL

             ORDER
                BY `views_one_month` DESC

             LIMIT 100
        ";
        $parameters = [
            $dateTimeMin->format('Y-m-d H:i:s'),
            $dateTimeMax->format('Y-m-d H:i:s'),
        ];
        $result = $this->sql->getAdapter()->query($sql)->execute($parameters);

        foreach ($result as $array) {
            yield $this->questionFactory->buildFromArray($array);
        }
    }
}
