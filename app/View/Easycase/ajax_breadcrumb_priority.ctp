<?php
$pri = "";
if(isset($pri_fil) && $pri_fil && $pri_fil != "all")
{
	if(strstr($pri_fil,"-"))
	{
		$expst2 = explode("-",$pri_fil);
		foreach($expst2 as $st2)
		{
			$pri.= $st2.", ";
		}
	}
	else
	{
		$pri = $pri_fil.", ";
	}
}
if(trim($pri) != "All," && trim($pri) != "") { echo "(".substr($pri,0,-2).")"; } else { echo "All"; }
?>