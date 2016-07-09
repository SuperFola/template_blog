<?php
    session_start();
?>
<!DOCTYPE HTML>

<HTML>
    <?php include('head.php'); ?>
    <body>
        <?php include('header.php'); ?>
        <div class="container">
            <?php
                $userManager = new UserManager();
                if (isset($_GET['id']))
                    $user = $userManager->findUser($_GET['id']);
                else
                    $user = $userManager->findUserByPseudo($_SESSION['pseudo']);
                if (!$user)
                    header("Location: error.php?error=404");
            ?>
            <h3 style="display: inline;"><?php echo $user->getPseudo() ?></h3>&nbsp;&nbsp;&nbsp;
            <?php echo '<img src="http://identicon.org?t=' . $user->getPseudo() . '&s=50" class="img-responsive" style="display: inline;">'; ?>
            <br />
            <br />
            <?php if ($user->getPseudo() == $_SESSION['pseudo']) { ?>
            Email : <?php echo $user->getEmail() ?>
            <br />
            <?php } ?>
            Rôle : <?php echo $user->getRole() ?>
            <br />
            Dernière connexion : <?php echo $user->getLastLogin() ?>
            <br />
            <?php if ($user->getPseudo() == $_SESSION['pseudo']) { ?>
            <br />
            <a href="logout.php" class="btn btn-danger">Déconnexion</a>
            <br />
            <?php } ?>
            <?php include('footer.php'); ?>
        </div>
    </body>
</html>