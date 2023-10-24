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

    /**
     * Search results may include deleted questions. So, if necessary, skip
     * deleted questions when looping through questions in your application.
     */
    public function getResults(
        string $query,
        int $page,
        int $queryWordCount = 30,
    ): array {
        $memcachedKey = md5(__METHOD__ . serialize(func_get_args()));
        if (null !== ($questionEntities = $this->memcachedService->get($memcachedKey))) {
            return $questionEntities;
        }

        $questionIds = $this->getQuestionIds(
            $query,
            $page,
            $queryWordCount,
        );

        $questionEntities = [];

        foreach ($questionIds as $questionId) {
            $questionEntity = $this->questionFactory->buildFromQuestionId(intval($questionId));

            if (isset($questionEntity->deletedDateTime)) {
                continue;
            }

            $questionEntities[] = $questionEntity;
        }

        $this->memcachedService->setForDays(
            $memcachedKey,
            $questionEntities,
            1
        );
        return $questionEntities;
    }

    protected function getQuestionIds(
        string $query,
        int $page,
        int $queryWordCount = 30,
    ): array {
        $query = strtolower($query);
        $query = $this->keepFirstWordsService->keepFirstWords(
            $query,
            $queryWordCount,
        );

        $questionIds = [];

        $result = $this->getPdoResult($query, $page);
        foreach ($result as $array) {
            $questionIds[] = $array['question_id'];
        }

        return $questionIds;
    }

    protected function getPdoResult(string $query, int $page): Result
    {
        try {
            return $this->questionSearchMessageTable
                ->selectQuestionIdWhereMatchAgainstOrderByScoreDesc(
                    $query,
                    ($page - 1) * 100,
                    100,
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
