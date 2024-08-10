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

        $result = $this->questionTable->selectQuestionIdWhereCreatedDatetimeBetween(
            $dateTimeMin,
            $dateTimeMax,
            0,
            100,
        );

        foreach ($result as $array) {
            yield $this->fromQuestionIdFactory->buildFromQuestionId(
                $array['question_id']
            );
        }
    }
}
