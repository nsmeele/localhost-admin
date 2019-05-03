<?php
$path = dirname(__FILE__) . '/pages';
$files = getFiles($path);

// Maak voor iedere bestand in "/pages" folder een nieuwe key aan
foreach ($files as $fileNumber => $fileName) {
    // Bestandsinfo
    $pageInfo[$fileNumber] = pathinfo($fileName);
    $fileExtension = (isset($pageInfo[$fileNumber]['extension']) ? '.' . $pageInfo[$fileNumber]['extension'] : '');
    // Bestandsnaam
    $page = basename($fileName, $fileExtension);
    // Verwijder speciale tekens en vervang door spatie
    $page = preg_replace("/[^a-zA-Z0-9.]/", " ", $page);
    // Get file extension
    $fileExtension = pathinfo($path . '/' . basename($fileName), PATHINFO_EXTENSION);
    // File-name without extension
    $fileLabel = basename($fileName, '.' . $fileExtension);

    //Type
    if (is_file($path . '/' . basename($fileName)))
        $fileType = "file";
    if (is_dir($path . '/' . basename($fileName)))
        $fileType = "folder";

    // Geef per array mijn gegevens
    $filterNavItems[$fileLabel] = array(
        "type" => $fileType,
        "parent-folder" => basename(dirname($path)),
        "path-folder" => basename($path),
        "file-name" => basename($fileName),
        "label" => $fileLabel,
        "pretty-label" => ucwords($page),
        "file-extension" => pathinfo($path . '/' . basename($fileName), PATHINFO_EXTENSION),
        "icon" => "chevron-right",
        // "pathinfo" => pathinfo($fileName),
    );

    $filterItems[] = $fileLabel;

}

if (isset($filterNavItems)) {
    // Menu icoontjes
    $filterNavItems['dashboard']['icon'] = "power-off";
    $filterNavItems['projects']['icon'] = "heart";
    $filterNavItems['server-info']['icon'] = "battery-three-quarters";
}


// Dynamic page
if (isset($_GET['p']) && in_array($_GET['p'], $filterItems)) {
    $page = $_GET['p'];
} else {
    $page = 'dashboard.php';
}


echo "<div class='inner'>";
echo "<h3>Menu</h3>";
echo "<ul>";
foreach ($filterNavItems as $navItem) {

    if ($navItem['type'] == 'folder' && $navItem['icon'] == 'chevron-right')
        $navItem['icon'] = "chevron-down";

    $nicePath = $navItem['parent-folder'] . "/" . $navItem['path-folder'] . "/" . $navItem['file-name'];
    $dynamicPath = $index . "?p=" . $navItem['label'];

    $active = (isset($_GET['p']) && $navItem['label'] == $_GET['p'] && empty($_GET['subpage']) ? implode(" ", $navItem) . ' current-menu-item' : implode(" ", $navItem));

    echo "<li class='" . $active . "'>";
    echo "<a href='" . $dynamicPath . "' title='Permalink naar " . $navItem['pretty-label'] . "'>";
    echo "<i class='fas fa-" . $navItem['icon'] . " fa-fw'></i>&nbsp;";
    echo $navItem['pretty-label'] . "</a>";


    // Subpages
    if ($navItem['type'] == 'folder') {
        $path = dirname(__FILE__) . '/pages' . '/' . $navItem['label'];
        foreach (getFiles($path) as $subpage) {

            if (is_file($path . '/' . basename($subpage)))
                $fileType = "file";
            if (is_dir($path . '/' . basename($subpage)))
                $fileType = "folder";

            $args = pathinfo($subpage);
            $args['type'] = $fileType;
            if (isset($args['extension'])) {
                $filterNavItems[$navItem['label']]['subpages'][basename($subpage, '.' . $args['extension'])] = $args;
            } else {
                $filterNavItems[$navItem['label']]['subpages'][basename($subpage)] = $args;
            }

        }


        if (isset($filterNavItems[$navItem['label']]['subpages'])) {
            echo "<ul class='submenu'>";

            if (array_key_exists('index', $filterNavItems[$navItem['label']]['subpages'])) {
                $indexPage = $filterNavItems[$navItem['label']]['subpages']['index'];
                unset($filterNavItems[$navItem['label']]['subpages']['index']);
            }

            foreach ($filterNavItems[$navItem['label']]['subpages'] as $subPageKey => $subPageValue) {
                // Verwijder speciale tekens en vervang door spatie
                $subPagePrettyLabel = preg_replace("/[^a-zA-Z0-9.]/", " ", $subPageValue['filename']);
                $subPagePrettyLabel = ucwords($subPagePrettyLabel);
                $filterNavItems[$navItem['label']]['subpages'][$subPageValue['filename']]['pretty-label'] = $subPagePrettyLabel;

                $dynamicPath = $index . "?p=" . $navItem['label'] . "&subpage=" . $subPageValue['filename'];
                $activeSub = (isset($_GET['subpage']) && $_GET['subpage'] == $subPageValue['filename'] ? implode(" ", $subPageValue) . ' current-menu-item child' : implode(" ", $subPageValue));


                echo "<li class='" . $activeSub . "'><a href='" . $dynamicPath . "' title='Permalink naar " . $subPagePrettyLabel . "'><span class='toggle-folder'><i class=\"fas fa-angle-double-right fa-fw\"></i></span>&nbsp;" . $subPagePrettyLabel . "</a></li>";


            }

            echo "</ul>";
        }


    }


    echo "</li>";
}


echo "</ul>";
echo "</div>";
