<?php

namespace App\Form;

use App\Entity\CustomItemAttributeValue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomAttributeValueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $attributeName = $options['customAttributeNames'];

//        dd($attributeName);

        $builder
            ->add('value', TextType::class, [
                'label' => 'Test label'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CustomItemAttributeValue::class,
            'customAttributeNames' => null,
        ]);
    }
}
