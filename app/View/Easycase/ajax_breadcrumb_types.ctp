<?php
$types = "";
if(isset($case_types) && $case_types && $case_types != "all")
{
	$case_types = strrev($case_types);
	if(strstr($case_types,"-"))
	{
		$expst3 = explode("-",$case_types);
		foreach($expst3 as $st3)
		{
			$types.= $this->Casequery->caseBcTypes($st3).", ";
		}
	}
	else
	{
		$types = $this->Casequery->caseBcTypes($case_types).", ";
	}
}
if($types) { echo "(".substr($types,0,-2).")"; } else { echo "All"; }
?>