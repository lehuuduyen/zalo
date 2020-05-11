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
							<a href="<?php echo admin_url('customer_policy/declared_region'); ?>" class="btn btn-info pull-left display-block"><?php echo _l('declared_region'); ?></a>
						</div>
						<div class="clearfix"></div>
						<hr class="hr-panel-heading" />
						<?php } ?>
						<div class="clearfix"></div>

						<div class="">

								<div class="col-md-8">
									<?php $days_of_confrontation  = array(
										array(
									    "label" => "Hằng Ngày",
									    "value" => "1"
										),
										array(
									    "label" => "Thứ 2",
									    "value" => "2"
										),
										array(
									    "label" => "Thứ 3",
									    "value" => "3"
										),
										array(
									    "label" => "Thứ 4",
									    "value" => "4"
										),
										array(
									    "label" => "Thứ 5",
									    "value" => "5"
										),
										array(
									    "label" => "Thứ 6",
									    "value" => "6"
										),
										array(
									    "label" => "Thứ 7",
									    "value" => "7"
										),
                                        array(
									    "label" => "Hằng ngày",
									    "value" => "Hằng ngày"
										),
									)
									  ;?>
									<div class="row">
										<div class="col-md-6">


											<?php  echo render_select('days_of_confrontation',$days_of_confrontation,array('value','label'),'Chọn Lịch Đối Soát');?>
										</div>
										<div class="col-md-6">
											<?php  echo render_select('customer',$customer,array('id','customer_shop_code'),'Khách Hàng');?>
										</div>
									</div>


								</div>


								<div class="col-md-4 total_calc_cover">
										<button class="btn btn-info mtop25" type="button" onclick="filter_customer()">Load danh sách</button>
										<p class="total-append"></p>
								</div>
							 <div class="clearfix"></div>

						</div>

						<?php render_datatable(array(
								_l('id'),
								_l('Ngày chứng từ'),
								_l('Khách hàng'),
								_l('Khách hàng'),
								_l('Chính sách đặc biệt'),
								_l('Phí đơn hoàn'),
								_l('Người tạo'),
								_l('Phí đơn hoàn'),
								_l('Phí đơn hoàn'),
								_l('Ghi chú khi giao hàng'),
								_l('Cấu Hình'),
								_l('options')
						),'customer_policy'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>




<div  class="modal fade" id="customer_policy" tabindex="-1" role="dialog">
	<div  class="modal-dialog" role="document">
		<?php echo form_open(admin_url('customer_policy/add_policy'),array('id' => 'add_new_customer_policy', )); ?>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><?php echo _l('modal_add_customer_policy'); ?></h4>
			</div>
			<div class="modal-body">
				<div class="row">
						<div class="">
								<div class="col-md-4">
									<div class="form-group">
								    <label for="day_policy">Ngày chứng từ</label>
								    <input placeholder="Ngày chứng từ" type="text" class="form-control" id="day_policy" name="day_policy">
										<?php $id = get_staff_user_id();  ?>
										<input type="hidden" id="staff_id" name="staff_id" value="<?php echo $id; ?>">
								  </div>

									<div class="form-group">
									  <label for="sel1"> Chọn Khách hàng:</label>
										<div class="form-group" style="position:relative">
											<input autocomplete="off" placeholder="Chọn Khách Hàng" class="form-control" id="search_customer" type="text" name="Tìm Kiếm" value="<?php echo set_value('search_customer'); ?>">
											<i class="fa fa-search search-icon" aria-hidden="true"></i>
											<input type="hidden" id="customer_id" name="customer_id" value="">

											<ul class="list-group search-item">

												<?php foreach ($dataCustomers as  $value): ?>
                                                <li data-id="<?php echo $value->id ?>" class="list-group-item">
                                                  <?php echo $value->customer_shop_code ?>
                                                </li>
                                              <?php endforeach; ?>
										  </ul>
										</div>
									</div>
                                    <?php
                                        $control_schedule = [
                                                ['name' => 'Thứ 2'],
                                                ['name' => 'Thứ 3'],
                                                ['name' => 'Thứ 4'],
                                                ['name' => 'Thứ 5'],
                                                ['name' => 'Thứ 6'],
                                                ['name' => 'Thứ 7'],
                                                ['name' => 'Chủ nhật'],
                                        ];


                                    ?>

																		<div class="form-group">
																			<label for="control_schedule" class="control-label">Chọn Lịch Đối Soát</label>
																			<select id="control_schedule" name="control_schedule" class="form-control" multiple>

																				<?php foreach ($control_schedule as $key => $value): ?>
																					<option value="<?php echo $value['name'] ?>"><?php echo $value['name'] ?></option>
																				<?php endforeach; ?>

																			</select>
																		</div>




									<div class="form-group">
									  <label for="config">Cấu Hình:</label>
									  <select class="form-control" id="config" name="config">
									    <option value="1">Cho Xem Hàng Nhưng Không Cho Thử Hàng</option>
									    <option value="2">Cho Thử Hàng</option>
									    <option value="3">Không Cho Xem Hàng</option>
									  </select>
									</div>

									<div class="form-group price_back_class">
										<label class="radio-inline">
								      <input id="price_back1" class="price_back" type="radio" name="price_back" value="1">Tính phí đơn hoàn
								    </label>
								    <label class="radio-inline">
								      <input class="price_back" type="radio" name="price_back" value="0">Không tính phí đơn hoàn
								    </label>


									</div>




								</div>

								<div class="col-md-4">
									<div class="form-group">
									  <label for="special_policy">Chính sách đặc biệt :</label>
									  <textarea style="resize:none; height:188px;" class="form-control" rows="5" id="special_policy" name="special_policy"></textarea>
									</div>
								</div>



								<div class="col-md-4">
									<div class="form-group">
									  <label for="note_default">Ghi chú khi giao hàng :</label>
									  <textarea style="resize:none; height:188px;" class="form-control" rows="5" id="note_default" name="note_default"></textarea>
									</div>

								</div>

								<div id="table-region" class="col-md-12	">

									<div class="form-group" class="price_pass">
									  <div class="">
									  	<label>Nhập Liệu Nâng Cao :</label>
									  </div>
										<label class="radio-inline">
								      <input id="price_pass1" type="radio" name="price_pass" value="1">Giá bảng giá cho tất cả
								    </label>
								    <label class="radio-inline">
								      <input id="price_pass2" type="radio" name="price_pass" value="0">Giá bảng giá cho dữ liệu chưa nhập
								    </label>
										<label class="radio-inline delete-all">
											<a class="btn btn-default delete-table" href="#">Xoá hết</a>
										</label>



									</div>

									<div class="form-group hide">
										<input  placeholder="% phí hoàn" type="hidden" class="form-control" id="fee_back" name="fee_back" onkeyup="formatNumBerKeyUp(this)">
										<span style="color:red">
											Cách nhập cột phí hoàn:Tính đủ 2 chiều nhập 200, chỉ tính 1 chiều nhập 100, 1 chiều đi và 50% chiều về nhập 150. Và nếu chỉ tính 1 nửa chiều đi  nhập 50.
										</span>
									</div>
									<div class="table-responsive">
										<table class="table ">
									    <thead>
									      <tr>
									        <th>D/sách nhóm vùng miền</th>
									        <th>Đơn Giá Vận Chuyển</th>
									        <th>phí hoàn (%)</th>
									        <th>Khối Lượng Free</th>
									        <th>Bước Kg tính phí</th>
									        <th>Thể Tích Free</th>
									        <th>Bước Thể Tích Tính Phí</th>
									        <th>Đơn Giá Vượt Kg</th>
									        <th>Đơn Giá Vượt Thể Tích</th>
									        <th>Số tiền Free Bảo Hiểm</th>
									        <th>Đơn giá bảo hiểm</th>
									      </tr>
									    </thead>
									    <tbody>
									      <tr>
									        <td>Nhóm 1</td>
									        <td>
									        	<input class="form-control" style="width:100%" type="text" name="" value="">
									        </td>
													<td>
									        	<input class="form-control" style="width:100%" type="text" name="" value="">
									        </td>
													<td>
									        	<input class="form-control" style="width:100%" type="text" name="" value="">
									        </td>
													<td>
									        	<input class="form-control" style="width:100%" type="text" name="" value="">
									        </td>
													<td>
									        	<input class="form-control" style="width:100%" type="text" name="" value="">
									        </td>
													<td>
									        	<input class="form-control" style="width:100%" type="text" name="" value="">
									        </td>
													<td>
									        	<input class="form-control" style="width:100%" type="text" name="" value="">
									        </td>
									      </tr>

									    </tbody>
									  </table>
									</div>

								</div>


								<div style="display:none" class="table-region-here-data">
									<?php echo json_encode($dataRegions); ?>
								</div>



						</div>
				</div>


			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
				<a href="javascript:;"  class="btn btn-primary submit_customer_policy"><?php echo _l('confirm'); ?></a>
			</div>
		</div><!-- /.modal-content -->
		<?php echo form_close(); ?>
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php init_tail(); ?>
<script type="text/javascript" src="/system/assets/plugins/serializejson/index.js"></script>



<script type="text/javascript">

$('#control_schedule').selectpicker({
	noneSelectedText:"Chọn Ngày Đối Soát"
});

	var checkAlert = <?php echo isset($_SESSION['success']) ? 'true' : 'false'?>;

	if (checkAlert) {
		alert_float('success','Delete Thành Công');
	}
	var param = {
		"customer": '[name="customer"]'
	}

	$(function(){
		var data = initDataTable('.table-customer_policy', window.location.href, [1], [1] , param);


		data.column(0).visible(false);
		data.column(2).visible(false);
		data.column(4).visible(false);
		data.column(7).visible(false);
		data.column(8).visible(false);
	});

	function filter_customer() {
		if ($.fn.DataTable.isDataTable('.table-customer_policy')) {
	      $('.table-customer_policy').DataTable().ajax.reload(false);
	  }


	}


	var dataTableRegion = JSON.parse($('.table-region-here-data').text());
	var dataTableRegion_none_fee_back = JSON.parse($('.table-region-here-data').text());



	dataTableRegion_none_fee_back.map((item,i)=>{
		item.fee_back_new = "0";
		return item;
	})

	dataTableRegion = dataTableRegion.slice(0);

	dataTableRegion.sort(function(a,b) {
	    var x = a.id;
	    var y = b.id;
	    return x > y ? -1 : x < y ? 1 : 0;
	});


	dataTableRegion.sort(function(a,b) {
	    var x = a.order_region;
	    var y = b.order_region;
	    return x < y ? -1 : x > y ? 1 : 0;
	});

	var dataTableRegionOnchange = JSON.parse($('.table-region-here-data').text());
	dataTableRegionOnchange.sort(function(a,b) {
	    var x = a.id;
	    var y = b.id;
	    return x > y ? -1 : x < y ? 1 : 0;
	});


	dataTableRegionOnchange.sort(function(a,b) {
	    var x = a.order_region;
	    var y = b.order_region;
	    return x < y ? -1 : x > y ? 1 : 0;
	});

	function MakedataTableRegionOnchangeNull() {
		dataTableRegionOnchange.map((item,i)=>{

			item.mass_region = null;
			item.mass_region_free = null;
			item.price_over_mass_region = null;
			item.price_over_volume_region = null;
			item.price_region = null;
			item.volume_region = null;
			item.volume_region_free = null;
			item.amount_of_free_insurance = null;
			item.insurance_price = null;
			return item;
		});
	}
	MakedataTableRegionOnchangeNull();


	$('.open-modal-addnew').click(function() {
		$('#fee_back').parent().addClass('hide');
		$('#customer_policy').modal('show');

		$('.price_back_class').hide();
		$('#price_back1').prop("checked", true);
		$('#table-region .table').show();
		$('#add_new_customer_policy .modal-title').text('Thêm Mới Chính Sách Khách Hàng');
		showDataRegionTable();
		$('#price_pass1').prop("checked", true);

		$('#fee_back').parent().removeClass('hide');
		showDataRegionTable(dataTableRegion);
		$( "#day_policy" ).datepicker().datepicker("setDate", new Date());


		$("#add_new_customer_policy").validate({
			errorClass: 'error text-danger',
			highlight: function(element) {
	      $(element).parent().addClass("has-error");
	    },
			unhighlight: function(element) {
	      $(element).parent().removeClass("has-error");
	    },
			ignore: [],
			rules: {
				day_policy: {
					required: true,
				},
				customer_id:{
					required:true,
				},
				price_back:{
					required:true
				}

			},
			messages: {
				day_policy: {
					required: 'Bắt Buộc',
				},
				customer_id:{
					required: 'Bắt Buộc',
				},
				price_back:{
					required:''
				}

			},
			submitHandler: function(form) {

				var data = {};
				if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
				data.day_policy = $('#day_policy').val();
				data.staff_id = $('#staff_id').val();
				data.customer_id = $('#customer_id').val();
				var price_back  = $('input[name=price_back]:checked').val();
				if (price_back == 1) {
					data.fee_back = 1;
				}else {
					data.fee_back = 0;

				}
				data.special_policy = $('#special_policy').val();
				data.note_default = $('#note_default').val();
				data.config = $('#config').val();
				data.control_schedule = $('#control_schedule').val();
				var data_region = $('#add_new_customer_policy').serializeJSON();


				data.data_table_region = {}
				data.data_table_region.id_region = data_region.id_region;
				data.data_table_region.insurance_price = data_region.insurance_price;
				data.data_table_region.mass_region = data_region.mass_region;
				data.data_table_region.mass_region_free = data_region.mass_region_free;
				data.data_table_region.price_over_mass_region = data_region.price_over_mass_region;
				data.data_table_region.price_over_volume_region = data_region.price_over_volume_region;
				data.data_table_region.price_region = data_region.price_region;
				data.data_table_region.volume_region = data_region.volume_region;
				data.data_table_region.volume_region_free = data_region.volume_region_free;
				data.data_table_region.amount_of_free_insurance = data_region.amount_of_free_insurance;

				data.data_table_region.fee_back_new = data_region.fee_back_new;

				$.ajax({
						url: '/system/admin/customer_policy/add_policy_customer',
						data,
						type: 'POST',
						success: function (data) {

							alert_float('success', 'Thêm Mới Thành Công');
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
	});




	//Submit policy
	$('.submit_customer_policy').click(function() {
		$('form').submit();
	});



	$('.price_back').click(function() {
		var price_back = $(this).val();

		if (price_back == 1) {
			$('#fee_back').parent().removeClass('hide');
			showDataRegionTable(dataTableRegion);
		}else {
			$('#fee_back').parent().addClass('hide');
			showDataRegionTable(dataTableRegion_none_fee_back);
		}
	});

	var dataChangeSet = [];

	$('#table-region .radio-inline input').click(function() {
		var price_pass = $(this).val();
		showDataRegionTable();

		if (price_pass == 1) {
			$('#table-region .table ').show();
		}
		else {
			$('#table-region .table ').show();
			$('#table-region .table input').val('');
		}
		console.log("crash");
		// dataTableRegionOnchange.map((item,i)=>{
		//
		// 	if (item.mass_region || item.mass_region_free || item.price_over_mass_region || item.price_over_volume_region || item.price_region || item.volume_region || item.volume_region_free || item.amount_of_free_insurance || item.insurance_price) {
		//
		// 		$(`.data-change-${i}`).addClass('active');
		//
		// 		if (dataTableRegionOnchange[i].price_region !== null) {
		// 			$('#table-region tr.data-change-'+i).find('[name^="price_region"]').val(dataTableRegionOnchange[i].price_region);
		// 			$('#table-region tr.data-change-'+i).find('[name^="price_region"]').addClass('active');
		// 		}else if(dataTableRegionOnchange[i].mass_region_free !== null){
		// 			$('#table-region tr.data-change-'+i).find('[name^="mass_region_free"]').val(dataTableRegionOnchange[i].mass_region_free);
		// 			$('#table-region tr.data-change-'+i).find('[name^="mass_region_free"]').addClass('active');
		// 		}
		// 		else if(dataTableRegionOnchange[i].price_over_mass_region !== null){
		// 			$('#table-region tr.data-change-'+i).find('[name^="price_over_mass_region"]').val(dataTableRegionOnchange[i].price_over_mass_region);
		// 			$('#table-region tr.data-change-'+i).find('[name^="price_over_mass_region"]').addClass('active');
		// 		}
		// 		else if(dataTableRegionOnchange[i].price_over_volume_region !== null){
		// 			$('#table-region tr.data-change-'+i).find('[name^="price_over_volume_region"]').val(dataTableRegionOnchange[i].price_over_volume_region);
		// 			$('#table-region tr.data-change-'+i).find('[name^="price_over_volume_region"]').addClass('active');
		// 		}
		// 		else if(dataTableRegionOnchange[i].mass_region !== null){
		// 			$('#table-region tr.data-change-'+i).find('[name^="mass_region"]').val(dataTableRegionOnchange[i].mass_region);
		// 			$('#table-region tr.data-change-'+i).find('[name^="mass_region"]').addClass('active');
		// 		}
		// 		else if(dataTableRegionOnchange[i].volume_region !== null){
		// 			$('#table-region tr.data-change-'+i).find('[name^="volume_region"]').val(dataTableRegionOnchange[i].volume_region);
		// 			$('#table-region tr.data-change-'+i).find('[name^="volume_region"]').addClass('active');
		// 		}
		// 		else if(dataTableRegionOnchange[i].volume_region_free !== null){
		// 			$('#table-region tr.data-change-'+i).find('[name^="volume_region_free"]').val(dataTableRegionOnchange[i].volume_region_free);
		// 			$('#table-region tr.data-change-'+i).find('[name^="volume_region_free"]').addClass('active');
		// 		}
		// 		else if(dataTableRegionOnchange[i].amount_of_free_insurance !== null){
		// 			$('#table-region tr.data-change-'+i).find('[name^="amount_of_free_insurance"]').val(dataTableRegionOnchange[i].amount_of_free_insurance);
		// 			$('#table-region tr.data-change-'+i).find('[name^="amount_of_free_insurance"]').addClass('active');
		// 		}
		// 		else if(dataTableRegionOnchange[i].insurance_price !== null){
		// 			$('#table-region tr.data-change-'+i).find('[name^="insurance_price"]').val(dataTableRegionOnchange[i].insurance_price);
		// 			$('#table-region tr.data-change-'+i).find('[name^="insurance_price"]').addClass('active');
		// 		}
		// 	}
		// })


	});

	$('.delete-table').click(function() {
		$('#table-region .table input').val('');
		$('#price_pass1').prop("checked", false);
		$('#price_pass2').prop("checked", true);
		$('#table-region table tbody tr.active input.active').removeClass('active');
		$('#table-region table tbody tr.active').removeClass('active');
		MakedataTableRegionOnchangeNull();
	});


	function renderTable(data) {
		$('#table-region table tbody').empty();
		var html = '';
		data.map((item , i) =>{

			item.price_region = formatNumber(item.price_region);
			item.mass_region = formatNumber(item.mass_region);
			item.mass_region_free = formatNumber(item.mass_region_free);
			item.volume_region = formatNumber(item.volume_region);
			item.volume_region_free = formatNumber(item.volume_region_free);
			item.price_over_mass_region = formatNumber(item.price_over_mass_region);
			item.price_over_volume_region = formatNumber(item.price_over_volume_region);
			item.amount_of_free_insurance = formatNumber(item.amount_of_free_insurance);
			item.fee_back_new = formatNumber(item.fee_back_new);

			html += `<tr class="data-change-${i}" data-change="${i}">
						<td>${item.name_region}</td>
						<td>
							<input class="form-control" style="width:100%" type="text" name="price_region[]" value="${item.price_region}">

							<input class="form-control" style="width:100%" type="hidden" name="id_region[]" value="${item.id}">
							<input class="form-control" style="width:100%" type="hidden" name="name_region[]" value="${item.name_region}">
						</td>
						<td>
							<input class="form-control" style="width:100%" type="text" name="fee_back_new[]" value="${item.fee_back_new}">
						</td>
						<td>
							<input class="form-control" style="width:100%" type="text" name="mass_region[]" value="${item.mass_region}">
						</td>
						<td>
							<input class="form-control" style="width:100%" type="text" name="mass_region_free[]" value="${item.mass_region_free}">
						</td>
						<td>
							<input class="form-control" style="width:100%" type="text" name="volume_region[]" value="${item.volume_region}">
						</td>
						<td>
							<input class="form-control" style="width:100%" type="text" name="volume_region_free[]" value="${item.volume_region_free}">
						</td>
						<td>
							<input class="form-control" style="width:100%" type="text" name="price_over_mass_region[]" value="${item.price_over_mass_region}">
						</td>
						<td>
							<input class="form-control" style="width:100%" type="text" name="price_over_volume_region[]" value="${item.price_over_volume_region}">
						</td>
						<td>
							<input class="form-control" style="width:100%" type="text" name="amount_of_free_insurance[]" value="${item.amount_of_free_insurance}">
						</td>
						<td>
							<input class="form-control" style="width:100%" type="text" name="insurance_price[]" value="${item.insurance_price}">
						</td>
					</tr>`;

		});

		$('#table-region table tbody').append(html);
	}

	function showDataRegionTable() {
		var check_back = $('[name="price_back"]:checked').val();

		if (!check_back) {
			renderTable(dataTableRegion_none_fee_back);
		}else if(check_back === '0') {
			renderTable(dataTableRegion_none_fee_back);
		}else {
			renderTable(dataTableRegion);
		}


	}



	$(document).on('click', '.delete-reminder-custom', function(){
		var a = confirm("Bạn có chắc chắn muốn xóa chính sách khách hàng hay không!");
		if (a === true) {
			return true;
		}else {
			return false;
		}
	});
	$(document).on('click', '.search-item li', function(){
		var id = $(this).attr('data-id');
		var text = $(this).text();


		$.ajax({
			url: '/system/admin/customer_policy/check_customer_policy/'+id,
			success: function (data) {
				if (JSON.parse(data).length === 0 ) {
					$('#search_customer').val(text.trim());
					$('.search-item').hide();
					$('#customer_id').val(id);
				}else {
					alert('Khách Hàng Này Đã Tạo Chính Sách');
				}

			}
		});




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




	$(document).on('keyup', '#table-region table tbody input', function(){
		var dataChange = $(this).parent().parent().attr('data-change');

		$('.data-change-'+dataChange).addClass('active');
		$(this).addClass('active');
		formatNumBerKeyUp(this);

		var key = $(this).attr('name').replace('[]','');


		switch (key) {
			case 'price_region':
				dataTableRegionOnchange[dataChange].price_region = $(this).val();
				break;
			case 'mass_region_free':
				dataTableRegionOnchange[dataChange].mass_region_free = $(this).val();
				break;
			case 'price_over_mass_region':
				dataTableRegionOnchange[dataChange].price_over_mass_region = $(this).val();
				break;
			case 'price_over_volume_region':
				dataTableRegionOnchange[dataChange].price_over_volume_region = $(this).val();
				break;
			case 'mass_region':
				dataTableRegionOnchange[dataChange].mass_region = $(this).val();
				break;
			case 'volume_region':
				dataTableRegionOnchange[dataChange].volume_region = $(this).val();
				break;
			case 'volume_region_free':
				dataTableRegionOnchange[dataChange].volume_region_free = $(this).val();
			case 'amount_of_free_insurance':
				dataTableRegionOnchange[dataChange].amount_of_free_insurance = $(this).val();
			case 'insurance_price':
				dataTableRegionOnchange[dataChange].insurance_price = $(this).val();
				break;
			default:

		}

	});




	//Script Edit

	// Load Data Edit
	var dataTableRegionEdit;
	var dataTableRegionOnchangeEdit;

	function MakedataTableRegionOnchangeNullEdit() {
		dataTableRegionOnchangeEdit.map((item,i)=>{

			item.mass_region = null;
			item.mass_region_free = null;
			item.fee_back_new = null;
			item.price_over_mass_region = null;
			item.price_over_volume_region = null;
			item.price_region = null;
			item.volume_region = null;
			item.volume_region_free = null;
			item.amount_of_free_insurance = null;
			item.insurance_price = null;
			return item;
		});
	}



	function showDataRegionTableEdit() {

		$('#table-region table tbody').empty();
		var html = '';
		dataTableRegionEdit.map((item , i) =>{

			item.price_region = formatNumber(item.price_region);
			item.mass_region = formatNumber(item.mass_region);
			item.mass_region_free = formatNumber(item.mass_region_free);
			item.volume_region = formatNumber(item.volume_region);
			item.volume_region_free = formatNumber(item.volume_region_free);
			item.price_over_mass_region = formatNumber(item.price_over_mass_region);
			item.price_over_volume_region = formatNumber(item.price_over_volume_region);
			item.amount_of_free_insurance = formatNumber(item.amount_of_free_insurance);
			if (item.fee_back_new) {
				item.fee_back_new = formatNumber(item.fee_back_new);
			}else {
				item.fee_back_new = 0;
			}


			html += `<tr class="data-change-${i}" data-change="${i}">
						<td>${item.name_region}</td>
						<td>
							<input class="form-control" style="width:100%" type="text" name="price_region[]" value="${item.price_region}">

							<input class="form-control" style="width:100%" type="hidden" name="id_region[]" value="${item.id_region}">
							<input class="form-control" style="width:100%" type="hidden" name="name_region[]" value="${item.name_region}">
						</td>
						<td>
							<input class="form-control" style="width:100%" type="text" name="fee_back_new[]" value="${item.fee_back_new}">
						</td>
						<td>
							<input class="form-control" style="width:100%" type="text" name="mass_region[]" value="${item.mass_region}">
						</td>
						<td>
							<input class="form-control" style="width:100%" type="text" name="mass_region_free[]" value="${item.mass_region_free}">
						</td>
						<td>
							<input class="form-control" style="width:100%" type="text" name="volume_region[]" value="${item.volume_region}">
						</td>
						<td>
							<input class="form-control" style="width:100%" type="text" name="volume_region_free[]" value="${item.volume_region_free}">
						</td>
						<td>
							<input class="form-control" style="width:100%" type="text" name="price_over_mass_region[]" value="${item.price_over_mass_region}">
						</td>
						<td>
							<input class="form-control" style="width:100%" type="text" name="price_over_volume_region[]" value="${item.price_over_volume_region}">
						</td>
						<td>
							<input class="form-control" style="width:100%" type="text" name="amount_of_free_insurance[]" value="${item.amount_of_free_insurance}">
						</td>
						<td>
							<input class="form-control" style="width:100%" type="text" name="insurance_price[]" value="${item.insurance_price}">
						</td>
					</tr>`;

		});

		$('#table-region table tbody').append(html);
	}

	$('#customer_policy').on('hidden.bs.modal', function () {
    statusEdit = false;
	});

	var statusEdit = false;

	$(document).on('click', '.edit-policy', function(){
		$('.price_back_class').show();
		$('.price_back_class').hide();
		$('#price_back1').prop("checked", false);
		$('#fee_back').parent().addClass('hide');
		var id = $(this).attr('data-id');
		var dataSearch = $(this).parent().parent().find('td').eq(1)[0].innerText;
		statusEdit = true;
		$.ajax({
				url: '/system/admin/customer_policy/customer_policy_id/'+id,
				success: function (data) {

					var dataJsonParse = JSON.parse(data);

					dataTableRegionEdit = dataJsonParse.data_table_region;
					dataTableRegionOnchangeEdit = dataJsonParse.data_table_region;

					$('#add_new_customer_policy .modal-title').text('Thay Đổi Chính Sách Khách Hàng');
					$('#customer_policy').modal('show');
					$('#table-region .table').show();
					$('#add_new_customer_policy').addClass('edit-here');
					showDataRegionTableEdit();
					MakedataTableRegionOnchangeNullEdit();
					$('#price_pass1').prop("checked", true);
					$('#special_policy').val(dataJsonParse.special_policy);
					$( "#day_policy" ).datepicker().datepicker("setDate", new Date(dataJsonParse.day_policy));



					if (dataJsonParse.control_schedule) {
						$('#control_schedule').selectpicker('val', dataJsonParse.control_schedule.split(","));
						$('#control_schedule').selectpicker('refresh');
					}


					if (dataJsonParse.fee_back > 0) {


						$("input[name='price_back'][value='1']").prop("checked",true);
						$(".form-group.hide").removeClass('hide');
						$("#fee_back").val(dataJsonParse.fee_back);


					}
					else {
						$("input[name='price_back'][value='0']").prop("checked",true);
					}

					$('#customer_id').val(dataJsonParse.customer_id);



					$('#search_customer').val(dataSearch);
					$('#note_default').val(dataJsonParse.note_default);
					$('#config').val(dataJsonParse.config);

					// $('#control_schedule').val(dataJsonParse.control_schedule);



					$("#add_new_customer_policy").validate({
						errorClass: 'error text-danger',
						highlight: function(element) {
				      $(element).parent().addClass("has-error");
				    },
						unhighlight: function(element) {
				      $(element).parent().removeClass("has-error");
				    },
						ignore: [],
						rules: {
							day_policy: {
								required: true,
							},
							customer_id:{
								required:true,
							},
							price_back:{
								required:true
							}

						},
						messages: {
							day_policy: {
								required: 'Bắt Buộc',
							},
							customer_id:{
								required: 'Bắt Buộc',
							},
							price_back:{
								required:''
							}

						},
						submitHandler: function(form) {

							var data = {};
							if (typeof(csrfData) !== 'undefined') {
			            data[csrfData['token_name']] = csrfData['hash'];
			        }
							data.day_policy = $('#day_policy').val();
							data.staff_id = $('#staff_id').val();

							data.customer_id = $('#customer_id').val();
							var price_back  = $('input[name=price_back]:checked').val();
							if (price_back == 1) {
								data.fee_back = 1;
							}else {
									data.fee_back = 0;
							}
							data.special_policy = $('#special_policy').val();
							var data_region = $('#add_new_customer_policy').serializeJSON();


							data.data_table_region = {}
							data.data_table_region.id_region = data_region.id_region;
							data.data_table_region.insurance_price = data_region.insurance_price;
							data.data_table_region.mass_region = data_region.mass_region;
							data.data_table_region.mass_region_free = data_region.mass_region_free;
							data.data_table_region.price_over_mass_region = data_region.price_over_mass_region;
							data.data_table_region.price_over_volume_region = data_region.price_over_volume_region;
							data.data_table_region.price_region = data_region.price_region;
							data.data_table_region.volume_region = data_region.volume_region;
							data.data_table_region.volume_region_free = data_region.volume_region_free;
							data.data_table_region.amount_of_free_insurance = data_region.amount_of_free_insurance;


							data.data_table_region.fee_back_new = data_region.fee_back_new;

							var dataIdEdit = [];
							for (var i = 0; i < dataJsonParse.data_table_region.length; i++) {
								dataIdEdit.push(dataJsonParse.data_table_region[i].id);


							}
							data.data_table_region.id = dataIdEdit;


							data.id = dataJsonParse.id;
							data.note_default = $('#note_default').val();
							data.config = $('#config').val();
							data.control_schedule = $('#control_schedule').val();



							$.ajax({
									url: '/system/admin/customer_policy/edit_policy_customer',
									data,
									type: 'POST',
									success: function (data) {

										alert_float('success', 'Sửa Thành Công');
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



				},
				error:function(e) {
					console.log(e);
				}
		});

	});

	//Reset Data
	$('#customer_policy').on('hidden.bs.modal', function () {
		$("#add_new_customer_policy").validate().destroy();
		if ($('#add_new_customer_policy').hasClass('edit-here')) {
			$('#search_customer').val('');
			$('.price_back').prop("checked",false);
			$('#price_pass1').prop("checked",true);
			$('#fee_back').val('');
			$('#special_policy').val('');
		}
	});


	$(document).on('click', '.fa-clone', function(){
		$('.price_back_class').hide();


		$('#price_back1').prop("checked", false);
		$('#fee_back').parent().addClass('hide');
		var id = $(this).parent().attr('data-id');
		var dataSearch = $(this).parent().parent().parent().find('td').eq(1)[0].innerText;

		$.ajax({
				url: '/system/admin/customer_policy/customer_policy_id/'+id,
				success: function (data) {

					var dataJsonParse = JSON.parse(data);

					dataTableRegionEdit = dataJsonParse.data_table_region;
					dataTableRegionOnchangeEdit = dataJsonParse.data_table_region;
					//
					$('#add_new_customer_policy .modal-title').text('Copy Chính Sách Khách Hàng');
					$('#customer_policy').modal('show');
					$('#table-region .table').show();
					$('#add_new_customer_policy').addClass('edit-here');
					showDataRegionTableEdit();
					MakedataTableRegionOnchangeNullEdit();
					$('#price_pass1').prop("checked", true);
					$('#special_policy').val(dataJsonParse.special_policy);
					$( "#day_policy" ).datepicker().datepicker("setDate", new Date());
					if (dataJsonParse.fee_back > 0) {

						$("input[name='price_back'][value='1']").prop("checked",true);
						$(".form-group.hide").removeClass('hide');
						$("#fee_back").val(dataJsonParse.fee_back);


					}
					else {
						$("input[name='price_back'][value='0']").prop("checked",true);
					}









					$("#add_new_customer_policy").validate({
						errorClass: 'error text-danger',
						highlight: function(element) {
				      $(element).parent().addClass("has-error");
				    },
						unhighlight: function(element) {
				      $(element).parent().removeClass("has-error");
				    },
						ignore: [],
						rules: {
							day_policy: {
								required: true,
							},
							customer_id:{
								required:true,
							},
							price_back:{
								required:true
							}

						},
						messages: {
							day_policy: {
								required: 'Bắt Buộc',
							},
							customer_id:{
								required: 'Bắt Buộc',
							},
							price_back:{
								required:''
							}

						},
						submitHandler: function(form) {

							var data = {};
							if (typeof(csrfData) !== 'undefined') {
			            data[csrfData['token_name']] = csrfData['hash'];
			        }
							data.day_policy = $('#day_policy').val();
							data.staff_id = $('#staff_id').val();
							data.customer_id = $('#customer_id').val();
							var price_back  = $('input[name=price_back]:checked').val();
							if (price_back == 1) {
								data.fee_back = 1;
							}else {
								data.fee_back = 0;

							}
							data.special_policy = $('#special_policy').val();
							var data_region = $('#add_new_customer_policy').serializeJSON();


							data.data_table_region = {}
							data.data_table_region.id_region = data_region.id_region;
							data.data_table_region.insurance_price = data_region.insurance_price;
							data.data_table_region.mass_region = data_region.mass_region;
							data.data_table_region.mass_region_free = data_region.mass_region_free;
							data.data_table_region.price_over_mass_region = data_region.price_over_mass_region;
							data.data_table_region.price_over_volume_region = data_region.price_over_volume_region;
							data.data_table_region.price_region = data_region.price_region;
							data.data_table_region.volume_region = data_region.volume_region;
							data.data_table_region.volume_region_free = data_region.volume_region_free;
							data.data_table_region.amount_of_free_insurance = data_region.amount_of_free_insurance;
							data.data_table_region.fee_back_new = data_region.fee_back_new;

							$.ajax({
									url: '/system/admin/customer_policy/add_policy_customer',
									data,
									type: 'POST',
									success: function (data) {

										alert_float('success', 'Copy Thành Công');
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



				},
				error:function(e) {
					console.log(e);
				}
		});

	});

</script>
<style media="screen">
	#table-region table tbody tr.active
	{
		background: aliceblue;
	}
	#table-region table tbody tr.active input.active
	{
		background: antiquewhite;
	}
	.search-icon{
		position: absolute;
    top: 8px;
    right: 12px;
    font-size: 18px;
	}
	.search-item {
		display: none;
		position: absolute;
    width: 100%;
    top: 43px;
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
	#table-region table {
		display: none;
	}
	.radio-inline.has-error {
		color: #fc2d42;
	}
	.radio-inline input[type='radio'] {
		width: 20px;
		height: 20px;
		margin: 0;
		margin-left: -20px;
	}
	body .list-group-item {
		margin-bottom: 0;
	}
	.modal-dialog  {
		width: 90%;
	}
	.mobile .modal-dialog {
		width: auto;
	}
	.mobile .radio-inline {
		display: block;
		margin-left: 0;
		margin-bottom: 10px;
		padding-left: 30px;
	}
	.mobile .radio-inline.delete-all {
		padding-left: 0;
	}
	.mobile .radio-inline input[type='radio'] {
		margin-left: -30px;
	}
</style>
</body>
</html>
