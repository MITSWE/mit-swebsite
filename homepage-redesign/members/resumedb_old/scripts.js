function copyFirstChildDiv(sectionID){

  var d = document.getElementById(sectionID);
  var r = d.getElementsByTagName("div");
  var newRow = r[0].cloneNode(true);
//  alert(newRow.childNodes[1].value);
  newRow.childNodes[1].value=-1; // sets degreeid=-1
 
  var newEl = document.createElement("input");
  newEl.type = "button";
  newEl.value = "delete";
  newEl.onclick = deleteCurrentDiv;
  
  var d1=newRow.childNodes[1].getElementsByTagName("td");
  d1[1].appendChild(newEl);
  
  d.insertBefore(newRow,r[r.length-1]);

}

function deleteCurrentDiv(e){

  if( !e ) var e = window.event;  // for IE
  if (e.target) var targ = e.target;  // for mozilla
  else if (e.srcElement) var targ = e.srcElement; // for IE
  var d = targ.parentNode.parentNode.parentNode;
  var r = targ.parentNode.parentNode;
  d.removeChild(r);
}

// Last updated 2006-02-21
function addRowToTable(table_id)
{
  var tbl = document.getElementById(table_id).tBodies[0];
  var lastRow = tbl.rows.length;
  var newNode = tbl.rows[0].cloneNode(true);
  tbl.appendChild(newNode);
  newNode.cells[1].innerHTML += " <input type=button value='delete' onClick=\"removeRowFromTable(event)\">";
  
  
//  var newCell=newNode.insertCell(2);
//  newCell.appendChild(document.createTextNode("<input type=button value='delete' onClick=\"removeRowFromTable(event)\">"));

}
function keyPressTest(e, obj)
{
  var validateChkb = document.getElementById('chkValidateOnKeyPress');
  if (validateChkb.checked) {
    var displayObj = document.getElementById('spanOutput');
    var key;
    if(window.event) {
      key = window.event.keyCode; 
    }
    else if(e.which) {
      key = e.which;
    }
    var objId;
    if (obj != null) {
      objId = obj.id;
    } else {
      objId = this.id;
    }
    displayObj.innerHTML = objId + ' : ' + String.fromCharCode(key);
  }
}
function removeRowFromTable(e)
{
/*  var tbl = document.getElementById(table_id);
  var lastRow = tbl.rows.length;
  if (lastRow > 1) tbl.deleteRow(lastRow - 1);*/
  
    if( !e ) var e = window.event;  // for IE
  if (e.target) var targ = e.target;  // for mozilla
  else if (e.srcElement) var targ = e.srcElement; // for IE
  var d = targ.parentNode.parentNode.parentNode;
  var r = targ.parentNode.parentNode;
  d.removeChild(r);
}

function getCheckBoxes()
{	
	var checked_values = "";
	
	for(i=0;i<document.myForm.elements.length;i++)
	{
		if(document.myForm.elements[i].type=="checkbox")
		{
			if(document.myForm.elements[i].checked == true)
				checked_values += document.myForm.elements[i].value + ",";
		}	
	}
	document.myForm.checked_values.value=checked_values;
}