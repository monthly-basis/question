<?php
namespace MonthlyBasis\Question\Model\Service\Answer;

use Exception;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use TypeError;
use Laminas\Db\Adapter\Adapter;

class Edit
{
    public function __construct(
        Adapter $adapter,
        QuestionTable\Answer $answerTable,
        QuestionTable\AnswerHistory $answerHistoryTable
    ) {
        $this->adapter            = $adapter;
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

        $this->adapter->getDriver()->getConnection()->beginTransaction();
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
        $this->adapter->getDriver()->getConnection()->commit();
    }
}
