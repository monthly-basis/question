<?php
namespace MonthlyBasis\Question\Model\Service\Question\Questions;

use Exception;
use Generator;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use Laminas\Db\Adapter\Exception\InvalidQueryException;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use TypeError;

class Similar
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

    public function getSimilar(
        QuestionEntity\Question $questionEntity,
        int $maxResults
    ): Generator {
        $query = $questionEntity->getMessage();
        $query = strip_tags($query);
        $query = preg_replace('/\s+/s', ' ', $query);
        $words = explode(' ', $query, 21);
        $query = implode(' ', array_slice($words, 0, 16));
        $query = strtolower($query);

        $questionIdStored = $questionEntity->getQuestionId();

        $result = $this->getPdoResult($query, $maxResults);

        $questionsYielded = 0;

        foreach ($result as $array) {
            if ($questionsYielded >= $maxResults) {
                break;
            }

            if ($array['question_id'] == $questionIdStored) {
                continue;
            }

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
            $questionsYielded++;
        }
    }

    protected function getPdoResult(string $query, int $maxResults): Result
    {
        try {
            return $this->questionSearchMessageTable
                ->selectQuestionIdWhereMatchAgainstOrderByViewsDescScoreDesc(
                    $query,
                    0,
                    100,
                    0,
                    $maxResults + 1
                );
        } catch (InvalidQueryException $invalidQueryException) {
            sleep($this->configEntity['sleep-when-result-unavailable'] ?? 1);
            $this->recursionIteration++;
            if ($this->recursionIteration >= 5) {
                throw new Exception('Unable to get PDO result.');
            }
            return $this->getPdoResult($query, $maxResults);
        }
    }
}
