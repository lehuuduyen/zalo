<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title></title>
<!--    <link rel="stylesheet" href="/system/assets/css/cash_book.css">-->


    <style media="screen">
        /*.list-detail p{*/
        /*    font-size: 18px;*/
        /*}*/
        .list-detail pre {
            font-size: 16px;
        }
        .stm {
            margin-top: 5px;
            margin-bottom: 5px;
            margin-left: 10px;
            margin-right: 10px;
        }
    </style>
    <style type="text/css" media="print">
        @page
        {
            margin: 0;
            margin: 0px;
            margin-left: 0px;
            margin-right: 0px;
            margin-bottom: 0;
            width: 339.84px;
            height: 226.56px;

            /*size: A4;*/
            /*margin: 0;*/
            /*margin: 20px;*/
            /*margin-left: 30px;*/
            /*margin-right: 30px;*/
            /*margin-bottom: 0;*/
            /*width: 339.84px;*/
            /*height: 226.56px;*/

        }
        @media print {

        }
    </style>

</head>
<?php
$this->load->helper('number_vnd_string');
?>
<body>
    <div class="head-print" style="width: 321.84px;height: 207.56px;border: 1px solid black;">
        <?php if(isset($dv) && $dv == 'VTP'){?>
            <div style="position: absolute;display: block;margin-top: 184px;margin-left: 245px;font-size: 20px;">
                SPSVTP
            </div>
        <?php }elseif (isset($dv) && $dv == 'VNC'){?>
            <div style="position: absolute;display: block;margin-top: 184px;margin-left: 245px;font-size: 20px;">
                SPSVNC
            </div>
        <?php }elseif (isset($dv) && $dv == 'NB'){?>
            <div style="position: absolute;display: block;margin-top: 184px;margin-left: 245px;font-size: 20px;">
                SPSNB
            </div>
        <?php }?>
        <div class="header-he">
            <p style=" margin-bottom: 5px;margin-top: 5px;">
                <?php $code = explode('.', $create_order->code_supership)?>
                <img style="    height: 55px;width: 286px;margin-left: 15px;;" src="<?=base_url('cron/gen_barcode/'.$code[count($code) - 1].'/code128/40')?>"/>
            </p>
        </div>
        <div class="list-detail" style="font-size:12px">
            <p style="margin-top: 0px;margin-bottom: 5px;margin-left: 10px;margin-right: 10px;">
                <span style="text-align:left;"><b><?=$create_order->shop?></b></span>
                <span style="float:right;"><b><?=$create_order->date_create?></b></span>
            </p>

            <p style="margin-top: 5px; margin-bottom: 5px;margin-left: 10px;margin-right: 10px;">
                <b>&nbsp;<?= $create_order->required_code?></b>
                <b style="float:right">&nbsp;<?=$create_order->soc?></b>
            </p>
            <p style="margin-top: 5px; margin-bottom: 5px;margin-left: 10px;margin-right: 10px;">
                <span>Thu Hộ: <b><?=number_format_data($create_order->collect)?></b></span>
                <span style="float:right"><b><?=$create_order->name?></b></span>
            </p>
            <div style="display: block;"></div>
            <p style="margin-top: 5px;margin-bottom: 5px;margin-left: 10px;margin-right: 10px"><i><?=$create_order->address.','.$create_order->district.'-'.$create_order->province?></i></p>
            <p style="margin-top: 5px;margin-bottom: 5px;margin-left: 10px;margin-right: 10px;">Ghi Chú: <i style="font-size:10px"><?=$create_order->note?></i></p>
        </div>
    </div>




    <script>
        // self executing function here
        (function() {
           document.title = "";
           var print = <?php echo $print ? 'true' : 'false'?>//;
           // if (print === true) {
           //     window.print()
           // }

        })();
        window.print()
    </script>

</body>

</html>

<?php //var_dump($receipts); ?>
