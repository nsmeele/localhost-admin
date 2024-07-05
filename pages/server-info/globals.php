<?php
echo '<table class="table table-hover col-md-6">';

echo '<tr><th>Itemkey</th><th>Itemvalue</th></tr>';
foreach ($GLOBALS as $itemKey => $item) {

    echo '<tr><td>'.$itemKey.'</td><td>';
    if (! is_array($item)) {
        echo $item;
    } elseif (! empty($item)) {
        print_r($item);;
    } else {
        echo '-';
    }
    echo '</td></tr>';

}


echo '</table>';