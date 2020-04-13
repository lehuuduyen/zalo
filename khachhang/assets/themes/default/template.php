<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Default Public Template
 */
?><!DOCTYPE html>
<html>
<head>


    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico?v=<?php echo $this->settings->site_version; ?>">
    <link rel="icon" type="image/x-icon" href="/favicon.ico?v=<?php echo $this->settings->site_version; ?>">
    <title><?php echo $page_title; ?> - <?php echo $this->settings->site_name; ?></title>
    <meta name="keywords" content="<?php echo $this->settings->meta_keywords; ?>">
    <meta name="description" content="<?php echo $this->settings->meta_description; ?>">

    <?php // CSS files ?>
    <?php if (isset($css_files) && is_array($css_files)) : ?>
        <?php foreach ($css_files as $css) : ?>
            <?php if (!is_null($css)) : ?>
                <?php $separator = (strstr($css, '?')) ? '&' : '?'; ?>
                <link rel="stylesheet"
                      href="<?php echo $css; ?><?php echo $separator; ?>v=<?php echo $this->settings->site_version; ?>"><?php echo "\n"; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>


    <style>
        .loading-page {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 123123;
            width: 100%;
            height: 100%;
            background: #fff;
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

        .footer-nav a.active i, .footer-nav a.active span {
            color: #a73a3b;
        }

        #limit_geted {

            position: absolute;
            top: 0;
            right: 10px;
            margin: 0;
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
</head>
<body>
<div class="loading-page bg">
    <div class="loader" id="loader-6">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>

<?php
$user = json_decode(json_encode(json_decode($this->input->cookie('logged_in'))), true);
?>

<input type="hidden" name="customer_shop_code" value="<?php echo $user['customer_shop_code'] ?>"
       id="customer_shop_code">
<input type="hidden" name="id_customer" value="<?php echo $user['id'] ?>" id="id_customer">

<input type="hidden" name="customer_phone_header" value="<?php echo $user['customer_phone'] ?>"
       id="customer_phone_header">

<input type="hidden" name="token_customer" value="<?php echo $user['token_customer'] ?>" id="token_customer">


<?php $is_mobile = $this->isAppMobile; ?>


<?php if ($is_mobile == false): ?>
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                        aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only"><?php echo lang('core button toggle_nav'); ?></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand logo-customer" href="/">
                    <img src="/khachhang/assets/themes/core/img/dcv_logo_white.png" alt="">
                </a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <?php // Nav bar left ?>
                <ul class="nav navbar-nav">
                    <li class="active tab tab1" data-tab="1"><a href="javascript:;">CÔNG NỢ</a></li>
                    <li class="tab tab7" data-tab="7"><a href="javascript:;">QUẢN LÝ ĐƠN HÀNG</a></li>
                    <li class="tab tab3" data-tab="3"><a href="javascript:;">YÊU CẦU LẤY HÀNG</a></li>
                    <li class="tab tab4" data-tab="4"><a href="javascript:;">TẠO ĐƠN HÀNG</a></li>
                    <li class="tab tab6" data-tab="6"><a href="javascript:;">TÌM KIẾM</a></li>
                    <li class="tab tab5" data-tab="5"><a href="javascript:;">TÀI KHOẢN</a></li>
                </ul>
                <?php // Nav bar right ?>
                <ul class="nav navbar-nav navbar-right">
                    <?php if ($user) : ?>

                        <li>
                            <a href="<?php echo base_url('logout'); ?>"><?php echo lang('core button logout'); ?></a>
                        </li>
                    <?php else : ?>
                        <li class="<?php echo (uri_string() == 'login') ? 'active' : ''; ?>">
                            <a href="<?php echo base_url('login'); ?>"><?php echo lang('core button login'); ?></a>
                        </li>
                    <?php endif; ?>
                    <li>
                          <span class="dropdown">

                              <ul id="session-language-dropdown" class="dropdown-menu" role="menu"
                                  aria-labelledby="session-language">
                                  <?php foreach ($this->languages as $key => $name) : ?>
                                      <li>
                                          <a href="#" rel="<?php echo $key; ?>">
                                              <?php if ($key == $this->session->language) : ?>
                                                  <i class="fa fa-check selected-session-language"></i>
                                              <?php endif; ?>
                                              <?php echo $name; ?>
                                          </a>
                                      </li>
                                  <?php endforeach; ?>
                              </ul>
                          </span>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
<?php endif; ?>


<?php // Main body ?>
<div class="container-fluid theme-showcase" role="main">

    <?php // Page title ?>
    <div class="page-header">
        <h1><?php echo $page_header; ?></h1>

        <div class="form-group" id="limit_geted">
            <label for="limit_geted">Hiển thị</label>
            <select name="limit_geted" id="limitpage">
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="all">Tất cả</option>
            </select>
        </div>
    </div>

    <?php // System messages ?>
    <?php if ($this->session->flashdata('message')) : ?>
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php echo $this->session->flashdata('message'); ?>
        </div>
    <?php elseif ($this->session->flashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php echo $this->session->flashdata('error'); ?>
        </div>
    <?php elseif (validation_errors()) : ?>
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php echo validation_errors(); ?>
        </div>
    <?php elseif ($this->error) : ?>
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php echo $this->error; ?>
        </div>
    <?php endif; ?>

    <?php // Main content ?>
    <?php echo $content; ?>

</div>

<?php if ($is_mobile == true): ?>
    <div class="footer-nav">

        <a class="active tab tab1" data-tab="1" href="javascript:;">
            <i class="fa fa-credit-card" aria-hidden="true"></i>
        </a>

<!--        <a class="tab tab7" data-tab="7" href="javascript:;">-->
<!--            <i class="fa fa-cart-arrow-down" aria-hidden="true"></i>-->
<!--        </a>-->

        <a class=" tab tab2" data-tab="2" href="javascript:;">
            <i class="fa fa-clock-o" aria-hidden="true"></i>
        </a>

        <a class=" tab tab3" data-tab="3" href="javascript:;">
            <i class="fa fa-archive" aria-hidden="true"></i>
        </a>


        <a class=" tab tab4" data-tab="4" href="javascript:;">
            <i class="fa fa-pencil" aria-hidden="true"></i>
        </a>

        <a class=" tab tab6" data-tab="6" href="javascript:;">
            <i class="fa fa-search" aria-hidden="true"></i>
        </a>

        <a class=" tab tab5" data-tab="5" href="javascript:;">
            <i class="fa fa-user" aria-hidden="true"></i>
        </a>
    </div>
<?php endif; ?>



<?php if (isset($js_files) && is_array($js_files)) : ?>
    <?php foreach ($js_files as $js) : ?>
        <?php if (!is_null($js)) : ?>
            <?php $separator = (strstr($js, '?')) ? '&' : '?'; ?>
            <?php echo "\n"; ?>
            <script type="text/javascript"
                    src="<?php echo $js; ?><?php echo $separator; ?>v=<?php echo $this->settings->site_version; ?>"></script><?php echo "\n"; ?>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>
<?php if (isset($js_files_i18n) && is_array($js_files_i18n)) : ?>
    <?php foreach ($js_files_i18n as $js) : ?>
        <?php if (!is_null($js)) : ?>
            <?php echo "\n"; ?>
            <script type="text/javascript"><?php echo "\n" . $js . "\n"; ?></script><?php echo "\n"; ?>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>

</body>

<script type="text/javascript">
    function formatNumber(nStr, decSeperate = ".", groupSeperate = ",") {
        nStr += '';
        x = nStr.split(decSeperate);
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + groupSeperate + '$2');
        }
        return x1 + x2;
    }

    function formatNumBerKeyUp(id_input) {
        key = "";
        money = $(id_input).val().replace(/[^\-\d\.]/g, '');
        a = money.split(" ");
        $.each(a, function (index, value) {
            key = key + value;
        });
        $(id_input).val(formatNumber(key, ' ', ','));
    }

    $(document).ready(function () {
        var activeTab = '4';
        $('.loading-page').hide();

        var isMobile = <?php echo $this->isAppMobile === true ? 'true' : 'false'?>;


        function TabNavMobile() {
            $('.tab').removeClass('active');
            $('.cover_tab').hide();
            $('#limit_geted').css('display', 'none');
            switch (activeTab) {
                case '1':
                    $('.tab1').addClass('active');
                    $('#tab1').show();
                    $('.page-header h1').text('Công Nợ Khách Hàng');
                    break;
                case '2':
                    $('.tab2').addClass('active');
                    $('#tab2').show();
                    $('.page-header h1').text('Quản Lý Đơn Hàng');
                    $('#limit_geted').css('display', 'block');
                    $('#limit_geted select').css('color', '#3fff00');
                    $("#limit_geted select").attr('id','limitpageTab2');
                    InitDataMobileTab2();
                    break;
                case '3':
                    $('.tab3').addClass('active');
                    $('#tab3').show();
                    $('.page-header h1').text('Yêu Cầu Lấy Hàng');
                    $('#limit_geted').css('display', 'block');
                    $("#limit_geted select").attr('id','limitpage');
                    if (isMobile == false) {
                        pickUpInit();
                    } else {
                        pickUpInitMobile();
                    }

                    break;
                case '4':
                    $('.tab4').addClass('active');
                    $('#tab4').show();
                    $('.page-header h1').text('Tạo Đơn Hàng');

                    if (isMobile == false) {
                        orderInit();
                    } else {
                        InitOrderMobile();
                    }

                    break;
                case '5':
                    $('.tab5').addClass('active');
                    $('#tab5').show();
                    $('.page-header h1').text('Tài Khoản');
                    initAccount();
                    break;
                case '6':
                    $('.tab6').addClass('active');
                    $('#tab6').show();
                    $('.page-header h1').text('Tìm kiếm');
                    $("ul#boxSearch").html("");
                    break;

                case '7':
                    $('.tab7').addClass('active');
                    $('#tab7').show();
                    $('.page-header h1').text('Quản lý đơn hàng');
                    initOrderManager();
                    break;
                default:
                // code block
            }
        }

        TabNavMobile();

        $('.tab').click(function () {
            activeTab = $(this).attr('data-tab');
            TabNavMobile();
        });
    });


</script>
<?php
if ($this->router->fetch_class() === 'app') {
    //Tab 1
    require_once('app_script.php');
    require_once('tab2_script.php');
    require_once('tab3_script.php');
    require_once('tab4_script.php');
    require_once('tab5_script.php');
}


?>


</html>
