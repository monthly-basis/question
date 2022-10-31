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
    protected int $recursionIteration = 0;

    public function __construct(
        QuestionEntity\Config $configEntity,
        QuestionFactory\Question $questionFactory,
        QuestionService\Question\HeadlineAndMessage $headlineAndMessageService,
        QuestionTable\QuestionSearchMessage $questionSearchMessageTable
    ) {
        $this->configEntity               = $configEntity;
        $this->questionFactory            = $questionFactory;
        $this->headlineAndMessageService  = $headlineAndMessageService;
        $this->questionSearchMessageTable = $questionSearchMessageTable;
    }

    public function getSimilar(
        QuestionEntity\Question $questionEntity,
        int $questionSearchMessageLimitOffset = 0,
        int $questionSearchMessageLimitRowCount = 20,
        int $outerLimitOffset = 0,
        int $outerLimitRowCount = 20,
    ): Generator {
        $query = $this->headlineAndMessageService->getHeadlineAndMessage(
            $questionEntity
        );
        $query = strip_tags($query);
        $query = preg_replace('/\s+/s', ' ', $query);
        $words = explode(' ', $query, 21);
        $query = implode(' ', array_slice($words, 0, 16));
        $query = strtolower($query);

        $result = $this->getPdoResult(
            questionEntity: $questionEntity,
            query: $query,
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
    }

    protected function getPdoResult(
        QuestionEntity\Question $questionEntity,
        string $query,
    ): Result {
        try {
            return $this->questionSearchMessageTable
                ->selectQuestionIdWhereMatchMessageAgainstAndQuestionIdNotEquals(
                    query: $query,
                    questionId: $questionEntity->getQuestionId(),
                    limitOffset: 0,
                    limitRowCount: 10,
                );
        } catch (InvalidQueryException $invalidQueryException) {
            sleep($this->configEntity['sleep-when-result-unavailable'] ?? 1);
            $this->recursionIteration++;
            if ($this->recursionIteration >= 5) {
                throw new Exception('Unable to get PDO result.');
            }
            return $this->getPdoResult(
                questionEntity: $questionEntity,
                query: $query,
            );
        }
    }
}
