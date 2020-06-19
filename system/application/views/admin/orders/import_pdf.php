<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        body{
            font-size: 15px;
        }
        @media print , screen{
            .background {
                background-color: yellow;
                -webkit-print-color-adjust: exact;

            }
            .width40{
                width: 40%;
                -webkit-print-color-adjust: exact;

            }
            .width20{
                width: 20%;
                -webkit-print-color-adjust: exact;

            }
        }
    </style>
</head>
<body>
<div class="row" style=" width: 90%; margin: auto;">
    <div class="col-md-6 " style="display: flex">
        <img width="10%" height="97px" src="/system/assets/images/super-ship-logo.png">
        <div style="margin-left: 30px;">
            <div style="color: red;font-size:17px;margin: 10px 0px 5px 0px"><b>CÔNG TY TNHH SUPERSHIP HẢI DƯƠNG</b></div>
            <div style="margin-bottom: 5px">Địa Chỉ: 11 Tứ Minh - P.Tứ Minh - Tp.Hải Dương</div>
            <div style="margin-bottom: 5px">Hotline: 0854.854.999 / 0901.5858.15 </div>
            <div>Email: haiduong@supership.vn</div>
        </div>

    </div>
    <div class="col-md-6" style="width: 100%;" >
        <div style="padding: 5px 0px;text-align: center;font-size:20px"><b>PHIẾU NHẬP HÀNG HOÀN</b></div>
        <div style="text-align: center;">(Phiếu số: <?=$data[0]->code_return?>)</div>
        <div>Ngày tạo: <?=date('d/m/Y',strtotime($data[0]->created_at))?></div>
    </div>
    <div style="width: 100%">
        <table border="1px" width="100%" style="border-collapse: collapse;text-align: center">
            <thead>
            <tr class="background">
                <th class="width40" ">SHOP</th>
                <th class="width20"">MÃ ĐƠN</th>
                <th class="width40">XÁC NHẬN HOÀN</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data as $key=> $orderReturn){?>
            <tr>
                <td rowspan="<?=count($orderReturn->list_code_supership)?>"><?=$orderReturn->shop?> <br> Số Đơn Trả: <?=$orderReturn->total?> Đơn</td>
                <td><?=$orderReturn->list_code_supership[0]?></td>
                <?php
                if($key ==0) {
                    ?>
                    <td rowspan="<?= $total ?>">
                        <div><b>Ký Nhận</b></div>
                        <br>
                        <br>
                        <br>
                        <div>……………………………</div>
                        <br>
                    </td>
                    <?php
                }
                ?>
            </tr>
<!--                //td  ben trong-->
                <?php if(count($orderReturn->list_code_supership)>1){ foreach($orderReturn->list_code_supership as $key=> $listCode){
                    if($key==0){
                        continue;
                    }
                    ?>

                    <tr>
                       <td><?=$listCode?></td>
                   </tr>

                <?php }}?>



            <?php }?>
            </tbody>
        </table>

        <h3>TỔNG SỐ ĐƠN TRẢ: <?=$total?></h3>
    </div>
</div>
<script>
    window.print();
</script>
</body>
</html>