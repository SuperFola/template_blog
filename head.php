    <head>
        <?php
            include(__DIR__ . '/private/autoloader.php');
            
            $cm = new ConfigManager();
            $title = $cm->getBlogTitle();
            $slogan = $cm->getBlogSlogan();
        ?>
        <title><?php echo $title . ' - ' . $slogan; ?></title>
        <meta charset="utf-8" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="Content-Language" content="fr-FR" />
        <meta name="robots" content="all" />
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no" />
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <!-- Website Style -->
        <link rel="stylesheet" href="css/style.css">
        <!-- Website JS -->
        <script type="text/javascript" src="scripts/spoiler.js"></script>
    </head>