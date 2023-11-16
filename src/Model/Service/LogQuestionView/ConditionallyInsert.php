<?php
namespace MonthlyBasis\Question\Model\Service\LogQuestionView;

use Laminas\Db\Adapter\Exception\InvalidQueryException;
use MonthlyBasis\Memcached\Model\Service as MemcachedService;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\String\Model\Service as StringService;
use MonthlyBasis\Superglobal\Model\Service as SuperglobalService;

class ConditionallyInsert
{
    public function __construct(
        protected MemcachedService\Memcached $memcachedService,
        protected QuestionTable\LogQuestionView $logQuestionViewTable,
        protected StringService\StartsWith $startsWithService,
        protected SuperglobalService\Server\HttpUserAgent\Bot $botService,
    ) {
    }

    public function conditionallyInsert(
        QuestionEntity\Question $questionEntity
    ): bool {
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
         * Therefore, to target US traffic, you must match the entire
         * 'https://www.google.com/' string.
         */
        $serverHttpReferer = $_SERVER['HTTP_REFERER'] ?? '';
        if (
            // !$this->startsWithService->startsWith($serverHttpReferer, 'https://www.bing.')
            !$this->startsWithService->startsWith($serverHttpReferer, 'https://www.google.')
        ) {
            return false;
        }

        if ($this->botService->isBot()) {
            return false;
        }

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
        /*
         * Comment out for now while we accept browsers using all languages.
        if (!$this->startsWithService->startsWith($serverHttpAcceptLanguage, 'en')) {
            return false;
        }
        $serverHttpAcceptLanguage = substr($serverHttpAcceptLanguage, 0, 255);
         */

        try {
            $this->logQuestionViewTable->insert(
                values: [
                    'question_id'                 => $questionEntity->getQuestionId(),
                    'ip'                          => $_SERVER['REMOTE_ADDR'],
                    'server_http_accept_language' => $serverHttpAcceptLanguage,
                    'server_http_referer'         => $serverHttpReferer,
                ]
            );
        } catch (InvalidQueryException $invalidQueryException) {
            return false;
        }

        return true;
    }
}
