<?php

namespace Component\Form\Field;

use Component\Form\FieldComponent;

class InputCheckbox extends FieldComponent
{
    protected function getDefaultProperties(): array
    {
        return array_merge(parent::getDefaultProperties(), ['checked' => false]);
    }

    public function __toString(): string
    {
        $format = '<div class="form-check">' .
            '<input class="form-check-input" type="checkbox" name="%s" value="%s" id="%s" />' .
            '<label class="form-check-label" for="%s">%s</label>' .
            '</div>';

        return sprintf($format, $this->name, $this->value, $this->id, $this->id, $this->label);
    }
}
