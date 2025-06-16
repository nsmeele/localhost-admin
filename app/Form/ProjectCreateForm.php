<?php

namespace Form;

use Module\Project\ProjectType;
use Service\ProjectService;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;

final class ProjectCreateForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', Type\TextType::class, [
                'label'       => 'Project Name',
                'constraints' => [
                    new Constraints\NotBlank(['message' => 'Name is required.']),
                ],
            ])
            ->add('parent', Type\ChoiceType::class, [
                'label'        => 'Parent Project',
                'choices'      => $this->getParentProjects(),
                'placeholder'  => 'Select a parent project',
            ])
            ->add('type', Type\EnumType::class, [
                'label'        => 'Project Type',
                'class'        => ProjectType::class,
                'choice_label' => static fn(ProjectType $projectType): string => $projectType->getLabel(),
                'placeholder'  => 'Select a project type',
                'multiple'     => false,
                'constraints'  => [
                    new Constraints\NotBlank(['message' => 'Type is required.']),
                ],
            ])
            ->add('submit', Type\SubmitType::class, [
                'label' => 'Create Project',
            ]);
    }

    private function getParentProjects(): array
    {
        $projectService = new ProjectService();
        $projectPath    = $projectService->getProjectPath();
        $finder         = new Finder();

        $projects = $finder
            ->directories()
            ->in($projectPath)
            ->depth('== 0');

        $options = [];
        foreach ($projects as $project) {
            $options[ $project->getRelativePathname() ] = $project->getRelativePathname();
        }

        return $options;
    }
}
