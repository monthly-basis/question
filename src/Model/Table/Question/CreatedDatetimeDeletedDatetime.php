<?php
namespace MonthlyBasis\Question\Model\Table\Question;

use Generator;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use Laminas\Db\Adapter\Adapter;

class CreatedDatetimeDeletedDatetime
{
    protected $adapter;

    public function __construct(
        Adapter $adapter,
        QuestionTable\Question $questionTable
    ) {
        $this->adapter       = $adapter;
        $this->questionTable = $questionTable;
    }

    public function selectWhereCreatedDatetimeIsBetweenAndDeletedDatetimeIsNullAndViewsNotBotGreaterThan0(
        string $createdDatetimeMin,
        string $createdDatetimeMax
    ): Generator {
        $sql = $this->questionTable->getSelect()
             . '
              FROM `question`
               USE INDEX (`created_datetime_deleted_datetime_views_not_bot_one_month`)
             WHERE `question`.`created_datetime` >= ?
               AND `question`.`created_datetime` < ?
               AND `question`.`deleted_datetime` IS NULL
               AND `question`.`views_not_bot_one_month` > 0
             ORDER
                BY `question`.`created_datetime` ASC
                 ;
        ';
        $parameters = [
            $createdDatetimeMin,
            $createdDatetimeMax,
        ];
        foreach ($this->adapter->query($sql)->execute($parameters) as $array) {
            yield $array;
        }
    }
}
