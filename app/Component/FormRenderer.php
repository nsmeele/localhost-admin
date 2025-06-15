<?php

namespace Component;

use Symfony\Component\Form\FormView;

final readonly class FormRenderer implements \Stringable
{
    public function __construct(
        private FormView $formView,
    ) {
    }

    private function renderLabel(FormView $child) : string
    {
        return '<label for="'.$child->vars[ 'id' ].'">'.$child->vars[ 'label' ].'</label>';
    }

    private function renderChoice(FormView $child) : string
    {
        $html        = sprintf("<select id=\"%s\" name=\"%s\">", $child->vars[ 'id' ], $child->vars[ 'full_name' ]);
        $placeholder = $child->vars[ 'placeholder' ] ?? null;

        if ($placeholder) {
            $html .= sprintf(
                "<option value=\"\"%s>%s</option>",
                $child->vars[ 'value' ] === null ? ' selected' : '',
                htmlspecialchars($placeholder)
            );
        }

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

    private function renderInput(FormView $child) : string
    {
        return '<input type="'.$child->vars[ 'block_prefixes' ][ 1 ].'" id="'.$child->vars[ 'id' ].'" name="'.$child->vars[ 'full_name' ].'" value="'.htmlspecialchars(
                $child->vars[ 'value' ] ?? ''
            ).'">';
    }

    private function renderWidget(FormView $child) : string
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

    private function formStart(FormView $formView) : string
    {
        $action = $formView->vars[ 'action' ] ?? '';
        $method = strtoupper($formView->vars[ 'method' ] ?? 'POST');
        return sprintf('<form action="%s" method="%s">', htmlspecialchars($action), htmlspecialchars($method));
    }

    private function formEnd() : string
    {
        return '</form>';
    }

    public function __toString() : string
    {
        $html = $this->formStart($this->formView);
        foreach ($this->formView->children as $child) {
            $html .= '<div>';
            $html .= $this->renderLabel($child).'<br>';
            $html .= $this->renderWidget($child);
            foreach ($child->vars[ 'errors' ] ?? [] as $error) {
                $html .= '<span style="color:red;">'.$error->getMessage().'</span><br>';
            }
            $html .= '</div>';
        }
        $html .= $this->formEnd();
        return $html;
    }
}
