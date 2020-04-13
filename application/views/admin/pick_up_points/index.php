<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">

				<div class="panel_s">
					<div class="panel-body">
						<?php if(has_permission('staff','','create')){ ?>
						<div class="_buttons">
							<a style="margin-right:10px;" href="javascript:;" class="open-modal-addnew btn btn-info pull-left display-block"><?php echo _l('new_polycy'); ?></a>

						</div>
						<div class="clearfix"></div>
						<hr class="hr-panel-heading" />
						<?php } ?>
						<div class="clearfix"></div>
						<ul class="nav nav-tabs tab-show-data">
					    <li data-tab="tab1" class="active"><a href="javascript:;">Chưa Lấy</a></li>
					    <li data-tab="tab2"><a href="javascript:;">Đã lấy</a></li>
					  </ul>
						<div class="tab-cover">
							<div class="tab1 tab">
								<?php render_datatable(array(
										_l('Ngày Tạo'),
										_l('id'),
										_l('Tên Shop'),
										_l('Tên Shop'),
										_l('SĐT Shop'),
										_l('Kho'),
										_l('Ghi Chú'),
										_l('Trạng Thái'),
										_l('Trạng Thái'),
										_l('Ngày cập nhật'),
										_l('Ngày cập nhật'),
										_l('Ngày cập nhật'),
										_l('Ngày cập nhật'),
										_l('Người đăng ký lấy'),
										_l('hide'),
										_l('hide'),
										_l('options')
								),'pick_up_points'); ?>
							</div>

							<div class="tab2 tab" style="display:none">
								<?php render_datatable(array(
										_l('Ngày Lấy Hàng'),
										_l('hide'),
										_l('hide'),
										_l('Tên Shop'),
										_l('SĐT Shop'),
										_l('Kho'),
										_l('Ghi Chú'),
										_l('Trạng Thái'),
										_l('hide'),
										_l('hide'),
										_l('hide'),
										_l('hide'),
										_l('hide'),
										_l('hide'),
										_l('Nguời lấy'),
										_l('hide'),
										_l('Số Đơn'),
										_l('options')
								),'pick_up_points_picked'); ?>
							</div>
						</div>







					</div>
				</div>
			</div>
		</div>
	</div>
</div>



<div  class="modal fade" id="customer" tabindex="-1" role="dialog">
	<div  class="modal-dialog" role="document">
		<input type="hidden" id="admin_url" value="<?php echo admin_url(); ?>">
		<?php echo form_open(admin_url('pick_up_points/add'),array('id' => 'add_new_pick_up_points', )); ?>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Thêm Điểm Nhận Hàng</h4>
			</div>
			<div class="modal-body">
				<div class="row">
						<div class="col-md-12">

              <div class="form-group">


								<div class="form-group">
									<label for="created">Ngày Tạo</label>
									<input placeholder="Ngày chứng từ" type="text" class="form-control" id="created" name="created">
									<?php $id = get_staff_user_id();  ?>
									<input type="hidden" id="user_created" name="user_created" value="<?php echo $id; ?>">
								</div>

								<div class="form-group">
								  <label for="receive_or_pay">Loại Hàng:</label>
								  <select class="form-control" id="receive_or_pay" name="receive_or_pay">
								    <option value="0">Lấy hàng</option>
								    <option value="1">Trả hàng</option>
								  </select>
								</div>

								<div class="form-group">
								  <label for="type_customer">Loại Khách Hàng:</label>
								  <select class="form-control" id="type_customer" name="type_customer">
								    <option value="old">Khách Hàng Cũ</option>
								    <option value="new">Khách Hàng Mới </option>
								  </select>
								</div>


								<div class="form-group old_cus" style="position:relative">
	                <label for="customer_phone_zalo">Chọn Khách Hàng</label>

	                <input autocomplete="off" placeholder="Chọn Khách Hàng" class="form-control" id="search_customer" type="text" name="search_customer"  value="<?php echo set_value('search_customer'); ?>">
	                <i class="fa fa-search search-icon" aria-hidden="true"></i>

	                <ul class="list-group search-item">
	                  <?php foreach ($custommer as  $value): ?>

	                    <li  data-phone="<?php echo $value->customer_phone ?>" data-token="<?php echo $value->token_customer ?>" data-id="<?php echo $value->id ?>" class="list-group-item">
	                      <?php echo $value->customer_shop_code  ?>
	                    </li>
	                  <?php endforeach; ?>
	                </ul>



	                <input type="hidden" id="customer_id" name="customer_id" value="<?php echo set_value('customer_id'); ?>">
	              </div>


              </div>


							<div id="repo_customer_cover" class="form-group">

							</div>
							<div id="phone_customer_cover" class="form-group">

							</div>


							<div style="display:none" class="new-cus">
								<div class="form-group">
									<label for="name_customer_new">Nhập Tên Khách Hàng</label>
									<input placeholder="Nhập Tên Khách Hàng" type="text" class="form-control" id="name_customer_new" name="name_customer_new">
								</div>
								<div class="form-group">
									<label for="name_customer_new">Nhập SĐT Khách Hàng</label>
									<input placeholder="Nhập SĐT Khách Hàng" type="text" class="form-control" id="phone_customer_new" name="phone_customer_new">
								</div>



								<div class="form-group">
								  <label for="type_customer">Chọn Quận Huyện/Thành Phố:</label>
								  <select class="form-control" id="district" name="district">

								    <?php foreach ($district_hd as $key => $value): ?>
											<option  value='<?php echo json_encode($value) ?>'><?php echo $value->name ?></option>
								    <?php endforeach; ?>
								  </select>
								</div>

								<div class="form-group">
								  <label for="type_customer">Chọn Phường Xã:</label>
								  <div class="load-area">
										<select class="form-control" id="area_hd" name="area_hd">

									    <?php foreach ($area_hd as $key => $value): ?>
												<option  value='<?php echo json_encode($value) ?>'><?php echo $value->name ?></option>
									    <?php endforeach; ?>
									  </select>
								  </div>
								</div>

								<div class="form-group">
									<label for="free_address">Nhập Địa Chỉ</label>
									<input placeholder="Nhập Địa Chỉ" type="text" class="form-control" id="free_address" name="free_address">
								</div>

							</div>

							<div id="loader-repo" class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>

							<div class="form-group">
								<label for="note">Ghi Chú :</label>
								<textarea style="resize:none; height:121px;" class="form-control" rows="5" id="note" name="note"></textarea>
							</div>

							<div class="hidden-form">
								<input type="hidden" id="district_filter" name="district_filter" value="">
								<input type="hidden" id="commune_filter" name="commune_filter" value="">
								<input type="hidden" id="address_filter" name="address_filter" value="">

							</div>

            </div>
					</div>



			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
				<button type="submit"  class="btn btn-primary submit_customer_policy"><?php echo _l('confirm'); ?></button>
			</div>
		</div><!-- /.modal-content -->
		<?php echo form_close(); ?>
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<div class="modal fade" id="modal-confirm-order" role="dialog">
  <div class="modal-dialog">


      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Nhập Số Đơn</h4>
        </div>
        <div class="modal-body">

            <div class="form-group">
              <label for="pwd">Nhập Số Đơn</label>
              <input required type="text" placeholder="Nhập Số Đơn" class="form-control" id="order_get" name="order_get" onkeyup="formatNumBerKeyUp(this)">
							<input type="hidden" id="set_id" value="">
            </div>
						<div class="form-group">
							<label for="user_geted">Người Lấy</label>

							<select name="user_geted" id="user_geted_number" class="form-control">

							  <?php foreach ($tblstaff as $key => $value): ?>
									 <option  value="<?php echo $value->staffid ?>"><?php echo $value->firstname ?></option>
							  <?php endforeach; ?>
							</select>
						</div>








        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
          <a href="javascript:;" name="button" class="btn btn-primary confirm-number">Xác Nhận</a>
        </div>
      </div>

  </div>
</div>


<div class="modal fade" id="modal-confirm-order-staff-add" role="dialog">
  <div class="modal-dialog">


      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Nhập Số Đơn</h4>
        </div>
        <div class="modal-body">

            <div class="form-group">
              <label for="pwd">Nhập Số Đơn</label>
              <input required type="text" placeholder="Nhập Số Đơn" class="form-control" id="order_get_number" name="order_get_number" onkeyup="formatNumBerKeyUp(this)">
							<input type="hidden" id="set_id" value="">
            </div>


						<div class="form-group">
							<label for="staff">Chọn Người lấy</label>
							<select class="form-control" name="user_geted" id="user_geted">
								<option value="NULL">Chọn Người Lấy</option>
								<?php foreach ($tblstaff as $key => $staff): ?>
									<option value="<?php echo $staff->staffid ?>">
										<?php echo $staff->firstname.' '.$staff->lastname; ?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>




        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
          <a href="javascript:;" name="button" class="btn btn-primary confirm-number-staff">Xác Nhận</a>
        </div>
      </div>

  </div>
</div>

<?php init_tail(); ?>

<div class="disable-view">

</div>
<script src="/system/assets/plugins/select2/index.js"></script>
<script type="text/javascript">

  $(function(){

		var data = initDataTable('.table-pick_up_points', $('#admin_url').val()+'pick_up_points' , [1], [0,1,2,3,4,5,6,7,8,9,10,11,12],{},[0,'desc']);
		data.column(1).visible(false);
		data.column(2).visible(false);
		data.column(8).visible(false);
		data.column(9).visible(false);
		data.column(10).visible(false);
		data.column(11).visible(false);
		data.column(12).visible(false);
		data.column(14).visible(false);
		data.column(15).visible(false);






		var data1 = initDataTable('.table-pick_up_points_picked', $('#admin_url').val()+'pick_up_points/indexPicked' , [1], [0,1,2,3,4,5,6,7,8,9,10,11,12],{},[0,'desc']);
		data1.column(1).visible(false);
		data1.column(2).visible(false);
		data1.column(8).visible(false);
		data1.column(9).visible(false);
		data1.column(10).visible(false);
		data1.column(11).visible(false);
		data1.column(12).visible(false);
		data1.column(13).visible(false);
		data1.column(15).visible(false);
		data1.column(17).visible(false);
	});





	$(document).on('click', '.tab-show-data li', function(e){
		var tab = $(this).attr('data-tab');
		$('.tab').hide();
		$('.tab-show-data li').removeClass('active');
		$('.'+tab).show();
		$(this).addClass('active');
	})
  function setValid() {

    $("#add_new_pick_up_points").validate().destroy();

    $("#add_new_pick_up_points").validate({
      errorClass: 'error text-danger',
      highlight: function(element) {
        $(element).parent().addClass("has-error");
      },
      unhighlight: function(element) {
        $(element).parent().removeClass("has-error");
      },
      ignore: [],
      rules: {
        customer_id: {
          required: true,
        }
      }

    });
  }

  var checkAlert = <?php echo isset($_SESSION['success']) ? 'true' : 'false'?>;

  if (checkAlert) {
    alert_float('success','Thêm Thành Công');
  }
	var checkAlertdelete_ok = <?php echo isset($_SESSION['delete_ok']) ? 'true' : 'false'?>;

  if (checkAlertdelete_ok) {
    alert_float('success','Xoá Thành Công');
  }

	var checkAlertsuccess_edit = <?php echo isset($_SESSION['success_edit']) ? 'true' : 'false'?>;

  if (checkAlertsuccess_edit) {
    alert_float('success','Sửa Thành Công');
  }




  $('.open-modal-addnew').click(function() {
		var action = $('#admin_url').val()+'/Pick_up_points/add';
    $('#customer').modal('show');
		$( "#created" ).datepicker().datepicker("setDate", new Date());
		$('.modal-title').text('Thêm Điểm Nhận Hàng');
		$('#type_customer').val('old');
		$('#receive_or_pay').val('0');
		$('#repo_customer').empty();
		$('#search_customer').val('');
		$('#note').val('');
		$('#district_filter').val('');
		$('#commune_filter').val('');
		$('#address_filter').val('');
		$('#name_customer_new').val('');
		$('#phone_customer_new').val('');
		$('#free_address').val('');
		$('#phone_customer').val('');
		$("#district").val($("#district option:first").val());
		$('.new-cus').hide();
		$('.old_cus').show();
    setValid();

  });



  $(document).on('click', '.search-item li', function(e){
		e.stopPropagation();
		var id = $(this).attr('data-id');
		var token = $(this).attr('data-token');
		var phone = $(this).attr('data-phone');

		var text = $(this).text();

		$('#search_customer').val(text.trim());
		$('.search-item').hide();
		$('#customer_id').val(id);
		$('.disable-view').show();
		$('#loader-repo').show();

		$.ajax({
	    url: '/system/admin/pick_up_points/curlGetRepo',
	    method: 'POST',
	    data: {token},
	    success: function(data){
				$('.disable-view').hide();
				$('#loader-repo').hide();
	      data = JSON.parse(data);

				var html = '<label for="repo_customer">Chọn Kho:</label><select class="form-control" id="repo_customer" name="repo_customer">';

				// $('#repo_customer_cover');
				for (var i = 0; i < data.length; i++) {
					html += `<option value="${data[i].formatted_address.replace(", Tỉnh Hải Dương" , "")}">${data[i].formatted_address.replace(", Tỉnh Hải Dương" , "")}</option>`;
				}
				html += '</select>';
				$('#repo_customer_cover').empty();
				$('#repo_customer_cover').append(html);

				var phoneHtml = '<label for="phone_customer">Số Điện Thoại Khách Hàng:</label>';
				phoneHtml += `<input autocomplete="off" placeholder="Phone Khách Hàng" class="form-control" id="phone_customer" type="text" name="phone_customer" value="${phone}">`;
				$('#phone_customer_cover').empty();
				$('#phone_customer_cover').append(phoneHtml);
				setDataHidden(data[0]);
	    },
			error:function(e) {
				console.log(e);
			}
  	});

	});

	function setDataHidden(data) {
		data = data.formatted_address.split(',');
		$('#address_filter').val(data[0]);
		$('#commune_filter').val(data[1]);
		$('#district_filter').val(data[2]);
	}

	$(document).on('change', '#repo_customer', function(){
		var data = $(this).val();
		setDataHidden(data)
	});
	$(document).on('keyup', '#free_address', function(){
		var data = $(this).val();
		$('#address_filter').val(data);
	});
	$(document).on('change', '#area_hd', function(){
		var data = $(this).val();
		$('#commune_filter').val(JSON.parse(data).name);
	});




	$(document).on('focus', '#search_customer', function(){
		$('.search-item').show();
	});
	$(document).on('focusout', '#search_customer', function(e){
		setTimeout(function () {
			$('.search-item').hide();
		}, 400);

	});
	$(document).on('keyup', '#search_customer', function(){
		$('.search-item').show();
		// Retrieve the input field text and reset the count to zero
		var filter = $(this).val(), count = 0;

		// Loop through the comment list
		$(".search-item li").each(function(){

				// If the list item does not contain the text phrase fade it out
				if ($(this).text().search(new RegExp(filter, "i")) < 0) {
					$(this).fadeOut();

				// Show the list item if the phrase matches and increase the count by 1
				} else {
					$(this).show();
					count++;
				}
		});

		// Update the count
		var numberItems = count;
			$("#filter-count").text("Number of Filter = "+count);
		});

	$(document).on('click', '.delete-reminder-custom', function(){
		var c = confirm("Bạn Muốn Xoá Thực Sự");
		if (!c) {
			return false;
		}
	});




	function setRepoWhenEdit(data) {
		var html = '<label for="repo_customer">Chọn Kho:</label><select class="form-control" id="repo_customer" name="repo_customer">';
		html += `<option value="${data}">${data}</option>`;
		html += '</select>';
		$('#repo_customer_cover').empty();
		$('#repo_customer_cover').append(html);
	}
	function setFromData(data) {


		var value = Object.values(data);
		var key = Object.keys(data);

		if (data.customer_id === '0') {
			$('#search_customer').parent().hide();
			$('#type_customer option[value="new"]').attr('selected','selected');
		}
		for (var i = 0; i < value.length; i++) {

			if ($(`#add_new_pick_up_points [name="${key[i]}"]`).length) {
				if (key[i] == 'customer_id') {

					if (data.customer_id !== "0") {
						$.ajax({
								url: '/system/admin/pick_up_points/getCustomer/'+value[i],
								success: function (data) {
									data = JSON.parse(data);

									$('#search_customer').val(data.customer_shop_code);
									$('#customer_id').val(data.id);

								},
								error:function(e) {
									console.log(e);
								}
						});
					}

				}
				if (data.customer_id !== "0") {
					setRepoWhenEdit(data.repo_customer);
				}

				$(`#add_new_pick_up_points [name="${key[i]}"]`).val(value[i]);
			}
		}

		if (data.customer_id  === "0") {


			$('.new-cus').show();
			$('#phone_customer_new').val(data.phone_customer);
			$('#free_address').val(data.repo_customer.split(",")[0]);
			// console.log($(`#district option[value='${data.district_code}']`));


			$( "#district option" ).each(function( index ) {

				if (JSON.parse(data.district_code).code === JSON.parse($(this).attr('value')).code) {
					$(this).attr('selected','selected');
				}

			});

			SetAreaEdit(JSON.parse(data.district_code).code,data.areas_code);


			$(`#district option[value='${data.district_code}']`).attr('selected','selected');


		}

	}
	function SetAreaEdit(code,compareData) {
		$.ajax({
			url: '/system/admin/pick_up_points/get_commune_by_hd/'+code,
			method: 'GET',
			success: function(data){
				$('.disable-view').hide();
				$('#loader-repo').hide();
				data = JSON.parse(data);

				$('#area_hd').empty();
				var html = '';

				for (var i = 0; i < data.length; i++) {
					html+= `<option  value='${JSON.stringify(data[i])}'>${data[i].name}</option>`;
				}
				$('#area_hd').append(html);


				$( "#area_hd option" ).each(function( index ) {

					if (JSON.parse(compareData).code === JSON.parse($(this).attr('value')).code) {
						$(this).attr('selected','selected');
					}

				});

			},
			error:function(e) {
				console.log(e);
			}
		});
	}
	$(document).on('click', '.edit-customer', function(){

		var id = $(this).attr('data-id');
		var action = $('#admin_url').val()+'pick_up_points/edit/'+id;
		$('#add_new_pick_up_points').attr('action', action);
		$('.modal-title').text('Sửa Điểm Nhận Hàng');

		$.ajax({
				url: '/system/admin/pick_up_points/getDataEdit/'+id,
				success: function (data) {
					data = JSON.parse(data);



					$('#customer').modal('show');
					setValid();
					setFromData(data);

					$( "#created" ).datepicker().datepicker("setDate", new Date(data.created));

				},
				error:function(e) {
					console.log(e);
				}
		});

	});


	$(document).on('change', '#type_customer', function(){
		var val = $(this).val();
		$('#district_filter').val('');
		$('#commune_filter').val('');
		$('#address_filter').val('');

		if (val === 'new') {
			$( "#customer_id" ).rules( "remove", "required" );
			$( "#name_customer_new" ).rules( "add", "required" );
			$( "#phone_customer_new" ).rules( "add", "required" );
			$( "#free_address" ).rules( "add", "required" );
			$('#search_customer').parent().hide();
			$('.new-cus').show();
			$('#repo_customer_cover').hide();
			$('#phone_customer_cover').hide();
			$('#district_filter').val(JSON.parse($('#district').val()).name);
			$('#commune_filter').val(JSON.parse($('#area_hd').val()).name);
			$('#address_filter').val($('#free_address').val());

		}
		else {
			$( "#customer_id" ).rules( "add", "required" );

			$( "#name_customer_new" ).rules( "remove", "required" );
			$( "#phone_customer_new" ).rules( "remove", "required" );
			$( "#free_address" ).rules( "remove", "required" );
			$('#search_customer').parent().show();
			$('.new-cus').hide();
			$('#repo_customer_cover').show();
			$('#phone_customer_cover').show();
			if ($('#repo_customer').val()) {
				var dataSet = $('#repo_customer').val().split(',');

				$('#address_filter').val(dataSet[0]);
				$('#commune_filter').val(dataSet[1]);
				$('#district_filter').val(dataSet[2]);

			}

		}


	});


	$(document).on('change', '#district', function(){
		var val = JSON.parse($(this).val());
		$('#district_filter').val(val.name);

		$('.disable-view').show();
		$('#loader-repo').show();

		$.ajax({
	    url: '/system/admin/pick_up_points/get_commune_by_hd/'+val.code,
	    method: 'GET',
	    success: function(data){
				$('.disable-view').hide();
				$('#loader-repo').hide();
	      data = JSON.parse(data);

				$('#area_hd').empty();
				var html = '';

				for (var i = 0; i < data.length; i++) {
					html+= `<option  value='${JSON.stringify(data[i])}'>${data[i].name}</option>`;
				}
				$('#area_hd').append(html);
				$('#commune_filter').val(data[0].name);

	    },
			error:function(e) {
				console.log(e);
			}
  	});

	});

	$('#modal-confirm-order-staff-add').on('hide.bs.modal', function (e) {
		$('.check-change-status-number').removeAttr('checked');
	});

	$(document).on('click', '.edit-number-order', function(){
		var id = $(this).attr('data-id');
		var user_geted = $(this).attr('data-user_geted');
		var number = $(this).parent().find('input').val();
		$('#set_id').val(id);
		$('#user_geted_number').val(user_geted);
		$('#order_get').val(number);
		$('#modal-confirm-order').modal('show');
	})

	$(document).on('click', '.check-change-status', function(){
		var dom = $(this).parent();
		var id = $(this).parent().attr('data-id');
		var status;
		if ($(this)[0].checked) {
			status = true;

		}else {
			status = false;

		}
		data = {id , status , mod:true , number_order_get:0 };

		$('#loader-repo').show();

		$.ajax({
			url: '/system/admin/pick_up_points/edit_status',
			type:'POST',
			data:data,
			success: function (data) {

				$('#loader-repo').hide();
				if (data === '0') {
					dom.css('color','red');
					dom.find('span').text('Chưa Lấy');
				}else {
					dom.css('color','green');
					dom.find('span').text('Đã lấy');
				}
				alert_float('success', 'Thay Đổi Thành Công');
				setTimeout(function () {
					location.reload();
				}, 1000);

			},
			error:function(e) {
				console.log(e);
			}
		});
	});

	$(document).on('click', '.confirm-number', function(e){
		window.onbeforeunload = null;
		e.preventDefault();
		var data = { id:$('#set_id').val(),mod:true ,number_order_get:$('#order_get').val() , user_geted:$('#user_geted_number').val() }

		$.ajax({
			url: '/system/admin/pick_up_points/edit_status_only_number',
			type:'POST',
			data:data,
			success: function (data) {
				$('#loader-repo').hide();

				$('#modal-confirm-order').modal('hide');

				alert_float('success', 'Thay Đổi Thành Công');
				$('.table-pick_up_points_picked').DataTable().ajax.reload();
				// setTimeout(function () {
				// 	location.reload();
				// }, 1000);

			},
			error:function(e) {
				console.log(e);
			}
		});

	});


	$(document).on('click', '.confirm-number-staff', function(e){
		window.onbeforeunload = null;
		e.preventDefault();
		var data = { id:$('#set_id').val(),status:true,mod:true ,number_order_get:$('#order_get_number').val() , user_geted:$('#user_geted').val() }
		if ($('#user_geted').val() == "NULL") {
			alert('Hãy Chọn Người lấy');
			return false;
		}
		$.ajax({
			url: '/system/admin/pick_up_points/edit_status_staff',
			type:'POST',
			data:data,
			success: function (data) {
				$('#loader-repo').hide();

				$('#modal-confirm-order-staff-add').modal('hide');

				alert_float('success', 'Thay Đổi Thành Công');
				setTimeout(function () {
					location.reload();
				}, 1000);

			},
			error:function(e) {
				console.log(e);
			}
		});

	});

	$(document).on('click', '.check-change-status-number', function(){
		var dom = $(this).parent();
		var id = $(this).parent().attr('data-id');
		var status;
		if ($(this)[0].checked) {
			status = true;
			$('#set_id').val(id);
			$('#modal-confirm-order-staff-add').modal('show');

		}else {
			status = false;
			$('#loader-repo').show();
			data = {id , status , mod:true };

			$.ajax({
				url: '/system/admin/pick_up_points/edit_status',
				type:'POST',
				data:data,
				success: function (data) {

					$('#loader-repo').hide();
					if (data === '0') {
						dom.css('color','red');
						dom.find('span').text('Chưa Lấy');
					}else {
						dom.css('color','green');
						dom.find('span').text('Đã lấy');
					}
					alert_float('success', 'Thay Đổi Thành Công');
					setTimeout(function () {
						location.reload();
					}, 1000);

				},
				error:function(e) {
					console.log(e);
				}
			});

		}




	});


</script>



<style media="screen">
	#loader-repo{
		display: none;
	}
	.disable-view {
		display: none;
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		z-index: 99999999999999;
	}
	.lds-ellipsis {
		display: block;
		position: relative;
		width: 64px;
		height: 64px;
		margin: 0 auto;
	}
	.lds-ellipsis div {
		position: absolute;
		top: 27px;
		width: 11px;
		height: 11px;
		border-radius: 50%;
		background: #03a9f4;
		animation-timing-function: cubic-bezier(0, 1, 1, 0);
	}
	.lds-ellipsis div:nth-child(1) {
		left: 6px;
		animation: lds-ellipsis1 0.6s infinite;
	}
	.lds-ellipsis div:nth-child(2) {
		left: 6px;
		animation: lds-ellipsis2 0.6s infinite;
	}
	.lds-ellipsis div:nth-child(3) {
		left: 26px;
		animation: lds-ellipsis2 0.6s infinite;
	}
	.lds-ellipsis div:nth-child(4) {
		left: 45px;
		animation: lds-ellipsis3 0.6s infinite;
	}
	@keyframes lds-ellipsis1 {
		0% {
			transform: scale(0);
		}
		100% {
			transform: scale(1);
		}
	}
	@keyframes lds-ellipsis3 {
		0% {
			transform: scale(1);
		}
		100% {
			transform: scale(0);
		}
	}
	@keyframes lds-ellipsis2 {
		0% {
			transform: translate(0, 0);
		}
		100% {
			transform: translate(19px, 0);
		}
	}


  .col-md-4.three-inline {
    padding: 3px;
  }
  .col-md-4.three-inline:nth-child(1) {
    padding-left: 0;
  }
  .col-md-4.three-inline:nth-child(3) {
    padding-right: 0;
  }
  textarea.txt-area.form-control {
    height: 150px;
    resize: none;
  }
  .search-icon{
		position: absolute;
    top: 33px;
    right: 12px;
    font-size: 18px;
	}
	.search-item {
		display: none;
		position: absolute;
    width: 100%;
    top: 64px;
    left: 0;
    z-index: 1;
    max-height: 275px;
		overflow-y: auto;
    cursor: pointer;
	}
  .search-item li:hover{
    background: #ddd;
    color: #fff;
  }

	.label-border {
		padding: 5px;
		border:1px solid #ddd;
	}
	.label-border span {
		margin-right: 5px;
	}
	table.dataTable thead>tr>th:nth-child(4) {
		width: 30%;
	}
	.tab-show-data {
		border-bottom: none;
	}

</style>
</body>
</html>
