<?php
$registred_users = array();
$registred_users['folaefolc'] = '827cff9ded58162cfbf6692f2ae059af9595ea0f';  // sha1

function check_access($user, $pass, $users){
    $access_granted = false;
    foreach($users as $user_ => $crypted_pass){
        if ($user_ == $user and ($crypted_pass == sha1($pass) or $crypted_pass == crypt($pass))){
            $access_granted = true;
            break;
        }
    }
    return $access_granted;
}
?>