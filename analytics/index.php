<?php
    // nombre de vues du site (index.php)
    $monfichier = fopen('Stats/views.txt', 'r');
    
    $vues = 0;
    $vues = fgets($monfichier);
    
    fclose($monfichier);
    
    echo "Nombre de vues du site web (index.php) : <b>" . $vues . "</b><br /><br />";
    
    // nombre de visites par ip (index.php)
    echo "Visites par IP : <br />";
    $ips_file = fopen('Stats/ip.txt', 'r');
    
    $nb_visites = 0;
    
    $current_ip = $_SERVER['REMOTE_ADDR'];
    
    while (!feof($ips_file)){
        $ligne_tmp = fgets($ips_file);
        
        foreach(explode(";", $ligne) as $ligne){
            $array_ligne = explode(":", $ligne);
            $tmp_ip = $array_ligne[0];
            $tmp_vues = $array_ligne[1];
            
            if ($tmp_ip == $current_ip){
                echo 'Vos visites (' . $current_ip . ') : <b>' . $tmp_vues . '</b><br />';
                $nb_visites = $tmp_vues;
            }else{
                echo 'IP : <b>' . $tmp_ip . '</b> ; nombre de visites : <b>' . $tmp_vues . '</b><br />';
            }
        }
    }
    
    $vues -= $nb_visites;
    
    echo '<br />Soit un total de ' . $vues . ' visites :) <br />';
    
    fclose($ips_file);
    
    echo '<br />';
    
    // nombre de visites par jour (index.php)
    /*
    echo "Nombre de visiteurs uniques ayant visité le site (par jour) (index.php) : <br />";
    
    // lecture de l'ip table par jour
    $ip_day_file = fopen('Stats/ip_per_day.txt', 'r');
    $cur_day = date("d-m-Y");
    
    while (!feof($ip_day_file)){
        $ligne = fgets($ip_day_file);
        
        $array_ligne = explode(":", $ligne);
        $tmp_day = $array_ligne[0];
        $tmp_vues = $array_ligne[1];
        $tmp_ips = explode(";", $array_ligne[2]);
        
        echo 'Date : <b>' . $tmp_day . '</b> ; vues (uniques) : <b>' . $tmp_vues . '</b>';
        
        foreach($tmp_ips as $ip){
            echo "<br />&nbsp;&nbsp;&nbsp;&nbsp;- <b>" . $ip . "</b>";
        }
    }
    fclose($ip_day_file);
    
    echo "<br />";
    */
?>