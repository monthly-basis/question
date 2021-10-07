<?php
namespace MonthlyBasis\Question\Model\Service\Post\Posts\Newest;

use Generator;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\User\Model\Entity as UserEntity;

class User
{
    public function __construct(
        QuestionFactory\Answer $answerFactory,
        QuestionFactory\Question $questionFactory,
        QuestionTable\Post $postTable
    ) {
        $this->answerFactory   = $answerFactory;
        $this->questionFactory = $questionFactory;
        $this->postTable       = $postTable;
    }

    /**
     * @yield QuestionEntity\Post
     */
    public function getNewestPosts(UserEntity\User $userEntity): Generator
    {
        $result = $this->postTable->selectFromAnswerUnionQuestionOrderByCreatedDatetimeDesc(
            $userEntity->getUserId()
        );

        foreach ($result as $array) {
            if ($array['entity_type'] == 'answer') {
                yield $this->answerFactory->buildFromArray($array);
            } elseif ($array['entity_type'] == 'question') {
                yield $this->questionFactory->buildFromArray($array);
            }
        }
    }
}
