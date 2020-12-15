<?php
namespace MonthlyBasis\Question\Model\Service\Question;

use Generator;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;

interface QuestionsInterface
{
    public function __construct(
        QuestionFactory\Question $questionFactory,
        QuestionTable\Question $questionTable
    );

    public function getQuestions(
        int $page,
        int $questionsPerPage = 100
    ): Generator;
}
