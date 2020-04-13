<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">


<?php if ($isAppMobile): ?>
  <div class="col-md-12">
    <a style="margin-bottom:10px;font-size: 10px;padding: 5px;" href="javascript:;" class="open-modal-addnew-create btn btn-info pull-left display-block"><?php echo _l('Tạo Điểm Lấy Hàng'); ?></a>
  </div>
  <ul class="scroll-list-tab3">

    <li>
        <p class="stt-left">
          Chưa lấy
        </p>
          <div class="left-width">
            <div class="row-1 border-row">
              <p class="">
                <span style="color:red;font-weight:bold">
                  14/09
                </span>
                <span style="color:#000;font-weight:bold">
                  DLKS782025LM.1368629
                </span>
              </p>
              <p class="">
                ghi chu
              </p>
            </div>



            <div class="row-3 border-row">
              <a style="margin-right:5px;" href="javascript:;"><i class='fa fa-pencil' ></i></a>
              <a style="color:#a73a3a" href="javascript:;"><i class='fa fa-trash' ></i></a>
            </div>

          </div>

          <div class="clear-fix"></div>
        </li>
  </ul>

  <?php else: ?>
    <div class="col-md-12">
      <a style="margin-right:10px;" href="javascript:;" class="open-modal-addnew-create btn btn-info pull-left display-block"><?php echo _l('Tạo Điểm Lấy Hàng'); ?></a>
    </div>
    <div class="col-md-12">

      <ul class="nav nav-tabs tab-show-data">
        <li data-tab="tab1_pick" class="active"><a href="javascript:;">Chưa Lấy</a></li>
        <li data-tab="tab2_pick"><a href="javascript:;">Đã lấy</a></li>
      </ul>
      <div class="tab-cover">
        <div class="tab1_pick tab-table">

            <table id="table_customer_pickup" class="table table table-striped table-debts_customer_detail">
                <thead>


                <tr>

                  <th>Ngày Tạo</th>
                  <th>a</th>
                  <th>customer_id</th>
                  <th>Tên Shop</th>
                  <th>SĐT Shop</th>
                  <th>Kho</th>
                  <th>Ghi Chú</th>
                  <th>Trạng Thái</th>
                  <th>a</th>
                  <th>a</th>
                  <th>a</th>
                  <th>a</th>
                  <th>a</th>
                  <th>a</th>
                  <th>a</th>
                  <th>Người Đăng Kí Lấy</th>
                  <th>Cài Đặt</th>

                </tr>
                </thead>
                <tbody></tbody>

            </table>

        </div>

        <div class="tab2_pick tab-table" style="display:none">
          <?php if ($isAppMobile == false): ?>
            <table id="table_customer_picked" class="table table table-striped table-debts_customer_detail">
                <thead>


                <tr>

                  <th>Ngày Tạo</th>
                  <th>ID</th>
                  <th>customer_id</th>
                  <th>Tên Shop</th>
                  <th>SĐT Shop</th>
                  <th>Kho</th>
                  <th>Ghi Chú</th>
                  <th>Trạng Thái</th>
                  <th>Người Đăng Kí Lấy</th>
                  <th>Người Đăng Kí Lấy</th>
                  <th>Người Đăng Kí Lấy</th>
                  <th>Người Đăng Kí Lấy</th>
                  <th>Người Đăng Kí Lấy</th>
                  <th>Người Đăng Kí Lấy</th>
                  <th>Người Đăng Kí Lấy</th>
                  <th>Người Lấy</th>
                  <th>Số Đơn Lấy</th>

                </tr>
                </thead>
                <tbody></tbody>

            </table>
          <?php else: ?>





          <?php endif; ?>
        </div>
      </div>


    </div>
<?php endif; ?>



</div>









<style media="screen">
	#loader-repo{
		display: none;
	}
	.disable-view {
		display: none;
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		z-index: 99999999999999;
	}
	.lds-ellipsis {
		display: block;
		position: relative;
		width: 64px;
		height: 64px;
		margin: 0 auto;
	}
	.lds-ellipsis div {
		position: absolute;
		top: 27px;
		width: 11px;
		height: 11px;
		border-radius: 50%;
		background: #03a9f4;
		animation-timing-function: cubic-bezier(0, 1, 1, 0);
	}
	.lds-ellipsis div:nth-child(1) {
		left: 6px;
		animation: lds-ellipsis1 0.6s infinite;
	}
	.lds-ellipsis div:nth-child(2) {
		left: 6px;
		animation: lds-ellipsis2 0.6s infinite;
	}
	.lds-ellipsis div:nth-child(3) {
		left: 26px;
		animation: lds-ellipsis2 0.6s infinite;
	}
	.lds-ellipsis div:nth-child(4) {
		left: 45px;
		animation: lds-ellipsis3 0.6s infinite;
	}
	@keyframes lds-ellipsis1 {
		0% {
			transform: scale(0);
		}
		100% {
			transform: scale(1);
		}
	}
	@keyframes lds-ellipsis3 {
		0% {
			transform: scale(1);
		}
		100% {
			transform: scale(0);
		}
	}
	@keyframes lds-ellipsis2 {
		0% {
			transform: translate(0, 0);
		}
		100% {
			transform: translate(19px, 0);
		}
	}


  .col-md-4.three-inline {
    padding: 3px;
  }
  .col-md-4.three-inline:nth-child(1) {
    padding-left: 0;
  }
  .col-md-4.three-inline:nth-child(3) {
    padding-right: 0;
  }
  textarea.txt-area.form-control {
    height: 150px;
    resize: none;
  }
  .search-icon{
		position: absolute;
    top: 33px;
    right: 12px;
    font-size: 18px;
	}
	.search-item {
		display: none;
		position: absolute;
    width: 100%;
    top: 64px;
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

	.label-border {
		padding: 5px;
		border:1px solid #ddd;
	}
	.label-border span {
		margin-right: 5px;
	}
	#tab3 table.dataTable thead>tr>th:nth-child(4) {
		width: 30%;
	}
	.tab-show-data {
		border-bottom: none;
	}

</style>
</body>
</html>
