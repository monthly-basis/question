<?php
namespace MonthlyBasis\Question\Model\Service\Question;

use Exception;
use Laminas\Db\Adapter\Driver\Pdo\Connection;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use Throwable;

class Edit
{
    public function __construct(
        Connection $connection,
        QuestionTable\Question $questionTable,
        QuestionTable\QuestionHistory $questionHistoryTable
    ) {
        $this->connection           = $connection;
        $this->questionTable        = $questionTable;
        $this->questionHistoryTable = $questionHistoryTable;
    }

    /**
     * @throws Exception
     */
    public function edit(
        QuestionEntity\Question $questionEntity,
        string|null $name,
        $subject,
        $message,
        $modifiedUserId,
        $modifiedReason
    ) {
        try {
            $this->connection->beginTransaction();
            $this->questionHistoryTable->insertSelectFromQuestion(
                $questionEntity->getQuestionId()
            );
            $this->questionTable->updateWhereQuestionId(
                $name,
                $subject,
                $message,
                $modifiedUserId,
                $modifiedReason,
                $questionEntity->getQuestionId()
            );
            $this->connection->commit();
        } catch (Throwable $throwable) {
            $this->connection->rollback();
        }
    }
}
