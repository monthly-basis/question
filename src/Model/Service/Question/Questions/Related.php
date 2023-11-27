<?php
namespace MonthlyBasis\Question\Model\Service\Question\Questions;

use Error;
use Exception;
use Generator;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use Laminas\Db\Adapter\Exception\InvalidQueryException;
use MonthlyBasis\Memcached\Model\Service as MemcachedService;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use TypeError;

class Related
{
    protected int $recursionIteration = 0;

    public function __construct(
        protected MemcachedService\Memcached $memcachedService,
        protected QuestionEntity\Config $configEntity,
        protected QuestionFactory\Question $questionFactory,
        protected QuestionTable\QuestionSearchMessage $questionSearchMessageTable
    ) {
    }

    public function getRelated(
        QuestionEntity\Question $questionEntity,
        int $limit = 10,
        int $queryWordCount = 30,
    ): Generator {
        $query = $questionEntity->message;
        $query = strip_tags($query);
        $query = preg_replace('/\s+/s', ' ', $query);
        $words = explode(' ', $query, ($queryWordCount + 1));
        $query = implode(' ', array_slice($words, 0, $queryWordCount));
        $query = strtolower($query);

        $questionIds = $this->getQuestionIds(
            $questionEntity->questionId,
            $query,
            $limit,
        );

        foreach ($questionIds as $questionId) {
            $questionEntity = $this->questionFactory->buildFromQuestionId(
                $questionId
            );

            if (isset($questionEntity->deletedDateTime)) {
                continue;
            }

            yield $questionEntity;
        }
    }

    protected function getQuestionIds(
        int $questionId,
        string $query,
        int $limit,
    ): array {
        $memcachedKey = md5(__METHOD__ . serialize(func_get_args()));
        if (null !== ($questionIds = $this->memcachedService->get($memcachedKey))) {
            return $questionIds;
        }

        try {
            $result = $this->questionSearchMessageTable
                ->selectQuestionIdWhereMatchMessageAgainstAndQuestionIdNotEquals(
                    query: $query,
                    questionId: $questionId,
                    limitOffset: 0,
                    limitRowCount: $limit,
                );
        } catch (InvalidQueryException) {
            return [];
        }

        $questionIds = [];
        foreach ($result as $array) {
            $questionIds[] = intval($array['question_id']);
        }

        $this->memcachedService->setForDays(
            $memcachedKey,
            $questionIds,
            1
        );
        return $questionIds;
    }
}
