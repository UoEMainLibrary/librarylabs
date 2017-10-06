<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>LDD Utilities Menu</title>

        <!-- Bootstrap -->
        <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/pure.css">
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
<body>
    <div = "header">
    <br>
    <img src="images/lddutilities.jpg">
    <h1>MD Games Moderator</h1>

    <?php

        //Scott Renton, September 2017
        //Harvest LUNA OAI and insert new rows into the metadata games database.

        include 'config/vars.php';
        ini_set('max_execution_time', 400);
        $error = '';
        echo 'PARMS'.$dbserver.$username.$password.$database;
        $link = mysqli_connect($dbserver, $username, $password, $database);
        @mysqli_select_db($database) ;
        $naughtyfile ='naughtyWords.txt';


        $checksql = "select value_text, id from orders.CROWD where status = 'P';";
        $checkresult = mysqli_query($link,$checksql) or die("A MySQL error has occurred.<br />Your Query: " . $checkresultsql . "<br /> Error: (" . mysqli_errno() . ") " . mysqli_error());
        while ($row = $checkresult->fetch_assoc())
        {
            $tag = $row['value_text'];
            $id = $row['id'];

            $file_handle = fopen($naughtyfile, "r") or die ("<p>Sorry, I can't open naughty file</p>");

            $foundit = false;
            while (!feof($file_handle) and $foundit == false)
            {
                $line = fgets($file_handle);
                if (trim(strtoupper($tag)) == trim(strtoupper($line)))
                {
                    $foundit = true;
                    $status = 'R';
                }
            }
            if ($foundit == false)
            {
                if (is_numeric($tag))
                {
                    $status = 'R';
                }
                $status = 'M';
            }

            $updatesql = "update orders.CROWD set status ='".$status."', date_created = CURRENT_TIMESTAMP  where id = '".$id."';";
            $updateresult = mysqli_query($link,$updatesql) or die("A MySQL error has occurred.<br />Your Query: " . $updatesql . "<br /> Error: (" . mysqli_errno() . ") " . mysqli_error());

            echo 'updated '.$id.' '.$tag.' to '.$status."<br>";
        }
    ?>
    </body>
</html>
