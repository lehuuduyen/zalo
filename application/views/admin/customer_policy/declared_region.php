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
							<a id="show-add-region" style="margin-right:10px;" href="javascript:;" class="btn btn-info pull-left display-block"><?php echo _l('new_polycy'); ?></a>
							<a href="<?php echo admin_url('customer_policy/'); ?>" class="btn btn-info pull-left display-block"><?php echo _l('als_customer_policy'); ?></a>
						</div>
						<div class="clearfix"></div>
						<hr class="hr-panel-heading" />
						<?php } ?>
						<div class="clearfix"></div>
						<div class="panel_s">
							<div class="panel-body">
								<div class="clearfix"></div>
								<?php render_datatable(array(
										_l('id'),
										_l('Nhóm Vùng Miền'),
										_l('Bảng Giá Của Vùng'),
										_l('Khối lượng Free Của Vùng'),
										_l('Bước khối lượng Tính Phí'),
										_l('Đơn Gía Vượt Khối Lượng'),
										_l('Thể tích  Free Của Vùng'),
										_l('Bước thể tích Tính Phí'),
										_l('Đơn Gía Vượt Thể Tích'),
										_l('Số tiền Free Bảo Hiểm'),
										_l('Đơn giá bảo hiểm'),
										_l('Thứ Tự Sắp Xếp'),
										_l('Số ngày tối đa nhận được hàng'),
										_l('options')
								),'region'); ?>

							</div>
							<input id="setIdDetail" type="hidden" name="" value="">
						</div>
						<!-- Region Table -->
						<div class="clearfix"></div>
						<div class="panel_s">
							<div class="panel-body">
								<div class="clearfix"></div>



								<?php render_datatable(array(
									_l('id'),
									_l('region_id'),
									_l('Tỉnh/Thành Phố'),
									_l('Tên Quận/Huyện')
								),'region_district'); ?>

							</div>
						</div>
						<!-- Region Detail -->

					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="add_region" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<?php echo form_open(admin_url('customer_policy/declared_region'),array('id' => 'add_new_region', )); ?>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><?php echo _l('modal_add_new_region'); ?></h4>
			</div>
			<div class="modal-body">
				<div class="row">
						<div class="col-md-12">
								<div id="additional"></div>
								<?php echo render_input('name_region','*Tên Nhóm Vùng Miền'); ?>

								<?php echo render_input('price_region','*Giá Bảng Giá Của Vùng','','text',['onkeyup' => 'formatNumBerKeyUp(this)']); ?>

								<?php echo render_input('fee_back_new','*Phí Hoàn Mặc Định(%)','','text',['onkeyup' => 'formatNumBerKeyUp(this)']); ?>

								<?php echo render_input('mass_region','*Khối lượng Free Của Vùng','','text',['onkeyup' => 'formatNumBerKeyUp(this)']); ?>

								<?php echo render_input('mass_region_free','*Bước Kg Tính Phí Của Vùng','','text',['onkeyup' => 'formatNumBerKeyUp(this)']); ?>

								<?php echo render_input('price_over_mass_region','*Đơn Gía Vượt Khối Lượng','','text',['onkeyup' => 'formatNumBerKeyUp(this)']); ?>

								<?php echo render_input('volume_region','*Thể tích  Free Của Vùng','','text',['onkeyup' => 'formatNumBerKeyUp(this)']); ?>

								<?php echo render_input('volume_region_free','*Bước thể tích Tính Phí','','text',['onkeyup' => 'formatNumBerKeyUp(this)']); ?>

								<?php echo render_input('price_over_volume_region','*Đơn Gía Vượt Thể Tích','','text',['onkeyup' => 'formatNumBerKeyUp(this)']); ?>



								<?php echo render_input('amount_of_free_insurance','*Số tiền Free Bảo Hiểm','','text',['onkeyup' => 'formatNumBerKeyUp(this)']); ?>



								<div class="form-group" >
									<label for="insurance_price" class="control-label">*Đơn giá bảo hiểm</label>
									<input type="text" id="insurance_price" name="insurance_price" class="form-control input-element" value="">
								</div>
								<?php echo render_input('max_day','*Số ngày tối đa nhận được hàng','','text',['onkeyup' => 'formatNumBerKeyUp(this)']); ?>
								<?php echo render_input('order_region','Thứ Tự Vùng Miền','','text',['onkeyup' => 'formatNumBerKeyUp(this)']); ?>

						</div>
				</div>


			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
				<button type="submit" class="btn btn-primary"><?php echo _l('confirm'); ?></button>
			</div>
		</div><!-- /.modal-content -->
		<?php echo form_close(); ?>
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->




<div class="modal fade" id="edit_region" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<?php echo form_open(admin_url('customer_policy/edit_region'),array('id' => 'edit_region_form', )); ?>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Thay Đổi Vùng Miền</h4>
			</div>
			<div class="modal-body">
				<div class="row">
						<div class="col-md-12">
								<div id="additional"></div>
								<input type="hidden" name="id_region" value="">
								<?php echo render_input('name_region','*Tên Nhóm Vùng Miền'); ?>

								<?php echo render_input('price_region','*Giá Bảng Giá Của Vùng','','text',['onkeyup' => 'formatNumBerKeyUp(this)']); ?>

								<?php echo render_input('fee_back_new','*Phí Hoàn Mặc Định(%)','','text',['onkeyup' => 'formatNumBerKeyUp(this)']); ?>

								<?php echo render_input('mass_region','*Khối lượng Free Của Vùng','','text',['onkeyup' => 'formatNumBerKeyUp(this)']); ?>

								<?php echo render_input('mass_region_free','*Bước Kg Tính Phí Của Vùng','','text',['onkeyup' => 'formatNumBerKeyUp(this)']); ?>

								<?php echo render_input('price_over_mass_region','*Đơn Gía Vượt Khối Lượng','','text',['onkeyup' => 'formatNumBerKeyUp(this)']); ?>

								<?php echo render_input('volume_region','*Thể tích  Free Của Vùng','','text',['onkeyup' => 'formatNumBerKeyUp(this)']); ?>

								<?php echo render_input('volume_region_free','*Bước thể tích Tính Phí','','text',['onkeyup' => 'formatNumBerKeyUp(this)']); ?>

								<?php echo render_input('price_over_volume_region','*Đơn Gía Vượt Thể Tích','','text',['onkeyup' => 'formatNumBerKeyUp(this)']); ?>

								<?php echo render_input('amount_of_free_insurance','*Số tiền Free Bảo Hiểm','','text',['onkeyup' => 'formatNumBerKeyUp(this)']); ?>



								<div class="form-group" >
									<label for="insurance_price" class="control-label">*Đơn giá bảo hiểm</label>
									<input type="text" name="insurance_price" class="form-control input-element2" value="">
								</div>

								<?php echo render_input('order_region','Thứ Tự Vùng Miền','','text',['onkeyup' => 'formatNumBerKeyUp(this)']); ?>

								<?php echo render_input('max_day','*Số ngày tối đa nhận được hàng','','text',['onkeyup' => 'formatNumBerKeyUp(this)']); ?>

						</div>
				</div>


			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
				<button type="submit" class="btn btn-primary"><?php echo _l('confirm'); ?></button>
			</div>
		</div><!-- /.modal-content -->
		<?php echo form_close(); ?>
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php init_tail(); ?>




<script type="text/javascript" src="/system/assets/plugins/cleave/index.js"></script>
<script type="text/javascript">


	var table = $('#example').DataTable();


	$(document).on('click', '.delete-reminder-custom', function(){
		var confirm = window.confirm("Bạn Muốn Xoá Vùng Miền!");

		if (confirm) {
			var id = $(this).attr('data-id');

			var doom = $(this);
			$.ajax({
					url: '/system/admin/customer_policy/delete_region/'+id,
					cache: false,
					contentType: false,
					processData: false,
					type: 'get',
					success: function (data) {

						doom.parent().parent().parent().hide();
						alert_float('success', 'Đã Xoá Region');
					},
					error:function(e) {
						console.log(e);
					}
			});
		}


	});


	$(function(){
		var data = initDataTable('.table-region', window.location.href, [1], [1]);
		data.column(0).visible(false);
	});



	var filterList = {
  		'filterStatus' : '[id="setIdDetail"]',

	};
	$.each(filterList, function(filterIndex, filterItem){
		 $(filterItem).on('change',function(){
		 	if($('.table-region_district').hasClass('dataTable'))
		 	{
				$('.table-region_district').DataTable().ajax.reload();
		 	}
		 });
	});

	$(document).on('click', '.btn-detail-csv', function(){
		var id = $(this).attr('data-id');
		$('#setIdDetail').val(id);
		if(!$('.table-region_district').hasClass('dataTable'))
		{
			initDataTable('.table-region_district', admin_url+'customer_policy/detail_region', [1], [1], filterList);
		}
		else
		{
			$('#setIdDetail').trigger('change');
		}


	});

	$('#show-add-region').click(function() {
		 $('#add_region').modal('show');
		 var cleave = new Cleave('.input-element', {
			    numeral: true,
			    numeralThousandsGroupStyle: 'thousand'
			});
	});





	$("#add_new_region").validate({
		errorClass: 'error text-danger',
		highlight: function(element) {
      $(element).parent().addClass("has-error");
    },
		unhighlight: function(element) {
      $(element).parent().removeClass("has-error");
    },
		rules: {
			fee_back_new:{
				required: true,
			},
			name_region: {
				required: true,
			},
			amount_of_free_insurance:{
				required: true,
			},
			insurance_price:{
				required: true,
			},
      price_region:{
        required: true,
				number:true
      },
      mass_region:{
				required: true,
				number:true
      },
			mass_region_free:{
				required: true,
				number:true
      },
      volume_region:{
				required: true,
				number:true
      },
			volume_region_free:{
				required: true,
				number:true
      },
			price_over_mass_region:{
				required: true,
				number:true
      },
			price_over_volume_region:{
				required: true,
				number:true
      }
		},
		messages: {
			name_region: {
				required: 'Bắt Buộc',
			},
      price_region:{
        required: 'Bắt Buộc',
				number:'Nhập Số'
      },
      mass_region:{
				required: 'Bắt Buộc',
				number:'Nhập Số'
      },
			mass_region_free:{
				required: 'Bắt Buộc',
				number:'Nhập Số'
      },
      volume_region:{
				required: 'Bắt Buộc',
				number:'Nhập Số'
      },
			volume_region_free:{
				required: 'Bắt Buộc',
				number:'Nhập Số'
      },
			price_over_mass_region:{
				required: 'Bắt Buộc',
				number:'Nhập Số'
      },
			price_over_volume_region:{
				required: 'Bắt Buộc',
				number:'Nhập Số'
      }
		}
	});


	$("#edit_region_form").validate({
		errorClass: 'error text-danger',
		highlight: function(element) {
      $(element).parent().addClass("has-error");
    },
		unhighlight: function(element) {
      $(element).parent().removeClass("has-error");
    },
		rules: {
			fee_back_new:{
				required: true,
			},
			name_region: {
				required: true,
			},
			amount_of_free_insurance:{
				required: true,
			},
			insurance_price:{
				required: true,
			},
      price_region:{
        required: true,
				number:true
      },
      mass_region:{
				required: true,
				number:true
      },
			mass_region_free:{
				required: true,
				number:true
      },
      volume_region:{
				required: true,
				number:true
      },
			volume_region_free:{
				required: true,
				number:true
      },
			price_over_mass_region:{
				required: true,
				number:true
      },
			price_over_volume_region:{
				required: true,
				number:true
      }
		},
		messages: {
			name_region: {
				required: 'Bắt Buộc',
			},
      price_region:{
        required: 'Bắt Buộc',
				number:'Nhập Số'
      },
      mass_region:{
				required: 'Bắt Buộc',
				number:'Nhập Số'
      },
			mass_region_free:{
				required: 'Bắt Buộc',
				number:'Nhập Số'
      },
      volume_region:{
				required: 'Bắt Buộc',
				number:'Nhập Số'
      },
			volume_region_free:{
				required: 'Bắt Buộc',
				number:'Nhập Số'
      },
			price_over_mass_region:{
				required: 'Bắt Buộc',
				number:'Nhập Số'
      },
			price_over_volume_region:{
				required: 'Bắt Buộc',
				number:'Nhập Số'
      }
		}
	});

	var checkAlert = <?php echo isset($_SESSION['success']) ? 'true' : 'false'?>;

	if (checkAlert) {
		alert_float('success','Thêm Thành Công');
	}

	var checkAlert2 = <?php echo isset($_SESSION['success2']) ? 'true' : 'false'?>;

	if (checkAlert2) {
		alert_float('success','Sửa Thành Công');
	}

	//Change Input For Import CSV

	$(document).on('change', '.file_region', function(){

		var id = $(this).parent().attr('data-id');

		var file_data = this.files;
		var form_data = new FormData();
		$.each(file_data, function(i, v){
			form_data.append('file_region[]', v);
		})
		form_data.append('csrf_token_name', csrfData.hash);
		form_data.append('id', id);

		$.ajax({
				url: '/system/admin/customer_policy/add_file_region',
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,
				type: 'post',
				success: function (data) {
					$('.file_region').val(null);

						alert_float(JSON.parse(data).alert_type, JSON.parse(data).message);
				},
				error:function(e) {
					console.log(e);
				}
		});

	});




	$(document).on('click', '.edit-region', function(){
		var id = $(this).attr('data-id');

		$.ajax({
				url: '/system/admin/customer_policy/data_edit/'+id,
				cache: false,
				contentType: false,
				processData: false,
				type: 'get',
				success: function (data) {

					data = JSON.parse(data);


					//Set Value
					$('#edit_region').modal('show');
					$('#edit_region_form [name="price_over_mass_region"]').val(data[0].price_over_mass_region);
					$('#edit_region_form [name="name_region"]').val(data[0].name_region);
					$('#edit_region_form [name="volume_region"]').val(data[0].volume_region);
					$('#edit_region_form [name="volume_region_free"]').val(data[0].volume_region_free);
					$('#edit_region_form [name="price_region"]').val(data[0].price_region);
					$('#edit_region_form [name="price_over_volume_region"]').val(data[0].price_over_volume_region);
					$('#edit_region_form [name="id_region"]').val(data[0].id);
					$('#edit_region_form [name="mass_region_free"]').val(data[0].mass_region_free);
					$('#edit_region_form [name="mass_region"]').val(data[0].mass_region);
					$('#edit_region_form [name="insurance_price"]').val(data[0].insurance_price);
					$('#edit_region_form [name="amount_of_free_insurance"]').val(data[0].amount_of_free_insurance);

					$('#edit_region_form [name="max_day"]').val(data[0].max_day);
					$('#edit_region_form [name="order_region"]').val(data[0].order_region);
					$('#edit_region_form [name="fee_back_new"]').val(data[0].fee_back_new);
					var cleave = new Cleave('.input-element2', {
		 			    numeral: true,
		 			    numeralThousandsGroupStyle: 'thousand'
		 			});


				},
				error:function(e) {
					console.log(e);
				}
		});

	});






</script>

<style>
	.btn.btn-default.btn-csv {
		width: 30px;
		border: none;
		padding: 0 ! important;
		position: relative;
	}
	.btn.btn-default.btn-csv label {
		cursor: pointer;
     display: flex;
     width: 100%;
     height: 100%;
     justify-content: center;
     align-items: center;
     margin: 0;
     height: 27px;
     background: #9E9E9E;
     color: #fff;
     border-radius: 5px;
	}

	.btn.btn-default.btn-csv input {
		display: block;
		width: 100%;
		height: 100%;
		border: none;
		opacity: 0;
   position: absolute;
   z-index: -1;
	}
	.cover-btn-csv {
		display: flex;
		justify-content: center;
		align-items: center;
	}
	table.table-region thead th:first-child {
		width: 20%;
	}
	.cover-btn-csv  a {
		display: block;
		margin-bottom: 16px;
	}
</style>

</body>
</html>
