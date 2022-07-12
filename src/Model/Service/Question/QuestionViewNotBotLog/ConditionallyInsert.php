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
        $serverHttpReferer = $_SERVER['HTTP_REFERER'] ?? '';
        if ($serverHttpReferer != 'https://www.google.com/') {
            return false;
        }

        $serverHttpAcceptLanguage = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
        $serverHttpAcceptLanguage = substr($serverHttpAcceptLanguage, 0, 255);

        try {
            $this->questionViewNotBotLogTableGateway
                ->insert([
                    'question_id'                 => $questionEntity->getQuestionId(),
                    'ip'                          => $_SERVER['REMOTE_ADDR'],
                    'server_http_accept_language' => $serverHttpAcceptLanguage,
                    'server_http_referer'         => $serverHttpReferer,
                ]);
        } catch (InvalidQueryException $invalidQueryException) {
            return false;
        }

        return true;
    }
}
