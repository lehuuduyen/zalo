<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body _buttons">
                        <a href="#" onclick="new_other(); return false;" class="btn btn-info pull-left display-block"><?php echo _l('Thêm'); ?></a>
                    </div>
                </div>
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="clearfix"></div>
                        <?php render_datatable(array(
                            _l('id'),
                            _l('Tên'),
                            _l('CMND'),
                            _l('Số điện thoại'),
                            _l('Địa chỉ'),
                            _l('Người tạo'),
                            _l('Số dư đầu kỳ'),
                            _l('options')
                        ),'other'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="type" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('other_object/add_other_object'),array('id'=>'id_type')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="edit-title"><?php echo _l('Sửa đối tượng vay mượn'); ?></span>
                    <span class="add-title"><?php echo _l('Thêm đối tượng vay mượn'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="additional"></div>
                        <?php echo render_input('name','Tên'); ?>
                        <?php echo render_input('cmnd','CMND'); ?>
                        <?php echo render_input('address','Địa chỉ'); ?>
                        <?php echo render_input('phone','Số điện thoại'); ?>
                        <?php echo render_input('opening_balance','Số dư đầu kỳ'); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php init_tail(); ?>
<script>

    var id_object="";
    function view_init_department(id)
    {
        id_object=id;
        $('#type').modal('show');
        $('.add-title').addClass('hide');
        var data = {};
        if(typeof(csrfData))
        {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        jQuery.ajax({
            type: "post",
            url:admin_url+"other_object/get_row_rack/"+id,
            data: data,
            cache: false,
            success: function (data) {
                var json = JSON.parse(data);
//                if($data!="")
                {
                    $('#name').val(json.name);
                    $('#phone').val(json.phone);
                    $('#address').val(json.address);
                    $('#cmnd').val(json.cmnd);
                    $('#opening_balance').val(json.opening_balance);
                    jQuery('#id_type').prop('action',admin_url+'other_object/update_other_object/'+id);
                }
            }
        });
    }

    $(function(){
        initDataTable('.table-other', window.location.href, [1], [1], {}, [['6','asc'],['2','desc'],]);
        _validate_form($('form'),{
            name: {
                required: true,
                remote:{
                    url: site_url + "admin/other_object/name_other_object",
                    type:'post',
                    data: {
                        name:function(){
                            return $('input[name="name"]').val();
                        },
                        id:function(){
                            return id_object;
                        }
                    }
                }
            },
        },manage_contract_types);
        $('#type').on('hidden.bs.modal', function(event) {
            $('#additional').html('');
            $('#type input').val('');
            $('.add-title').removeClass('hide');
            $('.edit-title').removeClass('hide');
        });
    });
    function manage_contract_types(form) {
        var data = $(form).serialize();
        var url = form.action;
        $.post(url, data).done(function(response) {
            response = JSON.parse(response);
            if(response.success == true){
                alert_float('success',response.message);
            }
            $('.table-other').DataTable().ajax.reload();
            $('#type').modal('hide');
        });
        return false;
    }

    function new_other(){
        id_object="";
        $('#type').modal('show');
        $('.edit-title').addClass('hide');
        $('#name').val('');
        $('#cmnd').val('');
        $('#address').val('');
        $('#opening_balance').val('');
        $('#id_type').prop('action',admin_url+'other_object/add_other_object');
    }
    function edit_type(invoker,id){
        var name = $(invoker).data('name');
        $('#additional').append(hidden_input('id',id));
        $('#type input[name="name"]').val(name);
        $('#type input[name="address"]').val(address);
        $('#type input[name="cmnd"]').val(cmnd);
        $('#type input[name="name"]').val(name);
        $('#type').modal('show');
        $('.add-title').addClass('hide');
    }

    

</script>
</body>
</html>
