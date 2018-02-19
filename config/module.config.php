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
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\UserController::class => InvokableFactory::class,
            Controller\RankingController::class => InvokableFactory::class,
            Controller\GroupController::class => InvokableFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Table\RankingTable::class => \MIABase\Factory\TableFactory::class,
            Table\Group\RelationUserTable::class => \MIABase\Factory\TableFactory::class,
            Table\GroupTable::class => \MIABase\Factory\TableFactory::class,
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
