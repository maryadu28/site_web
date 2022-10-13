<?php

declare(strict_types=1);

namespace Application;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Admin\Controller\AdminController;
use Book\Controller\BookController;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'user_accessory' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/accessoires',
                    'defaults' => [
                        'controller' => Controller\AccessoryController::class,
                        'action'     => 'index',
                    ],
                ],
            ],

            'user_shoes' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/chaussures',
                    'defaults' => [
                        'controller' => Controller\ShoesController::class,
                        'action'     => 'index',
                    ],
                ],
            ],

            'detail_accessory' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/detailaccessoire[/:id]',
                    'defaults' => [
                        'controller' => Controller\AccessorydetailController::class,
                        'action'     => 'index',
                    ],
                ],
            ],

            'detail_clothes' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/detailvetement[/:id]',
                    'defaults' => [
                        'controller' => Controller\ClothesdetailController::class,
                        'action'     => 'index',
                    ],
                ],
            ],

            'ajax_accessory' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/ajax[/:action]',
                    'defaults' => [
                        'controller' => Controller\AjaxController::class,
                        'action'     => 'index',
                    ],
                ],
            ],



            'user_clothes' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/vetements',
                    'defaults' => [
                        'controller' => Controller\ClothesController::class,
                        'action'     => 'index',
                    ],
                ],
            ],

            'panier' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/panier',
                    'defaults' => [
                        'controller' => Controller\PanierController::class,
                        'action'     => 'index',
                    ],
                ],
            ],


            'application' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
