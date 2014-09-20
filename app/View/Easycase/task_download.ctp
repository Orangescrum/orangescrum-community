<html>
<head>
	<title>Download Task</title>
<style type="text/css">
	body{
		font-family: "HelveticaNeue-Roman","HelveticaNeue","Helvetica Neue","Helvetica","Arial","sans-serif";
		line-height:1.42857;
	}
.dowonloadpopup{
   padding: 20px;
   margin-top:50px
}
.dwn-txt{
	font-size: 17px;
	padding: 5px;
}
.mail-dwn-btn .dwn-btn{
	background: none repeat scroll 0 0 #6FB3E0;
	color: #FFFFFF;
	font-size: 17px;
	margin-right: 15px;
	-moz-user-select: none;
	border: 1px solid rgba(0, 0, 0, 0);
	border-radius: 3px;
	cursor: pointer;
	display: inline-block;
	font-weight: normal;
	line-height: 1.42857;
	margin-bottom: 0;
	text-align: center;
	vertical-align: middle;
	white-space: nowrap;
}
a{
	color:  #6FB3E0;
	text-decoration: none;
}
a:hover{
	text-decoration: underline;
}
.success{
	font-size: 17px;
	text-align: center;
	color: #618F59;
}
.error{
	font-size: 17px;
	text-align: center;
	color: #EF0000;
}
</style>
<script type="text/javascript">
function sendDownloadTaskMail(obj){
	var url = HTTP_ROOT+'easycases/sendDownloadTaskMail';
	$.post(url,{"dwnldUrl":"<?php echo $downloadurl;?>",'projName':"<?php echo $projName;?>",'caseNum':"<?php echo $caseNum;?>",'taskTitle':"<?php echo $taskTitle;?>",'zipfile':"<?php echo $zipfilename;?>"},function(res){
		if(res=='Success'){
			$('#return_msg').removeClass('error').addClass('success');
			$('#return_msg').val('Mail send successfully.');
		}else{
			$('#return_msg').removeClass('success').addClass('error');
			$('#return_msg').val('Opps! We are not able to send email. Please try later.');
		}
		obj.close();
	});
}
function removemailMsg() {
	$('#return_msg').fadeOut(300);
}
</script>
</head>
<body>
<div class="dowonloadpopup">
<div id="return_msg" class="success"></div>	
<?php if($derror){?>
<div class="error"><?php echo $derror;?></div>
<?php }else{ ?>
<center>
	<div class="dwn-txt">Your download is ready click below to download</div>
	<!--<div class="dwn-txt">or</div>-->
	<div class="mail-dwn-btn"><a href="<?php echo $downloadurl;?>" id="download_task_link"><img style="height:60px;width:60px;" src="<?php echo HTTP_ROOT; ?>img/download.png" /></a></div>
	<!--<div class="dwn-txt"><a href="javascript:void(0);" id="sendemailtouser_btn"> Send to your Email Inbox</a></div>-->
</center>
<?php } ?>
</div>
</body>
</html>