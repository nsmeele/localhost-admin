<?php

namespace Module\Project;

final readonly class Resolver
{
    public static function fromType(ProjectType $projectType): StrategyInterface
    {
        return match ($projectType) {
            ProjectType::EMPTY_DIRECTORY => new Strategy\EmptyDirectoryStrategy(),
            default => throw new \InvalidArgumentException(
                sprintf('Project type "%s" is not supported.', $projectType->value)
            ),
        };
    }
}
