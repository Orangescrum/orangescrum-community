</div>             
<?php
if(CONTROLLER == "osadmins" && (PAGE_NAME == "manage_company" || PAGE_NAME=='admin_betauser' || PAGE_NAME == "deleted_company")){?>
<link rel="stylesheet"  type="text/css" href="<?php echo HTTP_ROOT; ?>css/demo_page.css">
<link rel="stylesheet"  type="text/css" href="<?php echo HTTP_ROOT; ?>css/demo_table.css">
<link rel="stylesheet"  type="text/css" href="<?php echo HTTP_ROOT; ?>css/betauser.css">
<script type="text/javascript" src="<?php echo JS_PATH.'datatable.js';?>"></script>
<script type="text/javascript" src="<?php echo JS_PATH.'betauser.js';?>"></script>
<?php } 

if(CONTROLLER == "osadmins" && PAGE_NAME == "company_user_details"){?>
<link rel="stylesheet"  type="text/css" href="<?php echo HTTP_ROOT; ?>css/demo_page.css">
<link rel="stylesheet"  type="text/css" href="<?php echo HTTP_ROOT; ?>css/demo_table.css">
<link rel="stylesheet"  type="text/css" href="<?php echo HTTP_ROOT; ?>css/betauser.css">
<script type="text/javascript" src="<?php echo JS_PATH.'datatable.js';?>"></script>
<script type="text/javascript" src="<?php echo JS_PATH.'betauser.js';?>"></script>
<script>
$(document).ready(function() {
  $('#example').dataTable({
	"aLengthMenu": [[10, 50, 100], [10,50, 100]],
	"iDisplayLength": 10,
	"iDisplayStart" : 0,
	"aaSorting": [[ 0, "asc" ]],
        "sPaginationType": "full_numbers",
	"aoColumns": [ 
			null,
			null,
			null,
			null,
			null,
			null,
			null
			
		    ]
	});
} );
</script>
<?php } 
if(CONTROLLER == "osadmins" && PAGE_NAME == "company_project_details"){?>
<link rel="stylesheet"  type="text/css" href="<?php echo HTTP_ROOT; ?>css/demo_page.css">
<link rel="stylesheet"  type="text/css" href="<?php echo HTTP_ROOT; ?>css/demo_table.css">
<link rel="stylesheet"  type="text/css" href="<?php echo HTTP_ROOT; ?>css/betauser.css">
<script type="text/javascript" src="<?php echo JS_PATH.'datatable.js';?>"></script>
<script type="text/javascript" src="<?php echo JS_PATH.'betauser.js';?>"></script>
<script>
$(document).ready(function() {
   $('#example').dataTable({
	"aLengthMenu": [[10, 50, 100], [10,50, 100]],
	"iDisplayLength": 10,
	"iDisplayStart" : 0,
	"aaSorting": [[ 0, "asc" ]],
        "sPaginationType": "full_numbers",
	"aoColumns": [ 
			null,
			null,
			null,
			null,
			null,
			null	
		    ]
	});
} );
</script>
<?php } ?>
<script type="text/javascript">
$(document).keydown(function(e) {
	var nofocus = 0;
	if(e.keyCode == 27) {
		hide_popoup();
		disablePopup();
	}
});
$(".popup_link").click(function(){
	if($(this).next(".popup").is(":visible")){
		$(".popup_option").hide();
		$(".popup").hide();
		$(this).next(".popup").hide();
	}
	else{
		$(".popup_option").hide();
		$(".popup").hide();
		$(this).next(".popup").show();
	}
});
</script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>jquery/jquery-ui.min.1.8.10.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>common_inner.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>easycase_new.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>jquery/jquery.tipsy.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>script.jquery.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>jquery.lazyload.min.js"></script>
<div style="clear:both"></div>
<div class="admin_outer ft_btm_bg">
</div>
