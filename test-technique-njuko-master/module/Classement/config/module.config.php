<?php

namespace Classement;

use Classement\Controller\ClassementControllerFactory;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'classement' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/classement',
                    'defaults' => [
                        'controller' => Controller\ClassementController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => array(
                    'classement' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => '[/:id/:name/:gender/:sorting]',
                            'defaults' => [
                                'controller' => Controller\ClassementController::class,
                                'action'     => 'classement',
                            ],
                        ], 
                    ],
                ),
            ],           
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\ClassementController::class => ClassementControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../../Application/view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../../Application/view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../../Application/view/error/404.phtml',
            'error/index'             => __DIR__ . '/../../Application/view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ]
];
