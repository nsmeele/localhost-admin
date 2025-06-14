<?php

namespace Service;

use Trait\SingletonPatternTrait;

class FileSystemService
{
    use SingletonPatternTrait;

    protected array $cache = [];

    public function getFolderContents(
        ?string $path = null,
        bool $excludeFiles = false
    ) {
        if (empty($path) || $path == 'root') {
            $path = dirname(ROOT_PATH);
        }

        $cacheIdentifier = md5($path . (int) $excludeFiles);

        if (isset($this->cache[ $cacheIdentifier ])) {
            return $this->cache[ $cacheIdentifier ];
        }

        $files = array_diff(scandir($path), array ('.', '..'));

        if ($excludeFiles) {
            $files = array_filter($files, function ($file) use ($path) {
                return is_dir($path . '/' . $file);
            });
        }

        $this->cache[ $cacheIdentifier ] = array_values($files);

        return $this->cache[ $cacheIdentifier ];
    }
}
