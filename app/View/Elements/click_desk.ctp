<!-- ClickDesk Live Chat Service for websites -->
<?php
if((int)SHOW_CLICKDESK){
	$sesUsrArr = $this->Format->getUserDtls(SES_ID);
	if(count($sesUsrArr)) {
		$sesuser_fulname = $sesUsrArr['User']['name'].' '.$sesUsrArr['User']['last_name'];
		$sesuser_email = $sesUsrArr['User']['email'];
	}
?>
<script type="text/javascript">
$CLICKDESK = (function() {
	<?php if(isset($sesuser_fulname) && trim($sesuser_fulname)) { ?>
	CLICKDESK_Live_Chat.setName('<?php echo $sesuser_fulname; ?>');
	<?php }
	if(isset($sesuser_email) && trim($sesuser_email)) { ?>
	CLICKDESK_Live_Chat.setEmail ('<?php echo $sesuser_email ?>');
	<?php } ?>
});
var _glc =_glc || []; _glc.push('all_ag9zfmNsaWNrZGVza2NoYXRyDgsSBXVzZXJzGKu-pxUM');
var glcpath = (('https:' == document.location.protocol) ? 'https://my.clickdesk.com/clickdesk-ui/browser/' :
'http://my.clickdesk.com/clickdesk-ui/browser/');
var glcp = (('https:' == document.location.protocol) ? 'https://' : 'http://');
var glcspt = document.createElement('script'); glcspt.type = 'text/javascript';
glcspt.async = true; glcspt.src = glcpath + 'livechat-new.js';
var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(glcspt, s);
</script>
<!-- End of ClickDesk -->
<?php } ?>
