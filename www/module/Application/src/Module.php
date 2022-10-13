<?php

declare(strict_types=1);

namespace Application;

class Module
{
    public function getConfig(): array
    {
        /** @var array $config */
        $config = include __DIR__ . '/../config/module.config.php';
        return $config;
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\IndexController::class => function ($container) {
                    return new Controller\IndexController(
                        //$container->get(Model\AlbumTable::class)
                    );
                },
                Controller\AccessoryController::class => function ($container) {
                    return new Controller\AccessoryController(
                        $container->get(\Admin\Model\AccessoryTable::class),
                        $container->get(\Admin\Model\MaterialTable::class)
                    );
                },
                Controller\ClothesController::class => function ($container) {
                    return new Controller\ClothesController(
                        $container->get(\Admin\Model\ClothesTable::class),
                        $container->get(\Admin\Model\TypeTable::class),
                        //$container->get(\Admin\Model\SizeTable::class),
                        $container->get(\Admin\Model\ColorTable::class)
                    );
                },
                Controller\AccessorydetailController::class => function ($container) {
                    return new Controller\AccessorydetailController(
                        $container->get(\Admin\Model\AccessoryTable::class),
                        $container->get(\Admin\Model\MaterialTable::class)
                    );
                },
                Controller\AjaxController::class => function ($container) {
                    return new Controller\AjaxController(
                        $container->get(\Admin\Model\ClothesTable::class),
                        $container->get(\Admin\Model\AccessoryTable::class),
                        $container->get(\Admin\Model\ShoesTable::class)
                    );
                },
                Controller\ShoesController::class => function ($container) {
                    return new Controller\ShoesController(
                        $container->get(\Admin\Model\ShoesTable::class),
                        $container->get(\Admin\Model\BrandTable::class),
                        $container->get(\Admin\Model\ColorTable::class)
                    );
                },
                Controller\ClothesdetailController::class => function ($container) {
                    return new Controller\ClothesdetailController(
                        $container->get(\Admin\Model\ClothesTable::class),
                        $container->get(\Admin\Model\TypeTable::class),
                        $container->get(\Admin\Model\ColorTable::class)
                    );
                },
            ],


        ];
    }
}
