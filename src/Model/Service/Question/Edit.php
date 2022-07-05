<?php
namespace MonthlyBasis\Question\Model\Service\Question;

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
        $name,
        $subject,
        $message,
        $modifiedUserId,
        $modifiedReason
    ) {
        try {
            $questionEntity->getCreatedUserId();
        } catch (TypeError $typeError) {
            if (empty($name)) {
                throw new Exception('Name cannot be empty');
            }
        }

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
