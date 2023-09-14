<?php
namespace MonthlyBasis\Question;

use Laminas\Db as LaminasDb;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use MonthlyBasis\Category\Model\Factory as CategoryFactory;
use MonthlyBasis\ContentModeration\View\Helper as ContentModerationHelper;
use MonthlyBasis\Group\Model\Service as GroupService;
use MonthlyBasis\Question\Controller as QuestionController;
use MonthlyBasis\Question\Model\Command as QuestionCommand;
use MonthlyBasis\Question\Model\Db as QuestionDb;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\Question\View\Helper as QuestionHelper;
use MonthlyBasis\Memcached\Model\Service as MemcachedService;
use MonthlyBasis\String\Model\Service as StringService;
use MonthlyBasis\Superglobal\Model\Service as SuperglobalService;
use MonthlyBasis\User\Model\Factory as UserFactory;
use MonthlyBasis\User\Model\Service as UserService;
use MonthlyBasis\User\View\Helper as UserHelper;
use MonthlyBasis\ContentModeration\Model\Service as ContentModerationService;
use MonthlyBasis\Flash\Model\Service as FlashService;
use MonthlyBasis\Vote\Model\Service as VoteService;

class Module
{
    public function getConfig()
    {
        $config = [
            'controllers' => include __DIR__ . '/../config/module.controllers.config.php',
            'laminas-cli' => include __DIR__ . '/../config/module.laminas-cli.config.php',

            'router' => include __DIR__ . '/../config/module.router.config.php',
            'view_manager' => [
                'template_path_stack' => [
                    'monthly-basis/question' => __DIR__ . '/../view',
                ],
            ],

            'view_helpers' => [
                'aliases' => [
                    'canBeUndeleted'                => QuestionHelper\Post\CanBeUndeleted::class,
                    'getAnswerFactory'              => QuestionHelper\Answer\Factory::class,
                    'getAnswerRootRelativeUrl'      => QuestionHelper\Answer\RootRelativeUrl::class,
                    'getAnswerTitle'                => QuestionHelper\Answer\Title::class,
                    'getAnswerUrl'                  => QuestionHelper\Answer\Url::class,
                    'getLinkToQuestionHtml'         => QuestionHelper\Question\Subject\LinkToQuestionHtml::class,
                    'getQuestionAuthor'             => QuestionHelper\Question\Author::class,
                    'getQuestionAuthorHtml'         => QuestionHelper\Question\Html\Author::class,
                    'getQuestionFactory'            => QuestionHelper\Question\Factory::class,
                    'getQuestionFromAnswer'         => QuestionHelper\QuestionFromAnswer::class,
                    'getQuestionH1Html'             => QuestionHelper\Question\Html\H1::class,
                    'getQuestionH2Html'             => QuestionHelper\Question\Html\H2::class,
                    'getQuestionH3Html'             => QuestionHelper\Question\Html\H3::class,
                    'getQuestionHeadlineOrSubject'  => QuestionHelper\Question\HeadlineOrSubject::class,
                    'getQuestionHeadlineAndMessage' => QuestionHelper\Question\HeadlineAndMessage::class,
                    'getQuestionLastmod'            => QuestionHelper\Question\Sitemap\Lastmod::class,
                    'getQuestionMessageHtml'        => QuestionHelper\Question\Html\Message::class,
                    'getQuestionPMessageHtml'       => QuestionHelper\Question\Html\P\Message::class,
                    'getQuestionPPreviewHtml'       => QuestionHelper\Question\Html\P\Preview::class,
                    'getQuestionPreviewHtml'       => QuestionHelper\Question\Html\Preview::class,
                    'getQuestionRootRelativeUrl'    => QuestionHelper\Question\RootRelativeUrl::class,
                    'getQuestionTitle'              => QuestionHelper\Question\Title::class,
                    'getQuestionUrl'                => QuestionHelper\Question\Url::class,
                    'getTrendingQuestions'          => QuestionHelper\Questions\Trending::class,
                ],
                'factories' => [
                    QuestionHelper\Answer\Factory::class => function($sm) {
                        return new QuestionHelper\Answer\Factory(
                            $sm->get(QuestionFactory\Answer::class)
                        );
                    },
                    QuestionHelper\Answer\RootRelativeUrl::class => function($sm) {
                        return new QuestionHelper\Answer\RootRelativeUrl(
                            $sm->get(QuestionService\Answer\RootRelativeUrl::class)
                        );
                    },
                    QuestionHelper\Answer\Title::class => function($sm) {
                        return new QuestionHelper\Answer\Title(
                            $sm->get(QuestionService\Answer\Title::class)
                        );
                    },
                    QuestionHelper\Answer\Url::class => function($sm) {
                        return new QuestionHelper\Answer\Url(
                            $sm->get(QuestionService\Answer\Url::class)
                        );
                    },
                    QuestionHelper\Post\CanBeUndeleted::class => function($sm) {
                        return new QuestionHelper\Post\CanBeUndeleted(
                            $sm->get(QuestionService\Post\CanBeUndeleted::class)
                        );
                    },
                    QuestionHelper\Question\HeadlineOrSubject::class => function($sm) {
                        return new QuestionHelper\Question\HeadlineOrSubject(
                            $sm->get(QuestionService\Question\HeadlineOrSubject::class)
                        );
                    },
                    QuestionHelper\Question\HeadlineAndMessage::class => function($sm) {
                        return new QuestionHelper\Question\HeadlineAndMessage(
                            $sm->get(QuestionService\Question\HeadlineAndMessage::class)
                        );
                    },
                    QuestionHelper\Question\Author::class => function($sm) {
                        return new QuestionHelper\Question\Author(
                            $sm->get(UserFactory\User::class),
                            $sm->get(UserService\DisplayNameOrUsername::class),
                        );
                    },
                    QuestionHelper\Question\Factory::class => function($sm) {
                        return new QuestionHelper\Question\Factory(
                            $sm->get(QuestionFactory\Question::class)
                        );
                    },
                    QuestionHelper\Question\Html\Author::class => function($sm) {
                        $vhm = $sm->get('ViewHelperManager');
                        return new QuestionHelper\Question\Html\Author(
                            $vhm->get(ContentModerationHelper\ReplaceAndEscape::class),
                            $vhm->get(ContentModerationHelper\ReplaceAndUrlencode::class),
                            $sm->get(UserFactory\User::class),
                            $vhm->get(UserHelper\UserHtml::class),
                        );
                    },
                    QuestionHelper\Question\Html\H1::class => function($sm) {
                        return new QuestionHelper\Question\Html\H1(
                            $sm->get(StringService\Escape::class)
                        );
                    },
                    QuestionHelper\Question\Html\H2::class => function($sm) {
                        return new QuestionHelper\Question\Html\H2(
                            $sm->get(StringService\Escape::class)
                        );
                    },
                    QuestionHelper\Question\Html\H3::class => function($sm) {
                        return new QuestionHelper\Question\Html\H3(
                            $sm->get(StringService\Escape::class)
                        );
                    },
                    QuestionHelper\Question\Html\Message::class => function($sm) {
                        return new QuestionHelper\Question\Html\Message(
                            $sm->get(ContentModerationService\ToHtml::class),
                        );
                    },
                    QuestionHelper\Question\Html\P\Message::class => function($sm) {
                        return new QuestionHelper\Question\Html\P\Message(
                            $sm->get(ContentModerationService\ToHtml::class)
                        );
                    },
                    QuestionHelper\Question\Html\P\Preview::class => function($sm) {
                        return new QuestionHelper\Question\Html\P\Preview(
                            $sm->get(ContentModerationService\Replace\BadWords::class),
                            $sm->get(ContentModerationService\Replace\LineBreaks::class),
                            $sm->get(ContentModerationService\Replace\Spaces::class),
                            $sm->get(StringService\Escape::class),
                            $sm->get(StringService\Shorten::class),
                        );
                    },
                    QuestionHelper\Question\Html\Preview::class => function($sm) {
                        return new QuestionHelper\Question\Html\Preview(
                            $sm->get(ContentModerationService\Replace\BadWords::class),
                            $sm->get(ContentModerationService\Replace\LineBreaks::class),
                            $sm->get(ContentModerationService\Replace\Spaces::class),
                            $sm->get(QuestionService\Question\RootRelativeUrl::class),
                            $sm->get(StringService\Escape::class),
                            $sm->get(StringService\Shorten::class),
                        );
                    },
                    QuestionHelper\Question\Sitemap\Lastmod::class => function($sm) {
                        return new QuestionHelper\Question\Sitemap\Lastmod(
                            $sm->get(QuestionTable\Answer::class)
                        );
                    },
                    QuestionHelper\Question\RootRelativeUrl::class => function($sm) {
                        return new QuestionHelper\Question\RootRelativeUrl(
                            $sm->get(QuestionService\Question\RootRelativeUrl::class)
                        );
                    },
                    QuestionHelper\Question\Subject\LinkToQuestionHtml::class => function($sm) {
                        return new QuestionHelper\Question\Subject\LinkToQuestionHtml(
                            $sm->get(ContentModerationService\Replace\Spaces::class),
                            $sm->get(QuestionService\Question\RootRelativeUrl::class),
                            $sm->get(StringService\Escape::class)

                        );
                    },
                    QuestionHelper\Question\Title::class => function($sm) {
                        return new QuestionHelper\Question\Title(
                            $sm->get(QuestionService\Question\Title::class)
                        );
                    },
                    QuestionHelper\Question\Url::class => function($sm) {
                        return new QuestionHelper\Question\Url(
                            $sm->get(QuestionService\Question\Url::class)
                        );
                    },
                    QuestionHelper\QuestionFromAnswer::class => function($sm) {
                        return new QuestionHelper\QuestionFromAnswer(
                            $sm->get(QuestionService\QuestionFromAnswer::class)
                        );
                    },
                    QuestionHelper\Questions\Trending::class => function($sm) {
                        return new QuestionHelper\Questions\Trending(
                            $sm->get(QuestionService\Question\Questions\MostPopular\Hour::class)
                        );
                    },
                ],
            ],
        ];

        return $config;
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                'laminas-db-sql-sql' => function ($sm) {
                    return new LaminasDb\Sql\Sql(
                        $sm->get('question')
                    );
                },
                'laminas-db-table-gateway-table-gateway-question_view_not_bot_log' => function ($sm) {
                    return new LaminasDb\TableGateway\TableGateway(
                        'question_view_not_bot_log',
                        $sm->get('question')
                    );
                },
                QuestionCommand\Answers\Import::class => function ($sm) {
                    return new QuestionCommand\Answers\Import(
                        $sm->get('config')['monthly-basis']['open-ai'],
                        $sm->get(QuestionDb\Sql::class),
                    );
                },
                QuestionDb\Sql::class => function ($sm) {
                    return new QuestionDb\Sql(
                        $sm->get('question')
                    );
                },
                QuestionEntity\Config::class => function ($sm) {
                    return new QuestionEntity\Config(
                        $sm->get('Config')['monthly-basis']['question'] ?? []
                    );
                },
                QuestionFactory\Answer::class => function ($sm) {
                    return new QuestionFactory\Answer(
                        $sm->get(QuestionTable\Answer::class),
                        $sm->get(UserFactory\User::class),
                        $sm->get(UserService\DisplayNameOrUsername::class)
                    );
                },
                QuestionFactory\Answer\FromAnswerId::class => function ($sm) {
                    return new QuestionFactory\Answer\FromAnswerId(
                        $sm->get(QuestionFactory\Answer::class),
                        $sm->get(QuestionTable\Answer::class),
                    );
                },
                QuestionFactory\Question::class => function ($sm) {
                    return new QuestionFactory\Question(
                        $sm->get(QuestionTable\Question::class),
                        $sm->get(UserFactory\User::class),
                        $sm->get(UserService\DisplayNameOrUsername::class)
                    );
                },
                QuestionFactory\Question\FromQuestionId::class => function ($sm) {
                    return new QuestionFactory\Question\FromQuestionId(
                        $sm->get(QuestionFactory\Question::class),
                        $sm->get(QuestionTable\Question::class),
                    );
                },
                QuestionFactory\Question\FromSlug::class => function ($sm) {
                    return new QuestionFactory\Question\FromSlug(
                        $sm->get(QuestionFactory\Question::class),
                        $sm->get(QuestionTable\Question\Slug::class),
                    );
                },
                QuestionService\Answer\Answers::class => function ($sm) {
                    return new QuestionService\Answer\Answers(
                        $sm->get(QuestionFactory\Answer::class),
                        $sm->get(QuestionTable\Answer::class),
                        $sm->get(VoteService\Votes\Multiple::class),
                    );
                },
                QuestionService\Answer\Answers\Newest::class => function ($sm) {
                    return new QuestionService\Answer\Answers\Newest(
                        $sm->get(QuestionFactory\Answer::class),
                        $sm->get(QuestionTable\Answer\DeletedDatetimeCreatedDatetime::class)
                    );
                },
                QuestionService\Answer\Answers\Newest\CreatedName::class => function ($sm) {
                    return new QuestionService\Answer\Answers\Newest\CreatedName(
                        $sm->get(QuestionFactory\Answer::class),
                        $sm->get(QuestionTable\Answer\CreatedName::class)
                    );
                },
                QuestionService\Answer\Answers\User\MostPopular::class => function ($sm) {
                    return new QuestionService\Answer\Answers\User\MostPopular(
                        $sm->get(QuestionFactory\Answer::class),
                        $sm->get(QuestionTable\Answer::class)
                    );
                },
                QuestionService\Answer\Count::class => function ($sm) {
                    return new QuestionService\Answer\Count(
                        $sm->get(QuestionTable\Answer::class)
                    );
                },
                QuestionService\Answer\Delete::class => function ($sm) {
                    return new QuestionService\Answer\Delete(
                        $sm->get(QuestionTable\Answer\AnswerId::class),
                        $sm->get(QuestionTable\AnswerReport::class),
                    );
                },
                QuestionService\Answer\Delete\Queue\Add::class => function ($sm) {
                    return new QuestionService\Answer\Delete\Queue\Add(
                        $sm->get(QuestionTable\AnswerDeleteQueue::class)
                    );
                },
                QuestionService\Answer\Delete\Queue\Approve::class => function ($sm) {
                    return new QuestionService\Answer\Delete\Queue\Approve(
                        $sm->get(QuestionTable\Answer\AnswerId::class),
                        $sm->get(QuestionTable\AnswerDeleteQueue::class)
                    );
                },
                QuestionService\Answer\Delete\Queue\Decline::class => function ($sm) {
                    return new QuestionService\Answer\Delete\Queue\Decline(
                        $sm->get(QuestionTable\AnswerDeleteQueue::class)
                    );
                },
                QuestionService\Answer\Delete\Queue\Pending::class => function ($sm) {
                    return new QuestionService\Answer\Delete\Queue\Pending(
                        $sm->get(QuestionTable\AnswerDeleteQueue::class)
                    );
                },
                QuestionService\Answer\Deleted::class => function ($sm) {
                    return new QuestionService\Answer\Deleted();
                },
                QuestionService\Answer\Duplicate::class => function ($sm) {
                    return new QuestionService\Answer\Duplicate(
                        $sm->get(QuestionTable\Answer\QuestionIdDeletedCreatedDatetime::class)
                    );
                },
                QuestionService\Answer\Edit::class => function ($sm) {
                    return new QuestionService\Answer\Edit(
                        $sm->get('question')->getDriver()->getConnection(),
                        $sm->get(QuestionTable\Answer::class),
                        $sm->get(QuestionTable\AnswerHistory::class)
                    );
                },
                QuestionService\Answer\Edit\Queue::class => function ($sm) {
                    return new QuestionService\Answer\Edit\Queue(
                        $sm->get(QuestionTable\AnswerEditQueue::class)
                    );
                },
                QuestionService\Answer\Edit\Queue\Approve::class => function ($sm) {
                    return new QuestionService\Answer\Edit\Queue\Approve(
                        $sm->get(QuestionFactory\Answer::class),
                        $sm->get(QuestionService\Answer\Edit::class),
                        $sm->get(QuestionTable\AnswerEditQueue::class)
                    );
                },
                QuestionService\Answer\Edit\Queue\Decline::class => function ($sm) {
                    return new QuestionService\Answer\Edit\Queue\Decline(
                        $sm->get(QuestionTable\AnswerEditQueue::class)
                    );
                },
                QuestionService\Answer\Edit\Queue\Pending::class => function ($sm) {
                    return new QuestionService\Answer\Edit\Queue\Pending(
                        $sm->get(QuestionFactory\Answer::class),
                        $sm->get(QuestionTable\AnswerEditQueue::class)
                    );
                },
                QuestionService\Answer\Insert\Deleted::class => function ($sm) {
                    return new QuestionService\Answer\Insert\Deleted(
                        $sm->get(QuestionFactory\Answer::class),
                        $sm->get(QuestionTable\Answer::class)
                    );
                },
                QuestionService\Answer\Insert\User::class => function ($sm) {
                    return new QuestionService\Answer\Insert\User(
                        $sm->get(QuestionFactory\Answer::class),
                        $sm->get(QuestionTable\Answer::class)
                    );
                },
                QuestionService\Answer\Insert\Visitor::class => function ($sm) {
                    return new QuestionService\Answer\Insert\Visitor(
                        $sm->get(QuestionFactory\Answer::class),
                        $sm->get(QuestionTable\Answer::class)
                    );
                },
                QuestionService\AnswerOrQuestionDeleted::class => function ($sm) {
                    return new QuestionService\AnswerOrQuestionDeleted(
                        $sm->get(QuestionService\Answer\Deleted::class),
                        $sm->get(QuestionService\Question\Deleted::class),
                        $sm->get(QuestionService\QuestionFromAnswer::class),
                    );
                },
                QuestionService\Answer\Queue\Insert::class => function ($sm) {
                    return new QuestionService\Answer\Queue\Insert(
                        $sm->get(QuestionTable\AnswerQueue::class)
                    );
                },
                QuestionService\Answer\RootRelativeUrl::class => function ($sm) {
                    return new QuestionService\Answer\RootRelativeUrl(
                        $sm->get(QuestionEntity\Config::class),
                        $sm->get(QuestionService\Answer\Slug::class),
                    );
                },
                QuestionService\Answer\Slug::class => function ($sm) {
                    return new QuestionService\Answer\Slug(
                        $sm->get(StringService\StripTagsAndShorten::class),
                        $sm->get(StringService\UrlFriendly::class),
                    );
                },
                QuestionService\Answer\Submit::class => function ($sm) {
                    return new QuestionService\Answer\Submit(
                        $sm->get(FlashService\Flash::class),
                        $sm->get(QuestionFactory\Answer::class),
                        $sm->get(QuestionTable\Answer::class)
                    );
                },
                QuestionService\Answer\Title::class => function ($sm) {
                    return new QuestionService\Answer\Title(
                        $sm->get(StringService\StripTagsAndShorten::class)
                    );
                },
                QuestionService\Answer\Undelete::class => function ($sm) {
                    return new QuestionService\Answer\Undelete(
                        $sm->get(QuestionTable\Answer\AnswerId::class)
                    );
                },
                QuestionService\Answer\Url::class => function ($sm) {
                    return new QuestionService\Answer\Url(
                        $sm->get(QuestionService\Answer\RootRelativeUrl::class),
                    );
                },
                QuestionService\Answers\CreatedName\Count::class => function ($sm) {
                    return new QuestionService\Answers\CreatedName\Count(
                        $sm->get(QuestionTable\Answer\CreatedName::class),
                    );
                },
                QuestionService\Answers\Related::class => function ($sm) {
                    return new QuestionService\Answers\Related(
                        $sm->get(QuestionFactory\Answer\FromAnswerId::class),
                        $sm->get(QuestionTable\AnswerSearchMessage::class),
                    );
                },
                QuestionService\Answers\User::class => function ($sm) {
                    return new QuestionService\Answers\User(
                        $sm->get(QuestionFactory\Answer\FromAnswerId::class),
                        $sm->get(QuestionTable\Answer::class),
                    );
                },
                QuestionService\AnswerSearchMessage\Rotate::class => function ($sm) {
                    return new QuestionService\AnswerSearchMessage\Rotate(
                        $sm->get(QuestionTable\AnswerSearchMessage::class),
                    );
                },
                QuestionService\Post\CanBeUndeleted::class => function ($sm) {
                    return new QuestionService\Post\CanBeUndeleted(
                        $sm->get(GroupService\LoggedInUserInGroupName::class),
                        $sm->get(StringService\Contains\CaseInsensitive::class),
                        $sm->get(UserService\LoggedIn::class),
                    );
                },
                QuestionService\Post\Duplicate::class => function ($sm) {
                    return new QuestionService\Post\Duplicate(
                        $sm->get(QuestionTable\Answer\CreatedDatetime::class),
                    );
                },
                QuestionService\Post\Posts\Newest\User::class => function ($sm) {
                    return new QuestionService\Post\Posts\Newest\User(
                        $sm->get(QuestionFactory\Answer::class),
                        $sm->get(QuestionFactory\Question::class),
                        $sm->get(QuestionTable\Post::class),
                    );
                },
                QuestionService\Post\Posts\NumberOfPostsRecentlyDeletedForFoulLanguage::class => function ($sm) {
                    return new QuestionService\Post\Posts\NumberOfPostsRecentlyDeletedForFoulLanguage(
                        $sm->get(QuestionTable\Answer\CreatedIp::class),
                        $sm->get(QuestionTable\Question\CreatedIp::class),
                    );
                },
                QuestionService\QuestionSearchMessage\Rotate::class => function ($sm) {
                    return new QuestionService\QuestionSearchMessage\Rotate(
                        $sm->get(QuestionTable\QuestionSearchMessage::class),
                    );
                },
                QuestionService\QuestionSearchSimilar\Rotate::class => function ($sm) {
                    return new QuestionService\QuestionSearchSimilar\Rotate(
                        $sm->get(QuestionTable\QuestionSearchSimilar::class),
                    );
                },
                QuestionService\Question\Categories::class => function ($sm) {
                    return new QuestionService\Question\Categories(
                        $sm->get(CategoryFactory\FromCategoryId::class),
                        $sm->get(QuestionTable\CategoryQuestion::class),
                    );
                },
                QuestionService\Question\Deleted::class => function ($sm) {
                    return new QuestionService\Question\Deleted();
                },
                QuestionService\Question\Edit::class => function ($sm) {
                    return new QuestionService\Question\Edit(
                        $sm->get('question')->getDriver()->getConnection(),
                        $sm->get(QuestionTable\Question::class),
                        $sm->get(QuestionTable\QuestionHistory::class)
                    );
                },
                QuestionService\Question\Edit\Queue::class => function ($sm) {
                    return new QuestionService\Question\Edit\Queue(
                        $sm->get(QuestionTable\QuestionEditQueue::class)
                    );
                },
                QuestionService\Question\Edit\Queue\Approve::class => function ($sm) {
                    return new QuestionService\Question\Edit\Queue\Approve(
                        $sm->get(QuestionFactory\Question::class),
                        $sm->get(QuestionService\Question\Edit::class),
                        $sm->get(QuestionTable\QuestionEditQueue::class)
                    );
                },
                QuestionService\Question\Edit\Queue\Decline::class => function ($sm) {
                    return new QuestionService\Question\Edit\Queue\Decline(
                        $sm->get(QuestionTable\QuestionEditQueue::class)
                    );
                },
                QuestionService\Question\Edit\Queue\Pending::class => function ($sm) {
                    return new QuestionService\Question\Edit\Queue\Pending(
                        $sm->get(QuestionFactory\Question::class),
                        $sm->get(QuestionTable\QuestionEditQueue::class)
                    );
                },
                QuestionService\Question\HeadlineAndMessage::class => function ($sm) {
                    return new QuestionService\Question\HeadlineAndMessage();
                },
                QuestionService\Question\HeadlineOrSubject::class => function ($sm) {
                    return new QuestionService\Question\HeadlineOrSubject();
                },
                QuestionService\Question\Insert\Deleted::class => function ($sm) {
                    return new QuestionService\Question\Insert\Deleted(
                        $sm->get(QuestionFactory\Question::class),
                        $sm->get(QuestionTable\Question::class)
                    );
                },
                QuestionService\Question\Insert\User::class => function ($sm) {
                    return new QuestionService\Question\Insert\User(
                        $sm->get(QuestionFactory\Question::class),
                        $sm->get(QuestionTable\Question::class)
                    );
                },
                QuestionService\Question\Insert\Visitor::class => function ($sm) {
                    return new QuestionService\Question\Insert\Visitor(
                        $sm->get(QuestionFactory\Question::class),
                        $sm->get(QuestionService\Question\Slug\FromMessage::class),
                        $sm->get(QuestionTable\Question::class),
                    );
                },
                QuestionService\Question\QuestionViewNotBotLog\ConditionallyInsert::class => function ($sm) {
                    return new QuestionService\Question\QuestionViewNotBotLog\ConditionallyInsert(
                        $sm->get(MemcachedService\Memcached::class),
                        $sm->get('laminas-db-table-gateway-table-gateway-question_view_not_bot_log'),
                        $sm->get(StringService\StartsWith::class),
                        $sm->get(SuperglobalService\Server\HttpUserAgent\Bot::class)
                    );
                },
                QuestionService\QuestionFromAnswer::class => function ($sm) {
                    return new QuestionService\QuestionFromAnswer(
                        $sm->get(QuestionFactory\Question\FromQuestionId::class)
                    );
                },
                QuestionService\NumberOfPostsDeletedByUserId0InLast24Hours::class => function ($sm) {
                    return new QuestionService\NumberOfPostsDeletedByUserId0InLast24Hours(
                        $sm->get(QuestionTable\Answer\CreatedIpDeletedDatetimeDeletedUserId::class),
                        $sm->get(QuestionTable\Question\CreatedIpDeletedDatetimeDeletedUserId::class)
                    );
                },
                QuestionService\Question\Delete::class => function ($sm) {
                    return new QuestionService\Question\Delete(
                        $sm->get(QuestionTable\AnswerReport::class),
                        $sm->get(QuestionTable\Question\QuestionId::class),
                        $sm->get(QuestionTable\QuestionReport::class),
                    );
                },
                QuestionService\Question\Delete\Queue\Add::class => function ($sm) {
                    return new QuestionService\Question\Delete\Queue\Add(
                        $sm->get(QuestionTable\QuestionDeleteQueue::class)
                    );
                },
                QuestionService\Question\Delete\Queue\Approve::class => function ($sm) {
                    return new QuestionService\Question\Delete\Queue\Approve(
                        $sm->get(QuestionTable\Question\QuestionId::class),
                        $sm->get(QuestionTable\QuestionDeleteQueue::class)
                    );
                },
                QuestionService\Question\Delete\Queue\Decline::class => function ($sm) {
                    return new QuestionService\Question\Delete\Queue\Decline(
                        $sm->get(QuestionTable\QuestionDeleteQueue::class)
                    );
                },
                QuestionService\Question\Delete\Queue\Pending::class => function ($sm) {
                    return new QuestionService\Question\Delete\Queue\Pending(
                        $sm->get(QuestionTable\QuestionDeleteQueue::class)
                    );
                },
                QuestionService\Question\Duplicate::class => function ($sm) {
                    return new QuestionService\Question\Duplicate(
                        $sm->get(QuestionFactory\Question::class),
                        $sm->get(QuestionTable\Question\MessageDeletedDatetimeCreatedDatetime::class)
                    );
                },
                QuestionService\Question\IncrementAnswerCountCached::class => function ($sm) {
                    return new QuestionService\Question\IncrementAnswerCountCached(
                        $sm->get(QuestionTable\Question\QuestionId::class)
                    );
                },
                QuestionService\Question\IncrementViews::class => function ($sm) {
                    return new QuestionService\Question\IncrementViews(
                        $sm->get(QuestionTable\Question::class)
                    );
                },
                QuestionService\Question\Questions::class => function ($sm) {
                    return new QuestionService\Question\Questions(
                        $sm->get(QuestionFactory\Question::class),
                        $sm->get(QuestionTable\Question::class)
                    );
                },
                QuestionService\Question\Questions\MostPopular\CreatedName::class => function ($sm) {
                    return new QuestionService\Question\Questions\MostPopular\CreatedName(
                        $sm->get('laminas-db-sql-sql'),
                        $sm->get(QuestionFactory\Question::class),
                        $sm->get(QuestionTable\Question::class)
                    );
                },
                QuestionService\Question\Questions\MostPopular\Day::class => function ($sm) {
                    return new QuestionService\Question\Questions\MostPopular\Day(
                        $sm->get('laminas-db-sql-sql'),
                        $sm->get(QuestionFactory\Question::class),
                        $sm->get(QuestionTable\Question::class)
                    );
                },
                QuestionService\Question\Questions\MostPopular\Hour::class => function ($sm) {
                    return new QuestionService\Question\Questions\MostPopular\Hour(
                        $sm->get(QuestionFactory\Question\FromQuestionId::class),
                        $sm->get(QuestionTable\Question::class)
                    );
                },
                QuestionService\Question\Questions\MostPopular\Month::class => function ($sm) {
                    return new QuestionService\Question\Questions\MostPopular\Month(
                        $sm->get(QuestionFactory\Question\FromQuestionId::class),
                        $sm->get(QuestionTable\Question::class)
                    );
                },
                QuestionService\Question\Questions\MostPopular\Week::class => function ($sm) {
                    return new QuestionService\Question\Questions\MostPopular\Week(
                        $sm->get('laminas-db-sql-sql'),
                        $sm->get(QuestionFactory\Question::class),
                        $sm->get(QuestionTable\Question::class)
                    );
                },
                QuestionService\Question\Questions\MostPopular\Year::class => function ($sm) {
                    return new QuestionService\Question\Questions\MostPopular\Year(
                        $sm->get(QuestionFactory\Question\FromQuestionId::class),
                        $sm->get(QuestionTable\Question::class)
                    );
                },
                QuestionService\Question\Questions\Newest::class => function ($sm) {
                    return new QuestionService\Question\Questions\Newest(
                        $sm->get(QuestionFactory\Question::class),
                        $sm->get(QuestionTable\Question\DeletedDatetimeCreatedDatetime::class)
                    );
                },
                QuestionService\Question\Questions\Newest\CreatedName::class => function ($sm) {
                    return new QuestionService\Question\Questions\Newest\CreatedName(
                        $sm->get(QuestionFactory\Question::class),
                        $sm->get(QuestionTable\Question\CreatedName::class)
                    );
                },
                QuestionService\Question\Questions\Newest\WithAnswers::class => function ($sm) {
                    return new QuestionService\Question\Questions\Newest\WithAnswers(
                        $sm->get(QuestionFactory\Answer::class),
                        $sm->get(QuestionService\Question\Questions::class),
                        $sm->get(QuestionTable\Answer::class)
                    );
                },
                QuestionService\Question\Questions\Related::class => function ($sm) {
                    return new QuestionService\Question\Questions\Related(
                        $sm->get(QuestionEntity\Config::class),
                        $sm->get(QuestionFactory\Question::class),
                        $sm->get(QuestionService\Question\HeadlineAndMessage::class),
                        $sm->get(QuestionTable\QuestionSearchMessage::class),
                    );
                },
                QuestionService\Question\Questions\Search\Results::class => function ($sm) {
                    return new QuestionService\Question\Questions\Search\Results(
                        $sm->get(QuestionEntity\Config::class),
                        $sm->get(QuestionFactory\Question::class),
                        $sm->get(QuestionTable\QuestionSearchMessage::class),
                        $sm->get(StringService\KeepFirstWords::class)
                    );
                },
                QuestionService\Question\Questions\Search\Results\Count::class => function ($sm) {
                    return new QuestionService\Question\Questions\Search\Results\Count(
                        $sm->get(MemcachedService\Memcached::class),
                        $sm->get(QuestionEntity\Config::class),
                        $sm->get(QuestionTable\QuestionSearchMessage::class),
                        $sm->get(StringService\KeepFirstWords::class)
                    );
                },
                QuestionService\Question\Questions\Similar::class => function ($sm) {
                    return new QuestionService\Question\Questions\Similar(
                        $sm->get(QuestionEntity\Config::class),
                        $sm->get(QuestionFactory\Question::class),
                        $sm->get(QuestionService\Question\HeadlineAndMessage::class),
                        $sm->get(QuestionTable\QuestionSearchSimilar::class),
                    );
                },
                QuestionService\Questions\Category::class => function ($sm) {
                    return new QuestionService\Questions\Category(
                        $sm->get(QuestionFactory\Question\FromQuestionId::class),
                        $sm->get(QuestionTable\CategoryQuestion::class),
                    );
                },
                QuestionService\Questions\Category\Count::class => function ($sm) {
                    return new QuestionService\Questions\Category\Count(
                        $sm->get(QuestionTable\CategoryQuestion::class),
                    );
                },
                QuestionService\Questions\CreatedName\Count::class => function ($sm) {
                    return new QuestionService\Questions\CreatedName\Count(
                        $sm->get(QuestionTable\Question\CreatedName::class),
                    );
                },
                QuestionService\Questions\Subject::class => function ($sm) {
                    return new QuestionService\Questions\Subject(
                        $sm->get(QuestionDb\Sql::class),
                        $sm->get(QuestionFactory\Question::class)
                    );
                },
                QuestionService\Questions\Subject\NumberOfPages::class => function ($sm) {
                    return new QuestionService\Questions\Subject\NumberOfPages(
                        $sm->get(QuestionTable\Question::class)
                    );
                },
                QuestionService\Questions\Unanswered::class => function ($sm) {
                    return new QuestionService\Questions\Unanswered(
                        $sm->get(QuestionFactory\Question::class),
                        $sm->get(QuestionTable\Question::class),
                    );
                },
                QuestionService\Question\Questions\Year::class => function ($sm) {
                    return new QuestionService\Question\Questions\Year(
                        $sm->get('laminas-db-sql-sql'),
                        $sm->get(QuestionFactory\Question::class),
                        $sm->get(QuestionTable\Question::class)
                    );
                },
                QuestionService\Question\Questions\YearMonth::class => function ($sm) {
                    return new QuestionService\Question\Questions\YearMonth(
                        $sm->get(QuestionFactory\Question::class),
                        $sm->get(QuestionTable\Question::class),
                    );
                },
                QuestionService\Question\Questions\YearMonthDay::class => function ($sm) {
                    return new QuestionService\Question\Questions\YearMonthDay(
                        $sm->get('laminas-db-sql-sql'),
                        $sm->get(QuestionFactory\Question::class),
                        $sm->get(QuestionTable\Question::class)
                    );
                },
                QuestionService\Question\Random::class => function ($sm) {
                    return new QuestionService\Question\Random(
                        $sm->get(QuestionFactory\Question\FromQuestionId::class),
                        $sm->get(QuestionTable\QuestionSearchMessage::class),
                    );
                },
                QuestionService\Question\RootRelativeUrl::class => function ($sm) {
                    return new QuestionService\Question\RootRelativeUrl(
                        $sm->get(QuestionEntity\Config::class),
                        $sm->get(QuestionService\Question\Slug::class),
                    );
                },
                QuestionService\Question\Slug::class => function ($sm) {
                    return new QuestionService\Question\Slug(
                        $sm->get(QuestionService\Question\Title::class),
                        $sm->get(StringService\UrlFriendly::class)
                    );
                },
                QuestionService\Question\Slug\FromMessage::class => function ($sm) {
                    return new QuestionService\Question\Slug\FromMessage(
                        $sm->get(StringService\StripTagsAndShorten::class),
                        $sm->get(StringService\UrlFriendly::class),
                    );
                },
                QuestionService\Question\Submit::class => function ($sm) {
                    return new QuestionService\Question\Submit(
                        $sm->get(FlashService\Flash::class),
                        $sm->get(QuestionFactory\Question::class),
                        $sm->get(QuestionTable\Question::class)
                    );
                },
                QuestionService\Question\Title::class => function ($sm) {
                    return new QuestionService\Question\Title(
                        $sm->get(StringService\StripTagsAndShorten::class)
                    );
                },
                QuestionService\Question\Undelete::class => function ($sm) {
                    return new QuestionService\Question\Undelete(
                        $sm->get(QuestionTable\Question\QuestionId::class)
                    );
                },
                QuestionService\Question\Url::class => function ($sm) {
                    return new QuestionService\Question\Url(
                        $sm->get(QuestionService\Question\RootRelativeUrl::class)
                    );
                },
                QuestionService\Questions\User::class => function ($sm) {
                    return new QuestionService\Questions\User(
                        $sm->get(QuestionFactory\Question::class),
                        $sm->get(QuestionTable\Question::class),
                    );
                },
                QuestionService\Question\Views\Increment\Conditionally::class => function ($sm) {
                    return new QuestionService\Question\Views\Increment\Conditionally(
                        $sm->get(MemcachedService\Memcached::class),
                        $sm->get(QuestionService\Question\IncrementViews::class),
                    );
                },
                QuestionService\Users::class => function ($sm) {
                    return new QuestionService\Users(
                        $sm->get(QuestionTable\Question\UserId::class),
                        $sm->get(UserFactory\User::class),
                    );
                },
                QuestionTable\Answer::class => function ($sm) {
                    return new QuestionTable\Answer(
                        $sm->get(QuestionDb\Sql::class),
                    );
                },
                QuestionTable\Answer\QuestionIdDeletedCreatedDatetime::class => function ($sm) {
                    return new QuestionTable\Answer\QuestionIdDeletedCreatedDatetime(
                        $sm->get('question')
                    );
                },
                QuestionTable\Answer\AnswerId::class => function ($sm) {
                    return new QuestionTable\Answer\AnswerId(
                        $sm->get('question'),
                        $sm->get(QuestionTable\Answer::class)
                    );
                },
                QuestionTable\Answer\CreatedDatetime::class => function ($sm) {
                    return new QuestionTable\Answer\CreatedDatetime(
                        $sm->get(QuestionDb\Sql::class)
                    );
                },
                QuestionTable\Answer\CreatedName::class => function ($sm) {
                    return new QuestionTable\Answer\CreatedName(
                        $sm->get('question'),
                        $sm->get(QuestionTable\Answer::class)
                    );
                },
                QuestionTable\Answer\CreatedIp::class => function ($sm) {
                    return new QuestionTable\Answer\CreatedIp(
                        $sm->get('question')
                    );
                },
                QuestionTable\Answer\CreatedIpCreatedDatetime::class => function ($sm) {
                    return new QuestionTable\Answer\CreatedIpCreatedDatetime(
                        $sm->get('question')
                    );
                },
                QuestionTable\Answer\CreatedIpDeletedDatetimeDeletedUserId::class => function ($sm) {
                    return new QuestionTable\Answer\CreatedIpDeletedDatetimeDeletedUserId(
                        $sm->get('question')
                    );
                },
                QuestionTable\Answer\CreatedNameDeletedCreatedDatetime::class => function ($sm) {
                    return new QuestionTable\Answer\CreatedNameDeletedCreatedDatetime(
                        $sm->get('question'),
                        $sm->get(QuestionTable\Answer::class)
                    );
                },
                QuestionTable\Answer\DeletedDatetime::class => function ($sm) {
                    return new QuestionTable\Answer\DeletedDatetime(
                        $sm->get('question'),
                        $sm->get(QuestionTable\Answer::class)
                    );
                },
                QuestionTable\Answer\DeletedDatetimeCreatedDatetime::class => function ($sm) {
                    return new QuestionTable\Answer\DeletedDatetimeCreatedDatetime(
                        $sm->get('question'),
                        $sm->get(QuestionTable\Answer::class)
                    );
                },
                QuestionTable\Answer\DeletedUserId::class => function ($sm) {
                    return new QuestionTable\Answer\DeletedUserId(
                        $sm->get('question'),
                        $sm->get(QuestionTable\Answer::class)
                    );
                },
                QuestionTable\Answer\Message::class => function ($sm) {
                    return new QuestionTable\Answer\Message(
                        $sm->get('question')
                    );
                },
                QuestionTable\Answer\UserId::class => function ($sm) {
                    return new QuestionTable\Answer\UserId(
                        $sm->get('question'),
                    );
                },
                QuestionTable\AnswerDeleteQueue::class => function ($sm) {
                    return new QuestionTable\AnswerDeleteQueue(
                        $sm->get('question')
                    );
                },
                QuestionTable\AnswerEditQueue::class => function ($sm) {
                    return new QuestionTable\AnswerEditQueue(
                        $sm->get('question')
                    );
                },
                QuestionTable\AnswerHistory::class => function ($sm) {
                    return new QuestionTable\AnswerHistory(
                        $sm->get('question')
                    );
                },
                QuestionTable\AnswerQueue::class => function ($sm) {
                    return new QuestionTable\AnswerQueue(
                        $sm->get(QuestionDb\Sql::class)
                    );
                },
                QuestionTable\AnswerReport::class => function ($sm) {
                    return new QuestionTable\AnswerReport(
                        $sm->get(QuestionDb\Sql::class)
                    );
                },
                QuestionTable\AnswerSearchMessage::class => function ($sm) {
                    return new QuestionTable\AnswerSearchMessage(
                        $sm->get(QuestionDb\Sql::class),
                    );
                },
                QuestionTable\CategoryQuestion::class => function ($sm) {
                    return new QuestionTable\CategoryQuestion(
                        $sm->get(QuestionDb\Sql::class)
                    );
                },
                QuestionTable\Post::class => function ($sm) {
                    return new QuestionTable\Post(
                        $sm->get(QuestionDb\Sql::class)
                    );
                },
                QuestionTable\Question::class => function ($sm) {
                    return new QuestionTable\Question(
                        $sm->get(QuestionDb\Sql::class),
                    );
                },
                QuestionTable\Question\CreatedDatetimeDeletedDatetime::class => function ($sm) {
                    return new QuestionTable\Question\CreatedDatetimeDeletedDatetime(
                        $sm->get('question'),
                        $sm->get(QuestionTable\Question::class)
                    );
                },
                QuestionTable\Question\CreatedNameDeletedCreatedDatetime::class => function ($sm) {
                    return new QuestionTable\Question\CreatedNameDeletedCreatedDatetime(
                        $sm->get('question'),
                        $sm->get(QuestionTable\Question::class)
                    );
                },
                QuestionTable\Question\DeletedDatetimeCreatedDatetime::class => function ($sm) {
                    return new QuestionTable\Question\DeletedDatetimeCreatedDatetime(
                        $sm->get('question'),
                        $sm->get(QuestionTable\Question::class)
                    );
                },
                QuestionTable\Question\CreatedIpDeletedDatetimeDeletedUserId::class => function ($sm) {
                    return new QuestionTable\Question\CreatedIpDeletedDatetimeDeletedUserId(
                        $sm->get('question')
                    );
                },
                QuestionTable\Question\CreatedIp::class => function ($sm) {
                    return new QuestionTable\Question\CreatedIp(
                        $sm->get('question')
                    );
                },
                QuestionTable\Question\CreatedIpCreatedDatetime::class => function ($sm) {
                    return new QuestionTable\Question\CreatedIpCreatedDatetime(
                        $sm->get('question')
                    );
                },
                QuestionTable\Question\CreatedName::class => function ($sm) {
                    return new QuestionTable\Question\CreatedName(
                        $sm->get('question'),
                        $sm->get(QuestionTable\Question::class)
                    );
                },
                QuestionTable\Question\Deleted::class => function ($sm) {
                    return new QuestionTable\Question\Deleted(
                        $sm->get('question'),
                        $sm->get(MemcachedService\Memcached::class),
                        $sm->get(QuestionTable\Question::class)
                    );
                },
                QuestionTable\Question\DeletedDatetime::class => function ($sm) {
                    return new QuestionTable\Question\DeletedDatetime(
                        $sm->get('question'),
                        $sm->get(MemcachedService\Memcached::class),
                        $sm->get(QuestionTable\Question::class)
                    );
                },
                QuestionTable\Question\DeletedUserId::class => function ($sm) {
                    return new QuestionTable\Question\DeletedUserId(
                        $sm->get('question'),
                        $sm->get(QuestionTable\Question::class)
                    );
                },
                QuestionTable\Question\QuestionId::class => function ($sm) {
                    return new QuestionTable\Question\QuestionId(
                        $sm->get('question'),
                        $sm->get(QuestionTable\Question::class)
                    );
                },
                QuestionTable\Question\Message::class => function ($sm) {
                    return new QuestionTable\Question\Message(
                        $sm->get('question'),
                        $sm->get(QuestionTable\Question::class)
                    );
                },
                QuestionTable\Question\MessageDeletedDatetimeCreatedDatetime::class => function ($sm) {
                    return new QuestionTable\Question\MessageDeletedDatetimeCreatedDatetime(
                        $sm->get('question'),
                        $sm->get(QuestionTable\Question::class)
                    );
                },
                QuestionTable\Question\Slug::class => function ($sm) {
                    return new QuestionTable\Question\Slug(
                        $sm->get('question'),
                        $sm->get(QuestionTable\Question::class)
                    );
                },
                QuestionTable\Question\Subject::class => function ($sm) {
                    return new QuestionTable\Question\Subject(
                        $sm->get('question'),
                        $sm->get(QuestionTable\Question::class)
                    );
                },
                QuestionTable\Question\SubjectDeletedDatetimeViewsBrowser::class => function ($sm) {
                    return new QuestionTable\Question\SubjectDeletedDatetimeViewsBrowser(
                        $sm->get('question'),
                        $sm->get(QuestionTable\Question::class)
                    );
                },
                QuestionTable\Question\UserId::class => function ($sm) {
                    return new QuestionTable\Question\UserId(
                        $sm->get('question'),
                    );
                },
                QuestionTable\QuestionDeleteQueue::class => function ($sm) {
                    return new QuestionTable\QuestionDeleteQueue(
                        $sm->get('question')
                    );
                },
                QuestionTable\QuestionEditQueue::class => function ($sm) {
                    return new QuestionTable\QuestionEditQueue(
                        $sm->get('question')
                    );
                },
                QuestionTable\QuestionHistory::class => function ($sm) {
                    return new QuestionTable\QuestionHistory(
                        $sm->get('question')
                    );
                },
                QuestionTable\QuestionReport::class => function ($sm) {
                    return new QuestionTable\QuestionReport(
                        $sm->get(QuestionDb\Sql::class)
                    );
                },
                QuestionTable\QuestionSearchMessage::class => function ($sm) {
                    return new QuestionTable\QuestionSearchMessage(
                        $sm->get(MemcachedService\Memcached::class),
                        $sm->get(QuestionDb\Sql::class),
                        $sm->get('question')
                    );
                },
                QuestionTable\QuestionSearchSimilar::class => function ($sm) {
                    return new QuestionTable\QuestionSearchSimilar(
                        $sm->get(QuestionDb\Sql::class),
                        $sm->get('question')
                    );
                },
            ],
        ];
    }
}
