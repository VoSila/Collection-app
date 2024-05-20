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

        if (isset($forms['tags'])) {
            $forms['tags']->setData($viewData->getTags());
        }

        foreach ($viewData->getCustomItemAttributeValues() as $attribute) {
            $formName = $attribute->getCustomItemAttribute()->getName();
            $value = $attribute->getValue();

            if (is_array($value) && isset($value['date'])) {
                $date = new \DateTimeImmutable($value['date']);
                $forms[$formName]->setData($date);
            } else {
                $forms[$formName]->setData($value);
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
                $existingAttribute = null;
                foreach ($viewData->getCustomItemAttributeValues() as $attribute) {
                    if ($attribute->getCustomItemAttribute()->getName() === $name) {
                        $existingAttribute = $attribute;
                        break;
                    }
                }

                if ($existingAttribute) {
                    $existingAttribute->setValue($form->getData());
                } else {
                    $customItemAttributeValue = new CustomItemAttributeValue();
                    $customItemAttributeValue->setValue($form->getData());

                    $customItemAttribute = $this->customItemAttributeRepository->findBySomeCriteria($name);

                    $customItemAttributeValue->setCustomItemAttribute($customItemAttribute);

                    $customItemAttributeValue->setItem($viewData);

                    $viewData->addCustomItemAttributeValue($customItemAttributeValue);

                    $this->entityManager->persist($customItemAttributeValue);
                }
            }
        }
    }
}
