<?php

use MonthlyBasis\Question\Controller as QuestionController;

return [
    'factories' => [
        QuestionController\Index::class => function ($sm) {
            return new QuestionController\Index();
        },
        QuestionController\Questions::class => function ($sm) {
            return new QuestionController\Questions();
        },
        QuestionController\Questions\Ask::class => function ($sm) {
            return new QuestionController\Questions\Ask();
        },
    ],
];
