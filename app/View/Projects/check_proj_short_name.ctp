<?php
if(isset($count))
{
	if($count != 0)
	{
		$err_short = "<font color='red'>'".$shortname."' is already exists !</font>";
	}
	else
	{
		$err_short = "<font color='green'>'".$shortname."' is available !</font>";
	}
	echo $err_short;
}
?>