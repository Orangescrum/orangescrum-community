<?php echo $this->Html->script('jquery.validate');?>
<style type="text/css">
    *,*:before,*:after{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;}
    .each_list_head {
        border-bottom:0px solid #FFFFFF;
        margin-bottom:0;
    }
    .right_panel_list {
        margin-left:25px;
    }
    .features {
        border:1px solid #B3CFE1;
    }
    .features:hover {
        box-shadow:0 0 12px #B3CFE1
    }
    .classli {
        margin-left:25px; line-height:25px; margin-bottom:20px;
    }
    .wrapper_help {
        width:1075px;
    }
    #wrapper{padding-left: 0px;}
    footer{margin-bottom:-80px}

</style>
<?php if($how_work) {?>
<script>
    $(function(){
        $('.customer_support').hide();
        $('.how_work').show();
    });
</script>
    <?php } ?>
<?php echo $this->Html->css('help');?>
<?php echo $this->element('help_tabbs');?>
<div style="margin-left:10px;"><?php echo $this->Session->flash();?></div>
<div class="customer_support" style="display:none;">
    
    <div class="head fl"><h3>Customer Support</h3></div>

    <div class="cb"></div>
    <div class="user_profile_con profileth">
        <?php echo $this->Form->create('customer_support');?>
        
        <table cellspacing="0" cellpadding="0" class="col-lg-7 col-xs-7" style="text-align:left;">
            <tbody>
                <tr>
                    <th style="padding-left:10px">Subject:</th>
                    <td>
                        <?php echo $this->Form->select('subject',array('Design Issue'=>'Design Issue','Functional Bug'=>'Functional Bug','Usability Problem'=>'Usability Problem','Feature Request'=>'Feature Request','How it works?'=>'How it works?','Payment related question'=>'Payment related question'),array('class'=>"form-control",'empty'=>'[Select]'));?>

                    </td>
                </tr>
                <tr>
                    <th style="padding-left:10px;padding-top:5px;vertical-align:top">Message:</th>
                    <td>
                        <?php echo $this->Form->textarea('message',array('label'=>FALSE,'placeholder'=>"Message",'class'=>"form-control", 'style'=>"height:220px;" ));?>
                        <?php echo $this->Form->input('from_email',array('type'=>'hidden','value'=>$from_email));?>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <div id="show_button">
                        <span id="quickcase" class="nwa" style="display:block;">
                            <button class="btn btn_blue"  type="submit">
                                <i class="icon-big-tick"></i>
                                <span id="ctask_btn">Post</span>
                            </button>
                            <span class="or_cancel">
                                or
                                <a id="rset" href="<?php echo $this->Html->url('/help');?>">Cancel</a>
                            </span>
                            
                        </span>
                        </div>
                        <div style="display:none;margin-left: -10px;" id="more_loader_arc_case" class="morebar_arc_case">
                                <img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="loading..." title="loading..."/>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="cb"></div>
</div>
<div class="how_work" style="display:none;">
    <div style="margin:0px auto;width:750px">
        <object width="750" height="450">
            <param name="movie" value="https://www.youtube.com/v/4qCaP0TZuxU" />
            <param name="wmode" value="transparent" />
            <embed src="https://www.youtube.com/v/4qCaP0TZuxU" allowfullscreen="true"  type="application/x-shockwave-flash" wmode="transparent" width="750" height="450" />
        </object>
    </div>
</div>
<div style="clear:both"></div>
<div style="clear:both"></div>
<script>
    $('button').click(function(){
        if($('#customer_supportSubject').val()==''){
            alert('Subject cannot be left blank!');
            return false;
        }
        if($('#customer_supportMessage').val()==''){
            alert('Message cannot be left blank!');
            return false;
        }
        $('#show_button').hide();
        $('.morebar_arc_case').show();
    });
</script>