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

class View extends AbstractActionController
{
    public function __construct(
        protected QuestionFactory\Answer $answerFactory,
        protected QuestionFactory\Question\FromSlug $questionFromSlugFactory,
        protected QuestionService\Answer\Answers $answersService,
        protected QuestionService\LogQuestionView\ConditionallyInsert $conditionallyInsertService,
        protected QuestionService\Question\Questions\Related $relatedService,
        protected QuestionService\Question\Url $urlService,
        protected QuestionService\Question\Views\Increment\Conditionally $conditionallyIncrementViewsService,
        protected QuestionService\QuestionFromAnswer $questionFromAnswerService,
    ) {}

    public function viewAction()
    {
        $slug = $this->params()->fromRoute('slug');

        try {
            $questionEntity = $this->questionFromSlugFactory->buildFromSlug(
                $slug
            );
        } catch (Error) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $url = $this->urlService->getUrl($questionEntity);
        if ('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] != $url) {
            return $this->redirect()->toUrl($url)->setStatusCode(301);
        }

        $relatedQuestions = $this->relatedService->getRelated(
            questionEntity: $questionEntity,
        );
        $answerEntities = $this->answersService->getAnswers(
            questionEntity: $questionEntity,
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
            'relatedQuestions' => $relatedQuestions,
        ];
    }
}
