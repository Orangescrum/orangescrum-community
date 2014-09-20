<?php
header("Content-Type: application/json; charset=utf-8");
echo $result;
die;
?>
<!--[if lte IE 9]>
    <style>
        #chked_all{top:2px!important;}
    </style>	
<![endif]-->
<?php
if(isset($quickMem) && is_array($quickMem)&& count($quickMem)){?>
<script>
	$(function () {
		$('[rel=tooltip], #main-nav span, .loader').tipsy({gravity:'s', fade:true});
	});
</script>
<div class="fl lbl-m-wid">Notify via Email:</div>
<div class="col-lg-9 fl rht-con email">
	<input type="checkbox" name="chk_all" id="chked_all"  value="all" onClick="checkedAllRes()">&nbsp;ALL
	<div  id="viewmemdtls">
<?php 
	$i = 0; $j = 0; $k = 0; $chked = "";
	foreach($quickMem as $getmems){	
		$j = $i%3;
		$chked ='';
		if(isset($dassign) && in_array($getmems['User']['id'],$dassign)){
			$chked = "checked='checked'";
		}elseif((isset($defaultAssign) && ($getmems['User']['id'] == $defaultAssign) && ($getmems['User']['id'] != SES_ID))){
			$chked = "checked='checked'";
		}else if(!$defaultAssign && ($getmems['User']['id'] == SES_ID) || ($getmems['ProjectUser']['default_email'] == 1)){
			$chked =  "checked='checked'";
		}
		?>
		<div class="viewmemdtls_cls fl">
			<input type="checkbox" name="data[Easycase][user_emails][]" id="chk_<?php echo $getmems['User']['id'];?>" class="notify_cls fl" value="<?php echo $getmems['User']['id']?>" onClick="removeAll()" <?php echo $chked;?> />&nbsp;<div class="fl user_email" style="padding-left:6px;" title="<?php echo $getmems['User']['name']; ?>" ><?php echo $this->Format->shortLength($getmems['User']['name'],20); ?>&nbsp;&nbsp;</div>
		</div>
		<?php
			$i = $i+1;$k = $i%3;
			if($k == 0){?>
			<div class="cb"></div>
		<?php }
		}?>		
	</div>
	<div class="cb"></div>
	<input type="hidden" name="hidtotproj" id="hidtotproj" value="<?php echo $i?>" readonly="true">
</div>
<?php }?>