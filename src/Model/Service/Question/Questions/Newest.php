<?php
namespace MonthlyBasis\Question\Model\Service\Question\Questions;

use Generator;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class Newest
{
    public function __construct(
        QuestionFactory\Question $questionFactory,
        QuestionTable\Question\DeletedDatetimeCreatedDatetime $deletedDatetimeCreatedDatetimeTable
    ) {
        $this->questionFactory                     = $questionFactory;
        $this->deletedDatetimeCreatedDatetimeTable = $deletedDatetimeCreatedDatetimeTable;
    }

    public function getNewestQuestions(): Generator
    {
        $generator = $this->deletedDatetimeCreatedDatetimeTable
            ->selectWhereDeletedDatetimeIsNullOrderByCreatedDatetimeDesc(
                0,
                100
            );
        foreach ($generator as $array) {
            yield $this->questionFactory->buildFromArray($array);
        }
    }
}
