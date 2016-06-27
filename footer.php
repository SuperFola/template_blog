            <footer>
                <?php
                    $cm = new ConfigManager();
                    $footer = $cm->getBlogFooter();
                ?>
                <hr>
                <center style="text-align: justify;">
                    <?php
                        echo $footer;
                    ?>
                    <br />
                    <a href="cgu.php">CGU</a>
                    <br />
                    <br />
                    <a href="https://hostux.fr/" style="text-decoration: none; width: auto;"><img src="https://hostux.fr/static/images/bannieres/horizontale.png" alt="Un site hébergé par Hostux.fr, hébergeur de services Internet à prix libre"></a>
                </center>
                <br />
            </footer>