<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Homepage for Library Labs">
    <meta name="author" content="University of Edinburgh, Library Digital Development Team">
    <link rel="shortcut icon" href="./favicon.ico">
	<!-- <script src="https://unpkg.com/scrollreveal/dist/scrollreveal.min.js"></script> -->
    <!-- Bootstrap -->
    <link href="./assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
    <script type="text/javascript" src="./js/jquery-3.2.1.min.js" ></script>
    <title>Welcome to Library Labs</title>
    <style type="text/css">
    	@media (min-width: 1500px) {
			.container {
			    width: 1500px;
    	}
		}

    	div.row.header-row {
    		padding-top: 30%;
    		background-image: url('./images/background0.jpg');
    		position: relative;
    		overflow: hidden;
    		background-size: cover;
    		-webkit-background-size: cover!important;
    		background-attachment: fixed;
    		background-repeat: no-repeat!important;
    		height: 100vh;
    		-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
			margin-top: 20px;
    	}
    	#title {
    		display: block;
    		/*width: 500px;*/
    		height: 80%;
    		width: auto;
    		/*margin-top: 33%;*/
    		margin: auto;
    		/*position: fixed;*/
    	}
    	#titleDiv {
    		overflow: auto;
    		/*margin-top: 33%;*/
    		position: fixed;
    		
    		/*width: 30%;*/
    		/*margin-top: 33%;*/
    		/*height: 30%;*/
    		width: 80%;
    		margin: auto;    		
    	}
    	.body {
    		overflow: auto;
			background-color: rgba(0,0,0,0.5);
    	}
        .home-box {
/*        	margin-top: 100px;*/
			width: 100%;
			height: 100%;
			padding-top: 120px;
            background: none;
            background-color: rgba(0, 0, 0, 0);
        }

        .col-lg-4 {
        	overflow: auto;
        	padding: 0;
            float: none;
            width: 98%;
            min-height: 500px;
            margin: auto !important;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            -webkit-transition: width 0.5s; /* For Safari 3.1 to 6.0 */
            /*transition: height 1s, width 1s;*/
            color: white;

        }
        div.col-lg-4:hover {
            /*height: 365px;*/
            width: 100%;
        }
        .col-lg-4 img {
            width: 250px;
            float: left;
            margin-left: 20px;
            clear: both;
        }
        #mapIcon {
            margin-left: 20px;
            /*margin-top: 10px;*/
            width: 260px;
            height: 260px;
            background-color: #1B325E;
            border-radius: 50px;
        }
        #mapIcon img {
            width: 250px;
            margin-top: 8px;
        }
        #polyannoIcon {
            /*float: right;*/
            /*width: 300px;*/
        }
        #blog {
            /*background-color: #ffee32;*/
            background-image: url('./images/background1.jpg');
        }
        #map {
        	height: 530px;
            background-image: url('./images/background2.jpg');
            /*background-color: #ff5e56;*/
        }
        #metadataGames {
            background-image: url('./images/background3.jpg');
            /*background-color: #389cf4;*/
        }
        #iiif {
            background-image: url('./images/background4.jpg');
            /*background-color: #389cf4;*/
        }
        #polyanno {
        	/*background-image: url('./polyanno_background.jpg');*/
        	background-image: url('./images/about_background.jpg');
            /*background-color: #4ace4c;*/
        }
        #about {
        	/*background-image: url('./about_background.jpg');*/
        	background-image: url('./iamges/polyanno_background.jpg');
        }
        h1 {
            font-size: 4em;
        }
        p {
            font-size: 1.5em;
        }
/*        .sticky {
        	position: fixed;
        	top: 0;        
        }*/
        .navbar {
        	/*position: fixed;*/
        }

        .menu {
		    padding:10px;
		}
		.menu {
		   	z-index: 10;
		    background:#428bca;
		    color:#fff;
		    height:50px;
		    line-height:30px;
		    letter-spacing:1px;
		    width:100%;
		}
		.menu-padding {
		    padding-top: 40px;
		}
		.sticky {
		    position:fixed;
		    top:0;
		}

        @media (max-width: 640px) {
            #footer img {
                display: none;
            }
            #footer ul {
                width: 100%;
                margin: auto;
            }
            div.footer-links {
                float: none;
                width: 98%;
                margin: auto;
            }
            div.row.header-row {
                background-image: url('./images/background7_mobile.svg');
            }
            .col-lg-4 img {
                float: none;
                margin: 0;
                margin: auto;
            }
            .col-lg-4 {
                overflow: auto;
                padding: 0;
                float: none;
                width: 100%;
                min-height: 510px;
                margin: auto !important;
                box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
                color: white;
                padding-bottom: 20px;
            }
            .home-box {
                overflow: auto;
                padding-top: 60px;
            }
            #metadataGames {
                margin: 0;
            }
            div.row.header-row {
                width: 100%;
                margin: 0;
            }
            div.container-fluid {
                padding: 0;
            }
        }
        @media (max-width: 240px) {
            .col-lg-4 img {
                width: 180px;
                float: left;
                margin-left: 20px;
                clear: both;
            }            
        }

        @media (min-width: 1600px) {
             #title {

                width: 1200px;


            }
        }
    </style>
</head>
<body>
<!-- <script type="text/javascript">
	window.sr = ScrollReveal();
	sr.reveal('.col-lg-4');
	// Changing the defaults
window.sr = ScrollReveal({ reset: true });

// Customizing a reveal set
sr.reveal('.col-lg-4', { 
	// 'bottom', 'left', 'top', 'right'
origin: 'bottom',

// Can be any valid CSS distance, e.g. '5rem', '10%', '20vw', etc.
distance: '20px',

// Time in milliseconds.
duration: 500,
delay: 0,

// Starting angles in degrees, will transition from these values to 0 in all axes.
rotate: { x: 0, y: 0, z: 0 },

// Starting opacity value, before transitioning to the computed opacity.
opacity: 0,

// Starting scale value, will transition from this value to 1
scale: 0.9,

// Accepts any valid CSS easing, e.g. 'ease', 'ease-in-out', 'linear', etc.
easing: 'cubic-bezier(0.6, 0.2, 0.1, 1)',

// `<html>` is the default reveal container. You can pass either:
// DOM Node, e.g. document.querySelector('.fooContainer')
// Selector, e.g. '.fooContainer'
container: window.document.documentElement,

// true/false to control reveal animations on mobile.
mobile: true,

// true:  reveals occur every time elements become visible
// false: reveals occur once as elements become visible
reset: false,

// 'always' — delay for all reveal animations
// 'once'   — delay only the first time reveals occur
// 'onload' - delay only for animations triggered by first load
useDelay: 'always',

// Change when an element is considered in the viewport. The default value
// of 0.20 means 20% of an element must be visible for its reveal to occur.
viewFactor: 0.2,

// Pixel values that alter the container boundaries.
// e.g. Set `{ top: 48 }`, if you have a 48px tall fixed toolbar.
// --
// Visual Aid: https://scrollrevealjs.org/assets/viewoffset.png
viewOffset: { top: 0, right: 0, bottom: 0, left: 0 },

// Callbacks that fire for each triggered element reveal, and reset.
beforeReveal: function (domEl) {},
beforeReset: function (domEl) {},

// Callbacks that fire for each completed element reveal, and reset.
afterReveal: function (domEl) {},
afterReset: function (domEl) {} 
});
	// sr.reveal('.bar');

</script> -->
<?php include_once("./analyticstracking.php") ?>
<div class="container">
    <header>
        <div class="container-fluid">
            <div class="row header-row">
            	<div id="titleDiv">
            		<img id="title" src="./title_new.svg">
            	</div>
                <div class="header-image">
                    <!-- <img src="./css/images/librarylabsheader.png" class="img-responsive"> -->
                    <!-- <img src="./header_new.svg"> -->
                </div>
            </div>
        </div>
        <!-- Static navbar -->
        <script type="text/javascript">
			// $(document).ready(function () {

			//     var menu = $('.menu');
			//     var origOffsetY = menu.offset().top;

			//     function scroll() {
			//         if ($(window).scrollTop() >= origOffsetY) {
			//             $('.menu').addClass('sticky');
			//             $('.content').addClass('menu-padding');
			//         } else {
			//             $('.menu').removeClass('sticky');
			//             $('.content').removeClass('menu-padding');
			//         }


			//     }

			//     document.onscroll = scroll;

			// });
        </script>
        <!-- <div class="menu">home | services | contact</div> -->
        <nav class="navbar navbar-default navbar-fixed-top navbar-inverse" style="margin-bottom: 0;">
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
                        <li class="active"><a href="./">Home</a></li>
                        <li><a href="./about">About</a></li>
                        <li class="dropdown">
                            <a href="./games" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Metadata Games <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="./games/gameMenu.php?theme=art">Tag It! Find It!</a></li>
                                <li><a href="./games/gameMenu.php?theme=classic">Class metadata games</a></li>
                                <li><a href="./games/gameMenu.php?theme=photo">Research Tagging</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="./contact">Contact</span></a></li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div><!--/.container-fluid -->
        </nav>

    </header>


    <div class="row">
        <div class="col-lg-4" id="blog" onclick="window.location='http://libraryblogs.is.ed.ac.uk/librarylabs';">
            <div class="link-box box-left home-box">
                <a href="http://libraryblogs.is.ed.ac.uk/librarylabs" style="display: block;">
                    <!-- <img title="Library Labs Blog" src="./css/images/BlogTile.png"> -->
                    <img title="Library Labs Blog" src="./images/blog_icon.png">
                </a>
                <div class="description">
                    <h1>Blog</h1>
                    <p>Read about the latest Library labs news & experiments</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4" id="metadataGames" onclick="window.location='./login';">
            <div class="link-box box-middle home-box">
                <a href="./login">
                    <!-- <img title="Metadata Games" src="./css/images/MetadataGamesTile.png"> -->
                    <img style="border-radius: 50px;" title="Metadata Games" src="./images/robot_icon.png">
                </a>
                <div class="description">
                    <h1>Metadata Games</h1>
                    <p>Teach our A.I. robot Leevi to recognize different pictures.</p>
                </div>               
            </div>
        </div>
        <div class="col-lg-4" id="map" onclick="window.location='https://www.librarymaps.is.ed.ac.uk';">
        <div class="link-box box-right home-box">
                <a href="https://www.librarymaps.is.ed.ac.uk">
                    <!-- <img title="About Library Labs" src="./css/images/AboutTile.png"> -->
                    <img id="mapIcon" title="Library Map" src="./images/map_logo.png">
                </a>
                <div class="description">
                    <h1>Library Maps</h1>
                    <p>Read about the latest Library labs news & experiments</p>
                </div>                
                </div>
        </div>
        <div class="col-lg-4" id="iiif" onclick="window.location='https://www.librarymaps.is.ed.ac.uk';">
        <div class="link-box box-right home-box">
                <a href="iiif/index.html">
                    <!-- <img title="About Library Labs" src="./css/images/AboutTile.png"> -->
                    <img id="iiifIcon" title="IIIF Logo" src="./images/iiif_logo.png">
                </a>
                <div class="description">
                    <h1>IIIF</h1>
                    <p>Image experimentation with IIIF</p>
                </div>
                </div>
        </div>
        <div class="col-lg-4" id="polyanno" onclick="window.location='https://test.polyanno.librarylabs.ed.ac.uk';">
        <div class="link-box box-right home-box">
                <a href="https://test.polyanno.librarylabs.ed.ac.uk">
                    <!-- <img title="About Library Labs" src="./css/images/AboutTile.png"> -->
                    <img id="polyannoIcon" title="Polyanno" src="./images/polyanno_icon.svg">
                </a>
                <div class="description">
                    <h1>Polyanno</h1>
                    <p>Read about the latest Library labs news & experiments</p>
                </div>                
                </div>
        </div>
        <div class="col-lg-4" id="about" onclick="window.location='./about';">
            <div class="link-box box-right home-box">
                <a href="./about">
                    <!-- <img title="About Library Labs" src="./css/images/AboutTile.png"> -->
                    <img title="About Library Labs" src="./images/contact_icon.png">
                </a>
                <div class="description">
                    <h1>Blog</h1>
                    <p>Read about the latest Library labs news & experiments</p>
                </div>
            </div>
        </div>

    </div>

    <footer id="footer">
        <div class="container">
            <div class="uoe-logo">
                <a target="_blank" href="http://www.ed.ac.uk/">
                    <img title="The University of Edinburgh" src="./css/images/UoELogo.png">
                </a>
            </div>
            <div class="footer-links">
                <ul>
                    <li>
                        <a href="http://www.ed.ac.uk.ezproxy.is.ed.ac.uk/about/website/website&#45;terms&#45;conditions">Terms &amp; conditions </a>
                    </li>

                    <li>
                        <a href="http://www.ed.ac.uk.ezproxy.is.ed.ac.uk/about/website/privacy">Privacy &amp; cookies </a></li>

                    <li>
                        <a href="http://www.ed.ac.uk.ezproxy.is.ed.ac.uk/about/website/accessibility">Website accessibility </a>
                    </li>

                    <li>
                        <a href="http://www.ed.ac.uk.ezproxy.is.ed.ac.uk/about/website/freedom&#45;information">Freedom of Information Publication Scheme </a>
                    </li>
                </ul>
            </div>
            <div class="luc-logo">
                <a target="_blank" href="http://libraryblogs.is.ed.ac.uk/">
                    <img title="Library and University Collections Blog" src="./css/images/L&UCLogo.png">
                </a>
            </div>
        </div>
    </footer>

</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="./assets/bootstrap/js/bootstrap.min.js"></script>

</body>
</html>
