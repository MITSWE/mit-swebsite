<?php
/**
 *                              AjaxCore 1.4.0
 *                          http://www.ajaxcore.org
 *			http://ajaxcore.sourceforge.net/
 *
 *  AjaxCore is a PHP framework that aims to ease development of rich 
 *  AJAX applications, using Prototype's JavaScript standard library.
 *  
 *  Copyright 2006,2007,2008 Mauro Niewolski (niewolski@users.sourceforge.net)
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */

require_once("AjaxTest.class.php"); // We include the class that inherits from AjaxCore
$ajax=new AjaxTest();               // create an instance of the inherited class

?>
<html>
	<head>
		<title>AjaxCore 1.4.0 BindTimerInline Example</title>
                <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" type="text/css" media="all" href="css/style.css" />
		<link rel = "stylesheet" type = "text/css" media = "all" href = "css/style.css"/>
		<script type = "text/javascript" src = "../prototype.js" /></script> <!-- include standard prototype library -->
		<script type = "text/javascript" src = "../AjaxCore.js" /></script><!-- include AjaxCore library -->
<?php echo $ajax->getJSCode(); /* print some header content to handle the results from the request */ ?>

	</head>
	<body>
    <div id="wrap">
        <div>
            <img src="images/ajaxcore.png" alt="AjaxCore" />
        </div>
        <div class="title">
            <div class = "inner">
                <span class = "corners-top">
                    <span>
                    </span>
                </span>
                AjaxCore 1.4.0 HelpDocs - BindTimerInline Example
                <span class = "corners-bottom">
                    <span>
                    </span>
                </span>
            </div>  <!-- inner -->
        </div> <!-- title -->
        <div class="content">
                <span class = "corners-top">
                    <span>
                    </span>
                </span>
               <div class="main">
                <h3>BindTimerInline Example</h3>
                    This is a simple example file to check out the binding options of the <a href="http://www.ajaxcore.org">AjaxCore</a> framework. <br />

                    <br />In this case we will use <b>BindTimerInline</b> function that maps an HTML object to a PHP function, starting a countdown timer to perform an Ajax request when the HTML event is performed,  in this example the PHP function is getRandomNumber (as our silly function just return a random number) defined in the file  <a href="AjaxTest.class.phps">AjaxTest.class.php</a>
                    <br />
                    <br />Please take in mind that the function executed by the Ajax request, must be defined in one class that extends from AjaxCore class, and therefore extends all Ajax functionality defined in parent class.
                    <br />
                    <br /><i>Method's parameter list </i>
                    <br /><b>bindto</b>: PHP function to be executed
                    <br /><b>timername</b>: name of the timer, multiple objects may share the same timer.
                    <br /><b>timerms</b>:  milliseconds the timer must waits until it expires.
                    <br /><b>params</b>: (optional) – Array of variables which will be sent to the Ajax function.
                    <br /><b>id</b>: (optional) – Reference ID for executing specific Javascript code.
                    <br />
                    <br />
                <?php
                                $ajax->setJSCode("mybutton", array($ajax->htmlDisable("mybutton"),$ajax->htmlInner('results', "Updating <img src='images/loading.gif'>")), array($ajax->htmlEnable("mybutton")));
				/*
                                Here we add some JavaScript to execute before and after the AJAX request, notice that we may type manually
				JavaScript code, or call the built-in functions that generates the appropriate JavaScript code, in this case
				to disable and enable buttons and to show the updating message. The methods accept an String or an Array with the Javascript Code.
				*/
				?>
				<input type = "button" id = "mybutton" name = "mybutton" value = "press me!" onclick="<?php echo $ajax->bindTimerInline("getRandomNumber","mytimer",1000,array(),"mybutton");  /* BindInline an HTML object to a JavaScript event to call a PHP function when timer expires */ ?>" /> 
				<br />
				
				
				<div id = "results" name = "results">
					<!-- div where results will be placed -->
				</div>
				<br />
				Press <a href="bindTimerInline.phps">here</a> to download this page source file, also check the <a href="AjaxTest.class.phps">AjaxTest.class.php</a> source file.
               </div>
               <div class="bar">
                    <dl>
                        <dd>
                            <h3>Binding Options</h3>
                        </dd>
                        <dd>
                           <a href="bindInline.php">Bind Inline</a>
                        </dd>
                        <dd>
                           <a href="bindTimerInline.php">Bind Timer Inline</a>
                        </dd>
                        <dd>
                           <a href="bindPeriodicalTimerInline.php">Bind Periodical Timer Inline</a>
                        </dd>
                        <dd>
                           <a href="onLoadBind.php">onLoad Bind</a>
                        </dd>
                        <dd>
                           <a href="onLoadBindTimer.php">onLoad Bind Timer</a>
                        </dd>
                        <dd>
                           <a href="onLoadBindPeriodicalTimer.php">onLoad Bind Periodical Timer</a>
                        </dd>
                        <dd>
                            <br />
                        </dd>
                        <dd>
                            <h3>Other Stuff</h3>
                        </dd>
                        <dd>
                            <a href="http://www.ajaxcore.org">Home Page</a>
                        </dd>
                        <dd>
                            <a href="http://sourceforge.net/projects/ajaxcore">Project Page</a>
                        </dd>
                         <dd>
                            <a href="http://www.ajaxcore.org/board">Community Board</a>
                        </dd>    
                        <dd>
                            <a href="http://ajaxcore.org/board/viewtopic.php?f=2&t=32">Class Documentation</a>
                        </dd>
                        <dd>
                            <a href="AjaxCore.class.phps">PHP5 Source File</a>
                        </dd>
                        <dd>
                            <a href="AjaxCore.class.php4.phps">PHP4 Source File</a>
                        </dd>    
                    </dl>
               </div>
                <span class = "corners-bottom">
                    <span>
                    </span>
                </span>
        </div> <!-- content -->    
        <div class="footer">
            <div class = "inner">
                <span class = "corners-top">
                    <span>
                    </span>
                </span>
                <a href="http://www.ajaxcore.org">AjaxCore</a> 1.4.0 &copy;  2006,2007,2008 Mauro Niewolski ( niewolski@users.sourceforge.net )
                <span class = "corners-bottom">
                    <span>
                    </span>
                </span>
            </div>  <!-- inner -->
        </div> <!-- footer -->
    </div>
	</body>
</html>