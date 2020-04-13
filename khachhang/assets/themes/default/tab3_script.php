<script type="text/javascript">

function pickUpInit() {
  var PickUpData = $('#table_customer_pickup').DataTable(configDataPickup);
  PickUpData.column(1).visible(false);
  PickUpData.column(2).visible(false);
  PickUpData.column(8).visible(false);
  PickUpData.column(9).visible(false);
  PickUpData.column(10).visible(false);
  PickUpData.column(11).visible(false);
  PickUpData.column(12).visible(false);
  PickUpData.column(13).visible(false);
  PickUpData.column(14).visible(false);

  var Picked = $('#table_customer_picked').DataTable(configDataPicked);
  Picked.column(1).visible(false);
  Picked.column(2).visible(false);
  Picked.column(8).visible(false);
  Picked.column(9).visible(false);
  Picked.column(10).visible(false);
  Picked.column(11).visible(false);
  Picked.column(12).visible(false);
  Picked.column(13).visible(false);
  Picked.column(14).visible(false);
}

function date_format(date) {
  var today = new Date(date);
  var dd = today.getDate();

  var mm = today.getMonth()+1;
  var yyyy = today.getFullYear();
  if(dd<10)
  {
      dd='0'+dd;
  }

  if(mm<10)
  {
      mm='0'+mm;
  }
  return dd+'/'+mm+'/'+yyyy;
}

function RenderMobile_Get_or_not(status) {
  if (status == 1) {
    return 'Đã Lấy'
  }else {
    return 'Chưa Lấy'
  }
}
function InitDate_tab3(data) {

  return `${data.split("-")[2]}/${data.split("-")[1]}`;
}

$(document).on("change","#limit_geted select",function() {

  pickUpInitMobile();



});
function user_geted_number_order(data) {

  if (data.status == '1') {
    if (data.lastname) {
      return `người lấy :${data.lastname} ${data.firstname} - số đơn: ${data.number_order_get}`;
    }else {
      return `số đơn: ${data.number_order_get}`;
    }
  }else {
    return '';
  }


}
function pickUpInitMobile() {
  var limit = $('#limit_geted select#limitpage').val();
  var customer_id = $('#id_customer').val();
  $.ajax({
      url: `/khachhang/app/pick_up_mobile?limit=${limit}&&customer_id=${customer_id}`,
      success: function (data) {
        $('.disable-view').fadeOut();
        data = JSON.parse(data);


        if (data.length > 0) {
          $('.table-data.table3 tbody').empty();
          var html = '';

          for (var i = 0; i < data.length; i++) {
            html += `<li>
              <p class="stt-left">
                ${RenderMobile_Get_or_not(data[i].status)}
              </p>
                <div class="left-width">
                  <div class="row-1 border-row">
                    <p class="">
                      <span style="color:red;font-weight:bold">
                        ${InitDate_tab3(data[i].created)}
                      </span>
                      <span style="color:#000;font-weight:bold">
                        ${data[i].repo_customer}
                      </span>
                    </p>
                    <p class="">
                      ${data[i].note}
                    </p>
                    <p>
                      ${user_geted_number_order(data[i])}
                    </p>

                  </div>

                  ${data[i].status == '1' ? '' : `<div class="row-3 border-row">
                    <a data-id="${data[i].id}" class="edit-customer" style="margin-right:5px;" href="javascript:;"><i class="fa fa-pencil"></i></a>
                    <a data-id="${data[i].id}" class="delete-reminder-custom" style="color:#a73a3a" href="javascript:;"><i class="fa fa-trash"></i></a>
                  </div>`}





                </div>

                <div class="clear-fix"></div>
              </li>`;


          }
          $('.scroll-list-tab3').empty();

          $('.scroll-list-tab3').append(html);
        }
        else {
          $('.scroll-list-tab3').empty();
          var html = `<li><p class="data-empty">Chưa Có Danh Sách Đã Lấy Hàng </p></li>`;
          $('.scroll-list-tab3').append(html);
        }

      }
  });
}

    var configDataPickup = {
      "processing": true,
      "serverSide": true,
      "info": false,
      "searching": false,
      "ordering": false,
      "autoWidth": false,
      dom: 'Bfrtip',
      "language": {
        "emptyTable": "Không Tồn Tại Dữ L"
      },
      select: true,
      buttons: [{
        extend: 'collection',
        text: 'Xuất Ra',
        exportOptions: {
                  columns: [1,2]
              },
        buttons: [

            {
                extend: 'excelHtml5',
                exportOptions: {
                  columns: [ 0, 2, 3 , 4 , 5 , 6 , 7 , 15 ]
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                  columns: [ 0, 2, 3 , 4 , 5 , 6 , 7 , 15 ]
                }
            }
          ]
        }],
          "ajax": {
              "url": "<?php echo base_url('/app/datatables_ajax_pick_up');?>",
              "type": "POST",
              data: function ( d ) {
                d.customer_shop_code = $('#customer_shop_code').val(),
                d.id_customer = $('#id_customer').val()
            }
          }
        };


    var configDataPicked = {
      "processing": true,
      "serverSide": true,
      "info": false,
      "searching": false,
      "autoWidth": false,
      "ordering": false,
      "dom": 'Bfrtip',
      "language": {
        "emptyTable": "Không Tồn Tại Dữ L"
      },
      select: true,
      buttons: [{
        extend: 'collection',
        text: 'Xuất Ra',
        exportOptions: {
                  columns: [1,2]
              },
        buttons: [

            {
                extend: 'excelHtml5',
                exportOptions: {
                  columns: [ 0, 2, 3 , 4 , 5 , 6 , 7 , 15 ]
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                  columns: [ 0, 2, 3 , 4 , 5 , 6 , 7 , 15 ]
                }
            }
          ]
        }],
          "ajax": {
              "url": "<?php echo base_url('/app/datatables_ajax_picked');?>",
              "type": "POST",
              data: function ( d ) {
                d.customer_shop_code = $('#customer_shop_code').val(),
                d.id_customer = $('#id_customer').val()
            }
          }
        };






  $('.open-modal-addnew-create').click(function() {

    if (window.innerWidth < 768) {
      var height = window.innerHeight - 62;
      $('#customer_create .modal-body').css('height',height+'px');
    }

    $('#customer_create').modal('show');
		$( "#created" ).datepicker().datepicker("setDate", new Date());
		$('.modal-title').text('Thêm Điểm Nhận Hàng');
		$('#type_customer').val('old');
		$('#receive_or_pay').val('0');
		$('#repo_customer').empty();
		$('#search_customer').val('');
		$('#note_pickup_create').val('');
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
    repoOld();
    GetRepo();

  });


  function setValid() {

    $("#add_new_pick_up_points_create").validate().destroy();

    $("#add_new_pick_up_points_create").validate({
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

  $(document).on('click', '.delete-reminder-custom', function(){
		var c = confirm("Bạn Muốn Xoá Thực Sự");

		if (!c) {
			return false;
		}else {
      $('.loading-page').show();
      var id = $(this).attr('data-id');


      $.ajax({
          url: '/khachhang/app/pick_up_points_delete/'+id,
          success: function (data) {
            $('.loading-page').hide();
            $.notify("Delete Thành Công", "success");

            if (window.innerWidth > 768) {
              $('#table_customer_pickup').DataTable().ajax.reload();
            }else {
              pickUpInitMobile();
            }


          },
          error:function(e) {
            $('.loading-page').hide();
            $.notify("Delete Thất Bại", "error");
          }
      });



    }
	});

  /**
   * Switch Tab
   * @var [type]
   */
	$(document).on('click', '.tab-show-data li', function(e){
		var tab = $(this).attr('data-tab');
		$('.tab-cover .tab-table').hide();
		$('.tab-show-data li').removeClass('active');
		$('.'+tab).show();
		$(this).addClass('active');
	})

  $(document).on('click', '.edit-customer', function(){

		var id = $(this).attr('data-id');
    $('#add_new_pick_up_points').attr('data-id',id);
		$('.modal-title').text('Sửa Điểm Nhận Hàng');

		$.ajax({
				url: '/khachhang/app/getDataEdit/'+id,
				success: function (data) {
					data = JSON.parse(data);

					$('#customer').modal('show');
          if (window.innerWidth < 768) {
            var height = window.innerHeight - 62;
            $('#customer .modal-body').css('height',height+'px');
          }

					setFromData(data);


				},
				error:function(e) {
					console.log(e);
				}
		});

	});


  function setFromData(data) {

		var value = Object.values(data);
		var key = Object.keys(data);


		for (var i = 0; i < value.length; i++) {

			if ($(`#add_new_pick_up_points [name="${key[i]}"]`).length) {

        if (data.customer_id !== "0") {
          $.ajax({
              url: '/khachhang/app/getCustomer/'+$('#id_customer').val(),
              success: function (data) {
                data = JSON.parse(data);

                $('#search_customer').val(data.customer_shop_code);

              },
              error:function(e) {
                console.log(e);
              }
          });
        }
				if (data.customer_id !== "0") {

					setRepoWhenEdit(data.repo_customer);
				}

				$(`#add_new_pick_up_points [name="${key[i]}"]`).val(value[i]);
			}
		}


	}


  function setRepoWhenEdit(data) {
    var token = $('#token_customer').val();

    $.ajax({
      url: '/khachhang/app/curlGetRepo',
      method: 'POST',
      data: {token},
      success: function(data){
        $('.disable-view').hide();
        $('#loader-repo').hide();
        data = JSON.parse(data);

        var html = '<label for="repo_customer">Chọn Kho:</label><select class="form-control" id="repo_customer" name="repo_customer">';

        // $('#repo_customer_cover');
        for (var i = 0; i < data.length; i++) {

          if (data == data[i].formatted_address.replace(", Tỉnh Hải Dương" , "")) {
            html += `<option selected value="${data[i].formatted_address.replace(", Tỉnh Hải Dương" , "")}">${data[i].formatted_address.replace(", Tỉnh Hải Dương" , "")}</option>`;
          }else {
            html += `<option value="${data[i].formatted_address.replace(", Tỉnh Hải Dương" , "")}">${data[i].formatted_address.replace(", Tỉnh Hải Dương" , "")}</option>`;
          }

        }
        html += '</select>';
        $('#repo_customer_cover2').empty();
        $('#repo_customer_cover2').append(html);


        // setDataHidden(data[0]);
      },
      error:function(e) {
        console.log(e);
      }
    });


	}

  $(document).on('click', '.submit_customer_policy', function(e){
    $('.loading-page').show();
    e.stopPropagation();
    var id = $('#add_new_pick_up_points').attr('data-id');

    var data = {
      id,
      note:$('#note').val(),
      repo_customer:$('#repo_customer').val()
    }

    $.ajax({
      url: '/khachhang/app/editPickup',
      method: 'POST',
      data,
      success: function(data){
        var data = JSON.parse(data);

        if (data.status) {
          $.notify("Sửa Thành Công", "success");
          $('.loading-page').hide();
          $('#customer').modal('hide');
          $('#table_customer_pickup').DataTable().ajax.reload();
        }else {
          $.notify("Sửa Thất Bại", "error");
          $('.loading-page').hide();
          $('#customer').modal('hide');
          $('#table_customer_pickup').DataTable().ajax.reload();
        }

      }

    });

  });



  $(document).on('change', '#repo_customer', function(){
		var data = $(this).val();
		setDataHidden(data)
	});

  $(document).on('click', '.submit_customer_policy_new', function(e){
    // $('.loading-page').show();
    e.stopPropagation();


    var data = {
      customer_id:$('#id_customer').val(),
      repo_customer:$('#add_new_pick_up_points_create #repo_customer').val(),
      phone_customer:$('#add_new_pick_up_points_create #phone_customer').val(),
      note:$('#add_new_pick_up_points_create #note_pickup_create').val(),
      district_filter:$('#add_new_pick_up_points_create #district_filter').val(),
      commune_filter:$('#add_new_pick_up_points_create #commune_filter').val(),
      address_filter:$('#add_new_pick_up_points_create #address_filter').val(),
      display_name:$('#customer_shop_code').val(),
      from_customer:1,
    }

    $.ajax({
      url: '/khachhang/app/add_pickup',
      method: 'POST',
      data,
      success: function(data){
        var data = JSON.parse(data);

        if (data.status) {
          $.notify("Thêm Thành Công", "success");
          $('.loading-page').hide();
          $('#customer_create').modal('hide');
          if (window.innerWidth > 768) {
            $('#table_customer_pickup').DataTable().ajax.reload();
          }else {
            pickUpInitMobile();
          }

        }else {
          if (data.mess) {
              $.notify(data.mess, "error");
          }else {
              $.notify("Thêm Thất Bại", "error");
          }

          $('.loading-page').hide();
          $('#customer_create').modal('hide');
          if (window.innerWidth > 768) {
            $('#table_customer_pickup').DataTable().ajax.reload();
          }else {
            pickUpInitMobile();
          }

        }

      }

    });

  });



  function repoOld() {

		$('#district_filter').val('');
		$('#commune_filter').val('');
		$('#address_filter').val('');

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


  function GetRepo() {
    var id = $('#id_customer').val();
		var token = $('#token_customer').val();
		var phone = $('#customer_phone_header').val();
		var text = $('#customer_shop_code').val();

		$('#search_customer').val(text.trim());
		$('.search-item').hide();
		$('#customer_id').val(id);
		$('.disable-view').show();
		$('#loader-repo').show();

		$.ajax({
	    url: '/khachhang/app/curlGetRepo',
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
  }

  $(document).on('change', '#repo_customer', function(){
		var data = $(this).val();
    console.log(data);
		setDataHidden(data)
	});
  function setDataHidden(data) {

		data = data.formatted_address.split(',');
      console.log(data[0]);
		$('#address_filter').val(data[0]);
		$('#commune_filter').val(data[1]);
		$('#district_filter').val(data[2]);
	}




  //delete ham















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

	$(document).on('click', '.check-change-status', function(){
		var dom = $(this).parent();
		var id = $(this).parent().attr('data-id');
		var status;
		if ($(this)[0].checked) {
			status = true;

		}else {
			status = false;

		}
		data = {id , status , mod:true };

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


</script>
