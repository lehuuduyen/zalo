<style>
    .progressbar:not(.initli) {
        margin: 0;
        padding: 0;
        counter-reset: step;
    }

    .progressbar li span {
        font-size: 9px;
    }

    .progressbar li:not(.initli) {
        list-style-type: none;
        width: 12%;
        float: left;
        font-size: 9px;
        position: relative;
        text-align: center;
        /* text-transform: uppercase; */
        color: #7d7d7d;
        z-index: 0;
    }

    .progressbar li:not(.initli):before {
        width: 10px;
        height: 10px;
        content: ' ';
        counter-increment: step;
        line-height: 51px;
        border: 5px solid #7d7d7d;
        display: block;
        text-align: center;
        margin: 0 auto 10px auto;
        border-radius: 50%;
        background-color: white;
    }

    .progressbar li:not(.initli):after {
        width: 100% !important;
        height: 2px !important;
        content: '' !important;
        position: absolute !important;
        background-color: #7d7d7d !important;
        top: 4px !important;
        left: -50% !important;
        z-index: -1 !important;
    }

    .progressbar li:first-child:after {
        content: none;
        display: none;
    }

    .progressbar li.active:not(.initli) {
        color: green;
    }

    .progressbar li.active:not(.initli):before {
        border-color: #55b776;
    }

    .progressbar li.cancel:before {
        border-color: red;
    }

    .progressbar li.active + li:after {
        background-color: #55b776 !important;
    }

    .font11 {
        font-size: 11px;
    }

    .btn-info.active, .btn-info:active {
        background-color: #094865;
    }

    .table-orders th, .table-orders td {
        white-space: nowrap;
    }

    .mw600 {
        min-width: 600px;
    }

    .li_pad10 {
        white-space: normal;
        padding-left: 10px;
    }

    .CRa {
        color: #55b776;
    }

    .progressbar_img {
        text-align: center !important;
        display: flex;
        flex-direction: row;
        justify-content: center;
        margin-right: 12px;
    }

    ul.progressbar_img li {
        width: 12%;
        float: left;
    }

    .CRwa {
        color: red;
    }

    .initli {
        margin-top: 10px;
        list-style-type: none;
        width: 12%;
        float: left;
        font-size: 9px;
        position: relative;
        text-align: center;
        /* text-transform: uppercase; */
        color: #7d7d7d;
        z-index: 0;
    }

    tr.bg-warning {
        background-color: #fcf8e3;
    }

    tr.bg-dd {
        background-color: #dddddd;
    }

    .initli .status_orders {
        color: red;
    }
    .label-green {
        color: green;
        border: 1px solid green;
    }
    .label-c {
        color: red;
        border: 1px solid red;
    }
    .bg-success{
        background-color: #84c529;
    }
</style>
<?php if (!empty($data)) { ?>
    <?php foreach ($data as $kData => $vData) { ?>
        <div class="div_advisory panel panel-info mtop5 mbot10">
            <div class="panel-heading">
                <b>
                    <?= mb_strtoupper(_l('cong_advisory_panel'), 'UTF-8'); ?>
                    <?php if(!empty($vData)){ ?>
                        (<a href="<?=base_url('messager/view_detail_orders/'.$vData->id)?>"><?= $vData->prefix . $vData->code.'-'.$vData->type_code ?></a>)
                    <?php } ?>
                </b>
                <a class="text-danger pull-right" onclick="BreakAdvisory(<?=$vData->id?>, 1, this); return false;">
                    <b><?=_l('cong_break_advisory')?></b>
                </a>
                <div class="clearfix_C"></div>
                <a class="text-danger pull-left  mtop5"  data-toggle="collapse" data-target="#js-more_<?=$vData->id?>">
                    <b><?=_l('more')?></b>
                </a>

                <div class="clearfix_C"></div>
            </div>


            <div class="panel-body padding-5">
                <div>
                    <?php
                        $object_it = $vData->type_object_it.'_'.$vData->id_object_it;
                        if(!empty($object_it))
                        {
                            if($vData->type_object_it == 'lead')
                            {
                                $DataObject_it = get_table_where_select_cong('tblleads', ['id' => $vData->id_object_it], '','id, name_system', 'row');
                            }
                            else
                            {
	                            $DataObject_it = get_table_where_select_cong('tblclients', ['userid' => $vData->id_object_it], '','userid as id, name_system', 'row');
                            }
                        }
                    ?>
                    <h5 class="lbl-title-silver bg-success">
                        <?=!empty($DataObject_it) ? (_l('cong_buy_it_object').' '.$DataObject_it->name_system ) : _l('cong_buy_this')?>
                        <a class="actionSelectSuggest" object_select="select_<?= $vData->id ?>" data-toggle="tooltip" data-original-title="<?=_l('cong_product_suggest')?>">
                            <i class="lnr lnr-cart" aria-hidden="true"></i>
                        </a>
                    </h5>
                    <div class="selectSuggest hide" object_select="select_<?= $vData->id ?>">
                        <input class="product_suggest" id="product_suggest_<?= $vData->id ?>">
                    </div>
                </div>

                <div>
                    <h5 class="lbl-title-silver">1. <?=_l('cong_infomation_advisory')?></h5>
                </div>

                <div class="mbot5">
                    <span class="col-md-6 row">1.1 <?=_l('cong_code_prioritize')?> :</span>

	                <?= priority_level_advisory($vData->status_active, $vData->id, true); ?>
                </div>


                <?php
                    $statusActive = StatusActiveAdvisory();
                    $activeStatusRow = $statusActive[$vData->status_active];
                    $htmlLi = '';
                    foreach($statusActive as $kActive => $vActive)
                    {
                        if($kActive > $vData->status_active) {
                            $htmlLi .= '<li><a class="AStatusAdvisory" status-table="'.$kActive.'" id-data="'.$vData->id.'">'.$vActive['name'].'</a></li>';
                        }
                        else {
	                        $htmlLi .= '<li><a class="css-no-drop">'.$vActive['name'].'</a></li>';
                        }
                    }

                    $status_active ='<div class="mbot5 viewchange"> <span class="col-md-6 row">1. '._l('cong_status_active_advisory').':</span>
                                <span class="inline-block label '.$activeStatusRow['class'].'">
                                '.$activeStatusRow['name'].'
                                    <div class="dropdown inline-block mleft5 table-export-exclude">
                                        <a href="#" style="font-size:14px;vertical-align:middle;" class="dropdown-toggle text-dark"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span data-toggle="tooltip" title="'.$activeStatusRow['name'].'">
                                                <i class="fa fa-caret-down" aria-hidden="true"></i>
                                            </span>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-right">'.$htmlLi.'</ul>
                                    </div>
                                </span>
                            </div>';
                    echo $status_active;
                ?>
                <div id="js-more_<?=$vData->id?>" class="js-more collapse" id_data="<?=$vData->id?>">
<!--                <div class="js-more hide">-->
                    <?php $Criteria = StatusCriteria(); ?>
                    <?php

                        $htmlLicriteria = '';
                        foreach($Criteria as $kCriteria => $vCriteria)
                        {
                            if(!empty($kCriteria))
                            {
                                $htmlLicriteria .= '<li><a class="criteria" status-table="'.$kCriteria.'" id-data="'.$vData->id.'">'.$vCriteria['name'].'</a></li>';
                            }
                        }


                        $Criteria_one_active = $Criteria[$vData->criteria_one];
                        $DropdowLicriteria_one ='<div class="mbot5 viewchange"> <span class="col-md-6 row">1.2. '._l('cong_criteria_one').':</span>
                                    <span class="inline-block label '.$Criteria_one_active['class'].'">
                                    '.$Criteria_one_active['name'].'
                                        <div class="dropdown inline-block mleft5 table-export-exclude">
                                            <a href="#" style="font-size:14px;vertical-align:middle;" class="dropdown-toggle text-dark"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span data-toggle="tooltip" title="'.$Criteria_one_active['name'].'">
                                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                </span>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-right"  colums="criteria_one">'.$htmlLicriteria.'</ul>
                                        </div>
                                    </span>
                                </div>';
                        echo $DropdowLicriteria_one;
                    ?>
                    <?php
                        $Criteria_two_active = $Criteria[$vData->criteria_two];
                        $DropdowLicriteria_two ='<div class="mbot5 viewchange"> <span class="col-md-6 row">1.3. '._l('cong_criteria_two').':</span>
                                    <span class="inline-block label '.$Criteria_two_active['class'].'">
                                    '.$Criteria_two_active['name'].'
                                        <div class="dropdown inline-block mleft5 table-export-exclude">
                                            <a href="#" style="font-size:14px;vertical-align:middle;" class="dropdown-toggle text-dark"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span data-toggle="tooltip" title="'.$Criteria_two_active['name'].'">
                                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                </span>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-right"  colums="criteria_two">'.$htmlLicriteria.'</ul>
                                        </div>
                                    </span>
                                </div>';
                        echo $DropdowLicriteria_two;
                    ?>
                    <div class="mbot5 viewchange">
                        <?php
                        $value = !empty($vData->product_other_buy) ? $vData->product_other_buy : '';
                        echo Create_wap_content_input('1.4. '._l('cong_product_other_buy'), 'product_other_buy', $value, $vData->id, 'admin/advisory_lead/editColum_advisory', array('class' => 'form_edit_advisory'), 'input', ' ');
                        ?>
                    </div>
                    <div class="mbot5 viewchange">
                        <?php
                        $value = !empty($vData->address_other_buy) ? $vData->address_other_buy : '';
                        echo Create_wap_content_input('1.5. '._l('cong_address_other_buy'), 'address_other_buy', $value, $vData->id, 'admin/advisory_lead/editColum_advisory', array('class' => 'form_edit_advisory'), 'input', ' ');
                        ?>
                    </div>

                    <div class="mbot5 viewchange">
                        <?php
                        $value = !empty($vData->note_reason_spam) ? $vData->note_reason_spam : '';
                        echo Create_wap_content_textarea('1.6. '._l('cong_reason_spam'), 'note_reason_spam', $value, $vData->id, 'admin/advisory_lead/editColum_advisory', array('class' => 'form_edit_advisory'),' ');
                        ?>
                    </div>
                    <div class="mbot5 viewchange">
                        <?php
                        $value = !empty($vData->note_reason_stop) ? $vData->note_reason_stop : '';
                        echo Create_wap_content_textarea('1.7. '._l('cong_reason_stop_advisory'), 'note_reason_stop', $value, $vData->id, 'admin/advisory_lead/editColum_advisory', array('class' => 'form_edit_advisory'), ' ');
                        ?>
                    </div>

                    <div class="mbot5 viewchange">1.8.
                        <?=_l('cong_date_contact')?> :
                        <?= _dt($vData->date); ?>
                    </div>

                    <div class="mbot5 viewchange">1.9.
                        <?=_l('cong_date_create_advisory')?> :
                        <?= _dt($vData->date_create); ?>
                    </div>
                    <div class="mtop5 mbot5 viewchange">1.10.
                        <?=_l('cong_staff_create_advisory')?> :
                        <?= get_staff_full_name($vData->create_by); ?>
                    </div>
                    <div class="mtop5 mbot5 viewchange">
                        <?php
                            $value = !empty($vData->note_appointment) ? $vData->note_appointment : '';
                            echo Create_wap_content_input('1.11. '._l('cong_note_appointment'), 'note_appointment', $value, $vData->id, 'admin/advisory_lead/editColum_advisory', array('class' => 'form_edit_advisory'), 'input', ' ');
                        ?>
                    </div>
                    <div class="mtop5 mbot5 viewchange">
	                    <?php
                            $staff = get_table_where_select_cong('tblstaff', ['active' => 1], '','staffid as id,concat(COALESCE(lastname), " ", COALESCE(firstname)) as name');
                            $value = !empty($vData->staff_appointment) ? $vData->staff_appointment : '';
                            echo Create_wap_content_select('1.12.'._l('cong_staff_appointment'), 'staff_appointment', $value, $vData->id, 'admin/advisory_lead/editColum_advisory', array('class' => 'form_edit_advisory'), $staff,'col-md-6 mright10');
	                    ?>
                    </div>
                    <div class="mtop5 mbot5 viewchange">
	                    <?php
                            $value = !empty($vData->note) ? $vData->note : '';
                            echo  Create_wap_content_input('1.13. '._l('cong_note_advisory'), 'note', $value, $vData->id, 'admin/advisory_lead/editColum_advisory', array('class' => 'form_edit_advisory'), 'input', ' ');
	                    ?>
                    </div>


	                <?php
                        if(empty($info_view_detail))
                        {
                            $info_view_detail = get_table_where('tblclient_info_detail', ['view_modal' => 1]);
                        }
                        foreach($info_view_detail as $keyView => $vView) {?>
                            <?php $info_view_detail_value = get_table_where('tblclient_info_detail_value', ['id_info_detail' => $vView['id']]) ?>
                            <?php if($vView['type_form'] == 'select') {?>
                                <?php $advisory_info = get_table_where('tbladvisory_info_value', ['id_info' => $vView['id'], 'id_advisory' => $vData->id], '', 'row'); ?>
                                <?php echo Create_wap_content_select('1.'.(14 + $keyView).'.'.$vView['name'], 'info['.$vView['id'].']', $advisory_info->value_info, $vData->id, 'admin/advisory_lead/editColum_advisory', array('class' => 'form_edit_advisory'), $info_view_detail_value,'col-md-6 mright10'); ?>
                            <?php } ?>
                    <?php } ?>


                    <div>
                        <h5 class="lbl-title-silver">2. <?=_l('cong_counseling_experience')?></h5>
                    </div>
                    <?php foreach($vData->experience as $kExperience => $vExperience){?>
                        <?php
                        $DropdownList = DropdownListexpErience($vExperience->id, explode(',', $vExperience->detail->listid), $vData->id);
                        $DropdownList.="<button type='button' class='SaveErience btn btn-info btn-icon mtop10'>"._l('cong_save')."</button>";
                        $DropdownList.="<button type='button' class='btn btn-danger btn-icon mtop10 close_popover'>"._l('cong_close')."</button>";
                        $DropdownList_Show = '<a href="#" class="PopverSelect2" data-toggle="popover" data-placement="left" title="'.$vExperience->name.'" data-html="true" data-content="'.$DropdownList.'">
                                    '.(!empty($vExperience->detail->listname) ? trim($vExperience->detail->listname) : "...").'
                             </a>';
                        ?>
                        <div class="mbot5 viewchange">2. <?=(1+$kExperience)?>.
                            <?=$vExperience->name?> :
                            <?= $DropdownList_Show; ?>
                        </div>
                    <?php }?>


                    <div>
                        <h5 class="lbl-title-silver">3. <?=_l('cong_consulting_process')?></h5>
                    </div>
                    <?php if(!empty($vData)){?>
                        <?php
                            $html_progressbar = '';
                            $html_progressbar_img = '';
                        ?>
                        <?php $html_progressbar.='<ul class="progressbar">';?>
                        <?php $html_progressbar_img.='<ul  class="progressbar_img" style="display: flex;flex-direction: row;justify-content: left;">';?>
                                <?php $active = 1;?>
                                <?php foreach($vData->detail as $key => $value) {?>

                                    <?php
		                                $DayMore = 0;
                                        $active = false;
                                        if(( ($key == 0 || !empty($vData->detail[$key - 1]->active) ) && empty($value->active)) || !empty($value->not_procedure)) {
                                            $active = true;
                                        }
                                        $next_step = false;
                                        if(empty($value->active)) {
                                            if(strtotime($value->date_expected) <= strtotime(date('Y-m-d'))) {
                                                $next_step = true;
                                            }
                                        }

                                        $next_step = true;
                                        if(!empty($value->date_create)) {
                                            $first_date = strtotime($vData->date);
                                            $second_date = strtotime($value->date_create);
                                            $datediff = abs($first_date - $second_date);
                                            $day =  floor($datediff / (60*60*24));

	                                        $date_expected = strtotime($value->date_expected);
	                                        $date_create = strtotime($value->date_create);
	                                        $datediff = abs($date_expected - $date_create);
	                                        $DayMore =  floor($datediff / (60*60*24));
	                                        if($date_expected < $date_create)
                                            {
	                                            $DayMore = $DayMore * (-1);
                                            }
                                        }
                                    ?>
                            <?php $html_progressbar.='<li class="'.(!empty($value->active) ? "active" : "").(!empty($value->not_procedure) ? ' initli' : '').'">' ?>
                                <?php $html_progressbar.='<a class="'.(!empty($value->active) ? 'text-success' : (!empty($next_step) ? ('text-danger') : '')).' '.(!empty($active) ? 'update_status_lead' : '').'" id-data="'.$vData->id.'" status-procedure="'.$value->status_procedure.'">';?>
                                <?php $html_progressbar.='<p>'; ?>
                                                    <?php  $html_progressbar.= $value->name_status.'<br/>'; ?>
                                                    <?php if(!empty($value->active)) {
                                                        $html_progressbar .= '<i class="text-success">'._l('finished_short').'<br/>(' . (_dC($value->date_create, false)) . ')'.'</i>';
                                                        $html_progressbar .= '<br/><i class="text-danger">'._l('cong_date_expected_short').'<br/>('._dC($value->date_expected).')'.'</i>';
                                                        $html_progressbar .= '<br/><i class="text-warning '.(!empty($DayMore) && $DayMore < 0 ? ' color-more-time' : '').'">'.$day." "._l('cong_day').'</i>';
                                                        }
                                                        else {
                                                            $html_progressbar .= ' <i>(' . (_dC($value->date_expected, false)) . ')</i>';
                                                        }
                                                    ?>
                                                <?php $html_progressbar .= '</p>';?>
                                <?php $html_progressbar .= '</a>'; ?>
                            <?php $html_progressbar .= '</li>';?>
                            <?php $html_progressbar_img .= '<li>'.staff_profile_image($value->create_by, ['staff-profile-image-small'],'small',[
                                    'data-toggle' => 'tooltip',
                                    'data-title' => !empty($value->create_by) ? get_staff_full_name($value->create_by) : ''
                                ]).'</li>'?>

                                <?php } ?>
                    <?php
                        $html_progressbar .= '</ul>';
                        $html_progressbar_img .= '</ul>';
                    ?>
                    <?php } else {
                        $html_progressbar .= '<p class="text-danger">'.mb_strtoupper(_l('cong_not_advisory_panel'), 'UTF-8').'</p>';
                    } ?>
                    <div class="div_overflow_auto">
                        <div class="w500">
                            <?php
                                echo $html_progressbar_img.$html_progressbar;
                            ?>
                        </div>
                        <div class="clearfix_C"></div>

                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
<?php } ?>
<script>
    $('.update_status_lead').click(function(e){
        var id_assigned  = $(this).attr('id-data');
        var status_procedure  = $(this).attr('status-procedure');
        if($.isNumeric(id_assigned)) {
            var data = {};
            var button = $(this);
            button.button({loadingText: '<?=_l('cong_please_wait')?>'});
            button.button('loading');
            if (typeof (csrfData) !== 'undefined') {
                data[csrfData['token_name']] = csrfData['hash'];
            }
            data['id'] = id_assigned;
            data['status_procedure'] = status_procedure;
            $.post(admin_url + 'advisory_lead/update_status/', data, function (data) {
                data = JSON.parse(data);
                if (data.success) {
                    var id_facebook = $('#id_facebook').val();
                    varInfoUser(id_facebook);
                }
                alert_float(data.alert_type, data.message);
            }).always(function () {
                button.button('reset')
            });
        }
    })


    $('body').on('shown.bs.popover', '#list_advisory .PopverSelect2', function(e){
        var id = $(this).attr('aria-describedby');
        if($('#'+id).find("select.SelectErience").length) {
            $('#' + id).find(".SelectErience").select2({
                escapeMarkup: function (m) {
                    return m;
                }
            });
        }
        init_datepicker();
    })

    appValidateForm($('.form_edit_advisory'), {}, manageAction_advisory);

    function manageAction_advisory(form) {
        var button = $('.form_edit_advisory').find('button[type="submit"]');
        button.button({loadingText: "<i class='fa fa-spinner fa-spin'></i>"});
        button.button('loading');
        var data = $(form).serialize();
        var url = form.action;
        $.post(url, data).done(function(response) {
            response = JSON.parse(response);
            alert_float(response.alert_type, response.message);
            if (response.success == true) {
                $('.form_edit_advisory').find('.action_profile').addClass('hide');
                $('.form_edit_advisory').find('#update_profile').removeClass('hide');
                var id_facebook = $('#id_facebook').val();
                varInfoUser(id_facebook);
            }
        }).always(function() {
            button.button('reset');
        });
        return false;
    }



    $('.AStatusAdvisory').click(function(e){
        var aClass = $(this);
        var status = aClass.attr('status-table');
        var id = aClass.attr('id-data');
        var data = {id : id, status : status};
        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        $.post(admin_url+'advisory_lead/updateStatus', data, function(data){
            data = JSON.parse(data);
            if(data.success)
            {
                var id_facebook = $('#id_facebook').val();
                varInfoUser(id_facebook);
            }
            alert_float(data.alert_type, data.message);
        }).always(function() {});
    })

    $('.criteria').click(function(e){
        var aClass = $(this);
        var status = aClass.attr('status-table');
        var id = aClass.attr('id-data');
        var colums =aClass.parents('ul').attr('colums');
        var data = {id : id, status : status, colums : colums};
        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        $.post(admin_url+'advisory_lead/updateCriteria', data, function(data){
            data = JSON.parse(data);
            if(data.success)
            {
                var id_facebook = $('#id_facebook').val();
                varInfoUser(id_facebook);
            }
            alert_float(data.alert_type, data.message);
        }).always(function() {});
    })


    <?php foreach ($data as $kData => $vData) { ?>
    <?php
        $type_object = !empty($vData->type_object_it) ? $vData->type_object_it : $vData->type_object;
        $id_object = !empty($vData->id_object_it) ? $vData->id_object_it : $vData->id_object;
    ?>
        ajaxSelectCallBack('#product_suggest_<?=$vData->id?>', "<?=admin_url('messager/SearchProductSuggest/'.$type_object.'/'.$id_object)?>", '', '');

        $('.actionSelectSuggest[object_select="select_<?= $vData->id ?>"]').click(function(e){

            $('.selectSuggest[object_select="select_<?= $vData->id ?>"]').removeClass('hide');
            $('#product_suggest_<?= $vData->id ?>').select2('open');
        })
    <?php } ?>

</script>
