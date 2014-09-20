<?php
if(isset($projUsrViewArr) && count($projUsrViewArr))
{
	$v = 0;
	$viewStatusMsg = "";
	foreach($projUsrViewArr as $prjArr)
	{
		$totCuv = $this->Casequery->caseViewData($prjArr['Project']['id'],"new");
		if($totCuv)
		{
			if($prjArr['Project']['name'])
			{
				$v++;
				if($totCuv == 1) { $cstxt = "task"; } else { $cstxt = "tasks"; }
				$viewStatusMsg.= "&nbsp;<b>".$totCuv."</b> new ".$cstxt." on <a href='javascript:jsVoid();' onClick=\"updateAllProj('proj_".$prjArr['Project']['uniq_id']."','".$prjArr['Project']['uniq_id']."','".$page."');\" style='text-decoration:underline;'>".$this->Format->shortLength($prjArr['Project']['name'],18)."</a>,";
			}
		}
	}
	if($v >= 1)
	{
		echo "You have".substr($viewStatusMsg,0,-1);
	}
}
?>