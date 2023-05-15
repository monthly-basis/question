<?php
namespace MonthlyBasis\Question\Controller\Questions;

use Exception;
use Laminas\Mvc\Controller\AbstractActionController;
use MonthlyBasis\Flash\Model\Service as FlashService;
use MonthlyBasis\Question\Controller as QuestionController;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use Orhanerday\OpenAi\OpenAi;

class Ask extends AbstractActionController
{
    public function __construct(
        protected array $openAiConfig,
        protected FlashService\Flash $flashService,
        protected QuestionFactory\Question $questionFactory,
        protected QuestionService\Question\IncrementAnswerCountCached $incrementAnswerCountCachedService,
        protected QuestionService\Question\Insert\Visitor $visitorInsertQuestionService,
        protected QuestionService\Question\Duplicate $duplicateService,
        protected QuestionService\Question\Url $urlService,
        protected QuestionTable\Answer $answerTable,
    ) {
    }

    public function askAction()
    {
        if (!empty($_POST)) {
            return $this->postAction();
        }
    }

    protected function postAction()
    {
        $errors = [];

        if (empty($_POST['message'])
            || empty(trim($_POST['message']))
        ) {
            $_POST['message'] = '';
            $errors[]         = 'You did not enter a question.';
        }

        if ($errors) {
            $this->flashService->set('message', $_POST['message']);
            $this->flashService->set('errors', $errors);
            return $this->redirect()->toRoute('monthly-basis-question-questions/ask')->setStatusCode(303);
        }

        try {
            $duplicateQuestion = $this->duplicateService->getDuplicate($_POST['message']);
            $this->flashService->set(
                'messageHtml',
                'This question has already been asked, see below.'
            );
            $url = $this->urlService->getUrl($duplicateQuestion);
            return $this->redirect()->toUrl($url)->setStatusCode(303);
        } catch (Exception $exception) {
            // Do nothing.
        }

        /*
         * No errors.
         */

        $this->flashService->set(
            'messageHtml',
            'Question successfully posted! <a href="/questions/ask" style="color: blue; white-space: nowrap;">Ask another question.</a>'
        );

        $questionEntity = $this->visitorInsertQuestionService->insert(
            withSlug: true,
        );

        $message = $this->getMessage($questionEntity);
        $message = trim($message);
        if (!empty($message)) {
            $this->answerTable->insert(
                values: [
                    'question_id' => $questionEntity->getQuestionId(),
                    'message'     => $message,
                    'imported'    => 1,
                ]
            );
            $this->incrementAnswerCountCachedService->incrementAnswerCountCached($questionEntity);
        }

        $url = $this->urlService->getUrl($questionEntity);
        return $this->redirect()->toUrl($url)->setStatusCode(303);
    }

    protected function getMessage(QuestionEntity\Question $questionEntity): string
    {
        ini_set('max_execution_time', 180);
        $open_ai = new OpenAi($this->openAiConfig['secret-key']);
        $open_ai->setTimeout(170);

        $completeJson = $open_ai->chat([
            'model' => 'gpt-4',
            'messages' => [
                ["role" => "user", "content" => $questionEntity->getMessage()]
            ],
            'temperature' => 1,
        ]);
        $completeArray = json_decode($completeJson, true);

        return $completeArray['choices'][0]['message']['content'] ?? '';
    }
}
