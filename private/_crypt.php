<?php
    if (isset($_GET['pass']) and $_GET['pass'] != ''){
        echo 'SHA1    : ' . sha1($_GET['pass']) . '<br />';
        echo 'CRYPT() : ' . crypt($_GET['pass']);
    }else{
        ?>
        <form method='get' action='_crypt.php'>
            Password : <input type="text" name="pass" /><br />
            <button type="submit">Crypt it !</button>
        </form>
        <?php
    }
?>