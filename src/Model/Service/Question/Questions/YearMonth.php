<?php
namespace MonthlyBasis\Question\Model\Service\Question\Questions;

use DateInterval;
use DateTime;
use DateTimeZone;
use Generator;
use Laminas\Db as LaminasDb;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class YearMonth
{
    public function __construct(
        protected QuestionFactory\Question\FromQuestionId $fromQuestionIdFactory,
        protected QuestionTable\Question $questionTable
    ) {
    }

    public function getQuestions(
        int $year,
        int $month
    ): Generator {
        $monthPadded = sprintf('%02d', $month);

        $dateTimeMin = new DateTime("$year-$monthPadded-01");
        $dateTimeMax = clone($dateTimeMin);
        $dateTimeMax->add(new DateInterval('P1M'))
            ->sub(new DateInterval('PT1S'))
            ;

        $result = $this->questionTable->select(
            columns: ['question_id'],
            where: [
                new LaminasDb\Sql\Predicate\Between(
                    'created_datetime',
                    $dateTimeMin->format('Y-m-d H:i:s'),
                    $dateTimeMax->format('Y-m-d H:i:s')
                ),
                'moved_datetime'   => null,
                'deleted_datetime' => null,
            ],
            order: 'views_not_bot_one_month DESC',
            limit: 100,
            offset: 0,
        );

        foreach ($result as $array) {
            yield $this->fromQuestionIdFactory->buildFromQuestionId(
                $array['question_id']
            );
        }
    }
}
