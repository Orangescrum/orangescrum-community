<?php
$url = "/easycases/exportTaskcsv";
echo $this->Form->create('Easycase', array('name' => 'taskcsvForm', 'id' => 'taskcsvForm', 'url' => $url, 'onsubmit' => "return validateCsvForm();"));
?>  
<table class="expt-tbl">
    <tr>
        <td align="right" valign="top" class="case_fieldprof"><?php echo __('Project', true); ?>: </td>
        <td align="left" valign="top" >
            <select name="data[Easycase][project]" class="form-control"  <?php if (intval($is_milestone)) { ?>onchange="change_milestone(this);change_member_assignto(this);milestone_export();"<?php } else { ?>onchange="change_member_assignto(this);"<?php } ?>>
                <option value="all" selected="selected">All</option>
                <?php
                if (isset($projArr)) {
                    foreach ($projArr as $prj) {
                        ?>
                        <option value="<?php echo $prj['Project']['uniq_id']; ?>" <?php if ($prj['Project']['uniq_id'] == $uniq_id) { ?><?php } ?>><?php echo ucfirst($prj['Project']['name']); ?></option>
                        <?php
                    }
                }
                ?>
            </select>
        </td>
    </tr>
    <?php if (intval($is_milestone)) { ?>
        <tr>
            <td><?php echo __("Milestone"); ?>: </td>
            <td>
                <div id="milestone_dv">
                    <select name="data[Easycase][milestone]" class="form-control"  onchange="milestone_export(this);" id="exportcsv_milestone">
                        <option value=""><?php echo __("All"); ?></option>
                        <?php
                        if (isset($milestones)) {
                            foreach ($milestones as $key => $milestone) {
                                ?>
                                <option value="<?php echo $key; ?>"><?php echo ucfirst($milestone); ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
            </td>
        </tr>
    <?php } ?>
    <tr>
        <td><?php echo __("Date", true); ?>: </td>
        <td>
            <?php
            $Date = $_COOKIE['DATE'];
            $cst_rng = explode(":", $Date);
            ?>
            <select name="data[Easycase][date]" id="csv_date" class="text_field form-control" onchange="showCustomRange(this);">
                <option value="all" <?php if (trim($Date) == '' && !strstr(trim($Date), ":")) { ?>selected="selected"<?php } ?>><?php echo __("Any time"); ?></option>
                <option value="1" <?php if (trim($Date) == 'one') { ?>selected="selected"<?php } ?>><?php echo __("Past hour"); ?></option>
                <option value="24" <?php if (trim($Date) == '24') { ?>selected="selected"<?php } ?>><?php echo __("Past 24 hours"); ?></option>
                <option value="week" <?php if (trim($Date) == 'week') { ?>selected="selected"<?php } ?>><?php echo __("Past week"); ?></option>
                <option value="month" <?php if (trim($Date) == 'month') { ?>selected="selected"<?php } ?>><?php echo __("Past month"); ?></option>
                <option value="year" <?php if (trim($Date) == 'year') { ?>selected="selected"<?php } ?>><?php echo __("Past year"); ?></option>
                <option value="cst_rng" <?php if (count($cst_rng) == 2) { ?>selected="selected"<?php } ?>><?php echo __("Custom range"); ?></option>
            </select>
        </td>
    </tr>
    <tr id="tr_cst_rng" style="<?php if (count($cst_rng) == 2) { ?>display: show;<?php } else { ?>display:none;<?php } ?>">
        <td align="right" valign="top" class="case_fieldprof" style="padding-top:13px;"><?php echo __("Range"); ?>: </td>
        <td align="left" valign="top" style="padding-top:10px;">
            <div style="float: left;text-align: left;">
                From <input type="text" id="cst_frm" name="data[Easycase][from]" value="<?php if (count($cst_rng) == 2) echo $cst_rng['0']; ?>" class="form-control" placeholder="From" />
            </div>
            <div style="float: left;padding-left: 0px;text-align: left;">
                To <input type="text" id="cst_to" name="data[Easycase][to]" value="<?php if (count($cst_rng) == 2) echo $cst_rng['1']; ?>" class="form-control" placeholder="To" />
            </div>
        </td>
    </tr>
    <tr>
        <td align="right" valign="top" class="case_fieldprof" style="padding-top:13px;"><?php echo __("Status"); ?>: </td>
        <td align="left" valign="top" style="padding-top:10px;">
            <?php $status = $_COOKIE['STATUS']; ?>
            <select name="data[Easycase][status]" class="text_field form-control" >
                <option value="all" <?php if (!$status || strstr($status, "all")) { ?>selected="selected"<?php } ?>><?php echo __("All"); ?></option>
                <option value="1" <?php if (strstr($status, "1")) { ?>selected="selected"<?php } ?>><?php echo __("New"); ?></option>
                <option value="2" <?php if (strstr($status, "2")) { ?>selected="selected"<?php } ?>><?php echo __("In Progress"); ?></option>
                <option value="3" <?php if (strstr($status, "3")) { ?>selected="selected"<?php } ?>><?php echo __("Closed"); ?></option>
                <option value="5" <?php if (strstr($status, "5")) { ?>selected="selected"<?php } ?>><?php echo __("Resolved"); ?></option>
                <option value="attach" <?php if (strstr($status, "attch")) { ?>selected="selected"<?php } ?>><?php echo __("Files"); ?></option>
                <option value="update" <?php if (strstr($status, "upd")) { ?>selected="selected"<?php } ?>><?php echo __("Updates"); ?></option>
            </select>
        </td>
    </tr>
    <tr>
        <td align="right" valign="top" class="case_fieldprof" style="padding-top:13px;"><?php echo __("Types"); ?>: </td>
        <td align="left" valign="top" style="padding-top:10px;">
            <?php $types = $_COOKIE['CS_TYPES']; ?>
            <select name="data[Easycase][types]" class="text_field form-control" >
                <option value="all" <?php if (!$types || trim($types) == 'all') { ?>selected="selected"<?php } ?>><?php echo __("All"); ?></option>
                <?php foreach ($typeArr as $key => $value) { ?>
                    <option value="<?php echo $value['types']['id'] ?>" <?php if (trim($types) == $value['types']['id']) { ?>selected="selected"<?php } ?>><?php echo $value['types']['name'] ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
    <tr id="tr_priority">
        <td align="right" valign="top" class="case_fieldprof" style="padding-top:13px;"><?php echo __("Priority"); ?>: </td>
        <td align="left" valign="top" style="padding-top:10px;">
            <?php $priority = $_COOKIE['PRIORITY']; ?>
            <select name="data[Easycase][priority]" class="text_field form-control" >
                <option value="all" <?php if (!$priority || trim($priority) == 'all') { ?>selected="selected"<?php } ?>><?php echo __("All"); ?></option>
                <option value="2" <?php if (trim($priority) == 'Low') { ?>selected="selected"<?php } ?>><?php echo __("Low"); ?></option>
                <option value="1" <?php if (trim($priority) == 'Medium') { ?>selected="selected"<?php } ?>><?php echo __("Medium"); ?></option>
                <option value="0" <?php if (trim($priority) == 'High') { ?>selected="selected"<?php } ?>><?php echo __("High"); ?></option>
            </select>
        </td>
    </tr>
    <tr id="tr_members">
        <td align="right" valign="top" class="case_fieldprof" style="padding-top:13px;"><?php echo __("Members"); ?>: </td>
        <td align="left" valign="top" style="padding-top:10px;">
            <select name="data[Easycase][members]" class="text_field form-control" >
                <option value="all"><?php echo __("All"); ?></option>
                <?php
                if (isset($memArr)) {
                    foreach ($memArr as $mem) {
                        $members = explode("-", $_COOKIE['MEMBERS']);
                        ?>
                        <option value="<?php echo $mem['User']['id']; ?>" <?php if (in_array($mem['User']['id'], $members)) { ?>selected="selected"<?php } ?>><?php echo ucfirst($mem['User']['name']); ?></option>
                        <?php
                    }
                }
                ?>
            </select>
        </td>
    </tr>
    <tr id="tr_assign_to">
        <td align="right" valign="top" class="case_fieldprof" style="padding-top:13px;"><?php echo __("Assign to"); ?>: </td>
        <td align="left" valign="top" style="padding-top:10px;">
            <select name="data[Easycase][assign_to]" class="text_field form-control">
                <option value="all"><?php echo __("All"); ?></option>
                <?php
                if (isset($asnArr)) {
                    foreach ($asnArr as $Asn) {
                        $Asnbers = explode("-", $_COOKIE['ASSIGNTO']);
                        ?>
                        <option value="<?php echo $Asn['User']['id']; ?>" <?php if (in_array($Asn['User']['id'], $Asnbers)) { ?>selected="selected"<?php } ?>><?php echo ucfirst($Asn['User']['name']); ?></option>
                        <?php
                    }
                }
                ?>
            </select>
        </td>
    </tr>
    <tr id="tr_assign_to">
        <td align="right" valign="top" class="case_fieldprof" style="padding-top:13px;"><?php echo __("Milestone"); ?>: </td>
        <td align="left" valign="top" style="padding-top:10px;">
            <select name="data[Easycase][milestone]" class="text_field form-control" id="milestone_list">
                <option value="all"><?php echo __("All"); ?></option>
                <?php
                if (isset($milestone)) {
                    foreach ($milestone as $key => $Asn) {
                        ?>
                        <option value="<?php echo $key; ?>" ><?php echo ucfirst($Asn); ?></option>
                        <?php
                    }
                }
                ?>
            </select>
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>	
        <td style="text-align: left;">
            <button type="submit"  class="btn btn_blue">
                <i class="icon-big-tick"></i>
<?php echo __("Export CSV"); ?>
            </button>
            <!--<button onclick="closePopup();" class="btn btn_grey" type="button">
                    <i class="icon-big-cross"></i>
                    Cancel
            </button>-->
            <span class="or_cancel"><?php echo __("or"); ?><a onclick="closePopup();"><?php echo __("Cancel"); ?></a></span>
        </td>
    </tr>
</table>
<?php echo $this->Form->end(); ?>

<script>
    $(function () {
        $("#cst_frm").datepicker({
            changeMonth: false,
            changeYear: false,
            hideIfNoPrevNext: true,
            maxDate: "0D",
            onClose: function (selectedDate) {
                $("#cst_to").datepicker("option", "minDate", selectedDate);
            }
        });
    });
    $(function () {
        $("#cst_to").datepicker({
            changeMonth: false,
            changeYear: false,
            hideIfNoPrevNext: true,
            maxDate: "0D",
            onClose: function (selectedDate) {
                $("#cst_frm").datepicker("option", "maxDate", selectedDate);
            }
        });
    });


    $("#ui-datepicker-div").click(function (e) {
        e.stopPropagation();
    });
    function milestone_export(obj) {
        if (typeof obj == 'undefined') {
            obj = $('#exportcsv_milestone');
        }
        if ($(obj).val()) {
            $('#taskcsvForm').attr('action', HTTP_ROOT + "easycases/exporttoCSV_Milestone")
        } else {
            $('#taskcsvForm').attr('action', HTTP_ROOT + "easycases/exportTaskcsv");
        }
    }
    function validateCsvForm() {
        var done = 1;
        if ($("#csv_date option:selected").val() == 'cst_rng') {
            var start_date = document.getElementById('cst_frm');
            var end_date = document.getElementById('cst_to');
            var errMsg;
            if (Date.parse(start_date.value) > Date.parse(end_date.value)) {
                errMsg = '<?php echo __("From Date cannot exceed To Date!"); ?>';
                end_date.focus();
                done = 0;
            } else if (start_date.value.trim() == "") {
                errMsg = '<?php echo __("From Date cannot be left blank!"); ?>';
                start_date.focus();
                done = 0;
            } else if (end_date.value.trim() == "") {
                errMsg = '<?php echo __("End Date cannot be left blank!"); ?>';
                end_date.focus();
                done = 0;
            }

            if (done == 0) {
                showTopErrSucc('error', errMsg);
                return false;
            }
        }
        if (done == 1) {
            closePopup();
            return true;
        } else {
            return false;
        }
    }
</script>
