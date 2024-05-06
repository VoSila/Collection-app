<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\CollectionCategory;

class CollectionCategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categoryNames = ['Animals', 'Cars', 'Games', 'Other'];

        foreach ($categoryNames as $name) {
            $collectionCategory = new CollectionCategory();
            $collectionCategory->setName($name);
            $manager->persist($collectionCategory);
        }

        $manager->flush();
    }
}
