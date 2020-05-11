<script>

    function InitDataMobileTab2() {

        $('.loading-page').show();
        var d = {};
        d.date_start_customer = $('#date_start_customer_order_tab2').val();
        d.date_end_customer = $('#date_end_customer_order_tab2').val();
        d.customer_shop_code = $('#customer_shop_code').val();
        d.id_customer = $('#id_customer').val();
        d.limit = $('#limitpageTab2').find(":selected").val();
        d.status = $("#status-tab2").find(":selected").val();

        $.ajax({
            type: 'POST',
            url: "<?php echo base_url('/app/datatables_ajax_detail_tab2');?>",
            data: d,
            dataType: "text",
            success: function (resultData) {
                $('.loading-page').hide();
                resultData = JSON.parse(resultData);
                var datalist = resultData.aaData;
                var html = '';
                var ind = 0;
                for (var i = 0; i < datalist.length; i++) {
                    required_code='';
                    if(datalist[i][20] !=""&& datalist[i][20] != null && datalist[i][20] != 'UNDEFINED'){
                        required_code = ` <div class="righ-row">
                                          ${datalist[i][20]}
                                        </div>`
                    }
                    ind++;
                    html += '<li onclick="fnDetail(\'' + datalist[i][4] + '\',' + datalist[i][0] + ',\'' + datalist[i][16] + '\',1)">';
                    html += '   <p class="stt-left">';
                    html += ind;
                    html += '   </p>';
                    html += '   <div class="left-width">';
                    html += '       <div class="row-1 border-row">';
                    html += '           <p class="left-row">';
                    html += '               <span style="color:red;font-weight:bold">';
                    html +=                     InitDate(datalist[i]);
                    html += '               </span>';
                    html += '               <span style="color:#000;font-weight:bold">';
                    html +=                     datalist[i][4] +'- <span style="font-weight:400;font-size:10px;">'+datalist[i][5] +'</span>';
                    html += '               </span>';
                    html += '           </p>';
                    html += '       </div>';
                    html += required_code;

                    html += ContentKL(datalist[i][17]);
                    html += '<div class="row-3 border-row">';
                    html += datalist[i][9];
                    html += '</div>';
                    html += '<div class="row-3 border-row">';
                    html += datalist[i][19];
                    html += '</div>';
                    html += `<div class="row-3 border-row"> <i class='fa fa-eye' ></i>  <a href="#">Hành trình</a> </div>`;

                    html += '</div><div class="clear-fix"></div></li>';
                }

                $('.scroll-list-tab2').empty();


                $('.scroll-list-tab2').append(html);
            }
        });
    }
</script>
