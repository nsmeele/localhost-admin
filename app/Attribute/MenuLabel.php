<?php

namespace Attribute;

#[\Attribute(\Attribute::TARGET_METHOD)]
final readonly class MenuLabel
{
    public function __construct(
        public string $label,
        public ?string $parent = null,
        public ?string $icon = 'chevron-right',
    ) {
    }
}
