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
<div class="row" style="width: 100%">
    <div class="col-md-6 " style="display: flex">
        <img width="15%" height="150px" src="/assets/images/super-ship-logo.png">
        <div>
            <p style="color: red;font-size:17px"><b>CÔNG TY TNHH SUPERSHIP HẢI DƯƠNG</b></p>
            <p>Địa Chỉ: 11 Tứ Minh - P.Tứ Minh - Tp.Hải Dương</p>
            <p>Hotline: 0854.854.999 / 0901.5858.15 </p>
            <p>Email: haiduong@supership.vn</p>
        </div>

    </div>
    <div class="col-md-6" style="width: 100%;" >
        <div style="padding: 5px 0px;text-align: center;font-size:20px"><b>DANH SÁCH HÀNG HOÀN SHOP</b></div>
        <div style="text-align: center;">(Phiếu số: <?=$data[0]->code_return?>)</div>
        <div>Ngày tạo: <?=date('d/m/Y',strtotime($data[0]->created_at))?></div>
    </div>
    <div style="width: 100%">
        <table border="1px" width="100%" style="border-collapse: collapse;text-align: center">
            <thead>
            <tr class="background">
                <th class="width40" ">SHOP</th>
                <th class="width20"">MÃ ĐƠN</th>
                <th class="width40">XÁC NHẬN KH</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data as $orderReturn){?>
            <tr>
                <td rowspan="<?=count($orderReturn->list_code_supership)?>"><?=$orderReturn->shop?> <br> Số Đơn Trả: <?=$orderReturn->total?> Đơn</td>
                <td><?=$orderReturn->list_code_supership[0]?></td>
                <td rowspan="<?=count($orderReturn->list_code_supership)?>" >
                  <div style="padding: 10px">Ngày Trả: Ngày ……Tháng ……Năm 2020</div>
                    <div><b>Ký Nhận</b></div>
                    <br>
                    <br>
                    <br>
                  <div>……………………………</div>
                    <br>
                </td>
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
    </div>
</div>
<script>
    window.print();
</script>
</body>
</html>