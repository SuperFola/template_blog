<?php
$registred_users = array();
$registred_users['jeanbaptistecaplan'] = 'f20a04456a8dbeb8a91a4d76806b30ab35e3e7ee';  // sha1

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