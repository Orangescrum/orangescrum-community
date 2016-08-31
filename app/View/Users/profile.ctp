<div class="user_profile_con profileth">
<!--Tabs section starts -->
    <?php echo $this->element("personal_settings");?>
<!--Tabs section ends -->
<!--<div style="margin-top:20px;margin-bottom:0px;margin-left:22px;">
	<h2 style="margin-bottom:7px;">Personal Info</h2>
	<hr style="margin-top:0px;background:grey;"/>
</div> -->
<?php echo $this->form->create('User',array('url'=>'/users/profile','onsubmit'=>'return submitProfile()','enctype'=>'multipart/form-data','class'=>'form-horizontal')); ?>
<input type="hidden" name="data[User][csrftoken]" class="csrftoken" readonly="true" value="" />
<table cellspacing="0" cellpadding="0" class="col-lg-5" style="text-align:left;">
    <tbody>
        <tr>
            <th>Name:</th>
            <td>
		<input type="text" name="data[User][name]" placeholder="John" id="profile_name" class="form-control" value="<?php echo $userdata['User']['name']; ?>"/>
	    </td>
        </tr>
        <tr>
            <th>Short Name:</th>
            <td>
		<input type="text" name="data[User][short_name]" placeholder="JD" id="short_name" class="form-control"  value="<?php echo $userdata['User']['short_name']; ?>"/>
	    </td>
        </tr>
		<tr>
            <th>Email:</th>
        <td>
		<input type="text" name="data[User][email]" placeholder="Email" id="email" class="form-control"  value="<?php echo $userdata['User']['email']; ?>"/>
	    </td>
        </tr
        <tr>
            <th>Time Zone:</th>
            <td class="v-top">
		<select name="data[User][timezone_id]" id="timezone_id" class="form-control">
		    <?php foreach ($timezones as $get_timezone) { ?>
    		    <option  <?php if ($get_timezone['TimezoneName']['id'] == $userdata['User']['timezone_id']) { ?> selected <?php } ?> value="<?php echo $get_timezone['TimezoneName']['id']; ?>"><?php echo $get_timezone['TimezoneName']['gmt']; ?> <?php echo $get_timezone['TimezoneName']['zone']; ?></option>
		    <?php } ?>
		</select>
            </td>
        </tr>
	<tr>
	    <th style="vertical-align:top;">Profile Image:</th>
	    <td>	
		<div id="profDiv"></div>
		<?php
		if(defined('USE_S3') && USE_S3) {
			if($this->Format->pub_file_exists(DIR_USER_PHOTOS_S3_FOLDER, trim($userdata['User']['photo']))) {
				$user_img_exists = 1;
			}
		} elseif($this->Format->imageExists(DIR_USER_PHOTOS,trim($userdata['User']['photo']))){
			$user_img_exists = 1;
		}
		if($user_img_exists) { ?>
    		<div id="existProfImg" onmouseover="showEditDeleteImg()" onmouseout="hideEditDeleteImg()">
		    <?php if(defined('USE_S3') && USE_S3) {
				$fileurl = $this->Format->generateTemporaryURL(DIR_USER_PHOTOS_S3 . $userdata['User']['photo']);
			} else {
				$fileurl = HTTP_ROOT.'files/photos/'.$userdata['User']['photo'];
			} ?>
    		    <div>
    			<a href="<?php echo $fileurl; ?>" target="_blank">
    			    <img src="<?php echo HTTP_ROOT; ?>users/image_thumb/?type=photos&file=<?php echo trim($userdata['User']['photo']); ?>&sizex=100&sizey=100&quality=100" border="0" id="profphoto"/>
    			</a>
			    <?php echo $this->Form->hidden('photo', array('class' => 'text_field', 'id' => 'imgName1', 'name' => 'data[User][photo]')); ?>
			    <?php echo $this->Form->hidden('exst_photo', array('value' => $userdata['User']['photo'], 'class' => 'text_field', 'name' => 'data[User][exst_photo]')); ?>
    		    </div>
		    <div style="display:none" id="editDeleteImg">
    			<div id="uploadImgLnk">
			    <a title="Edit Profile Image" href="javascript:void(0);" onClick="openProfilePopup()">
				<div><img src="<?php echo HTTP_IMAGES; ?>images/edit_reply.png" border="0" class="ed_del"></div>
			    </a>
			</div>
    			<a title="Delete Profile Image" href="<?php echo HTTP_ROOT; ?>users/profile/<?php echo urlencode($userdata['User']['photo']); ?>">
			    <div onclick="return confirm('Are you sure you want to delete?')" ><img src="<?php echo HTTP_IMAGES; ?>images/delete.png" border="0" class="ed_del"></div>
			</a>
    		    </div>
    		    <div class="cb"></div>
    		</div>
		<?php } else { ?>
    		<div id="defaultUserImg" style="margin-left:10px;float:left;">
		    <img width="55" height="55" src="../files/photos/profile_Img.png">
    		</div>
		<div id="uploadImgLnk" class="fl" style="margin-top:20px;margin-left:5px;">									
    		    <a href="javascript:void(0);" onClick="openProfilePopup()" >Choose Profile Image</a>
    		</div>
    		<input type="hidden" id="imgName1" name="data[User][photo]" />
		<?php } ?>
	    </td>
	</tr>
        <tr>
            <td colspan="2">
                <div style="float: left;"> <?php echo $this->Form->input('',array('label'=>FALSE,'name'=>'data[User][isemail]','type'=>'checkbox','style'=>'margin-top:- 10px;margin-left:120px;margin-right:10px;','div'=>FALSE,'checked'=>($userdata['User']['isemail'])?true:false));?>
               </div>
                <div style="float: left;">Keep me upto date with new features</div>

            </td>

        </tr>
        <tr>
	    <th></th>
            <td class="btn_align">
		<span id="subprof1">
		    <?php /* <button type="submit" value="Update" name="submit_Profile"  id="submit_Profile" class="btn btn_blue"><i class="icon-big-tick"></i>Update</button> */ ?>
		    <button type="button" value="Update" name="submit_Profile"  id="submit_Profile" class="btn btn_blue" onclick="checkCsrfToken('UserProfileForm');"><i class="icon-big-tick"></i>Update</button>
		    <!--<button type="button" class="btn btn_grey" onclick="cancelProfile('<?php echo $referer;?>');"><i class="icon-big-cross"></i>Cancel</button>-->
			<span class="or_cancel">or
				<a onclick="cancelProfile('<?php echo $referer;?>');">Cancel</a>
			</span>
		    <!--<a href="<?php //echo $referer; ?>">Cancel</a>-->
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
