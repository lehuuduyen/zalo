<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button group="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">
                <span class="title">
                    <?=(!empty($advisory_lead) ? _l('cong_update_advisory') : _l('cong_add_advisory') )?>
                </span>
            </h4>
        </div>
        <?php echo form_open('admin/advisory_lead/detail',array('id' => 'advisory-modal')); ?>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="panel panel-info mbot0">
                        <div class="panel-heading"><?=_l('als_info_client')?></div>
                        <div class="panel-body">
                            <div>
                                <span class="bold uppercase">1. <?=_l('cong_date_contact_first')?>: </span><br>
                                <span class="js-date_contact"><?=(!empty($date_contact) ? ' - '._dt($date_contact) : ' - ')?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="panel panel-info mbot0">
                        <div class="panel-heading"><?=_l('cong_infomation_advisory_lead')?></div>
                        <div class="panel-body">
	                        <?php $object = !empty($advisory_lead) ? $advisory_lead->type_object.'_'.$advisory_lead->id_object : ''?>
	                        <?php $object_it = !empty($advisory_lead) ? $advisory_lead->type_object_it.'_'.$advisory_lead->id_object_it : ''?>
	                        <?php
	                        if(empty($object))
	                        {
		                        if(!empty($type_object) && !empty($id_object))
		                        {
			                        $object = $type_object.'_'.$id_object;
		                        }
	                        }
	                        ?>
                            <div class="col-md-6 row">
                                <div class="radio radio-primary checkbox-templates">
                                    <input type="radio" id="profile" <?php echo empty($object_it) ? 'checked' : ''?> value="1">
                                    <label for="profile">
                                        <?= _l('cong_give_profile') ?>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="radio radio-primary checkbox-templates">
                                    <input type="radio"  id="not_profile" <?php echo !empty($object_it) ? 'checked' : ''?> value="2">
                                    <label for="not_profile">
                                        <?= _l('cong_give_not_profile') ?>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group <?=!empty($type_object) && !empty($id_object) ? 'hide' : ''?>" >
                                <label for="object" class="control-label"><?= _l('cong_lead').' - '._l('cong_client') ?></label>
                                <input id="object" name="object" style="width: 100%" value="<?=$object?>">
                            </div>

                            <div class="DivProfile form-group <?=!empty($object_it) ? '' : 'hide'?>" >
                                <label for="object_it" class="control-label"><?= _l('cong_buy_it') ?></label>
                                <input id="object_it" name="object_it" style="width: 100%" value="<?= $object_it ?>">
                            </div>
                            <?php if(empty($advisory_lead->active)){ ?>
                                <?php $value = !empty($advisory_lead->date) ? _d($advisory_lead->date) : _d(date('Y-m-d'))?>
                                <?php echo render_date_input('date','cong_date_contact', $value); ?>

                                <?php $value = !empty($advisory_lead->status_first) ? $advisory_lead->status_first : (!empty($procedure_detail_active) ? $procedure_detail_active : '')?>
                                <?php echo render_select('status_first', $procedure_detail, array('id', 'name'), 'cong_status_procedure', $value); ?>
                            <?php } ?>

	                        <?php
	                        if(empty($info_view_detail))
	                        {
		                        $info_view_detail = get_table_where('tblclient_info_detail', ['view_modal' => 1]);
	                        }
	                        foreach($info_view_detail as $keyView => $vView) {?>
                                <?php $info_view_detail_value = get_table_where('tblclient_info_detail_value', ['id_info_detail' => $vView['id']]) ?>
                                <?php if($vView['type_form'] == 'select') {?>
                                    <div class="form-group">
                                        <label for="product_other_buy" class="control-label">
                                            <?=$vView['name']?>
                                        </label>
                                       <select class="selectpicker form-control" name="info[<?=$vView['id']?>]" data-none-selected-text="<?=_l('dropdown_non_selected_tex')?>" data-live-search="true" tabindex="-98">
                                           <option></option>
                                           <?php $advisory_info = get_table_where('tbladvisory_info_value', ['id_info' => $vView['id'], 'id_advisory' => $advisory_lead->id]); ?>
                                           <?php foreach($info_view_detail_value as $keyInfo => $valInfo) {
                                                $selected = '';
                                                if(!empty($advisory_info))
                                                {
                                                    foreach($advisory_info as $Kinfo => $Vinfo)
                                                    {
                                                        if($Vinfo['value_info'] == $valInfo['id'])
                                                        {
	                                                        $selected = 'selected';
	                                                        break;
                                                        }
                                                    }
                                                }
                                               ?>
                                               <option value="<?=$valInfo['id']?>" <?=$selected?>><?=$valInfo['name']?></option>
                                           <?php } ?>
                                       </select>
                                    </div>
                                <?php } ?>
	                        <?php } ?>

                            <?php $value = !empty($advisory_lead->product_other_buy) ? $advisory_lead->product_other_buy : ''?>
                            <?php echo render_input('product_other_buy', 'cong_product_other_buy', $value);?>

                            <?php $value = !empty($advisory_lead->address_other_buy) ? $advisory_lead->address_other_buy : ''?>
                            <?php echo render_input('address_other_buy', 'cong_address_other_buy', $value);?>

                            <?php $id = !empty($advisory_lead->id) ? $advisory_lead->id : '' ?>
                            <?php echo form_hidden('id', $id); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button group="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            <button group="submit" type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<script>

    $(function(){
        ajaxSelectGroupOption_C('#object', "<?=admin_url('advisory_lead/SearchObject')?>", '<?=$object?>', '');

        ajaxSelectGroupOption_C('#object_it', "<?=admin_url('advisory_lead/SearchObject')?>", '<?=$object_it?>', '');
        appValidateForm($('#advisory-modal'), {
           lead: 'required',
           date: 'required',
           status_first: 'required'
        }, manage_advisory);

        function manage_advisory(form) {
            var button = $('#advisory-modal').find('button[type="submit"]');
            button.button({loadingText: '<?=_l('cong_please_wait')?>'});
            button.button('loading');
            var data = $(form).serialize();
            var url = form.action;
            $.post(url, data).done(function(response) {
                console.log(response);
                response = JSON.parse(response);
                if (response.success == true) {
                    if($.fn.DataTable.isDataTable('.table-advisory_lead')){
                        if(TblAdvisory)
                        {
                            TblAdvisory.ajax.reload();
                        }
                        else
                        {
                            $('.table-advisory_lead').DataTable().ajax.reload();
                        }
                    }
                    var id_facebook = $('#id_facebook').val();
                    if(id_facebook)
                    {
                        varInfoUser(id_facebook);
                    }
                    alert_float('success', response.message);
                }
                $('#modal_advisory_lead').modal('hide');
            }).always(function() {
                button.button('reset')
            });
            return false;
        }
        $('#advisory-modal').find('.selectpicker').selectpicker('refresh');
        init_datepicker();
    })
    $('#object').change(function(e){
        var id_obj = $('#object').val();
        var data = {};
        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        $.post(admin_url+'advisory_lead/getConcerns/'+id_obj, data, function(data){
            data = JSON.parse(data);
            $.each(data.view_detail, function(i, v){

                $('.jsViewDetail-'+v.id).text(' - '+( (v.value && v.value.value_name) ? v.value.value_name : ''));
            })
            $('.js-date_contact').text(' - '+data.date_contact);
        });
        change_procedure_detail();

    })

    function change_procedure_detail() {
        $('#status_first').html('<option value=""></option>');
        var id_obj = $('#object').val();
        var data = {};
        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        $.post(admin_url+'advisory_lead/getProcedure_detail/'+id_obj, data, function(data){
            data = JSON.parse(data);
            $.each(data, function(k,v){
                $('#status_first').append('<option value="'+v.id+'">'+v.name+'</option>');
            });
            $('#status_first').selectpicker('refresh');
        });

    }

    $('#profile').click(function()
    {
        $('#not_profile').prop('checked', false);
        $('.DivProfile').addClass('hide');
        ajaxSelectGroupOption_C('#object_it', "<?=admin_url('advisory_lead/SearchObject')?>", '<?=$object_it?>', '');
    })
    $('#not_profile').click(function()
    {
        $('#profile').prop('checked', false);
        $('.DivProfile').removeClass('hide');
    })
</script>
