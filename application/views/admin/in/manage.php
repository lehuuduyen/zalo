<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" type="text/css" id="reset-css" href="<?=base_url()?>assets/css/reset.min.css?v=2.3.3">
    <link rel="stylesheet" type="text/css" id="roboto-css" href="<?=base_url()?>assets/plugins/roboto/roboto.css?v=2.3.3">
    <link rel="stylesheet" type="text/css" id="vendor-css" href="<?=base_url()?>assets/builds/vendor-admin.css?v=2.3.3">
    <link rel="stylesheet" type="text/css" id="app-css" href="<?=base_url()?>assets/css/style.min.css?v=1584884630">
    <script type="text/javascript" id="vendor-js" src="<?=base_url()?>assets/builds/vendor-admin.js?v=2.3.3"></script>
    <script type="text/javascript" id="jquery-validation-js" src="<?=base_url()?>assets/plugins/jquery-validation/jquery.validate.min.js?v=2.3.3"></script>


<?php
    $this->load->helper('number_vnd_string');
?>
    <style>
        .to_file {
            background: blueviolet!important;
        }
    </style>
<body>
    <div class="content">
        <div class="panel_s">
            <div class="panel-body">
                <?php echo form_open(base_url('in/print_data'), array('id' => 'purchase-form', 'class' => '_transaction_form invoice-form', 'enctype'=> 'multipart/form-data')); ?>
<!--                <div class="col-md-2">-->
                    <input type="file" name="file[]" id="file" class="inputfile hide" data-multiple-caption="{count} files selected" multiple/>
<!--                </div>-->
                    <label for="file" class="btn btn-danger"><i class="fa fa-upload" aria-hidden="true"></i> <span>Chọn file excel</span></label>
<!--                <div class="col-md-3">-->
                    <button class="btn-success btn" type="submit">Upload</button>
<!--                </div>-->
                <?php echo form_close(); ?>
                <div class="print-data"></div>
            </div>
        </div>
        <div class="panel_s">
            <div class="panel-body">
                <div class="col-md-3">
                    <?php echo render_date_input('date_start', 'Ngày bắt đầu', _d($date_start))?>
                </div>
                <div class="col-md-3">
                    <?php echo render_date_input('date_end', 'Ngày kết thúc', _d($date_end))?>
                </div>
                <div id="data-table"></div>
            </div>
        </div>
    </div>
</body>
</html>


<script>

    <?php
        if($this->session->flashdata('success')) {?>
            alert("<?=$this->session->flashdata('success')?>");
        <?php }
    ?>

    $("#date_start").datepicker(
        {
            defaultDate: $("#date_start").val(),
            dateFormat: 'dd/mm/yy'
        }
    );
    $("#date_end").datepicker(
        {
            defaultDate: $("#date_end").val(),
            dateFormat: 'dd/mm/yy'
        }
    );

    $('body').on('change', '#date_start, #date_end', function(e) {
        reloadTable();
    })

    var inputs = document.querySelectorAll( '.inputfile' );
    Array.prototype.forEach.call( inputs, function( input )
    {
        var label	 = input.nextElementSibling,
            labelVal = label.innerHTML;

        input.addEventListener( 'change', function( e )
        {
            var fileName = '';
            if( this.files && this.files.length > 1 )
                fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
            else
                fileName = e.target.value.split( '\\' ).pop();

            if( fileName ) {
                label.querySelector('span').innerHTML = fileName;
                $(label).addClass('to_file');
            }
            else {
                label.innerHTML = labelVal;
                $(label).removeClass('to_file');
            }
        });
    });
    reloadTable();
    function reloadTable() {
        var date_start = $('#date_start').val().replace(/\//g, "-");
        var date_end = $('#date_end').val().replace(/\//g, "-");
        var dataString = {date_start : date_start, date_end : date_end};
        $.get("<?=base_url('in/getTable')?>", dataString, function(data) {
            $('#data-table').html(data);
        })
    }

    $('body').on('change', '#mass_select_all', function() {
        if($(this).prop('checked') == true) {
            $('#table-print-data tbody').find('input[type="checkbox"]').prop('checked', true);
        }
        else {
            $('#table-print-data tbody').find('input[type="checkbox"]').prop('checked', false);
        }
    })

    function print_data(array_data = "") {
        // var data = $('#table-print-data tbody').find('input[type="checkbox"]:checked');
        // var array_data = [];
        // $.each(data, function(i, v) {
        //     array_data.push($(v).val());
        // })
        // array_data = array_data.toString();
        // array_data = array_data.replace(/,/g, "-");
        var url = "<?=base_url('in/print_out?id=')?>" + array_data;
        console.log(url)
        window.open(url);
    }




    // (function() {
    //     document.title = "";
    //     var print = true;
    //     if (print === true) {
    //        window.print()
    //     }
    // })();
</script>
