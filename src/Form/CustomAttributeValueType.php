<?php

namespace App\Form;

use App\Entity\CustomItemAttributeValue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomAttributeValueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $customAttributes = $options['customAttributes'];

        foreach ($customAttributes as $attribute) {
            switch ($attribute->getType()->value) {
                case 'STRING':
                    $builder->add($attribute->getId(), TextType::class, [
                        'label' => $attribute->getName(),
                        'required' => false,
                    ]);
                    break;
                case 'NUMBER':
                    $builder->add($attribute->getId(), NumberType::class, [
                        'label' => $attribute->getName(),
                        'required' => false,
                    ]);
                    break;
                case 'DATE':
                    $builder->add($attribute->getId(), DateType::class, [
                        'label' => $attribute->getName(),
                        'widget' => 'single_text',
                        'required' => false,
                    ]);
                    break;
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CustomItemAttributeValue::class,
            'customAttributes' => null,
        ]);
    }
}
