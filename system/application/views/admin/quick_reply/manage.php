<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <?php if(has_permission('promotion','','create')){ ?>
    <div class="panel_s mbot10 H_scroll" id="H_scroll">
        <div class="panel-body _buttons">
            <span class="bold uppercase fsize18 H_title"><?=$title?></span>
            <a class="btn btn-info pull-right H_action_button" onclick="edit_quick_reply(); return false;">
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
                            _l('name_quick_reply'),
                            _l('content_quick_reply'),
                            _l('ch_option'),
                        ),'quick_reply'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function(){
        initDataTable('.table-quick_reply', admin_url+'quick_reply/table_quick_reply', [0], [0], [],<?php echo hooks()->apply_filters('customers_table_default_order', json_encode(array(0,'asc'))); ?>);
    });
    function edit_quick_reply(id = "") {
        var data = {};
        if (typeof(csrfData) !== 'undefined') {
          data[csrfData['token_name']] = csrfData['hash'];
        }
        $.post(admin_url+'quick_reply/getData/'+id, data).done(function(response){
           $('#cong_modal').html(response);
            $('#quick_reply_modal').modal('show');
        });
    }

    function delete_quick_reply(id) {
        var data = {};
        if (typeof(csrfData) !== 'undefined') {
          data[csrfData['token_name']] = csrfData['hash'];
        }
        $.post(admin_url+'quick_reply/delete_quick_reply/'+id, data).done(function(response){
            response = JSON.parse(response);
            alert_float(response.alert_type, response.message);
            $('.table-quick_reply').DataTable().ajax.reload();
        });
    }
    $('body').on('click', '.rolChild', function(e){
        if($(this).find('i').hasClass('fa-caret-down'))
        {
            $(this).find('i').removeClass('fa-caret-down');
            $(this).find('i').addClass('fa-caret-up');
        }
        else
        {
            $(this).find('i').removeClass('fa-caret-up');
            $(this).find('i').addClass('fa-caret-down');
        }
    })

</script>
</body>
</html>
