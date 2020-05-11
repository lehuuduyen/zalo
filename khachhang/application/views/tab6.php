<style>
    .scroll-list-tab2 {

    }
</style>

<div id="debts_customer" class="">

    <div class="boxTab6">

        <div class="col-md-12 col-xs-12">
            <div class="form-group" app-field-wrapper="date_end_customer">
                <label for="date_end_customer" class="control-label">Tìm kiếm</label>
                <div class="input-group date">
                    <input type="text" id="search" device="<?= (!empty($isAppMobile)) ? $isAppMobile : 0 ?>" onkeyup="searchTab6(event)" name="search" class="form-control" value=""
                           placeholder="Nhập số điện thoại hoặc mã đơn hàng" autocomplete="off">
                    <div class="input-group-addon" onclick="fnSearch(<?= (!empty($isAppMobile)) ? $isAppMobile : 0 ?>)">
                        <i class="fa fa-search"></i>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($isAppMobile)) { ?>
            <div class="init-data-mobile">

                <ul class="scroll-list-tab2" id="boxSearch">
                </ul>

                <div class="footer-app"></div>
            </div>
        <?php } else { ?>
            <table class="table table table-striped table-debts_customer_detail dataTable no-footer">
                <thead>
                <tr>
                    <th style="width:10%;">Ngày Tạo</th>
                    <th style="width:15%;">MÃ</th>
                    <th style="width:15%;">Trạng Thái</th>
                    <th style="width:50%;">Nội Dung</th>
                </tr>
                </thead>
                <tbody id="listBoxSearch"></tbody>

            </table>
        <?php } ?>
    </div>

</div>
