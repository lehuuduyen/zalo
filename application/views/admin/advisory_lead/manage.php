<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .progressbar {
        margin: 0;
        padding: 0;
        counter-reset: step;
    }
    .progressbar li:not(.initli) {
        list-style-type: none;
        width: 12%;
        float: left;
        font-size: 10px;
        position: relative;
        text-align: center;
        /*text-transform: uppercase;*/
        color: #7d7d7d;
        z-index: 0;
    }
    .progressbar li:not(.initli):before {
        width: 10px;
        height: 0px;
        content: ' ';
        counter-increment: step;
        line-height: 51px;
        border: 5px solid #7d7d7d;
        display: block;
        text-align: center;
        margin: 0 auto 0px auto;
        border-radius: 50%;
        background-color: white;
    }
    .progressbar li:not(.initli):after {
        width: 100%!important;
        height: 2px!important;
        content: ''!important;
        position: absolute!important;
        background-color: #7d7d7d!important;
        top: 4px!important;
        left: -50%!important;
        z-index: -1!important;
    }
    .progressbar li:first-child:after {
        content: none;
        display: none;
    }
    .progressbar li.active {
        color: green;
    }
    .progressbar li a {
        white-space: pre-line;
    }
    .progressbar li.active:before {
        border-color: #55b776;
    }
    .progressbar li.active + li:after {
        background-color: #55b776!important;
    }

    .progressbar_img{
        margin-bottom: 0px;
        text-align: center!important;
        display: flex;
        flex-direction: row;
        justify-content: center;
    }
    ul.progressbar_img li {
        width: 12%;
        float: left;
    }

    .initli {
        font-size: 10px;
        margin-top: 10px;
        width: 12%;
        float: left;
    }
    .mw800 p {
        margin-bottom: 0px!important;
    }


    .font11
    {
        font-size: 11px;
    }
    .btn-info.active, .btn-info:active{
        background-color: #094865;
    }
    .table-advisory_lead tbody tr td:nth-child(2){
        white-space: inherit;
        min-width: 100px;
    }
    .table-advisory_lead tbody tr td:nth-child(3){
        white-space: inherit;
        min-width: 100px;
    }
    .table-advisory_lead tbody tr td:nth-child(4){
        white-space: inherit;
        min-width: 180px;
    }
    .table-advisory_lead tbody tr td:nth-child(5){
        white-space: inherit;
        min-width: 150px;
    }
    .table-advisory_lead tbody tr td:nth-child(6){
        white-space: inherit;
        min-width: 80px;
    }
    .table-advisory_lead tbody tr td:nth-child(7){
        white-space: inherit;
        min-width: 80px;
    }
    .table-advisory_lead tbody tr td:nth-child(18){
        white-space: inherit;
        min-width: 700px;
    }
    .table-advisory_lead tbody tr td, .table-advisory_lead thead tr th {
        white-space: nowrap;
    }
    /*.table-advisory_lead .select2-choices{*/
    /*    border: 0px!important;*/
    /*    background-image:none!important;*/
    /*}*/
    .table-advisory_lead .select2-search-choice{
        height: auto!important;
        padding-top: 4px!important;
        padding-bottom: 3px!important;
        white-space: normal;
        max-width: 200px;
    }
    .table-advisory_lead .select2-search-choice-close
    {
        top: 5px!important;
    }
    .table-advisory_lead .dropdown-menu
    {
        bottom: auto!important;
    }
    .right {
        left: 0;
        right: 0;
    }
    a.PopverSelect2 {
        max-width: 300px;
        white-space: initial;
    }

    .label-green{
        background-color: green;
    }
    .label-c{
        background-color: #4e4949;
    }
    .label-siver {
        background-color: #d7dde2;
    }
    .mw800{
        min-width: 800px;
    }
    .li_pad10{
        white-space: normal;
        padding-left: 10px;
    }
    .CRa{
        color: #55b776;
    }
    .table-advisory_lead >tbody>tr>td
    {
        padding: 4px 10px 4px 10px;
    }

</style>

<div id="wrapper">
    <div class="panel_s mbot10 H_scroll" id="H_scroll">
        <div class="panel-body _buttons">
            <span class="bold uppercase fsize18 H_title">
                <?=$title?>
            </span>
            <a class="btn btn-info mright5 test pull-right H_action_button">
               <?php echo _l('Export excel'); ?></a>
            <a class="btn btn-info mright5 test pull-right H_action_button" data-toggle="collapse" data-target="#search">
                <?php echo _l('search'); ?>
            </a>
            <div class="line-sp"></div>
            <a href="#" class="btn btn-info pull-right H_action_button" onclick="editAdvisory_lead()">
                <i class="lnr lnr-plus-circle" aria-hidden="true"></i>
                <?php echo _l('cong_button_add_advisory'); ?>
            </a>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div id="search" class="collapse">
                            <div class="col-md-3">
                                <?php echo render_input('name_lead', 'cong_name_lead');?>
                            </div>
                            <div class="col-md-3">
                                <?php echo render_input('code_advisory', 'cong_code_advisory');?>
                            </div>
                            <div class="col-md-3">
                                <?php echo render_input('code_lead', 'cong_code_lead');?>
                            </div>
                            <div class="col-md-3">
                                <?php echo render_input('vip_code', 'vip_code');?>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-md-3">
                                <?php
                                    $data_vip_rating = [
                                            ['id' => '1', 'name' => _l('cong_1_start')],
                                            ['id' => '2', 'name' => _l('cong_2_start')],
                                            ['id' => '3', 'name' => _l('cong_3_start')],
                                            ['id' => '4', 'name' => _l('cong_4_start')],
                                            ['id' => '5', 'name' => _l('cong_5_start')]
                                    ];
                                    echo render_select('vip_rating_lead', $data_vip_rating, ['id', 'name'], 'cong_vip_rating');
                                ?>
                            </div>
                            <div class="col-md-3">
                                <?php
                                    echo render_date_input('date_start', 'cong_date_start_expected')
                                ?>
                            </div>
                            <div class="col-md-3">
                                <?php
                                    echo render_date_input('date_end', 'cong_date_end_expected')
                                ?>
                            </div>
                            <div class="clearfix"></div>

                            <hr class="hr-panel-heading" />
                        </div>
                        <div  class="btn-group mbot15">
                            <button type="button" data-toggle="tab" class="btn font11 btn-filter filter_all btn-icon btn-info active"><?=_l('cong_all')?></button>
                            <?php if(!empty($client_detail)){?>
                                <?php foreach($client_detail as $key => $value){?>
                                        <button type="button"  id_data="<?=$value['id']?>" class="btn-filter btn font11 btn-icon btn-info">
                                            <?=$value['name']?>
                                        </button>
                                <?php } ?>
                            <?php } ?>
                            <?php echo form_hidden('procedure'); ?>
                        </div>
                        <div class="clearfix"></div>
                            <?php
                                $arrayTable = [
                                    _l('cong_fullcode_advisory'),
                                    _l('cong_name_system'),
	                                _l('cong_image'),
                                    _l('cong_code_client_system'),
	                                _l('cong_buy_'),
                                    _l('cong_code_prioritize'),
                                    _l('cong_status_active_advisory'),
                                    _l('cong_zcode'),
                                    _l('cong_criteria_one'),
                                    _l('cong_criteria_two'),
                                    _l('cong_product_other_buy'),
                                    _l('cong_address_other_buy'),
                                ];

                                if(empty($info_view_detail))
                                {
                                    $info_view_detail = get_table_where('tblclient_info_detail', ['view_modal' => 1]);
                                    foreach($info_view_detail as $key => $value)
                                    {
                                        $arrayTable[] = $value['name'];
                                    }
                                }
                                $arrayTable[] = _l('cong_date_contact');
                                $arrayTable[] = _l('cong_date_create_client');
                                $arrayTable[] = _l('cong_create_by');
                                $arrayTable[] = _l('cong_date_create_advisory');
                                $arrayTable[] = _l('cong_create_by_advisory');
                                $arrayTable[] = _l('cong_inbox_to_lead_client');
                                $arrayTable[] = _l('cong_lead_to_client_advisory');
                                $arrayTable[] = _l('cong_step_advisory');
                                $arrayTable[] = _l('cong_note_appointment');
                                $arrayTable[] = _l('cong_reason_spam');
                                $arrayTable[] = _l('cong_reason_stop_advisory');
                                $arrayTable[] = _l('cong_staff_appointment');

                            $experience = get_table_where('tblexperience_advisory', [], 'id desc');
                            foreach($experience as $key => $value)
                            {
                                $arrayTable[] = $value['name'];
                            }
//                            echo "<pre>";
//                            var_dump($arrayTable);;die();
                            ?>

                            <?php render_datatable($arrayTable,'advisory_lead'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_advisory_lead" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"></div>
<?php init_tail(); ?>
<!-- hoang -->
<link rel="stylesheet" type="text/css" href="<?= css('fixdatatable.css') ?>">
<script type="text/javascript" src="<?= js('datatables/jquery.dataTables.min.js') ?>"></script>
<script type="text/javascript" src="<?= js('datatables/dataTables.fixedColumns.min.js') ?>"></script>
<!-- //end -->
<script>

    var filterList = {
        'datestart' : '[name="date_start"]',
        'dateend' : '[name="date_end"]',
        'procedure' : '[name="procedure"]',
        'name_lead' : '[name="name_lead"]',
        'code_advisory' : '[name="code_advisory"]',
        'code_lead' : '[name="code_lead"]',
        'vip_code' : '[name="vip_code"]',
        'vip_rating_lead' : '[name="vip_rating_lead"]'
    };
    var TblAdvisory = initDataTableCustom('.table-advisory_lead', admin_url+'advisory_lead/table', [0], [0], filterList, [0, 'desc'], fixedColumns = {leftColumns: 3, rightColumns: 0});


    $.each(filterList, function(i, filter){
        $(filter).on('change', function(e){
            if($('.table-advisory_lead').hasClass('dataTable'))
            {
                TblAdvisory.ajax.reload();
            }
        })
    })

    function editAdvisory_lead(id = "", _this)
    {
        var button = $(_this);
        button.button({loadingText: '<?=_l('cong_please_wait')?>'});
        button.button('loading');
        var data = {};
        if (typeof (csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        if($.isNumeric(id))
        {
            data['id'] = id;
        }
        $.post(admin_url+'advisory_lead/getModal', data, function(data){
            $('#modal_advisory_lead').html(data);
            $('#modal_advisory_lead').modal('show');
        }).always(function() {
            button.button('reset')
        });
    }

    function deleteAdvisory_lead(id = "", _this) {
        if($.isNumeric(id))
        {
            if(confirm("<?=_l('cong_you_must_delete')?>"))
            {
                var button = $(_this);
                button.button({loadingText: '<?=_l('cong_please_wait')?>'});
                button.button('loading');
                var data = {};
                if (typeof (csrfData) !== 'undefined') {
                    data[csrfData['token_name']] = csrfData['hash'];
                }
                data['id'] = id;
                $.post(admin_url+'advisory_lead/delete_advisory_lead', data, function(data){
                    data = JSON.parse(data);
                    alert_float(data.alert_type, data.message);
                    if(data.success)
                    {
                        TblAdvisory.ajax.reload();
                    }
                }).always(function() {
                    button.button('reset')
                });
            }
        }
    }
    $('body').on('click', '.update_status_lead', function(e){
        var id_assigned  = $(this).attr('id-data');
        var status_procedure  = $(this).attr('status-procedure');
        var data = {};
        var button = $(this);
        button.button({loadingText: '<?=_l('cong_please_wait')?>'});
        button.button('loading');
        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        data['id'] = id_assigned;
        data['status_procedure'] = status_procedure;
        $.post(admin_url+'advisory_lead/update_status/', data, function(data){
            data = JSON.parse(data);
            if(data.success)
            {
                TblAdvisory.ajax.reload();
            }
            alert_float(data.alert_type, data.message);
        }).always(function() {
            button.button('reset')
        });
    })


    $('.table-advisory_lead').on('draw.dt', function() {
        var invoiceReportsTable = $(this).DataTable();
        var _table = $('.table-advisory_lead');
        var lengthTh = _table.find('thead tr th').length;

        var TD_child = _table.find('tbody').find('tr.TD_child');
        TD_child.find('td:nth-child(1)').attr('colspan', lengthTh);
        TD_child.find('td:gt(0)').remove();
    })

    $('body').on('shown.bs.popover', '.PopverSelect2', function(e){
        var id = $(this).attr('aria-describedby');
        $('#'+id).find(".SelectErience").select2({
            escapeMarkup: function(m) { return m; }
        });
    })

    function restore_advisory_lead(id = "", _this)
    {
        var button = $(_this);
        button.button({loadingText: '<?=_l('cong_please_wait')?>'});
        button.button('loading');
        var data = {};
        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        data['id'] = id;
        $.post(admin_url+'advisory_lead/restore_advisory_lead', data, function(data){
            data = JSON.parse(data);
            if(data.success)
            {
                TblAdvisory.ajax.reload();
            }
            alert_float(data.alert_type, data.message);
        }).always(function() {
            button.button('reset')
        });
    }
    function BreakAdvisory(id = "", status, _this)
    {
        var button = $(_this);
        button.button({loadingText: '<?=_l('cong_please_wait')?>'});
        button.button('loading');
        var data = {};
        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        data['id'] = id;
        data['status'] = status;
        $.post(admin_url+'advisory_lead/break_advisory', data, function(data){
            data = JSON.parse(data);
            if(data.success)
            {
                TblAdvisory.ajax.reload();
            }
            alert_float(data.alert_type, data.message);
        }).always(function() {
            button.button('reset')
        });
    }

</script>
<script>

    $(document).on('click', '.btn-filter',function(e){
        if(!$(this).hasClass('filter_all'))
        {
            var id_procedure = $(this).attr('id_data');
            $('input[name="procedure"]').val(id_procedure);
        }
        else
        {
            $('input[name="procedure"]').val('');
        }

        TblAdvisory.ajax.reload();
        $('.btn-filter').removeClass('active');
        $(this).addClass('active');
    })


    $('body').on('click', '.SaveErience', function(e){
        var button = $(this);
        button.button({loadingText: '<?=_l('cong_please_wait')?>'});
        button.button('loading');
        var select = $(this).parents('.popover-content').find('select.SelectErience');
        var id = $(select).attr('id-data');
        var id_detail = $(select).attr('id-detail');
        var name = $(select).attr('name');
        var value = $(select).val();
        var data = {
            id : id,
            [name] : value,
            id_detail : id_detail
        };
        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        $.post(admin_url+'advisory_lead/ChangeErience', data, function(data){
            data = JSON.parse(data);
            if(data.success)
            {
                TblAdvisory.ajax.reload();
            }
            alert_float(data.alert_type, data.message);
        }).always(function() {
            button.button('reset')
        });
    })

    $('body').on('click', '.close_popover', function(e){
       var btn =  $(this);
       var popover = btn.parents('.popover').attr('id');
       btn.parents('.popover').parents('td').find('.PopverSelect2[aria-describedby="'+popover+'"]').click();
    })

    $('body').on('click', '.AStatusAdvisory', function(e){
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
                TblAdvisory.ajax.reload();
            }
            alert_float(data.alert_type, data.message);
        }).always(function() {});
    })

    $('body').on('click', '.criteria', function(e){
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
                TblAdvisory.ajax.reload();
            }
            alert_float(data.alert_type, data.message);
        }).always(function() {});
    })
</script>


</body>
</html>
