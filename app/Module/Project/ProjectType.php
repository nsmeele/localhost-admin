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
    case REACT = 'react';
    case DRUPAL = 'drupal';
    case DRUPAL_CMS = 'drupal-cms';
    case WORDPRESS = 'wordpress';

    public function getLabel(): string
    {
        return match ($this) {
            self::SYMFONY_7 => 'Symfony 7 + ViteJS + TailwindCSS',
            self::SYMFONY_LTS => 'Symfony LTS + ViteJS + TailwindCSS',
            self::NEXTJS => 'NextJS + TailwindCSS',
            self::REACT => 'ReactJS + TailwindCSS',
            self::DRUPAL => 'Drupal',
            self::DRUPAL_CMS => 'Drupal CMS',
            self::WORDPRESS => 'WordPress',
            self::EMPTY_DIRECTORY => 'Empty directory',
            self::EMPTY_FILE => 'Empty file',
            self::SYMFONY_6 => 'Symfony 6 + ViteJS + TailwindCSS',
        };
    }
}
