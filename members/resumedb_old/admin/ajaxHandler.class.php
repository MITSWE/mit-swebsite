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
require_once("../src/database.php");
class AjaxHandler extends AjaxCore
{
    function AjaxHandler()
    {
	    $this->setDebug(false);
	    parent::AjaxCore(basename(__FILE__));
    }
    
    public function GetEmails()
    {
	    $id = $this->getValue('a');
	    $ids = explode(",",$id);
	    foreach($ids as $id)
	    {
		 	if($id!="")
		    	$query .= "s_users_id='$id' OR ";   
	    }
	    $query = rtrim($query," OR ");
	    $sql = "select username from s_users where $query";
	    $res = db_query($sql);
	    while($row=mysql_fetch_array($res))
	    {
			$emails .= $row['username']."@mit.edu, ";   
	    }
	    $emails = rtrim($emails,", ");
	    
		echo $this->htmlInner('emails',"$emails");
    }
}   
    
new AjaxHandler(); // don't forget to include this

?> 