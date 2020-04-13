<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(false); ?>
<style media="screen">
  #header , #mobile-search{
    display: none;
  }
  #wrapper.shiper {
    padding: 0;
    margin: 0;
  }
  #wrapper.shiper .table>tbody>tr>td {
    padding: 1px 5px;
    font-size: 12px;
  }
  table {
    width: 750px;
    margin-top: 0;
    border-collapse: collapse;
    margin: auto;
    }

    /* Zebra striping */
    tr {
    background: #fff;
    }

    th {
    background: #3498db;
    color: white;
    font-weight: bold;
    }

    td, th {
    padding: 10px;
    border: 1px solid #ccc;
    text-align: left;
    font-size: 18px;
    }

    /*
    Max width before this PARTICULAR table gets nasty
    This query will take effect for any screen smaller than 760px
    and also iPads specifically.
    */
    @media
    only screen and (max-width: 760px),
    (min-device-width: 768px) and (max-device-width: 1024px)  {

    table {
        width: 100%;
    }

    /* Force table to not be like tables anymore */
    table, thead, tbody, th, td, tr {
      display: block;
    }

    /* Hide table headers (but not display: none;, for accessibility) */
    thead tr {
      position: absolute;
      top: -9999px;
      left: -9999px;
    }

    tr { border: 1px solid #ccc; }

    td {
      /* Behave  like a "row" */
      border: none;
      border-bottom: 1px solid #eee;
      position: relative;
      padding-left: 50%;
      font-size: 14px;
    }

    td:before {

      display: none;
      padding-right: 10px;
      white-space: nowrap;
      float: left;
      content: attr(data-column);
      font-size: 15px;
      color: #000;
      font-weight: bold;
    }

    }
    .row {
      margin: 0;
      padding: 10px;
    }
    .cover-table {
      display: block;
      overflow: auto;
      padding: 0;
      padding-top:34px;
    }
    .mobile .btn.btn-custom {
      font-size: 10px;
    padding: 3px 7px;
    display: block;
    float: left;
    width: 30%;
    }
    .mobile .btn.btn-custom.right {
      float: right;

    }
    .mobile .btn.btn-custom.center {
      margin-left: 5%;
    }
    .bold-shop {
      font-weight: bold;
      font-size: 14px;
    }

.clear-fix::before {
  content: "";
  clear: both;
  display: table;
}
.shiper-table tr{
  padding-top: 7px;
  padding-bottom: 7px;
}
.shiper-table tr td:nth-child(1) {
  border-top: none;
}
.shiper-table tr td:last-child{
  border-bottom: none;
}
.btn-primary.left{
  background: #a73b3b;
  font-weight: bold;
}
.footer-nav {
  display: flex;
  justify-content: space-around;
  background: #fff;
  position: fixed;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 60px;
  align-items: center;
  border-top: 1px solid #ddd;
}
.footer-nav a {
  display: block;
  text-align: center;
}
.footer-nav a i {
  display: block;
  color: #ddd;
  font-size: 25px;
}
.footer-nav a span {
  color: #ddd;
  font-size: 15px;
}
.footer-nav a.active i , .footer-nav a.active span {
  color: #a73a3b;
}
table.table {
  margin: 0;
}
.table-data {
  display: none;
}
.table-data.active {
  display: block;
}
.header-table {
  position: fixed;
  top:0;
  left: 0;
  width: 100%;
  padding: 10px 0;
  background: #a73a3b;
  z-index: 55;
}
.header-table  h5 {
  margin: 0;
  color: #fff;
  text-align:center
}
.data-empty {
  display: block;
width: 90%;
padding: 20px;
text-align: center;
border: 3px dotted #aaa;
margin: 0 auto;
margin-top: 30px;
}
.disable-view {
  display: block;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 99999999999999;
  background: #fff;
}
.lds-ellipsis {
  display: block;
  position: absolute;
  width: 64px;
  height: 64px;
  margin: 0 auto;
  top: 50%;
  margin-top: -32px;
  margin-left: -32px;
  left: 50%;
}
.lds-ellipsis div {
  position: absolute;
  top: 27px;
  width: 11px;
  height: 11px;
  border-radius: 50%;
  background: #a73a3b;
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

#limit_geted {

  position: absolute;
  top:0;
  right: 10px;
  margin:0;
  border: 1px solid #fff;
  color: #fff;
  margin-top: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0 10px;
  display: none;
}
#limit_geted label {
  color: #fff;
  margin-bottom: 0;
}
#limit_geted select {
  outline: none;
background: none;
border: none;
}
</style>
<div id="wrapper" class="shiper">

  <div class="disable-view">
    <div id="loader-repo" class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
  </div>
  <div class="cover-table">
    <div class="header-table">
      <h5 style="text-align: left;padding-left:10px;">Danh Sách Đơn Hàng Chưa Lấy</h5>
      <div class="form-group" id="limit_geted">
        <label for="limit_geted">Hiển thị</label>
        <select name="limit_geted" >
          <option value="20">20</option>
          <option value="50">50</option>
          <option value="100">100</option>
          <option value="all">Tất cả</option>
        </select>
      </div>

    </div>



    <div class="table-data table2">

      <?php if (sizeof($regs) > 0): ?>
        <table class="table shiper-table">

          <tbody>
            <?php foreach ($regs as $key => $reg): ?>
              <tr>
                 <td>


                   <?php if (isset($reg->customer_shop_code)): ?>
                    <?php echo date('d/m/Y' , strtotime($reg->created))  ?> - <span class="bold-shop"><?php echo $reg->customer_shop_code ?></span> - <?php echo $reg->phone_customer; ?>
                   <?php endif; ?>

                   <?php if (!isset($reg->customer_shop_code)): ?>

                     <?php echo date('d/m/Y' , strtotime($reg->created))  ?> - <span class="bold-shop"><?php echo $reg->name_customer_new ?></span> - <?php echo $reg->phone_customer; ?>

                   <?php endif; ?>





                 </td>




                 <td style="padding-right: 35px;min-height: 40px;">

                   <span><b>Đ/C:</b> <span class="mycopy" style="font-weight:bold;color:blue;"><?php echo $reg->repo_customer ?></span> </span>
                   <a target="_blank" class="copy-address" style="position: absolute;top: 0;right: 10px;font-size: 26px;" href="<?php echo "https://www.google.com/maps/search/?api=1&query=".$reg->repo_customer ?>">

                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                   </a>
                 </td>

                 <td>
                   <b>Ghi Chú:</b>
                   <span style="color:#a73b3b;font-weight:bold"><?php echo $reg->note ?></span>
                 </td>
                 <td>

                   <a style="<?php echo ($reg->receive_or_pay == '1') ? "background: #FF9800;" : "" ?>" data-id="<?php echo $reg->id ?>" href="javascript:;" class="btn btn-custom btn-primary left confirm_get">
                     <?php echo ($reg->receive_or_pay == '1') ? "Xác nhận đã trả" : "Xác nhận đã lấy" ?>
                   </a>
                   <a href="javascript:;" data-reg-id='<?php echo $reg->id ?>' class="btn btn-custom btn-warning center confirmation_un_reg">Nhả Điểm</a>
                   <a class="btn btn-custom btn-primary right" href="tel:<?php echo $reg->phone_customer; ?>">Gọi Shop</a>
                   <div class="clear-fix">

                   </div>
                 </td>

              </tr>
            <?php endforeach; ?>

          </tbody>
        </table>
      <?php else : ?>
        <p class="data-empty">Chưa Có Danh Sách Đơn Hàng  Đăng Ký</p>
      <?php endif; ?>



    </div>


    <div class="table-data active table1">

      <?php if (sizeof($waits) > 0): ?>
        <table class="table shiper-table">

          <tbody>
            <?php foreach ($waits as $key => $wait): ?>
              <tr>
                 <td>


                   <?php if (isset($wait->customer_shop_code)): ?>
                    <?php echo date('d/m/Y' , strtotime($wait->created))  ?> - <span class="bold-shop"><?php echo $wait->customer_shop_code ?></span> - <?php echo $wait->phone_customer; ?>
                   <?php endif; ?>

                   <?php if (!isset($wait->customer_shop_code)): ?>

                     <?php echo date('d/m/Y' , strtotime($wait->created))  ?> - <span class="bold-shop"><?php echo $wait->name_customer_new ?></span> - <?php echo $wait->phone_customer; ?>

                   <?php endif; ?>

                 </td>

                 <td style="padding-right: 35px;min-height: 40px;">

                   <span><b>Đ/C:</b> <span class="mycopy" style="font-weight:bold;color:blue;"><?php echo $wait->repo_customer ?></span> </span>
                   <a target="_blank" class="copy-address" style="position: absolute;top: 0;right: 10px;font-size: 26px;" href="<?php echo "https://www.google.com/maps/search/?api=1&query=".$wait->repo_customer ?>">

                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                   </a>
                 </td>
                 <td>
                   <b>Ghi Chú:</b>
                   <span style="color:#a73b3b;font-weight:bold"><?php echo $wait->note ?></span>
                 </td>
                 <td>
                   <a style="<?php echo ($wait->receive_or_pay == '1') ? "background: #FF9800;" : "" ?>" data-id="<?php echo $wait->id ?>" href="javascript:;" class="btn btn-custom btn-primary left confirm_get">
                     <?php echo ($wait->receive_or_pay == '1') ? "Xác nhận đã trả" : "Xác nhận đã lấy" ?>
                   </a>
                   <a style="<?php echo ($wait->receive_or_pay == '1') ? "background:#009688" : "" ?>"  href="javascript:;" data-id="<?php echo $wait->id ?>" class="btn btn-custom btn-warning center reg_data_order confirmation">

                     <?php echo ($wait->receive_or_pay == '1') ? "Đăng ký trả" : "Đăng ký lấy" ?>
                   </a>
                   <a class="btn btn-custom btn-primary right" href="tel:<?php echo $wait->phone_customer; ?>">Gọi Shop</a>
                   <div class="clear-fix">

                   </div>
                 </td>

              </tr>
            <?php endforeach; ?>

          </tbody>
        </table>
      <?php else: ?>
        <p class="data-empty">Chưa Có Danh Sách Điểm Đơn Hàng </p>
      <?php endif; ?>


    </div>



    <div class="table-data table3">
      <table class="table shiper-table">
        <tbody>

        </tbody>
      </table>

    </div>


    <div class="table-data table4">



      <div class="row">
        <div class="panel_s">

          <div class="panel-body">
          <h4 class="no-margin">
            Tài Khoản
          </h4>
         <hr class="hr-panel-heading">

            <div class="clearfix"></div>
              <?php echo staff_profile_image($current_user->staffid,array('img','img-responsive','staff-profile-image-thumb'),'thumb'); ?>
            <div class="profile mtop20 display-inline-block">
              <h4>
                <?php echo $user_display->lastname.' ' .$user_display->firstname ?>
              </h4>
              <p class="display-block">
                <i class="fa fa-envelope"></i>
                 <a href="mailto:<?php echo $user_display->email; ?>"><?php echo $user_display->email; ?></a>

              </p>
              <p>
                <i class="fa fa-phone-square"></i>
                 <?php echo $user_display->phonenumber ?>
               </p>
              </div>
              <div class="button-footer">
                <a style="margin-right:10px;" class="btn btn-default" href="/system/admin/authentication/logout">
                 <i class="fa fa-sign-out" aria-hidden="true"></i>
                 <span>Đăng Xuất</span>
                </a>
                <a class="btn btn-primary change-password-modal" href="javascript:;">
                 <i class="fa fa-key" aria-hidden="true"></i>
                 <span>  Đổi Mật Khẩu</span>
                </a>
              </div>
         </div>
       </div>
      </div>


    </div>


  </div>
   <!-- Table -->

   <div class="footer-nav">

     <a class="active tab tab1" data-tab="1" href="javascript:;">
       <i class="fa fa-map-marker" aria-hidden="true"></i>
       <span>Điểm Chờ</span>
     </a>

     <a class=" tab tab2" data-tab="2" href="javascript:;">
       <i class="fa fa-user" aria-hidden="true"></i>
       <span> Đã Đăng Kí</span>
     </a>

     <a class=" tab tab3" data-tab="3" href="javascript:;">
       <i class="fa fa-archive" aria-hidden="true"></i>
       <span>Đã Lấy</span>
     </a>


     <a class=" tab tab4" data-tab="4" href="javascript:;">
       <i class="fa fa-cogs" aria-hidden="true"></i>
       <span>Tài Khoản</span>
     </a>
   </div>
</div>
<?php init_tail(); ?>


<div class="modal fade" id="modal-confirm-order" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open(admin_url('customer_policy/add_policy'),array('id' => 'confirm_shiper',"method"=>'post' )); ?>

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Nhập Số Đơn</h4>
        </div>
        <div class="modal-body">

            <div class="form-group">
              <label for="pwd">Nhập Số Đơn</label>
              <input required type="text" placeholder="Nhập Số Đơn" class="form-control" id="order_get" name="order_get" onkeyup="formatNumBerKeyUp(this)">
            </div>



        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
          <button type="submit" name="button" class="btn btn-primary">Xác Nhận</button>
        </div>
      </div>
    <?php echo form_close(); ?>

  </div>
</div>




<div class="modal fade" id="modal-edit-password" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open( '/shiper/password',array('id' => 'password_shiper',"method"=>'post' )); ?>

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Thay đổi mật khẩu</h4>
        </div>
        <div class="modal-body">

            <div class="form-group">
              <label for="pwd">Mật Khẩu Cũ</label>
              <input required type="password" placeholder="Mật Khẩu Cũ" class="form-control" id="oldpassword" name="oldpassword">
            </div>

            <div class="form-group">
              <label for="pwd">Mật Khẩu Mới</label>
              <input required type="password" placeholder="Mật Khẩu Mới" class="form-control" id="newpasswordr" name="newpasswordr">
            </div>

            <div class="form-group">
              <label for="pwd">Mật Khẩu Xác Nhận</label>
              <input required type="password" placeholder="Mật Khẩu Xác Nhận" class="form-control" id="new_confirm_pass" name="new_confirm_pass">
            </div>



        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
          <button type="submit" class="btn btn-primary">Xác Nhận</button>
        </div>
      </div>
    <?php echo form_close(); ?>

  </div>
</div>


<script>


setTimeout(function () {
  $('.disable-view').fadeOut();
}, 100);

var checkAlert = <?php echo isset($_SESSION['success']) ? 'true' : 'false'?>;
var success2 = <?php echo isset($_SESSION['success2']) ? 'true' : 'false'?>;
var success3 = <?php echo isset($_SESSION['success3']) ? 'true' : 'false'?>;
var errorReg = <?php echo isset($_SESSION['error3']) ? 'true' : 'false'?>;
var tab1 = false;
var tab1 = <?php echo $tab2 ? 'true' : 'false'?>;

var error_change_pass = <?php echo isset($_SESSION['error_change_pass']) ? 'true' : 'false'?>;
var sucess_change_pass = <?php echo isset($_SESSION['sucess_change_pass']) ? 'true' : 'false'?>;
var mess_pass = <?php echo isset($_SESSION['mess_pass']) ? "'".$_SESSION['mess_pass']."'" : 'false'?>;

$('#password_shiper').validate({
  errorClass: 'error text-danger',
  highlight: function(element) {
    $(element).parent().addClass("has-error");
  },
  unhighlight: function(element) {
    $(element).parent().removeClass("has-error");
  },
  onfocusout: false,
  invalidHandler: function(form, validator) {
      var errors = validator.numberOfInvalids();
      if (errors) {
          validator.errorList[0].element.focus();
      }
  },
  rules: {
    oldpassword:{
      required:true
    },
    newpasswordr:{
      required:true
    },
    new_confirm_pass: {
      equalTo: "#newpasswordr"
    }
  }
});




if (error_change_pass) {
  $('.header-table h5').text('Tài Khoản');
  $('#modal-edit-password').modal('show');
  setTimeout(function () {
    alert(mess_pass);
  }, 100);

}
if (sucess_change_pass) {
  alert_float('success','Thay đổi mật khẩu thành công');
}

if (tab1 === true) {
  $('.header-table h5').text('Danh Sách Đơn Hàng Chưa Lấy');
  $('.table-data').removeClass('active');
  $('.table1').addClass('active');
  $('.tab').removeClass('active');
  $('.tab[data-tab="1"]').addClass('active');

}

if (checkAlert) {

  var limit = $('#limit_geted select').val();
  $('.disable-view').fadeIn();

  $.ajax({
      url: `/system/shiper/geted_ajax?limit=${limit}`,
      success: function (data) {
        $('.disable-view').fadeOut();
        data = JSON.parse(data);
        if (data.length > 0) {
          $('.table-data.table3 tbody').empty();
          var html = '';

          for (var i = 0; i < data.length; i++) {

            html += `<tr><td>`;
              if (data[i].customer_shop_code) {
                html += `${date_format(data[i].modified)} - <span class="bold-shop">${data[i].display_name}</span> - ${data[i].phone_customer}`;
              }else {
                html += `${date_format(data[i].modified)} - <span class="bold-shop">${data[i].display_name}</span> - ${data[i].phone_customer}`;
              }


            html += `<td>
              <span><b>Đ/C:</b> <span style="font-weight:bold;color:blue;">${data[i].repo_customer}</span> </span>
            </td>
            <td>
              <b>Ghi Chú:</b>
              <span style="color:#a73b3b;font-weight:bold">${data[i].note}</span>

            </td>`;

            html += `<td>
             <b>Người lấy:</b>
             <span>
               ${data[i].lastname} ${data[i].firstname} - Số Đơn: ${data[i].number_order_get}
             </span>
            </td>`;


            html += `</tr>`;

          }
          $('.table-data.table3 tbody').append(html);
        }
        else {
          $('.table-data.table3 tbody').empty();
          var html = `<tr><td><p class="data-empty">Chưa Có Danh Sách Đã Lấy Hàng </p></td></tr>`;
          $('.table-data.table3 tbody').append(html);
        }

      }
  });


  alert_float('success','Xác Nhận Thành Công');
  $('.header-table h5').text('Danh sách đơn hàng đã xử lý');
  $('.table-data').removeClass('active');
  $('.table3').addClass('active');
  $('.tab').removeClass('active');
  $('.tab[data-tab="3"]').addClass('active');
}
if (success2) {
  alert_float('success','Đăng Kí Thành Công');
}
if (success3) {
  alert_float('success','Nhả Điểm Thành Công');
}
if (errorReg) {
  alert_float('danger','Đơn hàng đã có người đăng ký');

}

$('.confirm_get').click(function() {

  var id = $(this).attr('data-id');

  $('#modal-confirm-order').modal('show');
  $('#modal-confirm-order form').attr('action','/system/shiper/confirm/'+id);
});

$(document).on("click",".confirm_get",function() {

  var id = $(this).attr('data-id');

  $('#modal-confirm-order').modal('show');
  $('#modal-confirm-order form').attr('action','/system/shiper/confirm/'+id);
});


var h = window.innerHeight;
var heightContainer = h - (60);
$('.cover-table').css('height',heightContainer);


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

$('.tab').click(function() {
  $('#limit_geted').css('display','none');

  $('.tab').removeClass('active');
  $(this).addClass('active');
  var dataTab = $(this).attr('data-tab');
  $('.table-data').removeClass('active');
  $('.table'+dataTab).addClass('active');
  if (dataTab === '2') {
    $('.header-table h5').text('Danh Sách Đơn Hàng Đã Đăng Ký');
  }
  if (dataTab === '1') {
    $('.header-table h5').text('Danh Sách Đơn Hàng Chưa Lấy');
    $('.disable-view').fadeIn();

    $.ajax({
        url: '/system/shiper/ajax_wait',
        success: function (data) {
          $('.disable-view').fadeOut();

          data = JSON.parse(data);
          if (data.length > 0) {
            $('.table-data.table1 tbody').empty();
            var html = '';

            for (var i = 0; i < data.length; i++) {

              html += `<tr><td>`;
                if (data[i].customer_shop_code) {
                  html += `${date_format(data[i].created)} - <span class="bold-shop">${data[i].customer_shop_code}</span> - ${data[i].phone_customer}`;
                }else {
                  html += `${date_format(data[i].created)} - <span class="bold-shop">${data[i].name_customer_new}</span> - ${data[i].phone_customer}`;
                }
              html += `<td/>`;
              html += `<td style="padding-right: 35px;min-height: 40px;"><span><b>Đ/C:</b> <span class="mycopy" style="font-weight:bold;color:blue;">${data[i].repo_customer}</span> </span><a target="_blank" class="copy-address" style="position: absolute;top: 0;right: 10px;font-size: 26px;" href="https://www.google.com/maps/search/?api=1&query=${data[i].repo_customer}"><i class="fa fa-map-marker" aria-hidden="true"></i></a></td><td><b>Ghi Chú:</b><span style="color:#a73b3b;font-weight:bold">${data[i].note}</span> </td> <td>`;

              html +=
              `<a ${data[i].receive_or_pay == '1' ? "style='background: #FF9800;'" : ""}   data-id="${data[i].id}" href="javascript:;" class="btn btn-custom btn-primary left confirm_get">
                ${ data[i].receive_or_pay == '1' ? 'Xác nhận đã trả' : 'Xác nhận đã lấy'}
              </a>`;

              html += ` <a  href="javascript:;" data-id="${data[i].id}" class="btn btn-custom btn-warning center reg_data_order confirmation">


                 ${ data[i].receive_or_pay == '1' ? 'Đăng ký trả' : 'Đăng ký lấy'}
               </a>`;

              html += ` <a class="btn btn-custom btn-primary right" href="tel:${data[i].phone_customer}">Gọi Shop</a>`;

              html += `<div class="clear-fix"></td>`;
              html += `</tr>`;
            }
            $('.table-data.table1 tbody').append(html);
          }
          else {
            $('.table-data.table1').empty();
            var html = `<p class="data-empty">Chưa Có Danh Sách Điểm Đơn Hàng </p>`;
            $('.table-data.table1').append(html);
          }

        }
    });
  }
  if (dataTab === '3') {
    $('.header-table h5').text('Danh sách đơn hàng đã xử lý');
    $('#limit_geted').css('display','flex');

    var limit = $('#limit_geted select').val();
    $('.disable-view').fadeIn();

    $.ajax({
        url: `/system/shiper/geted_ajax?limit=${limit}`,
        success: function (data) {
          $('.disable-view').fadeOut();
          data = JSON.parse(data);
          if (data.length > 0) {
            $('.table-data.table3 tbody').empty();
            var html = '';

            for (var i = 0; i < data.length; i++) {

              html += `<tr><td>`;
                if (data[i].customer_shop_code) {
                  html += `${date_format(data[i].modified)} - <span class="bold-shop">${data[i].display_name}</span> - ${data[i].phone_customer}`;
                }else {
                  html += `${date_format(data[i].modified)} - <span class="bold-shop">${data[i].display_name}</span> - ${data[i].phone_customer}`;
                }


              html += `<td>
                <span><b>Đ/C:</b> <span style="font-weight:bold;color:blue;">${data[i].repo_customer}</span> </span>
              </td>
              <td>
                <b>Ghi Chú:</b>
                <span style="color:#a73b3b;font-weight:bold">${data[i].note}</span>

              </td>`;

              html += `<td>
               <b>Người lấy:</b>
               <span>
                 ${data[i].lastname} ${data[i].firstname} - Số Đơn: ${data[i].number_order_get}
               </span>
              </td>`;


              html += `</tr>`;

            }
            $('.table-data.table3 tbody').append(html);
          }
          else {
            $('.table-data.table3 tbody').empty();
            var html = `<tr><td><p class="data-empty">Chưa Có Danh Sách Đã Lấy Hàng </p></td></tr>`;
            $('.table-data.table3 tbody').append(html);
          }

        }
    });

  }
  if (dataTab === '4') {
    $('.header-table h5').text('Tài Khoản');
  }
});
$('#modal-confirm-order').on('hide.bs.modal', function (e) {
  $('#modal-confirm-order form')[0].reset();
})



</script>


<script type="text/javascript">
    var elems = document.getElementsByClassName('confirmation');
    var confirmIt = function (e) {

      if (!confirm('Bạn Sẽ Đăng Ký Điểm Chờ Hàng?')){
        return false;
      }
      var idRe = $(this).attr('data-id');
      location.replace(`/system/shiper/reg/${idRe}`);

    };
    for (var i = 0, l = elems.length; i < l; i++) {
        elems[i].addEventListener('click', confirmIt, false);
    }

    $(document).on("click",".confirmation",function(e) {
      var c = confirm('Bạn Sẽ Đăng Ký Điểm Chờ Hàng?');

      if (!c) {
        return false;
      }

      var idRe = $(this).attr('data-id');
      location.replace(`/system/shiper/reg/${idRe}`);
    });


    var elems = document.getElementsByClassName('confirmation_un_reg');
    var confirmIt = function (e) {

        if (!confirm('Bạn Sẽ Nhả Điểm Chờ Hàng?')) {
          return false;
        }
      var idRe = $(this).attr('data-reg-id');

      location.replace(`/system/shiper/un_reg/${idRe}`);

    };
    for (var i = 0, l = elems.length; i < l; i++) {
        elems[i].addEventListener('click', confirmIt, false);
    }
    //






    $('.copy-address').click(function() {

      $(this).find('span').fadeIn();

      setTimeout( () =>  {
        $(this).find('span').fadeOut();
      }, 250);

      var copyText = $(this).parent().find('.mycopy');
      copyToClipboard(copyText);
    });

    function copyToClipboard(element) {
      var $temp = $("<input>");
      $("body").append($temp);
      $temp.val($(element).text()).select();
      document.execCommand("copy");
      $temp.remove();
    }



    $('.change-password-modal').click(function() {
      $('#modal-edit-password').modal('show');

    });

    $(document).on("change","#limit_geted select",function() {

      var limit = $('#limit_geted select').val();
      $('.disable-view').fadeIn();

      $.ajax({
          url: `/system/shiper/geted_ajax?limit=${limit}`,
          success: function (data) {
            $('.disable-view').fadeOut();
            data = JSON.parse(data);
            if (data.length > 0) {
              $('.table-data.table3 tbody').empty();
              var html = '';

              for (var i = 0; i < data.length; i++) {

                html += `<tr><td>`;
                  if (data[i].customer_shop_code) {
                    html += `${date_format(data[i].modified)} - <span class="bold-shop">${data[i].display_name}</span> - ${data[i].phone_customer}`;
                  }else {
                    html += `${date_format(data[i].modified)} - <span class="bold-shop">${data[i].display_name}</span> - ${data[i].phone_customer}`;
                  }


                html += `<td>
                  <span><b>Đ/C:</b> <span style="font-weight:bold;color:blue;">${data[i].repo_customer}</span> </span>
                </td>
                <td>
                  <b>Ghi Chú:</b>
                  <span style="color:#a73b3b;font-weight:bold">${data[i].note}</span>

                </td>`;

                html += `<td>
                 <b>Người lấy:</b>
                 <span>
                   ${data[i].lastname} ${data[i].firstname} - Số Đơn: ${data[i].number_order_get}
                 </span>
                </td>`;


                html += `</tr>`;

              }
              $('.table-data.table3 tbody').append(html);
            }
            else {
              $('.table-data.table3 tbody').empty();
              var html = `<tr><td><p class="data-empty">Chưa Có Danh Sách Đã Lấy Hàng </p></td></tr>`;
              $('.table-data.table3 tbody').append(html);
            }

          }
      });
    });



</script>

</body>
</html>
