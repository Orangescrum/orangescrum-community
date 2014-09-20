<?php
header("Content-Type: application/json; charset=utf-8");
echo $projUser;
die;
?>
<?php /*?><li class="pop_arrow_new"></li>
<?php
foreach($usrDtlsArr as $v) {
	//$t1 = $v['User']['short_name'];
	$t1 = $v['User']['name'];
	if($v['User']['id'] == SES_ID) {
		$t2 = 'me';
		$t = $v['User']['id']; ?>
	<li title="<?php echo $v['User']['name']; ?>" class="memHover" >
		<a href="javascript:void(0);" style="color:#E0814E" onclick="changeAssignTo('<?php echo $caseAutoId;?>', '<?php  echo $caseUniqId;?>','<?php  echo $t;?>')">me</a>
	</li>
	<?php }else {
		//$t2 = $v['User']['short_name'];
		$t2 = $v['User']['name'];
		$t = $v['User']['id']; ?>
		<li title="<?php echo $v['User']['name']; ?>" class="memHover" style="text-transform:capiltalize">
		<a href="javascript:void(0);" onclick="changeAssignTo('<?php echo $caseAutoId;?>', '<?php  echo $caseUniqId;?>','<?php  echo $t;?>')"><?php echo $this->Format->shortLength(ucfirst($t2),10); ?></a>
	</li>
<?php }
} ?><?php */?>