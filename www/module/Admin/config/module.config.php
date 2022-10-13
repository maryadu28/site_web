<?php

namespace Admin;

// use Laminas\ServiceManager\Factory\InvokableFactory;
use Laminas\Router\Http\Segment;

return [
	// 'controllers' => [
	//     'factories' => [
	//         Controller\AlbumController::class => InvokableFactory::class,
	//     ],
	// ],
	'router' => [
		'routes' => [
			'album' => [
				'type'    => Segment::class,
				'options' => [
					'route' => '/album[/:action[/:id]]',
					'constraints' => [
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					],
					'defaults' => [
						'controller' => \Admin\Controller\AlbumController::class,
						'action'     => 'index',
					],
				],
			],

			'book' => [
				'type'    => Segment::class,
				'options' => [
					'route' => '/book[/:action[/:id]]',
					'constraints' => [
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					],
					'defaults' => [
						'controller' => \Admin\Controller\BookController::class,
						'action'     => 'index',
					],
				],
			],

			'clothes' => [
				'type'    => Segment::class,
				'options' => [
					'route' => '/clothes[/:action[/:id]]',
					'constraints' => [
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					],
					'defaults' => [
						'controller' => \Admin\Controller\ClothesController::class,
						'action'     => 'index',
					],
				],
			],




			'type' => [
				'type'    => Segment::class,
				'options' => [
					'route' => '/type[/:action[/:id]]',
					'constraints' => [
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					],
					'defaults' => [
						'controller' => \Admin\Controller\TypeController::class,
						'action'     => 'index',
					],
				],
			],


			'color' => [
				'type'    => Segment::class,
				'options' => [
					'route' => '/color[/:action[/:id]]',
					'constraints' => [
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					],
					'defaults' => [
						'controller' => \Admin\Controller\ColorController::class,
						'action'     => 'index',
					],
				],
			],



			'fabric' => [
				'type'    => Segment::class,
				'options' => [
					'route' => '/fabric[/:action[/:id]]',
					'constraints' => [
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					],
					'defaults' => [
						'controller' => \Admin\Controller\FabricController::class,
						'action'     => 'index',
					],
				],
			],

			'log' => [
				'type'    => Segment::class,
				'options' => [
					'route'    => '/login[/:action]',
					'defaults' => [
						'controller' => Controller\LoginController::class,
						'action'     => 'index',
					],
				],
			],



			'size' => [
				'type'    => Segment::class,
				'options' => [
					'route' => '/size[/:action[/:id]]',
					'constraints' => [
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					],
					'defaults' => [
						'controller' => \Admin\Controller\SizeController::class,
						'action'     => 'index',
					],
				],
			],



			'accessory' => [
				'type'    => Segment::class,
				'options' => [
					'route' => '/accessory[/:action[/:id]]',
					'constraints' => [
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					],
					'defaults' => [
						'controller' => \Admin\Controller\AccessoryController::class,
						'action'     => 'index',
					],
				],
			],


			'material' => [
				'type'    => Segment::class,
				'options' => [
					'route' => '/material[/:action[/:id]]',
					'constraints' => [
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					],
					'defaults' => [
						'controller' => \Admin\Controller\MaterialController::class,
						'action'     => 'index',
					],
				],
			],


			'brand' => [
				'type'    => Segment::class,
				'options' => [
					'route' => '/brand[/:action[/:id]]',
					'constraints' => [
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					],
					'defaults' => [
						'controller' => \Admin\Controller\brandController::class,
						'action'     => 'index',
					],
				],
			],

			'category' => [
				'type'    => Segment::class,
				'options' => [
					'route' => '/category[/:action[/:id]]',
					'constraints' => [
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					],
					'defaults' => [
						'controller' => \Admin\Controller\CategoryController::class,
						'action'     => 'index',
					],
				],
			],

			'admin' => [
				'type'    => Segment::class,
				'options' => [
					'route' => '/admin[/:action[/:id]]',
					'constraints' => [
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					],
					'defaults' => [
						'controller' => \Admin\Controller\AdminController::class,
						'action'     => 'index',
					],
				],
			],

			'shoes' => [
				'type'    => Segment::class,
				'options' => [
					'route' => '/shoes[/:action[/:id]]',
					'constraints' => [
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					],
					'defaults' => [
						'controller' => \Admin\Controller\ShoesController::class,
						'action'     => 'index',
					],
				],
			],


			'group' => [
				'type'    => Segment::class,
				'options' => [
					'route' => '/group[/:action[/:id]]',
					'constraints' => [
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					],
					'defaults' => [
						'controller' => \Admin\Controller\GroupController::class,
						'action'     => 'index',
					],
				],
			]





		],

	],






	'view_manager' => [
		'template_path_stack' => [
			'album' => __DIR__ . '/../view',
			'book' => __DIR__ . '/../view',
			'clothes' => __DIR__ . '/../view',
			'type' => __DIR__ . '/../view',
			'color' => __DIR__ . '/../view',
			'fabric' => __DIR__ . '/../view',
			'accessory' => __DIR__ . '/../view',
			'size' => __DIR__ . '/../view',
			'brand' => __DIR__ . '/../view',
			'category' => __DIR__ . '/../view',
			'material' => __DIR__ . '/../view',
			'admin' => __DIR__ . '/../view',
			'group' => __DIR__ . '/../view',

		],
	]

];
