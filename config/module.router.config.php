<?php

use MonthlyBasis\Question\Controller as QuestionController;

return [
    'routes' => [
        'monthly-basis-question-questions' => [
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
                        'regex'    => '/(?<slug>[\w\-]+)',
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
            ],
        ],
        'monthly-basis-question-sitemaps' => [
            'type' => Literal::class,
            'options' => [
                'route'    => '/sitemaps',
            ],
            'may_terminate' => false,
            'child_routes' => [
                'questions' => [
                    'type' => Literal::class,
                    'options' => [
                        'route'    => '/questions.xml',
                        'defaults' => [
                            'controller' => QuestionController\Sitemaps\Questions::class,
                            'action'     => 'questions',
                        ],
                    ],
                    'may_terminate' => true,
                ],
            ],
        ],
    ],
];
