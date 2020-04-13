<style>
    .createTag{
        padding: 5px;
        text-align: center;
    }
    .wap-other {
        margin-bottom: 0;
        padding: 6px;
    }
    .border-top {
        border-top: 1px solid #bdbdbd !important;
        padding: 10px;
    }
    .bg-yellow{
        background: yellow;
        color:black;
        font-weight: 500;
    }
    .action-search{
        height: 35px;
        display: flex;
        align-items: center;
    }
    .search-profile{
        margin: 1px;
    }
    ._mtop10{
        margin-top: 10px;
    }
    .opacity-05 {
        opacity: 0.5
    }

</style>
<div class="chat-area">
    <div class="chat-area-header">
        <div class="profile-chat">
            <div class="profile-info mleft5">
                <img class="id_profile_chat" src="">
                <span class="id_name_profile_chat"></span>
                <p class="profile_staff_assigned hide">
                    <select id="browsers_staff_assigned" class="selectpicker" data-live-search="true" multiple data-none-selected-text="<?=_l('cong_staff_assigned')?>">
                        <?php
                            if(!empty($staff)) {
                                foreach($staff as $key => $value){
                                        echo '<option value="'.$value['staffid'].'">'.$value['lastname'].' '.$value['firstname'].'</option>';
                                }
                            }
                        ?>
                    </select>
                </p>
            </div>
            <div class="action-profile">
                <i class="fa fa-search" data-original-title="<?=_l('cong_search_content_message')?>" data-toggle="tooltip" onclick="searchContentFacebook()"></i>
                <span class="dropdown">
                    <a  class="dropdown-toggle"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-link" aria-hidden="true"></i>
                    </a>
                    <div class="dropdown-menu">
                        <a target="_blank" class="dropdown-item" href="https://www.facebook.com/<?=$_COOKIE['page_active']?>/inbox">
                            <i class="fa fa-compress" aria-hidden="true"></i>
                            <?=_l('cong_view_in_facebook')?>
                        </a>
                    </div>
                </span>
            </div>
        </div>
        <div class="search-chat">
            <div class="action-profile action-search col-md-12 hide">
                <div class="col-md-2 _mtop10">
                    <div class="active_chevron">
                        <i class="glyphicon glyphicon-chevron-down SearchContentDown opacity-05" aria-hidden="true"></i>
                        <i class="glyphicon glyphicon-chevron-up SearchContentUp opacity-05" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="col-md-8">
                    <span class="result_search_content hide"></span>
                    <div class="search-profile form-group mtop10">
                        <i id="search" class="fa fa-search"></i>
                        <div class="search-profile-text">
                            <input id="search_content_message"  class="form-control" placeholder="Tìm kiếm">
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="pull-right">
                        <a class="btn btn-icon btn-active-search hide" onclick="searchContentMessage()"><?=_l('search')?></a>
                        <a class="btn btn-icon btn-end-search hide" onclick="RightSearchContent()"><?=_l('cong_end_right')?></a>
                        <a class="btn btn-icon close_search" onclick="CloseSearchContentFacebook()"><?=_l('close')?></a>
                    </div>
                </div>
                <div class="clearfix_C"></div>
            </div>
        </div>
    </div>
    <div class="tab-content chat-area-body" id="chat_content_body">
        <?php $this->load->view('messagers/manage/content_mid')?>
    </div>
    <div class="chat-area-reply hide">
        <div class="ViewTag row font11" style="margin-right: 0px;margin-left: 0px;">
            <?php $tagview = get_tagsFB_table(); ?>
            <?php foreach($tagview as $key => $value) {?>
                <?php if($key <= 4){?>
                    <div class="col-md-2 pointer createTag" style="background-color: <?=$value['background_color']?>; color:<?=$value['color']?>;" title="<?=$value['name']?>" id-tag="<?=$value['id']?>">
                        <?= (mb_strlen($value['name'], 'UTF-8') > 10) ? mb_substr($value['name'],0,10, "utf-8").'...' : $value['name']?>
                    </div>
                <?php } else { break; }?>
            <?php } ?>
            <div class="col-md-2 div_tag_hidden">
                <label for="taghidden" class="control-label font11 wap-other EventTagHidden">
                    <i class="fa fa-tag" aria-hidden="true"></i>
                    <?php echo _l('cong_tag_other').' +'.count($tagview); ?>
                </label>
                <div class="selectHidden hide">
                    <?php $fullTagFB = get_tagsFB_table(); ?>
                    <select id="taghidden" style="width: 100%">
                        <option></option>
                        <?php foreach($fullTagFB as $kTag => $vTag) { ?>
                            <option value="<?=$vTag['id']?>" color="<?=$vTag['color']?>" background_color="<?=$vTag['background_color']?>" ><?=$vTag['name']?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
        <div class="reply-box-container">
            <img src="https://graph.facebook.com/<?= $_COOKIE['page_active']?>/picture?height=100&width=100&access_token=<?=$_COOKIE['access_token_page_active']?>">
            <textarea class="replyTextarea" id="replyMessager" placeholder="<?=_l('cong_input_rep')?>" type="text" wrap="hard"></textarea>
            <?php echo form_open('messager/uploadfilepc', array('id'=>'form_uploadfile', 'enctype' => 'multipart/form-data', 'autocomplete' => 'off')); ?>
                <input class="hide" type="file" name="file" id="file"/>
            <?php echo form_close();?>
        </div>
        <div class="clearfix_C"></div>
        <div class="action-profile_v2">
            <div class="col-md-12">
                <div class="selectProduct hide">
                    <input id="product_hidden">
                </div>
            </div>
            <div class="col-md-12">
                <div class="selectReply hide">
                    <select id="reply_hidden">
                        <?php
                            if(!empty($quick_reply)) {
                                foreach($quick_reply as $key => $value) {
                                    $content = mb_substr($value['content'], 0, 250, "utf-8"). (mb_strlen($value['content'], "utf-8") > 250 ? '...' : '');
                                    echo '<option value="'.$value['id'].'">'.$value['name'].' ( '.$content.')</option>';
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="wap-action actionSelectReply">
                <i class="lnr lnr-bubble"></i>
                <p><?=_l('cong_reply_speed')?></p>
            </div>
            <div class="wap-action actionSelectProductHidden">
                <i class="lnr lnr-cart"></i>
                <p><?=_l('cong_t_product')?></p>
            </div>
            <div class="btn-group dropup wap-action">
                <i class="fa fa-picture-o dropdown-toggle dropup" data-toggle="dropdown"></i>
                <div class="dropdown-menu">
                    <h5 class="dropdown-header text-info text-center dropdown-not-padding">
                        <?=_l('cong_image')?>
                    </h5>
                    <a class="dropdown-item border-top" onclick="AddPhotoModal()">
                        <?=_l('cong_select_img_facebook')?>
                    </a>
                    <a class="dropdown-item border-top poiner" onclick="GetFilePC()" >
                        <?=_l('cong_upload_image_pc')?>
                    </a>
                    <a class="dropdown-item border-top" onclick="GetFileLink()">
                        <?=_l('cong_url_bk')?>
                    </a>
                </div>
                <p><?=_l('cong_image')?></p>
            </div>

            <div class="wap-action pull-right">
                <img class="event_create_orders_draft" style="width: 20px; height: 20px;" src="<?=base_url('modules/messager/uploads/donhang.png')?>">
                <p class="event_create_orders_draft"><?=_l('cong_orders_draft')?></p>
            </div>

            <div class="wap-action pull-right">
                <img class="event_create_advisory_client" style="width: 20px; height: 20px;" src="<?=base_url('modules/messager/uploads/phieuchamsoc.png')?>">
                <p class="event_create_advisory_client"><?=_l('create_care_of')?></p>
            </div>
            <div class="wap-action pull-right">
                <img class="event_create_orders" style="width: 20px; height: 20px;" src="<?=base_url('modules/messager/uploads/donhang.png')?>">
                <p class="event_create_orders"><?=_l('cong_create_orders')?></p>
            </div>
            <div class="wap-action pull-right">
                <img class="event_create_advisory" style="width: 20px; height: 20px;" src="<?=base_url('modules/messager/uploads/phieuchamsoc.png')?>">
                <p class="event_create_advisory"><?=_l('create_advisory')?></p>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix_C"></div>
        <div id="all_file_send"></div>
    </div>
</div>