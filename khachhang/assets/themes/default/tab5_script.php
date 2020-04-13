<script type="text/javascript">

formatPhoneNumber = (str) => {
//Filter only numbers from the input
let cleaned = ('' + str).replace(/\D/g, '');

//Check if the input is of correct length
let match = cleaned.match(/^(\d{3})(\d{3})(\d{4})$/);

if (match) {
return match[1]+ '.' + match[2] + '.' + match[3]
};

return null
};
  function initAccount() {

    var url = `/khachhang/app/get_customer/${$('#id_customer').val()}`;

    $.ajax({
        type: "GET",
        url: url ,
        cache: false,
        success: function (data) {
          var data = JSON.parse(data);

          var html = `<div class="">
          <h2>${data.customer_shop_name}</h2>
          <p><strong>Email: </strong> ${data.customer_email} </p>
          <p><strong>Phone: </strong> ${data.customer_phone} </p>
          <p><strong>Zalo: </strong> ${data.customer_phone_zalo} </p>
          <p><strong>Chăm sóc khách hàng: </strong> ${data.lastname} ${data.firstname}  <strong>Phone:</strong> ${formatPhoneNumber(data.phonenumber)}</p>
        </div>`;



        $('.profile').empty();

        $('.profile').append(html);


      }
    });
  }

  $( document ).ready(function() {
    $('.show-hide-pass').click(function() {
      $('.cover-pass').toggleClass('hide');
    });
    $('.confirm-password-change').click(function() {
      var pass = $('#pwd').val();
      var re_pass = $('#re-pwd').val();
      var id = $('#id_customer').val();

      if (pass !== re_pass) {
        alert('Mật Khẩu Không Khớp');
      }else {
        $.ajax({
          url: '/khachhang/app/change_pass',
          method: 'POST',
          data: {pass,id},
          success: function(data){

            if (JSON.parse(data).status == '1') {
              $('.cover-pass').toggleClass('hide');
              $.notify("Đổi Mật Khẩu Thành Công", "success");
            }else {
              $('.cover-pass').toggleClass('hide');
              $.notify("Đổi Mật Khẩu Thất Bại", "error");
            }

          },
          error:function(e) {
            console.log(e);
          }
        });
      }
    });
  });






</script>
