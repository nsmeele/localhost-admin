<?php

namespace Module\Project;

final readonly class Resolver
{
    public static function fromType(ProjectType $projectType): StrategyInterface
    {
        $supportedTypes = [
            ProjectType::EMPTY_DIRECTORY => new Strategy\EmptyDirectoryStrategy(),
        ];

        return $supportedTypes[$projectType] ?? throw new \InvalidArgumentException(
            sprintf(
                'Project type "%s" is not supported. Supported types are: %s.',
                $projectType->value,
                implode(', ', array_keys($supportedTypes))
            )
        );
    }
}
