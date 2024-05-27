<?php

namespace App\Form;

use AllowDynamicProperties;
use App\Entity\Item;
use App\Form\DataMapper\CustomAttributesDataMapper;
use App\Form\DataTransformer\TagTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AllowDynamicProperties]
final class ItemType extends AbstractType
{
    public function __construct(private readonly TagTransformer             $tagTransformer,
                                private readonly CustomAttributesDataMapper $customAttributesDataMapper,
                                private readonly TranslatorInterface        $translator,

    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label' => $this->translator->trans('name'),
            'required' => true
        ]);

        foreach ($options['customAttributes'] as $attributeType) {
            $attributeName = $attributeType->getName();

            $modifiedName = str_replace("_", " ", $attributeName);

            $attributeTypeValue = $attributeType->getType()->value;

            switch ($attributeTypeValue) {
                case 'INTEGER':
                    $builder->add($attributeName, IntegerType::class, [
                        'label' => $modifiedName,
                        'required' => false
                    ]);
                    break;
                case 'TEXT':
                    $builder->add($attributeName, TextareaType::class, [
                        'label' => $modifiedName,
                        'required' => false
                    ]);
                    break;
                case 'BOOL':
                    $builder->add($attributeName, CheckboxType::class, [
                        'label' => $modifiedName,
                        'required' => false
                    ]);
                    break;
                case 'DATE':
                    $builder->add($attributeName, DateType::class, [
                        'label' => $modifiedName,
                        'required' => false
                    ]);
                    break;
                default:
                    $builder->add($attributeName, TextType::class, [
                        'label' => $modifiedName,
                        'required' => false
                    ]);
                    break;
            }
        }

        $builder->add('tags', TextType::class, [
            'label' => $this->translator->trans('tags'),
            'required' => false,
            'autocomplete' => true,
            'tom_select_options' => [
                'create' => true,
                'createOnBlur' => true,
                'delimiter' => ',',
            ],
            'autocomplete_url' => '/autocomplete/tags',
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
            'tags' => null,
        ]);
    }
}
