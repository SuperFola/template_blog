<?php
$hote = "mysql-yaalval.alwaysdata.net";
$user = "yaalval";
$pass = "HaZB#U!g:#";
$table = "yaalval_main";

try
{
    $bddmsql = new PDO('mysql:host=' . $hote . ';dbname=' . $table . ';charset=utf8', $user, $pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}catch (Exception $e){
    die('Erreur : ' . $e->getMessage());
}
?>