<?php
    $monfichier = fopen('analytics/Stats/views.txt', 'r+');
    $vues = 0;
    $vues = fgets($monfichier);
    $vues++;
    fseek($monfichier, 0);
    fputs($monfichier, $vues);
    fclose($monfichier);
?>