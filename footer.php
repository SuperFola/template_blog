            <footer>
                <?php
                    $cm = new ConfigManager();
                    $footer = $cm->getBlogFooter();
                ?>
                <hr>
                <center>
                    <?php
                        echo $footer;
                    ?>
                    <br />
                    <br />
                    <a href="https://hostux.fr/" style="text-decoration: none;"><img src="https://hostux.fr/static/images/bannieres/horizontale.png" alt="Un site hébergé par Hostux.fr, hébergeur de services Internet à prix libre"></a>
                </center>
                <br />
            </footer>