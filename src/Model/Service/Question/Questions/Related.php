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
    public function __construct(
        protected MemcachedService\Memcached $memcachedService,
        protected QuestionFactory\Question $questionFactory,
        protected QuestionTable\QuestionSearchMessage $questionSearchMessageTable
    ) {
    }

    public function getRelated(
        QuestionEntity\Question $questionEntity,
        int $limit = 10,
        int $queryWordCount = 16,
        array $questionIdNotIn = [],
    ): Generator {
        $query = $questionEntity->message;
        $query = strip_tags($query);
        $query = preg_replace('/\s+/s', ' ', $query);
        $words = explode(' ', $query, ($queryWordCount + 1));
        $query = implode(' ', array_slice($words, 0, $queryWordCount));
        $query = strtolower($query);

        if (empty($questionIdNotIn)) {
            $questionIdNotIn[] = $questionEntity->questionId;
        }

        $questionIds = $this->getQuestionIds(
            $query,
            $limit,
            $questionIdNotIn,
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
        string $query,
        int $limit,
        array $questionIdNotIn,
    ): array {
        $memcachedKey = md5(__METHOD__ . serialize(func_get_args()));
        if (null !== ($questionIds = $this->memcachedService->get($memcachedKey))) {
            return $questionIds;
        }

        try {
            $result = $this->questionSearchMessageTable
                ->selectQuestionIdWhereMatchAgainstOrderByViewsDescScoreDesc(
                    query: $query,
                    outerLimitOffset: 0,
                    outerLimitRowCount: $limit,
                    questionIdNotIn: $questionIdNotIn,
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
