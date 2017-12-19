<?php
//session_start();
//var_dump($_SESSION);
include '../games/config/vars.php';


// connect to db
$error = '';
$link = mysqli_connect($dbserver, $username, $password, $database);
//@mysqli_select_db($database) ;#or die( "Unable to select database".$database);

?>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
    <meta name="viewport" content="user-scalable=no" />
    <title>Special Collections UV Manifest Work</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <link href='http://fonts.googleapis.com/css?family="Comic Sans MS"' rel='stylesheet' type='text/css'>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <?php

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
        h1, h2, textarea, input, h3, span{
            background-color:#33cccc;
        }

        td{
            padding:3px;
            vertical-align: middle;
        }
        .lightheading{
            width:220px;
            height:220px;
        }
        img{
            vertical-align:middle;
            text-align:center;
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
<?php include_once("./../analyticstracking.php")?>

<?php
    $image_block = '';
    if ($_POST['imageblock'] !== '')
    {
        $image_block = $_POST['imageblock'];
    }
?>

    <div class="all container-fluid">
    <h1>Manifest Builder Prototype</h1>
    <h2>Put some image names into the box, one per row</h2>
    <div class = "box">
         <form name="form" method="post" action="manifestbuild.php">
              <textarea name="imageblock" rows="8" cols = "10"></textarea>
                <br>
              <input type="submit" name="button" value="Submit"/>
         </form>
    </div>

<?php

    }
    if ($image_block !== '')
{
?>

        <div class="all container-fluid">
    <h1>Manifest Builder Prototype</h1>
    <h2>Here comes your manifest</h2>

    <?php
        $image_array = explode ("\n", $image_block);

        foreach ($image_array as $image)
        {
            echo "<h2>".$image."</h2>";
        }
        echo '<h2>POST AFORE PURGE'.$_POST['imageblock'].'</h2>';
        unset($_POST["imageblock"]);
        echo '<h2>POST AFTER PURGE'.$_POST['imageblock'].'</h2>';
    }

?>


</div>
</body>

</html>
