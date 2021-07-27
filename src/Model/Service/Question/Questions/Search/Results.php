<?php
namespace MonthlyBasis\Question\Model\Service\Question\Questions\Search;

use Exception;
use Generator;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use Laminas\Db\Adapter\Exception\InvalidQueryException;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\String\Model\Service as StringService;
use TypeError;

class Results
{
    protected int $recursionIteration = 0;

    public function __construct(
        QuestionEntity\Config $configEntity,
        QuestionFactory\Question $questionFactory,
        QuestionTable\Question $questionTable,
        QuestionTable\QuestionSearchMessage $questionSearchMessageTable,
        StringService\KeepFirstWords $keepFirstWordsService
    ) {
        $this->configEntity               = $configEntity;
        $this->questionFactory            = $questionFactory;
        $this->questionTable              = $questionTable;
        $this->questionSearchMessageTable = $questionSearchMessageTable;
        $this->keepFirstWordsService      = $keepFirstWordsService;
    }

    public function getResults(string $query, int $page): Generator
    {
        $query = strtolower($query);
        $query = $this->keepFirstWordsService->keepFirstWords(
            $query,
            16
        );

        $result = $this->getPdoResult($query, $page);

        foreach ($result as $array) {
            $questionEntity = $this->questionFactory->buildFromQuestionId($array['question_id']);

            try {
                $questionEntity->getDeletedDatetime();
                continue;
            } catch (TypeError $typeError) {
                // Do nothing.
            }

            yield $questionEntity;
        }
    }

    protected function getPdoResult(string $query, int $page): Result
    {
        try {
            return $this->questionSearchMessageTable
                ->selectQuestionIdWhereMatchAgainstOrderByViewsDescScoreDesc(
                    $query,
                    ($page - 1) * 100,
                    100,
                    0,
                    100
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
