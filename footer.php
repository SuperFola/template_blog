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
                </center>
                <br />
            </footer>