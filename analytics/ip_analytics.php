<?php
    // lecture de l'ip table
    $ips_file = fopen('analytics/Stats/ip.txt', 'r+');
    $current_ip = $_SERVER['REMOTE_ADDR'];
    $ips_table = array();
    
    $ip_in_file = false;
    
    while (!feof($ips_file)){
        $ligne_tmp = fgets($ips_file);
        
        foreach (explode(";", $ligne_tmp) as $ligne){
            if (preg_match("#:#", $ligne)){
                $array_ligne = explode(":", $ligne);
                $tmp_ip = $array_ligne[0];
                $tmp_vues = $array_ligne[1];
                
                if (strlen($tmp_ip) >= 8 and preg_match("#.+#", $tmp_ip)){ $ips_table[$tmp_ip] = $tmp_vues; }
                
                if ($tmp_ip == $current_ip){
                    $ips_table[$tmp_ip]++;
                    $ip_in_file = true;
                }
            }
        }
    }
    
    if (!$ip_in_file){ $ips_table[$current_ip] = 1; }
    
    fseek($ips_file, 0);
    $first = true;
    foreach($ips_table as $ip => $vue){
        if (!is_array($ip) and !is_array($vue)){
            if($first){
                fputs($ips_file, $ip . ':' . $vue);
                $first = false;
            }else{
                fputs($ips_file, ";" . $ip . ':' . $vue);
            }
        }
    }
    fclose($ips_file);
?>