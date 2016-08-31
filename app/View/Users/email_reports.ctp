<div class="user_profile_con email_rpt">
    <!--Tabs section starts -->
    <?php echo $this->element("personal_settings");?>

    <div class="email_hd">
        <h2 style="">Send me Email Reports</h2>
    </div>
    <?php echo $this->Form->create('UserNotification',array('url'=>'/users/email_reports','onsubmit'=>"return validateemailrpt();")); ?>
    <input type="hidden" name="data[UserNotification][csrftoken]" class="csrftoken" readonly="true" value="" />
    <table cellspacing="0" cellpadding="0" class="email_mgt">
        <input type="hidden" name="data[UserNotification][id]" value="<?php echo @$getAllNot['UserNotification']['id']; ?>"/>
        <input type="hidden" name="data[UserNotification][type]" value="1"/>
        <tbody>
            <?php if(SES_TYPE<3) {?>
            <tr>
                <th>Weekly Usage:</th>
                <td>
                    <input type="radio" name="data[UserNotification][weekly_usage_alert]" id="wkugalyes" value="1" <?php if(@$getAllNot['UserNotification']['weekly_usage_alert'] == 1) {
                            echo 'checked="checked"';
                               } ?> />Yes
                    <input type="radio" name="data[UserNotification][weekly_usage_alert]" id="wkugalno" value="0" <?php if(@$getAllNot['UserNotification']['weekly_usage_alert'] == 0) {
                            echo 'checked="checked"';
                               } ?> />No
                </td>
            </tr>
                <?php } ?>
            <tr>
                <th>Task Status:</th>
                <td>
                    <input type="radio" name="data[UserNotification][value]" id="valdaily" value="1" <?php if(@$getAllNot['UserNotification']['value'] == 1) {
                        echo 'checked="checked"';
                           } ?> />Daily
                    <input type="radio" name="data[UserNotification][value]" id="valweekly" value="2" <?php if(@$getAllNot['UserNotification']['value'] == 2) {
                        echo 'checked="checked"';
                           } ?> />Weekly
                    <input type="radio" name="data[UserNotification][value]" id="valmonthly" value="3" <?php if(@$getAllNot['UserNotification']['value'] == 3) {
                        echo 'checked="checked"';
                           } ?> />Monthly
                    <input type="radio" name="data[UserNotification][value]" id="valnone" value="0" <?php if(@$getAllNot['UserNotification']['value'] == 0) {
                        echo 'checked="checked"';
                           } ?> />None
                </td>
            </tr>
            <tr>
                <th class="last">Task Due (daily):</th>
                <td class="last">
                    <input type="radio" name="data[UserNotification][due_val]" id="dueyes" value="1" <?php if(@$getAllNot['UserNotification']['due_val'] == 1) {
                        echo 'checked="checked"';
                           } ?> />Yes
                    <input type="radio" name="data[UserNotification][due_val]" id="dueno" value="0" <?php if(@$getAllNot['UserNotification']['due_val'] == 0) {
                        echo 'checked="checked"';
                           } ?> />No
                </td>
            </tr>
        </tbody>
    </table>
    <div class="cbt"></div>
    <div class="email_hd">
        <h2>Daily Update Report</h2>
    </div>
    <table cellspacing="0" cellpadding="0" class="email_mgt">
        <tbody>
            <tr>
                <th>Send me Email:</th>
                <td>
                    <input type="radio" name="data[DailyupdateNotification][dly_update]"  id="dlyupdateyes" value="1" <?php if(@$getAllDailyupdateNot['DailyupdateNotification']['dly_update'] == 1) {
                        echo 'checked="checked"';
                           } ?> onClick="showbox('show')" />Yes
                    <input type="radio" name="data[DailyupdateNotification][dly_update]"  id="dlyupdateno" value="0" <?php if(@$getAllDailyupdateNot['DailyupdateNotification']['dly_update'] == 0) {
                        echo 'checked="checked"';
                           } ?> onClick="showbox('hide')"/>No
                </td>
            </tr>
            <?php
            if(@$getAllDailyupdateNot['DailyupdateNotification']['dly_update'] == 1) {
                $style = '';
                $hr_min = split(':',$getAllDailyupdateNot['DailyupdateNotification']['notification_time']);
            }
            else
                $style = 'style="display:none"';
            ?>
            <tr <?php echo $style; ?> id="dlyupdt">
                <td colspan="2">
                    <table class="col-lg-5 email_mgt rpt_padding">
                        <tbody>
                            <tr>
                                <th>Time:</th>
                                <td>
                                    <select id="not_hr" class="form-control mod-wid-153 fl" name="data[DailyupdateNotification][not_hr]">
                                        <option selected="" value="">Hour</option>
                                        <?php
                                        for($i = 1;$i<=24;$i++) {
                                            if($i<=9) {
                                                $i = '0'.$i;
                                            }
                                            ?>
                                        <option value="<?php echo $i; ?>" <?php if($i == $hr_min[0]) echo 'selected'; ?>><?php echo $i; ?></option>
                                            <?php }	?>
                                    </select>
                                    <select id="not_mn" class="form-control mod-wid-153 fl min_mgt" name="data[DailyupdateNotification][not_mn]">
                                        <option selected="" value="">Min</option>
                                        <?php
                                        for($i =0;$i<=45;$i=$i+15) {
                                            if($i<10)
                                                $i = '0'.$i;
                                            ?>
                                        <option value="<?php echo $i; ?>"<?php if($i == $hr_min[1]) echo 'selected'; ?>><?php echo $i; ?></option>
                                            <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Select Projects:</th>
                                <td class="last">
                                    <div class="span4">
                                        <select name="data[DailyupdateNotification][proj_name]" id="rpt_selprj" class="form-control mod-wid-153 fl min_mgt">
                                            <?php
                                            if($getAllDailyupdateNot['DailyupdateNotification']['proj_name'] != '') {
                                                $pjarr = explode(",",$getAllDailyupdateNot['DailyupdateNotification']['proj_name']);
                                                if(isset($pjarr[0])) {
                                                    foreach($pjarr as $pjtnm) {
                                                        ?>
                                            <option value="<?php echo $pjtnm;?>" class="selected">
                                                            <?php
                                                            $prjtnm = $this->Casequery->getProjectName($pjtnm);
                                                            echo $prjtnm['Project']['name'];
                                                            ?>
                                            </option>
                                                        <?php  	}

                                                }else {  ?>
                                            <option value="<?php echo $pjarr;?>" class="selected">
                                                        <?php
                                                        $prjtnm = $this->Casequery->getProjectName($pjarr);
                                                        echo $prjtnm['Project']['name'];
                                                        ?>
                                            </option>
                                                    <?php	}
                                                ?>
                                                <?php }
                                            ?>
                                        </select>

                                    </div>
                                    <span id="ajax_loader" style="display:none;position:absolute; right: -25px;top: 59px;">
                                        <img src="<?php echo HTTP_IMAGES; ?>images/del.gif" alt="Loading..." />
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="cbt"></div>
    <table cellspacing="0" cellpadding="0" class="col-lg-5 rpt_tbl">
        <tbody>
            <tr>
                <th></th>
                <td class="btn_align btn_eml_lt">
                    <span id="subprof1">
                        <input type="hidden" name="data[User][changepass]" id="changepass" readonly="true" value="0"/>
                        <?php /* <button type="submit" value="Save" name="submit_Pass"  id="submit_Pass" class="btn btn_blue"><i class="icon-big-tick"></i>Update</button> */ ?>
                        <button type="button" value="Save" name="submit_Pass"  id="submit_Pass" class="btn btn_blue" onclick="checkCsrfToken('UserNotificationEmailReportsForm');"><i class="icon-big-tick"></i>Update</button>
                        <!--<button type="button" class="btn btn_grey" onclick="cancelProfile('<?php echo $referer;?>');"><i class="icon-big-cross"></i>Cancel</button>-->
                        <span class="or_cancel">or
                            <a onclick="cancelProfile('<?php echo $referer;?>');">Cancel</a>
                        </span>
                    </span>
                    <span id="subprof2" style="display: none">
                        <img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." />
                    </span>
                </td>
            </tr>
        </tbody>
    </table>
    <?php echo $this->Form->end(); ?>
    <div class="cbt"></div>
</div>
<script>
    $(document).ready(function(){
        getAutocompleteTag("rpt_selprj", "users/getProjects", "380px", "Type to select projects");
    });
    function showbox(act){
        if(act == 'show'){
            $('#dlyupdt').slideDown("fast");
        }else{
            $('#dlyupdt').slideUp("fast");
        }
    }
</script>
