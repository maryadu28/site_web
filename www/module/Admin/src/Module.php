<?php

namespace Admin;

use Admin\Model\AdminTable;
use Admin\Model\BookTable;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;

class Module implements ConfigProviderInterface
{
    //getconfig 
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    //getservicevonfig 

    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\AlbumTable::class => function ($container) {
                    $tableGateway = $container->get(Model\AlbumTableGateway::class);
                    return new Model\AlbumTable($tableGateway);
                },
                Model\AlbumTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Album());
                    return new TableGateway('album', $dbAdapter, null, $resultSetPrototype);
                },
                Model\BookTable::class => function ($container) {
                    $tableGateway = $container->get(Model\BookTableGateway::class);
                    return new Model\BookTable($tableGateway);
                },
                Model\BookTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Book());
                    return new TableGateway('book', $dbAdapter, null, $resultSetPrototype);
                },

                Model\ClothesTable::class => function ($container) {
                    $tableGateway = $container->get(Model\ClothesTableGateway::class);
                    return new Model\ClothesTable($tableGateway);
                },
                Model\ClothesTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Clothes());
                    return new TableGateway('clothes', $dbAdapter, null, $resultSetPrototype);
                },
				
				Model\TypeTable::class => function ($container) {
                    $tableGateway = $container->get(Model\TypeTableGateway::class);
                    return new Model\TypeTable($tableGateway);
                },
                Model\TypeTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Type());
                    return new TableGateway('type', $dbAdapter, null, $resultSetPrototype);
                },
				
				 Model\ColorTable::class => function ($container) {
                    $tableGateway = $container->get(Model\ColorTableGateway::class);
                    return new Model\ColorTable($tableGateway);
                },
                Model\ColorTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Color());
                    return new TableGateway('color', $dbAdapter, null, $resultSetPrototype);
                },
				
				
				 Model\FabricTable::class => function ($container) {
                    $tableGateway = $container->get(Model\FabricTableGateway::class);
                    return new Model\FabricTable($tableGateway);
                },
                Model\FabricTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Fabric());
                    return new TableGateway('fabric', $dbAdapter, null, $resultSetPrototype);
                },
				
				
				 Model\SizeTable::class => function ($container) {
                    $tableGateway = $container->get(Model\SizeTableGateway::class);
                    return new Model\SizeTable($tableGateway);
                },
                Model\SizeTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Size());
                    return new TableGateway('size', $dbAdapter, null, $resultSetPrototype);
                },
                
				
				 Model\AccessoryTable::class => function ($container) {
                    $tableGateway = $container->get(Model\AccessoryTableGateway::class);
                    return new Model\AccessoryTable($tableGateway);
                },
				
                Model\AccessoryTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Accessory());
                    return new TableGateway('accessory', $dbAdapter, null, $resultSetPrototype);
                },
				
				

				 Model\MaterialTable::class => function ($container) {
					$tableGateway = $container->get(Model\MaterialTableGateway::class);
					return new Model\MaterialTable($tableGateway);
				},
				 Model\MaterialTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Material());
                    return new TableGateway('material', $dbAdapter, null, $resultSetPrototype);
                },
				
				
				
				 Model\BrandTable::class => function ($container) {
                    $tableGateway = $container->get(Model\BrandTableGateway::class);
                    return new Model\BrandTable($tableGateway);
                },
				 Model\BrandTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Brand());
                    return new TableGateway('brand', $dbAdapter, null, $resultSetPrototype);
                },
				
				
				 Model\CategoryTable::class => function ($container) {
                    $tableGateway = $container->get(Model\CategoryTableGateway::class);
                    return new Model\CategoryTable($tableGateway);
                },

                Model\CategoryTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Category());
                    return new TableGateway('category', $dbAdapter, null, $resultSetPrototype);
                },

				Model\GroupTable::class => function ($container) {
                    $tableGateway = $container->get(Model\GroupTableGateway::class);
                    return new Model\GroupTable($tableGateway);
                },

                Model\GroupTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Group());
                    return new TableGateway('group', $dbAdapter, null, $resultSetPrototype);
                },
				
				
				Model\AdminTable::class => function ($container) {
                    $tableGateway = $container->get(Model\AdminTableGateway::class);
                    return new Model\AdminTable($tableGateway);
                },

                Model\AdminTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Admin());
                    return new TableGateway('admin', $dbAdapter, null, $resultSetPrototype);
                },
				Model\ShoesTable::class => function ($container) {
					$tableGateway = $container->get(Model\ShoesTableGateway::class);
					return new Model\ShoesTable($tableGateway);
				},
				 Model\ShoesTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Shoes());
                    return new TableGateway('shoes', $dbAdapter, null, $resultSetPrototype);
                },
				
            ],
        ];
    }



    // Add this method:
    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\AlbumController::class => function ($container) {
                    return new Controller\AlbumController(
                        $container->get(Model\AlbumTable::class)
                    );
                },
                Controller\BookController::class => function ($container) {
                    return new Controller\BookController(
                        $container->get(Model\BookTable::class)
                    );
                },
                Controller\ClothesController::class => function ($container) {
                    return new Controller\ClothesController(
                        $container->get(Model\ClothesTable::class),
                        $container->get(Model\TypeTable::class),
						$container->get(Model\ColorTable::class),
						$container->get(Model\FabricTable::class),
						$container->get(Model\SizeTable::class)

                    );
                },
				
				Controller\TypeController::class => function ($container) {
                    return new Controller\TypeController(
                        $container->get(Model\TypeTable::class)
                    );
                },
				
				Controller\AdminController::class => function ($container) {
                    return new Controller\AdminController(
                        $container->get(Model\AdminTable::class),
						$container->get(Model\GroupTable::class)
                    );
                },
			
				Controller\GroupController::class => function ($container) {
                   return new Controller\GroupController(
                        $container->get(Model\GroupTable::class)
                    );
                },
		
			     Controller\LoginController::class => function ($container) {
                    return new Controller\LoginController(
						 $container->get(\Admin\Model\AdminTable::class),
					     $container->get(\Admin\Model\GroupTable::class)	
					);
                },
				
				Controller\ColorController::class => function ($container) {
                    return new Controller\ColorController(
                        $container->get(Model\ColorTable::class)
                    );
                },
				
				Controller\FabricController::class => function ($container) {
                    return new Controller\FabricController(
                        $container->get(Model\FabricTable::class)
                    );
                },
				
				Controller\SizeController::class => function ($container) {
                    return new Controller\SizeController(
                        $container->get(Model\SizeTable::class)
                    );
                },
				Controller\AccessoryController::class => function ($container) {
                    return new Controller\AccessoryController(
		
                        $container->get(Model\AccessoryTable::class),
						$container->get(Model\CategoryTable::class),
						$container->get(Model\ColorTable::class),
						$container->get(Model\MaterialTable::class),
					    $container->get(Model\BrandTable::class)				
                    );
                },
				Controller\ShoesController::class => function ($container) {
                    return new Controller\ShoesController(
                        $container->get(Model\ShoesTable::class),
						$container->get(Model\ColorTable::class),
					    $container->get(Model\BrandTable::class)
                    );
                },
			
				Controller\BrandController::class => function ($container) {
                    return new Controller\BrandController(
                        $container->get(Model\BrandTable::class)
                    );
                },
				
				Controller\MaterialController::class => function ($container) {
                    return new Controller\MaterialController(
                        $container->get(Model\MaterialTable::class)
                    );
                },
				
            ],


        ];
    }
}
