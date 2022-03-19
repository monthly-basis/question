<?php
namespace MonthlyBasis\Question\Model\Service\Question;

use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\User\Model\Entity as UserEntity;

class Delete
{
    public function __construct(
        QuestionTable\AnswerReport $answerReportTable,
        QuestionTable\Question\QuestionId $questionIdTable
    ) {
        $this->answerReportTable = $answerReportTable;
        $this->questionIdTable   = $questionIdTable;
    }

    /**
     * @todo Calls to table models should occur within a single transaction.
     */
    public function delete(
        UserEntity\User $userEntity,
        string $reason,
        QuestionEntity\Question $questionEntity
    ): bool {
        $this->answerReportTable->updateSetReportStatusIdWhereQuestionIdAndReportStatusIdEquals0(
            -4,
            $questionEntity->getQuestionId()
        );

        return $this->questionIdTable->updateSetDeletedColumnsWhereQuestionId(
            $userEntity->getUserId(),
            $reason,
            $questionEntity->getQuestionId()
        );
    }
}
