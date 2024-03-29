<?php
namespace MonthlyBasis\Question\Model\Factory;

use DateTime;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\User\Model\Factory as UserFactory;
use MonthlyBasis\User\Model\Service as UserService;
use TypeError;

class Answer
{
    public function __construct(
        QuestionTable\Answer $answerTable,
        UserFactory\User $userFactory,
        UserService\DisplayNameOrUsername $displayNameOrUsernameService
    ) {
        $this->answerTable                  = $answerTable;
        $this->userFactory                  = $userFactory;
        $this->displayNameOrUsernameService = $displayNameOrUsernameService;
    }

    public function buildFromArray(
        array $array
    ): QuestionEntity\Answer {
        $answerEntity = $this->getNewInstance()
            ->setAnswerId($array['answer_id'])
            ->setCreatedDateTime(new DateTime($array['created_datetime']))
            ->setMessage($array['message'])
            ;

        if (isset($array['created_ip'])) {
            $answerEntity->setCreatedIp($array['created_ip']);
        }
        if (isset($array['created_name'])) {
            $answerEntity->setCreatedName($array['created_name']);
        }
        if (isset($array['deleted_datetime'])) {
            $answerEntity->setDeletedDateTime(new DateTime($array['deleted_datetime']));
        }
        if (isset($array['deleted_user_id'])) {
            $answerEntity->setDeletedUserId($array['deleted_user_id']);
        }
        if (isset($array['deleted_reason'])) {
            $answerEntity->setDeletedReason($array['deleted_reason']);
        }
        if (isset($array['question_id'])) {
            $answerEntity->setQuestionId($array['question_id']);
        }
        if (isset($array['user_id'])) {
            $answerEntity->setCreatedUserId((int) $array['user_id']);

            $userEntity = $this->userFactory->buildFromUserId(
                $array['user_id']
            );
            $answerEntity->setCreatedName(
                $this->displayNameOrUsernameService->getDisplayNameOrUsername(
                    $userEntity
                )
            );
        }

        return $answerEntity;
    }

    /**
     * @deprecated Use QuestionFactory\Answer\FromAnswerId::buildFromAnswerId instead.
     */
    public function buildFromAnswerId(
        int $answerId
    ): QuestionEntity\Answer {
        $answerEntity = $this->buildFromArray(
            $this->answerTable->selectWhereAnswerId($answerId)
        );

        return $answerEntity;
    }

    protected function getNewInstance(): QuestionEntity\Answer
    {
        return new QuestionEntity\Answer();
    }
}
