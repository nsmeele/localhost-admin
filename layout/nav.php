<?php
$basePath = realpath(ROOT_PATH.'/pages');
$navItems = getMenuItems($basePath);

function renderMenu(array $navItems) : string
{
    $html = '';
    if (! empty($navItems)) {
        $html .= '<ul>';
        foreach ($navItems as $navItem) {

            $navItemHtml = sprintf(
                '<a href="%s">%s%s</a>',
                $navItem[ 'url' ],
                (! empty($navItem[ 'icon' ]) ? '<i class="fas fa-'.$navItem[ 'icon' ].' fa-fw mr-2"></i>' : ''),
                ucfirst(strtolower($navItem[ 'title' ]))
            );

            $childrenHtml = '';

            if (! empty($navItem[ 'children' ])) {
                $childrenHtml = renderMenu($navItem[ 'children' ]);

            }

            $html .= sprintf('<li>%s%s</li>', $navItemHtml, $childrenHtml);

        }
        $html .= '</ul>';
    }

    return $html;

}

function getMenuItems($path, int $depth = 0) : array
{
    global $basePath;

    $navItems = [];
    $files    = array_diff(scandir($path), array ('.', '..'));

    foreach ($files as $file) {

        if ($file === 'index.php') {
            // we handle that differently
            continue;
        }

        $subPath = $path.'/'.$file;
        $url     = str_replace($basePath, '', $path.'/'.basename($file, '.php'));

        $navItems[ $subPath ] = [
            'url'      => $url,
            'title'    => basename($subPath, '.php'),
            'icon'     => '',
            'parent'   => $path,
            'children' => array (),
        ];

        if (is_dir($subPath)) {
            $navItems[ $subPath ][ 'children' ] = getMenuItems($subPath, $depth + 1);
        }
    }

    return $navItems;

}

$navItems[ $basePath.'/dashboard.php' ][ 'icon' ] = 'power-off';
$navItems[ $basePath.'/projects' ][ 'title' ]     = 'Projects';
$navItems[ $basePath.'/projects' ][ 'icon' ]      = 'heart';
$navItems[ $basePath.'/server-info' ][ 'title' ]  = 'Server info';
$navItems[ $basePath.'/server-info' ][ 'icon' ]   = 'battery-three-quarters';


echo "<div class='inner'>";
echo "<h3>Menu</h3>";

$filterNavItems = $navItems;
echo renderMenu($navItems);

echo '</div>';
