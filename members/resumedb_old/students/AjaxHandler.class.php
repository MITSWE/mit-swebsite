<?php 

/**
 *                              AjaxCore 1.4.0
 *                          http://www.ajaxcore.org
 *            http://ajaxcore.sourceforge.net/
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
require_once("../ajaxcore/AjaxCore.class.php");
class AjaxHandler extends AjaxCore
{
    function AjaxHandler()
    {
        parent::AjaxCore(basename(__FILE__));
    }
    
    public function ValidatePassword()
    {
	    $pwd1=$this->getValue('pwd1');
	    $pwd2=$this->getValue('pwd2');
	   	$error="";
	    if (strlen($pwd1) < 6) {
		    $error = " Password must be at least 6 characters. ";
		}
	  	else if (strlen($pwd1) > 30) {
	    	$error = " Password must be less than 30 characters. ";
		}
	   
	    if($pwd1==$pwd2)
		{
			if(strlen($error) == 0)
				echo $this->htmlInner('results',"<span class='okay'>Password okay</span>");
			else
				echo $this->htmlInner('results',"<span class='error'>$error</span>");
		}
		else
			echo $this->htmlInner('results',"<span class='error'>Password does not match</span>");
    }
}   
    
new AjaxHandler(); // don't forget to include this

?> 