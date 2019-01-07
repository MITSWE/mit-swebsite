<!DOCTYPE html>
<html class="no-js">

<head>
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
		<title>MIT SWE | Board </title>
		<meta name="keywords" content="MIT SWE, MIT, Society of Women Engineers, MIT Society of Women Engineers" />
		<meta name="description" content="Massachusetts Institute of Technology Society of Women Engineers is the largest diversity student organization on campus and aims to inspire younger generations about engineering, encourage the notion of diversity in engineering, and determine and advocate for the needs of women engineers at MIT and in the professional world." />
		<!-- global styles -->
		<link rel="stylesheet" href="../../css/bootstrap.css">
		<link rel="stylesheet" href="../../css/footer-distributed.css">
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">

		<link rel="stylesheet" href="../../css/mt-global.css">
<!-- 		page specific styles -->	
		<link rel="stylesheet" href="../css/styles_board.css">
		<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>

</head>

<body class="page  page--home">

	<header class="siteHeader  strip">
	    <div class="wrapper">
	        <!-- Logo -->
	        <a href="../../home/" class="branding"><img src="../../images/SWE_Logo_MIT-horz.png" alt="(MITSWE) SWE Logo"></a>

	         <!-- Primary site Nav -->
	        <a href="#siteNav" class="hamburger  js-menuLink">
			    <span class="hamburger-bun  hamburger-bun--top"></span>
			    <span class="hamburger-patty"></span>
			    <span class="hamburger-bun  hamburger-bun--btm"></span>
			</a>

		<nav id="siteNav" class="navbar  primaryNav" role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
		    <ul class="primaryNav-list">

		        <!-- START: Only visible on mobile -->
		       <!--  <li class="navItem  navItem--supportNumber">
		                            <a class="supportNumber" href="tel:+18775784000">877.578.4000</a>
		                    </li> -->
<!-- 		        <li class="navItem  navItem--login">
		            <a class="loginLink" href="https://ac.mediatemple.net/login.mt?redirect=home.mt"><span>Members Only</span></a>
		        </li> -->
		        <!-- END: Only visible on mobile -->


		       	<!-- Web Hosting -->
		        <!--  About -->
		        <li class="navItem  hasDropdown  js-hasDropdown">
		            <a class="js-dropdownTrigger" href="../../about/"><span>About</span></a>

		            <div class="navDropdown">
		                <ul class="nav  nav--stacked" style="clear:both">
		                    <li class="navItem"><a href="../../about/board">Board Members</a></li>
		                    <li class="navItem"><a href="../../about/national_swe_membership">National SWE</a></li>
		                </ul>
		            </div>
		        </li>

		        <!-- Outreach -->
		        <li class="navItem  hasDropdown  js-hasDropdown ">
		            <a class="js-dropdownTrigger" href="../../outreach/"><span >Outreach</span></a>

					 <div class="navDropdown">
		                <ul class="nav  nav--stacked">
		                    <li class="navItem"><a href="../../outreach/elementary_school">Elementary School</a></li> 
		                    <li class="navItem"><a href="../../outreach/middle_school">Middle School</a></li> 
		                    <li class="navItem"><a href="../../outreach/high_school">High School</a></li> 
		                    <li class="navItem"><a href="../../outreach/special_events">Special Events</a></li> 
		                    <li class="navItem"><a href="../../outreach/resources">Resources</a></li> 

		                </ul>
		            </div>
		        </li>

		        <!-- Corporate -->
		        <li class="navItem hasDropdown  js-hasDropdown">
		            <a class="js-dropdownTrigger" href="../../corporate/"><span>Corporate</span></a>
		            <div class="navDropdown">
		                <ul class="nav  nav--stacked">
		                    <li class="navItem"><a href="../../corporate/banquet/">Career Fair Banquet</a></li>
		                    <li class="navItem"><a href="../../corporate/resume_database/">Resume Database</a></li>
		                </ul>
		            </div>
		        </li>

		        <!-- Calendar -->
		        <li class="navItem ">
		            <a href="../../calendar/"><span>Calendar</span></a>
		        </li>

		        <!--  News -->
		        <li class="navItem ">
		            <a href="../../news/"><span>News</span></a>
		        </li>

		        <!-- Contact Us -->
		        <li class="navItem ">
		            <a href="../../contact/"><span>Contact Us</span></a>
		        </li>		        

				<!-- Members Only -->
		        <li class="navItem  hasDropdown  js-hasDropdown">
		            <a class="js-dropdownTrigger" href="../../members/"><span>For Members</span></a>
		            <div class="navDropdown">
		                <ul class="nav  nav--stacked">
		                    <li class="navItem"><a href="../../members/section_resources">Section Resources</a></li>
		                    <li class="navItem"><a href="../../members/resume_upload">Resume Upload</a></li>
		                    <li class="navItem"><a href="http://swe.mit.edu/wiki">Board Wiki</a></li>
		                </ul>
		            </div>
		        </li>
		    </ul>
		</nav>

<!-- 
        <div id="siteNav" class="navbar  primaryNav rightNav">
		    <ul class="nav  headerNav  u-pullRight">
				
		           
	        </ul>
        </div>
	    </div> <!-- /.wrapper -->
	    <div class="navDropdown--background offPage"></div> 


	</header> <!-- /.siteHeader --> 


    <div class="jumbotron">
      <div class="container">
      </div>
    </div>

    <style>
    .people-container {
    	width:96vw;
    	margin-right: 2vw;
    	margin-left: 2vw;
    }
    .person {
    	display: inline-block;
    	width: 28vw;
    	height: 28vw;
    	margin: 2vw;
    }

	.person-photo-container {
		width: 18vw;
    	height: 18vw;
    	margin-right: 5vw;
    	margin-left: 5vw;
	}

    .person-photo {
    	width: 18vw;
    	height: 18vw;
    }
    .person-info {
    	height: 14vw;
    }
    </style>

    <div class="learn-more">

	  <div class="people-container">
	  	<h4 style="font-size: 30px; text-align: center"><span style="color: #20bc7e;"><strong>SWE Board</strong></span></h4><br><br>
	  	<div class="person-container">
	  	<?php
            $dbh = mysql_connect('sql.mit.edu', 'swe', 'zam52fin')or die('Could not connect: ' . mysql_error() . '<br />');

            mysql_select_db("swe+board") or die("No database selected.");
            
            include_once 'database.php';

            $query = "SELECT * FROM board2017"; 

            $result = mysql_query($query) or die(mysql_error());

            while($row = mysql_fetch_array($result)){

            	$file_name = str_replace(" ", "_", $row['name']).".jpg";

            	$s = "<div class='person'>";
            	$s .= "<div class='person-photo-container'><img class = 'person-photo' src='../board_pics/".$file_name."''></div>";
            	$s .= "<div class='person-info'>".$row['name']."<br/>";
            	$s .= "Group: ".$row['group']."</br>";
            	$s .= $row['position']."</br>";
            	$s .= " Major: ".$row['major']."<br/>";
            	$s .= $row['fact']."</div></div>";

                echo $s;
            }
		?>
		</div>
	</div>

</div></div></td></tr>


	<div id="footer">
		<footer class="footer-distributed">
			<div class="footer-right">

				<a href="https://www.facebook.com/swe.mit/"><i class="fa fa-facebook"></i></a>
				<a href="https://twitter.com/mitswe"><i class="fa fa-twitter"></i></a>
				<a href="https://www.instagram.com/mitswe/"><i class="fa fa-instagram"></i></a>
			</div>

			<div class="footer-left">

				<p class="footer-links">
					<a href="../../home/">Home</a>
					<a href="../../about/">About</a>
					<a href="../../outreach/">Outreach</a>
					<a href="../../corporate/">Corporate</a>
					<a href="../../calendar/">Calendar</a>
					<a href="../../news/">News</a>
					<a href="../../contact/">Contact</a>
					<a href="../../members/">Members Only</a>
				</p>

				<p>MIT Society of Women Engineers &copy; 2017</p>
			</div>

		</footer>
	</div>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>

    <script src="../../js/mt-global.js"></script>


	</body>

</html>
