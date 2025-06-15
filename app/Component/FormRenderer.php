<?php

namespace Component;

use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormView;

final readonly class FormRenderer implements \Stringable
{
    private FormView $formView;

    public function __construct(
        private FormInterface $formType,
    ) {
        $this->formView = $this->formType->createView();
    }

    private function renderLabel(FormView $child): string
    {
        return sprintf(
            "<div><label class='font-medium' for=\"%s\">%s</label></div>",
            $child->vars[ 'id' ],
            $child->vars[ 'label' ]
        );
    }

    private function renderSelect(FormView $child): string
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

    private function renderInput(FormView $child): string
    {
        return sprintf(
            "<input type=\"%s\" id=\"%s\" name=\"%s\" value=\"%s\">",
            $child->vars[ 'block_prefixes' ][ 1 ],
            $child->vars[ 'id' ],
            $child->vars[ 'full_name' ],
            htmlspecialchars(
                $child->vars[ 'value' ] ?? ''
            )
        );
    }

    private function renderButton(FormView $child): string
    {
        return sprintf(
            '<button type="%s" id="%s" name="%s">%s</button>',
            $child->vars[ 'block_prefixes' ][ 1 ],
            $child->vars[ 'id' ],
            $child->vars[ 'full_name' ],
            htmlspecialchars($child->vars[ 'label' ] ?? '')
        );
    }

    private function getFieldType(string $name): FormTypeInterface
    {
        if (! $this->formType->has($name)) {
            throw new \InvalidArgumentException("Field '$name' does not exist.");
        }

        return $this->formType->get($name)
            ->getConfig()
            ->getType()
            ->getInnerType();
    }

    private function renderWidget(
        FormView $child,
        FormTypeInterface $type,
    ): string {
        $innerTypeClass = get_class($type);
        return match ($innerTypeClass) {
            Type\TextareaType::class => sprintf(
                "<textarea id=\"%s\" name=\"%s\">%s</textarea>",
                $child->vars[ 'id' ],
                $child->vars[ 'full_name' ],
                htmlspecialchars(
                    $child->vars[ 'value' ]
                )
            ),
            Type\EnumType::class, Type\ChoiceType::class => $this->renderSelect($child),
            Type\ButtonType::class, Type\SubmitType::class => $this->renderButton($child),
            default => $this->renderInput($child),
        };
    }

    private function formStart(FormView $formView): string
    {
        $action = $formView->vars[ 'action' ] ?? '';
        $method = strtoupper($formView->vars[ 'method' ] ?? 'POST');
        return sprintf('<form action="%s" method="%s"><div class="flex flex-col gap-2">', htmlspecialchars($action), htmlspecialchars($method));
    }

    private function formEnd(): string
    {
        return '</div></form>';
    }

    public function __toString(): string
    {
        $html = $this->formStart($this->formView);
        foreach ($this->formView->children as $child) {
            $html .= '<div>';
            $type = $this->getFieldType($child->vars[ 'name' ]);
            if (
                $type instanceof Type\HiddenType === false
                && $type instanceof Type\ButtonType === false
                && $type instanceof Type\SubmitType === false
            ) {
                $html .= $this->renderLabel($child);
            }
            $html .= $this->renderWidget($child, $type);
            foreach ($child->vars[ 'errors' ] ?? [] as $error) {
                $html .= '<div class="text-red-500">' . $error->getMessage() . '</div>';
            }
            $html .= '</div>';
        }
        $html .= $this->formEnd();
        return $html;
    }
}
