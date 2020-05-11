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
        }
        @media print {

        }
        @media print {
            .pagebreak { page-break-before: always; } /* page-break-after works, as well */
        }
        @media print {
        .pagebreak {
                clear: both;
                page-break-after: always;
                height: 4.2in;
            }
    }
    </style>

</head>
<?php $this->load->helper('number_vnd_string'); ?>
<body>
<?php foreach($data as $key => $value) {?>
    <?php foreach($value['detail'] as $k => $v) {?>
        <div class="head-print pagebreak" style="width: 3in;min-height: 4in;border: 1px solid black;margin-right:10px;margin-bottom:3px;">
            <div class="header-he"></div>
            <div class="list-detail" style="font-size:14px">
                <p style="margin-top: 5px;margin-bottom: 10px;margin-left: 10px;margin-right: 10px; text-align:center;">
                    <span style="text-align:left;float: left;"><b>#<?= ($k + 1)?></b></span>
                    <span style="text-align:center;font-size:16px"><b>SANG SEN SHOP</b></span>
                </p>

                <p style="margin-top: 5px; margin-bottom: 10px;margin-left: 10px;margin-right: 10px;text-align:center;">
                    Sỉ lẻ quần áo cao cấp
                </p>
                <p style="margin-top: 5px; margin-bottom: 15px;margin-left: 10px;margin-right: 10px;text-align:center;">
                    (Hotline / Zalo: 0356.035.269)
                </p>
                <p style="margin-top: 5px; margin-bottom: 15px;margin-left: 10px;margin-right: 10px;font-size:16px">
                    <span>SĐT: <b><?=$v->phone[0] == 0 ? '0' : ''?><?= number_format(substr($v->phone, 0, 10), 0, '.', '.') ?></b></span>
                </p>
                <p style="margin-top: 5px; margin-bottom: 15px;margin-left: 10px;margin-right: 10px;font-size:16px">
                    <span>TÊN: <b><?= $v->name ?></b></span>
                </p>
                <p style="margin-top: 5px; margin-bottom: 15px;margin-left: 10px;margin-right: 10px;font-size:16px">
                    <span>THU HỘ: <b>................................................</b></span>
                </p>
                <p style="margin-top: 5px; margin-bottom: 15px;margin-left: 10px;margin-right: 10px;">
                    <span>ĐC: <b>..................................................................</b></span>
                </p>
                <p style="margin-top: 5px; margin-bottom: 15px;margin-left: 10px;margin-right: 10px;">
                    <span>PHƯỜNG/XÃ: <b>................................................</b></span>
                </p>
                <p style="margin-top: 5px; margin-bottom: 15px;margin-left: 10px;margin-right: 10px;">
                    <span>QUẬN/HUYỆN: <b>.............................................</b></span>
                </p>
                <p style="margin-top: 5px; margin-bottom: 15px;margin-left: 10px;margin-right: 10px;">
                    <span>TỈNH: <b>..............................................................</b></span>
                </p>
                <p style="margin-top: 5px;margin-bottom: 15px;margin-left: 10px;margin-right: 10px;">
                    <i style="font-size:13px"><?= $v->note ?></i>
                </p>
                <p style="margin-top: 5px; margin-bottom: 15px;margin-left: 10px;margin-right: 10px;">
                    <span><?= $v->network ?></span>
                </p>
            </div>
        </div>
    <?php } ?>
<?php } ?>




    <script>
        // self executing function here
        (function() {
            document.title = "";
            var print = true;
            if (print === true) {
                window.print()
            }

        })();
    </script>

</body>

</html>

<?php //var_dump($receipts); ?>
