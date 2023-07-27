<?php
namespace MonthlyBasis\Question\Model\Service\Answers;

use Generator;
use Laminas\Db\Adapter\Exception\InvalidQueryException;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class Related
{
    public function __construct(
        protected QuestionFactory\Answer\FromAnswerId $fromAnswerIdFactory,
        protected QuestionTable\AnswerSearchMessage $answerSearchMessageTable,
    ) {
    }

    public function getRelated(
        QuestionEntity\Answer $answerEntity,
        int $limitOffset = 0,
        int $limitRowCount = 10,
    ): Generator {
        $query = $answerEntity->message;
        $query = strip_tags($query);
        $query = preg_replace('/\s+/s', ' ', $query);
        $words = explode(' ', $query, 21);
        $query = implode(' ', array_slice($words, 0, 16));
        $query = strtolower($query);

        try {
            $result = $this->answerSearchMessageTable
                ->selectAnswerIdWhereMatchMessageAgainstAndAnswerIdNotEquals(
                    query: $query,
                    answerId: $answerEntity->answerId,
                    limitOffset: $limitOffset,
                    limitRowCount: $limitRowCount,
                );
        } catch (InvalidQueryException $invalidQueryException) {
            // return; followed by yield; returns empty generator.
            return;
            yield;
        }

        foreach ($result as $array) {
            $answerEntity = $this->fromAnswerIdFactory->buildFromAnswerId(
                (int) $array['question_id']
            );

            if (isset($answerEntity->deletedDateTime)) {
                continue;
            }

            yield $answerEntity;
        }
    }
}
