<?php
namespace MonthlyBasis\Question\Model\Command\Answers;

use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\Laminas\Model\Db\Table;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use Orhanerday\OpenAi\OpenAi;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Import extends Command
{
    protected int $longestTimeTaken = 0;

    public function __construct(
        protected array $openAiConfig,
        protected \Laminas\Db\Sql\Sql $sql,
    ) {
        $this->adapter = $this->sql->getAdapter();
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption(
                'iterations',
                'i',
                InputOption::VALUE_REQUIRED,
                'Number of iterations',
                1,
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        echo "Importing answers...\n";
        $iterations = intval($input->getOption('iterations'));

        for ($iteration = 0; $iteration < $iterations; $iteration++) {
            echo "Iteration start.\n";
            echo "Iteration $iteration of $iterations\n";
            echo "Importing answer...\n";
            $questionArray = $this->selectQuestion()->current();

            if (empty($questionArray)) {
                break;
            }

            var_dump($questionArray);
            $this->insertIntoLog($questionArray['question_id']);
            $answerMessage = $this->getAnswerMessage($questionArray);

            $answerMessage = trim($answerMessage);

            if (empty($answerMessage)) {
                echo "Answer was empty and therefore not imported.\n";
                continue;
            }
            $this->insertIntoAnswer(
                questionId: $questionArray['question_id'],
                answerMessage: $answerMessage,
            );
            $this->updateQuestionSetAnswerCountCached(
                questionId: $questionArray['question_id'],
            );
            echo "Imported answer.\n";

            /*
             * API rate limit is 20 requests per minute, which is 1 request every 3 seconds. Therefore we should loop no more than once every 6 seconds so that other uses of the API do not get denied by the rate limit.
             *
             * However, since, at the time of this programming, GPT 4
             * is much slower, we can disregard sleep altogether.
             */
            //echo "Sleeping for 9 seconds..\n";
            //sleep(9);

            echo "Iteration end.\n\n";
        }

        echo "Done importing answers.\n";
        echo "Longest time taken: {$this->longestTimeTaken} seconds\n";
        return Command::SUCCESS;
    }

    protected function selectQuestion(): Result
    {
        $sql = '
            SELECT `question`.`question_id`
                 , `question`.`message`

              FROM `question`

              LEFT
              JOIN `log_question_open_ai`
             USING (`question_id`)

             WHERE `question`.`moved_datetime` IS NULL
               AND `question`.`deleted_datetime` IS NULL
               AND `question`.`answer_count_cached` = 0
               AND `log_question_open_ai`.`question_id` IS NULL

             ORDER
                BY `question`.`created_datetime` DESC

             LIMIT 1
                 ;
        ';
        return $this->adapter->query($sql)->execute();
    }

    protected function insertIntoLog(int $questionId): Result
    {
        $sql = '
            INSERT
              INTO `log_question_open_ai`
                   (`question_id`)
            VALUES (?)
                 ;
        ';
        $parameters = [
            $questionId,
        ];
        return $this->adapter->query($sql)->execute($parameters);
    }

    protected function getAnswerMessage(array $questionArray): string
    {
        $open_ai = new OpenAi($this->openAiConfig['secret-key']);
        $open_ai->setTimeout(600);

        $messages = [
            [
                'role'    => 'user',
                'content' => $questionArray['message'],
            ]
        ];
        $timeStart = time();
        $completeJson = $open_ai->chat([
            'model' => 'gpt-4',
            'messages' => $messages,
            'temperature' => 1,
        ]);
        $timeEnd = time();
        $timeTaken = $timeEnd - $timeStart;
        echo 'Took ' . $timeTaken . " seconds\n";

        if ($timeTaken > $this->longestTimeTaken) {
            $this->longestTimeTaken = $timeTaken;
        }

        $completeArray = json_decode($completeJson, true);
        var_dump($completeArray);

        $message = $completeArray['choices'][0]['message']['content'] ?? '';
        return $message;
    }

    protected function insertIntoAnswer(
        int $questionId,
        string $answerMessage,
    ): Result {
        $sql = '
            INSERT
              INTO `answer`
                   (`question_id`, `message`, `imported`)
            VALUES (?, ?, ?)
                 ;
        ';
        $parameters = [
            $questionId,
            $answerMessage,
            1,
        ];
        return $this->adapter->query($sql)->execute($parameters);
    }

    protected function updateQuestionSetAnswerCountCached(
        int $questionId,
    ): Result {
        $sql = '
            UPDATE `question`
               SET `answer_count_cached` = `answer_count_cached` + 1
             WHERE `question_id` = ?
                 ;
        ';
        $parameters = [
            $questionId,
        ];
        return $this->adapter->query($sql)->execute($parameters);
    }
}
