<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <?php if(has_permission('promotion','','create')){ ?>
    <div class="panel_s mbot10 H_scroll" id="H_scroll">
        <div class="panel-body _buttons">
            <span class="bold uppercase fsize18 H_title"><?=$title?></span>
            <a href="<?=admin_url('promotion/detail');?>" class="btn btn-info pull-right H_action_button">
                <i class="lnr lnr-plus-circle" aria-hidden="true"></i>
                <?php echo _l('create_add_new'); ?>
            </a>
        </div>
    </div>
    <?php } ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <?php render_datatable(array(
                            _l('#'),
                            _l('promotion_list_name'),
                            _l('promotion_name'),
                            _l('promotion_type'),
                            _l('promotion_method_of_application'),
                            _l('promotion_area_of_application'),
                            _l('clients'),
                            _l('promotion_area'),
                            _l('promotion_time'),
                            _l('ch_option'),
                        ),'promotion_detail'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<link rel="stylesheet" type="text/css" href="<?= css('fixdatatable.css') ?>">
<script type="text/javascript" src="<?= js('datatables/jquery.dataTables.min.js') ?>"></script>
<script type="text/javascript" src="<?= js('datatables/dataTables.fixedColumns.min.js') ?>"></script>
<script>
    $(function(){
        initDataTableCustom('.table-promotion_detail', admin_url+'promotion/table_promotion_detail', [0], [0], [],<?php echo hooks()->apply_filters('customers_table_default_order', json_encode(array(0,'asc'))); ?>);
    });
    function delete_promotion(id) {
        var data = {};
        if (typeof(csrfData) !== 'undefined') {
          data[csrfData['token_name']] = csrfData['hash'];
        }
        $.post(admin_url+'promotion/delete_promotion/'+id, data).done(function(response){
            response = JSON.parse(response);
            alert_float(response.alert_type,response.message);
            $('.table-promotion_detail').DataTable().ajax.reload();
        });
    }
</script>
</body>
</html>
