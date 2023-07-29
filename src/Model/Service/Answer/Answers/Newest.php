<?php
namespace MonthlyBasis\Question\Model\Service\Answer\Answers;

use Generator;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class Newest
{
    public function __construct(
        QuestionFactory\Answer $answerFactory,
        QuestionTable\Answer\DeletedDatetimeCreatedDatetime $deletedDatetimeCreatedDatetimeTable
    ) {
        $this->answerFactory                       = $answerFactory;
        $this->deletedDatetimeCreatedDatetimeTable = $deletedDatetimeCreatedDatetimeTable;
    }

    public function getNewestAnswers(int $limit = 100): Generator
    {
        $result = $this->deletedDatetimeCreatedDatetimeTable
              ->selectWhereDeletedDatetimeIsNullOrderByCreatedDatetimeDesc(
                  limitRowCount: $limit
              );

        foreach ($result as $array) {
            yield $this->answerFactory->buildFromArray($array);
        }
    }
}
