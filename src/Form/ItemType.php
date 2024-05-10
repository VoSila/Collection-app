<?php

namespace App\Form;

use App\Entity\Item;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType as BaseCollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        dd($options['customItemAttribute']);
        $builder
            ->add('name', TextType::class)
            ->add('customItemAttributeValues', BaseCollectionType::class, [
                'entry_type' => CustomAttributeValueType::class,
                'entry_options' => [
                    'customItemAttribute' => $options['customItemAttribute'],
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
            'customItemAttribute' => null,
        ]);
    }
}
