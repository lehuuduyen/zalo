<?php init_head(); ?>

<style>
    @media (min-width: 992px) {
        .col-md-2-2 {
            width: 14% !important;
        }
    }
    .point-ev{
        pointer-events: none;
    }
.menu-item-bookcash{ padding: 10px;
    border: 2px dashed #000;
    color: #000;
    text-transform: uppercase;
    padding: 12px 20px 12px 16px;
    font-size: 13px;
    text-align: center;
    font-weight: bold;
 }
.width200{
    width: 200px;
}
.bg-info {
    background-color: #d9edf7!important;
}
fieldset {
    padding: .35em .625em .75em!important;
    margin: 0 2px!important;
    border: 1px solid #19a9ea!important;
}
legend {
    font-size: 15px;
    font-weight: 500;
    width: auto!important;
}
.span-tag {
    padding: 2px 5px 2px 5px;
    background: #fff;
    color: #e47724;
    border: 1px solid #e47724;
    line-height: 2;
    font-weight: 400;
    font-size: 13px;
    border-radius: 3px;
}



</style>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body _buttons">
                        <h4 class="bold no-margin"><?=_l('Lịch thông báo zalo đến nhân viên')?></h4>
                        <div class="col-md-4">
                            <hr class="no-mbot no-border">
                            <a href="#" onclick="add_notification('')" class="btn btn-info pull-left mright5 display-block mtop20"><?php echo _l('Thêm lịch thông báo'); ?></a>
                        </div>
                        <div class="col-md-4"></div>
                        <div class="hide">
                            <?php echo form_open_multipart(admin_url() . 'notification_zalo/read_excel', array('id' => 'form_excel', 'autocomplete' => 'off')); ?>
                            <input type="file" name="file_csv" onchange="add_products_excel()">
                            <?php echo form_close(); ?>
                        </div>
                        <div class="col-md-4">
                            <fieldset>
                                <legend>Thêm thông báo zalo nhanh(không lưu vào danh sách)</legend>
                                <a href="<?=base_url('uploads/file_send_zalo_demo.xlsx')?>" title="file mẫu" class="mleft20 btn  btn-default form-group">
                                    <i class="fa fa-download" aria-hidden="true"></i>
                                </a>
                                <button type="button" onclick="check_file()" class="mleft20 btn btn-default form-group">
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                </button>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="panel_s">
                    <div class="panel-body">
                        <input type="hidden" id="filterStatus" value="0"/>
                        <div class="col-md-4 hide">
                            <div data-toggle="btn" class="btn-group mbot15">
                                <button style=" font-size: 11px;" type="button" id="btnDatatableFilterNotApproval" data-toggle="tab" class="btn btn-info active">Đang hoạt động</button>
                                <button style=" font-size: 11px;" type="button" id="btnDatatableFilterApproval" data-toggle="tab" class="btn btn-info">Kết thúc hoạt động</button>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-3">
                            <?php
                            $type_notification=array(
                                array('id'=>'days','name'=>'Ngày'),
                                array('id'=>'weeks','name'=>'Tuần'),
                                array('id'=>'months','name'=>'Tháng'),
                                array('id'=>'years','name'=>'Năm')
                            );
                            ?>
                            <?php
                                echo render_select('filter_type_notification', $type_notification,array('id','name'),'Loại nhắc');
                            ?>
                        </div>
                        <div class="clearfix"></div>

                        <table class="table table-striped table-notification dataTable no-footer dtr-inline" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Loại thông báo</th>
                                    <th>Chu kỳ</th>
                                    <th>Ngày tiếp theo</th>
                                    <th>Ngày kết thúc</th>
                                    <th>Nội dung</th>
                                    <th>Nhân viên nhận</th>
                                    <th>SĐT(ZALO) bổ sung</th>
                                    <th>Người tạo</th>
                                    <th>Thuộc tính</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div id="modal_notification" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <?php echo form_open(admin_url('notification_zalo/order_detail'), ['id' => 'form_notification', 'autocomplete' => 'off']); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title title-notification">Thêm phiếu</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                         <div class="col-md-12">
                             <?php
                                $type_notification=array(
                                        array('id'=>'days','name'=>'Ngày'),
                                        array('id'=>'weeks','name'=>'Tuần'),
                                        array('id'=>'months','name'=>'Tháng'),
                                        array('id'=>'years','name'=>'Năm')
                                );
                             ?>
                             <?php echo render_select('type_notification', $type_notification,array('id','name'),'Loại nhắc');?>
                             <?php echo render_input('number','Chu kì nhắc(Số Ngày,Tuần,Tháng,Năm)') ?>
                             <?php echo render_input('number_date','Số lần nhắc(bỏ trống để không giới hạn)') ?>
                             <?php echo render_date_input('date_start','Ngày bắt đầu') ?>
                             <div class="checkbox checkbox-primary">
                                 <input type="checkbox" value="1" name="staff_all" id="staff_all">
                                 <label for="staff_all" data-toggle="tooltip" data-original-title="" title="">Tất cả nhân viên</label>
                             </div>
                            <div class="div_staff">
                                <?php echo render_select('staff[]',$staff,array('staffid',['lastname', 'firstname']),'Nhân viên',array(),array('multiple'=>'true'));?>
                            </div>
                             <?php echo render_textarea('phone_add','Số điện thoại bổ sung(cách nhau dấu phẩy)') ?>
                             <?php echo render_textarea('note','Nội dung'); ?>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info"><?=_l('submit')?></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>

    </div>
</div>
<?php init_tail(); ?>
<script type="text/javascript">


    $(function(){
        $('#btnDatatableFilterNotApproval').click(function(){
            $('input[id="filterStatus"]').val(0);
            $('input[id="filterStatus"]').change();
        });
        $('#btnDatatableFilterApproval').click(function(){
            $('input[id="filterStatus"]').val(2);
            $('input[id="filterStatus"]').change();
        });

         $('[data-toggle="btn"] .btn').on('click', function(){
            var $this = $(this);
            $this.parent().find('.active').removeClass('active');
            $this.addClass('active');
        });
        var filterList = {
            'filter_client' : '[id="filter_client"]',
            'filterStatus' : '[id="filterStatus"]',
            'filter_type_notification' : '[id="filter_type_notification"]',
        };
        initDataTable('.table-notification', window.location.href, [1], [1],filterList,[[1,'DESC'],[0,'DESC']]);
         $.each(filterList, function(filterIndex, filterItem){
            $(filterItem).on('change',function()
            {
                $('.table-notification').DataTable().ajax.reload();
            });
        });
    });
    $('body').on('click', '._delete-remind', function() {
        var r = confirm(confirm_action_prompt);

        if (r == false) {
            return false;
        } else {
            $.get($(this).attr('href'), function(response) {
                alert_float(response.alert_type, response.message);
                $('.table-notification').DataTable().ajax.reload();
            }, 'json');
        }
        return false;
    });

    function formatNumber(nStr, decSeperate=".", groupSeperate=",") {
        nStr += '';
        x = nStr.split(decSeperate);
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + groupSeperate + '$2');
        }
        return x1 + x2;
    }

    function add_notification(id='')
    {

        $('#form_notification').find('button[type="submit"]').removeClass('hide');
        if(id=="")
        {
            $('#form_notification').prop('action','<?=admin_url('notification_zalo/order_detail')?>');
            $('.title-notification').html('Thêm bảng thông báo zalo');
            $('#type_notification').val('').selectpicker('refresh');
            $('#number').val('');
            $('#number_date').val('');
            $('#date_start').val('');
            $('#phone_add').val('');
            $('#note').val('');
            $('select[name="staff[]"]').val('').selectpicker('refresh');
            $('.div_staff').addClass('hide');
            $('#staff_all').prop('checked',true);
        }
        else
        {
            $.post("<?=admin_url('notification_zalo/get_notification')?>", {id:id}).done(function(form) {
                var obj = JSON.parse(form);
                $('#type_notification').val(obj.type_notification).selectpicker('refresh').trigger('change');
                $('#number').val(obj.number).selectpicker('refresh');
                $('#number_date').val(obj.number_date);
                $('#date_start').val(obj.date_start);
                $('#note').val(obj.note);
                $('#phone_add').val(obj.phone_add);
                var dataarray=obj.staff;
                $('select[name="staff[]"]').selectpicker('val',dataarray).selectpicker('refresh');
                $('.title-notification').html('Cập nhật bảng thông báo zalo nhân viên');
                $('#form_notification').prop('action','<?=admin_url('notification_zalo/order_detail/')?>'+id);
                if(obj.staff_all==1)
                {
                    $('.div_staff').addClass('hide');
                    $('#staff_all').prop('checked',true);
                }
                else
                {
                    $('.div_staff').removeClass('hide');
                    $('#staff_all').prop('checked',false);
                }

            }), !1
        }
        $('#modal_notification').modal('show');
    }


    $('body').on('change','#type_notification',function(e){

        $('#number').html('<option></option>').selectpicker('refresh');
        if($(this).val()=='days')
        {
            for(var i = 1; i < 32; i++)
            {
                $('#number').append('<option value="'+i+'">Ngày '+i+'</option>');
            }
        }
        if($(this).val()=='weekday')
        {
            var weekday={
                'Monday':'Thứ 2',
                'Tuesday':'Thứ 3',
                'Wednesday':'Thứ 4',
                'Thursday':'Thứ 5',
                'Friday':'Thứ 6',
                'Saturday':'Thứ 7',
                'Sunday':'Chủ nhật'
            };
            $.each(weekday,function(i,v){
                $('#number').append('<option value="'+i+'">Ngày '+v+'</option>');
            })
        }
        $('#number').selectpicker('refresh');
    })

    function view_status(status,id)
    {
        dataString={id:id,status:status};
        jQuery.ajax({
            type: "post",
            url:"<?=admin_url()?>notification_zalo/update_status",
            data: dataString,
            cache: false,
            success: function (response) {
                response = JSON.parse(response);
                if (response.success == true) {
                    $('.table-notification').DataTable().ajax.reload();
                    alert_float('success', response.message);
                }
                else
                {
                    alert_float('danger', response.message);
                }
                return false;
            }
        });

    }

    $(function() {
        _validate_form($("#form_notification"), {
            date_start: "required",
            type_notification: "required",
            number: "required",
            note: "required"
        }, manage_cash)
    });
    function manage_cash(form) {
        var data = $(form).serialize(),
            action = form.action;
        return $.post(action, data).done(function(form) {
            form = JSON.parse(form), alert_float(form.alert_type, form.message),
                $('.table-notification').DataTable().ajax.reload(),
                $('#modal_notification').modal('hide');
        }), !1
    }

    $('body').on('change','#staff_all',function(e){
        if($(this).prop('checked'))
        {
            $('.div_staff').addClass('hide');
        }
        else
        {
            $('.div_staff').removeClass('hide');
        }
    })


    function check_file() {
        $('input[name="file_csv"]').click();
    }

    function add_products_excel() {
        var form = $('#form_excel');
        var file_data = $('input[type="file"]').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file_csv', file_data);
        $.ajax({
            url: form.attr('action'),
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (data) {
                alert_float(data.type_alert,data.message);
            }
        });
    }
</script>

