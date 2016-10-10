<?php
error_reporting(E_ERROR | E_USER_ERROR |  E_USER_WARNING  | E_USER_NOTICE );
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
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */

class AjaxCore
{
    private $currentfile;
    private $placeholder;
    private $placeholder_enabled = false; // gets enabled if used 
    private $method = "get";
    private $output_headers = false;
    private $cache = false;
    private $updating;
    private $request;
    private $version = "1.4.0"; 
    private $debug = false;
    private $JSCode = array();
    private $trails = array();
    private $watcher = true;
    private $encode_vars=false;
    private $encode_values=true;

    /* private methods */
   
    /**
    * decode
    * 
    * Performs the decoding of the content.
    * @access private
    * @param string $content is the content to be decoded
    * @return string decoded text
    */
    private function decode($content)
    {
        return base64_decode(str_replace("_", "=",$content));
    }  

    /**
    * encode
    * 
    * Performs the encoding of the content.
    * @access private
    * @param string $content is the content to be encoded
    * @return string encoded text
    */
    private function encode($content)
    {
        return str_replace("=", "_", base64_encode($content));
    }    
    
    /**
    * getRequest
    *
    * Returns get or post array of values, decoding it if needed
    * @access private
    * @return array string request
    */
    private function getRequest ( )
    {       
        $this->request=array();        
        foreach(($this->method=="get"?$_GET:$_POST) as $key => $value)
        {
            $this->request[($this->encode_vars?$this->decode($key):$key)]=($this->encode_values?$this->decode($value):$value);
        }
    }
    
    /**
    * lookForAction
    *
    * Determines what PHP function should be called upon each AJAX request
    * @access private
    */
    private function lookForAction ( )
    {
        $this->parseCache();
        $this->getRequest();

        if (!empty($this->request['bind']) && method_exists($this, $this->request['bind']))
        {
            $method=$this->request['bind'];
            $this->initialize();  // method that is called upon each request
            $this->$method();
        }
        else if (isset($this->request['_AjaxCore']) && !method_exists($this, $this->request['bind']))
        {
             trigger_error("AjaxCore error: couldn't find method ".$this->request['bind'], E_USER_ERROR);
        }
    }
    
    /**
    * onLoad
    *
    * Deprecated, See onLoadBind onLoadBindTimer onLoadBindPeriodicalTimer for same binding usage.
    * @access private
    */
    private function onLoad ($bindto, $params = array(), $request = "bind", $timerms = 300)
    {
        if(!$this->triggerOnEmpty($bindto, "bindto", "onLoad") && !$this->triggerOnUnknown($request, "bind", "onLoad", array("bind","bindTimer","bindPeriodicalTimer")) && !$this->triggerOnMismatchType($timerms, "timerms", "onLoad", "integer"))
        {
            if(!is_array($params))
            {
                // convert into an array, for compatibility with previous versions
                $arrayparams=array();
                foreach (explode(",", $params) as $param)
                {
                    if(strlen(trim($param))==0)
                        continue;                    
                    if (substr($param, 0, 1) == '_')
                    {
                        // static value
                        $const =explode('=', $param);
                        $arrayparams[$const[0]]=$const[1];
                    }
                    else
                    {
                        // clone the var
                        $arrayparams[$param]=$param; 
                    }
                }

                $params=$arrayparams;
            }

            $params['_AjaxCore']=$this->version;            
            $code=array();
            $code[]="<script type='text/javascript'>";
            $code[]="var onLoad = function () {";  
            $arrayparams=explode(",", $params);

            if (isset($this->JSCode['onLoad']))
            {
                $code[]=$this->JSCode['onLoad'][0]; // setJS before, there's no html ID, so we'll use onLoad name tag
            }

            $code[]="eval (' var request= { ";

            foreach ($params as $param_key => $param_value)
            {   
                if (substr($param_key, 0, 1) == '_')
                {
                    $code[]=($this->encode_vars?$this->encode($param_key):$param_key).": \'".($this->encode_values?$this->encode($param_value):$param_value)."\',";
                }
                else
                {
                    $code[]=($this->encode_vars?$this->encode($param_key):$param_key).":".($this->encode_values?"encode64(\$F(\'$param_value\'))":"\$F(\'$param_value\')").",";
                }
            }

            if($this->cache == false)
            {
                $code[]=($this->encode_vars?$this->encode("AjaxCoreRandomId"):"AjaxCoreRandomId").":".($this->encode_values?"encode64(Math.floor(Math.random()*100000))":"Math.floor(Math.random()*100000)").",";
            }

            $code[]=($this->encode_vars?$this->encode("bind"):"bind").": \'".($this->encode_values?$this->encode($bindto):$bindto)."\'";
            $code[]="  }; ');";
            $code[]=" var query = \$H(request);";

            if (!empty($this->updating) && $this->placeholder_enabled)
            {
                $code[]="\$('".$this->placeholder."').innerHTML = '".$this->updating."';";
            }

            $code[]="AjaxCore('".$this->currentfile."','".$this->method."',query.toQueryString(),function(originalResponse){" .($this->placeholder_enabled?$this->placeholder:"AjaxCore")."Response(originalResponse);";

            if (isset($this->JSCode['onLoad']))
            {
                $code[]=$this->JSCode['onLoad'][1]; // setJS before, there's no html ID, so we'll use onLoad name tag
            }

            $code[]="});";

            if ($request == "bindPeriodicalTimer")
            {
                $code[]="timers['onLoad'].start();";
            }

            $code[]="};";

            if ($request == "bindTimer" || $request == "bindPeriodicalTimer")
            {
                $code[]="timers['onLoad']=new AjaxCoreTimer(onLoad,$timerms);";
                $code[]="window.onload=new function(){ timers['onLoad'].start() };";
            }
            else
            {
                $code[]="window.onload=new onLoad";
            }

            $code[]="</script>";
            $appended="";

            foreach ($code as $ech)
            {
                $appended.=$ech;
            }

            return $appended;
            
        }
    }        
    
    /**
    * parseCache
    *
    * Parses the current cache
    * @access private
    */
    private function parseCache ( )
    {
        if ($this->output_headers == true && $this->cache == false)
        {
            header("Cache-Control: no-cache, must-revalidate");
        }
    }
    
    /**
    * triggerOnMismatchType
    * 
    * Trigger an error if value's type is mismatched 
    * @access private
    * @return boolean false if not mismatched
    */
    private function triggerOnMismatchType($element,$elementname,$method,$type)
    {
        switch($type)
        {
            case 'boolean':
            if(is_bool($element))
                  return false;
            case 'integer':  
            if(is_integer($element))
                  return false;
            case 'array':
            if(is_array($element))
                  return false;                  
              break;
        }
        
        trigger_error("AjaxCore error: ".$method." \"".$elementname."\" value mismatch it's type, this method expects a value of the type ".$type, E_USER_ERROR);

    }
    
    /**
    * triggerOnEmpty
    *
    * Trigger error an error if value is empty
    * @access private
    * @return boolean false if not empty
    */
    private function triggerOnEmpty ($element, $elementName, $method)
    {
        if (empty($element))
        {
         trigger_error("AjaxCore error: ".$method." \"".$elementName."\" parameter is empty ", E_USER_ERROR);       
        }
        else
        {
            return false;
        }
    }
    
    /**
    * triggerOnUnknown
    *
    * trigger an error if value is not found from the allowed array of values
    * @access private
    * @return boolean false if not unknown
    */
    private function triggerOnUnknown($element,$elementname,$method,$allowed_array)
    {
        foreach($allowed_array as $allowed)
        {
            if($allowed==$element)
            {
                return false;
            }
        }
        
        trigger_error("AjaxCore error: ".$method." \"".$elementName."\" value is unknown, this method expects one of this values (".implode(",",$allowed_array).")", E_USER_NOTICE);       
    }
    
    /*  protected methods  */
    
    /**
    * AjaxCore() 
    *
    * Class constructor
    * @access protected
    */
    protected function AjaxCore ($file=null)
    {
        if(!is_null($file))
        {
            $this->setCurrentFile($file);
        }
        $this->lookForAction();
    }
    
    /**
    * getValue
    *
    * Returns the values sent within the request
    * @access protected
    * @param string var is the variable name sent within the request
    * @return string the value of the var sent within the request
    */
    protected function getValue($var)
    {
        if(!$this->triggerOnEmpty($var, "var","getValue"))
        {
            if(isset($this->request[$var]))
            {
                return $this->request[$var];
            }
            else
            {
                trigger_error("AjaxCore error: getValue cannot find variable name ".$var, E_USER_ERROR);
            }
        }
    }

    /**
    * initialize
    *
    * Method that is called just before any PHP function, useful to initialize databases and so on
    * @access protected
    */
    protected function initialize ( ){} 

    /* public methods*/    

    /**
    * addTrail
    *
    * Adds a trail to the URL for bookmarking and back button
    * @access public
    */
    public function addTrail($id)
    {
        if(!$this->triggerOnEmpty($id, "id", "addTrail"))
        {
             $this->trails[$id]=true;
        }
    }	
    
    /**
    * alert
    *
    * Return JavaScript Alert Message
    * @access public
    * @param string $message message to alert
    * @param string $die true to stop any further execution of the class
    * @return string JavaScript alert
    */
    public function alert ($message, $die = true)
    {
        if(!$this->triggerOnMismatchType($die, "die", "alert", "boolean"))
        {
            $message=$this->escapeJS($message);
            $alert  ="alert('$message');";

            if ($die)
            {
                die($alert);
            }
            else
            {
                return $alert;	
            }          
        }

    }
    
    /**
    * bind
    *
    * For compatibility issues. See bindInline for same binding usage.
    */
    public function bind ($id, $event, $bindto, $params = array())
    {
        if (!$this->triggerOnEmpty($id, "id", "bind") && !$this->triggerOnUnknown($event, "event", "bind",array("onfocus","onblur","onmouseover","onmouseout","onmousedown","onmouseup","onsubmit","onclick","onload","onchange","onkeypress","onkeydown","onkeyup")) && !$this->triggerOnEmpty($bindto, "bindto", "bind"))
        {
            $code=array();
            $code[]="<script type='text/javascript'>";
            $code[]="\$('$id').$event ="; 
            $code[]=substr($this->bindInline($bindto, $params, $id), 3);
            $code[]="</script>";
            
            return implode("",$code);
        }
    }

    /**
    * bindInline
    *
    * Maps an HTML object to a PHP function, no event is required as the code generated is placed on <element onclick="javascript: BINDINLINE"> wherever onclick could be any JavaScript event given.
    * @access public
    * @param string $bindto PHP function to be executed
    * @param string $params (optional) – Array of variables which will be sent to the Ajax function.
    * @param string $id (optional) – Reference ID for executing specific Javascript code.
    * @return string JavaScript inline code to handle the binding.
    */
    public function bindInline ($bindto, $params = array(), $id = "")
    {
        if (!$this->triggerOnEmpty($bindto, "bindto", "bindInline"))
        {        
            if(!is_array($params))
            {
                // convert into an array, for compatibility with previous versions
                $arrayparams=array();
                foreach (explode(",", $params) as $param)
                {
                    if(strlen(trim($param))==0)
                        continue;
                    if (substr($param, 0, 1) == '_')
                    {
                        // static value
                        $const =explode('=', $param);
                        $arrayparams[$const[0]]=$const[1];
                    }
                    else
                    {
                        // clone the var
                        $arrayparams[$param]=$param; 
                    }
                }               
                $params=$arrayparams;
            }
            
            $params['_AjaxCore']=$this->version;            
            $code=array();
            $code[] = "new function () {";  

            if (isset($this->JSCode[$id]))
            {
                $code[]=$this->JSCode[$id][0]; // setJS before
            }

            $code[]=" eval('var request= { ";

            foreach ($params as $param_key => $param_value)
            {   
                if (substr($param_key, 0, 1) == '_')
                {
                    $code[]=($this->encode_vars?$this->encode($param_key):$param_key).": \'".($this->encode_values?$this->encode($param_value):$param_value)."\',";
                }
                else
                {
                    $code[]=($this->encode_vars?$this->encode($param_key):$param_key).":".($this->encode_values?"encode64(\$F(\'$param_value\'))":"\$F(\'$param_value\')").",";
                }
            }
            

            if($this->cache == false)
            {
                $code[]=($this->encode_vars?$this->encode("AjaxCoreRandomId"):"AjaxCoreRandomId").":".($this->encode_values?"encode64(Math.floor(Math.random()*100000))":"Math.floor(Math.random()*100000)").",";
            }

            $code[]=($this->encode_vars?$this->encode("bind"):"bind").": \'".($this->encode_values?$this->encode($bindto):$bindto)."\'";
            $code[]="  }');";

            $code[]=" var query = \$H(request);";

            if (!empty($this->updating) && $this->placeholder_enabled)
            {
                $code[]="\$('".$this->placeholder."').innerHTML = '".$this->updating."';";
            }

            $code[] ="AjaxCore('".$this->currentfile."','".$this->method."',query.toQueryString(),function(originalResponse){".($this->placeholder_enabled?$this->placeholder:"AjaxCore")."Response(originalResponse);";

            if (isset($this->JSCode[$id]))
            {
                $code[]=$this->JSCode[$id][1]; // setJS after
            }

            if(isset($this->trails[$id]))
            {
                $code[]="bread.addSingleTrail('".$this->currentfile."','".$this->method."',query.toQueryString(),'".$this->escapeJS((isset($this->JSCode[$id][0])?$this->JSCode[$id][0]:""))."','".($this->placeholder_enabled?$this->placeholder:"AjaxCore")."Response','".$this->escapeJS((isset($this->JSCode[$id][1])?$this->JSCode[$id][1]:""))."');";
                $code[]="bread.updateURL();"; 
            }

            $code[]="});";
            $code[]="};";

            return implode("",$code);
        }
    }
    
    /**
    * bindPeriodicalTimer
    *
    * For compatibility issues. See bindPeriodicalTimerInline for same binding usage.
    */
    public function bindPeriodicalTimer ($id, $event, $bindto, $timername, $timerms, $params = array())
    {
        if (!$this->triggerOnEmpty($id, "id", "bindPeriodicalTimer") && !$this->triggerOnUnknown($event, "event", "bindPeriodicalTimer",array("onfocus","onblur","onmouseover","onmouseout","onmousedown","onmouseup","onsubmit","onclick","onload","onchange","onkeypress","onkeydown","onkeyup"))) 
        {
            $code=array();
            $code[]="<script type=\"text/javascript\">";
            $code[]="\$('$id').$event ="; 
            $code[]=substr($this->bindPeriodicalTimerInline($bindto, $timername, $timerms, $params, $id), 3);
            $code[]="</script>";

            return implode("",$code);;
        }
    }

    /**
    * bindPeriodicalTimerInline
    *
    * Maps an HTML object to a PHP function,starting a countdown timer to perform an Ajax request when the HTML event is performed, and keeps repeating it forever ( unless timer is killed ), no event is required as the code generated is placed on <element onclick="javascript: BINDINLINE"> wherever onclick could be any JavaScript event given.
    * @access public
    * @param string $bindto  PHP function to be executed
    * @param string $timername name of the timer, multiple objects may share the same timer. 
    * @param int $timerms  milliseconds the timer must waits until it expires. 
    * @param string $params  (optional) – Array of variables which will be sent to the Ajax function.
    * @param string $id (optional) – Reference ID for executing specific Javascript code.
    * @return string JavaScript code to handle the binding.
    */
    public function bindPeriodicalTimerInline ($bindto, $timername, $timerms, $params = array(), $id = "")
    {
        if (!$this->triggerOnEmpty($bindto, "bindto", "bindPeriodicalTimerInline") && !$this->triggerOnEmpty($timername, "timername", "bindPeriodicalTimerInline") && !$this->triggerOnMismatchType($timerms,"timerms", "BindPeriodicalTimerInline", "integer") )
        {
            if(!is_array($params))
            {
                // convert into an array, for compatibility with previous versions
                $arrayparams=array();
                foreach (explode(",", $params) as $param)
                {
                    if(strlen(trim($param))==0)
                        continue;                    
                    if (substr($param, 0, 1) == '_')
                    {
                        // static value
                        $const =explode('=', $param);
                        $arrayparams[$const[0]]=$const[1];
                    }
                    else
                    {
                        // clone the var
                        $arrayparams[$param]=$param; 
                    }
                }

                $params=$arrayparams;
            }
            
            $params['_AjaxCore']=$this->version;            
            $code=array();
            $code[] = "new function () {"; 
            $code[]="if(undefined!=window.timers['".$timername."ID']){  window.clearTimeout(window.timers['".$timername."ID']); }";
            $code[]="timers['".$timername."Handle'] = function () {"; 

            if (isset($this->JSCode[$id]))
            {
                // setJS before
                $code[]=$this->JSCode[$id][0];      
            }
			
            $code[]=" eval('var request= { ";

            foreach ($params as $param_key => $param_value)
            {   
                if (substr($param_key, 0, 1) == '_')
                {
                    $code[]=($this->encode_vars?$this->encode($param_key):$param_key).": \'".($this->encode_values?$this->encode($param_value):$param_value)."\',";
                }
                else
                {
                    $code[]=($this->encode_vars?$this->encode($param_key):$param_key).":".($this->encode_values?"encode64(\$F(\'$param_value\'))":"\$F(\'$param_value\')").",";
                }
            }
					
            if($this->cache == false)
            {
                $code[]=($this->encode_vars?$this->encode("AjaxCoreRandomId"):"AjaxCoreRandomId").":".($this->encode_values?"encode64(Math.floor(Math.random()*100000))":"Math.floor(Math.random()*100000)").",";
            }

            $code[]=($this->encode_vars?$this->encode("bind"):"bind").": \'".($this->encode_values?$this->encode($bindto):$bindto)."\'";
            $code[]="  }');";
            $code[]=" var query = \$H(request);";

            if (!empty($this->updating) && $this->placeholder_enabled)
            {
                $code[]="\$('".$this->placeholder."').innerHTML = '".$this->updating."';";
            }

            $code[]="AjaxCore('".$this->currentfile."','".$this->method."',query.toQueryString(),function(originalResponse){".($this->placeholder_enabled?$this->placeholder:"AjaxCore")."Response(originalResponse);";

            if (isset($this->JSCode[$id]))
            {
                $code[]=$this->JSCode[$id][1]; // setJS after
            }
			
            if(isset($this->trails[$id]))
            {
                $code[]="bread.addSingleTrail('".$this->currentfile."','".$this->method."',query.toQueryString(),'".$this->escapeJS((isset($this->JSCode[$id][0])?$this->JSCode[$id][0]:""))."','".($this->placeholder_enabled?$this->placeholder:"AjaxCore")."Response','".$this->escapeJS((isset($this->JSCode[$id][1])?$this->JSCode[$id][1]:""))."');";
                $code[]="bread.updateURL();"; 
            }

            $code[]="});";
            $code[]=$this->startTimer($timername);
            $code[]="};";
            $code[]="timers['$timername']=new AjaxCoreTimer(timers['".$timername."Handle'],$timerms);";
            $code[]="timers['".$timername."ID']=".$this->startTimer($timername);
            $code[]="};";

            return implode("",$code);
        }
    }
    
    /**
    * bindTimer
    *
    * For compatibility issues. See bindTimerInline for same binding usage.
    */
    public function bindTimer ($id, $event, $bindto, $timername, $timerms, $params = array())
    {
        if (!$this->triggerOnEmpty($id, "id", "bindTimer") && !$this->triggerOnUnknown($event, "event", "bindTimer",array("onfocus","onblur","onmouseover","onmouseout","onmousedown","onmouseup","onsubmit","onclick","onload","onchange","onkeypress","onkeydown","onkeyup")))
        {
            $code=array();
            $code[]="<script type=\"text/javascript\">";
            $code[]="\$('$id').$event ="; 
            $code[]=substr($this->bindTimerInline($bindto, $timername, $timerms, $params, $id), 3);
            $code[]="</script>";

            return implode("",$code);
        }
    }

    /**
    * bindTimerInline
    *
    * Maps an HTML object to a PHP function, starting a countdown timer to perform an Ajax request when the HTML event is performed, no event is required as the code generated is placed on <element onclick="javascript: BINDINLINE"> wherever onclick could be any JavaScript event given.
    * @access public
    * @param string $bindto  PHP function to be executed
    * @param string $timername name of the timer, multiple objects may share the same timer. 
    * @param int $timerms  milliseconds the timer must waits until it expires. 
    * @param string $params  (optional) – Array of variables which will be sent to the Ajax function.
    * @param string $id (optional) – Reference ID for executing specific Javascript code.
    * @return string JavaScript code to handle the binding.
    */
    public function bindTimerInline ($bindto, $timername, $timerms, $params = array(), $id = "")
    {
        if (!$this->triggerOnEmpty($bindto, "bindto", "bindTimerInline") && !$this->triggerOnEmpty($timername, "timername", "bindTimerInline")  && !$this->triggerOnMismatchType($timerms, "timerms", "bindTimerInline", "integer"))
        {
            if(!is_array($params))
            {
                // convert into an array, for compatibility with previous versions
                $arrayparams=array();
                foreach (explode(",", $params) as $param)
                {
                    if(strlen(trim($param))==0)
                        continue;                    
                    if (substr($param, 0, 1) == '_')
                    {
                        // static value
                        $const =explode('=', $param);
                        $arrayparams[$const[0]]=$const[1];
                    }
                    else
                    {
                        // clone the var
                        $arrayparams[$param]=$param; 
                    }
                }

                $params=$arrayparams;
            }
            
            $params['_AjaxCore']=$this->version;            
            $code=array();
            $code[] = "new function () {";  
            $code[]="if(undefined!=window.timers['".$timername."ID']){  window.clearTimeout(window.timers['".$timername."ID']); }";
            $code[]="timers['".$timername."Handle'] = function () {"; 
             
            if (isset($this->JSCode[$id]))
            {
                // setJS before
                $code[]=$this->JSCode[$id][0];    
            }

            $code[]=" eval('var request= { ";

            foreach ($params as $param_key => $param_value)
            {   
                if (substr($param_key, 0, 1) == '_')
                {
                    $code[]=($this->encode_vars?$this->encode($param_key):$param_key).": \'".($this->encode_values?$this->encode($param_value):$param_value)."\',";
                }
                else
                {
                    $code[]=($this->encode_vars?$this->encode($param_key):$param_key).":".($this->encode_values?"encode64(\$F(\'$param_value\'))":"\$F(\'$param_value\')").",";
                }
            }
			
            if($this->cache == false)
            {
                $code[]=($this->encode_vars?$this->encode("AjaxCoreRandomId"):"AjaxCoreRandomId").":".($this->encode_values?"encode64(Math.floor(Math.random()*100000))":"Math.floor(Math.random()*100000)").",";
            }
				
            $code[]=($this->encode_vars?$this->encode("bind"):"bind").": \'".($this->encode_values?$this->encode($bindto):$bindto)."\'";
            $code[]="  }');";
            $code[]=" var query = \$H(request);";
            
            if (!empty($this->updating) && $this->placeholder_enabled)
            {
                $code[]="\$('".$this->placeholder."').innerHTML = '".$this->updating."';";
            }

            $code[] ="AjaxCore('".$this->currentfile."','".$this->method."',query.toQueryString(),function(originalResponse){" .($this->placeholder_enabled?$this->placeholder:"AjaxCore")."Response(originalResponse);";

            if (isset($this->JSCode[$id]))
            {
                $code[]=$this->JSCode[$id][1]; // setJS after
            }
			
            if(isset($this->trails[$id]))
            {
                $code[]="bread.addSingleTrail('".$this->currentfile."','".$this->method."',query.toQueryString(),'".$this->escapeJS((isset($this->JSCode[$id][0])?$this->JSCode[$id][0]:""))."','".($this->placeholder_enabled?$this->placeholder:"AjaxCore")."Response','".$this->escapeJS((isset($this->JSCode[$id][1])?$this->JSCode[$id][1]:""))."');";
                $code[]="bread.updateURL();"; 
            }

            $code[]="});";
            $code[]="};";
            $code[]="timers['$timername']=new AjaxCoreTimer(timers['".$timername."Handle'],$timerms);";
             $code[]="timers['".$timername."ID']=".$this->startTimer($timername);
            $code[]="};";

            return implode("",$code);
        }
    }    

    /**
     * escapeJS (borrowed from Smarty)
     *
     * Escape the string to JavaScript
     * @access public
     * @param string $string String unscaped
     * @return string escaped string
     * @link http://smarty.php.net/manual/en/language.modifier.escape.php  escape (Smarty online manual)
     * @author Monte Ohrt <monte at ohrt dot com>
     */
    public function escapeJS ($string)
    {
        // escape quotes and backslashes, newlines, etc.
        return strtr($string, array(
                '\\'=>'\\\\',
                "'"=>"\\'",
                '"'=>'\\"',
                "\r"=>'\\r',
                "\n"=>'\\n',
            ));
    }    
    
    /**
    * getJSCode
    *
    * Returns string header JavaScript code for evaluating the results of an AJAX Call
    * @access public
    * @return string JavaScript code to be placed on the header of the page
    */
    public function getJSCode ( )
    {
        $code=array();
        $code[]="<script>";
        $code[]="var lastbind='load';";
        $code[]="var timers=Array();";
        $code[]="var onload;";
        
        if($this->placeholder_enabled)
        {
            $code[]="function ".$this->placeholder."Response (originalRequest)";
        }
        else
        {
            $code[]="function AjaxCoreResponse (originalRequest)";
        }
        
        $code[]="{";
        $code[]="	try{";
        
        if($this->placeholder_enabled)
        {
            $code[]="\$('".$this->placeholder."').innerHTML = '';";
        }
        
        $code[]="		eval(originalRequest.responseText);";
        $code[]="	}";
        $code[]="	catch(e)";
        $code[]="	{";

        if ($this->debug)
        {
             $code[]="var msg = e.name + ':' + e.message;";

             $code[]="if (e.fileName) {";
              $code[]="msg += ' at ' + e.fileName + ':' + e.lineNumber;";
             $code[]="}";
            
            $code[]="alert(msg);";
        }

        if($this->placeholder_enabled)
        {
            $code[]="	 \$('".$this->placeholder."').innerHTML = originalRequest.responseText;";
        }
        
        $code[]="	}";
        $code[]="}";
		
        $code[]="var bread=new AjaxCoreBreadcrumb();";
        $code[]="var breadTimer=new AjaxCoreTimer(function(){ bread.start();},1000);";	
        $code[]="breadTimer.start();";	

        if($this->watcher)
        {
            $code[]=" var watcher=new AjaxCoreTimer(AjaxCoreWatcher,1000);";
            $code[]=" watcher.start();";
        }
		
        $code[]="</script>";

        return implode("",$code);
    }    
    
    /**
    * htmlDisable
    *
    * Disables an HTML element
    * @access public
    * @param string $element is the ID of the element
    * @return string JavaScript code to disable element
    */
    public function htmlDisable ($element)
    {
        if(!$this->triggerOnEmpty($element, "element", "htmlDisable"))
        {
            return "\$('".$element."').disabled=true;";
        }
    }
    
    /**
    * htmlEnable
    *
    * Enables an HTML element
    * @access public
    * @param string $element is the ID of the element
    * @return string JavaScript code to enable element
    */
    public function htmlEnable ($element)
    {
        if(!$this->triggerOnEmpty($element, "element", "htmlEnable"))
        {
            return "\$('".$element."').disabled=false;";
        }
    }

    /**
    * htmlSetValue
    *
    * Sets the value for an HTML object
    * @access public
    * @param string $element is the ID of the element
    * @param string $value is the value to be set
    * @return string JavaScript code to set the value of the element
    */
    public function htmlSetValue ($element,$value)
    {
        if(!$this->triggerOnEmpty($element, "element", "htmlEnable"))
        {
            return "\$('".$element."').value=".(is_string($value)?"'$value'":$value).";";
        }
    }

    /**
    * htmlInner
    *
    * Sets an HTML inner content
    * @access public
    * @param string $element is the ID of the element
    * @param string $value is the content to put in
    * @return string JavaScript code to set inner content
    */
    public function htmlInner ($element, $value)
    {
        if(!$this->triggerOnEmpty($element, "element", "htmlInner"))
        {
            return "\$('".$element."').innerHTML = '".$this->escapeJS($value)."';";
        }
    }
    
    /**
    * htmlLocation
    *
    * Sets browser current location
    * @access public
    * @param string $location is the new location
    * @return string JavaScript code to set location 
    */
    public function htmlLocation ($location)
    {
        if(!$this->triggerOnEmpty($location, "location", "htmlLocation"))
        { 
            return "window.location='$location';";
        }
    }    
    
    /**
    * htmlWindowTitle
    *
    * Sets browser current title
    * @access public
    * @param string $string is the new title
    * @return string JavaScript code to set windows title 
    */
    public function htmlWindowTitle ($string)
    {
        return "document.title='".$this->escapeJS($string)."';";
    }   
    
    /**
    * phpArrayToJS
    *
    * Converts an array from PHP to JavaScript.
    * @access public
    * @param array $array php array
    * @return string JavaScript array
    */
    public function phpArrayToJS ($array)
    {
        if(!$this->triggerOnMismatchType($array, "array", "phpArrayToJS", "array"))
        {
            $items=array();

            foreach ($array as $key => $value)
            {
                if (is_array($value))
                {
                    $items[]=$this->phpArrayToJS($value);
                }
                else if (is_int($value))
                {
                    $items[]=$value;
                }
                else
                {
                    $items[]="'".$this->escapeJS($value)."'";
                }
            }

            return '['.implode(',', $items).']';
        }

    }    
    

    
    /**
    * onLoadBind
    *
    * Triggers an Ajax request when the page is loaded
    * @access public
    * @param string $bindto PHP function to be executed
    * @param string $params  (optional) – Array of variables which will be sent to the Ajax function.
    * @return string JavaScript code to handle the binding.
    */    
    public function onLoadBind ($bindto, $params = array())
    {
        return $this->onLoad($bindto,$params, "bind");
    }
 
    /**
    * onLoadBindTimer
    *
    * Triggers a countdown to perform an Ajax request when the page is loaded and timer expired,
    * @access public
    * @param string $bindto PHP function to be executed
    * @param string $params  (optional) – Array of variables which will be sent to the Ajax function.
    * @param int $timerms  milliseconds the timer must waits until it expires. 
    * @return string JavaScript code to handle the binding.
    */    
    public function onLoadBindTimer ($bindto, $params = array(), $timerms = 300)
    {
        return $this->onLoad($bindto, $params = array(), "bindTimer", $timerms);
    }

     /**
    * onLoadBindPeriodicalTimer
    *
    * triggers a countdown to perform an Ajax request when the page is loaded and timer expired and keeps repeating it forever ( unless timer is killed )
    * @access public
    * @param string $bindto PHP function to be executed
    * @param string $params  (optional) – Array of variables which will be sent to the Ajax function.
    * @param int $timerms  milliseconds the timer must waits until it expires. 
    * @return string JavaScript code to handle the binding.
    */   
    public function onLoadBindPeriodicalTimer ($bindto, $params = array(), $timerms = 300)
    {
        return $this->onLoad($bindto, $params = array(), "bindPeriodicalTimer", $timerms);
    }
    
    /**
    * setCache
    *
    * Sets to use cache or not
    * @access protected
    * @param bool $cache boolean value
    */
    public function setCache ($value)
    {
        if (!$this->triggerOnMismatchType($value, "value","setCache", "boolean"))
        {
            $this->cache=$value;
        }
    }    
    
    /** 
    * setCurrentFile 
    *
    * Sets filename of the extended class that inherits of AjaxCore
    * @access protected
    * @param string $file filename of the inherited class, where the AJAX request will be made.
    */
    public function setCurrentFile ($file)
    {
        if (!$this->triggerOnEmpty($file,"file","setCurrentFile"))
        {
            $this->currentfile=$file;
        }
    }
    
    /**
    * setDebug
    *
    * Set if it should print JavaScript error's message when they occurs evaluating the results
    * @access public
    * @param bool $debug boolean value.
    */
    public function setDebug ($value)
    {
        if (!$this->triggerOnMismatchType($value,"value","setDebug", "boolean"))
        {
            $this->debug=$value;
        }
    }
    
    /**
    * setEncodeValues
    * 
    * Sets if all variable values should be encoded prior to being used as request params
    * @access protected
    * @param boolean $value
    */
    public function setEncodeValues($value)
    {
        if (!$this->triggerOnMismatchType($value, "value", "setEncodeValues", "boolean"))
        {
            $this->encode_values=$value;
        }
    }    
    
    /**
    * setEncodeVars
    * 
    * Sets if all variable names should be encoded prior to being used as request params
    * @access protected
    * @param boolean $value
    */
    public function setEncodeVars($value)
    {
        if (!$this->triggerOnMismatchType($value, "value", "setEncodeVars", "boolean"))
        {
            $this->encode_vars=$value;
        }
    }    

    /**
    * setJSCode
    * 
    * Sets specific JavaScript code to execute before and after the AJAX request is made. 
    * @access public
    * @param string $id HTML object id for binding methods, or reference id for inline bindings
    * @param string $before JavaScript code to execute before the AJAX request is being made
    * @param string $after JavaScript code to execute before the AJAX request is being made
    */
    public function setJSCode ($id, $before, $after)
    {
        if(!$this->triggerOnEmpty($id, "id", "setJSCode"))
        {
            if(is_array($before))
            {
                $before=implode("",$before);
            }
            if(is_array($after))
            {
                $after=implode("",$after);
            }
            $this->JSCode[$id]=array($before,$after);
        }
    }    
    
    /**
    * setMethod
    *
    * Sets if the method should be Get or Post
    * @access protected
    * @param string $method get or post
    */
    public function setMethod ($method)
    {
        $method=strtolower($method);

        if (!$this->triggerOnUnknown($method, "method", "setMethod",array("get","post")))
        {
            $this->method=$method;
        }
    }    
    
    
    /**
    * setOutputHeaders
    *
    * Sets output headers, it should prevent people not using template's engine the error - Cannot modify header information
    * @access protected
    * @param string $value true or false
    */
    public function setOutputHeaders ($value)
    {
        if (!$this->triggerOnMismatchType($value, "value", "setOutputHeaders", "boolean"))
        {
            $this->output_headers=$value;
        }
    }
    
    /**
    * setPlaceHolder
    *
    * Sets the <Div> ID that will be used as placeholder, as AjaxCore returns JavaScript code or HTML content in case JavaScript output is not understood, it will echo it on the placeHolder
    * @access protected
    * @param string $placeholder <Div id=""> used to return Html results
    * @deprecated the use of placeholders is deprecated, please use htmlInner for outputting content into a place
    */
    public function setPlaceHolder ($placeHolder)
    {

        trigger_error("AjaxCore warning:  setPlaceHolder method is deprecated, set htmlInner to output content to any div on the page ", E_USER_DEPRECATED);
            
        if(!$this->triggerOnEmpty($placeHolder,"placeHolder","setPlaceHolder"))
        {
            $this->placeholder_enabled=true;
            $this->placeholder=$placeHolder;
        }
    }

    /**
    * setUpdating
    *
    * Sets an HTML code while the AJAX request is being made
    * @access protected
    * @param string $code HTML code to show while making the request
    */
    public function setUpdating ($code)
    {
        $this->updating=$this->escapeJS($code);
    }

    /**
    * setWatcher
    *
    * Sets to use or not a watcher, this checks the current URL to track changes and to perform any action required (i.e. back button)
    * @access protected
    * @param bool $value boolean value
    */
    public function setWatcher($value)
    {
        if(!$this->triggerOnMismatchType($value,"value","setWatcher" ,"boolean"))
        {
             $this->watcher=$value;
        }
    }

    /**
    * startTimer
    *
    * Restarts a timer
    * @access public
    * @param string id is the timer id
    * @return string JavaScript code to start timer
    */
    public function startTimer ($id)
    {
        if(!$this->triggerOnEmpty($id, "id", "startTimer"))
        {
            return "timers['$id'].start();";
        }
    }

    /**
    * stopTimer
    *
    * Stops a timer
    * @access public
    * @param string $id is the timer id
    * @return string JavaScript code to stop timer
    */
    public function stopTimer ($id)
    {
        if(!$this->triggerOnEmpty($id, "id", "stopTimer"))
        {
            return "timers['$id'].reset();";
        }
    }
}
?>