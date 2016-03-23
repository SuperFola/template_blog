        <div class="jumbotron">
            <div class="login-btn col-md-12">
                <?php
                    if (!isset($_SESSION) or !isset($_SESSION['pseudo'])){
                 ?>
                <a class="btn btn-primary btn-xs" href="login.php">Connexion</a>
                <?php
                    } else {
                        if (in_array($_SESSION['role'], array('ADMINISTRATEUR', 'MODERATEUR', 'AUTEUR'))){
                            echo "<a class=\"btn btn-primary btn-xs\" href=\"admin/index.php\">" . $_SESSION['pseudo'] . "</a>";
                        } else {
                            echo "<a class=\"btn btn-primary btn-xs\">" . $_SESSION['pseudo'] . "</a>";
                        }
                        
                        echo " <a class=\"btn btn-danger btn-xs\" href=\"logout.php\">Déconnexion</a>";
                    }
                ?>
            </div>
            <?php
                $cm = new ConfigManager();
                $title = $cm->getBlogTitle();
                $slogan = $cm->getBlogSlogan();
            ?>
            <h1><a href="index.php" style="text-decoration: none;"><?php echo $title; ?></a></h1>
            <h3><?php echo $slogan; ?></h3>
        </div>