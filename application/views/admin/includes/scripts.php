<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php include_once(APPPATH.'views/admin/includes/helpers_bottom.php'); ?>

<?php hooks()->do_action('before_js_scripts_render'); ?>

<?php echo app_compile_scripts();

/**
 * Global function for custom field of type hyperlink
 */
echo get_custom_fields_hyperlink_js_function(); ?>
<?php
/**
 * Check for any alerts stored in session
 */
app_js_alerts();
?>
<?php
/**
 * Check pusher real time notifications
 */
if (get_option('pusher_realtime_notifications') == 1) { ?>
   <script type="text/javascript">
   $(function(){
         // Enable pusher logging - don't include this in production
         // Pusher.logToConsole = true;
         <?php $pusher_options = hooks()->apply_filters('pusher_options', array());
            if (!isset($pusher_options['cluster']) && get_option('pusher_cluster') != '') {
                $pusher_options['cluster'] = get_option('pusher_cluster');
            }
         ?>
         var pusher_options = <?php echo json_encode($pusher_options); ?>;
         var pusher = new Pusher("<?php echo get_option('pusher_app_key'); ?>", pusher_options);
         var channel = pusher.subscribe('notifications-channel-<?php echo get_staff_user_id(); ?>');
         channel.bind('notification', function(data) {
            fetch_notifications();
         });
   });
   </script>
<?php } ?>
<?php app_admin_footer(); ?>





<script>


  var data_delay;
  var data_over;
  var data_status;
  var data_half;

  $(document).on("click",".click-show-table-delay",function() {
    $('#delay_popup').modal('show');

    if ($.fn.DataTable.isDataTable('.table-header_order_delay')) {
      $('.table-header_order_delay').DataTable().ajax.reload(function() {
          setTimeout(function () {
            highlightRow();
          }, 1000);
      },false);
    }else {
      data_delay = initDataTable('.table-header_order_delay', '/system/admin/header_controller/get_delay' , [1] , [1]);
      data_delay.column(0).visible(false);
      data_delay.column(9).visible(false);
      setTimeout(function () {
        highlightRow();
      }, 1000);
    }


  });





  $(document).on("click",".click-show-table-over",function() {
      console.log("crash");
    $('#over_popup').modal('show');
    //
    if ($.fn.DataTable.isDataTable('.table-header_order_over')) {
      $('.table-header_order_over').DataTable().ajax.reload(function() {
          // setTimeout(function () {
          //   highlightRowOver();
          // }, 1000);
      },false);
    }else {
      data_over = initDataTable('.table-header_order_over', '/system/admin/header_controller_over_order' , [1] , [1]);
      data_over.column(0).visible(false);
      data_over.column(2).visible(false);
      // data_over.column(11).visible(false);
      // setTimeout(function () {
      //   highlightRowOver();
      // }, 1000);
    }

  });


  $(document).on("click",".click-show-table-check_status",function() {

    $('#status_popup').modal('show');
    if ($.fn.DataTable.isDataTable('.table-header_order_over')) {
      $('.table-header_status').DataTable().ajax.reload(function() {
          setTimeout(function () {
             highlightRowStaus();
          }, 1000);
      },false);
    }else {
      data_status = initDataTable('.table-header_status', '/system/admin/Header_controller_status' , [1] , [1]);
      data_status.column(0).visible(false);
      data_status.column(9).visible(false);
     setTimeout(function () {
        highlightRowStaus();
     }, 1000);


    }


  });






  function highlightRow() {
    var number = 0;
    $('.table-header_order_delay tbody tr').each(function(i,item) {

      if ($(item).find('td').eq(9).find('span').html() == 'Chưa XL') {
        $(item).css('background','antiquewhite');
        number = number + 1;
      }
    });

    initRed();

  }


  function highlightRowOver() {
    var number = 0;
    $('.table-header_order_over tbody tr').each(function(i,item) {

      if ($(item).find('td').eq(9).find('span').html() == 'Chưa XL') {
          $(item).css('background','antiquewhite');
        number = number + 1;
      }
    });

    initRed();

  }

  function highlightRowStaus() {

      var number = 0;
      $('.table-header_status tbody tr').each(function(i,item) {

        if ($(item).find('td').eq(9).find('span').html() == 'Chưa XL') {
            $(item).css('background','antiquewhite');
          number = number + 1;
        }
      });

      initRed();
  }










  function initRed() {

    $.get("/system/admin/header_controller/getNumberList", function(data, status){
      if (data != 0) {
        $('.number_delay').html(data);
        $('.number_delay').show();
      }else {
        $('.number_delay').hide();
      }
    });


    $.get("/system/admin/Header_controller_status/getNumberList", function(data, status){
      if (data != 0) {
        $('.number_status').html(data);
        $('.number_status').show();
      }else {
        $('.number_status').hide();
      }
    });




  }

  $.get("/system/admin/header_controller/getNumberList", function(data, status){
    if (data != 0) {
      $('.number_delay').html(data);
      $('.number_delay').show();
    }else {
      $('.number_delay').hide();
    }
  });

  $.get("/system/admin/header_controller_over_order/getNumberList", function(data, status){
    if (data != 0) {
      $('.number_over').html(data);
      $('.number_over').show();
    }else {
      $('.number_over').hide();
    }
  });


  $.get("/system/admin/Header_controller_status/getNumberList", function(data, status){
    if (data != 0) {
      $('.number_status').html(data);
      $('.number_status').show();
    }else {
      $('.number_status').hide();
    }
  });


  function ajaxCallData(dom,data) {

    $.ajax({
      url: '/system/admin/header_controller/edit_status',
      type:'POST',
      data:data,
      success: function (data) {

        if (data_delay) {
          $('.table-header_order_delay').DataTable().ajax.reload(function() {
            highlightRow();
          },false);
        }
        if (data_over) {
          $('.table-header_order_over').DataTable().ajax.reload(function() {
            highlightRowOver();
          },false);
        }
        if (data_status) {
            $('.table-header_status').DataTable().ajax.reload(function() {
              highlightRowStaus();
            },false);
        }
        alert_float('success', 'Thay Đổi Thành Công');
      },
      error:function(e) {
        console.log(e);
      }
    });
  }


  function ajaxCallDataBack(id) {
    var data  = {
        id
    }
    $.ajax({
      url: '/system/admin/Header_controller_status/edit_status',
      type:'POST',
      data:data,
      success: function (data) {

        if (data_status) {
            $('.table-header_status').DataTable().ajax.reload(function() {
              highlightRowStaus();
            },false);
        }
        alert_float('success', 'Thay Đổi Thành Công');
      },
      error:function(e) {
        console.log(e);
      }
    });
  }


  function ajaxCallDataOver(dom,data) {
    $.ajax({
      url: '/system/admin/header_controller_over_order/edit_status',
      type:'POST',
      data:data,
      success: function (data) {


        if (data_over) {
          $('.table-header_order_over').DataTable().ajax.reload(function() {

          },false);
        }
        alert_float('success', 'Thay Đổi Thành Công');
      },
      error:function(e) {
        console.log(e);
      }
    });
  }


  function ajaxCallDataNote(data) {
    $.ajax({
      url: '/system/admin/header_controller/edit_note',
      type:'POST',
      data:data,
      success: function (data) {
        $('#delay_popup_note').modal('hide');
        if (data_delay) {
          data_delay.ajax.reload();
        }
        if (data_over) {
          data_over.ajax.reload();
        }
        if (data_status) {
            data_status.ajax.reload();
        }
        alert_float('success', 'Thay Đổi Ghi Chú Thành Công');
      },
      error:function(e) {
        console.log(e);
      }
    });
  }

  function ajaxCallDataNoteOver(data) {
    $.ajax({
      url: '/system/admin/header_controller_over_order/edit_note',
      type:'POST',
      data:data,
      success: function (data) {
        $('#over_popup_note').modal('hide');
        if (data_delay) {
          data_delay.ajax.reload();
        }
        if (data_over) {
          data_over.ajax.reload();
        }
        alert_float('success', 'Thay Đổi Ghi Chú Thành Công');
      },
      error:function(e) {
        console.log(e);
      }
    });
  }



  $(document).on('click', '#delay_popup .check-change-status', function(){
		var dom = $(this).parent();
		var id = $(this).parent().attr('data-id');
		var status;
		if (!$(this)[0].checked) {
      status_delay = false;
      data = {id , status_delay };
      ajaxCallData(dom,data);
		}
    else {
      status_delay = true;
      data = {id , status_delay };
      ajaxCallData(dom,data);
		}

	});

    $(document).on('click', '#status_popup .check-change-status-status', function(){
  		var dom = $(this).parent();
  		var id = $(this).parent().attr('data-id');
        var status_delay;
  		if (!$(this)[0].checked) {
        status_delay = false;
        data = {id , status_delay };
        ajaxCallData(dom,data);
  		}
      else {
        status_delay = true;
        data = {id , status_delay };
        ajaxCallData(dom,data);
  		}

  	});

    $(document).on('click', '#status_popup .change_status_to_back', function(){
        var txt;
        var r = confirm("Bạn chắc chắn đơn hàng này đã trả hàng cho shop!");
        var id = $(this).attr('data-id');

        if (r == true) {

          ajaxCallDataBack(id);
        } else {
          txt = "You pressed Cancel!";
        }

  	});


  $(document).on('click', '#over_popup .check-change-status-over', function(){

    var dom = $(this).parent();
    var id = $(this).parent().attr('data-id');

    var status;
    if (!$(this)[0].checked) {
      status = false;
      data = {id , status };
      ajaxCallDataOver(dom,data);
    }
    else {
      status = true;
      data = {id , status };
      ajaxCallDataOver(dom,data);
    }

  });



  $(document).on('click', '#over_popup .popup-edit-over', function(){
    var id = $(this).attr('data-id');
    $('#over_popup_note').modal('show');
    $.ajax({
      url: '/system/admin/header_controller_over_order/get_note?id='+id,
      success: function (data) {
        data = JSON.parse(data);
        $('#note_over').val(data.note_over);
        $('#id_note').val(id);
      },
      error:function(e) {
        console.log(e);
      }
    });
  });

  $(document).on('click', '#delay_popup .popup-edit-note', function(){
    var id = $(this).attr('data-id');
    $('#delay_popup_note').modal('show');
    $.ajax({
      url: '/system/admin/header_controller/get_note?id='+id,
      success: function (data) {
        data = JSON.parse(data);
        $('#note_delay').val(data.note_delay);
        $('#id_note').val(id);
      },
      error:function(e) {
        console.log(e);
      }
    });
  });


  $(document).on('click', '#status_popup .popup-edit-note', function(){
    var id = $(this).attr('data-id');
    $('#delay_popup_note').modal('show');
    $.ajax({
      url: '/system/admin/header_controller/get_note?id='+id,
      success: function (data) {
        data = JSON.parse(data);
        $('#note_delay').val(data.note_delay);
        $('#id_note').val(id);
      },
      error:function(e) {
        console.log(e);
      }
    });
  });


  $(document).on('mouseover', '#delay_popup .popup-edit-note', function(){
    $(this).parent().find('.will-show-hover').show();
  });

  $(document).on('mouseout', '#delay_popup .popup-edit-note', function(){
    $(this).parent().find('.will-show-hover').hide();
  });

  $(document).on('mouseover', '#status_popup .popup-edit-note', function(){

    $(this).parent().find('.will-show-hover').show();
  });

  $(document).on('mouseout', '#status_popup .popup-edit-note', function(){

    $(this).parent().find('.will-show-hover').hide();
  });


  $(document).on('mouseover', '#over_popup .popup-edit-over', function(){
    $(this).parent().find('.will-show-hover').show();
  });

  $(document).on('mouseout', '#over_popup .popup-edit-over', function(){
    $(this).parent().find('.will-show-hover').hide();
  });

  function mouseOver() {

    $(this).parent().find('.will-show-hover').show();
  }

  function mouseOut() {
    $(this).parent().find('.will-show-hover').hide();

  }



  $(document).on('change', '#note_delay', function(){
    var note_delay = $('#note_delay').val();
    var id = $('#id_note').val();
    data = {id , note_delay };
    ajaxCallDataNote(data);
  });


  $(document).on('change', '#note_over', function(){
    var note_over = $('#note_over').val();
    var id = $('#id_note').val();
    data = {id , note_over };
    ajaxCallDataNoteOver(data);
  });




  $(document).on("click",".click-show-table-order_half",function() {
      $('#modal_order_half').modal('show');
      if ($.fn.DataTable.isDataTable('.table-header_order_half')) {
          $('.table-header_order_half').DataTable().ajax.reload(function() {
              setTimeout(function () {
                  highlightRow();
              }, 1000);
          },false);
      }else {
          data_half = initDataTable('.table-header_order_half', '/system/admin/header_controller/get_order_half' , [1] , [1]);
          // data_delay.column(0).visible(false);
          // data_delay.column(9).visible(false);
          setTimeout(function () {
              highlightRow();
          }, 1000);
      }
  });


  $(document).on('click', '#modal_order_half .check-change-status-status-half', function(){
      var id = $(this).attr('data-id');
      if($(this).attr('type') == 'checkbox') {
            var data = $(this).val();
      }
      else {
          var data = $(this).attr('value');
      }
      var colums = $(this).attr('id-colum');
      $.ajax({
          url: '/system/admin/header_controller/update_status_half?id=' + id + '&data=' + data + '&colums=' + colums,
          success: function (data) {
              data = JSON.parse(data);
              if(data.success) {
                  $('.table-header_order_half').DataTable().ajax.reload(function() {
                      setTimeout(function () {
                          highlightRow();
                      }, 1000);
                  },false);
              }
              alert_float(data.alert_type, data.message);
          },
          error:function(e) {
              console.log(e);
          }
      });

  });

  $(document).on('click', '#modal_order_half .popup-add-note-half, #modal_order_half .popup-edit-note-half', function(){
      var id = $(this).attr('data-id');
      var note = '';
      if($(this).hasClass('popup-edit-note-half')) {
          note = $(this).attr('title')
      }
      var id = $(this).attr('data-id');
      $('#note_half').val(note);
      $('#id_half').val(id);
      $('#delay_popup_note_half').modal('show');
  });

  function ajaxCallDataNoteHalf(data) {
      $.ajax({
          url: '/system/admin/header_controller/update_note_half',
          type:'POST',
          data:data,
          success: function (data) {
              $('#delay_popup_note').modal('hide');
              data = JSON.parse(data);
              if(data.success) {
                  data_half.ajax.reload();
              }
              alert_float(data.alert_type, data.message);
          },
          error:function(e) {
              console.log(e);
          }
      });
  }
  $(document).on('change', '#note_half', function(){
      var note_half = $('#note_half').val();
      var id = $('#id_half').val();
      data = {id , note_half };
      ajaxCallDataNoteHalf(data);
  });

  $.get("/system/admin/Header_controller/getNumberListHalf", function(data, status){
      if (data != 0) {
          $('.number_status_half').html(data);
          $('.number_status_half').show();
      }
      else {
          $('.number_status_half').hide();
      }
  });

</script>
