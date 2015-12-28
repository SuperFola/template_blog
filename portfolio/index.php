<!DOCTYPE html>

<HTML>
    <head>
        <title>Mon portfolio</title>
        <link rel="stylesheet" type="text/css" href="../css/design.css" />
        <meta charset="utf-8" />
        
        <script src="../scripts/spoiler.js"></script>
        <script src="../scripts/expand.js"></script>
        <script src="http://strapdownjs.com/v/0.2/strapdown.js"></script>
    </head>
    <body>
        <div class="start">
            <h1>Titre</h1>
            <h3>Slogan</h3>
            <?php include('../public/search.php'); ?>
        </div>
        
        <div class="main">
            Sommaire <br />
            <ol>
                <li><a href="#cv">Curriculum Vitae</a></li>
                <li><a href="#experience">Mon expérience</a></li>
                <li><a href="#objectifs">Mes objectifs</a></li>
                <li><a href="#exemples">Exemples de réalisations personnelles</a></li>
            </ol>
            
            <br />
            <br />
            
            <a name="cv" onclick="s('cv')">Curriculum Vitae</a> <br />
            <div class="spoiler" id="cv">
                Mes coordonées : <br />
                <ul>
                    <li></li>
                </ul>
                Mon parcours scolaire : <br />
                <ul>
                    <li></li>
                </ul>
                Mes diplômes : <br />
                <ul>
                    <li></li>
                </ul>
                Mon expérience professionnelle : <br />
            </div>
            
            <br />
            
            <a name="experience" onclick="s('experience')">Mon expérience</a> <br />
            <div class="spoiler" id="experience">
                J'ai pu acquérir différentes compétences au cours de mon apprentissage en autodidacte, dont : <br />
                <ul>
                    <li></li>
                </ul>
            </div>
            
            <br />
            
            <a name="objectifs" onclick="s('objectifs')">Mes objectifs</a> <br />
            <div class="spoiler" id="objectifs">
                Mes objectifs à court terme : <br />
                <ul>
                    <li></li>
                </ul>
                Mes objectifs à long terme : <br />
                <ul>
                    <li></li>
                </ul>
            </div>
            
            <br />
            
            <a name="exemples" onclick="s('exemples')">Exemples de réalisations</a> <br />
            <div class="spoiler" id="exemples">
                Plusieurs projets que j'ai pu mener à terme : <br />
                <ul>
                    <li></li>
                </ul>
            </div>
            
            <br />
            
            <a href="../index.php">Retour à l'accueil</a>
        </div>
        <!-- NE RIEN ECRIRE APRES CETTE BALISE -->
    </body>
</HTML>