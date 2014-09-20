<?php
$isExceed = 0; $usedspace = 0;
if($user_subscription['storage'] != "Unlimited") {
	$usedspace = $this->Casequery->usedSpace();
	if($usedspace >= $user_subscription['storage']) {
		$isExceed = 1;
	}
}
?>
<input type="hidden" id="storageusedqc" value="<?php echo $usedspace; ?>">
<input type="hidden" id="isExceed" value="<?php echo $isExceed; ?>">
<?php
exit;
?>
