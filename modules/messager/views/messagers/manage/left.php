<?php
    if(!empty($list_data))
    {
        $senders = json_decode($list_data)->data;
    }
    else
    {
        $senders = [];
    }
?>
<?php
$CI = & get_instance();
$CI->load->model('messager_model');
foreach($senders as $key => $value){ ?>
    <!--Lấy thông tin người dùng facebook-->
    <?php
        $infoFacebook = getInfoIdFacebook($value->senders->data[0]->id);
	    $phoneFB = getPhoneFacebook($value->senders->data[0]->id);

        $dataInfoHTML = ' phone="'.(!empty($phoneFB) ? $phoneFB : '').'"';
        $dataInfoHTML .= ' orders="'.(!empty($infoFacebook['orders']) ? $infoFacebook['orders'] : '').'"';
        $dataInfoHTML .= ' assigned="'.(!empty($infoFacebook['assigned']) ? $infoFacebook['assigned'] : '').'"';
        $CI->messager_model->addProfileListFB($value->senders->data[0]->id, $value->senders->data[0]->name);
        $seeMessage = false;
        if(!empty(SeeMessage($value->senders->data[0]->id))) {
	        $seeMessage = true;
        }
    ?>
    <div class="content-profile <?=(empty($seeMessage) ? 'profile_unread' : '')?>" <?=$dataInfoHTML?> id_senders="<?=$value->id?>" id_user = "<?=$value->senders->data[0]->id?>" data-toggle="tab" href="#tab_<?=$value->id?>">
        <div class="img-info profile_active">
            <?php
                $img = 'https://graph.facebook.com/'.$value->senders->data[0]->id.'/picture?height=100&width=100&access_token='.$_COOKIE['access_token_page_active'];
            ?>
            <img src="<?=$img?>">
        </div>
        <div class="some-info profile_active">
            <div class="name-profile">
                <?=!empty($infoFacebook['name_system']) ? $infoFacebook['name_system'] : $value->senders->data[0]->name?>
            </div>
            <div class="chat-profile" id="chat_<?=$value->senders->data[0]->id?>">...</div>
        </div>
        <div class="time-info profile_active" id="time_<?=$value->senders->data[0]->id?>" time="<?=_d($value->updated_time)?>">
            <?= _dt($value->updated_time); ?>
        </div>
        <div class="pull-left">
            <span class="sone-unread">
                <?php if(!empty($seeMessage)) {?>
                    <i class="fa fa-circle-o" data-toggle="tooltip" data-original-title="<?=_l('cong_check_unread')?>" aria-hidden="true"></i>
                <?php } else { ?>
                    <i class="fa fa-circle" data-toggle="tooltip" aria-hidden="true" data-original-title="<?=_l('cong_check_read')?>"></i>
                <?php } ?>

            </span>
            <img style="width: 15px; height: 15px;" src="<?=base_url('modules/messager/uploads/messager-fb.png')?>">
        </div>
        <div class="clearfix_C"></div>
        <table class="table table-tag dont-responsive-table mtop10 mbot0">
            <tbody>
                <tr>
                    <td class="classify_client">
	                    <?= $infoFacebook['type'] ?>
                    </td>
                    <td class="tag_left">
	                    <?php  $get_info_tag = getInfoTagFacebook($value->senders->data[0]->id);?>
	                    <?php
                            if(!empty($get_info_tag))
                            {
                                foreach($get_info_tag as $Ktag => $vtag) {?>
                                    <span class="label label-default inline-block pointer mtop5 tag-lef-<?=$vtag['id']?>" id="tag-lef-<?=$value->senders->data[0]->id?>-<?=$vtag['id']?>">
                                        <i class="fa fa-circle" style="color:<?=$vtag['background_color']?>" aria-hidden="true"></i>
                                        <?=$vtag['name'];?>
                                    </span>
                                <?php   }
                            }
                        ?>
                    </td>
                    <td class="tag-muals">
                        <?php
                        $tagMuals = GetTagMunualsFacebook($value->senders->data[0]->id);
                        if(!empty($tagMuals)) {?>
	                        <?php foreach($tagMuals as $keyMuals => $valueMuals) {?>
                                <span id-href="<?=base_url($valueMuals['link'])?>" data-toggle="tooltip" data-original-title="<?=$valueMuals['fullcode']?>" class="class_href label label-default inline-block pointer mtop5 span-tag-muals" data-title="<?=$valueMuals['name'];?>" data-color="<?=$valueMuals['color']?>">
                                    <i class="fa fa-circle bg-<?=$valueMuals['color']?> text-<?=$valueMuals['color']?>" aria-hidden="true"></i>
                                    <?=$valueMuals['name'];?>
                                </span>
		                        <?php $arrayTagMuals[$valueMuals['name']] = [
			                        'name' => $valueMuals['name'],
			                        'color' => $valueMuals['color'],
			                        'fullcode' => $valueMuals['fullcode'],
			                        'link' => $valueMuals['link']
		                        ]; ?>
	                        <?php } ?>
                        <?php } ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="clearfix"></div>
    </div>
<?php } ?>
<script>
    var TagSearchMuals = <?= (!empty($arrayTagMuals) ? json_encode($arrayTagMuals) : '[]'); ?>;
</script>
