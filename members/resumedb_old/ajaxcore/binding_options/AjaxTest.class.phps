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
require_once("../AjaxCore.class.php");
class AjaxTest extends AjaxCore
{
	function AjaxTest()
	{
		parent::AjaxCore(basename(__FILE__));
	}
	
	function getRandomNumber()
	{
		sleep(1); // don't use this on a production environment
                echo $this->htmlInner("results", "<h3>success the returned number is ".rand(0,999)."</h3>");
	}
} 

new AjaxTest(); // don't forget to include this

?>