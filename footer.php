            <footer>
                <?php
                    $cm = new ConfigManager();
                    $footer = $cm->getBlogFooter();
                ?>
                <hr>
                <div style="text-align: justify; margin: 0 auto; width: 30em;">
                    <?php
                        echo $footer;
                    ?>
                    <br />
                    <a href="cgu.php">CGU</a>&nbsp;&nbsp;-&nbsp;&nbsp;<a href="members.php">Liste des membres</a>
                    <br />
                    <br />
                    <a href="https://hostux.fr/" style="text-decoration: none; width: auto;"><img src="https://hostux.fr/static/images/bannieres/horizontale.png" alt="Un site hébergé par Hostux.fr, hébergeur de services Internet à prix libre"></a>
                </div>
                <br />
            </footer>