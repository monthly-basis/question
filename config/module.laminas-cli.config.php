<?php

use MonthlyBasis\Question\Model\Command as QuestionCommand;

return [
    'commands' => [
        'monthly-basis:question:import-answers' => QuestionCommand\Answers\Import::class,
    ],
];
