<?php
namespace MonthlyBasis\Question\Model\Service\Question\Questions;

use DateInterval;
use DateTime;
use Generator;
use Laminas\Db as LaminasDb;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class YearMonthDay
{
    public function __construct(
        protected LaminasDb\Sql\Sql $sql,
        protected QuestionFactory\Question\FromQuestionId $fromQuestionIdFactory,
        protected QuestionTable\Question $questionTable
    ) {
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

        $sql = '
            SELECT `question_id`
              FROM `question`
             WHERE `created_datetime` BETWEEN ? AND ?
               AND `moved_datetime` IS NULL
               AND `deleted_datetime` IS NULL
             ORDER
                BY `views_one_month` DESC
             LIMIT 100
        ';
        $parameters = [
            $dateTimeMin->format('Y-m-d H:i:s'),
            $dateTimeMax->format('Y-m-d H:i:s'),
        ];
        $result = $this->sql->getAdapter()->query($sql)->execute($parameters);

        foreach ($result as $array) {
            yield $this->fromQuestionIdFactory->buildFromQuestionId($array['question_id']);
        }
    }
}
