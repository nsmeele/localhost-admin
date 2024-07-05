<?php

echo '<table class="table table-hover col-md-6">';

echo '<tr><th>Itemkey</th><th>Itemvalue</th></tr>';
foreach ($GLOBALS as $itemKey => $item) {
    echo '<tr><td>' . $itemKey . '</td><td>';

    try {
        if (! is_array($item)) {
            echo $item;
        } elseif (! empty($item)) {
            print_r($item);
            ;
        } else {
            echo '-';
        }
    } catch (\Throwable $exception) {
        printf($exception);
    }
    echo '</td></tr>';
}


echo '</table>';
