<?php

namespace App\Form;

use App\Entity\CustomItemAttribute;
use App\Entity\Item;
use App\Entity\ItemCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('itemCollection', EntityType::class, [
                'class' => ItemCollection::class,
                'choice_label' => 'name',
            ])
            ->add('customItemAttribute', EntityType::class, [
                'class' => CustomItemAttribute::class,
                'choice_label' => 'name',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
