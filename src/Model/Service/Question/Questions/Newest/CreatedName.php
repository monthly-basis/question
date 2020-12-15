<?php
namespace MonthlyBasis\Question\Model\Service\Question\Questions\Newest;

use Generator;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class CreatedName
{
    public function __construct(
        QuestionFactory\Question $questionFactory,
        QuestionTable\Question\CreatedName $questionCreatedNameTable
    ) {
        $this->questionFactory          = $questionFactory;
        $this->questionCreatedNameTable = $questionCreatedNameTable;
    }

    public function getNewestQuestions(
        string $createdName,
        int $page
    ): Generator {
        $generator = $this->questionCreatedNameTable->selectWhereCreatedName(
            $createdName,
            ($page - 1) * 100,
            100
        );

        foreach ($generator as $array) {
            yield $this->questionFactory->buildFromArray($array);
        }
    }
}
