<?php

include 'config/vars.php';
session_start();

?>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <title>Metadata Games</title>
    <?php echo $_SESSION['stylesheet']; ?>
    <meta name="author" content="Library Digital Development" />
    <meta name="description" content="Edinburgh University Library Crowd Sourcing"/>
    <meta name="distribution" content="global"/>
    <meta name="resource-type" content="document"/>
    <meta http-equiv="Content-Type" content="text/html; charset=us-ascii"/>
</head>

<body>
<?php include_once("./../analyticstracking.php") ?>
<div class="central">
<div class="heading">
    <a href="gameMenu.php" title="Metadata Games Menu">
        <img src="<?php echo $_SESSION['banner']; ?>" alt="The University of Edinburgh Image Collections"
             width="800" height="80" border="0"/>
    </a>
    <hr/>
    <h2>LICENSING AND COPYRIGHT INFORMATION</h2>
    <hr/>
</div>

    <p class="menutext">We endeavour to undertake reasonable efforts to make sure that the reproduction of content on this site is done with the consent of copyright owners. <br />However, due to the size of the collection, this has not been possible in all cases and some clearances are still awaiting action. <br />All material that is reproduced here is only done so at low resolution and for the purposes of teaching and research.</p>

    <p class="menutext">If you are a rights holder and wish to request the removal of a work to which you hold the rights (or is not covered by a copyright exception under the UK Copyright, Designs and Patents Act 1988), <br />please <a href="mailto:lddt@mlist.is.ed.ac.uk" class="menutext">contact us</a> in writing giving us your contact details and a description of the image in question.</p>

<?php include 'footer.php';?>
</div>
<!-- div central -->
</body>
</html>
