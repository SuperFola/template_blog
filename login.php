<!DOCTYPE html>

<HTML>
    <?php include("head.php"); ?>
    <body>
        <?php include('header.php'); ?>
        <div class="container">
            <div class="connection">
                <form method="post" class="login" action="loginconfirm.php">
                    <div class="form-group">
                        <label for="user">Utilisateur :</label>
                        <input type="text" class="form-control" id="user" name="user" placeholder="Nom d'utilisateur">
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" class="form-control" id="pwd" name="pwd" placeholder="Mot de passe">
                    </div>
                    <input type="submit" class="btn btn-primary" value="Connexion">
                </form>
            </div>
            <hr />
            <center>
                <a href="signup.php">S'inscrire</a>
            </center>
            <?php include('footer.php'); ?>
        </div>
    </body>
</HTML>