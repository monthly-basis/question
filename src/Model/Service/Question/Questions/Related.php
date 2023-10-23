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

class Related
{
    protected int $recursionIteration = 0;

    public function __construct(
        QuestionEntity\Config $configEntity,
        QuestionFactory\Question $questionFactory,
        QuestionTable\QuestionSearchMessage $questionSearchMessageTable
    ) {
        $this->configEntity               = $configEntity;
        $this->questionFactory            = $questionFactory;
        $this->questionSearchMessageTable = $questionSearchMessageTable;
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

        try {
            $result = $this->questionSearchMessageTable
                ->selectQuestionIdWhereMatchMessageAgainstAndQuestionIdNotEquals(
                    query: $query,
                    questionId: $questionEntity->questionId,
                    limitOffset: 0,
                    limitRowCount: $limit,
                );

            foreach ($result as $array) {
                $questionEntity = $this->questionFactory->buildFromQuestionId(
                    (int) $array['question_id']
                );

                try {
                    $questionEntity->getDeletedDatetime();
                    continue;
                } catch (TypeError $typeError) {
                    // Do nothing.
                }

                yield $questionEntity;
            }
        } catch (InvalidQueryException) {
            yield from [];
        }
    }
}
