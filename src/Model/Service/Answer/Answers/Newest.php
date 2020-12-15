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

    public function getNewestAnswers(): Generator
    {
        $generator = $this->deletedDatetimeCreatedDatetimeTable
            ->selectWhereDeletedDatetimeIsNullOrderByCreatedDatetimeDesc();

        foreach ($generator as $array) {
            yield $this->answerFactory->buildFromArray($array);
        }
    }
}
