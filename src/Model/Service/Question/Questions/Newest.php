<?php
namespace MonthlyBasis\Question\Model\Service\Question\Questions;

use Generator;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class Newest
{
    public function __construct(
        protected QuestionFactory\Question $questionFactory,
        protected QuestionTable\Question\DeletedDatetimeCreatedDatetime $deletedDatetimeCreatedDatetimeTable
    ) {
    }

    public function getNewestQuestions(
        int $page = 1
    ): Generator {
        $generator = $this->deletedDatetimeCreatedDatetimeTable
            ->selectWhereDeletedDatetimeIsNullOrderByCreatedDatetimeDesc(
                ($page - 1) * 100,
                100
            );
        foreach ($generator as $array) {
            yield $this->questionFactory->buildFromArray($array);
        }
    }
}
