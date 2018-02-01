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
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\UserController::class => InvokableFactory::class
        ],
    ],
    'service_manager' => [
        'factories' => [
            
        ],
    ],
    'authentication_acl' => [
        'resources' => [
            Controller\UserController::class => [
                'actions' => [
                    'mobileia' => ['allow' => 'guest'],
                ]
            ]
        ],
    ],
    'view_manager' => [
        'template_map' => [
            
        ],
    ]
);
