<?php
session_start(); 
$prepath="..";
include "$prepath/src/database.php";
include "$prepath/src/functions.php";

if(!isset($_SESSION['uid']))
	session_defaults();
	
$user = new User('s_users');

if(isset($_POST['submit']))
{
	$user->_checkLogin($_POST['username'],$_POST['password'],isset($_POST['remember'])?1:0);
}	


require_once("AjaxTest.class.php"); // We include the class that inherits from AjaxCore

$ajax=new AjaxTest();               // create an instance of the inherited class
site_header();
?>


        <script type = "text/javascript" src = "../ajaxcore/prototype.js" /></script> <!-- include standard prototype library -->
        <script type = "text/javascript" src = "../ajaxcore/AjaxCore.js" /></script><!-- include AjaxCore library -->
        <?php echo $ajax->getJSCode(); /* print some header content to handle the results from the request */ ?>
    </head>
    <body>
                    <?php

                                $ajax->setJSCode("mybutton", array($ajax->htmlDisable("mybutton"),$ajax->htmlInner('results', "Updating <img src='$prepath/images/loading.gif'>")), array($ajax->htmlEnable("mybutton")));
                /*
                                Here we add some JavaScript to execute before and after the AJAX request, notice that we may type manually
                JavaScript code, or call the built-in functions that generates the appropriate JavaScript code, in this case
                to disable and enable buttons and to show the updating message. The methods accept an String or an Array with the Javascript Code.
                */
                
                ?>
                <input type = "button" id = "mybutton" name = "mybutton" value = "press me!" onclick="<?php echo $ajax->bindInline("getRandomNumber",array(),"mybutton"); /* BindInline an HTML object to a JavaScript event to call a PHP function  */ ?>;"/> 
                <br />
                
                <div id = "results" name = "results">
                    <!-- div where results will be placed -->
                </div>
                <br />
                Press <a href="bindInline.phps">here</a> to download this page source file, also check the <a href="AjaxTest.class.phps">AjaxTest.class.php</a> source file.
               </div>
