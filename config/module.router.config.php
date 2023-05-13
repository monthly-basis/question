<?php

use MonthlyBasis\Question\Controller as QuestionController;

return [
    'routes' => [
        'questions' => [
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
                'view' => [
                    'type' => Regex::class,
                    'options' => [
                        'regex'    => '/[\w\-]+',
                        'defaults' => [
                            'controller' => QuestionController\Questions\View::class,
                            'action'     => 'view',
                        ],
                        'spec' => '',
                    ],
                    'may_terminate' => true,
                ],
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
