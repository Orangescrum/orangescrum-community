function ajax_add_Technology(strURL,id) 
{
	
	
	var tech = document.getElementById(id).value;
	 var done=1;
	if(tech == "")
	{
		 var err ="'Technology' can't be left blank!";
		 done=0;
	}
	if(done == 0)
	{
		document.getElementById('errorTechnology').innerHTML=err;
		return false;
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
    self.xmlHttpReq.open('post', strURL, true);
    self.xmlHttpReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    self.xmlHttpReq.onreadystatechange = function() 
	{
        if(self.xmlHttpReq.readyState == 4)
		{
            getResponsetech(self.xmlHttpReq.responseText);
			cover_close('cover','inner_addTechnology');
			//document.getElementById('upperDiv').style.display='block';
			//document.getElementById('upperDiv').style.color='#00802B';
			//document.getElementById('upperDiv').style.backgroundColor='#DEFEE1';
			//document.getElementById('upperDiv').innerHTML="Technology Added Successfully";
			
        }
	 }
    self.xmlHttpReq.send(sendValuetech(tech));
}

function sendValuetech(tech)
{
	var qstr;
	qstr ='getresponsetech='+escape(tech);
	return qstr;
}
function getResponsetech(str)
{
	document.getElementById('add_tech').innerHTML = str;
}

