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

                    if(info_order.hd_fee == null)
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
        var limit_geted = $("#limitpageTab2").find(":selected").val();

        $.ajax({
            url: '<?= base_url('app/filter_list')?>',
            data: {
                province: province,
                date_start_customer_order: date_start_customer_order,
                date_end_customer_order: date_end_customer_order,
                limit_geted: limit_geted
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
        if(pages > 1)
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
                            if(pages > 0){
                                pages++;
                                var totalpage = Math.ceil(result.total/100);
                                if(pages > totalpage)
                                    $("#boxpage").hide();
                                $("#boxpage td").attr('onclick', 'initOrderManager('+ pages +')');
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
