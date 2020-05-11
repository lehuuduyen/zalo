<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
  #region_id-error {
    position: absolute;
    top: 40px;
    left: 0;
  }
  
</style>
<div id="wrapper">
  <div class="content">
    <?php echo form_open(admin_url('create_order/add_new_region'),array('id' => 'add_new_region')); ?>
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><?php echo "Khai Báo Vùng Miền"; ?></h4>
        </div>

        <div class="modal-body">
          <div class="form-group">
            <label for="">Tỉnh</label>
            <input class="form-control" type="text" name="city" id="city" value="<?php echo $_GET['province'] ?>">
          </div>

          <div class="form-group">
            <label for="">Huyện/Thành Phố</label>
            <input class="form-control" type="text" name="district" id="district" value="<?php echo $_GET['district'] ?>">
          </div>



          <div class="form-group">
            <label for="">Thuộc vùng miền chính sách nào</label>
            <select data-live-search="true" class="form-control selectpicker" id="region_id" name="region_id">
              <option value="NULL">Chọn Chính Sách</option>
              <?php foreach ($tbldeclared_region as $key => $value): ?>
                <option  value='<?php echo $value->id ?>'><?php echo $value->name_region ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
          <button type="submit"  id="disable_ask" class="btn  btn-primary"><?php echo _l('confirm'); ?></button>
        </div>
      </div><!-- /.modal-content -->
    <?php echo form_close(); ?>
  </div>
</div>



<?php init_tail(); ?>

<script>
var checkAlert = <?php echo isset($_SESSION['error_default_region']) ? 'true' : 'false'?>;

if (checkAlert) {
	alert_float('danger','Đã Tồn tại');
}
$.validator.addMethod("valueNotEquals", function(value, element, arg){
 return arg !== value;
}, "Hãy chọn");


  $('#add_new_region').validate({
    errorClass: 'error text-danger',
    highlight: function(element) {
      $(element).parent().addClass("has-error");
    },
    unhighlight: function(element) {
      $(element).parent().removeClass("has-error");
    },
    onfocusout: false,
    invalidHandler: function(form, validator) {
        var errors = validator.numberOfInvalids();
        if (errors) {
            validator.errorList[0].element.focus();
        }
    },
    rules: {
      region_id: {
        valueNotEquals: "NULL",
      },
      city:{
        required:true
      },
      district:{
        required:true
      }
    }
  });
</script>
