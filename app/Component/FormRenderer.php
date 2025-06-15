<?php

namespace Component;

use Symfony\Component\Form\FormView;

final readonly class FormRenderer implements \Stringable
{
    public function __construct(
        private FormView $formView,
    ) {
    }

    private function renderLabel(FormView $child): string
    {
        return '<label for="' . $child->vars[ 'id' ] . '">' . $child->vars[ 'label' ] . '</label>';
    }

    private function renderChoice(FormView $child): string
    {
        $html = sprintf("<select id=\"%s\" name=\"%s\">", $child->vars[ 'id' ], $child->vars[ 'full_name' ]);
        foreach ($child->vars[ 'choices' ] as $choice) {
            $selected = $choice->value === $child->vars[ 'value' ] ? ' selected' : '';
            $html     .= sprintf(
                "<option value=\"%s\"%s>%s</option>",
                htmlspecialchars($choice->value),
                $selected,
                htmlspecialchars(
                    $choice->label
                )
            );
        }
        $html .= '</select>';
        return $html;
    }

    private function renderInput(FormView $child): string
    {
        return '<input type="' . $child->vars[ 'block_prefixes' ][ 1 ] . '" id="'.$child->vars[ 'id' ].'" name="' . $child->vars[ 'full_name' ] . '" value="' . htmlspecialchars(
            $child->vars[ 'value' ]
        ) . '">';
    }

    private function renderWidget(FormView $child): string
    {
        return match ($child->vars[ 'block_prefixes' ][ 1 ]) {
            'textarea' => sprintf(
                "<textarea id=\"%s\" name=\"%s\">%s</textarea>",
                $child->vars[ 'id' ],
                $child->vars[ 'full_name' ],
                htmlspecialchars(
                    $child->vars[ 'value' ]
                )
            ),
            'choice' => $this->renderChoice($child),
            default => $this->renderInput($child),
        };
    }

    public function __toString(): string
    {
        $html = '<form method="POST">';
        foreach ($this->formView->children as $child) {
            $html .= '<div>';
            $html .= $this->renderLabel($child) . '<br>';
            $html .= $this->renderWidget($child);
            foreach ($child->vars[ 'errors' ] as $error) {
                $html .= '<span style="color:red;">' . $error->getMessage() . '</span><br>';
            }
            $html .= '</div>';
        }
        $html .= '<button type="submit">Save</button></form>';
        return $html;
    }
}
