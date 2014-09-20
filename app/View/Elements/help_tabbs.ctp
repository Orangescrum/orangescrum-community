<?php $url = HTTP_ROOT."help/"; ?>
<?php if(!defined('SES_ID') || !SES_ID){ ?>
<div class="gray_pattern gray_color help_section" style="padding-top:80px; background:#ffffff">
<?php } ?>
<div class="wrapper_help">
	<?php if(defined('SES_ID') && SES_ID){ ?>
    <a href="<?php echo HTTP_ROOT."help"; ?>">
        <div class="sub_div">
        	<div class="fl icon_outer qsn"></div>
            <div class="sub_txt fl">General Questions</div>
            <div class="cb"></div>
        </div>
    </a>
    <a href="<?php echo $this->Html->url('/users/tour');?>">
        <div class="sub_div">
        	<div class="fl icon_outer feature"></div>
            <div class="sub_txt fl" style="margin-top: 13px;">Features</div>
			 <div class="cb"></div>
        </div>
    </a>
	<a href="<?php echo $this->Html->url('/users/customer_support/how_works');?>">
    <div class="sub_div">
        <div class="fl icon_outer how"></div>
        <div class="sub_txt fl">How it works?</div>
        <div class="cb"></div>
    </div>
    
  <div class="cb" style="margin-bottom:20px;"></div>
	<?php } ?>



