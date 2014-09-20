<table cellpadding="0" cellspacing="0" border="1">
	<tr>
		<td align="left">
			Showing tasks of&nbsp;&nbsp;
		</td>
		<td align="left">
			<?php
			$jsSearch = "";
			$status = "";
			if(isset($case_status) && $case_status && $case_status != "all")
			{
				$resVal = 1;
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
			if($status) { echo substr($status,0,-2); } else { echo "All"; }
			?>
			&nbsp;
		</td>
		<td align="left">
			<a href="javascript:jsVoid();" id="statusRefresh-root" onclick="showHideDropDown('statusRefresh')">Status</a>
			<div id="statusRefresh" class="slide_menu"></div>,&nbsp;
		</td>
		<td align="left">
			<?php
			$types = "";
			if(isset($case_types) && $case_types && $case_types != "all")
			{
				$resVal = 1;
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
			if($types) { echo substr($types,0,-2); } else { echo "All"; }
			?>
			&nbsp;
		</td>
		<td align="left">
			<a href="javascript:jsVoid();" id="statusTypes-root" onclick="showHideDropDown('statusTypes')">Types</a>
			<div id="statusTypes" class="slide_menu"></div>,&nbsp;
		</td>
		<td align="left">
			<?php
			$pri = "";
			if(isset($pri_fil) && ($pri_fil || $pri_fil == 0) && $pri_fil != "" && $pri_fil != "all")
			{
				$resVal = 1; $pname = ""; $pname1 = "";
				$pri_fil = strrev($pri_fil);
				if(strstr($pri_fil,"-"))
				{
					$expst2 = explode("-",$pri_fil);
					foreach($expst2 as $st2)
					{
						if($st2 == 0) { $pname1 = "High"; } elseif($st2 == 1) { $pname1 = "Medium"; } else { $pname1 = "Low"; };
						//$pname = $pname.$pname1;
						$pri.= $pname1.", ";
					}
				}
				else
				{
					if($pri_fil == 0) { $pname1 = "High"; } elseif($pri_fil == 1) { $pname1 = "Medium"; } else { $pname1 = "Low"; };
					$pri = $pname1.", ";
				}
			}
			if($pri) { echo substr($pri,0,-2); } else { echo "All"; }
			?>
			&nbsp;
		</td>
		<td align="left">
			<a href="javascript:jsVoid();" id="prioritRefresh-root" onclick="showHideDropDown('prioritRefresh')">Priority</a>
			<div id="prioritRefresh" class="slide_menu"></div>,&nbsp;
		</td>
		<td align="left">
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
			if($mems) { echo substr($mems,0,-2); } else { echo "All"; }
			?>
			&nbsp;
		</td>
		<td align="left">
			<a href="javascript:jsVoid();" id="csMemAjx-root" onclick="showHideDropDown('csMemAjx')">Members</a>
			<div id="csMemAjx" class="slide_menu"></div>
		</td>
		<td align="left">
			<?php
			if(isset($case_page) && $case_page && $case_page != 1)
			{
				$resVal = 1;
				echo ",&nbsp;Page: <i>".$case_page."</i>";
			}
			?>
		</td>
		<td align="left">
			<?php
			if(isset($case_search) && $case_search != "")
			{
				$resVal = 1;
				echo ",&nbsp;Search: <i>".$case_search."</i>";
				$jsSearch = "setSearchValue();";
			}
			if(isset($resVal) && $resVal)
			{
			?>
				&nbsp;&nbsp;&nbsp;<a href="javascript:jsVoid();" class="button-link" rel="tooltip" title="Reset Filters" onClick="<?php echo $jsSearch; ?>resetAllFilters();ajaxCaseView('case_project.php');" style="padding:0px 4px;font-size:11px;">Reset</a>
			<?php
			}
			?>
		</td>
	</tr>
</table>