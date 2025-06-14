<?php

namespace Module\Project;

enum ProjectType : string
{
    case EMPTY_DIRECTORY = 'empty-directory';
    case EMPTY_FILE = 'empty-file';
    case SYMFONY_6 = 'symfony-6';

    case SYMFONY_7 = 'symfony-7';
    case SYMFONY_LTS = 'symfony-lts';
    case NEXTJS = 'nextjs';
    case REACTJS = 'reactjs';
    case DRUPAL = 'drupal';
    case DRUPAL_CMS = 'drupal-cms';
    case WORDPRESS = 'wordpress';

    public function getLabel(): string
    {
        return match ($this) {
            self::SYMFONY_7 => 'Symfony 7 + ViteJS + TailwindCSS',
            self::SYMFONY_LTS => 'Symfony LTS + ViteJS + TailwindCSS',
            self::NEXTJS => 'NextJS + TailwindCSS',
            self::REACTJS => 'ReactJS + TailwindCSS',
            self::DRUPAL => 'Drupal',
            self::DRUPAL_CMS => 'DrupalCMS',
            self::WORDPRESS => 'Wordpress',
            self::EMPTY_DIRECTORY => 'Empty directory',
            self::EMPTY_FILE => 'Empty file',
            self::SYMFONY_6 => 'Symfony 6 + ViteJS + TailwindCSS',
        };
    }

    public static function getChoices(): array
    {
        return array_reduce(
            self::cases(),
            fn(array $choices, self $type) => $choices + [$type->value => $type->getLabel()],
            []
        );
    }
}
