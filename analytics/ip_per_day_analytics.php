<?php
    // lecture de l'ip table par jour
    $ip_day_file = fopen('analytics/Stats/ip_per_day.txt', 'r+');
    $current_ip = $_SERVER['REMOTE_ADDR'];
    $ips_day_table = array();
    $cur_day = date("d-m-Y");
    
    $ip_in_file = false;
    $empty_file = false;
    
    while (!feof($ip_day_file)){
        $ligne = fgets($ip_day_file);
        
        $array_ligne = explode(":", $ligne);
        if (count($array_ligne) == 0 or !preg_match("#:+#", $ligne)){ $empty_file = true; break; }
        $tmp_day = $array_ligne[0];
        $tmp_vues = $array_ligne[1];
        $tmp_ips = explode(";", $array_ligne[2]);
        if (count($tmp_ips) == 0 or !preg_match("#;+#", $array_ligne[2])){ $empty_file = true; break; }
        
        $ips_day_table[$tmp_day] = array($tmp_vues, $tmp_ips);
        
        if ($tmp_day == $cur_day){
            foreach($tmp_ips as $tmp_ip){
                if ($tmp_ip == $current_ip){
                    $ips_day_table[$tmp_day][0]++;
                    $ip_in_file = true;
                    break;
                }
            }
        }
    }
    
    if ($empty_file){ $ips_day_table = array(); }
    
    if ($ip_in_file == false){
        if (!$empty_file){
            $ips_day_table[$cur_day][0]++;
            $ips_day_table[$cur_day][1][] = $current_ip;
        }else{
            $ips_day_table[$cur_day] = array();
            $ips_day_table[$cur_day][0]++;
            $ips_day_table[$cur_day][1][] = $current_ip;
        }
    }
    
    fseek($ip_day_file, 0);
    $first = true;
    $buffer = "";
    foreach($ips_day_table as $day => $value){
        $tmp_cpt = 0;
        $tmp_array = $value[1];
        foreach($tmp_array as $ip){
            if ($tmp_cpt == count($value[1]) - 1){
                $buffer .= $ip;
            }else{
                $buffer .= $ip . ';';
            }
            $tmp_cpt++;
        }
        
        if ($first){
            fputs($ip_day_file, $day . ':' . $value[0] . ':' . $buffer);
            $first = false;
        }else{
            fputs($ip_day_file, "\r" . $day . ':' . $value[0] . ':' . $buffer);
        }
    }
    fclose($ip_day_file);
?>