/************************** Make Owner **********************************/
function makeOwner(urlVal,projId,userId,pageSpan,loader,type,name,idd) 
{
	
	document.getElementById(idd).style.display='none';
	document.getElementById(loader).style.display='block';
	
	var op = 100;
	document.getElementById('upperDiv').style.filter="alpha(opacity="+op+")";
	document.getElementById('upperDiv').style.MozOpacity = 1;

	var xmlHttpReq = false;
	var self = this;
	if(window.XMLHttpRequest) 
	{
		self.xmlHttpReq = new XMLHttpRequest();
	}
	else if(window.ActiveXObject) 
	{
		self.xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
	}
	self.xmlHttpReq.open('POST', urlVal, true);
	self.xmlHttpReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	self.xmlHttpReq.onreadystatechange = function() 
	{
		if(self.xmlHttpReq.readyState == 4) 
		{
			updatepage_owner(self.xmlHttpReq.responseText,pageSpan);
			document.getElementById(loader).style.display='none';
			document.getElementById('topmostdiv').style.display='block';
			if(type == "make")
			{
				var msg = "Owner assigned successfully to '"+name+"'"
			}
			else if(type == "remove")
			{
				var msg = "Owner removed successfully from '"+name+"'"
			}
			if(msg)
			{
				document.getElementById('upperDiv').style.backgroundColor='#DEFEE1';
				document.getElementById('upperDiv').style.color='#00802B';
				document.getElementById('upperDiv').style.display='block';
				document.getElementById('upperDiv').innerHTML=msg;
			}
		}
	 }
	self.xmlHttpReq.send(getquerystring_owner(projId,userId,type));
}
function getquerystring_owner(projId,userId,type)
{
	var qstr_owner;
	qstr_owner ='get_project_owner=' +escape(projId)+"|"+escape(userId)+"|"+escape(type);
	return qstr_owner;
}
function updatepage_owner(str,pageSpan)
{
	document.getElementById(pageSpan).innerHTML = str;	 
}
function editProject(url,spn,loader,pr,id) 
{
	var op = 100;
	document.getElementById('upperDiv').style.filter="alpha(opacity="+op+")";
	document.getElementById('upperDiv').style.MozOpacity = 1;
	
	proj1 = document.getElementById(pr).value;

	proj = proj1.trim();
	if(proj == "")
	{
		document.getElementById('topmostdiv').style.display='block';
		document.getElementById('upperDiv').style.backgroundColor='#FADAD8';
		document.getElementById('upperDiv').style.color='#FF0000';
		document.getElementById('upperDiv').style.display='block';
		document.getElementById('upperDiv').innerHTML="Project Name cannot be left blank !";
		return false;
	}
	else
	{
		document.getElementById('upperDiv').style.display='none';
		document.getElementById('update').style.display='none';
		document.getElementById(loader).style.display='block';
	}

	var xmlHttpReq = false;
	var self = this;
	if(window.XMLHttpRequest) 
	{
		self.xmlHttpReq = new XMLHttpRequest();
	}
	else if(window.ActiveXObject) 
	{
		self.xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
	}
	self.xmlHttpReq.open('POST', url, true);
	self.xmlHttpReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	self.xmlHttpReq.onreadystatechange = function() 
	{
		if(self.xmlHttpReq.readyState == 4) 
		{
			updatepage_edit(self.xmlHttpReq.responseText,spn);
			document.getElementById(loader).style.display='none';

			document.getElementById('topmostdiv').style.display='block';
			document.getElementById('upperDiv').style.backgroundColor='#DEFEE1';
			document.getElementById('upperDiv').style.color='#00802B';
			document.getElementById('upperDiv').style.display='block';
			document.getElementById('upperDiv').innerHTML="Project Name updated successfully";
		}
	 }
	self.xmlHttpReq.send(getquerystring_edit(pr,id));
}
function getquerystring_edit(pr,id)
{
	var qstr_edit;
	var prj = document.getElementById(pr).value;
	qstr_edit ='get_project_edit=' +escape(prj)+"@|@"+escape(id);
	return qstr_edit;
}
function updatepage_edit(str,spn)
{
	document.getElementById(spn).innerHTML = str;	 
}