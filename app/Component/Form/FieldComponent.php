<?php

namespace Component\Form;

use Trait\DynamicPropertiesTrait;

#[\AllowDynamicProperties]
abstract class FieldComponent
{
    use DynamicPropertiesTrait;

    protected function getDefaultProperties(): array
    {
        return [
            'name'  => null,
            'id'    => null,
            'value' => null,
            'label' => null,
            'type'  => null
        ];
    }
}
