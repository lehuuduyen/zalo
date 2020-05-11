<html lang="vi"><!--<![endif]--><head>
    <meta charset="utf-8">
    <title>In Đơn Hàng - SuperShip</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Dịch vụ Giao hàng Chuyên nghiệp cho Thương mại Điện tử SuperShip">
    <meta name="author" content="SuperShip">
    <meta name="csrf-token" content="lqimF4u8LZoHhpIcqR17sa4BXOh4QuAjWqGERr4A">
    <link href="https://mysupership.vn/favicon.ico" rel="shortcut icon">
    <link href="//www.google-analytics.com" rel="dns-prefetch">
    <link href="//www.googletagmanager.com" rel="dns-prefetch">
    <link href="https://mysupership.vn/orders/print" rel="canonical">
    <meta name="apple-itunes-app" content="app-id=1445532737, app-argument=https://apps.apple.com/us/app/supership/id1445532737?ls=1">
    <meta property="fb:app_id" content="248003215696439">
    <meta property="og:site_name" content="SuperShip">
    <meta property="og:locale" content="vi_VN">
    <meta property="og:locale:alternate" content="en_US">
    <meta property="og:url" content="https://mysupership.vn/orders/print">
    <meta property="og:type" content="website">
    <meta property="og:title" content="In Đơn Hàng - SuperShip">
    <meta property="og:description" content="Dịch vụ Giao hàng Chuyên nghiệp cho Thương mại Điện tử SuperShip">
    <meta property="og:image" content="https://mysupership.vn/custom/img/flogo.png">
    <meta name="twitter:card" value="summary">
    <meta name="twitter:url" content="https://mysupership.vn/orders/print">
    <meta name="twitter:title" content="In Đơn Hàng">
    <meta name="twitter:description" content="Dịch vụ Giao hàng Chuyên nghiệp cho Thương mại Điện tử SuperShip">
    <meta name="twitter:image" content="https://mysupership.vn/custom/img/flogo.png">
    <meta name="twitter:site" content="@supershipvn">
    <meta name="twitter:creator" content="@supershipvn">
    <!-- Google Tag Manager -->
    <script type="text/javascript" async="" src="https://www.google-analytics.com/analytics.js"></script><script async="" src="https://www.googletagmanager.com/gtm.js?id=GTM-5SXSTQL"></script><script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-5SXSTQL');</script>
    <!-- End Google Tag Manager -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=all" rel="stylesheet" type="text/css">
    <link href="https://mysupership.vn/custom/plg/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="https://mysupership.vn/custom/css/supership.css?v=853834057" rel="stylesheet" type="text/css">
    <link href="https://mysupership.vn/custom/css/print.css?v=603313694" rel="stylesheet" type="text/css">
    <style>
        img{
            width: 90%;
        }
        @media print {
            .footer {page-break-after: always;}
        }
    </style>
</head>
<body cz-shortcut-listen="true">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5SXSTQL"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<a class="btn btn-danger hidden-print custom-button" onclick="javascript:window.print();">
    In
    <i class="fa fa-print"></i>
</a>
<div id="orders-print">
    <div class="">
        <?php foreach ($data as $result){?>
        <div class="footer" style="width: 650px; margin: 0 auto;">
            <div style="display: block; border: 2px dashed #000000; margin: 10px 0; padding: 20px;">
                <table class="table table-bordered text-center">
                    <tbody>
                    <tr>
                        <td>
                            <div class="div-barcode">
                                <svg class="svg-barcode" id="img-barcode<?=$result['id']?>"></svg>
                                <input type="hidden" class="val-barcode" value="<?=$result['required_code_in']?>">
                            </div>
                        </td>
                        <td>
                            <div>
                                <img src="https://mysupership.vn/custom/img/logo-big-black.png" alt="SuperShip">
                            </div>
                        </td>
                        <td>
                            <h4 class="bold" style="font-size: 15px"><?=$result['name_shop']?></h4>
                            <div><?=date('d/m/Y H:i:s',strtotime($result['created']))?></div>
                        </td>
                        <td>
                            <div class="div-qrcode">
                                <img style="width: 50%" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFQAAABUAQMAAAAmpYKCAAAABlBMVEX///8AAABVwtN+AAAAAXRSTlMAQObYZgAAAAlwSFlzAAAOxAAADsQBlSsOGwAAALtJREFUKJGVkkEOBCEIBPkBX+YHftUjt14aMTuHkXXJJNTEDkKjAPD8MOVfdhHH8EgNw6mPtFhhF/zQj9+MB7tcsBdHnzzYPb8x56V8z37D2x8GYLLjjeFKcaR5yVgxpni5rHbm1McsGnr1vNbjoOFcV1QwusB/6VjZVupnMev0HM3DtPwZZDty+jlqdqYRs0nP6dXeI+/qGGtzjsXyL0ex9eDPTP+l9Cgfeo7Q+S2lduRsJUeodxhneuYPz6uewpsj6lMAAAAASUVORK5CYII=" alt="barcode" class="img-qrcode" id="img-qrcode<?=$result['id']?>">
                                <input type="hidden" class="val-qrcode" value="<?=$result['required_code_in']?>">
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <table class="table table-bordered text-center">
                    <tbody>
                    <tr>
                        <td width="130">
                            <h4 class="bold"><?=$result['required_code']?></h4>
                            <h4 class="bold"><?=$result['soc']?></h4>
                        </td>
                        <td width="120">
                            <div>Thu Hộ</div>
                            <h4 class="bold"><?=number_format($result['amount'],0,',','.')?> ₫</h4>
                        </td>
                        <td width="140">
                            <div>Tổng Khối Lượng</div>
                            <h4 class="bold"><?=number_format($result['weight'],0,',','.')?> gr</h4>
                        </td>
                        <td>
                            <div>Lấy Hàng</div>
                            <h5 class="bold"><?=$result['pickup_province']?></h5>
                        </td>
                        <td>
                            <div>Giao Hàng</div>
                            <h5 class="bold"><?=$result['province']?></h5>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <table class="table table-bordered text-center">
                    <tbody>
                    <tr>
                        <td>
                            <div class="bold"><?=$result['address'] .', '.$result['commune'].', '.$result['district'].', '.$result['province']?></div>
                            <div><?=$result['name']?> - <?=$result['phone']?></div>

                            <div class="text-left margin-top-10 bold uppercase"><?=$result['note']?></div>

                        </td>
                    </tr>

                    </tbody>
                </table>

                <table class="table table-bordered text-center">
                    <tbody>
                    <tr>
                        <td>
                            <h4 class="bold"><?=$result['product']?> </h4>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
        <?php } ?>

    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/jsbarcode/3.6.0/JsBarcode.all.min.js"></script>
<script>

    function generateBarCode(idqr,val)
    {

        var url = 'https://api.qrserver.com/v1/create-qr-code/?data=' + val + '&amp;size=30x30';
        console.log(url);
        console.log(idqr);
        $('#'+idqr).attr('src', url);
    }
    let elemendDivBarcode = document.querySelectorAll(".div-barcode");
    $.each(elemendDivBarcode,function (key,elemendDiv) {
        val = elemendDiv.querySelectorAll(".val-barcode")[0].value
        idSvg =  elemendDiv.querySelectorAll(".svg-barcode")[0].getAttribute("id");

        JsBarcode("#"+idSvg, val,{
            width: 2,
            height: 40,
        });

    })

    let elemendDivQrcode = document.querySelectorAll(".div-qrcode");
    $.each(elemendDivQrcode,function (key,elemendDivQR) {
        val = elemendDivQR.querySelectorAll(".val-qrcode")[0].value
        idqr =  elemendDivQR.querySelectorAll(".img-qrcode")[0].getAttribute("id");
        generateBarCode(idqr,val)
    })
    setTimeout(function(){  window.print(); }, 1000);


</script>

</body></html>