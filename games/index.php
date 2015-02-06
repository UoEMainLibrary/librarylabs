<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
    <meta name="generator" content="HTML Tidy for Linux/x86 (vers 11 February 2007), see www.w3.org" />
    <title>Crowd Sourcing Game</title>
    <link rel="stylesheet" type ="text/css" href="css/diustyles.css">
    <meta name="author" content="Library Online Editor" />
    <meta name="description" content=
            "Edinburgh University DIU Crowd Sourcing" />
    <meta name="distribution" content="global" />
    <meta name="resource-type" content="document" />
    <meta http-equiv="Content-Type" content="text/html; charset=us-ascii" />
</head>

<body>
<div class = "central">
    <div class = "heading">
        <a href="gameMenu.php" title="DIU Games Home Link">
            <img src="images/header4.jpg" alt="The University of Edinburgh Image Collections" width="754" height="65" border="0" />
        </a>
        <hr/>
        <h2>LIBRARY LABS...</h2>
        <hr/>
    </div>
    <div class = "box"></div>
    <?php
        session_start();
        session_unset();
        session_destroy();
        session_write_close();
        setcookie(session_name(),'',0,'/');
        session_regenerate_id(true);
    ?>
        <ul>
            <li><a href = "gameMenu.php?theme=art"> Innovative Learning Week: Tag Art Images</a></li>
            <li><a href = "gameMenu.php">Classic Retro Image Tagging</a></li>
            <li><a href = "gameMenu.php?theme=photo">Photography Research Tagging</a></li>
        </ul>

    </div>


</div>
</body>

</html>
