<?php

use MonthlyBasis\Question\Controller as QuestionController;

return [
    'routes' => [
        'monthly-basis/question/questions' => [
            'type' => Literal::class,
            'options' => [
                'route'    => '/questions',
                'defaults' => [
                    'controller' => QuestionController\Questions::class,
                    'action'     => 'index',
                ],
            ],
            'priority' => -1,
            'may_terminate' => true,
            'child_routes' => [
                'ask' => [
                    'type' => Literal::class,
                    'options' => [
                        'route'    => '/ask',
                        'defaults' => [
                            'controller' => QuestionController\Questions\Ask::class,
                            'action'     => 'ask',
                        ],
                    ],
                    'may_terminate' => true,
                ],
            ]
        ],
    ],
];
