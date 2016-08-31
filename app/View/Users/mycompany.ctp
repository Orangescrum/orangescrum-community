<div class="user_profile_con thwidth">
<!--Tabs section starts -->
    <?php echo $this->element("company_settings");?>
    
    <?php 
     echo $this->Form->create('Company',array('url'=>'/users/mycompany','onsubmit'=>'return submitCompany()','enctype'=>'multipart/form-data')); ?>
<input type="hidden" name="data[Company][csrftoken]" class="csrftoken" readonly="true" value="" />
<table cellspacing="0" cellpadding="0" class="col-lg-5" style="text-align:left;">
    <tbody>
        <tr>
            <th>Name:</th>
            <td>
		<?php echo $this->Form->text('name',array('value'=>$getCompany['Company']['name'],'class'=>'form-control','id'=>'cmpname','autocomplete'=>'off')); ?>
	    </td>
        </tr>
        <?php /* ?><tr>
            <th>Orangescrum URL:</th>
            <td>
		<?php echo $this->Form->text('secsite',array('value'=>HTTP_ROOT,'name'=>'securesite', 'class'=>'form-control','id'=>'secsite','autocomplete'=>'off','disabled'=>'disabled')); ?>
	    </td>
        </tr>
        <tr>
            <th>Website:</th>
            <td>
		<?php echo $this->Form->text('website',array('value'=>$getCompany['Company']['website'],'class'=>'form-control','id'=>'website','autocomplete'=>'off')); ?>
	    </td>
        </tr>
        <tr>
            <th>Contact Number:</th>
            <td>
		<?php echo $this->Form->text('contact_phone',array('value'=>$getCompany['Company']['contact_phone'],'class'=>'form-control','id'=>'contact_phone','autocomplete'=>'off')); ?>
	    </td>
        </tr>
        <tr>
		<?php */ ?>
	    <th></th>
            <td class="btn_align">
            	<span id="subprof1">
		<input type="hidden" name="data[User][changepass]" id="changepass" readonly="true" value="0"/>
		<?php /* <button type="submit" value="Update" name="submit_Pass"  id="submit_Pass" class="btn btn_blue"><i class="icon-big-tick"></i>Update</button> */ ?>
		<button type="button" value="Update" name="submit_Pass"  id="submit_Pass" class="btn btn_blue" onclick="checkCsrfToken('CompanyMycompanyForm');"><i class="icon-big-tick"></i>Update</button>
		<!--<button type="button" class="btn btn_grey" onclick="cancelProfile('<?php echo $referer;?>');"><i class="icon-big-cross"></i>Cancel</button>-->
         <span class="or_cancel">or
            <a onclick="cancelProfile('<?php echo $referer;?>');">Cancel</a>
        </span>
		</span>
		<span id="subprof2" style="display:none">
		    <img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." />
		</span>
            </td>
        </tr>						
    </tbody>
</table>
<?php echo $this->Form->end(); ?>

<div class="cbt"></div>
</div>
<style>
.thwidth table th {
    width: 152px;
}
</style>