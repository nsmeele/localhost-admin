<?php
$browserStatics = get_browser(null, true);

echo '<table class="table table-hover">';
foreach ($browserStatics as $browserStaticKey => $browserValue) {

    echo '<tr>';
    echo '<td>'.strtoupper($browserStaticKey).'</td>';
    echo '<td>'.$browserValue.'</td>';
    echo '</tr>';

}
echo '</table>';