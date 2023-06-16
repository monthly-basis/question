<?php
namespace MonthlyBasis\QuestionTest\View\Helper\Questions;

use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\View\Helper as QuestionHelper;
use PHPUnit\Framework\TestCase;

class TrendingTest extends TestCase
{
    protected function setUp(): void
    {
        $this->hourServiceMock = $this->createMock(
            QuestionService\Question\Questions\MostPopular\Hour::class
        );

        $this->trendingHelper = new QuestionHelper\Questions\Trending(
            $this->hourServiceMock,
        );
    }

    public function test___invoke()
    {
        $generator = $this->trendingHelper->__invoke();
        $this->assertEmpty(iterator_to_array($generator));
    }
}
