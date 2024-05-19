<?php

namespace App\Form;

use AllowDynamicProperties;
use App\Entity\Item;
use App\Form\DataMapper\CustomAttributesDataMapper;
use App\Form\DataTransformer\TagTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

#[AllowDynamicProperties] final class ItemType extends AbstractType
{
    private CustomAttributesDataMapper $customAttributesDataMapper;

    public function __construct(TagTransformer $tagTransformer, CustomAttributesDataMapper $customAttributesDataMapper)
    {
        $this->tagTransformer = $tagTransformer;
        $this->customAttributesDataMapper = $customAttributesDataMapper;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label' => 'Name',
            'required' => true
        ]);

        foreach ($options['customAttributes'] as $attributeType) {
            $attributeName = $attributeType->getName();
            $attributeTypeValue = $attributeType->getType()->value;

            switch ($attributeTypeValue) {
                case 'INTEGER':
                    $builder->add($attributeName, IntegerType::class, [
                        'label' => $attributeName,
                        'required' => false
                    ]);
                    break;
                case 'DATE':
                    $builder->add($attributeName, DateType::class, [
                        'label' => $attributeName,
                        'required' => false
                    ]);
                    break;
                default:
                    $builder->add($attributeName, TextType::class, [
                        'label' => $attributeName,
                        'required' => false
                    ]);
                    break;
            }
        }

        $builder->add('tags', TextType::class, [
        'label' => 'Tags',
        'required' => false
    ]);

        $builder->get('tags')
            ->addModelTransformer($this->tagTransformer);

        $builder->setDataMapper($this->customAttributesDataMapper);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
            'customAttributes' => [],
        ]);
    }
}
