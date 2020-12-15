<?php
namespace MonthlyBasis\Question\Model\Service\Question;

use Exception;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class Duplicate
{
    public function __construct(
        QuestionFactory\Question $questionFactory,
        QuestionTable\Question\MessageDeletedDatetimeCreatedDatetime $messageDeletedDatetimeCreatedDatetimeTable
    ) {
        $this->questionFactory                            = $questionFactory;
        $this->messageDeletedDatetimeCreatedDatetimeTable = $messageDeletedDatetimeCreatedDatetimeTable;
    }

    /**
     * @throws Exception
     */
    public function getDuplicate(
        string $message
    ): QuestionEntity\Question {
        $result = $this->messageDeletedDatetimeCreatedDatetimeTable
            ->selectWhereMessageAndDeletedDatetimeIsNullOrderByCreatedDatetimeDescLimit1(
                $message
            );
        if (!count($result)) {
            throw new Exception('No duplicate question found.');
        }

        return $this->questionFactory->buildFromArray(
            $result->current()
        );
    }
}
