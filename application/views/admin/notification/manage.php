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


</style>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body _buttons">
                    <h4 class="bold no-margin"><?=_l('Lịch thông báo')?></h4>
                    <hr class="no-mbot no-border">
                        <a href="#" onclick="add_notification('')" class="btn btn-info pull-left mright5 display-block"><?php echo _l('Thêm lịch thông báo'); ?></a>
                </div>
                <div class="clearfix"></div>
                <div class="panel_s">
                    <div class="panel-body">
                        <input type="hidden" id="filterStatus" value="0"/>
                        <div class="col-md-4">
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
                                echo render_select('filter_type_notification',$type_notification,array('id','name'),'Loại nhắc');
                            ?>
                        </div>
                        <div class="clearfix"></div>

                        <table class="table table-striped table-notification dataTable no-footer dtr-inline" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Loại thông báo</th>
                                    <th>Ngày bắt đầu</th>
                                    <th>Ngày nhắc tiếp theo</th>
                                    <th>Ngày cuối nhắc</th>
                                    <th>Nội dung</th>
                                    <th>Tình trạng</th>
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
        <?php echo form_open('admin/notification/order_detail',array('id' => 'form_notification')); ?>
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
                                        array('id'=>'weeks','name'=>'tuần'),
                                        array('id'=>'months','name'=>'Tháng'),
                                        array('id'=>'years','name'=>'Năm')
                                );
                             ?>
                             <?php echo render_select('type_notification',$type_notification,array('id','name'),'Loại nhắc');?>
                             <?php echo render_input('number','Chu kì nhắc(Số Ngày,Tuần,Tháng,Năm)') ?>
                             <?php echo render_input('number_date','Số lần nhắc(bỏ trống để không giới hạn)') ?>
                             <?php echo render_date_input('date_start','Ngày bắt đầu') ?>
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
            'filterStatus' : '[id="filterStatus"]'
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
            $('#form_notification').prop('action','<?=admin_url('notification/order_detail')?>');
            $('.title-notification').html('Thêm bảng thông báo');
            $('#type_notification').val('').selectpicker('refresh');
            $('#number').val('');
            $('#number_date').val('');
            $('#date_start').val('');
            $('#note').val('');
        }
        else
        {
            var data = {id:id};
            if (typeof (csrfData) !== 'undefined') {
                data[csrfData['token_name']] = csrfData['hash'];
            }

            $.post("<?=admin_url('notification/get_notification')?>", data).done(function(form) {
                obj = JSON.parse(form);
                $('#type_notification').val(obj.type_notification).selectpicker('refresh');
                $('#number').val(obj.number);
                $('#number_date').val(obj.number_date);
                $('#note').val(obj.note);
                $('#date_start').val(obj.date_start);
                $('.title-notification').html('Cập nhật bảng thông báo');
                $('#form_notification').prop('action','<?=admin_url('notification/order_detail/')?>'+id);

            }), !1
        }
        $('#modal_notification').modal('show');
    }

    function view_status(status,id)
    {
        dataString={id:id,status:status};
        jQuery.ajax({
            type: "post",
            url:"<?=admin_url()?>notification/update_status",
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
        appValidateForm($("#form_notification"), {
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
</script>

