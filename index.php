<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Homepage for Library Labs">
    <meta name="author" content="University of Edinburgh, Library Digital Development Team">
    <link rel="shortcut icon" href="./favicon.ico">

    <!-- Bootstrap -->
    <link href="./assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
    <title>Welcome to Library Labs</title>
</head>
<body>
<?php include_once("./analyticstracking.php") ?>
<div class="container">
    <header>
        <div class="container-fluid">
            <div class="row header-row">
                <div class="header-image">
                    <img src="./css/images/librarylabsheader.png" class="img-responsive">
                </div>
            </div>
        </div>
        <!-- Static navbar -->
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="./">Home</a></li>
                        <li><a href="./about">About</a></li>
                        <li class="dropdown">
                            <a href="./games" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Metadata Games <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="./games/gameMenu.php?theme=art">Tag It! Find It!</a></li>
                                <li><a href="./games/gameMenu.php?theme=classic">Class metadata games</a></li>
                                <li><a href="./games/gameMenu.php?theme=photo">Research Tagging</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="./contact">Contact</span></a></li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div><!--/.container-fluid -->
        </nav>

    </header>


    <div class="row">
        <div class="col-lg-4">
            <div class="link-box box-left home-box">
                <a href="http://libraryblogs.is.ed.ac.uk/librarylabs">
                    <img title="Library Labs Blog" src="./css/images/BlogTile.png">
                </a>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="link-box box-middle home-box">
                <a href="./games">
                    <img title="Metadata Games" src="./css/images/MetadataGamesTile.png">
                </a>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="link-box box-right home-box">
                <a href="./about">
                    <img title="About Library Labs" src="./css/images/AboutTile.png">
                </a>
            </div>
        </div>
    </div>

    <footer id="footer">
        <div class="container">
            <div class="uoe-logo">
                <a target="_blank" href="http://www.ed.ac.uk/">
                    <img title="The University of Edinburgh" src="./css/images/UoELogo.png">
                </a>
            </div>
            <div class="footer-links">
                <ul>
                    <li>
                        <a href="http://www.ed.ac.uk.ezproxy.is.ed.ac.uk/about/website/website&#45;terms&#45;conditions">Terms &amp; conditions </a>
                    </li>

                    <li>
                        <a href="http://www.ed.ac.uk.ezproxy.is.ed.ac.uk/about/website/privacy">Privacy &amp; cookies </a></li>

                    <li>
                        <a href="http://www.ed.ac.uk.ezproxy.is.ed.ac.uk/about/website/accessibility">Website accessibility </a>
                    </li>

                    <li>
                        <a href="http://www.ed.ac.uk.ezproxy.is.ed.ac.uk/about/website/freedom&#45;information">Freedom of Information Publication Scheme </a>
                    </li>
                </ul>
            </div>
            <div class="luc-logo">
                <a target="_blank" href="http://libraryblogs.is.ed.ac.uk/">
                    <img title="Library and University Collections Blog" src="./css/images/L&UCLogo.png">
                </a>
            </div>
        </div>
    </footer>

</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="./assets/bootstrap/js/bootstrap.min.js"></script>

</body>
</html>
