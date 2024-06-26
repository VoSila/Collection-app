<?php

namespace App\Form;

use App\Entity\CustomItemAttribute;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Enum\CustomAttributeType as CustomAttributeTypeEnum;
use Symfony\Contracts\Translation\TranslatorInterface;


class CustomAttributeType extends AbstractType
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => $this->translator->trans('name')
            ])
            ->add('type', EnumType::class, [
                'class' => CustomAttributeTypeEnum::class,
                'label' => $this->translator->trans('type')
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CustomItemAttribute::class,
        ]);
    }
}
