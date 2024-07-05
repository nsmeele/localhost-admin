<h1>Projects</h1>

<?php

$files = getProjects('root');

// Laad Masonry in,
echo '<script src="../local-admin/assets/js/masonry.pkgd.min.js"></script>';
echo "<div class='masonry row'>";
foreach ($files as $file) {

    $file = basename($file);

    echo "<div class='col-lg-4 col-sm-6 col-xs-12 parent-folder'>";
    echo "<div class='inner'>";
    echo "<h3><a href='/".$file."' target='_blank'>".$file."</a></h3>";

    $subdirs = array_filter(glob($file.'\*'), 'is_dir');
    $count   = count($subdirs);

    if ($count > 0) {
        echo '<ul>';
        foreach ($subdirs as $subdir) {
            echo "<li><a href='".$subdir."' target='_blank'><span class='icon mr-2'><i class=\"far fa-folder\"></i></span><span class='subdir'>".basename($subdir)."</span></a></li>";
        }
        echo '</ul>';
    }
    echo "</div>";
    echo "</div>";

}
echo "</div>"; ?>


<script>

    //Wrap every 3d div
    // var divs = $(".parent-folder");
    // for(var i = 0; i < divs.length; i+=3) {
    //   divs.slice(i, i+3).wrapAll("<div class='row'></div>");
    // }

    $('main article .entry-content .masonry').masonry({
        columnWidth: '.parent-folder',
        itemSelector: '.parent-folder',
        percentPosition: true
    });

</script>
