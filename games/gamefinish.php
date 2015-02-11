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
        <a href="gameMenu.php" title="Metadata Games">
            <img src="<?php echo $banner; ?>" alt="The University of Edinburgh Image Collections" width="800" height="80" border="0" />
        </a>
        <hr />
        <h2>REGISTRATION</h2>
        <hr/>
    </div>
				<?php
					/* SR 26/9/12
					Check input registration info. If ok, insert to database */
					
					//Connect To Database
					include 'config/vars.php';
					$error = '';

					$link = mysql_connect($dbserver, $username, $password);
					@mysql_select_db($database) or die( "Unable to select database");

					//Declare Variables
					$uun = $_POST['uun'];
					$email = $_POST['email'];
					$email1 = "@";
					$email_check = strpos($email,$email1);
					$pwd = $_POST['password'];
					$re_password = $_POST['re-password'];
					$surname = $_POST['surname'];
					$first_name = $_POST['first_name'];
					$telephone = $_POST['telephone'];
					$address_line_1 = $_POST['address_line_1'];
					$address_line_2 = $_POST['address_line_2'];
					$country = $_POST['country'];
					$post_code = $_POST['post_code'];
					$town = $_POST['town'];
					$user_type = $_POST['user_type'];

					//Check To See If All Information Is Correct
					if($uun == "")
					{
					die('<div class = "die"><p>You did not enter a uun</p><a href="gameRegistration.php?theme='.$_REQUEST['theme'].'">Return</a></div>');
					}

					if($pwd == "" || $re_password == "")
					{
					die('<div class = "die"><p>You did not enter one of your passwords</p><a href="gameRegistration.php?theme='.$_REQUEST['theme'].'">Return</a></div>');
					}

					if($pwd != $re_password)
					{
					die('<div class = "die"><p>Your passwords do not match- try again</p><a href="../gameRegistration.php?theme='.$_REQUEST['theme'].'">Return</a></div>');
					}

					if($surname =="")
					{
					die('<div class = "die"><p>You did not enter a surname</p><a href="../gameRegistration.php?theme='.$_REQUEST['theme'].'">Return</a></div>');
					}

					if($email_check == false)
					{
					die('<div class = "die"><p>Invalid email</p><a href="../gameRegistration.php?theme='.$_REQUEST['theme'].'">Return</a></div>');
					}
					
					
					if($user_type == '')
					{
					die('<div class = "die"><p>You need to enter a user type.</p><a href="../gameRegistration.php?theme='.$_REQUEST['theme'].'">Return</a></div>');
					}

					$sql_check_duplicate = "SELECT * from orders.USER where uun = '$uun'";
					$result_check_duplicate=mysql_query($sql_check_duplicate) or die( "A MySQL error has occurred.<br />Your Query: " . $sql_check_duplicate . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
					$num =  mysql_numrows($result_check_duplicate);

					if ($num > 0)
					{
					  die('<div class = "die"><p>UUN already in use - pick another.</p><a href="../gameRegistration.php?theme='.$_REQUEST['theme'].'">Return</a></div>');
					}


                // A higher "cost" is more secure but consumes more processing power
               $cost = 10;

                // Create a random salt
                $salt = substr(str_replace('+', '.', base64_encode(pack('N4', mt_rand(), mt_rand(), mt_rand(), mt_rand()))), 0, 22);

                // Prefix information about the hash so PHP knows how to verify it later.
               // // "$2a$" Means we're using the Blowfish algorithm. The following two digits are the cost parameter.
                $salt = sprintf("$2a$%02d$", $cost) . $salt;

                // Value:
                // $2a$10$eImiTXuWVxfM37uY4JANjQ==

                // Hash the password with the salt
               $hash = crypt($pwd, $salt);
                //$hash = password_hash($password, PASSWORD_BCRYPT);
					//Insert Information Into Database
					$sql = "INSERT INTO orders.USER (email, uun, password, surname, first_name, status, type) VALUES ('".$email."', '".$uun."', '".$hash."', '".$surname."', '".$first_name."', 'P', '".$user_type."');";

                $result=mysql_query($sql) or die( "A MySQL error has occurred.<br />Your Query: " . $sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
					echo '<div class = "die"><p>REGISTRATION COMPLETE. </div>';
					
					//Compose and send confirmation emails
					$concatenated_message .= "First Name: ";
					$concatenated_message .= $first_name."\n";
					$concatenated_message .= "Surname:";
					$concatenated_message .= $surname."\n";
					$concatenated_message .= "UUN";
					$concatenated_message .= $uun;
					$whoto = "scott.renton@ed.ac.uk, diu@ed.ac.uk";
					$subject = "New User Registered For DIU Admin (Approve To Make Administrator)";

					mail( $whoto, $subject,$concatenated_message, "From: $email" );

                    $_SESSION['uun'] = null;

				?>
    <form action="gameMenu.php?theme=<?php echo $_REQUEST['theme']; ?>" method="post">
        <table>
            <tr>
                <th>UUN:</th>
                <td>
                    <input type="text" name="uun" value = "<?php echo $uun; ?>"/><br />
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
        <?php include 'footer.php';?>
        </div>
    </body>
</html>
