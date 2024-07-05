<?php

// On Windows:

$disk1 = ".";
$bytes = disk_free_space($disk1);
// $bytes = disk_free_space("D:");

// $bytes = disk_free_space($disks);
$si_prefix = array ('B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB');
$base      = 1024;
$class     = min((int) log($bytes, $base), count($si_prefix) - 1);
echo sprintf('%1.2f', $bytes / pow($base, $class)) . ' ' . $si_prefix[ $class ] . '<br />';
