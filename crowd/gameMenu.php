<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
    <meta name="generator" content="HTML Tidy for Linux/x86 (vers 11 February 2007), see www.w3.org" />
    <title>Crowd Sourcing Game</title>
    <link rel="stylesheet" type ="text/css" href="css/crowd.css">
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
            <img src="images/crowdbanner.gif" alt="The University of Edinburgh Image Collections" width="800" height="80" border="0" />
        </a>
        <hr/>
        <h2>HELP US DESCRIBE OUR IMAGES!</h2>
        <hr/>
    </div>
    <?php
        session_start();
    include 'config/vars.php';
    $error = '';

    $link = mysql_connect($dbserver, $username, $password);
    @mysql_select_db($database) or die( "Unable to select database".$database);


    if (!$_POST['logging_in'] && !isset($_SESSION['uun']))
    {
        echo' <form action="gameMenu.php" method="post">
                <table>
                    <tr>
                        <th>UUN:</th>
                        <td>
                            <input type="text" name="uun" /><br />
                        </td>
                    </tr>
                    <tr>
                        <th>Password:</th>
                        <td><input type="password" name="password" /><br />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="submit" value="Log In" />
                        </td>
                    </tr>
                </table>
                <input type="hidden" name="logging_in" value="logging_in" />
            </form>
            <p>Not registered? <a href="gameregistration.html"><h4>REGISTER HERE</h4></a></p>
';
    }
    else {


        if($_POST['logging_in'])
        {

            //Check for missing data and guide user accordingly
            if (empty ($_POST['uun']))
            {
                if (empty ($_SESSION['uun']))
                {
                    die('<div class = "die">Please enter your name!<br><a href="game.html">Back</a></div>');
                }
                else
                {
                    $uun = $_SESSION['uun'];
                }
            }
            else
            {
                $_SESSION['uun'] = $_POST['uun'];
                $uun = $_SESSION['uun'];
            }

            $pwd = $_REQUEST['password'];
            $_SESSION['pwd'] = $pwd;

            if (empty ($_POST['password']))
            {
                if (empty ($_SESSION['uun']))
                {
                    die('<div class = "die">Please enter a password<br><a href="game.html">Back</a></div>');
                }
                else
                {
                    $pwd= $_SESSION['password'];
                }
            }
            else
            {
                $_SESSION['password'] = $_POST['password'];
                $pwd = $_SESSION['password'];
            }

            //Check user exists
            $sql="SELECT * FROM orders.USER WHERE uun ='".$uun."';";

            $result=mysql_query($sql,$link) or die( "A MySQL error has occurred.<br />Your Query: " . $sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
            $numrows = mysql_num_rows($result);
            $row=mysql_fetch_array($result);

            if ($row == null)
            {
                die('<div class = "die">Invalid username<br><a href="game.html">Back</a></div>');
            }
            else
            {
                //Get user details
                //$sql="SELECT uun, first_name, surname, email, status, points, password FROM orders.USER WHERE uun ='".$uun."';";
                //$result=mysql_query($sql) or die( "A MySQL error has occurred.<br />Your Query: " . $sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());;
                //$row=mysql_fetch_array($result);

                $hash = $row['password'];

                if (crypt($pwd, $hash) == $hash)
                {
                    $uun = $_SESSION['uun'] = $row["uun"];
                    $first_name = $_SESSION['first_name'] = $row["first_name"];
                    $surname = $_SESSION['surname'] = $row["surname"];
                    $email = $_SESSION['email'] = $row["email"];
                    $status = $_SESSION['status'] = $row["status"];
                    $points = $_SESSION['points'] = $row["points"];

                }
                else
                {

                    die('<div class = "die">invalid password<br><a href="game.html">Back</div>');
                }
            }
        }

    ?>
    <form action="gameCrowdSourcing.php" method="post">
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
        <input type="hidden" name="logging_in" value="logging_in" />
    </form>

    <form action="gameCrowdSourcingApproval.php" method="post">
        <table>
            <tr>
                <td class = "menutext"  colspan="2">
                    Vote on the tags that people have ascribed to images already- good, bad or impossible to say?
                </td>
            </tr>
            <tr>
                <td class = "menu" colspan="2">
                    <input type="submit" value="Vote" />
                </td>
            </tr>
        </table>
        <input type="hidden" name="logging_in" value="logging_in" />
    </form>

    <form action="gameCrowdSourcingHighScores.php" method="post">
        <table>
            <tr>
                <td class = "menutext"  colspan="2">
                    See how you compare to your colleagues and fellow students!
                </td>
            </tr>
            <tr>
                <td  class = "menu" colspan="2">
                    <input type="submit" value="Hi Scores" />
                </td>
            </tr>
        </table>
        <input type="hidden" name="logging_in" value="logging_in" />
    </form>

    <form action="gameCrowdSourcing.php" method="post">
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
         <input type="hidden" name="logging_in" value="logging_in" />

     </form>
<?php
    }

  ?>
</div>
</body>

</html>
