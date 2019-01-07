function validateForm()
{
	var spans; 
	spans = document.getElementsByTagName('span')
	gErrors=0;	
	
	for (i=0; i<spans.length; i++)//loop through all the <span> elements 
	{
		// if the class name of that td element is rules check to see if there are error warnings
		if (spans[i].className == "error")
		{
	
//			alert("text: " +spans[i].innerHTML);
			//if it's blank then it passes
			if (spans[i].innerHTML == ''||spans[i].innerHTML == ' ')
			{
				
			}
			else
			{
				gErrors = gErrors + 1; //the error count increases by 1
			}
		}
	}
	if (gErrors > 0)
	{
		//if there are any errors give a message
		alert ("Please make sure all fields are properly completed.  Errors are marked in red!");
		gErrors = 0;// reset errors to 0
		return false;
	}
	else return true;	
	
}

function confirm_entry(message)
{
	input_box=confirm(message);
	if (input_box==true)
		return true;
	else
		return false;
}