<?php
namespace MonthlyBasis\Question\Model\Service\Question\Questions\Search;

use Generator;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use Laminas\Db\Adapter\Exception\InvalidQueryException;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\String\Model\Service as StringService;
use TypeError;

class Results
{
    public function __construct(
        QuestionFactory\Question $questionFactory,
        QuestionTable\Question $questionTable,
        QuestionTable\QuestionSearchMessage $questionSearchMessageTable,
        StringService\KeepFirstWords $keepFirstWordsService
    ) {
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
            sleep(1);
            return $this->getPdoResult($query, $page);
        }
    }
}
