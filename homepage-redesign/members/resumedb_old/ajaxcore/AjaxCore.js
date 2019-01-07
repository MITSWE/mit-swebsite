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
 var keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/_";
 var address = location.href;
 var requestsSerialized;
 
function AjaxCore(url,method,pars,response)
{

	var Request = new Ajax.Request(
		url, 
		{
			method: method, 
			parameters: pars, 
			onComplete: response
		});
}

function AjaxCoreSerialized(requests)
{
    this.request_index=0;
    this.requests=requests;
}

AjaxCoreSerialized.prototype.request = function ()
{
    if(this.request_index<this.requests.length)
    {
        var data=this.requests[this.request_index];
        eval(data[3]); // js before

	    var Request = new Ajax.Request(
		    data[0], 
		    {
			    method: data[1], 
			    parameters: data[2], 
			    onComplete: this.callBack
		    });
    }
}

AjaxCoreSerialized.prototype.callBack = function (originalRequest)
{
    var data=requestsSerialized.requests[requestsSerialized.request_index];
    eval(data[4])(originalRequest); // callback
    eval(data[5]); // js after
    requestsSerialized.request_index++;
    requestsSerialized.request();
}

function encode64(input) 
{
   input=input+"";
   var output = "";
   var chr1, chr2, chr3;
   var enc1, enc2, enc3, enc4;
   var i = 0;

   do {
      chr1 = input.charCodeAt(i++);
      chr2 = input.charCodeAt(i++);
      chr3 = input.charCodeAt(i++);

      enc1 = chr1 >> 2;
      enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
      enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
      enc4 = chr3 & 63;

      if (isNaN(chr2)) {
         enc3 = enc4 = 64;
      } else if (isNaN(chr3)) {
         enc4 = 64;
      }

      output = output + keyStr.charAt(enc1) + keyStr.charAt(enc2) + 
         keyStr.charAt(enc3) + keyStr.charAt(enc4);
   } while (i < input.length);
   
   return output;
}

function decode64(input) 
{
   var output = "";
   var chr1, chr2, chr3;
   var enc1, enc2, enc3, enc4;
   var i = 0;

   input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
   do {
      enc1 = keyStr.indexOf(input.charAt(i++));
      enc2 = keyStr.indexOf(input.charAt(i++));
      enc3 = keyStr.indexOf(input.charAt(i++));
      enc4 = keyStr.indexOf(input.charAt(i++));

      chr1 = (enc1 << 2) | (enc2 >> 4);
      chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
      chr3 = ((enc3 & 3) << 6) | enc4;

      output = output + String.fromCharCode(chr1);

      if (enc3 != 64) {
         output = output + String.fromCharCode(chr2);
      }
      if (enc4 != 64) {
         output = output + String.fromCharCode(chr3);
      }
   } while (i < input.length);

   return output;
}	

function AjaxCoreWatcher()
{
   if(address!=location.href)
   {
      window.location.reload();
   }
   
   watcher.start(); 
}

function AjaxCoreTimer(handle, ms)
{
    this.handle = handle;
    this.ms = ms;
    this.timer = 0;
}

AjaxCoreTimer.prototype.start = function()
{
    if (this.timer > 0)
    {
        this.reset();
    }
    
    this.timer = window.setTimeout(this.handle, this.ms);

    return this.timer;
}

AjaxCoreTimer.prototype.reset = function()
{
    if (this.timer > 0)
    {
        window.clearTimeout(this.timer);
    }
    
    this.timer = 0;
}

function AjaxCoreBreadcrumb()
{
    this.trails= new Array();
}

AjaxCoreBreadcrumb.prototype.start = function()
{
    var url=location.href;
    var url=url.replace(new RegExp(/%20/g),' ')
    var urlTrails=url.split('#');
 
    if(urlTrails.length>1)
    {
        urlTrails[1]=decode64(urlTrails[1]);
        this.trails=urlTrails[1].toString().split(',');
        var requests=Array();
        for(var i=0;i<this.trails.length;i++)
        { 
           var trail=String(this.trails[i]).substring(0,this.trails[i].length).split("%");
           requests[i]=trail;
        }
         requestsSerialized=new AjaxCoreSerialized(requests);
         requestsSerialized.request();
         
    }
}
	
AjaxCoreBreadcrumb.prototype.addMultipleTrail = function(url,method,pars,jsbefore,response,jsafter)
{
    // Support for multiple levels of trails, working but not tested
    this.trails[this.trails.length]=url+"%"+method+"%"+pars+"%"+jsbefore+"%"+response+"%"+jsafter;
}

AjaxCoreBreadcrumb.prototype.addSingleTrail = function(url,method,pars,jsbefore,response,jsafter)
{
    this.trails[0]=url+"%"+method+"%"+pars+"%"+jsbefore+"%"+response+"%"+jsafter;
}

AjaxCoreBreadcrumb.prototype.updateURL = function()
{

    var url=location.href;
    var notrail=url.split("#");
    var local_address;
    var request="";
    if(notrail.length>0)
    {
       local_address=notrail[0]; 
    }
    
    else
    {
       local_address=notrail;
    }
    
    local_address+="#";
    for(var i=0;i<this.trails.length;i++)
    {
        if(i>0)
        {
            request+=",";
        }
        request+=this.trails[i];
    }
 
    local_address+=encode64(request);
    local_address+="";
    location.href=local_address;
    address=local_address; 
}

