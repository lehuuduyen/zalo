<?php $this->load->view('messagers/manage/include/header')?>
<style>
    .dropdown-not-padding{
        margin-top: 0px;
        margin-bottom: 0px;
        font-size: 16px;
    }
    .fa-picture-o.dropdown-menu.dropdown-item{
        border-top: 1px solid black;
    }
    .border-top{
        border-top:1px solid black;
    }

    .my-messages-container a{
        color: #422222;
    }
    .file_send{
        margin-top:5px;
        padding-left: 10px;
        padding-top: 7px;
        padding-bottom: 10px;
        height: 30px;
        background-color: #e2e4e4;
    }
    .name_file{
        width:96%;
        float:left;
    }
    .clost_file{
        width:3%;
        float:left;
    }
    .chat-area-reply{
        margin-bottom:5px;
    }
    .clearfix_C{
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        display: table;
        content: " ";
        clear: both;
    }
    .mbot0{
        margin-bottom: 0px;
    }
    .navSearch > li {
        width: 25%;
    }
    .navSearch > li > a{
        font-size: 18px;
        text-align: center;
    }
    .TLeftSearch i {
        font-size: 20px;
        padding: 5px;
    }
    .side-bar .right{
        left: 53.5781px!important;
    }
    .daterangepicker.ltr.show-calendar.opensright{
        top: 23%!important;
        left: 23px!important;
    }

    .div_careof .progressbar li{
        width: 25%!important;
    }
    .div_careof .progressbar_img li{
        width: 25%!important;
    }
</style>
<div class="side-bar">
    <div class="TLeftSearchALL" >
        <a data-toggle="tooltip" data-placement="right" id-data="all" title="<?=_l('cong_t_not_search_all')?>">
            <i class="lnr lnr-database" aria-hidden="true"></i>
        </a>
    </div>
    <div class="TLeftSearch TTimeChat" >
        <a class="pointer hide DeleteSreach" id-delete="assigned">x</a>
        <a data-toggle="tooltip" data-placement="right" id-data="time" title="<?=_l('cong_t_search_time')?>">
            <i class="lnr lnr-calendar-full" aria-hidden="true"></i>
        </a>
    </div>
    <input id="time_chat" type="hidden">
    <div class="TLeftSearch">
        <a data-toggle="tooltip" data-placement="right" id-data="phone" title="<?=_l('cong_t_search_phone')?>">
            <i class="lnr lnr-phone" aria-hidden="true"></i>
        </a>
    </div>
    <div class="TLeftSearch">
        <a data-toggle="tooltip" data-placement="right" id-data="not_phone" title="<?=_l('cong_t_search_not_phone')?>">
            <i class="lnr lnr-cross-circle" aria-hidden="true"></i>
        </a>
    </div>
    <div class="TLeftSearch" >
        <a data-toggle="tooltip" data-placement="right" id-data="orders" title="<?=_l('cong_t_search_orders')?>">
            <i class="lnr lnr-cart" aria-hidden="true"></i>
        </a>
    </div>
    <div class="TLeftSearch" >
        <a data-toggle="tooltip" data-placement="right" id-data="posts" title="<?=_l('cong_t_search_post')?>">
            <i class="lnr lnr-text-align-center" aria-hidden="true"></i>
        </a>
    </div>
    <div class="TLeftSearch" >
        <a data-toggle="tooltip" data-placement="right" id-data="remind" title="<?=_l('cong_t_search_res')?>">
            <i class="lnr lnr-alarm" aria-hidden="true"></i>
        </a>
    </div>
    <div class="TLeftSearch" >
        <a data-toggle="tooltip" data-placement="right" id-data="farthest" title="<?=_l('cong_t_search_tr')?>">
            <i class="lnr lnr-warning" aria-hidden="true"></i>
        </a>
    </div>
    <div class="TLeftSearch TAssigned" data-toggle="tooltip" data-placement="right"  title="<?=_l('cong_t_search_assigned')?>">
        <a class="pointer hide DeleteSreach" id-delete="assigned">x</a>
        <input id="assignedSearch" type="hidden" value=""/>
        <?php
            $GetAllStaff = get_table_where('tblstaff', ['active' => 1]);
            $contentHtml = "<div style='color:black'>";
            $contentHtml .= "    <h4>"._l('cong_t_assigned')."</h4>";
            $contentHtml .= "    <div class='font-12'>";
            $contentHtml .= "        <ul class='list-group'>";
            foreach($GetAllStaff as $key => $value)
            {
                $img = 'uploads/staff_profile_images/'.$value['staffid'].'/small_'.$value['profile_image'];
                if(!file_exists($img)) {
                    $img = 'assets/images/user-placeholder.jpg';
                }
                $contentHtml .= "       <li class='list-group-item ItemSearchAssigned' id-data='".$value['staffid']."'> <img  src='".base_url($img)."' class='staff-profile-image-small' alt='".$value['lastname'].' '.$value['firstname']."'> ".$value['lastname'].' '.$value['firstname']."</li>";
            }
            $contentHtml .= "        </ul>";
            $contentHtml .= "    </div>";
            $contentHtml .= "</div>";
        ?>
        <a data-toggle="popover" data-placement="right" data-html="true" data-content="<?=$contentHtml?>" id-data="assigned">
            <i class="lnr lnr-user" aria-hidden="true"></i>
        </a>
    </div>
</div>
<div class="clearfix"></div>
<div class="app-content">
    <!--Left-->
    <div class="list">
        <div class="tabSearch">
            <ul class="nav nav-tabs navSearch mbot0">
                <li class="active">
                    <a class="TSearch" id-data="all" data-toggle="tooltip" data-placement="top" title="<?=_l('cong_all')?>">
                        <i class="fa fa-inbox" aria-hidden="true"></i>
                    </a>
                </li>
                <li>
                    <a class="TSearch" id-data="message" data-toggle="tooltip" data-placement="top" title="<?=_l('cong_message')?>">
                        <i class="fa fa-envelope-o" aria-hidden="true"></i>
                    </a>
                </li>
                <li>
                    <a class="TSearch" id-data="not_see" data-toggle="tooltip" data-placement="top" title="<?=_l('cong_not_see')?>">
                        <i class="fa fa-eye-slash" aria-hidden="true"></i>
                    </a>
                </li>
                <li class="dropdown"  data-toggle="tooltip" data-placement="top" title="<?=_l('cong_tag')?>">
                    <a class="TSearch dropdown-toggle" data-toggle="dropdown" id-data="tag" >
                        <i class="fa fa-tags" aria-hidden="true"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <?php $tagview = get_tagsFB_table(); ?>
                        <li class="DSearch">
                            <a class="DSearchTag" id-data="">
                                <i class="fa fa-hashtag" aria-hidden="true"></i>
                                <?=_l('cong_not_tag')?>
                            </a>
                        </li>
                        <?php foreach($tagview as $key => $value){?>
                            <li class="DSearch">
                                <a class="DSearchTag" id-data="<?=$value['id']?>">
                                    <i class="fa fa-circle" style="color:<?=$value['background_color']?>" aria-hidden="true"></i>
                                    <?=$value['name']?>
                                </a>
                            </li>
                        <?php } ?>

                    </ul>
                </li>
            </ul>
        </div>
        <div class="filter-search">
            <div class="filter-by-name">
                <div class="pos-search-icon">
                    <i class="fa fa-search"></i>
                </div>
                <div class="pos-search-text">
                    <input autocomplete="off" id="search_Chat" type="text" placeholder="<?=_l('cong_input_name_or_phone_to_search')?>">
                </div>
            </div>
        </div>
        <div class="list-profile">
            <?php $this->load->view('messagers/manage/left')?>
        </div>
    </div>
    <!--end left-->

    <!--Start mid-->
    <?php require_once(APP_MODULES_PATH.'messager/views/messagers/manage/mid.php');?>
    <!--End mid-->

    <!--start right-->
    <?php $this->load->view('messagers/manage/right')?>
    <!--End right-->

    <div class="clearfix"></div>
</div>


<div class="modal fade" id="file_link" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> <?=_l('cong_send_img_to_link')?> </h4>
            </div>
            <div class="modal-body">
                <div>
                    <input id="input_file_link" class="form-control" placeholder="<?=_l('cong_input_url_image')?>">
                    <button class="btn btn-info mtop15 pull-right mbot10" onclick="SendFileLink()"><?=_l('cong_send')?></button>
                </div>
            </div>
            <div class="clearfix_C"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?=_l('cong_close')?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="fanpage_photo_modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> <?=_l('cong_img_to_fanpage')?></h4>
            </div>
            <div class="clearfix_C"></div>
            <div class="modal-body">
                <div class="body_modal"></div>
                <div class="clearfix_C"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?=_l('cong_close')?></button>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('messagers/manage/include/loader')?>
<?php $this->load->view('messagers/manage/include/footer')?>