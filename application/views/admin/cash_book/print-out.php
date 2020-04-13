<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="/system/assets/css/cash_book.css">


    <style media="screen">
      .list-detail p ,{
        font-size: 18px;
      }
      .list-detail pre {
        font-size: 16px;
      }
    </style>
    <style type="text/css" media="print">
    @page
    {
      size: A4;
      margin: 0;
      margin: 20px;
      margin-left: 30px;
      margin-right: 30px;
      margin-bottom: 0;

    }
</style>

  </head>
  <?php
    $this->load->helper('number_vnd_string');
  ?>
  <body>
    <div class="head-print">
      <div class="header-he">
        <p class="cover-image">
          <img src="/system/assets/images/super-ship-logo.png" alt="super-ship-logo">
        </p>
        <div class="text-info">
          <span class="red">CÔNG TY TNHH SUPER SHIP HẢI DƯƠNG</span>
          <span class="black">ĐỊA CHỈ 126A Nguyễn Lương Bằng - P.Phạm Ngũ Lão - Tp.Hải Dương</span>
          <span class="black">SĐT:02203.891.888. Hotline:0854.854.999. Email:haiduong@supership.vn</span>
        </div>
      </div>
      <div class="title">
        <h1 class="type"><?php echo ($receipts->type === '0') ? 'PHIẾU THU' : "PHIẾU CHI" ?></h1>
          <?php $date_receipts = to_sql_date($receipts->date);?>
        <span class="date">Ngày <?php echo $day = date("d", strtotime($date_receipts)); ?> Tháng <?php echo $month = date("m", strtotime($date_receipts)); ?> Năm <?php echo $y = date("Y", strtotime($date_receipts)); ?></span>
      </div>




      <div class="list-detail">

        <?php if(!empty($receipts->date_control)) { ?>
            <p>Ngày đối soát: <?php echo _dC($receipts->date_control) ?></p>
        <?php } ?>
        <p>Mã Phiếu: <?php echo $receipts->code ?></p>
        <p>Đối tượng <?php echo ($receipts->type == '0') ? 'nhận' : "nộp" ?> tiền: <b><?php echo $_GET['object'] ?></b> </p>
        <p>Nhóm quỹ: <?php echo $receipts->group_name ?></p>
        <p>Tài Khoản: <?php echo $receipts->pay_name ?></p>
        <p>Nội dung: <pre><?php echo $receipts->note ?></pre> </p>
        <p>Số Tiền: <b><?php echo number_format($receipts->price) ?> VND</b> </p>
        <p>Số Tiền Viết Bằng chữ: <?php echo convert_number_to_words($receipts->price) ?> </p>
        <p>Kèm theo: .......... Chứng từ gốc</p>
      </div>

      <ul class="asign">
        <li>
          <span class="cv">Giám Đốc</span>
          <span>( kí,họ tên,đóng dấu )</span>
        </li>
        <li>
          <span class="cv">kế Toán Trưởng</span>
          <span>( kí,họ tên )</span>
        </li>
        <li>
          <span class="cv">Thủ Quỹ</span>
          <span>( kí,họ tên )</span>
        </li>
        <li>
          <span class="cv">Người Lập Phiêú</span>
          <span>( kí,họ tên )</span>
        </li>
        <li>
          <span class="cv"> <?php echo ($receipts->type == '0') ? 'Người Nộp Tiền' : "Người Nhận Tiền" ?> </span>
          <span>( kí,họ tên )</span>
        </li>
      </ul>


      <?php if ($_GET['combo'] === '2'): ?>
      <p style="border:1px dotted #ddd;margin-top:120px;margin-bottom:80px;" ></p>


      <div class="header-he">
        <p class="cover-image">
          <img src="/system/assets/images/super-ship-logo.png" alt="super-ship-logo">
        </p>
        <div class="text-info">
          <span class="red">CÔNG TY TNHH SUPER SHIP HẢI DƯƠNG</span>
          <span class="black">ĐỊA CHỈ 126A Nguyễn Lương Bằng - P.Phạm Ngũ Lão - Tp.Hải Dương</span>
          <span class="black">SĐT:02203.891.888. Hotline:0854.854.999. Email:haiduong@supership.vn</span>
        </div>
      </div>
      <div class="title">
        <h1 class="type"><?php echo ($receipts->type === '0') ? 'PHIẾU THU' : "PHIẾU CHI" ?></h1>
        <span class="date">Ngày <?php echo $day = date("d", strtotime($date_receipts)); ?> Tháng <?php echo $month = date("m", strtotime($date_receipts)); ?> Năm <?php echo $y = date("Y", strtotime($date_receipts)); ?></span>
      </div>




      <div class="list-detail">
          <?php if(!empty($receipts->date_control)) { ?>
              <p>Ngày đối soát: <?php echo _dC($receipts->date_control) ?></p>
          <?php } ?>
        <p>Mã Phiếu: <?php echo $receipts->code ?></p>
        <p>Đối tượng <?php echo ($receipts->type == '0') ? 'nhận' : "nộp" ?> tiền: <b><?php echo $_GET['object'] ?></b></p>
        <p>Nhóm quỹ: <?php echo $receipts->group_name ?></p>
        <p>Tài Khoản: <?php echo $receipts->pay_name ?></p>
        <p>Nội dung: <pre><?php echo $receipts->note ?></pre> </p>
        <p>Số Tiền: <?php echo number_format($receipts->price) ?> VND</p>
        <p>Số Tiền Viết Bằng chữ: <?php echo convert_number_to_words($receipts->price) ?> </p>
        <p>Kèm theo: .......... Chứng từ gốc</p>
      </div>

      <ul class="asign">
        <li>
          <span class="cv">Giám Đốc</span>
          <span>( kí,họ tên,đóng dấu )</span>
        </li>
        <li>
          <span class="cv">kế Toán Trưởng</span>
          <span>( kí,họ tên )</span>
        </li>
        <li>
          <span class="cv">Thủ Quỹ</span>
          <span>( kí,họ tên )</span>
        </li>
        <li>
          <span class="cv">Người Lập Phiêú</span>
          <span>( kí,họ tên )</span>
        </li>
        <li>
          <span class="cv"> <?php echo ($receipts->type == '0') ? 'Người Nộp Tiền' : "Người Nhận Tiền" ?> </span>
          <span>( kí,họ tên )</span>
        </li>
      </ul>

      <?php endif; ?>

    </div>




    <script>
      // self executing function here
      (function() {
        document.title = "";
        var print = <?php echo $print ? 'true' : 'false'?>;
        if (print === true) {
          window.print()
        }

      })();
</script>

  </body>

</html>

<?php //var_dump($receipts); ?>
