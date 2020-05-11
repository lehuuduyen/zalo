<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <?php $this->load->view('admin/suppliers/profile'); ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    // initDataTable('.table-contracts_tab', admin_url+'suppliers/init_contracts_tab','undefined', 'undefined', {}, [2, 'ASC']);
</script>
<script type="text/javascript">
    var userid=<?=(!empty($supplier->userid)?$supplier->userid:'');?>;
    _validate_form($('.supplier-form'),{
        company: {
            required: true,
            remote:{
                url: site_url + "admin/suppliers/name_supplier",
                type:'post',
                data: {
                    company:function(){
                        return $('input[name="company"]').val();
                    },
                    userid:function(){
                        return userid;
                    }
                }
            }
        },
        phonenumber:'required',
    });


$(document).ready(function(){


    initDataTable('.table-contracts_tab', admin_url+'contracts/init_contract_staff','undefined', 'undefined', {}, [2, 'ASC']);

    var default_city  = '<?php echo isset($supplier) ? $supplier->city : 0 ?>';
    var default_state = '<?php echo isset($supplier) ? $supplier->state : 0 ?>';
    var default_ward  = '<?php echo isset($supplier) ? $supplier->address_ward : 0?>';

    function loadFromCity(city_id, currentTarget, default_value_state, default_value_ward){
      var objState = $(currentTarget).parent().parent().next().find('select');
      var objWard = $(currentTarget).parent().parent().next().next().find('select');
      objState.find('option').remove();
      objState.append('<option value=""></option>');
      objWard.find('option').remove();
      objWard.append('<option value=""></option>');

      objState.selectpicker("refresh");
      objWard.selectpicker("refresh");

      if(city_id != 0 && city_id != '') {
        $.ajax({
          url : admin_url + 'clients/get_districts/' + city_id,
          dataType : 'json',
        })
        .done(function(data){
          objState.find('option').remove();
          objState.append('<option value=""></option>');
          var foundSelected = false;
          $.each(data, function(key,value){
            var stringSelected = "";
            if(!foundSelected && value.districtid == default_value_state) {
              stringSelected = ' selected="selected"';
              foundSelected = true;
            }
            objState.append('<option value="' + value.districtid + '"'+stringSelected+'>' + value.name + '</option>');
          });
          objState.selectpicker('refresh');
          if(foundSelected) {
            loadFromState(default_value_state, objState, default_value_ward);
          }
        });
      }
    };
    function loadFromState(state_id, currentTarget, default_value_ward){
      var objWard = $(currentTarget).parent().parent().next().find('select');

      objWard.find('option').remove();
      objWard.append('<option value=""></option>');
      objWard.selectpicker("refresh");
      if(state_id != 0 && state_id != '') {
        $.ajax({
          url : admin_url + 'clients/get_wards/' + state_id,
          dataType : 'json',
        })
        .done(function(data){
          $.each(data, function(key,value){
            var stringSelected = "";
            if(value.wardid == default_value_ward) {
              stringSelected = 'selected="selected"';
            }
            objWard.append('<option value="' + value.wardid + '"' + stringSelected + '>' + value.name + '</option>');
          });
          objWard.selectpicker('refresh');
        });
      }
    };
    loadFromCity(default_city, $('#city'), default_state, default_ward);
    $('#city').change(function(e){
      var city_id = $(e.currentTarget).val();
      loadFromCity(city_id, e.currentTarget, default_state, default_ward);
    });
    $('#state').change(function(e){
      var state_id = $(e.currentTarget).val();
      loadFromState(state_id, e.currentTarget, default_ward);
    });
});


initDataTable('.table-contracts_tab', '<?=admin_url('suppliers/init_contracts_tab/'.$supplier->userid)?>', [0], [0], {},[1,'DESC']);
initDataTable('.table-_exports_tab', '<?=admin_url('suppliers/init_exports/'.$supplier->userid)?>', [1], [1], {},[1,'DESC']);
initDataTable('.table-imp_internal','<?=admin_url('suppliers/init_imp_internal/'.$supplier->userid)?>', [1], [1], {},[1,'DESC']);
</script>
</body>
</html>
