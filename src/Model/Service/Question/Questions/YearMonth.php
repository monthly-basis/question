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
        int $month
    ): Generator {
        $monthPadded = sprintf('%02d', $month);

        $dateTimeMin = new DateTime("$year-$monthPadded-01");
        $dateTimeMax = clone($dateTimeMin);
        $dateTimeMax->add(new DateInterval('P1M'))
            ->sub(new DateInterval('PT1S'))
            ;

        $select = $this->sql
            ->select('question')
            ->columns($this->questionTable->getSelectColumns())
            ->where([
                new LaminasDb\Sql\Predicate\Between(
                    'created_datetime',
                    $dateTimeMin->format('Y-m-d H:i:s'),
                    $dateTimeMax->format('Y-m-d H:i:s')
                ),
                'deleted_datetime' => null,
            ])
            ->order('views_not_bot_one_month DESC')
            ->limit(100)
            ->offset(0)
            ;
        $result = $this->sql->prepareStatementForSqlObject($select)->execute();

        foreach ($result as $array) {
            yield $this->questionFactory->buildFromArray($array);
        }
    }
}
