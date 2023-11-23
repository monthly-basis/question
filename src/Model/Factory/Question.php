<?php
namespace MonthlyBasis\Question\Model\Factory;

use DateTime;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\User\Model\Factory as UserFactory;
use MonthlyBasis\User\Model\Service as UserService;

class Question
{
    public function __construct(
        protected QuestionTable\Question $questionTable,
        protected UserFactory\User $userFactory,
        protected UserService\DisplayNameOrUsername $displayNameOrUsernameService
    ) {
    }

    public function buildFromArray(
        array $array
    ): QuestionEntity\Question {
        $questionEntity = (static::getNewInstance())
            ->setCreatedDateTime(new DateTime($array['created_datetime']))
            ->setQuestionId($array['question_id'])
            ;

        if (isset($array['answer_count_cached'])) {
            $questionEntity->setAnswerCountCached($array['answer_count_cached']);
        }
        if (isset($array['created_ip'])) {
            $questionEntity->setCreatedIp($array['created_ip']);
        }
        if (isset($array['created_name'])) {
            $questionEntity->setCreatedName($array['created_name']);
        }
        if (isset($array['did_you_know'])) {
            $questionEntity->didYouKnow = $array['did_you_know'];
        }
        if (isset($array['image_prompt'])) {
            $questionEntity->imagePrompt = $array['image_prompt'];
        }
        if (isset($array['image_prompt_revised'])) {
            $questionEntity->imagePromptRevised = $array['image_prompt_revised'];
        }
        if (isset($array['image_rru_128x128_webp'])) {
            $questionEntity->imageRru128x128WebP = $array['image_rru_128x128_webp'];
        }
        if (isset($array['image_rru_512x512_webp'])) {
            $questionEntity->imageRru512x512WebP = $array['image_rru_512x512_webp'];
        }
        if (isset($array['image_rru_1024x1024_jpeg'])) {
            $questionEntity->imageRru1024x1024Jpeg = $array['image_rru_1024x1024_jpeg'];
        }
        if (isset($array['image_rru_1024x1024_png'])) {
            $questionEntity->imageRru1024x1024Png = $array['image_rru_1024x1024_png'];
        }
        if (isset($array['message'])) {
            $questionEntity->setMessage($array['message']);
        }
        if (isset($array['views'])) {
            $questionEntity->setViews((int) $array['views']);
        }
        if (isset($array['views_one_year'])) {
            $questionEntity->viewsOneYear = intval($array['views_one_year']);
        }
        if (isset($array['deleted_datetime'])) {
            $questionEntity->setDeletedDateTime(new DateTime($array['deleted_datetime']));
        }
        if (isset($array['deleted_user_id'])) {
            $questionEntity->setDeletedUserId($array['deleted_user_id']);
        }
        if (isset($array['deleted_reason'])) {
            $questionEntity->setDeletedReason($array['deleted_reason']);
        }
        if (isset($array['headline'])) {
            $questionEntity->setHeadline($array['headline']);
        }
        if (isset($array['modified_datetime'])) {
            $questionEntity->setModifiedDateTime(new DateTime($array['modified_datetime']));
        }
        if (isset($array['modified_reason'])) {
            $questionEntity->setModifiedReason($array['modified_reason']);
        }
        if (isset($array['modified_user_id'])) {
            $questionEntity->setModifiedUserId(intval($array['modified_user_id']));
        }
        if (isset($array['moved_country'])) {
            $questionEntity->setMovedCountry($array['moved_country']);
        }
        if (isset($array['moved_datetime'])) {
            $questionEntity->setMovedDateTime(new DateTime($array['moved_datetime']));
        }
        if (isset($array['moved_user_id'])) {
            $questionEntity->setMovedUserId($array['moved_user_id']);
        }
        if (isset($array['moved_language'])) {
            $questionEntity->setMovedLanguage($array['moved_language']);
        }
        if (isset($array['moved_question_id'])) {
            $questionEntity->setMovedQuestionId($array['moved_question_id']);
        }
        if (isset($array['slug'])) {
            $questionEntity->setSlug($array['slug']);
        }
        if (isset($array['subject'])) {
            $questionEntity->setSubject($array['subject']);
        }
        if (isset($array['user_id'])) {
            $questionEntity->setCreatedUserId((int) $array['user_id']);

            $userEntity = $this->userFactory->buildFromUserId(
                $array['user_id']
            );
            $questionEntity->setCreatedName(
                $this->displayNameOrUsernameService->getDisplayNameOrUsername(
                    $userEntity
                )
            );
        }

        return $questionEntity;
    }

    /**
     * @deprecated Use QuestionFactory\Question\FromQuestionId::buildFromQuestionId
     */
    public function buildFromQuestionId(
        int $questionId
    ): QuestionEntity\Question {
        return $this->buildFromArray(
            $this->questionTable->selectWhereQuestionId($questionId)
        );
    }

    protected function getNewInstance(): QuestionEntity\Question
    {
        return new QuestionEntity\Question();
    }
}
