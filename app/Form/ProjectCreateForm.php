<?php

namespace Form;

use Module\Project\ProjectType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProjectCreateForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label'       => 'Project Name',
                'constraints' => [
                    new NotBlank(['message' => 'Name is required.']),
                ],
            ])
            ->add('type', EnumType::class, [
                'label'       => 'Project Type',
                'class'       => ProjectType::class,
                'multiple'    => false,
                'constraints' => [
                    new NotBlank(['message' => 'Type is required.']),
                ],
            ]);
    }
}
