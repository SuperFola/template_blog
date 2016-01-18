<!DOCTYPE html>

<HTML>
    <?php include("head.php"); ?>
    <body>
        <?php include('header.php'); ?>
        <div class="container">
            <div class="connection">
                <form method="post" action="loginconfirm.php">
                    Utilisateur : <input type="text" name="user" /><br />
                    Mot de passe : <input type="password" name="pwd" /><br /><br />
                    <button type="submit">Connexion</button>
                </form>
            </div>
            <?php include('footer.php'); ?>
        </div>
    </body>
</HTML>