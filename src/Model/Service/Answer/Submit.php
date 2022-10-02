<?php
namespace MonthlyBasis\Question\Model\Service\Answer;

use Exception;
use MonthlyBasis\Flash\Model\Service as FlashService;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\User\Model\Entity as UserEntity;

class Submit
{
    public function __construct(
        FlashService\Flash $flashService,
        QuestionFactory\Answer $answerFactory,
        QuestionTable\Answer $answerTable
    ) {
        $this->flashService  = $flashService;
        $this->answerFactory = $answerFactory;
        $this->answerTable   = $answerTable;
    }

    /**
     * Submit.
     *
     * @param $userId
     * @return QuestionEntity\Answer
     */
    public function submit(
        UserEntity\User $userEntity = null
    ) : QuestionEntity\Answer {
        $errors = [];

        if (empty($_POST['question-id'])) {
            $errors[] = 'Invalid question ID.';
        }
        if (empty($_POST['name'])) {
            $_POST['name'] = null;
        }
        if (empty($_POST['message'])) {
            $errors[] = 'Invalid message.';
        }

        if ($errors) {
            $this->flashService->set('errors', $errors);
            throw new Exception('Invalid form input.');
        }

        $userId = isset($userEntity) ? $userEntity->getUserId() : null;
        $name   = $_POST['name'] ?? null;

        $answerId = $this->answerTable->insertDeprecated(
            $_POST['question-id'],
            $userId,
            $_POST['message'],
            $_POST['name'],
            $_SERVER['REMOTE_ADDR']
        );

        return $this->answerFactory->buildFromAnswerId($answerId);
    }
}
