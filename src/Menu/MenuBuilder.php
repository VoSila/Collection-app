<?php

namespace App\Menu;

use App\Repository\CollectionCategoryRepository;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final readonly class MenuBuilder
{
    public function __construct(
        private FactoryInterface             $factory,
        private TranslatorInterface          $translator,
        private CollectionCategoryRepository $categoryRepository
    )
    {
    }

    public function createSidebarMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('All', [
            'route' => 'app_collection',
        ]);

        foreach ($this->categoryRepository->findAll() as $category) {
            $menu->addChild($this->translator->trans($category->getName()), [
                'route' => 'app_collection',
                'routeParameters' => ['category' => $category->getId()]
            ]);
        }

        return $menu;
    }
}


