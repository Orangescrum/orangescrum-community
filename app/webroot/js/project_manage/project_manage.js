function submitProject(proj,shrt)
{
	document.getElementById('topmostdiv').style.display='block';
	var op = 100;
	document.getElementById('upperDiv').style.filter="alpha(opacity="+op+")";
	document.getElementById('upperDiv').style.MozOpacity = 1;
	var done=1;

	if(document.getElementById(proj).value.trim() == "")
	{
		var msg="'Project Name' cannot be left blank!";
		document.getElementById(proj).focus();
		done=0;
	}
	if(document.getElementById(shrt).value.trim() == "")
	{
		var msg="'Project ShortName' cannot be left blank!";
		document.getElementById(shrt).focus();
		done=0;
	}
	if(done == 1)
	{
		document.getElementById('upperDiv').style.display='none';
		return true;
	}
	else
	{
		document.getElementById('upperDiv').style.display='block';
		document.getElementById('upperDiv').style.backgroundColor='#FADAD8';
		document.getElementById('upperDiv').style.color='#FF0000';
		document.getElementById('upperDiv').value='';
		document.getElementById('upperDiv').innerHTML=msg;
		return false;
	}
}
function projectEditOver(a,b)
{
	if(document.getElementById(a).style.display == 'none')
	{
		document.getElementById(b).style.display='block';
	}
	if(a == "proj_edit1")
	{
		document.getElementById(b).style.textDecoration='underline';
	}
}
function projectEditOut(a,b)
{
	document.getElementById(b).style.display='none';
	if(a == "proj_edit1")
	{
		document.getElementById(b).style.textDecoration='none';
	}
}
function showProj(a,b,c,d)
{
	document.getElementById(a).style.display='none';
	document.getElementById(b).style.display='block';
	document.getElementById(c).style.display='none';
	document.getElementById(d).style.display='block';
}
function hideProject(a,b,c,d)
{
	document.getElementById(a).style.display='block';
	document.getElementById(b).style.display='none';
	document.getElementById(c).style.display='block';
	document.getElementById(d).style.display='none';
	
	var projNm = document.getElementById('hidPrj').value;
	document.getElementById('txt_proj').value = projNm;
}