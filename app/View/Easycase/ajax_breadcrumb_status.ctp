<?php
$status = "";
if(isset($case_status) && $case_status && $case_status != "all")
{
	$case_status = strrev($case_status);
	if(strstr($case_status,"-"))
	{
		$expst = explode("-",$case_status);
		foreach($expst as $st)
		{
			$status.= $this->Format->displayStatus($st).", ";
		}
	}
	else
	{
		$status = $this->Format->displayStatus($case_status).", ";
	}
}
if($status) { echo "(".substr($status,0,-2).")"; } else { echo "All"; }
?>