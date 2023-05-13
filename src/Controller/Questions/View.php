<?php
namespace MonthlyBasis\Question\Controller\Questions;

use Application\Model\Factory as ApplicationFactory;
use Application\Model\Service as ApplicationService;
use Error;
use Laminas\Mvc\Controller\AbstractActionController;
use MonthlyBasis\Group\Model\Service as GroupService;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\String\Model\Service as StringService;
use MonthlyBasis\Vote\Model\Factory as VoteFactory;
use MonthlyBasis\Vote\Model\Service as VoteService;
use TypeError;

class View extends AbstractActionController
{
    public function __construct(
        protected QuestionFactory\Answer $answerFactory,
        protected QuestionFactory\Question $questionFactory,
        protected QuestionService\Answer\Answers $answersService,
        protected QuestionService\Question\Questions\Related $relatedService,
        protected QuestionService\Question\QuestionViewNotBotLog\ConditionallyInsert $conditionallyInsertService,
        protected QuestionService\Question\Url $urlService,
        protected QuestionService\Question\Views\Increment\Conditionally $conditionallyIncrementViewsService,
        protected QuestionService\QuestionFromAnswer $questionFromAnswerService,
    ) {}

    public function viewAction()
    {
        $questionId = $this->params()->fromRoute('questionId');

        $questionEntity = $this->questionFactory->buildFromQuestionId(
            $questionId
        );

        $url = $this->urlService->getUrl($questionEntity);
        if ('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] != $url) {
            return $this->redirect()->toUrl($url)->setStatusCode(301);
        }

        $relatedQuestions = $this->relatedService->getRelated(
            questionEntity: $questionEntity,
            questionSearchMessageLimitOffset: 0,
            questionSearchMessageLimitRowCount: 100,
            outerLimitOffset: 0,
            outerLimitRowCount: 10,
        );
        $answerEntities = $this->answersService->getAnswers(
            questionEntity: $questionEntity,
            withVotes: true,
        );

        $answerEntityIds = [];
        foreach ($answerEntities as $answerEntity) {
            $answerEntityIds[] = $answerEntity->getAnswerId();
        }

        $this->conditionallyIncrementViewsService->conditionallyIncrementViews(
            $questionEntity
        );
        $this->conditionallyInsertService->conditionallyInsert(
            $questionEntity
        );

        return [
            'answerEntities'   => $answerEntities,
            'questionEntity'   => $questionEntity,
            'relatedQuestions' => iterator_to_array($relatedQuestions),
        ];
    }
}
