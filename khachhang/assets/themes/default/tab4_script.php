<script>
    let baseUrl =window.location.origin;
    $(document).ready(function () {
        var dateForm =$("#data-date-from").val()
        var dateTo =$("#data-date-to").val()
        $("#order-from-date-tab4").val(dateForm)
        $("#order-to-date-tab4").val(dateTo)



        $('.datetimepicker-date').datepicker({
            format: 'd-m-Y',
            timePicker: false,
        });

        $(".datetimepicker-date").each(function () {
            if ($(this).val() != "") {
                realDate = new Date($(this).val());
                $(this).datepicker("option", "dateFormat", "dd/mm/yy"); // format to show
                $(this).datepicker('setDate', realDate);
            }

        });


        var city = JSON.parse($("#data-city").val());
        let html_city = "<option><option>"
        html_city += city.map(function (value, index) {
            return `<option value="${value.city}">${value.city}</option>`
        }).join('');
        $("#cityTab4").html(html_city)


        $("#cityTab4").select2({
            placeholder: "Vui Lòng Chọn Tỉnh",
            allowClear: true
        });
        $("#kh-district-tab4").select2({
            placeholder: "Vui Lòng Chọn Huyện",
            allowClear: true
        });
        $("#region").select2({
            placeholder: "Vui Lòng Chọn Vùng Miền",
            allowClear: true
        });
        $("#status").select2({
            placeholder: "Vui Lòng Chọn Tình Trạng",
            allowClear: true
        });
        $("#is_hd_branch").select2({
            placeholder: "Vui Lòng Chọn Chi Nhánh",
            allowClear: true
        });
        $("#dvvc").select2({
            placeholder: "Vui Lòng Chọn Đơn Vị Vận Chuyển",
            allowClear: true
        })
        let link = getLinkTab4();
        loadDatatablesNew(link)
    });

    function emptyDate() {
        $(".datetimepicker-date").each(function () {
            realDate = new Date("");
            $(this).datepicker('setDate', '');
        });
    }
    function clickPrintA5(){
        var names = [];
        $('#example td input:checked').each(function() {
            names.push(this.value);
        });
        let listId = names.join(',');
        window.open(`/khachhang/api/create_order/print?list_id=${listId}`)
    }
    function getLinkView(key, code_supership, code_ghtk) {
        let link = '';
        if (key == "SPS") {
            link = 'https://mysupership.com/search?category=orders&query=' + code_supership;
        } else if (key == "GHTK") {
            link = 'https://khachhang.giaohangtietkiem.vn/khachhang?code=' + code_ghtk;
        }

        return link;
    }

    function getLinkPrint(key, create_order_id, code_supership) {
        let link = '';
        if (key == "SPS") {
            link = `https://mysupership.com/orders/print?code=${code_supership}&size=S9`;
        } else if (key == "GHTK") {
            link = `http://spshd.com/system/admin/create_order_ghtk/print_data_order/${create_order_id}?print=true`;
        }
        return link;
    }
    function toggleCheckBox(master,group){
        var array = document.getElementsByName(group);
        for(var i =0 ; i < array.length ; i++){
            array[i].checked = master.checked
        }
    }

    let loadDatatablesNew = (link) => {

        var table = $('#example').DataTable({
            "ajax": link,
            "columnDefs": [
                {
                    "width": "5%",
                    "targets": 0,
                    "data": null,
                    "render": function (data, type, row, meta) {
                        return '<div style="width: 100%;text-align: center;"><input name="check_id[]" value="'+row.id+'" type="checkbox"><div>';
                    }
                },
                {
                    "width": "5%",
                    "targets": 1,
                    "data": null,
                    "render": function (data, type, row, meta) {
                        return row.date_create;
                    }
                },
                {
                    "width": "20%",
                    "targets": 2,
                    "data": null,
                    "render": function (data, type, row, meta) {
                        let backgroundStatus =getColorStatus(row.status)
                        let dvvc = "";
                        if (row.DVVC != "") {
                            dvvc = `<p>ĐVVC: ${row.DVVC}</p>`
                        }
                        let mkh = "";
                        if (row.soc != null && row.soc != "") {
                            mkh = `<p>Mã Đơn KH : <span style="color:green">${row.soc}</span></p>`
                        }


                        return `
                                <div style="width: 100%" class="mb-5"><label class="label label-orange label-xs tooltips" style="color:red;" >&emsp;&emsp;${row.required_code} &emsp;&emsp;</label></div>
                                ${mkh}
                                <p>Ngày tạo : ${moment(row.created).format('DD-MM-YYYY HH:mm:SS')}</p>`;
                    }
                },
                {
                    "width": "20%",
                    "targets": 3,
                    "data": null,
                    "render": function (data, type, row, meta) {
                        let phi = `<p>Phí DV: <span style="color:red">${formatCurrency(row.hd_fee)}</span></p>`;
                        if (row.hd_fee == null) {
                            phi = `<p>Phí DV: <span style="color:red">${formatCurrency(row.hd_fee_stam)}</span></p>`
                        }
                        if (row.is_hd_branch == 0) {
                            phi = `<p>Phí DV: <span style="color:red">${formatCurrency(row.pay_transport)}</span></p>`
                        }
                        let mass = '';
                        if(row.weight != "" && row.weight != null){
                            mass = row.weight
                        }

                        return `
                                <p>SP: ${row.product}</p>
                                <p>Khối lượng: <span style="color:red">${mass}</span></p>
                                <p>Thu Hộ: <span style="color:red">${formatCurrency(row.amount)}</span></p>
                                <p>Phí DV: <span style="color:red">${formatCurrency(row.supership_value)}</span> </p>
                                `;
                    }
                },
                {
                    "width": "20%",
                    "targets": 4,
                    "data": null,
                    "render": function (data, type, row, meta) {
                        let address = `${(row.address) ? row.address + ", " : ""} ${(row.commune) ? row.commune + ", " : ""} ${(row.district) ? row.district + ", " : ""}  ${(row.province) ? row.province : ""} `;


                        return `
                                <div style="width: 100%" class="mb-5"><label class="label label-orange label-xs tooltips" data-original-title="Được tạo bằng API">&emsp;&emsp;${(row.province)?row.province:""}&emsp;&emsp;</label>&emsp;</div>
                                <p>${row.name}</p>
                                <p style="color:red">${row.phone}</p>
                                <p>${address}</p>`;
                    }
                },
                {
                    "width": "25%",
                    "targets": 5,
                    "data": null,
                    "render": function (data, type, row, meta) {
                        let linkXem = getLinkView(row.DVVC, row.code_supership, row.code_ghtk);
                        let linkPrint = `/khachhang/api/create_order/print?list_id=${row.id}`
                        return `<div style="width: 100%;table-layout: fixed;"><div class="mb-15" style="display:flex">
                                <a style="color: white" style="padding-right: 5px;" class="btn btn-sm btn-primary mr-2  " target="_blank" href="${linkPrint}"><i style="padding-right: 5px;" class="fa fa-print"></i>IN</a>
                                <a href="javascript:;" style="padding-right: 5px;" class=" edit-reminder-custom-order  btn btn-sm btn-primary button-blue mr-2 btn${row.id}"  data-id="${row.id}" data-note="${row.note}"><i style="padding-right: 5px;" class="fa fa-edit"></i>SỬA</a>
                                <a href="javascript:;" style="color: white" class="btn btn-sm btn-primary delete-reminder-custom-order" target="_blank" data-id="${row.id}"><i style="padding-right: 5px;" class="fa fa-trash"></i>HỦY</a>
                                </div>
                                <p style="color:#557f38"><strong>Ghi Chú Giao Hàng:</strong></p>
                                <p>${(row.note) ? row.note : ""}</p>
                                 </div>`;
                    }
                }

            ],
            "drawCallback": function (settings) {
            },
            "order": [[0, 'DESC']],
            searching: false,
            info: false,
            // lengthChange: false, // Will Disabled Record number per page
            processing: true,
            language: {
                emptyTable: " ",
                loadingRecords: '&nbsp;',
                processing: 'Loading...',
                lengthMenu: 'Hiển Thị <select>' +
                    '<option value="10">10</option>' +
                    '<option value="20">20</option>' +
                    '<option value="50">50</option>' +
                    '<option value="-1">Tất cả</option>' +
                    '</select> Dòng'
            }

        });
        table.on('order.dt search.dt', function () {
            table.column(1, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                cell.innerHTML = `<div style="text-align: center">${i + 1}</div>`;
            });

        }).draw();
        $('.kh-tab4 table').removeClass('dataTable')
    };

    function getColorStatus(status) {
        let color = "";
        switch (status) {
            case "Chờ Duyệt":
                color = "#0b8a00";
                break;
            case "Chờ Lấy Hàng":
                color = "#0b8a00";
                break;
            case "Đang Lấy Hàng":
                color = "#0b8a00";
                break;
            case "Đã Lấy Hàng":
                color = "#4f0080";
                break;
            case "Hoãn Lấy Hàng":
                color = "#cca200";
                break;
            case "Không Lấy Được":
                color = "#424242";
                break;
            case "Đang Nhập Kho":
                color = "#B40404";
                break;
            case "Đã Nhập Kho":
                color = "#04B4AE";
                break;
            case "Đang Chuyển Kho Giao":
                color = "#0040FF";
                break;
            case "Đã Chuyển Kho Giao":
                color = "#0B614B";
                break;
            case "Đang Giao Hàng":
                color = "#B40404";
                break;
            case "Đã Giao Hàng Toàn Bộ":
                color = "#610B0B";
                break;
            case "Đã Giao Hàng Một Phần":
                color = "#0080FF";
                break;
            case "Hoãn Giao Hàng":
                color = "#cca200";
                break;
            case "Không Giao Được":
                color = "#424242";
                break;
            case "Đã Đối Soát Giao Hàng":
                color = "#060070";
                break;
            case "Đã Đối Soát Trả Hàng":
                color = "#060070";
                break;
            case "Đang Chuyển Kho Trả":
                color = "#00646F";
                break;
            case "Đã Chuyển Kho Trả":
                color = "#777100";
                break;
            case "Đang Trả Hàng":
                color = "#B40404";
                break;
            case "Đã Trả Hàng":
                color = "#322F65";
                break;
            case "Hoãn Trả Hàng":
                color = "#cca200";
                break;
            case "Hủy":
                color = "#5F5F5F";
                break;
            case "Đang Vận Chuyển":
                color = "#B40404";
                break;
            case "Xác Nhận Hoàn":
                color = "#98782E";
                break;
            case "Đã Trả Hàng Một Phần":
                color = "#322F65";
                break;
        }
        return color
    }



    

    function clickSearchTab4() {

        let link = getLinkTab4();
        $('#example').dataTable().fnDestroy();
        loadDatatablesNew(link)
    }

    function convertDate(userDate) {
        str = userDate.split("/");
        return str[1] + "/" + str[0] + "/" + str[2]
    }

    function getLinkTab4(checkExcel = false) {
        let date_form = $("#order-from-date-tab4").val();
        let date_to = $("#order-to-date-tab4").val();
        let customer = $("#id_customer").val();
        let code_order = $("#code_order").val();
        let code_request = $("#code_request").val();
        let city = $("#cityTab4").val();
        let district = $("#districtTab4").val();
        let data = {
            date_form: (date_form) ? moment(new Date(convertDate(date_form))).format('YYYY/MM/DD') : "",
            date_to: (date_to) ? moment(new Date(convertDate(date_to))).format('YYYY/MM/DD') : "",
            customer: customer,
            status: status,
            code_order: code_order,
            code_request: code_request,
            city: city,
            district: district,
        };
        let linkApi = baseUrl+'/khachhang/api/order_tab4?jsonData=' + JSON.stringify(data);
        if (checkExcel) {
            linkApi = baseUrl+'/system/api/order/export_excel?jsonData=' + JSON.stringify(data)
        }
        return linkApi;
    }

    function get_region_by_city(city, district) {
        let data = {
            city: city,
            district: district,
        };
        let linkApi = '/api/get_region_by_city?jsonData=' + JSON.stringify(data);
        return linkApi;
    }

    function formatCurrency(amount) {
        if (!amount) {
            amount = 0;
        }
        let _currency = '';
        var formatter = new Intl.NumberFormat('vi-VN');
        amount = amount.toString().match(/\d+/);
        if (amount) {
            _currency = formatter.format(amount);
        }
        return _currency;
    }

    function modalUpdate(_this) {
        $("#modal-update").modal();
        document.getElementById("shop_id").value = $(_this).data('id');
        document.getElementById("ghi-chu-noi-bo-old").value = ($(_this).attr('data-note') != "null") ? $(_this).attr('data-note') : "";
        document.getElementById("ghi-chu-noi-bo-new").value = ""
    }

    function updateNode() {
        let noteOld = document.getElementById("ghi-chu-noi-bo-old").value;
        let noteNew = document.getElementById("ghi-chu-noi-bo-new").value;
        if (noteOld != "") {
            noteOld += '\n';
        }
        if (noteNew != "") {
            noteNew +=  moment(new Date()).format('HH:mm DD/MM') + " " + noteNew;
        }
        let text = noteOld + noteNew;
        note = JSON.stringify(text);
        let id = document.getElementById("shop_id").value;

        $.ajax({
            url: `/khachhang/api/order/update?note=${note}&id=${id}`, success: function (result) {
                if (result == true) {
                    $("#modal-update").modal('hide');
                    toastr.success('Ghi Chú Nội Bộ!', 'Cập Nhật Thành Công');
                    $(`.span${id}`).html(text);
                    $(`.btn${id}`).attr('data-note', text)
                }
            }
        });
    }
    function getDistrictTab4(_this) {
        let city = $(_this).val();

        $.ajax({
            url: "/khachhang/api/order/district?city=" + city, success: function (result) {
                let data = JSON.parse(result);
                if (data.data.length > 0) {
                    let html = "<option></option>";
                    html += data.data.map(function (value) {
                        return `<option value="${value.district}">${value.district}</option>`
                    }).join('');
                    $("#kh-district-tab4").html(html)
                }
            }
        });
    }

</script>


<script type="text/javascript">

    $('#success-order').on('hidden.bs.modal', function () {
        window.location.reload();
    });

    function format_date(data) {

        return `${data.split("-")[2]}/${data.split("-")[1]}`;
    }

    function InitOrderMobile() {
        $.ajax({
            type: "POST",
            url: "/khachhang/app/get_datatable_ajax_order",
            data: {
                customer_shop_code: $('#customer_shop_code').val(),
                id_customer: $('#id_customer').val(),
                mobile: true
            },
            cache: false,
            success: function (data) {
                var datalist = JSON.parse(data).list;

                var html = '';
                var i = 0;
                $.each(datalist, function (index, value) {
                    i++;
                    html += '<li>';
                    html += '   <p class="stt-left">';
                    html += i;
                    html += '   </p>';
                    html += '   <div class="left-width">';
                    html += '       <div class="row-1 border-row">';
                    html += '           <p class="left-row">';
                    html += '               <span style="color:red;font-weight:bold">' + moment(value.created).format('DD/MM HH:mm') + '</span>';
                    html += '               <span style="color:#000;font-weight:bold">' + value.required_code + '</span>';
                    html += '           </p>';
                    html += '<br>';
                    if(value.soc !="" && value.soc !="null" && value.soc != null){
                        html += '           <p>';
                        html += '               <span>Mã KH: </span>';
                        html += '               <span style="color:#000;font-weight:bold">' + value.soc + '</span>';
                        html += '           </p>';
                    }

                    html += '       </div>';
                    html += '       <div class="row-3 border-row" style="color:red">';
                    html += '             KL:' + value.weight + ' $Thu hộ:' + formatNumber(value.amount, '.') + ' $Phí:' + formatNumber(value.supership_value, '.');
                    html += '       </div>';
                    html += '       <div class="row-3 border-row">';
                    html += value.name + ' - ' + value.phone + ' - ' + value.province + ' - ' + value.district;
                    html += '       </div>';
                    html += '       <div class="row-3 border-row">';
                    html += value.note;
                    html += '       </div>';
                    html += '</div><div class="clear-fix"></div></li>';
                });

                $('.scroll-list-tab4').empty();


                $('.scroll-list-tab4').append(html);


            }
        });
    }

    $(document).ready(function () {

        var checkMobile = <?php echo $is_mobile ? 'true' : 'false'?>;
        if (checkMobile && $('.tab4').hasClass('active')) {
            $(window).scroll(function () {
                if ($(window).scrollTop() == $(document).height() - $(window).height()) {
                    console.log("crash");
                    // var msg_id = $(".message_box:last").data("id");
                    // $("#msg_loader").show();
                    // $.ajax({
                    //     type: "POST",
                    //     url: "fetch.php",
                    //     data: {msg_id: msg_id},
                    //     cache: false,
                    //     success: function (data) {
                    //         //Insert data after the message_box
                    //         $(".message_box:last").after(data);
                    //         $("#msg_loader").hide();
                    //     }
                    // });

                }
            });
        }


        var the_fee_bearer = 1;
        var province = '';
        var district = '';
        var policy_id = '';
        var data_for_calc = '';
        $("#date_start_customer_order").datepicker(
            {
                defaultDate: $(this).val(),
                dateFormat: 'dd-mm-yy'
            }
        );

        $("#date_end_customer_order").datepicker(
            {
                defaultDate: $(this).val(),
                dateFormat: 'dd-mm-yy'
            }
        );

        //Create order

        $('.open-modal-addnew-create-order').click(function () {

            $('#product').val('');
            $('#ap').val('');
            $('#phone_more').val('');
            $('#f').val('');
            $('#a').val('');
            $('#province').val('null');
            $('#district_order').empty();
            $('#area_hd_order').empty();
            // $('#mass').val('');
            $('#value_order').val('');
            // $('#volume').val('');
            $('#supership_value').val('');
            $('#check_disable_super').prop('checked', true);
            $('#barter').prop('checked', false);
            $('#the_fee_bearer').val('1');
            $('#cod').val('');
            $('#total_money').val('');
            $('#note_create').val('');
            $('#service').val('1');
            $('#config').val('1');
            $('#soc').val('');


            $('#create_order').modal('show');
            GetRepoOrder();
            $('#customer_id_create').val($('#id_customer').val());
            $('#pickup_phone').val($('#customer_phone_header').val());
            setValid();
            var w = window.innerWidth;
            var h = window.innerHeight;

            if (w < 768) {
                $('.modal-body').css('height', h - (44));
            } else {

                $('.modal-body').css('height', h - (44));
            }


            $.ajax({
                url: '/khachhang/app/check_customer_policy_exits/' + $('#id_customer').val(),
                method: 'GET',
                success: function (check) {
                    //
                    if (check === "custommer_no") {
                        alert("Khách Hàng Này Chưa Có Chính Sách");
                    } else {
                        policy_id = JSON.parse(check).id;

                        if (JSON.parse(check).special_policy !== '') {

                            var html = `<label>Chhính Sách Đặc Biệt</label><div style="height: 100px;overflow: auto;" class="form-control">${JSON.parse(check).special_policy}</div>`;

                            $('#special').empty();
                            $('#special').append(html);
                        }

						if (JSON.parse(check).note_default !== '')
                            $("#note_create").val(JSON.parse(check).note_default);
                    }
                }
            })

        });


		// Add excel
        $(".open-modal-addnew-create-order-excel").click(function (){
            $("#create_order-excel").modal("show");
            var token = $("#token_customer").val();
            $.ajax({
                url: '/khachhang/app/curlGetRepo',
                method: 'POST',
                data: {token},
                success: function (data) {
                    $('.disable-view').hide();
                    $('#loader-repo').hide();
                    data = JSON.parse(data);

                    var html = '<label for="repo_customer" style="margin-top: 2%">Kho hàng <span style="color: red">[*]</span></label><select class="form-control" id="repo_customer_order_excel" name="repo_customer_order_excel">';

                    // $('#repo_customer_cover');
                    for (var i = 0; i < data.length; i++) {
                        html += `<option value="${data[i].formatted_address}">${data[i].formatted_address}</option>`;
                    }
                    html += '</select>';
                    $('#repo_customer_cover_create_order_excel').empty();
                    $('#repo_customer_cover_create_order_excel').append(html);


                },
                error: function (e) {
                    console.log(e);
                }
            });

        });

        // Upload
        var http_arr = new Array();
        $(".submit_create_order_excel").click(function () {
            var http = new XMLHttpRequest();
            http_arr.push(http);
            $("#btn_upload").html("<i class='fa fa-spin fa-refresh'></i>");
            var files = document.getElementById('uploadfile').files[0];
            var warehouse = $('#repo_customer_order_excel').find(":selected").val();
            var id_customer = $('#id_customer').val();

            if(warehouse == ""){
                $("#alertError").show();
                $("#alertError").html('Kho hàng không được để trống.');
                $("#btn_upload").html("Tải");
                setTimeout(function () {
                    $("#alertError").hide();
                }, 5000);

                return false;
            }

            if(files == undefined){
                $("#alertError").show();
                $("#alertError").html('Bạn chưa chọn tệp tin.');
                $("#btn_upload").html("Tải");
                setTimeout(function () {
                    $("#alertError").hide();
                }, 5000);
                return false;
            }
            var data = new FormData();
            data.append('filename', files.name);
            data.append('uploadfile', files);
            data.append('warehouse', warehouse);
            data.append('id_customer', id_customer);
            http.open('POST',  '<?= base_url()?>app/upload', true);
            http.send(data);

            http.onreadystatechange = function (event) {
                $("#btn_upload").html("Tải");
                //Kiểm tra điều kiện
                if (http.readyState == 4 && http.status == 200){
                    try {
                        var server = JSON.parse(http.responseText);
                        if (server.status) {
                            $("#alertSuccess").show();
                            $("#alertSuccess").html(server.message);
                        } else {
                            $("#alertError").show();
                            $("#alertError").html(server.message);

                            setTimeout(function () {
                                $("#alertError").hide();
                            }, 5000);
                        }
                    } catch (e) {

                    }
                };
            }

        });

		$('#create_order-excel').on('hidden.bs.modal', function () {
            window.location.reload();
        });

        $(document).on('click', '.explain', function () {

            $(this).hide();
            $('.more-config').show();
            $('.collapse').show();
        });

        $(document).on('click', '.collapse', function () {
            $(this).hide();
            $('.more-config').hide();
            $('.explain').show();
        });

        $('.submit_create_order').click(function () {
            $('#create_order_ob').submit();
        });

        $('.submit_edit_order').click(function () {
            $('#create_order_ob').submit();
        });


        $(document).on('change', '#check_disable_super', function () {
            var check = $('#check_disable_super').prop('checked');
            $('#total_money').val('');
            $('#cod').val('');
            $('#supership_value').val('');
            if (check) {
                $('#supership_value').attr('disabled', true);
                $('#total_money').attr('disabled', false);
            } else {
                $('#supership_value').attr('disabled', false);
                $('#total_money').attr('disabled', true);
            }
        });
        $(document).on('change', '#mass', function () {
            $('#total_money').val('');
            $('#cod').val('');
            $('#supership_value').val('');
        });
        $(document).on('change', '#volume', function () {
            $('#total_money').val('');
            $('#cod').val('');
            $('#supership_value').val('');
        });

        var data_area = '';
        var data_city = '';
        var data_district = '';

        $(document).on('change', '#repo_customer_order', function () {

            var code = $(this).find(':selected').attr('data-code');
            $('#pickup_code').val(code);
        });
        $(document).on('change', '#province', function () {
            if (policy_id === '') {
                $('#province.selectpicker').selectpicker('val', 'null');
                alert('Chọn Khách Hàng Trước');
                return false;
            }
            var val = JSON.parse($(this).val());
            $('#total_money').val('');
            $('#cod').val('');
            $('#supership_value').val('');
            if (val) {

                $('.disable-view').show();
                $('#loader-repo2').show();
                province = val.name;
                district = '';
                $.ajax({
                    url: '/khachhang/app/get_district_by_hd/' + val.code,
                    method: 'GET',
                    success: function (data) {
                        data_area = '';
                        data_city = '';
                        data_district = '';

                        $('.disable-view').hide();
                        $('#loader-repo2').hide();
                        data = JSON.parse(data);
                        district = data;
                        $('#district_order').empty();
                        $('#area_hd_order').empty();
                        $('.load-html').empty();
                        $('#cod').val('');
                        $('#supership_value').val('');
                        $('#total_money').val('');
                        var html = '';

                        html += `<option  value='-1'>Chọn Quận Huyện/Thành Phố</option>`;
                        for (var i = 0; i < data.length; i++) {
                            html += `<option  value='${i}'>${data[i].name}</option>`;
                        }

                        $('#district_order').append(html);

                        $('#district_order').selectpicker({
                            liveSearch: true
                        });
                        $('#district_order').selectpicker('refresh');
                    },
                    error: function (e) {
                        console.log(e);
                    }
                });
            }
        });


        $(document).on('change', '#district_order', function () {
            console.log(district);

            var index = $(this).val();

            if (index > -1) {

                if (policy_id === '') {
                    alert('Chọn Khách Hàng Trước');
                    return false;
                }
                $('.disable-view').show();
                $('#loader-repo2').show();
                $('#total_money').val('');
                $('#cod').val('');
                $('#supership_value').val('');
                district_name = district[index].name;

                $.ajax({
                    url: '/khachhang/app/get_commune_by_hd/' + district[index].code,
                    method: 'GET',
                    success: function (data) {
                        $('.disable-view').hide();
                        $('#loader-repo2').hide();
                        data = JSON.parse(data);
                        area_hd_order = data;
                        $('#area_hd_order').empty();
                        var html = `<option  value='-1'>Vui lòng chọn xã / Phường</option>`;
                        for (var i = 0; i < data.length; i++) {
                            html += `<option  value='${i}'>${data[i].name}</option>`;
                        }
                        console.log(html);
                        $('#area_hd_order').append(html);
                        $('#area_hd_order').selectpicker({
                            liveSearch: true
                        });
                        $('#area_hd_order').selectpicker('refresh');

                        //Call Api
                        $.ajax({
                            url: `/khachhang/app/search_region?province=${province}&district=${district[index].name}&policy_id=${policy_id}`,
                            method: 'GET',
                            success: function (region) {

                                region = JSON.parse(region);
                                if (region.error === true) {
                                    alert('Huyện – Tỉnh này chưa có trong cơ sở dữ liệu thuộc nhóm vùng miền tính phí nào. Vui lòng chọn nhóm vùng miền tính phí.');
                                    // open modal add new region
                                    $('#create_order').modal('hide');
                                    $('#repo_customer_cover').empty();
                                    $('#search_customer_create').val('');

                                    $('#pickup_phone').val('');

                                    $('#create_order_ob input').val('');
                                    $('#special').val('');
                                    $('#pickup_code').val(0);
                                    $('#province').val('');
                                    $('#province').selectpicker('refresh');

                                    $('#district_order').empty();
                                    $('#area_hd_order').empty();


                                    $('#add_new_region').modal('show');

                                    $('#city_region').val(province);
                                    $('#district_region').val(district);
                                    province = '';
                                    district = '';
                                    policy_id = '';
                                    data_for_calc = '';
                                } else {
                                    var cost_super_ship;
                                    var fee_transport = '';

                                    data_for_calc = region.data_region;
                                    $('.load-html').empty();
                                    $('#cod').val('');
                                    $('#supership_value').val('');
                                    $('#total_money').val('');
                                    data_district = "";
                                    data_area = "";
                                    $('.region-box input').val(region.id);
                                    var tableHTML = `<table class="table">
                                          <tbody>
                                                <tr>
                                                  <td style="color:red">Nhóm Vùng Miền : ${region.name_region}</td>
                                                </tr>
                                          </tbody>
                                        </table>`;
                                    $('.region-box .load-html').empty();
                                    $('.region-box .load-html').append(tableHTML);
                                }


                            }
                        });


                    },
                    error: function (e) {
                        console.log(e);
                    }
                });
            }
        });

		$(document).on('change', '#area_hd_order', function(){
			var index = $(this).val();
			if(index > -1){
				data_area = area_hd_order[index].name;
			}
		});


        $.validator.addMethod("valueNotEquals", function (value, element, arg) {
            return arg !== value;
        }, "Hãy chọn.");

        $.validator.addMethod("valueUnique", function (value, element, arg) {
            var ReturnVal = false;

            return ReturnVal;
        }, "Mã đơn hàng đã tồn tại ");


        $(document).on('change', '#soc', function (e) {
            var value = $('#soc').val();
            $.ajax({
                url: '/khachhang/app/check_soc/' + value,
                type: 'GET',
                success: function (data) {
                    if (JSON.parse(data).length === 0) {
                        return true;

                    } else {
                        $('#soc').val('');
                        alert('Mã đơn hàng đã tồn tại');
                    }
                },
                error: function (e) {
                    console.log(e);
                }
            });
        });


        $('#success-order').on('hidden.bs.modal', function () {
            $('#table_customer_order').DataTable().ajax.reload();
        });


        $(document).on('click', '.delete-reminder-custom-order', function () {
            var c = confirm("Bạn Muốn Xoá Thực Sự");

            if (!c) {
                return false;
            } else {
                $('.loading-page').show();
                var id = $(this).attr('data-id');


                $.ajax({
                    url: '/khachhang/app/delete_order/' + id,
                    success: function (data) {

                        if (JSON.parse(data).success == 'ok') {
                            $('.loading-page').hide();
                            $.notify("Delete Thành Công", "success");

                            if (window.innerWidth > 768) {
                                $('#table_customer_order').DataTable().ajax.reload();
                            } else {
                                //Mobile
                            }
                        } else {
                            $('.loading-page').hide();
                            $.notify("Delete Thất Bại", "error");
                            if (window.innerWidth > 768) {
                                $('#table_customer_order').DataTable().ajax.reload();
                            } else {
                                //Mobile
                            }
                        }
                    },
                    error: function (e) {
                        $('.loading-page').hide();
                        $.notify("Delete Thất Bại", "error");
                    }
                });


            }
        });

        // Edit order
        $(document).on('click', '.edit-reminder-custom-order', function () {
            var id = $(this).attr('data-id');
            $.ajax({
                url: '<?= base_url('app/get_order')?>',
                data: {id: id},
                method: "GET",
                beforeSend: function () {
                    $(".loading-page").show();
                },
                success: function (data) {
                    $(".loading-page").hide();
                    var result = JSON.parse(data).order;
                    $("#product").val(result.product);
                    $("#ap").val(result.phone);
                    if (result.sphone != "") {
                        $("#phone-more").show();
                        $("#phone_more").val(result.sphone);
                    }
                    $("#f").val(result.name);
                    $("#a").val(result.address);
                    $("#mass").val(result.weight);
                    $("#value_order").val(formatNumber(result.value));
                    $("#cod").val(formatNumber(result.cod));
                    $("#note_create").val(result.note);
                    $("#total_money").val(formatNumber(result.amount));
                    $("#supership_value").val(formatNumber(result.supership_value));

                    $('#province').val(JSON.stringify({'name': result.province}));
                    $('.filter-option-inner-inner').html(result.province);

                    var districts = result.list_districts;
                    district = result.list_districts;
                    // Quận huyện
                    $('#district_order').empty();
                    var html = '';
                    html += `<option  value='null'>Chọn Quận Huyện/Thành Phố</option>`;
                    for (var i = 0; i < districts.length; i++) {
                        html += '<option value="' + i + '">' + districts[i].name + '</option>';
                    }
                    $('#district_order').append(html);
                    $('#district_order').selectpicker({
                        liveSearch: true
                    });

                    $('#district_order').selectpicker('refresh');
                    // district = result.district;
                    $('#district_order').val(JSON.stringify({'name': result.district}));
                    $('#district_order').parent().find('.filter-option-inner-inner').html(result.district);

                    var areas = result.list_areas;
					area_hd_order = result.list_areas;

                    $('#area_hd_order').empty();
                    var html = '<option  value="null">Chọn Phường Xã</option>';
                    for (var i = 0; i < areas.length; i++) {
                        html += `<option  value='${i}'>${areas[i].name}</option>`;
                    }
                    $('#area_hd_order').append(html);
                    $('#area_hd_order').selectpicker({
                        liveSearch: true
                    });
                    $('#area_hd_order').selectpicker('refresh');
                    $('#area_hd_order').parent().find('.filter-option-inner-inner').html(result.commune);
                    $('#area_hd_order').val(result.commune);

                    data_area = result.commune;
                    data_city = result.province;
                    province = result.province;
                    data_district = result.district;

					$.ajax({
                        url: '/khachhang/app/check_customer_policy_exits/' + $('#id_customer').val(),
                        method: 'GET',
                        success: function (check) {
                            //
                            if (check === "custommer_no") {
                                alert("Khách Hàng Này Chưa Có Chính Sách");
                            } else {
                                policy_id = JSON.parse(check).id;

                                if (JSON.parse(check).special_policy !== '') {

                                    var html = `<label>Chhính Sách Đặc Biệt</label><div style="height: 100px;overflow: auto;" class="form-control">${JSON.parse(check).special_policy}</div>`;

                                    $('#special').empty();
                                    $('#special').append(html);
                                }


								$.ajax({
                        url: `/khachhang/app/search_region?province=${result.province}&district=${result.district}&policy_id=${policy_id}`,
                        method: 'GET',
                        success: function (region) {
                            region = JSON.parse(region);
                            if (region.error === true) {
                                alert('Huyện – Tỉnh này chưa có trong cơ sở dữ liệu thuộc nhóm vùng miền tính phí nào. Vui lòng chọn nhóm vùng miền tính phí.');
                                // open modal add new region
                                $('#create_order').modal('hide');
                                $('#repo_customer_cover').empty();
                                $('#search_customer_create').val('');

                                $('#pickup_phone').val('');

                                $('#create_order_ob input').val('');
                                $('#special').val('');
                                $('#pickup_code').val(0);
                                $('#province').val('');
                                $('#province').selectpicker('refresh');

                                $('#district_order').empty();
                                $('#area_hd_order').empty();


                                $('#add_new_region').modal('show');

                                $('#city_region').val(province);
                                $('#district_region').val(district);
                                province = '';
                                district = '';
                                policy_id = '';
                                data_for_calc = '';
                            } else {
                                var cost_super_ship;
                                var fee_transport = '';

                                data_for_calc = region.data_region;
                                $('.load-html').empty();
                                // $('#cod').val('');
                                // $('#supership_value').val('');
                                // $('#total_money').val('');

                                $('.region-box input').val(region.id);
                                var tableHTML = `<table class="table">
                                          <tbody>
                                                <tr>
                                                  <td style="color:red">Nhóm Vùng Miền : ${region.name_region}</td>
                                                </tr>
                                          </tbody>
                                        </table>`;
                                $('.region-box .load-html').empty();
                                $('.region-box .load-html').append(tableHTML);
                            }


                        }
                    });

					GetRepoOrder();
                    setValid();

                    $("#create_order").modal();
                    $("#btn_create").removeClass('submit_create_order');
                    $("#btn_create").addClass('submit_edit_order');
                    $("#btn_create").attr('data-id', result.id);

                            }
                        }
                    });


                }
            });
        });


        function setValid() {
            $("#create_order_ob").validate({
                errorClass: 'error text-danger',
                highlight: function (element) {
                    $(element).parent().addClass("has-error");
                },
                unhighlight: function (element) {
                    $(element).parent().removeClass("has-error");
                },
                onfocusout: false,
                invalidHandler: function (form, validator) {
                    var errors = validator.numberOfInvalids();
                    if (errors) {
                        validator.errorList[0].element.focus();
                    }
                },
                ignore: [],
                rules: {
                    customer_id: {
                        required: true,
                    },
                    product: {
                        required: true,
                    },
                    ap: {
                        required: true,
                        number: true,
                        minlength: 10,
                        maxlength: 10
                    },
                    province: {
                        valueNotEquals: "null"
                    },
                    f: {
                        required: true,
                    },
                    a: {
                        required: true,
                        minlength: 5,
                    },
                    district: {
                        valueNotEquals: "null"
                    },
                    area_hd: {
                        valueNotEquals: "null"
                    },
                    mass: {
                        required: true,
                    },
                    volume: {
                        required: true,
                    },
                    cod: {
                        required: true,
                    },
                    total_money: {
                        required: true,
                    }
                },
                messages: {},
                submitHandler: function (form) {
                    var data = {};
                    var repo_customer = $('#repo_customer_order').val().split(",");

                    var order_id = $("#btn_create").attr("data-id");
                    if (order_id > 0) {
                        data.id = order_id;
                    }

                    data.customer_id = $('#id_customer').val();


                    data.pickup_address = repo_customer.slice(0, repo_customer.length - 2).toString().trim();

                    data.pickup_district = repo_customer[repo_customer.length - 2].trim();
                    data.pickup_province = repo_customer[repo_customer.length - 1].trim();
                    if (parseINT($('#total_money').val()) >= 3000000 && !$('#value_order').val()) {
                        alert('Đơn hàng này phải tính phí bảo hiểm. Vui lòng nhập trị giá. ');
                        $('#value_order').focus();
                        return false;
                    }

                    if (parseINT($('#value_order').val()) > 0 && parseINT($('#value_order').val()) < parseINT($("#cod").val())) {
                        alert('Trị giá đơn hàng phải lớn hơn hoặc bằng tiền hàng. ');
                        return false;
                    }
                    data.product = $('#product').val();
                    data.product_type = "1";
                    data.name = $('#f').val();
                    data.phone = $('#ap').val();
                    data.sphone = $('#phone_more').val();
                    data.address = $('#a').val();

                    if (order_id < 1) {
                        data.province = JSON.parse($('#province').val()).name;
                        data.district = district[$('#district_order').val()].name;
                        data.commune = area_hd_order[$('#area_hd_order').val()].name;
                    } else {
                        if (data_city != "") {
                            data.province = data_city;
                        } else {
                            data.province = JSON.parse($('#province').val()).name;
                            data.district = district[$('#district_order').val()].name;
                            data.commune = area_hd_order[$('#area_hd_order').val()].name;
                        }

                        if(data_district != ""){
                            data.district = data_district;
                        }else{
                            data.district = district[$('#district_order').val()].name;
                        }

                        if(data_area != ""){
                            data.commune = data_area;
                        }else{
                            data.commune = area_hd_order[$('#area_hd_order').val()].name;
                        }
                    }

                    data.amount = parseINT($('#total_money').val());
                    data.weight = parseINT($('#mass').val());
                    data.volume = parseINT($('#volume').val());
                    data.soc = $('#soc').val();
                    data.note = $('#note_create').val();
                    data.pickup_phone = $('#pickup_phone').val();
                    data.service = $('#service').val();
                    data.supership_value = parseINT($('#supership_value').val());
                    data.config = $('#config').val();
                    data.payer = $('#payer').val();
                    data.cod = parseINT($('#cod').val());
                    if (barterValue) {
                        data.barter = "1";
                    } else {
                        data.barter = "0";
                    }
                    var value_order = parseINT($('#value_order').val());
                    if (parseINT($('#value_order').val()) == 0 || !$('#value_order').val())
                        value_order = parseINT($("#total_money").val());


                    data.value = value_order;
                    data.token = $('#token_customer').val();
                    data.region_id = $("#region_id").val();
                    $('.loading-page').show();

                    var url = '/khachhang/app/create_order/';
                    if (order_id > 0) {
                        url = '/khachhang/app/edit_order/';
                    }

                    $.ajax({
                        url: url,
                        data,
                        type: 'POST',
                        success: function (data) {
                            $('.loading-page').hide();

                            $('#create_order').modal('hide');

                            if (JSON.parse(data).success === 'ok') {

                                $('#success-order').modal();
                                $('#show-code').text(JSON.parse(data).code);

                            } else {

                                $('.loading-page').hide();
                                $('#create_order').modal('hide');

                            }

                        },
                        error: function (e) {
                            $('.loading-page').hide();
                            $('#create_order').modal('hide');
                        }
                    });

                }
            });

        }

        function parseINT(a) {
            a = a.replace(/\,/g, ''); // 1125, but a string, so convert it to number
            return a = parseInt(Number(a), 10);
        }

        // $('#create_order').modal('show');


        var barterValue = false;
        $(document).on('click', '#barter', function (e) {

            barterValue = !barterValue;
        });


        $(document).on('keyup', '#supership_value', function (e) {
            var cod = parseINT($('#cod').val());
            var super_ship = parseINT($('#supership_value').val());
            var total = Number(cod) + Number(super_ship);
            if (policy_id === '') {
                alert('Hãy chọn khách hàng trước');
                return false;
            } else if (district === '') {
                alert('Hãy chọn huyện trước');
                return false;
            } else if (province === '') {
                alert('Hãy chọn tỉnh trước');
                return false;
            } else if (data_for_calc === '') {
                alert('Xảy ra lỗi đáng tiếc');
                return false;
            } else if ($('#mass').val() === '') {
                alert('Hãy Nhập Khối lượng trước');
                return false;
            } else if ($('#volume').val() === '') {
                alert('Hãy Nhập thể tích trước');
                return false;
            } else {
                if (cod) {
                    var total = Number(cod) + Number(super_ship);
                } else {
                    var total = Number(super_ship);
                }
                $('#total_money').val(formatNumber(total));
            }
        });

        $(document).on('keyup', '#value_order', function (e) {
            $('#total_money').val('');
            $('#cod').val('');
        });


        function CalcAll() {
            var supership_cost = '';
            var cod;
            if (policy_id === '') {
                alert('Hãy chọn khách hàng trước');

                return false;
            } else if (district === '') {

                alert('Hãy chọn huyện trước');

                return false;
            } else if (province === '') {
                alert('Hãy chọn tỉnh trước');


                return false;
            } else if (data_for_calc === '') {
                alert('Xảy ra lỗi đáng tiếc');
                return false;
            } else if ($('#mass').val() === '') {
                alert('Hãy Nhập Khối lượng trước');
                return false;
            } else if ($('#volume').val() === '') {
                alert('Hãy Nhập thể tích trước');
                return false;
            } else {
                supership_cost = Number(data_for_calc.price_region);
                $('#tvc span').html(formatNumber(Number(data_for_calc.price_region)));

                //Tính khối lượng
                var massInput = parseINT($('#mass').val());
                var massFree = Number(data_for_calc.mass_region);
                var masscalc = (massInput - massFree) / Number(data_for_calc.mass_region_free)
                if (masscalc < 0) {
                    masscalc = 0;
                } else {
                    masscalc = Math.ceil(masscalc) * Number(data_for_calc.price_over_mass_region);
                }
                $('#tvk span').html(formatNumber(masscalc));


                //Tính Thể Tích
                var volumeInput = parseINT($('#volume').val());
                var volumeFree = Number(data_for_calc.volume_region);
                var volumecalc = (volumeInput - volumeFree) / Number(data_for_calc.volume_region_free)
                if (volumecalc < 0) {
                    volumecalc = 0;
                } else {
                    volumecalc = Math.ceil(volumecalc) * Number(data_for_calc.price_over_volume_region);
                }
                $('#tvtt span').html(formatNumber(volumecalc));

                if (masscalc > volumecalc) {
                    supership_cost += masscalc;
                } else if (masscalc < volumecalc) {
                    supership_cost += volumecalc;
                } else {
                    supership_cost += masscalc;
                }


                var value_order = 0;
                if ($('#value_order').val() == '') {
                    value_order = 0;
                } else {
                    value_order = parseINT($('#value_order').val());
                }


                var x = value_order - data_for_calc.amount_of_free_insurance;
                //So sánh trị giá
                if (x <= 0) {
                    insurance = 0;
                } else {
                    insurance = (value_order * data_for_calc.insurance_price) / 100;
                    insurance = Math.round(insurance / 1000) * 1000;
                }


                $('#tbh span').html(formatNumber(insurance));


                supership_cost = supership_cost + insurance;
                $('#supership_value').val(formatNumber(supership_cost));


                return supership_cost;
            }
        }

        $(document).on('change', '#the_fee_bearer', function (e) {
            the_fee_bearer = $(this).val();
            $('#cod').val('');
            $('#total_money').val('');

            // if (the_fee_bearer == 1) {
            //
            //     if ($('#note_create').val() != '') {
            //         $('#note_create').val($('#note_create').val() + "\nNgười GỬI CHỊU PHÍ supership.");
            //     } else {
            //
            //         $('#note_create').val('Người GỬI CHỊU PHÍ supership.');
            //     }
            // } else {
            //     $('#note_create').val($('#note_create').val().replace('Người GỬI CHỊU PHÍ supership.', ''));
            // }
        });

        $(document).on('keyup', '#cod', function (e) {
            var superShip = CalcAll();
            var total;

            if (the_fee_bearer == '0') {
                total = superShip + parseINT($('#cod').val());
            } else {
                total = parseINT($('#cod').val());
            }

            $('#total_money').val(formatNumber(total));
        });

        $(document).on('keyup', '#total_money', function (e) {
            var superShip = CalcAll();
            var cod;
            if (the_fee_bearer == '0') {
                cod = parseINT($('#total_money').val()) - superShip;
            } else {
                cod = parseINT($('#total_money').val());
            }

            $('#cod').val(formatNumber(cod));

        });

        $(document).on('click', '.add-more-phone', function (e) {
            $('.phone-more').toggle();
        });


        function GetRepoOrder() {
            var id = $('#id_customer').val();
            var token = $('#token_customer').val();
            var phone = $('#customer_phone_header').val();
            var text = $('#customer_shop_code').val();

            $('#search_customer_create').val(text.trim());
            $('.search-item').hide();
            $('#customer_id').val(id);
            $('.disable-view').show();
            $('#loader-repo').show();

            $.ajax({
                url: '/khachhang/app/curlGetRepo',
                method: 'POST',
                data: {token},
                success: function (data) {
                    $('.disable-view').hide();
                    $('#loader-repo').hide();
                    data = JSON.parse(data);

                    var html = '<label for="repo_customer">Chọn Kho:</label><select class="form-control" id="repo_customer_order" name="repo_customer_order">';

                    // $('#repo_customer_cover');
                    for (var i = 0; i < data.length; i++) {
                        html += `<option value="${data[i].formatted_address}">${data[i].formatted_address}</option>`;
                    }
                    html += '</select>';
                    $('#repo_customer_cover_create_order').empty();
                    $('#repo_customer_cover_create_order').append(html);


                },
                error: function (e) {
                    console.log(e);
                }
            });
        }

    });

    function orderInit() {
        var PickUpData = $('#table_customer_order').DataTable(configDataOrder);
        PickUpData.column(0).visible(false);
        PickUpData.column(2).visible(false);
        PickUpData.column(4).visible(false);

        PickUpData.column(8).visible(false);
        PickUpData.column(9).visible(false);
        PickUpData.column(10).visible(false);
        PickUpData.column(11).visible(false);
        PickUpData.column(12).visible(false);
        PickUpData.column(15).visible(false);
        PickUpData.column(18).visible(false);
        PickUpData.column(19).visible(false);
        PickUpData.column(20).visible(false);
        PickUpData.column(21).visible(false);
        PickUpData.column(22).visible(false);
        PickUpData.column(23).visible(false);
        PickUpData.column(24).visible(false);
        PickUpData.column(25).visible(false);
        PickUpData.column(27).visible(false);
    }


    var configDataOrder = {
        "processing": true,
        "serverSide": true,
        "info": false,
        "searching": false,
        "ordering": false,
        "autoWidth": false,
        dom: 'lBfrtip',
        "language": {
            "emptyTable": "Không Tồn Tại Dữ Liệu",
            "lengthMenu": " _MENU_ "
        },

        select: true,
        buttons: [{
            extend: 'collection',
            text: 'Xuất Ra',
            exportOptions: {
                columns: [1, 2]
            },
            buttons: [

                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [0, 2, 3, 4, 5, 6, 7, 15]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: [0, 2, 3, 4, 5, 6, 7, 15]
                    }
                }
            ]
        }],
        "ajax": {
            "url": "<?php echo base_url('/app/get_datatable_ajax_order');?>",
            "type": "POST",
            data: function (d) {
                d.customer_shop_code = $('#customer_shop_code').val(),
                    d.id_customer = $('#id_customer').val()
            }
        }
    };


    $(document).on('change', '#province_filter', function () {
        var val = JSON.parse($(this).val());

        if (val) {

            $('.disable-view').show();
            $('#loader-repo4').show();
            province = val.name;
            districts = '';
            $.ajax({
                url: '/khachhang/app/get_district_by_hd/' + val.code,
                method: 'GET',
                success: function (data) {

                    $('.disable-view').hide();
                    $('#loader-repo4').hide();
                    data = JSON.parse(data);
                    districts = data;
                    $('#district_filter').empty();

                    console.log(districts);
                    var html = '';

                    html += `<option  value='null'>Chọn Quận Huyện/Thành Phố</option>`;
                    for (var i = 0; i < data.length; i++) {
                        html += '<option  value="' + i + '">' + data[i].name + '</option>';
                    }
                    $('#district_filter').append(html);
                    $('#district_filter').selectpicker({
                        liveSearch: true
                    });
                    $('#district_filter').selectpicker('refresh');
                },
                error: function (e) {
                    console.log(e);
                }
            });
        }

    });

    function load_table_customer_tab4() {
        var province_filter = 'null';
        var district_filter = 'null';


        if ($('#province_filter').val() != 'null') {
            province_filter = JSON.parse($('#province_filter').val()).name;
        } else {
            province_filter = 'null';
        }

        if ($('#district_filter').val() != null) {
            district_filter = $('#district_filter').val();

        } else {
            district_filter = 'null';
        }
        console.log(districts);
        var configDataOrderFilter = {
            "processing": true,
            "serverSide": true,
            "info": false,
            "searching": false,
            "ordering": false,
            "autoWidth": false,
            dom: 'lBfrtip',
            "language": {
                "emptyTable": "Không Tồn Tại Dữ Liệu",
                "lengthMenu": "Display _MENU_ records"
            },
            select: true,
            buttons: [{
                extend: 'collection',
                text: 'Xuất Ra',
                exportOptions: {
                    columns: [1, 2]
                },
                buttons: [

                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [0, 2, 3, 4, 5, 6, 7, 15]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [0, 2, 3, 4, 5, 6, 7, 15]
                        }
                    }
                ]
            }],
            "ajax": {
                "url": "<?php echo base_url('/app/get_datatable_ajax_order');?>",
                "type": "POST",
                data: function (d) {
                    d.customer_shop_code = $('#customer_shop_code').val(),
                        d.id_customer = $('#id_customer').val(),
                        d.date_start_customer_order = $('#date_start_customer_order').val(),
                        d.date_end_customer_order = $('#date_end_customer_order').val(),
                        d.province_filter = province_filter,
                        d.district_filter = districts[district_filter].name,
                        d.filter = true
                }
            }
        };

        $('#table_customer_order').DataTable().destroy();
        var PickUpData = $('#table_customer_order').DataTable(configDataOrderFilter);
        PickUpData.column(0).visible(false);
        PickUpData.column(2).visible(false);
        PickUpData.column(4).visible(false);

        PickUpData.column(8).visible(false);
        PickUpData.column(9).visible(false);
        PickUpData.column(10).visible(false);
        PickUpData.column(11).visible(false);
        PickUpData.column(12).visible(false);
        PickUpData.column(15).visible(false);
        PickUpData.column(18).visible(false);
        PickUpData.column(19).visible(false);
        PickUpData.column(20).visible(false);
        PickUpData.column(21).visible(false);
        PickUpData.column(22).visible(false);
        PickUpData.column(23).visible(false);
        PickUpData.column(24).visible(false);
        PickUpData.column(25).visible(false);
        PickUpData.column(27).visible(false);
    }

    $('#btn-export-excel').on('click', function () {
        console.log("1234");
        let url = window.location.href + '/app/export_exel_orders?startDate=' + $('#date_start_customer_order').val() + '&endDate=' + $('#date_end_customer_order').val();
        window.open(url, '_blank');
        win.focus();
    });

    function load_mobile_customer_tab4() {
        var province_filter = 'null';
        var district_filter = 'null';


        if ($('#province_filter').val() != 'null') {
            province_filter = JSON.parse($('#province_filter').val()).name;
        } else {
            province_filter = 'null';
        }

        if ($('#district_filter').val() != null) {
            district_filter = $('#district_filter').val();

        } else {
            district_filter = 'null';
        }

        $.ajax({
            url: "<?php echo base_url('/app/get_datatable_ajax_order');?>",
            data: {
                customer_shop_code: $('#customer_shop_code').val(),
                id_customer: $('#id_customer').val(),
                date_start_customer_order: $('#date_start_customer_order').val(),
                date_end_customer_order: $('#date_end_customer_order').val(),
                province_filter: province_filter,
                district_filter: districts[district_filter].name,
                filter: true,
                mobile: true
            },
            method: "POST",
            beforeSend: function () {
            },
            success: function (data) {
                var datalist = JSON.parse(data).list;

                var html = '';
                var i = 0;
                $.each(datalist, function (index, value) {
                    i++;
                    html += '<li>';
                    html += '   <p class="stt-left">';
                    html += i;
                    html += '   </p>';
                    html += '   <div class="left-width">';
                    html += '       <div class="row-1 border-row">';
                    html += '           <p class="left-row">';
                    html += '               <span style="color:red;font-weight:bold">' + moment(value.created).format('DD/MM HH:mm') + '</span>';
                    html += '               <span style="color:#000;font-weight:bold">' + value.required_code + '</span>';
                    html += '           </p>';
                    html += '<br>';
                    html += '           <p>';
                    html += '               <span>Mã ĐH: </span>';
                    html += '               <span style="color:#000;font-weight:bold">' + value.soc + '</span>';
                    html += '           </p>';
                    html += '       </div>';
                    html += '       <div class="row-3 border-row" style="color:red">';
                    html += '             KL:' + value.weight + ' $Thu hộ:' + formatNumber(value.amount, '.') + ' $Phí:' + formatNumber(value.supership_value, '.');
                    html += '       </div>';
                    html += '       <div class="row-3 border-row">';
                    html += value.name + ' - ' + value.phone + ' - ' + value.province + ' - ' + value.district;
                    html += '       </div>';
                    html += '</div><div class="clear-fix"></div></li>';
                });

                $('.scroll-list-tab4').empty();

                $('.scroll-list-tab4').append(html);

            }
        });

    }

</script>
