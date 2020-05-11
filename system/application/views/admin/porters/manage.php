<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body _buttons">
                        <a href="#" onclick="new_rack(); return false;" class="btn btn-info pull-left display-block"><?php echo _l('Thêm bốc vác mới'); ?></a>
                    </div>
                </div>
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="clearfix"></div>
                        <?php render_datatable(array(
                            _l('id'),
                            _l('Tên'),
                            _l('Số điện thoại'),
                            _l('Email'),
                            _l('Địa chỉ'),
                            _l('Số dư đầu kỳ'),
                            _l('options')
                        ),'porters'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="type" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('porters/add_porters'),array('id'=>'id_type')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="edit-title"><?php echo _l('Bốc vác'); ?></span>
                    <span class="add-title"><?php echo _l('Thêm bốc vác'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="additional"></div>
                        <?php echo render_input('name','Tên'); ?>
                        <?php echo render_input('phone','Số điện thoại'); ?>
                        <?php echo render_input('email','email'); ?>
                        <?php echo render_input('address','Địa chỉ'); ?>
                        <?php echo render_input('opening_balance','Số dư đầu kỳ'); ?>
                        <?php echo render_textarea('note','Ghi chú'); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php init_tail(); ?>
<script>

    var id_port='';
    function view_init_department(id)
    {

        id_port = id;
        $('#type').modal('show');
        $('.add-title').addClass('hide');
        var data = {};
        if(typeof(csrfData))
        {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        $.ajax({
            type: "post",
            url: admin_url+"porters/get_row_porters/"+id,
            data: data,
            cache: false,
            success: function (data) {
                var json = JSON.parse(data);
                $('#name').val(json.name);
                $('#phone').val(json.phone);
                $('#email').val(json.email);
                $('#address').val(json.address);
                $('#note').val(json.note);
                $('#opening_balance').val(json.opening_balance);
                $('#id_type').prop('action',admin_url+'porters/update_porters/'+id);
            }
        });
    }

    $(function(){
        initDataTable('.table-porters', window.location.href, [1], [1]);
        _validate_form($('form'),{
            name: {
                required: true,
                remote:{
                    url: site_url + "admin/porters/name_porters",
                    type:'post',
                    data: {
                        name:function(){
                            return $('input[name="name"]').val();
                        },
                        id:function(){
                            return id_port;
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
            $('.table-porters').DataTable().ajax.reload();
            $('#type').modal('hide');
        });
        return false;
    }

    function new_rack(){
        id_port='';
        $('#type').modal('show');
        $('.edit-title').addClass('hide');
        $('#name').val('');
        $('#phone').val('');
        $('#email').val('');
        $('#address').val('');
        $('#note').val('');
        $('#supplier').val('').selectpicker('refresh');
        $('#opening_balance').val('');
        $('#id_type').prop('action',admin_url+'porters/add_porters');
    }
    function edit_type(invoker,id){
        var name = $(invoker).data('name');
        $('#additional').append(hidden_input('id',id));
        $('#type input[name="porters"]').val(name);
        $('#type').modal('show');
        $('.add-title').addClass('hide');
    }

    

</script>
</body>
</html>
