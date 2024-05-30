<?php

namespace App\Validator;

use App\Entity\CustomItemAttribute;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CollectionCustomAttributeValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        /* @var CollectionCustomAttribute $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_iterable($value)) {
            return;
        }

        $countPerType = [];

        /**@var CustomItemAttribute[] $value */
        foreach ($value as $attribute) {
            $countPerType[$attribute->getType()->name] = isset($countPerType[$attribute->getType()->name])
                ? ++$countPerType[$attribute->getType()->name]
                : 1;
        }

        foreach ($countPerType as $type => $count){
            if($count > $constraint->maxItemsPerType){
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{max}}', $constraint->maxItemsPerType)
                    ->setParameter('{{count}}', $count)
                    ->setParameter('{{type}}', $type)

                    ->addViolation();
            }
        }


    }
}
