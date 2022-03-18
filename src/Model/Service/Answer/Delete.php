<?php
namespace MonthlyBasis\Question\Model\Service\Answer;

use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\User\Model\Entity as UserEntity;

class Delete
{
    public function __construct(
        QuestionTable\Answer\AnswerId $answerIdTable,
        QuestionTable\AnswerReport $answerReportTable
    ) {
        $this->answerIdTable     = $answerIdTable;
        $this->answerReportTable = $answerReportTable;
    }

    /**
     * @todo Calls to table models should occur within a single transaction.
     */
    public function delete(
        UserEntity\User $userEntity,
        string $reason,
        QuestionEntity\Answer $answerEntity
    ): bool {
        $this->answerReportTable->updateSetReportStatusIdWhereAnswerIdAndReportStatusIdEquals0(
            -5,
            $answerEntity->getAnswerId()
        );

        return (bool) $this->answerIdTable->updateSetDeletedColumnsWhereAnswerId(
            $userEntity->getUserId(),
            $reason,
            $answerEntity->getAnswerId()
        );
    }
}
