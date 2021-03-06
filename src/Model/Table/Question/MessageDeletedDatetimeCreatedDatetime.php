<?php
namespace MonthlyBasis\Question\Model\Table\Question;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class MessageDeletedDatetimeCreatedDatetime
{
    /**
     * @var Adapter
     */
    protected $adapter;

    public function __construct(
        Adapter $adapter,
        QuestionTable\Question $questionTable
    ) {
        $this->adapter       = $adapter;
        $this->questionTable = $questionTable;
    }

    public function selectWhereMessageAndDeletedDatetimeIsNullOrderByCreatedDatetimeDescLimit1(
        string $message
    ): Result {
        $sql = $this->questionTable->getSelect()
             .  '
              FROM `question`
             WHERE `question`.`message` = ?
               AND `question`.`deleted_datetime` IS NULL
             ORDER
                BY `question`.`created_datetime` DESC
                 , `question`.`question_id` DESC
             LIMIT 1
        ';
        $parameters = [
            $message
        ];
        return $this->adapter->query($sql)->execute($parameters);
    }
}
