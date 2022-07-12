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

        /*
         * Only insert if referer is exactly:
         * https://www.google.com/
         *
         * International traffic can come from other Google domains, e.g.:
         * https://www.google.com.bd/
         * https://www.google.com.do/
         * https://www.google.com.pa/
         * https://www.google.com.ph/
         * https://www.google.com.vn/
         *
         * Therefore you cannot simply match 'google.com'. Instead, you must
         * match the entire 'https://www.google.com/' string.
         */
        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        if ($referer != 'https://www.google.com/') {
            return false;
        }

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
