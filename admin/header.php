        <div class="jumbotron">
            <div class="login-btn col-md-12">
                <?php
                    if (!isset($_SESSION) or !isset($_SESSION['pseudo'])){
                 ?>
                <a class="btn btn-primary btn-xs" href="../login.php">Connexion</a>
                <?php
                    } else {
                        if ($_SESSION['role'] == 'ADMINISTRATEUR'){
                            echo "<a class=\"btn btn-primary btn-xs\" href=\"index.php\">" . $_SESSION['pseudo'] . "</a>";
                        } else {
                            echo "<a class=\"btn btn-primary btn-xs\">" . $_SESSION['pseudo'] . "</a>";
                        }
                    }
                ?>
            </div>
            <?php
                $cm = new ConfigManager();
                $title = $cm->getBlogTitle();
                $slogan = $cm->getBlogSlogan();
            ?>
            <h1><?php echo $title; ?></h1>
            <h3><?php echo $slogan; ?></h3>
            <h5>- Administration -</h5>
        </div>