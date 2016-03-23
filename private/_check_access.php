<?php
$registred_users = array();
$registred_users['Folaefolc'] = '48c5ab554eeef97b05c30ef020f16554c55d5359';  // sha1

function check_access($user, $pass, $users){
    $access_granted = false;
    foreach($users as $user_ => $crypted_pass){
        if ($user_ == $user and $crypted_pass == sha1($pass)){
            $access_granted = true;
            break;
        }
    }
    return $access_granted;
}
?>