<?php
namespace MonthlyBasis\Question\Model\Service\Question\Questions\Newest;

use Generator;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class WithAnswers
{
    public function __construct(
        QuestionFactory\Answer $answerFactory,
        QuestionService\Question\QuestionsInterface $questionsInterface,
        QuestionTable\Answer $answerTable
    ) {
        $this->answerFactory      = $answerFactory;
        $this->questionsInterface = $questionsInterface;
        $this->answerTable        = $answerTable;
    }

    public function getQuestionsWithAnswers(
        int $page
    ): Generator {
        $questionEntities = $this->questionsInterface->getQuestions(
            $page,
            50
        );

        foreach ($questionEntities as $questionEntity) {
            $answerEntities = [];
            $result = $this->answerTable
                ->selectWhereQuestionIdAndDeletedDatetimeIsNullOrderByCreatedDateTimeAsc(
                    $questionEntity->getQuestionId()
                );
            foreach ($result as $answerArray) {
                $answerEntities[] = $this->answerFactory->buildFromArray($answerArray);
            }
            $questionEntity->setAnswers($answerEntities);

            yield $questionEntity;
        }
    }
}
