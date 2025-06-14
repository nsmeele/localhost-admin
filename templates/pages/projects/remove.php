<?php

global $request;

$path = (string) $request->query->get('path');

$fileSystem = new \Symfony\Component\Filesystem\Filesystem();
if ($fileSystem->exists($path)) {
    $fileSystem->remove($path);
}
