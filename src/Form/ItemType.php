<?php

namespace App\Form;

use App\Entity\Item;
use App\Form\DataTransformer\TagTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType as BaseCollectionType;

class ItemType extends AbstractType
{
    public function __construct(private readonly TagTransformer $tagTransformer)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('customItemAttributeValues', BaseCollectionType::class, [
                'entry_type' => CustomAttributeValueType::class,
                'entry_options' => [
                    'label' => false,
                    'customAttributeNames' => $options['customAttributeNames']
                ],
            ])
            ->add('tags', TextType::class, [
                'label' => 'Tags',
                'required' => false
            ]);

        $builder->get('tags')
            ->addModelTransformer($this->tagTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
            'customAttributeNames' => [], // Пустой массив по умолчанию
        ]);
    }
}
