<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2014 Zend Technologies USA Inc. (http://www.zend.com)
 */

namespace MIAProde;

use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return array(
    'router' => [
        'routes' => [
            'api-register-private' => [
                'type'    => Segment::class,
                'options' => [
                        'route'    => '/api/register/mobileia',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action'     => 'mobileia',
                    ],
                ],
            ],
            'api-prode-ranking' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/api/prode/ranking',
                    'defaults' => [
                        'controller' => Controller\RankingController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'api-prode-group' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/api/prode/group[/:action]',
                    'defaults' => [
                        'controller' => Controller\GroupController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'api-prode-match' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/api/prode/match[/:action]',
                    'defaults' => [
                        'controller' => Controller\MatchController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'api-prode-prediction' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/api/prode/prediction[/:action]',
                    'defaults' => [
                        'controller' => Controller\PredictionController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'api-prode-standing' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/api/prode/standing[/:action]',
                    'defaults' => [
                        'controller' => Controller\StandingController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\UserController::class => InvokableFactory::class,
            Controller\RankingController::class => InvokableFactory::class,
            Controller\GroupController::class => InvokableFactory::class,
            Controller\MatchController::class => InvokableFactory::class,
            Controller\PredictionController::class => InvokableFactory::class,
            Controller\StandingController::class => InvokableFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Table\RankingTable::class => \MIABase\Factory\TableFactory::class,
            Table\Group\RelationUserTable::class => \MIABase\Factory\TableFactory::class,
            Table\GroupTable::class => \MIABase\Factory\TableFactory::class,
            Table\TeamTable::class => \MIABase\Factory\TableFactory::class,
            Table\MatchTable::class => \MIABase\Factory\TableFactory::class,
            Table\PredictionTable::class => \MIABase\Factory\TableFactory::class,
            Table\TournamentTable::class => \MIABase\Factory\TableFactory::class,
            Table\StageTable::class => \MIABase\Factory\TableFactory::class,
            Table\StandingTable::class => \MIABase\Factory\TableFactory::class,
        ],
    ],
    'authentication_acl' => [
        'resources' => [
            Controller\UserController::class => [
                'actions' => [
                    'mobileia' => ['allow' => 'guest'],
                ]
            ],
            Controller\RankingController::class => [
                'actions' => [
                    'index' => ['allow' => 'guest'],
                ]
            ],
            Controller\GroupController::class => [
                'actions' => [
                    'index' => ['allow' => 'guest'],
                    'add' => ['allow' => 'guest'],
                    'removeUser' => ['allow' => 'guest'],
                    'leave' => ['allow' => 'guest'],
                    'invitation' => ['allow' => 'guest'],
                ]
            ],
            Controller\MatchController::class => [
                'actions' => [
                    'next' => ['allow' => 'guest'],
                    'list' => ['allow' => 'guest'],
                    'listPrevious' => ['allow' => 'guest'],
                    'listNext' => ['allow' => 'guest'],
                ]
            ],
            Controller\PredictionController::class => [
                'actions' => [
                    'send' => ['allow' => 'guest'],
                ]
            ],
            Controller\StandingController::class => [
                'actions' => [
                    'index' => ['allow' => 'guest'],
                ]
            ],
        ],
    ],
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);
