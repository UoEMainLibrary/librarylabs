<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Homepage for Library Labs">
    <meta name="author" content="University of Edinburgh, Library Digital Development Team">
    <link rel="shortcut icon" href="./../favicon.ico">

    <!-- Bootstrap -->
    <link href="./../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./../css/style.css">
    <title>Contact Libray Labs</title>
</head>
<body>

<div class="container">
    <header>
        <div class="container-fluid">
            <div class="row header-row">
                <div class="header-image">
                    <img src="./../css/images/librarylabsheader.png" class="img-responsive">
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
                        <li><a href="./../">Home</a></li>
                        <li><a href="./../about">About</a></li>
                        <li class="dropdown">
                            <a href="./../games" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Metadata Games <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="./../games/gameMenu.php?theme=art">Tag It! Find It!</a></li>
                                <li><a href="./../games/gameMenu.php">Class metadata games</a></li>
                                <li><a href="./../games/gameMenu.php?theme=photo">Research Tagging</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="active"><a href="./../contact">Contact</span></a></li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div><!--/.container-fluid -->
        </nav>

    </header>


    <div class="row">
        <div class="col-lg-12">
            <div class="container">
                <h3>Contact Library Labs</h3>
                <p>If you would like to provide feedback about Library Labs or have any questions, then please contact us using the form below:</p>

                <?php
                // Script for the form has been downloaded from http://chrisplaneta.com/freebies/php_contact_form_script_with_recaptcha/

                $captchaErrorMsg = false;

                //If the form is submitted:
                if(isset($_POST['submitted'])) {

                    //load recaptcha file
                    require_once('./../assets/captcha/recaptchalib.php');
                    include './../games/config/vars.php';

                    //check recaptcha fields
                    $resp = recaptcha_check_answer ($privatekey,
                        $_SERVER["REMOTE_ADDR"],
                        $_POST["recaptcha_challenge_field"],
                        $_POST["recaptcha_response_field"]);

                    //Check to see if the invisible field has been filled in
                    if(trim($_POST['checking']) !== '') {
                        $blindError = true;
                    } else {

                        //Check to make sure that a contact name has been entered
                        $authorName = (filter_var($_POST['formAuthor'], FILTER_SANITIZE_STRING));
                        if ($authorName == ""){
                            $authorError = true;
                            $hasError = true;
                        }else{
                            $formAuthor = $authorName;
                        };

                        //Check to make sure sure that a valid email address is submitted
                        $authorEmail = (filter_var($_POST['formEmail'], FILTER_SANITIZE_EMAIL));
                        if (!(filter_var($authorEmail, FILTER_VALIDATE_EMAIL))){
                            $emailError = true;
                            $hasError = true;
                        } else{
                            $formEmail = $authorEmail;
                        };

                        //Check to make sure the subject of the message has been entered
                        $msgSubject = (filter_var($_POST['formSubject'], FILTER_SANITIZE_STRING));
                        if ($msgSubject == ""){
                            $subjectError = true;
                            $hasError = true;
                        }else{
                            $formSubject = $msgSubject;
                        };

                        //Check to make sure content has been entered
                        $msgContent = (filter_var($_POST['formContent'], FILTER_SANITIZE_STRING));
                        if ($msgContent == ""){
                            $commentError = true;
                            $hasError = true;
                        }else{
                            $formContent = $msgContent;
                        };

                        // if all the fields have been entered correctly and there are no recaptcha errors build an email message
                        if (($resp->is_valid) && (!isset($hasError))) {
                            $emailTo = 'lddt@mlist.is.ed.ac.uk'; // here you must enter the email address you want the email sent to
                            $subject = 'Library Labs Feedback from: ' . $formAuthor . ' | ' . $formSubject; // This is how the subject of the email will look like
                            $body = "Email: $formEmail \n\nContent: $formContent  \n\n$formAuthor"; // This is the body of the email
                            $headers = 'From: <'.$formEmail.'>' . "\r\n" . 'Reply-To: ' . $formEmail . "\r\n" . 'Return-Path: ' . $formEmail; // Email headers

                            //send email
                            mail($emailTo, $subject, $body, $headers);

                            // set a variable that confirms that an email has been sent
                            $emailSent = true;
                        }

                        // if there are errors in captcha fields set an error variable
                        if (!($resp->is_valid)){
                            $captchaErrorMsg = true;
                        }
                    }
                } ?>

                <?php // if the page the variable "email sent" is set to true show confirmation instead of the form ?>
                <?php if(isset($emailSent) && $emailSent == true) { ?>
                    <p>
                        Your email was successfully sent. I check my inbox all the time, so I should be in touch soon.
                    </p>
                <?php } else { ?>
                    <?php // if there are errors in the form show a message ?>
                    <?php if(isset($hasError) || isset($blindError)) { ?>
                        <p>There was an error submitting the form. Please check all the marked fields.</p>
                    <?php } ?>
                    <?php // if there are recaptcha errors show a message ?>
                    <?php if ($captchaErrorMsg){ ?>
                        <p>Captcha error. Please, type the check-words again.</p>
                    <?php } ?>
                    <?php
                    // here, you set what the recaptcha module should look like
                    // possible options: red, white, blackglass and clean
                    // more infor on customisation can be found here: http://code.google.com/intl/pl-PL/apis/recaptcha/docs/customization.html
                    ?>
                    <script type="text/javascript">
                        var RecaptchaOptions = {
                            theme : 'clean'
                        };
                    </script>
                    <?php
                    // this is where the form starts
                    // action attribute should contain url of the page with this form
                    // more on that you can read here: http://www.w3schools.com/TAGS/att_form_action.asp
                    ?>
                    <form id="contactForm" action="" method="post">
                        <div id="singleParagraphInputs">
                            <div>
                                <label for="formAuthor">
                                    Name
                                </label>
                                <input class="requiredField <?php if($authorError) { echo 'formError'; } ?>" type="text" name="formAuthor" id="formAuthor" value="<?php if(isset($_POST['formAuthor']))  echo $_POST['formAuthor'];?>" size="40" />
                            </div>
                            <div>
                                <label for="formEmail">
                                    Email
                                </label>
                                <input class="requiredField <?php if($emailError) { echo 'formError'; } ?>" type="text" name="formEmail" id="formEmail" value="<?php if(isset($_POST['formEmail']))  echo $_POST['formEmail'];?>" size="40" />
                            </div>
                            <div>
                                <label for="formSubject">
                                    Subject
                                </label>
                                <input class="requiredField <?php if($subjectError) { echo 'formError'; } ?>" type="text" name="formSubject" id="formSubject" value="<?php if(isset($_POST['formSubject']))  echo $_POST['formSubject'];?>" size="40" />
                            </div>
                        </div>
                        <div id="commentTxt">
                            <label for="formContent">
                                Message
                            </label>
                            <textarea class="requiredField <?php if($commentError) { echo 'formError'; } ?>" id="formContent" name="formContent" cols="40" rows="5"><?php if(isset($_POST['formContent'])) { if(function_exists('stripslashes')) { echo stripslashes($_POST['formContent']); } else { echo $_POST['formContent']; } } ?></textarea>
                            <?php
                            // this field is visible only to robots and screenreaders
                            // if it is filled in it means that a human hasn't submitted this form thus it will be rejected
                            ?>
                            <div id="screenReader">
                                <label for="checking">
                                    If you want to submit this form, do not enter anything in this field
                                </label>
                                <input type="text" name="checking" id="checking" value="<?php if(isset($_POST['checking']))  echo $_POST['checking'];?>" />
                            </div>
                        </div>
                        <?php
                        // load recaptcha file
                        require_once('./../assets/captcha/recaptchalib.php');
                        include './../games/config/vars.php';
                        // display recaptcha test fields
                        echo recaptcha_get_html($publickey);
                        ?>
                        <input type="hidden" name="submitted" id="submitted" value="true" />
                        <?php // submit button ?>
                        <input type="submit" value="Send Message" tabindex="5" id="submit" name="submit">
                    </form>
                <?php } // yay! that's all folks! ?>
            </div>
        </div>
    </div>

    <footer id="footer">
        <div class="container">
            <div class="uoe-logo">
                <a target="_blank" href="http://www.ed.ac.uk/">
                    <img title="The University of Edinburgh" src="./../css/images/UoELogo.png">
                </a>
            </div>
            <div class="footer-links">
                <ul>
                    <li>
                        <a href="http://www.ed.ac.uk.ezproxy.is.ed.ac.uk/about/website/website&#45;terms&#45;conditions" style="width: 90px;">Terms &amp; conditions </a>
                    </li>

                    <li>
                        <a href="http://www.ed.ac.uk.ezproxy.is.ed.ac.uk/about/website/privacy" style="width: 90px;">Privacy &amp; cookies </a></li>

                    <li>
                        <a href="http://www.ed.ac.uk.ezproxy.is.ed.ac.uk/about/website/accessibility" style="width: 90px;">Website accessibility </a>
                    </li>

                    <li>
                        <a href="http://www.ed.ac.uk.ezproxy.is.ed.ac.uk/about/website/freedom&#45;information"  style="width: 90px;">Freedom of Information Publication Scheme </a>
                    </li>
                </ul>
            </div>
            <div class="luc-logo">
                <a target="_blank" href="http://libraryblogs.is.ed.ac.uk/">
                    <img title="Library and University Collections Blog" src="./../css/images/L&UCLogo.png">
                </a>
            </div>
        </div>
    </footer>

</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="./../assets/bootstrap/js/bootstrap.min.js"></script>
    <!-- Include our contact form js -->
    <script src="./../js/custom.js" type="text/javascript"></script>


</body>
</html>
