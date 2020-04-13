<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                  <div class="_buttons">
                      <div class="col-md-3">
                          <form action="<?=base_url('turndownload/read_file')?>" id="form_excel" autocomplete="off" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                              <div class="form-group">
                                  <label for="company" class="control-label">Tên doanh nghiệp</label>
                                  <input type="file" id="file" name="file" class="form-control"  value="">
                              </div>
                          </form>
                      </div>

                      <div class="col-md-3 div_tb_file text-danger mtop30"></div>
                      <div class="clearfix"></div>
                      <div class="col-md-3">
                        <button class="btn btn-info" type="button" onclick="add_products_excel()">Upload file</button>
                      </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php init_tail(); ?>
<script>

    function add_products_excel() {
        var form = $('#form_excel');
        var file_data = $('input[type="file"]').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file_csv', file_data);
        form_data.append('csrf_token_name', csrfData.hash);
        $.ajax({
            url: form.attr('action'),
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (data) {
                if(data.success)
                {
                    var key = 0;
                    var count_end = data.result.length;
                    $.each(data.result, function(i,v){
                        if(v.code)
                        {
                            setTimeout(function(){
                                window.open('https://mysupership.com/deliveries/download?type=selected&code='+v.code, '_blank');
                                alert_float('success', 'Còn: '+(count_end-1)+' file');
                                $('.div_tb_file').html('<h4>Còn: '+(count_end-1)+' file</h4>');
                                count_end--;
                                }, 2500*(i+1));
                            key++;

                        }
                    })
                }
                else
                {
                    alert_float('danger', 'Không tìm thấy file');
                    $('.div_tb_file').html('<h4>Không tìm thấy file</h4>');
                }
            }
        });
    }
</script>
</body>
</html>
