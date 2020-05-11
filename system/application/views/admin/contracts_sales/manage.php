<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="panel_s mbot10 H_scroll" id="H_scroll">
        <div class="panel-body _buttons">
            <span class="bold uppercase fsize18 H_title"><?=!empty($title) ? $title : ''?></span>
            <?php if(has_permission('contracts_sales','','create')){ ?>
                <div class="line-sp"></div>
                <a href="<?php echo admin_url('contracts_sales/detail'); ?>" class="btn btn-info pull-right H_action_button">
                    <i class="lnr lnr-plus-circle" aria-hidden="true"></i>
                    <?php echo _l('create_add_new'); ?>
                </a>
            <?php } ?>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="panel_s">
                <div class="panel-body">
                    <div class="horizontal-scrollable-tabs">
                        <div class="scroller scroller-left arrow-left disabled" style="display: block;"><i class="fa fa-angle-left"></i></div>
                        <div class="scroller scroller-right arrow-right" style="display: block;"><i class="fa fa-angle-right"></i></div>
                        <div class="horizontal-tabs">
                            <ul class="nav nav-tabs nav-tabs-horizontal" role="tablist">
                                <li class="active">
                                    <a class="H_filter" data-id="">
                                        <?=_l('cong_all')?> <b class="filter_"></b>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <input type="hidden" id="filterStatus" name="filterStatus" value=""/>
                    <div class="clearfix mtop20"></div>
                    <?php $table_data = array(
                        _l('#'),
                        _l('code_contract_sales'),
                        _l('clients'),
                        _l('title_contract_sales'),
                        _l('Mã báo giá'),
                        _l('als_staff'),
                        _l('contract_value'),
                        _l('date_start'),
                        _l('date_end'),
                        _l('ch_option'),
                    );
                    render_datatable($table_data, 'contracts_sales');
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
$(function(){
    var CustomersServerParams = {
      'filterStatus' : '[name="filterStatus"]',
    };
    var tAPI = initDataTable('.table-contracts_sales', admin_url+'contracts_sales/table', [0], [0], CustomersServerParams,[1, 'desc']);
    $.each(CustomersServerParams, function(filterIndex, filterItem){
      $('' + filterItem).on('change', function(){
        tAPI.ajax.reload();
      });
    });
});
$(document).on('click', '.delete-remind', function() {
    var r = confirm("<?php echo _l('confirm_action_prompt');?>");
    if (r == false) {
        return false;
    } else {
        $.get($(this).attr('href'), function(response) {
          alert_float(response.alert_type, response.message);
            $('.table-contracts_sales').DataTable().ajax.reload();
        }, 'json');
    }
    return false;
});
</script>
</body>
</html>
