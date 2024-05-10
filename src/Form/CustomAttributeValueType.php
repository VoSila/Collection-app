<?php

namespace App\Form;

use App\Entity\CustomItemAttribute;
use App\Entity\CustomItemAttributeValue;
use App\Entity\Item;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomAttributeValueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('value', TextType::class, [
                'label' => $options['customItemAttribute']->getName(),
                'mapped' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('customItemAttribute');
        $resolver->setAllowedTypes('customItemAttribute', CustomItemAttribute::class);
    }
}
