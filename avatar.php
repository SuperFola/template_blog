<?php
if(isset($_GET['i']) AND $_GET['i'] > 0 AND $_GET['i'] <= 12) {
    $image = intval($_GET['i']);
    
    $fichier = fopen('avatars/image.txt', 'w');
    fseek($fichier, 0); 
    fwrite($fichier, $image);
    fclose($fichier);
    
    $count = fopen('avatars/count_change.txt', 'r+');
    $changes = fgets($count);
    $changes++;
    fseek($count, 0);
    fwrite($count, $changes);
    fclose($count);
    
    header("Location: change_avatar.php");
}else{
    $fichier = fopen('avatars/image.txt', 'r'); 
    $image = fgets($fichier); 
    header('Location: avatars/avatar-' . $image . '.jpg'); 
    fclose($fichier);
}
?>