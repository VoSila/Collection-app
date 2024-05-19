<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class CollectionCustomAttribute extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public string $message = 'Collection of attributes should contain no more than "{{max}}" of type "{{type}}". It contains "{{count}}"';

    public int $maxItemsPerType = 3;

    public function __construct(mixed $options = null, ?array $groups = null, mixed $payload = null, int $maxItemsPerType = 3)
    {
        parent::__construct($options, $groups, $payload);

        $this->maxItemsPerType = $maxItemsPerType;
    }
}
