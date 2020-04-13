<?php init_head(); ?>

<style>

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
 .menu-item-bookcash a:hover{
    cursor: pointer;
 }
 .totalcashbook{margin-bottom: 10px !important;}
.totalcashbook span {text-align: center !important;}
}


</style>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">

                    <div class="panel_s">
                        <div class="panel-body _buttons">
                        <h4 class="bold no-margin"><?=_l('Điều chỉnh công nợ')?></h4>
                        <hr class="no-mbot no-border">
                        <?php if(has_permission('debit_object','','create')){?>
                            <a href="#" onclick="add_debit_object('')" class="btn btn-info pull-left mright5 display-block"><?php echo _l('Thêm phiếu'); ?></a>
                        <?php }?>
                        </div>
                    </div>
                <div class="clearfix"></div>
                <div class="panel_s">
                    <div class="panel-body">
                        <div>
                            <div class="col-md-3">
                                <?php $array_object_search=array(
                                    array('id' => 'tblstaff','name' => 'nhân viên'),
                                    array('id' => 'tblcustomers','name' => 'Khách hàng'),
                                    array('id' => 'tblsuppliers','name' => 'Nhà cung cấp'),
                                    array('id' => 'tblracks','name' => 'Lái xe'),
                                    array('id' => 'tblporters','name' => 'Bốc vác'),
                                    array('id' => 'tblother_object','name' => 'Vay-Mượn')
                                );?>
                                <?php echo render_select('object_search',$array_object_search,array('id','name'),'Đối tượng');?>

                            </div>
                            <div class="col-md-3">
                                <?php echo render_date_input('date_start', 'Ngày bắt đầu', _d($date_start));?>

                            </div>
                            <div class="col-md-3">
                                <?php echo render_date_input('date_end', 'Ngày kết thúc', _d($date_end));?>
                            </div>

                            <div class="col-md-3">
                                <?php $array_type=array(
                                    array('id' => '1', 'name' => 'Tính chi phí công ty'),
                                    array('id' => '2', 'name' => 'Không tính chi phí công ty')
                                );?>
                                <?php echo render_select('type', $array_type,array('id', 'name'),'Loại điều chỉnh');?>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <table class="table table-striped table-debit_object dataTable no-footer dtr-inline">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Mã</th>
                                    <th>Ngày tháng</th>
                                    <th>Đối tượng</th>
                                    <th>Số tiền điều chỉnh</th>
                                    <th>Loại</th>
                                    <th>Lý do điều chỉnh</th>
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



<div id="modal_debit_object" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <?php echo form_open(admin_url('debit_object/order_detail'), array('id'=>'form_debit_object','autocomplete'=>'off')); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Thêm phiếu</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                         <div class="col-md-12">
                            <div class="col-md-6">
                                <?php echo render_date_input('date', 'Ngày Tháng', _d(date('Y-m-d'))) ?>
                                <?php echo render_textarea('note', 'Lý do điều chỉnh') ?>
                                <div class="form-group">
                                    <div class="radio radio-primary radio-inline">
                                        <input type="radio" name="type" id="type_1" value="0">
                                        <label for="type_0">Tính chi phí công ty</label>
                                    </div>
                                    <div class="radio radio-primary radio-inline">
                                        <input type="radio" name="type" id="type_0" value="0">
                                        <label for="type_0">Không tính chi phí công ty</label>
                                    </div>
                                </div>
                            </div>

                             <div class="col-md-6" >
                                 <?php echo render_input('price','Số tiền điều chỉnh','','text',array('onkeyup'=>'formatNumBerKeyUp(this)')); ?>
                                 <?php $array_object=array(
                                         array('id'=>'','name'=>''),
//                                         array('id'=>'tblstaff','name'=>'nhân viên'),
                                         array('id'=>'tblcustomers','name'=>'Khách hàng'),
//                                         array('id'=>'tblsuppliers','name'=>'Nhà cung cấp'),
//                                         array('id'=>'tblracks','name'=>'Lái xe'),
//                                         array('id'=>'tblporters','name'=>'Bốc vác'),
                                         array('id'=>'tblother_object','name'=>'Vay-Mượn')
                                 );?>
                                <div class="form-group">
                                    <label for="id_object" class="control-label">Đối tượng</label>
                                    <select id="id_object" name="id_object" class="selectpicker" data-width="100%" data-none-selected-text="Không có gì được chọn" data-live-search="true" tabindex="-98">
                                        <?php foreach ($array_object as $key=>$value){?>
                                            <option value="<?=$value['id']?>"><?=$value['name']?></option>
                                        <?php }?>
                                    </select>
                                </div>
                                 <?php echo render_select('staff_id', $staff, array('staffid', ['lastname', 'firstname']), 'Danh sách đối tượng');?>
                            </div>
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
         $('[data-toggle="btn"] .btn').on('click', function(){
            var $this = $(this);
            $this.parent().find('.active').removeClass('active');
            $this.addClass('active');
        });
        $('#btnDatatableFilterAll').click(function(){

            $('#filterStatus').val('');
            $('#filterStatus').change();
        });

        var filterList = {
            'filterStatus' : '[id="filterStatus"]',
            'object_search' : '[id="object_search"]',
            'date_start' : '[id="date_start"]',
            'date_end' : '[id="date_end"]',
            'type' : '[id="type"]'
        };
        initDataTable('.table-debit_object', window.location.href, [1], [1], filterList, [[7,'ASC'],[2,'DESC']] );
         $.each(filterList, function(filterIndex, filterItem){
            $(filterItem).on('change',function(){
                $('.table-debit_object').DataTable().ajax.reload();
            });
        });
    });
    function var_status(status,id)
    {
        dataString={id:id,status:status};
        jQuery.ajax({
            type: "post",
            url:"<?=admin_url()?>debit_object/update_status",
            data: dataString,
            cache: false,
            success: function (response) {
                response = JSON.parse(response);
                if (response.success == true) {
                    $('.table-debit_object').DataTable().ajax.reload();
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
    $('body').on('click', '.delete-remind,.delete-reminder', function() {
        var r = confirm(confirm_action_prompt);
        var table='.table-debit_object';
        if (r == false) {
            return false;
        } else {
            $.get($(this).attr('href'), function(response) {
                alert_float(response.alert_type, response.message);
                    if ($.fn.DataTable.isDataTable(table)) {
                        $('body').find(table).DataTable().ajax.reload();
                    }
            }, 'json');
        }
        return false;
    });


    function add_debit_object(id='')
    {
        $('#form_debit_object').find('button[type="submit"]').removeClass('hide');
        if(id=="")
        {
            $('#modal_debit_object').modal('show');
            $('#form_debit_object').prop('action','<?=admin_url('debit_object/order_detail')?>');
            $('#type_no').attr('checked','checked');
            $('#price').val('');
            $('#note').val('');
            $('#id_object').val('').selectpicker('refresh').trigger('change');
            $('input[name="type"]').prop('checked', false);
        }
        else
        {
            $.post("<?=admin_url('debit_object/get_cash')?>", {id:id}).done(function(form) {
                obj = JSON.parse(form);
                $('#date').val(obj.date);
                $('#id_object').val(obj.id_object).selectpicker('refresh');
                check_data(obj.staff_id);
                $('#note').val(obj.note);
                $('#price').val(formatNumber(obj.price));
                $('#staff_id').val(obj.staff_id).selectpicker('refresh');
                $('#modal_debit_object').modal('show');
                $('#form_debit_object').prop('action','<?=admin_url('debit_object/order_detail/')?>'+id);
                if(obj.type==1)
                {
                    $('input[name="type"][value="1"]').prop('checked', true);
                }
                else
                {
                    $('input[name="type"][value="0"]').prop('checked', true);
                }


            }), !1
        }
    }
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

    $(function() {
        _validate_form($("#form_debit_object"), {
            date: "required",
            note: "required",
            price: "required",
            id_object: "required",
            staff_id: "required",
            type: "required"
        }, manage_cash)
    });
    function manage_cash(form) {
        var data = $(form).serialize(),
            action = form.action;
        return $.post(action, data).done(function(form) {
            form = JSON.parse(form), alert_float(form.alert_type, form.message),
                $('.table-debit_object').DataTable().ajax.reload(),
                $('#modal_debit_object').modal('hide');
            if(form.add)
            {
                // window.open(admin_url+'receipts/pdf/'+form.add+'?print=true','_blank');
            }

        }), !1
    }

    $('body').on('click', '._delete-remind', function() {
        var r = confirm(confirm_action_prompt);

        if (r == false) {
            return false;
        } else {
            $.get($(this).attr('href'), function(response) {
                if(response.success)
                {
                  alert_float('success', response.message);
                }
                $('.table-debit_object').DataTable().ajax.reload();
            }, 'json');
        }
        return false;
    });


    $('body').on('change','#id_object',function(data){
        var id=$(this).val();
        if(id)
        {
            $('.staff_not').hide();
            $('.staff_yes').show();
            $('#staff_id_not').val();
            $.post("<?=admin_url('debit_object/get_object')?>", {id:id}).done(function(form) {
                obj = JSON.parse(form);
                var_option="<option></option>";
                $.each(obj,function(i,v){
                    var_option+="<option value='"+v.id+"'>"+v.name+"</option>";
                })
                $('#staff_id').html(var_option).selectpicker('refresh');
                return true;
            })
        }
        else
        {
            $('.staff_not').show();
            $('.staff_yes').hide();
            $('#staff_id').val('').selectpicker('refresh');
            return true;
        }
    })

    function check_data(data){
        var id=$('#id_object').val();
        if(id)
        {
            $.post("<?=admin_url('debit_object/get_object')?>", {id:id}).done(function(form) {
                obj = JSON.parse(form);
                var_option="<option></option>";
                $.each(obj,function(i,v){
                    var_option+="<option value='"+v.id+"'>"+v.name+"</option>";
                })
                $('#staff_id').html(var_option).selectpicker('refresh').val(data).selectpicker('refresh');
                return true;
            })
        }
        else
        {
            $('#staff_id').val('').selectpicker('refresh');
            return true;
        }
    }

     $('.table-debit_object').on('draw.dt', function() {
     var invoiceReportsTable = $(this).DataTable();
     var sums = invoiceReportsTable.ajax.json().sum;
     if (sums!='undefined'){
        $('.totaldebit_object').text(sums+' VNĐ');
     }
     <?php foreach ($payments_modes as $key=>$value){?>
         var total_payment_<?=$value['id']?>=invoiceReportsTable.ajax.json().total_payment_<?=$value['id']?>;
         if (total_payment_<?=$value['id']?>!='undefined'){
             $('.total_payments_<?=$value['id']?>').text(total_payment_<?=$value['id']?>+' VNĐ');
          }
      <?php }?>
   });
</script>

