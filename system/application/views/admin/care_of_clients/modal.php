<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style>
    #s2id_id_product .select2-search-choice{
        padding-top: 1px;
        padding-left: 20px;
        padding-bottom: 1px;
        padding-right: 5px;
    }
    #s2id_id_product .select2-search-choice .select2-search-choice-close{
        top: 7px;
    }
</style>
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button group="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">
                <span class="title">
                    <?=(!empty($care_of_clients) ? _l('cong_update_care_of') : _l('cong_add_care_of') )?>
                </span>
            </h4>
        </div>
        <?php echo form_open('admin/care_of_clients/detail',array('id'=>'care_of_from')); ?>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                        <?php
                            if(empty($id_client)) {
                                echo '<div class="col-md-6">';
                                $value = !empty($care_of_clients) ? $care_of_clients->client : '';
                                echo render_select('client', $clients, ['userid', 'name_system'], 'cong_name_system', $value, [], [], '','c_customer with-ajax');
                                echo '</div>';
                            }
                            else
                            {
                                $customer = get_table_where("tblclients", ['userid' => $id_client], '', 'row');
                                echo "<div class='col-md-6'>
                                            <div class='form-group'>
                                                <label class='control-label'>"._l('cong_name_system')."</label>
                                                <div class='form-control'>".$customer->name_system."</div>
                                            </div>
                                      </div>";
                                echo '<input type="hidden" id="client" name="client" value="'.$id_client.'">';
                            }
                        ?>
                        <?php if(empty($care_of_clients->count_care_of)){ ?>
                            <div class="col-md-6">
                                <?php $value = !empty($care_of_clients->date) ? _dt($care_of_clients->date) : _dt(date('Y-m-d H:i:s'))?>
                                <?php echo render_datetime_input('date','cong_date_client_feedback', $value); ?>
                            </div>
                        <?php } ?>
                        <div class="clearfix"></div>

                        <div class="col-md-6">
                            <?php $theme_of = StatusThemeCare_of();?>
                            <?php $value = !empty($care_of_clients->theme_of) ? $care_of_clients->theme_of : '' ?>
                            <?php echo render_select('theme_of', $theme_of, ['id', 'name'], 'cong_theme_care_of_client', $value);?>
                        </div>
                        <div class="col-md-6">
                            <?php
                            $solution = care_solutions();
                            ?>
                            <?php $value = !empty($care_of_clients->solution) ? $care_of_clients->solution : ''?>
                            <?php echo render_select('solution', $solution, array('id', 'name'), 'cong_solution_care_of_client', $value); ?>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-6">
                        <?php
                             $value = !empty($care_of_clients) ? $care_of_clients->id_orders : '';
                             echo render_select('id_orders', $orders, ['id', 'full_code'], 'cong_orders', $value, [], [], '','orders with-ajax');
                        ?>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" app-field-wrapper="id_product">
                                <label for="id_product" class="control-label"><?=_l('cong_product')?></label>
                                <input id="id_product" name="id_product" style="width:100%" value="<?=!empty($care_of_clients->id_product->group_product) ? $care_of_clients->id_product->group_product : '';?>"/>
                            </div>
                        </div>
                        <div class="clearfix"></div>

                        <div class="col-md-12">
                            <?php $value = !empty($care_of_clients->note) ? $care_of_clients->note : '' ?>
                            <?php echo render_textarea('note', 'cong_note_care_of_client', $value)?>
                        </div>
                        <?php $id = !empty($care_of_clients->id) ? $care_of_clients->id : '' ?>
                        <?php echo form_hidden('id', $id); ?>
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
    var pls_option_select = "<?=_l('cong_pls_selected_option')?>";
    var lang_money = "<?=_l('cong_money')?>";
    $(function(){
       appValidateForm($('#care_of_from'), {
           client: 'required',
           date: 'required',
           status_procedure: 'required'
        }, manage_advisory);

        selectpicker_jax_customer('#client', '<?=admin_url('orders/getCustomerAjax')?>');

        selectpicker_jax_orders('#id_orders', '<?=admin_url('care_of_clients/getOrderAjax')?>', {client:'#client'});

        ajaxSelect2ImgCallBack('#id_product', "<?=admin_url('care_of_clients/getProductAjax')?>", '<?=!empty($care_of_clients->id_product->group_product) ? $care_of_clients->id_product->group_product : '';?>', '', {orders : '#id_orders'});

        function manage_advisory(form) {
            var button = $('#care_of_from').find('button[type="submit"]');
            button.button({loadingText: '<?=_l('cong_please_wait')?>'});
            button.button('loading');
            var data = $(form).serialize();
            var url = form.action;
            $.post(url, data).done(function(response) {
                console.log(response);
                response = JSON.parse(response);
                if (response.success == true) {
                    if($.fn.DataTable.isDataTable('.table-care_of_clients')){
                        if(TableCare_of)
                        {
                            TableCare_of.ajax.reload();
                        }
                        else
                        {
                            $('.table-care_of_clients').DataTable().ajax.reload();
                        }
                    }
                    alert_float('success', response.message);
                    var id_facebook = $('#id_facebook').val();
                    if(id_facebook)
                    {
                        varInfoUser(id_facebook);
                    }
                }
                $('#modal_care_of_clients').modal('hide');
            }).always(function() {
                button.button('reset')
            });
            return false;
        }
        $('#care_of_from').find('.selectpicker').selectpicker('refresh');
        init_datepicker();

        $('body').on('change', '#client', function(e){
            $('#id_orders').html('').selectpicker('refresh').trigger('change');
        })

        $('body').on('change', '#id_orders', function(e){
            $('#id_product').val('');
            ajaxSelect2ImgCallBack('#id_product', "<?=admin_url('care_of_clients/getProductAjax')?>", '', '', {orders : '#id_orders'});
        })

    })

</script>
