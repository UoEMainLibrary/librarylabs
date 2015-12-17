<?php
    session_start();
    //var_dump($_SESSION);
    include 'config/vars.php';


    // connect to db
    $error = '';
    $link = mysql_connect($dbserver, $username, $password);
    @mysql_select_db($database) or die( "Unable to select database".$database);

?>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
    <title>Metadata Games</title>

    <?php

    if(!isset($_SESSION['theme']) || isset($_REQUEST['theme']))
    {
        $_SESSION['theme'] = $_REQUEST['theme'];

        if ($_SESSION['theme'] == 'art') {
            $_SESSION['stylesheet'] = '<link rel="stylesheet" type ="text/css" href="css/art.css">';
            $_SESSION['banner'] = "./images/artbanner.jpg";
            $_SESSION['game'] = 'A';
        }
        else if ($_SESSION['theme'] == 'photo')
        {
            $_SESSION['stylesheet'] = '<link rel="stylesheet" type ="text/css" href="css/photo.css">';
            $_SESSION['banner'] = "./images/photobanner.jpg";
            $_SESSION['game'] = 'P';
        }
        else if ($_SESSION['theme'] =='artAccessible')
        {
            $_SESSION['stylesheet'] = '<link rel="stylesheet" type ="text/css" href="css/artAccessible.css">';
            $_SESSION['banner'] = "./images/artbanner.jpg";
            $_SESSION['game'] = 'A';
        }
        else if ($_SESSION['theme'] =='roslin')
        {
            $_SESSION['stylesheet'] = '<link rel="stylesheet" type ="text/css" href="css/roslin.css">';
            $_SESSION['banner'] = "./images/rosbanner.jpg";
            $_SESSION['game'] = 'R';
        }
        else // classic and default
        {
            $_SESSION['stylesheet'] = '<link rel="stylesheet" type ="text/css" href="css/crowd.css">';
            $_SESSION['banner'] = "./images/crowdbanner.gif";
            $_SESSION['game'] = 'D';
        }
    }

    echo $_SESSION['stylesheet'];

    ?>
    <meta name="author" content="Library Digital Development" />
    <meta name="description" content=
            "Edinburgh University Library Crowd Sourcing" />
    <meta name="distribution" content="global" />
    <meta name="resource-type" content="document" />
    <meta http-equiv="Content-Type" content="text/html; charset=us-ascii" />
</head>

<body>
<?php include_once("./../analyticstracking.php") ?>
<div class="all">
<div class = "central">
    <div class = "heading">
        <a href="gameMenu.php" title="Metadata Games">
            <img src="<?php echo $_SESSION['banner']; ?>" alt="The University of Edinburgh Image Collections" width="800" height="80" border="0" />
        </a>
        <?php
        if ($_SESSION['theme'] != 'roslin')
        {
        echo'<hr/>';
        echo '<h2>HELP US DESCRIBE OUR IMAGES!</h2>';
        echo'<hr/>';
        }
        else{
            echo '<br/><br/><br/>';
        }
        ?>
    </div>
    <?php

    if ($_SESSION['theme'] == 'art' || $_SESSION['theme'] == 'artAccessible')
    {
        echo'<form action="gameCrowdSourcing.php" method="post">
        <p>Hello '.$_SESSION['first_name'].'. Nice to see you!</p>
        <br><br>
        <p class="menutext">You will now be taken into our tagging zone.</p>
        <br><br>
        <p class="menutext">You will tag 10 images, then vote on metadata for 10 previously tagged images.</p>
        <br><br>
        <p class="menutext">If you are ready, press the button!</p>
                  <table>
                   <tr>
              <td class = "menu" colspan="2">
                 <input type="submit" value="GO!"/>
              </td>
            </tr>
         </table>

        </form>
        <br><br>
        <form action="gameCrowdSourcingHighScores.php" method="post">
        <table>
            <tr>
                <td class = "menutext"  colspan="2">
                    See how you compare to your colleagues and fellow students!
                </td>
            </tr>
            <tr>
                <td  class = "menu" colspan="2">
                    <input type="submit" value="High Scores" />
                </td>
            </tr>
        </table>

    </form>';

        if ($_SESSION['status'] == 'C')
        {

            echo'
            <p class="menutext">As you are an admin, you can moderate tags. Press the button to do this.</p>
            <form action="assessData.php" method="post">
            <table>
               <tr>
                  <td class = "menu" colspan="2">
                     <input type="submit" value="Moderate Metadata" />
                  </td>
                </tr>
             </table>

         </form>';
        }
    }
    else if ($_SESSION['theme'] == 'photo')
    {
        echo '<form action="gameCrowdSourcing.php" method="post">
        <p> Submit research for a random photo, or for a specific item
        <table>
            <tr>
              <td class = "menu" colspan="2">
                 <input type="submit" value="Random" style="width:320px;" />
              </td>
            </tr>

        <table>
            <tr>
                <td class = "menutext"  colspan="2">
                    If you want to add data to a specific record that you know the ID for, enter it here.
                </td>
            </tr>
           <tr>
              <td class = "menu" colspan="2"><input type ="text" name = "image_id">
                 <input type="submit" value="Tag Specific Image" style="width:320px;" />
              </td>
            </tr>
         </table>

        </form>';
    }
    else if ($_SESSION['theme'] == 'roslin')
        //We need to break into a new file for Roslin- gameCrowdSourcing.php is just getting too complicated.
    {
        echo'<form action="gameCrowdSourcingDolly.php" method="post">
        <p>Hello '.$_SESSION['first_name'].'. Nice to see you!</p>
        <p class="menutext">You will now be taken into our tagging zone.</p>
        <p class="menutext">You&#39;ll tag 10 images, then vote on metadata for 10 previously tagged images.</p>
        <p class="menutext">Can you find Dolly The Sheep???</p>
        <p class="menutext">If you&#39;re ready, press the button!</p>
                  <table>
                   <tr>
              <td class = "menu" colspan="2">
                 <input type="submit" value="GO!"/>
              </td>
            </tr>
         </table>

        </form>

        <form action="gameCrowdSourcingHighScoresDolly.php" method="post">
        <table>
            <tr>
                <td class = "menutext"  colspan="2">
                    See how you compare to your colleagues and fellow students!
                </td>
            </tr>
            <tr>
                <td  class = "menu" colspan="2">
                    <input type="submit" value="High Scores" />
                </td>
            </tr>
        </table>

    </form>';

        if ($_SESSION['status'] == 'C')
        {

            echo'
            <p class="menutext">As you are an admin, you can moderate tags. Press the button to do this.</p>
            <form action="assessData.php" method="post">
            <table>
               <tr>
                  <td class = "menu" colspan="2">
                     <input type="submit" value="Moderate Metadata" />
                  </td>
                </tr>
             </table>

         </form>';
        }
    }
    else // it's normal metadata games / crowd sourcing
    {
    echo '<form action="gameCrowdSourcing.php" method="post">
        <table>
            <tr>
                <td class = "menutext" colspan="2">
                    Describe images from our collections by tagging them with things you see!
                </td>
            </tr>
            <tr>
                <td  class = "menu" colspan="2">
                    <input type="submit" value="Tag" />
                </td>
            </tr>
        </table>
    </form>

    <form action="gameCrowdSourcingApproval.php" method="post">
        <table>
            <tr>
                <td class="menutext"  colspan="2">
                    Vote on the tags that people have ascribed to images already - good, bad or impossible to say?
                </td>
            </tr>
            <tr>
                <td class="menu" colspan="2">
                    <input type="submit" value="Vote" />
                </td>
            </tr>
        </table>

    </form>

    <form action="gameCrowdSourcingHighScores.php" method="post">
        <table>
            <tr>
                <td class="menutext" colspan="2">
                    See how you compare to your colleagues and fellow students!
                </td>
            </tr>
            <tr>
                <td  class="menu" colspan="2">
                    <input type="submit" value="High Scores" />
                </td>
            </tr>
        </table>
    </form>

    <form action="gameCrowdSourcing.php" method="post">
        <table>
            <tr>
                <td class="menutext" colspan="2">
                    If you want to add data to a specific record that you know the ID for, enter it here.
                </td>
            </tr>
           <tr>
              <td class="menu" colspan="2"><input type ="text" name = "image_id">
                 <input type="submit" value="Tag Specific Image"/>
              </td>
            </tr>
         </table>
     </form>';

     if ($_SESSION['status'] == 'C')
     {

     echo'
     <form action="assessData.php" method="post">
        <table>
           <tr>
              <td class = "menu" colspan="2">
                 <input type="submit" value="Moderate Metadata"" />
              </td>
            </tr>
         </table>

     </form>';
     }


    }

  ?>
</div>

<div>

    <div>
        <p>
            <?php
            if ($_SESSION['theme'] == 'art')
            {
                echo'<a href = "gameMenu.php?theme=artAccessible">Toggle accessible view</a></p>';
            }
            else if ($_SESSION['theme'] == 'artAccessible')
            {
                echo'<a href = "gameMenu.php?theme=art">Toggle accessible view</a></p>';
            }

            if ($_SESSION['theme'] != 'roslin')
            {
                echo'<hr/>';
            }
            ?>


        <p><?php session_write_close(); ?><a href="index.php">Back To Menu</a></p>
    </div>
</div>

<?php

    // close mysql connection
    mysql_close($link);

?>
<?php include 'footer.php';?>
</div>
</body>

</html>
