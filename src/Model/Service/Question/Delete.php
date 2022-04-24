<?php
namespace MonthlyBasis\Question\Model\Service\Question;

use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\User\Model\Entity as UserEntity;

/**
 * @todo Write unit test(s) for this class.
 */
class Delete
{
    public function __construct(
        QuestionTable\AnswerReport $answerReportTable,
        QuestionTable\Question\QuestionId $questionIdTable,
        QuestionTable\QuestionReport $questionReportTable
    ) {
        $this->answerReportTable   = $answerReportTable;
        $this->questionIdTable     = $questionIdTable;
        $this->questionReportTable = $questionReportTable;
    }

    /**
     * @todo Calls to table models should occur within a single transaction.
     */
    public function delete(
        UserEntity\User $userEntity,
        string $reason,
        QuestionEntity\Question $questionEntity
    ): bool {
        $this->questionReportTable->updateSetReportStatusIdWhereQuestionIdAndReportStatusIdEquals0(
            -4,
            $questionEntity->getQuestionId()
        );
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
