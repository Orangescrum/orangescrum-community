<?php
$mems = "";
if(isset($case_member) && $case_member && $case_member != "all")
{
	$resVal = 1;
	if(strstr($case_member,"-"))
	{
		$expst4 = explode("-",$case_member);
		foreach($expst4 as $st4)
		{
			$mems.= $this->Casequery->caseBcMems($st4).", ";
		}
	}
	else
	{
		$mems = $this->Casequery->caseBcMems($case_member).", ";
	}
}
if($mems) { echo "(".substr($mems,0,-2).")"; } else { echo "All"; }
?>