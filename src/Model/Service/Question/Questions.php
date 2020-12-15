<?php
namespace MonthlyBasis\Question\Model\Service\Question;

use Generator;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class Questions implements QuestionService\Question\QuestionsInterface
{
    public function __construct(
        QuestionFactory\Question $questionFactory,
        QuestionTable\Question $questionTable
    ) {
        $this->questionFactory = $questionFactory;
        $this->questionTable   = $questionTable;
    }

    public function getQuestions(
        int $page,
        int $questionsPerPage = 100
    ): Generator {
        if ($page > 50) {
            throw new Exception('Invalid page number.');
        }

        $generator = $this->questionTable
            ->selectWhereDeletedDatetimeIsNullOrderByCreatedDateTimeDesc(
                ($page - 1) * $questionsPerPage,
                $questionsPerPage
            );
        foreach ($generator as $array) {
            yield $this->questionFactory->buildFromArray($array);
        }
    }
}
