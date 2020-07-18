<style>
    .scroll-list-tab2 {

    }
    input ,
    input::-webkit-input-placeholder {
        font-size: 12px !important;
    }
    #overlay{
        position: fixed;
        top: 0;
        z-index: 100;
        width: 100%;
        height:100%;
        display: none;
        background: rgba(0,0,0,0.6);
    }
    .cv-spinner {
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .spinner {
        width: 40px;
        height: 40px;
        border: 4px #ddd solid;
        border-top: 4px #2e93e6 solid;
        border-radius: 50%;
        animation: sp-anime 0.8s infinite linear;
    }
    @keyframes sp-anime {
        100% {
            transform: rotate(360deg);
        }
    }
    .is-hide{
        display:none;
    }

</style>

<div class="layout-mobile">
    <div class="row">
        <div class="col-md-2 col-xs-6">
            <?php echo render_date_input('date_start_customer_order_tab2', 'Ngày bắt đầu', $date_start); ?>
        </div>
        <div class="col-md-2 col-xs-6">
            <?php echo render_date_input('date_end_customer_order_tab2', 'Ngày kết thúc', $date_end); ?>
        </div>



    </div>
    <div class="row">
        <div class="col-md-6 col-xs-6">
            <div class="form-group" app-field-wrapper="date_start_customer_order_tab2">
                <label for="date_start_customer_order_tab2" class="control-label">Nhập Mã Tìm Kiếm</label>
                <input type="text" class="form-control font" placeholder="Nhập SĐT Người Nhận, Mã Yêu Cầu,Mã Đơn Hàng hoặc Mã Đơn Shop" id="code_order_tab2">

            </div>
        </div>
    </div>
    <div class="row">

        <div class="col-md-6 col-xs-6">
            <div class="form-group">
                <label for="province">Trạng thái</label>
                <select data-live-search="true" class="form-control selectpicker" multiple id="status-tab2"
                        name="status-tab2"></select>
            </div>


            <div class="col-md-12">
                <div id="loader-repo4" class="lds-ellipsis" style="display:none;">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xs-12" style="margin-bottom: 2%">
            <button class="btn btn-info mtop25" type="button" onclick="fnFilter_listTab2(<?= (!empty($isAppMobile)) ? $isAppMobile : 0 ?>)">Lọc danh sách</button>
<!--            <button class="btn btn-success mtop25" type="button" onclick="exportExcelMobile()">Xuất ra excel</button>-->
        </div>
        <div class="clearfix"></div>
    </div>

</div>
<div id="overlay">
    <div class="cv-spinner">
        <span class="spinner"></span>
    </div>
</div>
<div id="debts_customer" class="">

    <div class="">
        <div class="init-data-mobile">


            <ul class="scroll-list-tab2">
                <li>
                    <p class="stt-left">
                        1
                    </p>
                    <div class="left-width">

                        <div class="row-1 border-row">
                            <p class="left-row">
                                <span style="color:red;font-weight:bold">07/09</span>
                                <span style="color:#000;font-weight:bold">HDGS209724NT.1280296</span>
                            </p>

                        </div>


                        <div class="row-3 border-row" style="color:red">
                            Nội dung cái khối lượng ở đây
                        </div>

                        <div class="row-3 border-row">
                            Nội dung cái Node ở đây
                        </div>

                    </div>

                    <div class="clear-fix"></div>
                </li>
            </ul>

            <div class="footer-app"></div>
        </div>

    </div>

</div>
