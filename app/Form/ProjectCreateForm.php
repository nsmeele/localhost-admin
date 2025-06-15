<?php

namespace Form;

use Module\Project\ProjectType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;

class ProjectCreateForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Project Name',
                'required' => true,
            ])
            ->add('type', EnumType::class, [
                'label' => 'Project Type',
                'required' => true,
                'class' => ProjectType::class,
                'multiple' => false,
            ]);
    }
}
