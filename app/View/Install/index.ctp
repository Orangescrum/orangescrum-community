<div class="install-wrapper">
    <h2 class="welcome"><?php echo __("Welcome to OrangeScrum Community version Installation wizard"); ?></h2>
    <hr />
<?php 
    if($addon != '' && in_array($addon, array_keys($addonName))){ ?>
    <div class="steps">
        <?php echo __('Make sure to copy the downloaded zip file to the "app/webroot" folder before starting installation. Please ignore if it is already done.');?>
    </div>
    <div class="meter orange">
        <span style="width:0%"></span>
    </div>
    <div class="step" id="first_step">
        <p class="valid-nm"><?php echo __('Please click on start button to start')." $addonName[$addon] ".__('add-on installation procedure.'); ?></p>
        <div class="import_btn_div fl" style="width: 100%;height: 60px;">
            <img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="<?php echo __("Loading"); ?>..." title="<?php echo __("Loading"); ?>..."  id="loader_img_tt" style="display: none;position: absolute;"/>
            <button type="button" id="tt_save_btn" name="tt_save_btn" class="btn btn_blue" onclick="checkInstall()">
                <i class="icon-big-tick"></i>
                <span style="color: #fff;"><?php echo __("Start"); ?></span>
            </button>
        </div>
    </div>
    <div class="step" id="second_step">
        <p class="valid-nm"></p>
        <div class="import_btn_div fl" style="width: 100%;height: 60px;">
            <img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="<?php echo __("Loading"); ?>..." title="<?php echo __("Loading"); ?>..."  id="loader_img_tt1" style="display: none;position: absolute;"/>
            <button type="button" id="tt_save_btn1" name="tt_save_btn" class="btn btn_blue" onclick="importDB()">
                <i class="icon-big-tick"></i>
                <span style="color: #fff;"><?php echo __("Next"); ?></span>
            </button>
        </div>
    </div>
    <div class="step" id="third_step">
        <p class="valid-nm"></p>
        <div class="import_btn_div fl" style="width: 100%;height: 60px;">
            <img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="<?php echo __("Loading"); ?>..." title="<?php echo __("Loading"); ?>..."  id="loader_img_tt2" style="display: none;position: absolute;"/>
            <button type="button" id="tt_save_btn2" name="tt_save_btn" class="btn btn_blue" onclick="copyAddonFolder()">
                <i class="icon-big-tick"></i>
                <span style="color: #fff;"><?php echo __("Next"); ?></span>
            </button>
        </div>
    </div>
    <div class="step" id="fourth_step">
        <p class="valid-nm"></p>
        <div class="import_btn_div fl" style="width: 100%;height: 60px;">
            <img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="<?php echo __("Loading"); ?>..." title="<?php echo __("Loading"); ?>..."  id="loader_img_tt3" style="display: none;position: absolute;"/>
            <button type="button" id="tt_save_btn3" name="tt_save_btn" class="btn btn_blue" onclick="verifyAddonInstalled()">
                <i class="icon-big-tick"></i>
                <span style="color: #fff;"><?php echo __("Verify"); ?></span>
            </button>
        </div>
    </div>
    <div class="step" id="fifth_step">
        <p class="valid-nm"></p>
        <div class="import_btn_div fl" style="width: 100%;height: 60px;">
            <img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="<?php echo __("Loading"); ?>..." title="<?php echo __("Loading"); ?>..."  id="loader_img_tt4" style="display: none;position: absolute;"/>
            <button type="button" id="tt_save_btn4" name="tt_save_btn" class="btn btn_blue" onclick="finishAddonInstalled()">
                <i class="icon-big-tick"></i>
                <span style="color: #fff;"><?php echo __("Finish"); ?></span>
            </button>
        </div>
    </div>
    <?php }else{ ?>
        <h2 class="invalid-nm"><?php echo __("Please provide a valid add-on name in the URL as shown in the installation guide to install that add-on."); ?></h2>
        <a href="http://www.orangescrum.org/how-to-install-timelog-addon-in-orangescrum" class="guide-link"><?php echo __("Go to Installation guide."); ?></a>
    <?php }
?>
</div>
<script type="text/javascript">
    $(function(){
        $('#first_step').show();
        $(".meter > span").each(function() {
            $(this).width('0%').animate({
                width: '0%'
            }, 1200); 
        });
    });
function checkInstall(){
    $('#tt_save_btn').hide();
    $('#loader_img_tt').show();
    var url = HTTP_ROOT+'Install/check_installation';
    var addon = '<?php echo $addon; ?>';
    $.post(url, {addon:addon}, function(res){
        if(res.error){
            $('#loader_img_tt').hide();
            $('#tt_save_btn').show();
            $('#first_step p').text(res.msg);
        }else if(res.success){
            $('.meter').show();
            $(".meter > span").each(function() {
                $(this).width('0%').animate({
                    width: '25%'
                }, 1200); 
            });
            $('.step').hide();
            $('#second_step').show();
            $('#second_step p').text(res.msg);
        }
    }, 'json');
}
function importDB(){
    $('#tt_save_btn1').hide();
    $('#loader_img_tt1').show();
    var url = HTTP_ROOT+'Install/import_addonTable';
    var addon = '<?php echo $addon; ?>';
    $.post(url, {addon:addon}, function(res){
        if(res.error){
            $('#loader_img_tt1').hide();
            $('#tt_save_btn1').show();
            $('#second_step p').text(res.msg);
        }else if(res.success){
            $(".meter > span").each(function() {
                $(this).width('25%').animate({
                    width: '50%'
                }, 1200); 
            });
            $('.step').hide();
            $('#third_step').show();
            $('#third_step p').text(res.msg);
        }
    }, 'json');
}
function copyAddonFolder(){
    $('#tt_save_btn2').hide();
    $('#loader_img_tt2').show();
    var url = HTTP_ROOT+'Install/copy_addonFolder';
    var addon = '<?php echo $addon; ?>';
    $.post(url, {addon:addon}, function(res){
        if(res.error){
            $('#loader_img_tt2').hide();
            $('#tt_save_btn2').show();
            $('#third_step p').text(res.msg);
        }else if(res.success){
            $(".meter > span").each(function() {
                $(this).width('50%').animate({
                    width: '75%'
                }, 1200); 
            });
            $('.step').hide();
            $('#fourth_step').show();
            $('#fourth_step p').text(res.msg);
        }
    }, 'json');
}
function verifyAddonInstalled(){
    $('#tt_save_btn3').hide();
    $('#loader_img_tt3').show();
    var url = HTTP_ROOT+'Install/verify_addonInstalled';
    var addon = '<?php echo $addon; ?>';
    $.post(url, {addon:addon}, function(res){
        if(res.error){
            $('#loader_img_tt3').hide();
            $('#tt_save_btn3').show();
            $('#fourth_step p').text(res.msg);
        }else if(res.success){
            $(".meter > span").each(function() {
                $(this).width('75%').animate({
                    width: '100%'
                }, 1200); 
            });
            $('.step').hide();
            $('#fifth_step').show();
            $('#fifth_step p').text(res.msg);
        }
    }, 'json');
}
function finishAddonInstalled(){
    $('#tt_save_btn3').hide();
    $('#loader_img_tt3').show();
    var addon = '<?php echo $addon; ?>';
    switch(addon){
        case 'timelog':
        case 'timelogpayment':
        case 'timeloggold':
            window.location = HTTP_ROOT+'timelog';break;
        case 'invoice':
            if(TLG && TLG == 1){
                window.location = HTTP_ROOT+'invoice';break;
            }else{
                window.location = HTTP_ROOT+'invoice#invoice';break;
            }
        case 'taskstatusgroup':
            window.location = HTTP_ROOT+'Task-Status-Group';break;
            //window.location = HTTP_ROOT+'taskstatusgroup/Workflows/workflow';break;
        case 'api':
            window.location = HTTP_ROOT+'api-settings';break;
        case 'ganttchart':
            window.location = HTTP_ROOT+'gantt-chart';break;
		case 'projecttemplate':
			window.location = HTTP_ROOT+'projecttemplate/ProjectTemplates/projects';break;
        case 'clientmanagement':
            window.location = HTTP_ROOT+'clientrestriction/ClientRestriction/settings';break;
		case 'mobileapi':
			window.location = HTTP_ROOT+'my-company';break;
        default :
            window.location = HTTP_ROOT+'dashboard#tasks';
    }
}

</script>