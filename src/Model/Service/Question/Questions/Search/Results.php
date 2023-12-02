<?php
namespace MonthlyBasis\Question\Model\Service\Question\Questions\Search;

use Exception;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use Laminas\Db\Adapter\Exception\InvalidQueryException;
use MonthlyBasis\Memcached\Model\Service as MemcachedService;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\String\Model\Service as StringService;

class Results
{
    protected int $recursionIteration = 0;

    public function __construct(
        protected MemcachedService\Memcached $memcachedService,
        protected QuestionEntity\Config $configEntity,
        protected QuestionFactory\Question $questionFactory,
        protected QuestionTable\QuestionSearchMessage $questionSearchMessageTable,
        protected StringService\KeepFirstWords $keepFirstWordsService
    ) {
    }

    public function getResults(
        string $query,
        int $page,
        int $questionsPerPage = 100,
        int $queryWordCount = 30,
    ): array {
        $questionIds = $this->getQuestionIds(
            query: $query,
            queryWordCount: $queryWordCount,
            page: $page,
            questionsPerPage: $questionsPerPage,
        );

        $questionEntities = [];

        foreach ($questionIds as $questionId) {
            $questionEntity = $this->questionFactory->buildFromQuestionId(intval($questionId));

            if (isset($questionEntity->deletedDateTime)) {
                continue;
            }

            $questionEntities[] = $questionEntity;
        }

        return $questionEntities;
    }

    protected function getQuestionIds(
        string $query,
        int $queryWordCount = 30,
        int $page,
        int $questionsPerPage = 100,
    ): array {
        $memcachedKey = md5(__METHOD__ . serialize(func_get_args()));
        if (null !== ($questionIds = $this->memcachedService->get($memcachedKey))) {
            return $questionIds;
        }

        $query = strtolower($query);
        $query = $this->keepFirstWordsService->keepFirstWords(
            $query,
            $queryWordCount,
        );

        $questionIds = [];

        $result = $this->getPdoResult($query, $page, $questionsPerPage);
        foreach ($result as $array) {
            $questionIds[] = $array['question_id'];
        }

        $this->memcachedService->setForDays(
            $memcachedKey,
            $questionIds,
            1
        );
        return $questionIds;
    }

    protected function getPdoResult(
        string $query,
        int $page,
        int $questionsPerPage = 100,
    ): Result
    {
        try {
            return $this->questionSearchMessageTable
                ->selectQuestionIdWhereMatchAgainstOrderByScoreDesc(
                    $query,
                    ($page - 1) * $questionsPerPage,
                    $questionsPerPage,
                );
        } catch (InvalidQueryException $invalidQueryException) {
            sleep($this->configEntity['sleep-when-result-unavailable'] ?? 1);
            $this->recursionIteration++;
            if ($this->recursionIteration >= 5) {
                throw new Exception('Unable to get PDO result.');
            }
            return $this->getPdoResult($query, $page);
        }
    }
}
