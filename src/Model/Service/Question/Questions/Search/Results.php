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

class Results
{
    protected int $recursionIteration = 0;

    public function __construct(
        protected QuestionEntity\Config $configEntity,
        protected QuestionFactory\Question $questionFactory,
        protected QuestionTable\QuestionSearchMessage $questionSearchMessageTable,
        protected StringService\KeepFirstWords $keepFirstWordsService
    ) {
    }

    public function getResults(
        string $query,
        int $page,
        int $queryWordCount = 30,
    ): array {
        $query = strtolower($query);
        $query = $this->keepFirstWordsService->keepFirstWords(
            $query,
            $queryWordCount,
        );

        $result = $this->getPdoResult($query, $page);

        $questionEntities = [];

        foreach ($result as $array) {
            $questionEntity = $this->questionFactory->buildFromQuestionId($array['question_id']);

            if (isset($questionEntity->deletedDatetime)) {
                continue;
            }

            $questionEntities[] = $questionEntity;
        }

        return $questionEntities;
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
