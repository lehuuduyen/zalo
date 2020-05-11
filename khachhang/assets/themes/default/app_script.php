<script>
    //Check Date For Mobile
    function InitDate(data) {
        if (data[16] === 'Đơn Hàng') {
            return `${data[1].split("/")[0]}/${data[1].split("/")[1]}`;
        } else {
            return `${data[2].split("/")[0]}/${data[2].split("/")[1]}`;
        }
    }

    function parseINT(a) {
        a = a.replace(/\,/g, ''); // 1125, but a string, so convert it to number
        return a = parseInt(Number(a), 10);
    }

    function formatNumber(nStr, decSeperate = ".", groupSeperate = ",") {
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

    //Check PS
    function InitPS(data) {
        if (data[16] === 'Đơn Hàng') {
            if ((parseINT(data[6]) - parseINT(data[7])) >= 0) {
                return '+' + formatNumber(parseINT(data[6]) - parseINT(data[7]));
            } else {
                return '-' + formatNumber(parseINT(data[6]) - parseINT(data[7]));
            }
            return formatNumber(parseINT(data[6]) - parseINT(data[7]));
        } else {
            if (data[7] != '0') {
                return '-' + data[7];

            } else {
                return '+' + data[6];

            }
        }
    }

    //Chekc Color Red Or Green
    function CheckColor(data) {

        if (data[16] === 'Đơn Hàng') {
            if (parseINT(data[6]) - parseINT(data[7]) >= 0) {
                return 'green';
            } else {
                return 'red';
            }
            return formatNumber(parseINT(data[7]) - parseINT(data[6]));
        } else if (data[3].toLowerCase() === 'đã thu') {
            return 'green';
        } else if (data[3].toLowerCase() === 'đã chi') {
            return 'red';
        } else {

            if (data[7] != '0') {
                return 'red';
            } else {
                return 'green';
            }
        }
    }

    function CheckStatus(data, date, check) {
        if (check === 'Đơn Hàng') {
            return `<div class="row-2 border-row">
      Tạo (${date.split("/")[0]}/${date.split("/")[1]}),${data}
    </div>`;
        } else {
            return '';
        }
    }

    function ContentKL(data) {
        if (data) {
            return `<div class="row-3 border-row" style="color:red">
      ${data}
    </div>`;
        } else {
            return ``;
        }
    }

    var TaAPI;


    var checkMobile = <?php echo $is_mobile ? 'true' : 'false'?>;
    $(document).ready(function () {


        $("#date_start_customer").datepicker(
            {
                defaultDate: $(this).val(),
                dateFormat: 'dd-mm-yy',
                minDate: new Date(2019, 9 - 1, 1)
            }
        );

        $("#date_end_customer").datepicker(
            {
                defaultDate: $(this).val(),
                dateFormat: 'dd-mm-yy'
            }
        );

        $("#date_start_order").datepicker(
            {
                defaultDate: $(this).val(),
                dateFormat: 'dd-mm-yy',
                minDate: new Date(2019, 9 - 1, 1)
            }
        );

        $("#date_end_order").datepicker(
            {
                defaultDate: $(this).val(),
                dateFormat: 'dd-mm-yy'
            }
        );

        $("#date_start_create_order").datepicker(
            {
                defaultDate: $(this).val(),
                dateFormat: 'dd-mm-yy',
                minDate: new Date(2019, 9 - 1, 1)
            }
        );

        $("#date_end_create_order").datepicker(
            {
                defaultDate: $(this).val(),
                dateFormat: 'dd-mm-yy'
            }
        );

        $("#date_start_customer_order_tab2").datepicker(
            {
                defaultDate: $(this).val(),
                dateFormat: 'dd-mm-yy',
                minDate: new Date(2019, 9 - 1, 1)
            }
        );

        $("#date_end_customer_order_tab2").datepicker(
            {
                defaultDate: $(this).val(),
                dateFormat: 'dd-mm-yy'
            }
        );

        $.fn.dataTable.ext.errMode = 'none';

        var configData = {
            "processing": true,
            "serverSide": true,
            "info": false,
            "paging": false,
            "searching": false,
            "ordering": false,
            dom: 'Bfrtip',
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
                            columns: [1, 2, 3, 4, 5, 6, 8, 9]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 8, 9]
                        }
                    }
                ]
            }],
            "ajax": {
                "url": "<?php echo base_url('/app/datatables_ajax_detail');?>",
                "type": "POST",
                data: function (d) {
                    d.date_start_customer = $('#date_start_customer').val(),
                        d.date_end_customer = $('#date_end_customer').val(),
                        d.customer_shop_code = $('#customer_shop_code').val(),
                        d.id_customer = $('#id_customer').val()
                }
            }
        };

        var tableDetail = TaAPI = $('#table-debts_customer_detail').DataTable(configData);
        tableDetail.column(0).visible(false);
        tableDetail.column(10).visible(false);
        tableDetail.column(7).visible(false);
        tableDetail.column(11).visible(false);
        tableDetail.column(12).visible(false);
        tableDetail.column(13).visible(false);
        tableDetail.column(14).visible(false);


        if (checkMobile == true) {

            InitDataMobile();
        }

        function load_table_customer() {
            if (checkMobile != true) {
                $('#table-debts_customer_detail').DataTable().ajax.reload();
            } else {
                InitDataMobile();
            }

        }
    });


    function InitDataMobile() {
        $('.loading-page').show();
        var d = {};
        d.date_start_customer = $('#date_start_customer').val(),
            d.date_end_customer = $('#date_end_customer').val(),
            d.customer_shop_code = $('#customer_shop_code').val(),
            d.id_customer = $('#id_customer').val();

        $.ajax({
            type: 'POST',
            url: "<?php echo base_url('/app/datatables_ajax_detail');?>",
            data: d,
            dataType: "text",
            success: function (resultData) {
                $('.loading-page').hide();

                resultData = JSON.parse(resultData);

                var datalist = resultData.aaData;
                var html = ``;

                for (var i = 0; i < (datalist.length - 1); i++) {
                    required_code ='';
                    if(datalist[i][20] !=""&& datalist[i][20] != null && datalist[i][20] != 'UNDEFINED'){
                        required_code = ` <p class="righ-row">
                                          ${datalist[i][20]}
                                        </p>`
                    }
                    if (datalist[i][1]) {
                        html += `<li>
                <div class="left-width">
                  <div class="row-1 border-row">
                    <p class="left-row">
                      <span style="color:red;font-weight:bold">
                        ${InitDate(datalist[i])}
                      </span>
                      <span style="color:#000;font-weight:bold">
                        ${datalist[i][4]}
                      </span>
                    </p>
                    <p class="righ-row ${CheckColor(datalist[i])}">
                      ${InitPS(datalist[i])}
                    </p>
                    ${required_code}
                  </div>

                  ${CheckStatus(datalist[i][5], datalist[i][2], datalist[i][16])}

                  ${ContentKL(datalist[i][17])}





                  <div class="row-3 border-row">
                    ${datalist[i][9]}
                  </div>

                </div>
                <div class="right-width">
                  ${datalist[i][8]}
                </div>

                <div class="clear-fix"></div>
              </li>`;
                    }

                }


                html += `<li>
            <div class="left-width">
              <div class="row-1 border-row">
                <p class="left-row">
                  <span style="color:red;font-weight:bold">

                  </span>
                  <span style="color:#000;font-weight:bold">

                  </span>
                </p>
                <p style="text-align:right" class="righ-row ">
                  Công Nợ Trước Ngày: ${$('#date_start_customer').val()}
                </p>
              </div>





            </div>
            <div class="right-width">
              ${datalist[datalist.length - 1][8]}
            </div>
            <div class="clear-fix"></div>
          </li>`;


                $('.scroll-list').empty();
                $('.scroll-list').append(html);
            }
        });
    }


    function InitTableCong() {
        if (checkMobile != true) {
            TaAPI.draw('page');
        } else {
            InitDataMobile();
        }
    }
    // $("#search").on('keyup', function (e) {
    //     if (e.keyCode === 13) {
    //         fnSearch($(this).attr('device'))
    //     }
    // });
    function searchTab6(event) {
            if (event.keyCode === 13) {
                fnSearch($(this).attr('device'))
            }
    }
    function enterTab7(event) {
        if (event.keyCode === 13) {
            clickSearch()
        }
    }
    function enterTab4(event) {
        if (event.keyCode === 13) {
            $('#create_order_ob').submit();
        }
    }
    function fnSearch(device = 0) {
        var keyword = $("#search").val();

        if (keyword == "") {
            alert('Bạn cần nhập số điện thoại hoặc mã đơn hàng.');
            $("#search").focus();
            return false;
        }

        $(".loading-page").show();

        $.ajax({
            url: '<?= base_url('app/search')?>',
            data: {keyword: keyword},
            success: function (data) {
                var html = "";
                var result = JSON.parse(data);
                var i = 0;
                $(".loading-page").hide();
                if (device) {
                    $.each(result.list_result, function (index, value) {
                        i++;
                        html += '<li onclick="fnDetail(\'' + value.code_supership + '\',' + value.id + ',\'' + value.DVVC + '\',' + device + ')">';
                        html += '   <p class="stt-left">' + i + '</p>';
                        html += '   <div class="left-width">';
                        html += '       <div class="row-1 border-row">';
                        html += '           <p class="left-row">';
                        html += '               <span style="color:red;font-weight:bold">' + value.date_create.split(" ")[0].split('-')[2] + '/' + value.date_create.split(" ")[0].split('-')[1] + '</span>';
                        html += '               <span style="color:#000;font-weight:bold">' + value.code_supership + ' - <span style="font-weight:400;font-size:10px;">' + value.status + '</span></span>';
                        html += '           </p>';
                        html += '        </div>';
                        html += '        <div class="row-3 border-row" style="color:red">';

                        if (value.hd_fee != null) {
                            html += '           KL:' + value.mass + ' $Thu hộ:' + formatNumber(value.collect) + ' $Phí:' + formatNumber(value.hd_fee);
                        } else
                            html += '           KL:' + value.mass + ' $Thu hộ:' + formatNumber(value.collect) + ' $Phí:' + formatNumber(value.hd_fee_stam);

                        html += '       </div>';
                        html += '       <div class="row-3 border-row">';
                        html += value.receiver + ' - ' + value.phone + ' - ' + value.city + ' - ' + value.district;
                        html += '       </div>';
                        html += '   </div>';
                        html += '   <div class="clear-fix"></div>';
                        html += '</li>';
                    });

                    $("div.boxTab6 > div.init-data-mobile > ul#boxSearch").html(html);
                } else {
                    $.each(result.list_result, function (index, value) {
                        html += '<tr onclick="fnDetail(\'' + value.code_supership + '\',' + value.id + ',\'' + value.DVVC + '\',' + device + ')" style="cursor: pointer">';
                        html += '   <td>' + value.date_create.split(" ")[0].split('-')[2] + '/' + value.date_create.split(" ")[0].split('-')[1] + '/' + value.date_create.split(" ")[0].split('-')[0] + '</td>';
                        html += '   <td>' + value.code_supership + '</td>';
                        html += '   <td>' + value.status + '</td>';
                        if (value.hd_fee != null) {
                            html += '<td> $Thu hộ:' + formatNumber(value.collect) + ' $Phí:' + formatNumber(value.hd_fee) + ' (KL:' + value.mass + ', ' + value.receiver + ' - ' + value.phone + ' - ' + value.city + ' - ' + value.district + ')</td>';
                        } else {
                            html += '<td> $Thu hộ:' + formatNumber(value.collect) + ' $Phí:' + formatNumber(value.hd_fee_stam) + ' (KL:' + value.mass + ', ' + value.receiver + ' - ' + value.phone + ' - ' + value.city + ' - ' + value.district + ')</td>';
                        }
                        html += '</tr>';
                    });
                    $('#listBoxSearch').html(html);
                }
            }
        });
    }

    function fnDetail(code_supship, id, dvvc, device) {
        $.ajax({
            url: '<?= base_url('app/tracking')?>',
            data: {code: code_supship, id: id, dvvc: dvvc},
            success: function (data) {
                var result = JSON.parse(data);
                if (result.status == true && result.error == '') {
                    var info_order = result.info_order;
                    var journeys = result.journeys;
                    var phone = result.phone;
                    var html = '';

                    html += '<div class="modal-dialog">';
                    html += '   <div class="modal-content">';
                    html += '       <div class="modal-header">';
                    html += '           <h5 class="modal-title" style="font-size: 20px">Thông tin đơn hàng</h5>';
                    html += '           <button type="button" class="close" style="margin-top: -27px;" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    html += '       </div>';
                    html += '       <div class="modal-body" style="text-align:center">';
                    html += '           <div class="init-data-mobile">';
                    html += '               <ul class="scroll-list-tab2" id="boxSearch" style="list-style: none;">';
                    html += '                   <li>';
                    html += '                       <div class="left-width" style="padding-left: 0px">';
                    html += '                           <div class="row-1 border-row">';
                    html += '                               <p>';
                    html += '                                   <span style="color:red;font-weight:bold">' + info_order.date_create.split(" ")[0].split('-')[2] + '/' + info_order.date_create.split(" ")[0].split('-')[1] + '</span>';
                    html += '                                   <span style="color:#000;font-weight:bold">' + info_order.code_supership + '<br><span style="font-weight:400;">' + info_order.status + '</span></span>';
                    html += '                               </p>';
                    html += '                           </div>';
                    html += '                           <div class="row-3 border-row" style="color:red">';

                    if (info_order.hd_fee == null)
                        html += '                            KL:' + info_order.mass + ' $Thu hộ:' + formatNumber(info_order.collect, '.') + ' $Phí:' + formatNumber(info_order.hd_fee_stam, '.');
                    else
                        html += '                            KL:' + info_order.mass + ' $Thu hộ:' + formatNumber(info_order.collect, '.') + ' $Phí:' + formatNumber(info_order.hd_fee, '.');


                    html += '                           </div>';
                    html += '                           <div class="row-3 border-row">';
                    html += info_order.receiver + ' - ' + info_order.phone + ' - ' + info_order.city + ' - ' + info_order.district;
                    html += '                           </div>';
                    html += '                       </div>';
                    html += '                       <div class="clear-fix"></div>';
                    html += '                   </li>';
                    html += '                </ul>';
                    html += '               <div class="footer-app"></div>';
                    html += '           </div>';

                    if (dvvc == 'SPS') {
                        var i = 0;
                        if (device == 1)
                            html += '<h3 style="margin-top: 35%">Hành trình</h3>';
                        else
                            html += '<h3>Hành trình</h3>';
                        html += '<div style="overflow-y: scroll;height: 290px;">';
                        $.each(journeys, function (index, value) {
                            i++;
                            html += '<div class="row">';
                            html += '   <div class="col-xs-7"><b>' + value.note + '</b></div>';
                            html += '   <div class="col-xs-1"></div>';
                            html += '   <div class="col-xs-4" style="float: right; width: 34.333333%;">' + value.time.split('T')[0].split('-')[2] + '-' + value.time.split('T')[0].split('-')[1] + ' ' + value.time.split('T')[1].split(':')[0] + ':' + value.time.split('T')[1].split(':')[1] + '</div>';
                            html += '</div>';
                            html += '<hr>';
                        })
                        html += '</div>';
                    }

                    html += '       </div>';
                    if (device == 1)
                        html += '           <a style="width: 100%;background: #a73a3a" type="button" class="btn btn-danger" href="tel:' + result.phone + '">Liên hệ hỗ trợ</a>';
                    else
                        html += '           <a style="width: 100%;background: #a73a3a" type="button" class="btn btn-danger" href="javascript:void(0)">Hotline: ' + formatPhoneNumber(result.phone) + '</a>';
                    html += '   </div>';
                    html += '</div>';

                    $("#info-order").html(html);

                    $("#info-order").modal('show');
                }

            }
        });
    }

    function fnFilter_list(device = 0) {
        var province = $("#status-tab2").val();
        var date_start_customer_order = $("#date_start_customer_order_tab2").val();
        var date_end_customer_order = $("#date_end_customer_order_tab2").val();
        var code_order_tab2 = $("#code_order_tab2").val();
        var limit_geted = $("#limitpageTab2").find(":selected").val();

        $.ajax({
            url: '<?= base_url('app/filter_list')?>',
            data: {
                province: province,
                date_start_customer_order: date_start_customer_order,
                date_end_customer_order: date_end_customer_order,
                limit_geted: limit_geted,
                code_order:code_order_tab2
            },
            beforeSend: function () {

            },
            success: function (data) {
                var result = JSON.parse(data);
                // alert_float('success',response.message);
                if (result.status == true && result.error == '') {
                    var datalist = result.aaData;
                    var html = '';
                    var i = 0;
                    $.each(datalist, function (index, value) {
                        required_code='';
                        if( value['required_code'] !=""&&  value['required_code'] != null &&  value['required_code'] != 'UNDEFINED'){
                            required_code = ` <div class="righ-row">
                                          ${ value['required_code']}
                                        </div>`
                        }
                        i++;
                        html += '<li onclick="fnDetail(\'' + value.code_supership + '\',' + value.id + ',\'' + value.DVVC + '\',' + device + ')">';
                        html += '   <p class="stt-left">' + i + '</p>';
                        html += '   <div class="left-width">';
                        html += '       <div class="row-1 border-row">';
                        html += '           <p class="left-row">';
                        html += '               <span style="color:red;font-weight:bold">' + value.date_create.split(" ")[0].split('-')[2] + '/' + value.date_create.split(" ")[0].split('-')[1] + '</span>';
                        html += '               <span style="color:#000;font-weight:bold">' + value.code_supership + ' - <span style="font-weight:400;font-size:10px;">' + value.status + '</span></span>';
                        html += '           </p>';
                        html += '        </div>';
                        html += required_code;

                        html += '        <div class="row-3 border-row" style="color:red">';

                        if (value.hd_fee != null) {
                            html += '           KL:' + value.mass + ' $Thu hộ:' + formatNumber(value.collect) + ' $Phí:' + formatNumber(value.hd_fee);
                        } else
                            html += '           KL:' + value.mass + ' $Thu hộ:' + formatNumber(value.collect) + ' $Phí:' + formatNumber(value.hd_fee_stam);

                        html += '       </div>';
                        html += '       <div class="row-3 border-row">';
                        html += value.receiver + ' - ' + value.phone + ' - ' + value.city + ' - ' + value.district;
                        html += '       </div>';
                        html += '   </div>';
                        html += '       <div class="row-3 border-row">';
                        html += value.note;
                        html += '       </div>';
                        html += `<div class="row-3 border-row"> <i class='fa fa-eye' ></i> <a href="#">Hành trình</a> </div>`;
                        html += '   <div class="clear-fix"></div>';
                        html += '</li>';

                    });
                    $('.scroll-list-tab2').empty();

                    $('.scroll-list-tab2').append(html);
                } else
                    alert("Xảy ra lỗi!");
            }
        });
    }

    var isMobile = <?php echo $this->isAppMobile === true ? 'true' : 'false'?>;

    function initOrderManager(pages = 0) {
        var date_start_customer_order = $("#date_start_order").val();
        var date_end_customer_order = $("#date_end_order").val();
        var status = $("#status-tab2-pc").find(":selected").val();
        page = 1;
        if (pages > 1)
            var page = $("#number-page").val();

        $.ajax({
            url: '<?= base_url('app/order_manager_list')?>',
            data: {
                date_start_customer_order: date_start_customer_order,
                date_end_customer_order: date_end_customer_order,
                status: status,
                page: page
            },
            method: "POST",
            beforeSend: function () {
                $(".loading-page").show();
            },
            success: function (data) {
                $(".loading-page").hide();
                var result = JSON.parse(data);
                var html = '';
                if (result.status == true && result.error == '') {
                    var list_order = result.list_order;
                    var i = 0;
                    if (isMobile == true) {
                        $.each(list_order, function (index, value) {
                            i++;
                            html += '<li onclick="fnDetail(\'' + value.code_supership + '\',' + value.id + ',\'' + value.DVVC + '\',1)" style="cursor: pointer">';
                            html += '   <p class="stt-left">';
                            html += i;
                            html += '   </p>';
                            html += '   <div class="left-width">';
                            html += '       <div class="row-1 border-row">';
                            html += '           <p class="left-row">';
                            html += '               <span style="color:red;font-weight:bold">' + value.date_create.split(" ")[0].split('-')[2] + '/' + value.date_create.split(" ")[0].split('-')[1] + '</span>';
                            html += '               <span style="color:#000;font-weight:bold">' + value.code_supership + ' - <span style="font-weight:400;font-size:10px;">' + value.status + '</span></span>';
                            html += '           </p>';
                            html += '       </div>';
                            html += '       <div class="row-3 border-row" style="color:red">';
                            if (value.hd_fee != null) {
                                html += '           KL:' + value.mass + ' $Thu hộ:' + formatNumber(value.collect) + ' $Phí:' + formatNumber(value.hd_fee);
                            } else
                                html += '           KL:' + value.mass + ' $Thu hộ:' + formatNumber(value.collect) + ' $Phí:' + formatNumber(value.hd_fee_stam);

                            html += '       </div>';
                            html += '       <div class="row-3 border-row">';
                            html += value.receiver + ' - ' + value.phone + ' - ' + value.city + ' - ' + value.district;
                            html += '       </div>';
                            html += '   </div>';
                            html += '   <div class="clear-fix"></div>';
                            html += '</li>';
                        });

                        $("div.init-data-mobile > ul.scroll-list-tab7").html(html);

                    } else {
                        $.each(list_order, function (index, value) {
                            i++;
                            html += '<tr onclick="fnDetail(\'' + value.code_supership + '\',' + value.id + ',\'' + value.DVVC + '\',0)" style="cursor: pointer">';
                            html += '   <td>' + i + '</td>';
                            html += '   <td>' + value.date_create.split(" ")[0].split('-')[2] + '/' + value.date_create.split(" ")[0].split('-')[1] + '/' + value.date_create.split(" ")[0].split('-')[0] + '</td>';
                            html += '   <td>' + value.required_code + '</td>';
                            html += '   <td>' + value.code_supership + '</td>';
                            html += '   <td>' + value.status + '</td>';
                            html += '   <td>' + formatNumber(value.collect) + '</td>';
                            if (value.hd_fee == null)
                                html += '   <td>' + formatNumber(value.hd_fee_stam) + '</td>';
                            else
                                html += '   <td>' + formatNumber(value.hd_fee) + '</td>';
                            html += '   <td>' + formatNumber(value.mass) + '</td>';
                            html += '   <td>' + value.receiver + ' - ' + value.phone + ' - ' + value.city + ' - ' + value.district + '</td>';
                            html += '</tr>';
                        });
                        if (result.total > 100) {
                            $("#boxpage").show();
                            if (pages > 0) {
                                pages++;
                                var totalpage = Math.ceil(result.total / 100);
                                if (pages > totalpage)
                                    $("#boxpage").hide();
                                $("#boxpage td").attr('onclick', 'initOrderManager(' + pages + ')');
                                $("#number-page").val(pages);
                            }
                        }
                        $("#listBoxOrderManager").html(html);
                    }
                }
            }
        });
    }

    function exportExcel() {
        var date_start_customer_order = $("#date_start_order").val();
        var date_end_customer_order = $("#date_end_order").val();
        var status = $("#status-tab2-pc").find(":selected").val();
        window.location.href = '<?= base_url('app/export_exel?datestart=')?>' + date_start_customer_order + '&datend=' + date_end_customer_order + '&status=' + status;
    }

    function exportExcelMobile() {
        var date_start_customer_order = $("#date_start_customer_order_tab2").val();
        var date_end_customer_order = $("#date_end_customer_order_tab2").val();
        var status = $("#status-tab2").find(":selected").val();
        var limit = $("#limitpageTab2").find(":selected").val();

        window.location.href = '<?= base_url('app/export_exel?datestart=')?>' + date_start_customer_order + '&datend=' + date_end_customer_order + '&status=' + status + '&limit=' + limit;
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="https://momentjs.com/downloads/moment-with-locales.min.js"></script>

<script>
    jQuery(function ($) {
        $.datepicker.regional["vi-VN"] =
            {
                closeText: "Đóng",
                prevText: "Trước",
                nextText: "Sau",
                currentText: "Hôm nay",
                monthNames: ["Tháng một", "Tháng hai", "Tháng ba", "Tháng tư", "Tháng năm", "Tháng sáu", "Tháng bảy", "Tháng tám", "Tháng chín", "Tháng mười", "Tháng mười một", "Tháng mười hai"],
                monthNamesShort: ["Một", "Hai", "Ba", "Bốn", "Năm", "Sáu", "Bảy", "Tám", "Chín", "Mười", "Mười một", "Mười hai"],
                dayNames: ["Chủ nhật", "Thứ hai", "Thứ ba", "Thứ tư", "Thứ năm", "Thứ sáu", "Thứ bảy"],
                dayNamesShort: ["CN", "Hai", "Ba", "Tư", "Năm", "Sáu", "Bảy"],
                dayNamesMin: ["CN", "T2", "T3", "T4", "T5", "T6", "T7"],
                weekHeader: "Tuần",
                dateFormat: "dd/mm/yy",
                firstDay: 1,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: ""
            };

        $.datepicker.setDefaults($.datepicker.regional["vi-VN"]);
    });
</script>

<script>

    $(document).ready(function () {
        var list_status = JSON.parse($("#data-list-status").val());
        var date_from = $("#data-date-from").val();

        var date_to = $("#data-date-to").val();
        $("#order-from-date").val(date_from)
        $("#order-to-date").val(date_to)


        var city = JSON.parse($("#data-city").val());
        var regions = JSON.parse($("#data-regions").val());

        let html_list_status = "<option><option>"
        html_list_status += list_status.map(function (value, index) {
            return `<option value="${value}">${value}</option>`
        }).join('');
        $("#kh-status").html(html_list_status)

        let html_city = "<option><option>"
        html_city += city.map(function (value, index) {
            return `<option value="${value.city}">${value.city}</option>`
        }).join('');
        $("#city").html(html_city)

        let html_regions ="<option><option>"
        html_regions += regions.map(function (value,index) {
            return `<option value="${value.name_region}">${value.name_region}</option>`
        }).join('');
        $("#region").html(html_regions)




        $("#city").select2({
            placeholder: "Vui Lòng Chọn Tỉnh",
            allowClear: true
        });
        $("#kh-district").select2({
            placeholder: "Vui Lòng Chọn Huyện",
            allowClear: true
        });
        $("#region").select2({
            placeholder: "Vui Lòng Chọn Vùng Miền",
            allowClear: true
        });
        $("#kh-status").select2({
            placeholder: "Vui Lòng Chọn Tình Trạng",
            allowClear: true
        });
        $("#is_hd_branch").select2({
            placeholder: "Vui Lòng Chọn Chi Nhánh",
            allowClear: true
        });

        let link = getLink();
        loadDatatables(link)
    });

    function emptyDate() {
        $(".datetimepicker-date").each(function () {
            realDate = new Date("");
            $(this).datepicker('setDate', '');
        });
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

    let loadDatatables = (link) => {
        var table = $('#kh-order').DataTable({
            "ajax": link,
            "columnDefs": [
                {
                    "width": "5%",
                    "targets": 0,
                    "data": null,
                    "render": function (data, type, row, meta) {
                        return row.date_create;
                    }
                },
                {
                    "width": "20%",
                    "targets": 1,
                    "data": null,
                    "render": function (data, type, row, meta) {
                        let backgroundStatus = getColorStatus(row.status)

                        let mkh = "";
                        if (row.code_orders != null && row.code_orders != "") {
                            mkh = `<p>Mã Đơn KH : <span style="color:green">${row.code_orders}</span></p>`
                        }
                        let requestCode = "";
                        if (row.required_code != null && row.required_code != "") {
                            requestCode = `<p>Mã Yêu Cầu : <span style="color:#6a7dfe">${row.required_code}</span></p></p>`
                        }

                        return `
                                <div style="width: 100%" class="mb-5"><label class="label label-orange label-xs tooltips" style="color:white;background-color:${backgroundStatus}" >&emsp;&emsp;${row.status}  &emsp;&emsp;</label></div>
                                <p style="color:red"> ${row.code_supership} </p>
                                ${requestCode}

                                ${mkh}
                                <p>Ngày tạo : ${moment(row.date_create).format('DD-MM-YYYY HH:mm:SS')}</p>`;
                    }
                },
                {
                    "width": "20%",
                    "targets": 2,
                    "data": null,
                    "render": function (data, type, row, meta) {
                        let phi = `<p>Phí DV: <span style="color:red">${formatCurrency(row.hd_fee)}</span></p>`;
                        if (row.hd_fee == null) {
                            phi = `<p>Phí DV: <span style="color:red">${formatCurrency(row.hd_fee_stam)}</span></p>`
                        }
                        if (row.is_hd_branch == 0) {
                            phi = `<p>Phí DV: <span style="color:red">${formatCurrency(row.pay_transport)}</span></p>`
                        }
                        let date = ``;

                        if (row.date_debits != null && row.date_debits != "" && row.date_debits != "0000-00-00 00:00:00") {
                            date = `<p style="color:#6a7dfe">NTN:${moment(row.date_debits).format('DD-MM-YYYY')}</p>`
                        }
                        return `
                                <p>SP: ${row.product}</p>
                                <p>Khối lượng: <span style="color:red">${row.mass}</span></p>
                                <p>Thu Hộ: <span style="color:red">${formatCurrency(row.collect)}</span></p>
                                ${phi}
                                ${date}`;
                    }
                },
                {
                    "width": "20%",
                    "targets": 3,
                    "data": null,
                    "render": function (data, type, row, meta) {
                        let address = `${(row.address) ? row.address + ", " : ""} ${(row.ward) ? row.ward + ", " : ""} ${(row.district) ? row.district + ", " : ""}  ${(row.city) ? row.city : ""} `;


                        return `
                                <div style="width: 100%" class="mb-5"><label class="label label-orange label-xs tooltips" data-original-title="Được tạo bằng API">&emsp;&emsp;${row.city}&emsp;&emsp;</label>&emsp;</div>
                                <p>${row.receiver}</p>
                                <p style="color:red">${row.phone}</p>
                                <p>${address}</p>`;
                    }
                },
                {
                    "width": "25%",
                    "targets": 4,
                    "data": null,
                    "render": function (data, type, row, meta) {
                        return `<div style="width: 100%;table-layout: fixed;"><div class="mb-15" style="display:flex">
                                <button onclick="fnDetail('${row.code_supership}','${row.id}','${row.DVVC}',0)" style="color: white" class="btn btn-sm btn-primary button-blue mr-2" target="_blank" ><i style="padding-right: 5px;" class="fa fa-eye"></i>Hành Trình</button>
<!--                                <button class="btn btn-sm btn-primary button-blue mr-2 btn${row.id}" onclick="modalUpdate(this)" data-id="${row.id}" data-note="${row.note_delay}"><i style="padding-right: 5px;" class="fa fa-comment"></i>Ghi chú</button>-->
                                <a style="color: white" class="btn btn-sm btn-primary button-blue" target="_blank" ><i style="padding-right: 5px;" class="fa fa-comment"></i>Gửi Yêu Cầu</a>
                                </div>
                                <p style="color:#557f38"><strong>Ghi Chú Giao Hàng:</strong></p>
                                <p>${(row.note) ? row.note : ""}</p>
                                </div>`;
                    }
                }

            ],
            "drawCallback": function (settings) {
                $("#kh-order thead").remove();
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
            table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                cell.innerHTML = `<div style="text-align: center">${i + 1}</div>`;
            });

        }).draw();
        $('.kh-tab7 table').removeClass('dataTable')
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
                case "Huỷ":
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

    function getDistrict(_this) {
        let city = $(_this).val();

        $.ajax({
            url: "/khachhang/api/order/district?city=" + city, success: function (result) {
                let data = JSON.parse(result);
                if (data.data.length > 0) {
                    let html = "<option></option>";
                    html += data.data.map(function (value) {
                        return `<option value="${value.district}">${value.district}</option>`
                    }).join('');
                    $("#kh-district").html(html)
                }
            }
        });
    }

    function exportExcel() {
        let link = getLink(true);
        window.location.href = link
    }

    function clickSearch() {
        let link = getLink();
        $('#kh-order').dataTable().fnDestroy();
        loadDatatables(link)
    }

    function convertDate(userDate) {
        str = userDate.split("/");
        return str[1] + "/" + str[0] + "/" + str[2]
    }

    function getLink(checkExcel = false) {
        let date_form = $("#order-from-date").val();
        let date_to = $("#order-to-date").val();
        let customer = $("#customer_shop_code").val();
        let status = $("#kh-status").val();
        let code_order = $("#code_order_tab7").val();
        let code_request = $("#code_request_tab7").val();
        let city = $("#city").val();
        let district = $("#kh-district").val();
        let region = $("#region").val();
        let is_hd_branch = $("#is_hd_branch").val();
        let dvvc = $("#dvvc").val();
        let data = {
            date_form: (date_form) ? moment(new Date(convertDate(date_form))).format('YYYY/MM/DD') : "",
            date_to: (date_to) ? moment(new Date(convertDate(date_to))).format('YYYY/MM/DD') : "",
            customer: customer,
            status: status,
            code_order: code_order,
            code_request: code_request,
            city: city,
            district: district,
            region: region,
            is_hd_branch: is_hd_branch,
            dvvc: dvvc
        };
        let linkApi = '/khachhang/api/order?jsonData=' + JSON.stringify(data);
        if (checkExcel) {
            linkApi = '/khachhang/api/order/export_excel_kh?jsonData=' + JSON.stringify(data)
        }
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
            noteNew += moment(new Date()).format('HH:mm DD/MM') + " " + noteNew;
        }
        let text = noteOld + noteNew;
        note = JSON.stringify(text);
        let id = document.getElementById("shop_id").value;

        $.ajax({
            url: `/api/order/update?note=${note}&id=${id}`, success: function (result) {
                if (result == true) {
                    $("#modal-update").modal('hide');
                    toastr.success('Ghi Chú Nội Bộ!', 'Cập Nhật Thành Công');
                    $(`.span${id}`).html(text);
                    $(`.btn${id}`).attr('data-note', text)
                }
            }
        });
    }

</script>