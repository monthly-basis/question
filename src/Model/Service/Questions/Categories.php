<?php
namespace MonthlyBasis\Question\Model\Service\Questions;

use MonthlyBasis\Question\Model\Service as QuestionService;

class Categories
{
    public function __construct(
        protected QuestionService\Question\Categories $questionCategoriesService,
    ) {
    }

    public function getQuestionsCategories(
        array $questionEntities
    ): array {
        $questionsCategories = [];

        foreach ($questionEntities as $questionEntity) {
            $questionCategories = $this->questionCategoriesService->getCategories(
                $questionEntity
            );
            foreach ($questionCategories as $categoryEntity) {
                $questionsCategories[$categoryEntity->categoryId] = $categoryEntity;
            }
        }

        usort($questionsCategories, function ($categoryEntity1, $categoryEntity2) {
            return strcmp($categoryEntity1->name, $categoryEntity2->name);
        });

        return $questionsCategories;
    }
}
