<?php

namespace App\Menu;

use App\Entity\Item;
use App\Repository\CollectionCategoryRepository;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

final class MenuBuilder
{
    public function __construct(
        private FactoryInterface             $factory,
        private CollectionCategoryRepository $categoryRepository
    )
    {
    }

    public function createMainMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Home', ['route' => 'app_home']);
        $menu->addChild('Home', ['route' => 'app_home']);
        $menu->addChild('Home', ['route' => 'app_home']);

        return $menu;
    }

    public function createSidebarMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('All',[
            'route' => 'app_collection',
        ]);

        foreach ($this->categoryRepository->findAll() as $category) {
            $menu->addChild($category->getName(),[
                'route' => 'app_collection',
                'routeParameters' => ['category' => $category->getId()]
            ]);
        }

        return $menu;
    }
}


