<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
    <meta name="generator" content="HTML Tidy for Linux/x86 (vers 11 February 2007), see www.w3.org" />
    <title>Crowd Sourcing Game</title>

    <?php
    if ($_REQUEST['theme'] == 'art') {
        echo '<link rel="stylesheet" type ="text/css" href="css/art.css">';
        $banner = "images/artbanner.jpg";
    } elseif ($_REQUEST['theme'] == 'photo') {
        echo '<link rel="stylesheet" type ="text/css" href="css/photo.css">';
        $banner = "images/photobanner.jpg";
    } elseif ($_REQUEST['theme'] =='artAccessible')
    {
        echo '<link rel="stylesheet" type ="text/css" href="css/artAccessible.css">';
        $banner = "images/artbanner.jpg";
    }else {
        echo '<link rel="stylesheet" type ="text/css" href="css/crowd.css">';
        $banner = "images/crowdbanner.gif";
    }

    ?>
    <meta name="author" content="Library Digital Development" />
    <meta name="description" content=
    "Edinburgh University Library Crowd Sourcing" />
    <meta name="distribution" content="global" />
    <meta name="resource-type" content="document" />
    <meta http-equiv="Content-Type" content="text/html; charset=us-ascii" />
</head>

<body>
<?php include_once("analyticstracking.php") ?>
<div class = "central">
    <div class = "heading">
        <a href="gameMenu.php" title="DIU Games Home Link">
            <img src="<?php echo $banner; ?>" alt="The University of Edinburgh Image Collections" width="800" height="80" border="0" />
        </a>
        <hr />
	    <h2>REGISTRATION</h2>
        <hr/>
     </div>
            <div id = "reg_form">
                <form action="gameFinish.php?theme=<?php echo $_REQUEST['theme'];?>" method="POST">
                <table>
                    <tr>
                        <td>* Email:</td>
                        <td><input type="text" name="email" /></td>
                    </tr>
                    <tr>
                        <td>* UUN: </td>
                        <td><input type="text" name="uun" /></td>
                    </tr>
                    <tr>
                        <td>* First Name:</td>
                        <td> <input type="text" name="first_name" /></td>
                    </tr>
                    <tr>
                        <td>* Surname: </td>
                        <td><input type="text" name="surname" /></td>
                    </tr>
                    <tr>
                        <td>* User Type <br />
                            (Student, type U; Staff, type S): </td>
                        <td><br /><input type="text" name="user_type" value = "U" /></td>
                    </tr>
                    <tr>
                        <td>* Password: </td>
                        <td><input type="password" name="password" /></td>
                    </tr>
                    <tr>
                        <td>* Re-type Password:</td>
                        <td> <input type="password" name="re-password" /></td>
                    </tr>
                    <tr>
                        <td>* Mandatory <field></field></td>
                        <td><input type="submit"value ="Register" /></td>
                    </tr>
                </table>
				</form>
            </div>
            <?php include 'footer.php';?>
        </div>
    </body>
</html>
