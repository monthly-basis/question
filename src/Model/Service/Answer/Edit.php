<?php
namespace MonthlyBasis\Question\Model\Service\Answer;

use Exception;
use Laminas\Db\Adapter\Driver\Pdo\Connection;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use Throwable;
use TypeError;

class Edit
{
    public function __construct(
        Connection $connection,
        QuestionTable\Answer $answerTable,
        QuestionTable\AnswerHistory $answerHistoryTable
    ) {
        $this->connection         = $connection;
        $this->answerTable        = $answerTable;
        $this->answerHistoryTable = $answerHistoryTable;
    }

    /**
     * @throws Exception
     */
    public function edit(
        QuestionEntity\Answer $answerEntity,
        $name,
        $message,
        $modifiedUserId,
        $modifiedReason
    ) {
        try {
            $answerEntity->getCreatedUserId();
        } catch (TypeError $typeError) {
            if (empty($name)) {
                throw new Exception('Name cannot be empty');
            }
        }

        try {
            $this->connection->beginTransaction();
            $this->answerHistoryTable->insertSelectFromAnswer(
                $answerEntity->getAnswerId()
            );
            $this->answerTable->updateWhereAnswerId(
                $name,
                $message,
                $modifiedUserId,
                $modifiedReason,
                $answerEntity->getAnswerId()
            );
            $this->connection->commit();
        } catch (Throwable $throwable) {
            $this->connection->rollback();
        }
    }
}
