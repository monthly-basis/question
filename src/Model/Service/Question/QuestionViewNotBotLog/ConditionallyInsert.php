<?php
namespace MonthlyBasis\Question\Model\Service\Question\QuestionViewNotBotLog;

use Laminas\Db\Adapter\Exception\InvalidQueryException;
use Laminas\Db\TableGateway\TableGateway;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\String\Model\Service as StringService;
use MonthlyBasis\Superglobal\Model\Service as SuperglobalService;

class ConditionallyInsert
{
    public function __construct(
        TableGateway $questionViewNotBotLogTableGateway,
        StringService\StartsWith $startsWithService,
        SuperglobalService\Server\HttpUserAgent\Bot $botService
    ) {
        $this->questionViewNotBotLogTableGateway = $questionViewNotBotLogTableGateway;
        $this->startsWithService                 = $startsWithService;
        $this->botService                        = $botService;
    }

    public function conditionallyInsert(
        QuestionEntity\Question $questionEntity
    ): bool {
        /*
         * Comment out for now while we insert all views.
        if ($this->botService->isBot()) {
            return false;
        }
         */

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
        /*
         * Comment out for now while we insert all views.
        if ($serverHttpReferer != 'https://www.google.com/') {
            return false;
        }
         */

        /*
         * International traffic usually starts with many other language
         * strings. Some of most popular non-US languages currently logged are:
         *
         * en-GB,en-US;q=0.9,en;q=0.8
         * (empty string)
         * en-IN,en-GB;q=0.9,en-US;q=0.8,en;q=0.7
         * en-GB,en;q=0.9
         * en-CA,en-US;q=0.9,en;q=0.8
         * en-IN,en-GB;q=0.9,en-US;q=0.8,en;q=0.7,hi;q=0.6
         * en-PH,en-US;q=0.9,en;q=0.8
         * zh-CN,zh;q=0.9
         * ko-KR,ko;q=0.9,en-US;q=0.8,en;q=0.7
         * th-TH,th;q=0.9
         * en
         * ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7
         *
         * At the present time let's only log visitors with en set as
         * primary language. We may expand this logic later to log and target
         * traffic from different countries.
         */
        $serverHttpAcceptLanguage = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
        if (!$this->startsWithService->startsWith($serverHttpAcceptLanguage, 'en')) {
            return false;
        }
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
