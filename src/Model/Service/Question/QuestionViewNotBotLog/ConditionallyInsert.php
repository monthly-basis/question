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

        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        if (strpos($referer, 'google.com') === false) {
            return false;
        }
        $referer = substr($referer, 0, 255);

        try {
            $this->questionViewNotBotLogTableGateway
                ->insert([
                    'question_id'         => $questionEntity->getQuestionId(),
                    'ip'                  => $_SERVER['REMOTE_ADDR'],
                    'server_http_referer' => $referer,
                ]);
        } catch (InvalidQueryException $invalidQueryException) {
            return false;
        }

        return true;
    }
}
