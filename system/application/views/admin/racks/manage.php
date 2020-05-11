<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body _buttons">
                        <a href="#" onclick="new_rack(); return false;" class="btn btn-info pull-left display-block"><?php echo _l('Thêm'); ?></a>
                    </div>
                </div>
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="clearfix"></div>
                        <?php render_datatable(array(
                            _l('id'),
                            _l('Tên'),
                            _l('Số điện thoại'),
                            _l('Trọng tải (Tấn)'),
                            _l('Các tuyến chạy'),
                            _l('Số dư đầu kỳ'),
                            _l('Loại'),
                            _l('options')
                        ),'racks'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="type" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('racks/add_rack'),array('id'=>'id_type')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="edit-title"><?php echo _l('Sửa lái xe'); ?></span>
                    <span class="add-title"><?php echo _l('Thêm lái xe'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="additional"></div>
                        <?php echo render_input('rack','Tên'); ?>
                        <?php echo render_input('note','Số điện thoại'); ?>
                        <?php echo render_input('route','Các tuyến chạy'); ?>
                        <?php echo render_input('gross_ton','Trọng tải(Tấn)'); ?>
                        <?php echo render_input('opening_balance','Số dư đầu kỳ'); ?>
                        <div class="checkbox checkbox-primary">
                            <input type="checkbox" value="1" id="not_freight" name="not_freight">
                            <label for="not_freight" data-toggle="tooltip" data-original-title="" title="">Không tính cước xe</label>
                        </div>
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

    var id_rack="";
    function view_init_department(id)
    {
        id_rack=id;
        $('#type').modal('show');
        $('.add-title').addClass('hide');
        var data = {};
        if(typeof(csrfData))
        {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        $.ajax({
            type: "post",
            url:admin_url+"racks/get_row_rack/"+id,
            data: data,
            cache: false,
            success: function (data) {
                var json = JSON.parse(data);
//                if($data!="")
                {
                    $('#rack').val(json.rack);
                    $('#note').val(json.note);
                    $('#gross_ton').val(json.gross_ton);
                    $('#route').val(json.route);
                    $('#opening_balance').val(json.opening_balance);
                    if(json.not_freight==1)
                    {
                        $('#not_freight').prop('checked',true);
                    }
                    else
                    {
                        $('#not_freight').prop('checked',false);
                    }
                    jQuery('#id_type').prop('action',admin_url+'racks/update_rack/'+id);
                }
            }
        });
    }

    $(function(){
        initDataTable('.table-racks', window.location.href, [1], [1]);
        _validate_form($('form'),{
            rack: {
                required: true,
                remote:{
                    url: site_url + "admin/racks/name_racks",
                    type:'post',
                    data: {
                        rack:function(){
                            return $('input[name="rack"]').val();
                        },
                        id:function(){
                            return id_rack;
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
            $('.table-racks').DataTable().ajax.reload();
            $('#type').modal('hide');
        });
        return false;
    }

    function new_rack(){
        id_rack='';
        $('#type').modal('show');
        $('.edit-title').addClass('hide');
        $('#rack').val('');
        $('#opening_balance').val('');
        $('#route').val('');
        $('#gross_ton').val('');
        $('#not_freight').prop('checked',false);
        $('#id_type').prop('action',admin_url+'racks/add_rack');
    }
    function edit_type(invoker,id){
        var name = $(invoker).data('name');
        $('#additional').append(hidden_input('id',id));
        $('#type input[name="rack"]').val(name);
        $('#type').modal('show');
        $('.add-title').addClass('hide');
    }



</script>
</body>
</html>
