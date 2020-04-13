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
                    html += '               <span style="color:red;font-weight:bold">' + value.created.split('-')[2] + '/' + value.created.split('-')[1] + '</span>';
                    html += '               <span style="color:#000;font-weight:bold">' + value.required_code + '</span>';
                    html += '           </p>';
                    html += '<br>';
                    html += '           <p>';
                    html += '               <span>Mã ĐH: </span>';
                    html += '               <span style="color:#000;font-weight:bold">' + value.code + '</span>';
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


                    }
                }
            })

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
                        var html = '';
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


                    data.customer_id = $('#id_customer').val();


                    data.pickup_address = repo_customer.slice(0, repo_customer.length - 2).toString().trim();

                    data.pickup_district = repo_customer[repo_customer.length - 2].trim();
                    data.pickup_province = repo_customer[repo_customer.length - 1].trim();
                    if (parseINT($('#total_money').val()) >= 3000000 && !$('#value_order').val()) {
                        alert('Đơn hàng này phải tính phí bảo hiểm. Vui lòng nhập trị giá. ');
                        $('#value_order').focus();
                        return false;
                    }

                    if ($('#value_order').val() && parseINT($('#value_order').val()) >= 0 && parseINT($('#value_order').val()) < parseINT($("#cod").val())) {
                        alert('Trị giá đơn hàng phải lớn hơn hoặc bằng tiền hàng. ');
                        return false;
                    }
                    data.product = $('#product').val();
                    data.product_type = "1";
                    data.name = $('#f').val();
                    data.phone = $('#ap').val();
                    data.sphone = $('#phone_more').val();
                    data.address = $('#a').val();
                    data.province = JSON.parse($('#province').val()).name;
                    data.district = district[$('#district_order').val()].name;
                    data.commune = area_hd_order[$('#area_hd_order').val()].name;
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

                    $.ajax({
                        url: '/khachhang/app/create_order/',
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
                        html += `<option value="${data[i].formatted_address.replace(", Tỉnh Hải Dương", "")}">${data[i].formatted_address.replace(", Tỉnh Hải Dương", "")}</option>`;
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
                    html += '               <span style="color:red;font-weight:bold">' + value.created.split('-')[2] + '/' + value.created.split('-')[1] + '</span>';
                    html += '               <span style="color:#000;font-weight:bold">' + value.required_code + '</span>';
                    html += '           </p>';
                    html += '<br>';
                    html += '           <p>';
                    html += '               <span>Mã ĐH: </span>';
                    html += '               <span style="color:#000;font-weight:bold">' + value.code + '</span>';
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
