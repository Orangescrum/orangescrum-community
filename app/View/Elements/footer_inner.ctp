</div>
<span id="remember_filter" style="display:none;color:#666666"></span>
<footer <?php if(CONTROLLER == 'easycases' && PAGE_NAME == 'help'){ ?> class="help_footer" <?php } ?> id="footersection">
	<div class="col-lg-5 ft_lt_div" id="csTotalHours">
	</div>
	<div class="col-lg-2 text-centre ft_md_div">
	Orangescrum <?php echo VERSION; ?>
	</div>
	<div class="col-lg-5 text-right rht_ft_txt ft_rt_div" id="projectaccess">
	
	</div>
    
    <div style="clear:both"></div>
    <div style="text-align:center;padding:10px 0 0 0;">
    <a href="https://groups.google.com/forum/#!forum/orangescrum-community-support" target="_blank" style="margin:0;"><img src="<?php echo HTTP_ROOT."img/google_groups.jpg"; ?>" style="width:100px;"/></a>
    <br/>
    You can ask for help, share your ideas, contribute to the community edition and also let us know your feedback using the <a href="https://groups.google.com/forum/#!forum/orangescrum-community-support" target="_blank" style="margin:0;">Orangescrum's Google Group</a>.
    </div>
</footer>
<!-- Footer ends -->  

<script type="text/javascript">
var DOMAIN = '<?php echo DOMAIN; ?>'; //Domain
var HTTP_ROOT = '<?php echo HTTP_ROOT; ?>'; //pageurl
var HTTP_IMAGES = '<?php echo HTTP_IMAGES; ?>'; //hid_http_images
var MAX_FILE_SIZE = '<?php echo MAX_FILE_SIZE; ?>'; //fmaxilesize
var SES_ID = '<?php echo SES_ID; ?>'; //pub_show
var SES_TYPE = '<?php echo SES_TYPE; ?>';
var GLOBALS_TYPE = <?php echo json_encode($GLOBALS['TYPE']); ?>;
var DESK_NOTIFY = <?php echo (int)DESK_NOTIFY; ?>;
var CONTROLLER = '<?php echo CONTROLLER; ?>';
var PAGE_NAME = '<?php echo PAGE_NAME; ?>';
var ARC_CASE_PAGE_LIMIT = 10;
var ARC_FILE_PAGE_LIMIT = 10;
var PUSERS = <?php echo json_encode($GLOBALS['projUser']); ?>;
var PROJECTS = <?php echo json_encode($GLOBALS['getallproj']); ?>;
var defaultAssign = '<?php echo $defaultAssign; ?>';
var dassign;
var TASKTMPL = <?php echo json_encode($GLOBALS['getTmpl']); ?>;
var SITENAME = 'Orangescrum';
var TITLE_DLYUPD = '<?php echo "Daily Update - ".date("m/d"); ?>';
</script>

<script type="text/javascript" src="<?php echo JS_PATH; ?>os_core.js?v=<?php echo RELEASE; ?>"></script>
<?php if((CONTROLLER == 'templates') || (CONTROLLER == 'easycases' && PAGE_NAME == "mydashboard")){ ?>
<script type="text/javascript" src="<?php echo JS_PATH; ?>jquery-ui-1.10.3.js"></script>
<?php }else{ ?>
<!--<script type="text/javascript" src="<?php echo JS_PATH; ?>jquery-ui.min.1.8.10.js"></script>-->
<script type="text/javascript" src="<?php echo JS_PATH; ?>jquery-ui-1.9.2.custom.min.js"></script>
<?php } ?>
<script type="text/javascript" src="<?php echo JS_PATH; ?>script.js?v=<?php echo RELEASE; ?>"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>easycase_new.js?v=<?php echo RELEASE; ?>"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>jquery.tipsy.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>jquery.lazyload.min.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>tinymce/jquery.tinymce.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>tinymce/tiny_mce.js"></script>

<!-- Dropbox starts-->
<?php if(defined('USE_LOCAL') && USE_LOCAL==1) {?>
<script type="text/javascript" src="<?php echo JS_PATH; ?>dropins.js" id="dropboxjs" data-app-key="<?php echo DROPBOX_KEY;?>"></script>
<?php } else {?>
<script type="text/javascript" src="https://www.dropbox.com/static/api/1/dropins.js" id="dropboxjs" data-app-key="<?php echo DROPBOX_KEY;?>"></script>
<?php }?>
<!-- Dropbox ends-->

<!-- Google drive starts-->
<script type="text/javascript">
    var CLIENT_ID = "<?php echo CLIENT_ID; ?>";
    var REDIRECT = "<?php echo REDIRECT_URI; ?>";
    var API_KEY = "<?php echo API_KEY; ?>";
    var DOMAIN_COOKIE = "<?php echo DOMAIN_COOKIE; ?>";
</script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>google_drive.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>google_contact.js"></script>
<?php if(defined('USE_LOCAL') && USE_LOCAL==1) {?>
<script src="<?php echo JS_PATH; ?>jsapi.js"></script>
<script src="<?php echo JS_PATH; ?>client.js"></script>
<?php } else {?>
<script src="https://www.google.com/jsapi?key=<?php echo API_KEY; ?>"></script>
<script src="https://apis.google.com/js/client.js"></script>
<?php }?>
<!-- Google drive ends-->

<script type="text/javascript" src="<?php echo JS_PATH; ?>fileupload.js"></script>

<?php if(PAGE_NAME == "dashboard"){ ?>

<script type="text/javascript">
$(document).ready(function(){
	var pjuniq=$('#projFil').val();
	var url = "<?php echo HTTP_ROOT?>easycases/ajax_case_menu";
	loadCaseMenu(url,{"projUniq":pjuniq,"pageload":1,"page":"<?php echo PAGE_NAME; ?>","filters":"<?php echo $filters; ?>","case":"<?php echo $caseunid; ?>"}, 1);
});
</script>

<?php if(defined('NODEJS_HOST') && trim(NODEJS_HOST)){ ?>
<script src="<?php echo JS_PATH; ?>io.js"></script>
<script type="text/javascript">
var client;
function subscribeClient(){
	var prjuniqid = $("#CS_project_id").val();
	if(client && prjuniqid!='all'){
		client.emit('subscribeTo', { channel: prjuniqid });
		return;
	}
	
	var alltasks = new Array();
	try{
		client = io.connect('<?php echo NODEJS_HOST; ?>',{secure: <?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on')?"true":"false"; ?>});
		
		client.on('connect',function (data) {
			var prjuniqid = $("#CS_project_id").val();
			//alert('Joining client to: '+prjuniqid);
			if(prjuniqid!='all'){
				client.emit('subscribeTo', { channel: prjuniqid });
			}
		});
	
	
		client.on('iotoclient', function (data) {
			var message = data.message;//alert(message);
			var session_id = message.split('~~')[1];
			var msg = message.split('~~')[0];
			var caseNum = message.split('~~')[2];
			var caseTyp = message.split('~~')[3];
			var caseTtl = message.split('~~')[4];
			var projShName =  message.split('~~')[5];
			//var show_pub = $("#pub_show").val();
			
			if(session_id != SES_ID)          
			{
				var counter =$("#pub_counter").val();
				var casenumHid = $("#hid_casenum").val();
				if(casenumHid == '0') {
					alltasks = [];
				}
				
				//var index = alltasks.indexOf(caseNum);
				var index = $.inArray(caseNum, alltasks);
				
				if(index == -1) { //if the case number is not present
					alltasks.push(caseNum);
					$("#hid_casenum").val(alltasks);
					counter ++;
				} 
				
				if(counter == 1) {
					var tsk = "Task";
				} else {
					var tsk = "Tasks";
				}
				$("#punnubdiv").show();
				$("#pub_counter").val(counter);
				$('#pubnub_notf').html(counter+' '+ tsk +' '+msg);
				$("#pubnub_notf").slideDown("1000");
				//if (window.webkitNotifications) {
					notify(getImNotifyMsg(projShName, caseNum, caseTtl, caseTyp),'Orangescrum.com');
				//}
			}
			
		});
	} catch(e){ console.log('Socket ERROR\n'); console.log(e); }
}
</script>
<?php } else { ?>
<script type="text/javascript">
	function subscribeClient(){}
</script>
<?php } ?>

<?php }?>

<?php 
if(CONTROLLER == "templates" && (PAGE_NAME == "tasks" || PAGE_NAME == "projects")){
?>
<script type="text/javascript">
	$(document).ready(function(){
		$('#desc').tinymce({
			// Location of TinyMCE script
			script_url : '<?php echo HTTP_ROOT; ?>js/tinymce/tiny_mce.js',
			theme : "advanced",
			plugins : "paste",
			theme_advanced_buttons1 : "bold,italic,strikethrough,underline,|,numlist,bullist,|,indent,outdent",
			theme_advanced_resizing : false,
			theme_advanced_statusbar_location : "",
			paste_text_sticky : true,
			gecko_spellcheck : true,
			paste_text_sticky_default : true,
			forced_root_block : false,
			width : "650px",
			height : "200px",
		});
		$('#desc_edit').tinymce({
			// Location of TinyMCE script
			script_url : '<?php echo HTTP_ROOT; ?>js/tinymce/tiny_mce.js',
			theme : "advanced",
			plugins : "paste",
			theme_advanced_buttons1 : "bold,italic,strikethrough,underline,|,numlist,bullist,|,indent,outdent",
			theme_advanced_resizing : false,
			theme_advanced_statusbar_location : "",
			paste_text_sticky : true,
			gecko_spellcheck : true,
			paste_text_sticky_default : true,
			forced_root_block : false,
			width : "650px",
			height : "200px",
		});
	});
</script>
<?php
}
if(PAGE_NAME == "dashboard" || PAGE_NAME=='milestone' || (CONTROLLER == "archives" && PAGE_NAME == "listall") || PAGE_NAME=='milestonelist') {?>
<script type="text/javascript" src="<?php echo JS_PATH; ?>dashboard.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>jquery.prettyPhoto.js"></script>
<?php }
if(PAGE_NAME == "mydashboard" || PAGE_NAME=='milestone' || PAGE_NAME=='dashboard' || PAGE_NAME=='milestonelist') {?>
	<script type="text/javascript" src="<?php echo HTTP_ROOT;?>js/jquery/jquery.mousewheel.js"></script>
    <script type="text/javascript" src="<?php echo HTTP_ROOT;?>js/jquery/jquery.jscrollpane.min.js"></script>
<?php } ?>
<script type="text/javascript">
<?php	if(PAGE_NAME != "dashboard" && PAGE_NAME !='pricing' && PAGE_NAME !='onbording') {?>
	<?php if(CONTROLLER == "milestones" && PAGE_NAME == "manage") {?>
			var project = $("#projFil").val();
	<?php }else{?>
			var project = 'all';
	<?php } ?>
	$.post(HTTP_ROOT+"easycases/ajax_project_size",{"projUniq":project,"pageload":0}, function(data){
		 if(data){
			$('#csTotalHours').html(data.used_text);
			if(data.last_activity){
				$('#projectaccess').html(data.last_activity);
				$('#last_project_id').val(data.lastactivity_proj_id);
				$('#last_project_uniqid').val(data.lastactivity_proj_uid);
				var url=document.URL.trim();
				if(isNaN(url.substr(url.lastIndexOf('/')+1)) && (url.substr(url.lastIndexOf('/')+1)).length != 32){
					$('#selproject').val($('#last_project_id').val());
					$('#project_id').val($('#last_project_id').val());
				}
		<?php if(CONTROLLER == "milestones" && PAGE_NAME == "add" && !$milearr['Milestone']['project_id']){	?>
					$('#selproject').val(data.lastactivity_proj_id);
					$('#project_id').val(data.lastactivity_proj_id);
		<?php }	?>
			}
		  }
		},'json');
<?php }
if(!$this->Format->isiPad()) { ?>
$(function(){
	checkuserlogin();
});
<?php } ?>
$(function(){
	
	$(".more_in_menu").parent("li").click(function(){
		if($(".more_menu_li").css("display")=="none"){
			$(".more_menu_li").css({display:"block"});
			$(this).children("a.more_in_menu").text("Less");
			$(this).addClass("open");
			$(".cust_rec").css({display:"none"});
		}
		else{
			$(".more_menu_li").css({display:"none"});
			$(this).children("a.more_in_menu").text("More");
			$(this).removeClass("open");
			$(".cust_rec").css({display:"block"});
		}
	});
	
	
	$('[rel=tooltip]').tipsy({gravity:'s', fade:true});
	$(".scrollTop").click(function(){
		$('html, body').animate({ scrollTop: 0 }, 1200);
	});
	$('body').click(function() {
		$(".tipsy").remove();
	 });
});

function showhelp(){
	openPopup();
	$('.popup_bg').css({'width':'700px'});
	$('.loader_dv').hide();
	$('.help_popup').show();
}
</script>

<?php if(PAGE_NAME == "profile") {?>
    <script type="text/javascript" src="<?php echo JS_PATH;?>scripts/jquery.imgareaselect.pack.js"></script>
<?php } ?>

<script type="text/javascript" src="<?php echo JS_PATH;?>jquery.fileupload.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH;?>jquery.fileupload-ui.js"></script>

<!-- For multi autocomplete and tagging -->
<script type="text/javascript" src="<?php echo JS_PATH;?>jquery.fcbkcomplete.js"></script>

<?php /*?>Moved from Create New project ajax request page<?php */?>
<script type="text/javascript" src="<?php echo JS_PATH;?>wiki.js?v=<?php echo RELEASE; ?>"></script>
<script type="text/javascript" src="<?php echo JS_PATH;?>jquery.textarea-expander.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>highcharts.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>exporting.js"></script>
<style>
    #holder_detl { border: 4px dashed #F8F81E;padding: 8px;height:85px;background: #F0F0F0;}
    #holder_detl.hover { border: 4px dashed #0c0; }
</style>
