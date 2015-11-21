<?php
function count_news($from = "./"){
    $cur_new = 0;

    $tmp = opendir($from . "news/") or die('Erreur');
    while($entry = @readdir($tmp)) {
        if(!is_dir($directory . '/' . $entry) && $entry != '.' && $entry != '..' && $entry != 'index.php') {
            $cur_new++;
        }
    }
    closedir($tmp);
    
    return $cur_new;
}
?>