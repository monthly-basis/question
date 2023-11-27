<?php
namespace MonthlyBasis\Question\Model\Service\Question\Questions;

use Error;
use Exception;
use Generator;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use Laminas\Db\Adapter\Exception\InvalidQueryException;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use TypeError;

class Similar
{
    public function __construct(
        protected QuestionFactory\Question $questionFactory,
        protected QuestionTable\QuestionSearchSimilar $questionSearchSimilarTable,
    ) {}

    public function getSimilar(
        QuestionEntity\Question $questionEntity,
    ): Generator {
        $query = $questionEntity->message;
        $query = strip_tags($query);
        $query = preg_replace('/\s+/s', ' ', $query);
        $words = explode(' ', $query, 21);
        $query = implode(' ', array_slice($words, 0, 16));
        $query = strtolower($query);

        $questionIds = $this->getQuestionIds(
            questionEntity: $questionEntity,
            query: $query,
        );

        foreach ($questionIds as $questionId) {
            $questionEntity = $this->questionFactory->buildFromQuestionId(
                intval($questionId)
            );

            if (isset($questionEntity->deletedDateTime)) {
                continue;
            }

            yield $questionEntity;
        }
    }

    protected function getQuestionIds(
        QuestionEntity\Question $questionEntity,
        string $query,
    ): array {
        try {
            $result = $this->questionSearchSimilarTable
                ->selectQuestionIdWhereMatchMessageAgainstAndQuestionIdNotEquals(
                    query: $query,
                    questionId: $questionEntity->questionId,
                    limitOffset: 0,
                    limitRowCount: 10,
                );
        } catch (InvalidQueryException $invalidQueryException) {
            return [];
        }

        $questionIds = [];
        foreach ($result as $array) {
            $questionIds[] = intval($array['question_id']);
        }
        return $questionIds;
    }
}
