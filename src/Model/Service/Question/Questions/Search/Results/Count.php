<?php
namespace MonthlyBasis\Question\Model\Service\Question\Questions\Search\Results;

use Exception;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use Laminas\Db\Adapter\Exception\InvalidQueryException;
use MonthlyBasis\Memcached\Model\Service as MemcachedService;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\String\Model\Service as StringService;

class Count
{
    protected int $recursionIteration = 0;

    public function __construct(
        MemcachedService\Memcached $memcachedService,
        QuestionEntity\Config $configEntity,
        QuestionTable\QuestionSearchMessage $questionSearchMessage,
        StringService\KeepFirstWords $keepFirstWordsService
    ) {
        $this->memcachedService      = $memcachedService;
        $this->configEntity          = $configEntity;
        $this->questionSearchMessage = $questionSearchMessage;
        $this->keepFirstWordsService = $keepFirstWordsService;
    }

    public function getCount(string $query): int
    {
        $cacheKey = md5($_SERVER['DOCUMENT_ROOT'] . __METHOD__ . $query);
        if (null !== ($count = $this->memcachedService->get($cacheKey))) {
            return $count;
        }

        $query = strtolower($query);
        $query = $this->keepFirstWordsService->keepFirstWords(
            $query,
            16
        );

        $result = $this->getPdoResult($query);

        $count = intval($result->current()['COUNT(*)']);
        $this->memcachedService->setForDays($cacheKey, $count, 7);
        return $count;
    }

    protected function getPdoResult(string $query): Result
    {
        try {
            return $result = $this->questionSearchMessage->selectCountWhereMatchMessageAgainst(
                $query
            );
        } catch (InvalidQueryException $invalidQueryException) {
            sleep($this->configEntity['sleep-when-result-unavailable'] ?? 1);
            $this->recursionIteration++;
            if ($this->recursionIteration >= 5) {
                throw new Exception('Unable to get PDO result.');
            }
            return $this->getPdoResult($query);
        }
    }
}
