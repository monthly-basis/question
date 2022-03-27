<?php
namespace MonthlyBasis\Question\Model\Service\Question\QuestionViewNotBotLog;

use Laminas\Db\Adapter\Exception\InvalidQueryException;
use Laminas\Db\TableGateway\TableGateway;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Superglobal\Model\Service as SuperglobalService;

class ConditionallyInsert
{
    public function __construct(
        TableGateway $questionViewNotBotLogTableGateway,
        SuperglobalService\Server\HttpUserAgent\Bot $botService
    ) {
        $this->questionViewNotBotLogTableGateway = $questionViewNotBotLogTableGateway;
        $this->botService                        = $botService;
    }

    public function conditionallyInsert(
        QuestionEntity\Question $questionEntity
    ): bool {
        if ($this->botService->isBot()) {
            return false;
        }

        try {
            $this->questionViewNotBotLogTableGateway
                ->insert([
                    'question_id' => $questionEntity->getQuestionId(),
                    'ip' => $_SERVER['REMOTE_ADDR'],
                ]);
        } catch (InvalidQueryException $invalidQueryException) {
            return false;
        }

        return true;
    }
}
