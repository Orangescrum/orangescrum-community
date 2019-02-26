<?php include_once("config.php"); ?>
<!DOCTYPE html>
<html>
<head>
	<title>Orangescrum :: Add-Ons Installer</title>
	<link rel="shortcut icon" href="images/favicon.ico"/>
	<link rel="stylesheet" href="css/style.css">
	<link href='//fonts.googleapis.com/css?family=Open+Sans:400,300italic,300,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
	<link href='//fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="keywords" content="File Upload widget Widget Responsive, Login Form Web Template, Flat Pricing Tables, Flat Drop-Downs, Sign-Up Web Templates, Flat Web Templates, Login Sign-up Responsive Web Template, Smartphone Compatible Web Template, Free Web Designs for Nokia, Samsung, LG, Sony Ericsson, Motorola Web Design" />
	<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>

		
</head>

<body>
<div class="ld_pop_mcnt" style="display:none;">
	<div class="loader_pop">
			<div>
				<div style="float: left">Please do not refresh the page while installation is being processed.</div>
				<div class="lds-ellipsis" ><div></div><div></div><div></div><div></div></div>
				<div style="clear: both"></div>
			</div>
	 </div>
</div>
<div class="logo_landing">
    <a href="https://www.orangescrum.org/" target="_blank">
    	<img src="images/Os-logo-outer.png" border="0" alt="Orangescrum" title="Orangescrum">
    </a>
</div>
<div class="agile-its">
	<div class="orangescrum">
		<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.
			<div><strong style="color: green;font-size: 14px;text-align: center;">Note : Please Choose AddonInstaller-V<?php echo NEWUI_VERSION;?>.zip Or ExecutiveDashboard-V<?php echo EXEUI_VERSION;?>.zip file</strong> </div>
		</p>
		<div class="photos-upload-view">
			<form id="upload" action="install.php" method="POST" enctype="multipart/form-data">
				<div class="custom_file">
					<label> Choose Your File
						<input type="file" id="addon_installer_id" name="addon_installer" />
					 </label> 
				</div>
				<div>
					<input type="submit" id="btn_upload" value="Upload" />
				</div>
			</form>
		</div>
		<div class="clearfix"></div>

	</div>
</div>
<div class="footer">
	<p> © 2011-<?php echo gmdate('Y');?> Orangescrum. <a href="http://www.andolasoft.com/" target="_blank" title="Andolasoft">Andolasoft</a></p>
</div>

<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.validate.min.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
        $("#upload").validate({
            rules:{
                addon_installer: {
                    required: true,
                }
            },

            messages:{
                addon_installer: {
                    required: "This field is required!",
                }
            },
            errorElement: "small",
            errorPlacement: function(error, element) {
                error.insertAfter(element);
            },
            submitHandler: function (form) {
            	$(".ld_pop_mcnt").show();
            	 var file = $("#addon_installer_id").val();
	             var extension = file.substr((file.lastIndexOf('.') +1));
	             if(extension!='zip'){
	             	alert('Please Choose Zip File');
	             	$(".ld_pop_mcnt").hide();
	             	return false;	
	             }
	             form.submit();
	         }
        });

    });
</script>

</div>
</body>
</html>