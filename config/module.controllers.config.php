<?php

use MonthlyBasis\Flash\Model\Service as FlashService;
use MonthlyBasis\Question\Controller as QuestionController;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;

return [
    'factories' => [
        QuestionController\Index::class => function ($sm) {
            return new QuestionController\Index();
        },
        QuestionController\Questions::class => function ($sm) {
            return new QuestionController\Questions();
        },
        QuestionController\Questions\Ask::class => function ($sm) {
            return new QuestionController\Questions\Ask(
                $sm->get('config')['monthly-basis']['open-ai'],
                $sm->get(FlashService\Flash::class),
                $sm->get(QuestionFactory\Question::class),
                $sm->get(QuestionService\Question\IncrementAnswerCountCached::class),
                $sm->get(QuestionService\Question\Insert\Visitor::class),
                $sm->get(QuestionService\Question\Duplicate::class),
                $sm->get(QuestionService\Question\Url::class),
                $sm->get(QuestionTable\Answer::class),
            );
        },
        QuestionController\Questions\View::class => function ($sm) {
            return new QuestionController\Questions\View(
            );
        },
    ],
];
