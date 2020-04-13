<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button group="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">
                <span>
                    <?=(!empty($advisory_lead) ? _l('cong_update_advisory') : _l('cong_add_advisory') )?>
                </span>
            </h4>
        </div>
        <?php echo form_open('admin/messager/AddAdvisory_listfb_convert_lead',array('id' => 'advisory-modal')); ?>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="panel panel-info mbot0">
                        <div class="panel-heading"><?=_l('als_info_client')?></div>
                        <div class="panel-body">
                            <div>
                                <span class="bold uppercase">1. <?=_l('cong_date_contact')?>: </span><br>
                                <span class="js-date_contact"><?=(!empty($date_contact) ? ' - '.$date_contact : ' - ')?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="panel panel-info mbot0">
                        <div class="panel-heading"><?=_l('lead_general_info')?></div>
                        <div class="panel-body">
                            <?php
                                echo form_hidden('id_listfb', $id);
                            ?>
                            <?php echo render_date_input('date','cong_date_start', _d(date('Y-m-d'))); ?>

                            <?php echo render_select('status_first', $procedure_detail, array('id', 'name'), 'cong_status_procedure', ''); ?>

                            <?php echo render_input('product_other_buy', 'cong_product_other_buy', '');?>

                            <?php echo render_input('address_other_buy', 'cong_address_other_buy', '');?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button group="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            <button group="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<script>

    $(function(){
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
                        $('.table-advisory_lead').DataTable().ajax.reload();
                    }
                    alert_float('success', response.message);
                    var id_facebook = $('#id_facebook').val();
                    if(id_facebook)
                    {
                        varInfoUser(id_facebook);
                    }
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

</script>
