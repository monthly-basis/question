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
        protected GroupService\VisitorLoggedInAndInGroupName $visitorLoggedInAndInGroupNameService,
        protected QuestionFactory\Answer $answerFactory,
        protected QuestionService\Answer\Answers $answersService,
        protected QuestionFactory\Question $questionFactory,
        protected QuestionService\Question\Questions\Related $relatedService,
        protected QuestionService\Question\QuestionViewNotBotLog\ConditionallyInsert $conditionallyInsertService,
        protected QuestionService\Question\Url $urlService,
        protected QuestionService\Question\Views\Increment\Conditionally $conditionallyIncrementViewsService,
        protected QuestionService\QuestionFromAnswer $questionFromAnswerService,
        protected VoteFactory\VoteByIp $voteByIpFactory,
        protected VoteFactory\Votes $votesFactory,
        protected VoteService\VoteByIp\Multiple $multipleVoteByIpService,
        protected VoteService\Votes\Multiple $multipleVotesService
    ) {}

    public function viewAction()
    {
        $questionId = $this->params()->fromRoute('questionId');

        try {
            $questionEntity = $this->questionFactory->buildFromQuestionId(
                $questionId
            );
        } catch (TypeError $typeError) {
            try {
                $answerEntity = $this->answerFactory->buildFromAnswerId(
                    $questionId
                );
                $questionEntity = $this->questionFromAnswerService->getQuestionFromAnswer(
                    $answerEntity
                );
                $url = $this->urlService->getUrl(
                    $questionEntity
                );
                return $this->redirect()->toUrl($url)->setStatusCode(301);
            } catch (TypeError $typeError) {
                $url = 'https://' . $_SERVER['HTTP_HOST'];
                return $this->redirect()->toUrl($url)->setStatusCode(301);
            }
        }

        try {
            $deletedDateTime = $questionEntity->getDeletedDateTime();

            if (
                !$this->visitorLoggedInAndInGroupNameService->isVisitorLoggedInAndInGroupName('Admin')
                && !$this->visitorLoggedInAndInGroupNameService->isVisitorLoggedInAndInGroupName('Webmaster')
            ) {
                $url = 'https://' . $_SERVER['HTTP_HOST'];
                return $this->redirect()->toUrl($url)->setStatusCode(301);
            }
        } catch (TypeError $typeError) {
            // Do nothing.
        }

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

        /*
        usort($answerEntities, function (QuestionEntity\Answer $answerEntity1, QuestionEntity\Answer $answerEntity2) {
            if ($answerEntity1->getRating() != $answerEntity2->getRating()) {
                return $answerEntity2->getRating() - $answerEntity1->getRating();
            }

            return $answerEntity1->getCreatedDatetime()->getTimestamp() - $answerEntity2->getCreatedDatetime()->getTimestamp();
        });
         */

        $voteByIpEntity = $this->voteByIpFactory->buildFromIpEntityTypeIdTypeId(
            $_SERVER['REMOTE_ADDR'],
            1,
            $questionEntity->getQuestionId()
        );
        $voteByIpEntities = $this->multipleVoteByIpService->getMultiple(
            $_SERVER['REMOTE_ADDR'],
            2,
            $answerEntityIds
        );

        $votesEntity = $this->votesFactory->buildFromEntityTypeIdTypeId(
            1,
            $questionEntity->getQuestionId()
        );

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
            'voteByIpEntity'   => $voteByIpEntity,
            'voteByIpEntities' => $voteByIpEntities,
            'votesEntity'      => $votesEntity,
        ];
    }
}
