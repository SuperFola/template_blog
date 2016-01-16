            <footer>
                <?php
                    include("private/configmanager.php");
                    $cm = new ConfigManager();
                    $footer = $cm->getBlogFooter();
                ?>
                <hr>
                <?php
                    echo $footer;
                ?>
            </footer>