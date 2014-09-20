<?php
if(isset($allCases) && count($allCases))
{
	if($this->Format->getBrowser() == "C")
	{
		$pjShrtNm = "";
		$usrShrtNm = "";
		foreach($allCases as $case)
		{
			$easycase = $this->Casequery->getCaseNotification($case['CaseUserView']['easycase_id']);
			if(count($easycase)){
				$pjArr = $this->Casequery->getProjectShortName($easycase['Easycase']['project_id']);
				if(count($pjArr))
				{
					$pjShrtNm = $pjArr['Project']['short_name'];
					$userArr = $this->Casequery->getUserDtls($easycase['Easycase']['user_id']);
					if(count($userArr)){
						$usrShrtNm = $userArr['User']['name'];
					}
				}
			}
			if($pjShrtNm && $usrShrtNm) {
				$title = "New task from ".$usrShrtNm;
				$caseTitle = $easycase['Easycase']['title'];
				$caseTitle = $this->Format->formatText($caseTitle);
				$caseTitle = html_entity_decode($caseTitle, ENT_QUOTES);
				
				//$title = urlencode($title);
				//$caseTitle = urlencode($caseTitle);
				?>
				
				<script>notification('<?php echo $title; ?>','<?php echo $caseTitle; ?>','<?php echo HTTP_ROOT; ?>','<?php echo $case['CaseUserView']['id']; ?>')</script>
				<table cellpadding="0" cellspacing="0" border="0" width="100%" style="font-size:13px;cursor:pointer;font-family:'PT Sans', Arial, sans-serif">
					<tr>
						<td valign="top">
							<a href="<?php echo HTTP_ROOT; ?>dashboard/?project=<?php echo $pjArr['Project']['uniq_id']; ?>&case=<?php echo $easycase['Easycase']['uniq_id']; ?>" target="_blank" style="text-decoration:none;color:#000000;"><img src="<?php echo HTTP_IMAGES;?>images/logo_notification.png" alt="Orangescrum" title="Orangescrum" border="0"/></a>
						</td>
						<td valign="top">
							<a href="<?php echo HTTP_ROOT; ?>dashboard/?project=<?php echo $pjArr['Project']['uniq_id']; ?>&case=<?php echo $easycase['Easycase']['uniq_id']; ?>" target="_blank" style="text-decoration:none;color:#000000">
								<b><font color='#253743'><?php echo $title; ?></font></b><br/>
								<b><?php  echo $pjShrtNm." - ".$easycase['Easycase']['case_no']; ?>: </b><?php echo $caseTitle; ?></i>
							</a>
						</td>
					</tr>
				</table>
				<?php
				//$this->Casequery->completeNotification($case['CaseUserView']['id']);
			}
		}
	}
}
?>
