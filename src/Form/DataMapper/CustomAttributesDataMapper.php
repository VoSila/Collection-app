<?php

namespace App\Form\DataMapper;

use App\Entity\CustomItemAttributeValue;
use App\Entity\Item;
use App\Repository\CustomItemAttributeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\FormInterface;

final readonly class CustomAttributesDataMapper implements DataMapperInterface
{

    public function __construct(
        private EntityManagerInterface        $entityManager,
        private CustomItemAttributeRepository $customItemAttributeRepository
    )
    {
    }

    public function mapDataToForms($viewData, \Traversable $forms): void
    {
        if (!$viewData instanceof Item) {
            throw new UnexpectedTypeException($viewData, Item::class);
        }

        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        if (isset($forms['name'])) {
            $forms['name']->setData($viewData->getName());
        }

        foreach ($viewData->getCustomItemAttributeValues() as $attribute) {
            $formName = $attribute->getAttribute()->getName();
            if (isset($forms[$formName])) {
                $forms[$formName]->setData($attribute->getValue());
            }
        }
    }

    public function mapFormsToData(\Traversable $forms, &$viewData): void
    {
        if (!$viewData instanceof Item) {
            throw new UnexpectedTypeException($viewData, Item::class);
        }

        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        if (isset($forms['name'])) {
            $viewData->setName($forms['name']->getData());
        }

        if (isset($forms['tags'])) {
            $viewData->setTags($forms['tags']->getData());
        }

        foreach ($forms as $name => $form) {
            if ($name !== 'name' && $name != 'tags') {
                // Создание нового объекта CustomItemAttributeValue
                $customItemAttributeValue = new CustomItemAttributeValue();
                // Установка значения атрибута из формы
                $customItemAttributeValue->setValue($form->getData());

                // Получение уже существующего объекта CustomItemAttribute по вашим требованиям
                // Например, вы можете использовать ваш репозиторий для поиска нужного объекта
                $customItemAttribute = $this->customItemAttributeRepository->findBySomeCriteria($name);

                // Устанавливаем связь с существующим объектом CustomItemAttribute
                $customItemAttributeValue->setCustomItemAttribute($customItemAttribute);

                // Добавляем кастомное значение атрибута к объекту Item
                $viewData->addCustomItemAttributeValue($customItemAttributeValue);

                // Сохранение объекта CustomItemAttributeValue
                $this->entityManager->persist($customItemAttributeValue);
            }
        }

    }
}
