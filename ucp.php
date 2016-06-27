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
                $user = $userManager->findUserByPseudo($_SESSION["pseudo"]);
                if (!$user)
                    header("Location: error.php?error=404");
            ?>
            <h2><?php $user->getPseudo() ?></h2>
            Email : <?php $user->getEmail() ?>
            <br />
            Rôle : <?php $user->getRole() ?>
            <br />
            Dernière connexion : <?php $user->getLastLogin() ?>
            <br />
            <br />
            <a href="logout.php" class="btn btn-danger">Déconnexion</a>
            
        </div>
    </body>
</html>