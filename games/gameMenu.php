<?php
    session_start();
    //var_dump($_SESSION);
    include 'config/vars.php';


    // connect to db
    $error = '';
    $link = mysqli_connect($dbserver, $username, $password, $database);
    //@mysqli_select_db($database) ;#or die( "Unable to select database".$database);

?>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
    <meta name="viewport" content="user-scalable=no" />
    <title>Metadata Games</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <link href='http://fonts.googleapis.com/css?family="Comic Sans MS"' rel='stylesheet' type='text/css'>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <?php

    if(!isset($_SESSION['theme']) || isset($_REQUEST['theme']))
    {
        $_SESSION['theme'] = $_REQUEST['theme'];

        if ($_SESSION['theme'] == 'art') {
            $_SESSION['stylesheet'] = '<link rel="stylesheet" type ="text/css" href="css/art.css">';
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
    <style>
        html {
            font-size: 20px;
        }
        * {
            font-family: Arial;
            background-color: #333333;
            border-color: #333333;
        }
        h2 {
            margin-left: 20px;
            margin-right: 20px;
        }
        .menutext {
            color: white;
        }
        h1 {
            font-size: 100px;
            font-weight: bold;
            letter-spacing: normal;
            display: block;
        }
        span {
            letter-spacing: normal;

        }
        a:hover {
            text-decoration: none;
        }
        .heading {
            margin-bottom: 30px;
            max-height: 700px;
        }
        .heading-first {
            margin-bottom: 50px;
        }
        body {
            background-color: #333333;
        }
        div.container-fluid div.all {
            font-family: Arial;
        }
        div.central {
            background-color: #333333;
        }
        /* iPhone 6 in portrait  */
        @media only screen 
        and (min-device-width : 375px) 
        and (max-device-width : 667px) 
        and (orientation : portrait) { 
            html {
                padding-right: 1em;
                padding-left: 1em;
            }
            h2 {
                font-weight: bold;
                font-size: 2rem;
            }
            .menutext {
                font-size: 1.5rem;
            }
            input.btn {
                width: 30rem;
                height: 5rem;
                font-size: 3em;
            }
            input.form-control.form-inline {
                margin-bottom: 2rem;
                height: 4rem;
                font-size: 3em;
            }
            td.menu {
                box-sizing: border-box;
                padding-left: 2rem;
                padding-right: 2rem;
            }
            a {
                font-size: 1.3rem;
            }
            #footer a{
                color: ;
                font-size: 1.3rem;
                padding-bottom: 2rem;
            }
            #footer div.uoe-logo img{
                float: left;
                width: 5rem;
            }
            #footer div.luc-logo img{
                float: right;
                width: 4rem;
            }
        }

    </style>
</head>

<body>
<?php include_once("./../analyticstracking.php") ?>
<div class="all container-fluid">
<div class = "central">
    <div class = "heading">
        <div class="heading-first">
        <a href="gameMenu.php" title="Metadata Games">
            <img id="header" class="img-responsive center-block" src="images/crowdbanner.gif" alt="The University of Edinburgh Image Collections" width="800" height="80" border="0">
        </a>
        
        <?php
        if ($_SESSION['theme'] != 'roslin')
        {
        echo'<hr/>';
        echo '<h2>Teach Leevi what you know about the images and he will become smarter and smarter in recognizing images over time!</h2>';
        echo'<hr/>';
        }
        else{
            echo '<br/><br/><br/>';
        }
        ?>
        </div>
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
        echo'<form action="gameCrowdSourcing.php" method="post">
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
    else // it's normal metadata gameMenu.phpmysqli_select_db / crowd sourcing
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
                    <input class="btn btn-success active" type="submit" value="Tag" />
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
                    <input  class="btn btn-primary active" type="submit" value="Vote" />
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
                    <input  class="btn btn-danger active" type="submit" value="High Scores" />
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
              <td class="menu" colspan="2"><input class="form-control form-inline" type ="text" name = "image_id">
                 <input class="btn btn-success btn-xs active" type="submit" value="Tag Specific Image"/>
              </td>
            </tr>
         </table>
     </form>';

        /*
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
*/

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

    // close mysqli connection
    mysqli_close($link);

?>
<?php include 'footer.php';?>
<style type="text/css">
    #footer img {
        height: 80px;
        width: auto;
    }
    @media (max-width: 1000px) {
        div.footer-links {
            width: 70%;
        }
    }
    @media (max-width: 640px) {
        #footer img {
            display: none;
        }
        div.footer-links {
            float: none;
            width: 98%;
            margin: auto;
        }
    }
</style>
</div>
</body>

</html>
