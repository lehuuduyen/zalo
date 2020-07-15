<?php

defined('BASEPATH') or exit('No direct script access allowed');

function app_init_admin_sidebar_menu_items()
{
    $CI = &get_instance();

    $CI->app_menu->add_sidebar_menu_item('dashboard', [
        'name'     => _l('als_dashboard'),
        'href'     => admin_url(),
        'position' => 1,
        'icon'     => 'fa fa-home',
    ]);

    if (has_permission('customers', '', 'view')
        || (have_assigned_customers()
        || (!have_assigned_customers() && has_permission('customers', '', 'create')))) {
        $CI->app_menu->add_sidebar_menu_item('menu_object', [
            'name'     => _l('object'),
            'href'     => '#',
            'position' => 2,
            'icon'     => 'fa fa-user-o',
        ]);
        $CI->app_menu->add_sidebar_menu_item('sales_reports', [
            'name'     => 'Báo Cáo',
            'href'     => 'javscript:;',
            'icon'     => 'fa fa-money menu-icon',
            'position' => 100,
        ]);

        $CI->app_menu->add_sidebar_menu_item('attrition', [
            'name'     => 'Tài sản cố định',
            'href'     => admin_url('reports_revenue/attrition'),
            'icon'     => 'fa fa-money menu-icon',
            'position' => 101,
        ]);
        // admin_url('sales_report_customer')
        //
        //
        $CI->app_menu->add_sidebar_children_item('sales_reports', [
            'slug'     => 'dtkh',
            'name'     =>  'Doanh Thu KH',
            'href'     => admin_url('sales_report_customer'),
            'position' => 100
        ]);



        $CI->app_menu->add_sidebar_children_item('sales_reports', [
            'slug'     => 'slkh',
            'name'     =>  'Sản Lượng  Khách Hàng',
            'href'     => admin_url('customer_output'),
            'position' => 100
        ]);
        $CI->app_menu->add_sidebar_children_item('menu_object', [
            'slug'     => 'customers',
            'name'     => _l('als_clients'),
            'href'     => admin_url('customer'),
            'position' => 5
        ]);
        // $CI->app_menu->add_sidebar_children_item('menu_object', [
        //     'slug'     => 'suppliers',
        //     'name'     => _l('als_supplier'),
        //     'href'     => admin_url('suppliers'),
        //     'position' => 5
        // ]);
        $CI->app_menu->add_sidebar_children_item('menu_object', [
            'slug'     => 'staff',
            'name'     => _l('als_staff'),
            'href'     => admin_url('staff'),
            'position' => 5,
        ]);
        $CI->app_menu->add_sidebar_menu_item('pick_up_points', [
            'name'     => 'Điểm Lấy - Trả',
            'href'     => admin_url('pick_up_points'),
            'position' => 5,
            'icon'     => 'fa fa-user-o',
        ]);

        $CI->app_menu->add_sidebar_menu_item('create_order', [
            'name'     => 'Đơn Hàng SPS',
            'href'     => admin_url('create_order'),
            'position' => 5,
            'icon'     => 'fa fa-user-o',
        ]);
        $CI->app_menu->add_sidebar_menu_item('create_order_ghtk', [
            'name'     => 'Đơn Hàng GHTK',
            'href'     => admin_url('create_order_ghtk'),
            'position' => 10,
            'icon'     => 'fa fa-window-restore',
        ]);

        $CI->app_menu->add_sidebar_menu_item('create_order_viettel', [
            'name'     => 'Đơn Hàng VPOST',
            'href'     => admin_url('create_order_viettel'),
            'position' => 10,
            'icon'     => 'fa fa-window-restore',
        ]);

        $CI->app_menu->add_sidebar_menu_item('change_order_ghtk', [
            'name'     => 'CẬP NHẬT ĐƠN HÀNG',
            'href'     => admin_url('change_order'),
            'position' => 10,
            'icon'     => 'fa fa-window-restore',
        ]);

		$CI->app_menu->add_sidebar_menu_item('create_order_bestinc', [
            'name'     => 'Đơn Hàng Best Inc',
            'href'     => admin_url('create_order_bestinc'),
            'position' => 10,
            'icon'     => 'fa fa-window-restore',
        ]);

        $CI->app_menu->add_sidebar_menu_item('convert_orders', [
            'name'     => 'ĐỔI ĐV VẬN CHUYỂN',
            'href'     => admin_url('convert_orders'),
            'position' => 10,
            'icon'     => 'fa fa-window-restore',
        ]);

		$CI->app_menu->add_sidebar_menu_item('confirm_order', [
            'name'     => 'DUYỆT ĐƠN HÀNG',
            'href'     => admin_url('confirm_order'),
            'position' => 10,
            'icon'     => 'fa fa-window-restore',
        ]);

//        $CI->app_menu->add_sidebar_children_item('menu_object', [
//            'slug'     => 'racks',
//            'name'     => _l('als_racks'),
//            'href'     => admin_url('racks'),
//            'position' => 5,
//        ]);
        $CI->app_menu->add_sidebar_children_item('menu_object', [
            'slug'     => 'other_object',
            'name'     => _l('als_other_object'),
            'href'     => admin_url('other_object'),
            'position' => 5,
        ]);
//        $CI->app_menu->add_sidebar_children_item('menu_object', [
//            'slug'     => 'porters',
//            'name'     => _l('als_porters'),
//            'href'     => admin_url('porters'),
//            'position' => 5,
//        ]);

        $CI->app_menu->add_sidebar_menu_item('pay', [
            'name'     => _l('Tài chính'),
            'href'     => '#',
            'position' => 6,
            'icon'     => 'fa fa-ticket menu-icon',
        ]);

        $CI->app_menu->add_sidebar_children_item('pay', [
            'slug'     => 'cash_book',
            'name'     => _l('cash_book'),
            'href'     => admin_url('cash_book'),
            'position' => 7
        ]);

        $CI->app_menu->add_sidebar_children_item('pay', [
            'slug'     => 'debit_object',
            'name'     => _l('debit_object'),
            'href'     => admin_url('debit_object'),
            'position' => 8
        ]);


//         $CI->app_menu->add_sidebar_children_item('pay', [
//            'slug'     => 'report_cod_sum',
//            'name'     =>  _l('Công nợ đối soát tiền hàng'),
//            'href'     => admin_url('report_cod_sum'),
//            'position' => 10
//        ]);

        $CI->app_menu->add_sidebar_children_item('pay', [
            'slug'     => 'debts',
            'name'     => _l('debts'),
            'href'     => admin_url('reports/debts'),
            'position' => 9
        ]);




//        $CI->app_menu->add_sidebar_menu_item('paymentmodes', [
//            'href'     => admin_url('paymentmodes'),
//            'name'     => _l('paymentmodes'),
//            'position' => 7,
//            'icon'     => 'fa fa-bank menu-icon',
//        ]);

        $CI->app_menu->add_sidebar_menu_item('customer_policy', [
            'name'     => _l('als_customer_policy'),
            'href'     => admin_url('customer_policy'),
            'position' => 11,
            'icon'     => 'fa fa-user-o',
        ]);

        $CI->app_menu->add_sidebar_menu_item('import_data', [
            'name'     => _l('IMPORT DATA'),
            'href'     => admin_url('import_data'),
            'position' => 60,
            'icon'     => 'fa fa-ticket menu-icon',
        ]);

        $CI->app_menu->add_sidebar_menu_item('notification', [
            'name'     => _l('LỊCH NHẮC'),
            'href'     => '#',
            'position' => 6,
            'icon'     => 'fa fa-ticket menu-icon',
        ]);

        $CI->app_menu->add_sidebar_children_item('notification', [
            'slug'     => 'notification_zalo',
            'name'     => _l('Thông báo qua zalo'),
            'href'     => admin_url('notification_zalo'),
            'position' => 9
        ]);

        $CI->app_menu->add_sidebar_children_item('notification', [
            'slug'     => '_notification',
            'name'     => _l('Thông báo'),
            'href'     => admin_url('notification'),
            'position' => 10
        ]);


        $modules_name = _l('modules');

        if ($modulesNeedsUpgrade = $CI->app_modules->number_of_modules_that_require_database_upgrade()) {
            $modules_name .= '<span class="badge menu-badge bg-warning">' . $modulesNeedsUpgrade . '</span>';
        }

        $CI->app_menu->add_setup_menu_item('modules', [
                    'href'     => admin_url('modules'),
                    'name'     => $modules_name,
                    'position' => 35,
            ]);

        $CI->app_menu->add_setup_menu_item('custom-fields', [
                    'href'     => admin_url('custom_fields'),
                    'name'     => _l('asc_custom_fields'),
                    'position' => 45,
            ]);

        $CI->app_menu->add_setup_menu_item('gdpr', [
                    'href'     => admin_url('gdpr'),
                    'name'     => _l('gdpr_short'),
                    'position' => 50,
            ]);

        $CI->app_menu->add_setup_menu_item('roles', [
                    'href'     => admin_url('roles'),
                    'name'     => _l('acs_roles'),
                    'position' => 55,
            ]);

        /*             $CI->app_menu->add_setup_menu_item('api', [
                                  'href'     => admin_url('api'),
                                  'name'     => 'API',
                                  'position' => 65,
                          ]);*/
    }












    $CI->app_menu->add_sidebar_menu_item('turndownload', [
        'href'     => base_url('turndownload'),
        'name'     => _l('Turndownload'),
        'position' => 99,
        'icon'     => 'fa fa-building',
    ]);

    $CI->app_menu->add_sidebar_menu_item('order', [
        'href'     => admin_url('order'),
        'name'     => _l('managa_orders'),
        'position' => 56,
        'icon'     => 'fa fa-building',
    ]);

    $CI->app_menu->add_sidebar_menu_item('returns', [
        'href'     => admin_url('returns'),
        'name'     => "TRẢ HÀNG SHOP",
        'position' => 56,
        'icon'     => 'fa fa-building',
    ]);
    $CI->app_menu->add_sidebar_menu_item('imports', [
        'href'     => admin_url('imports'),
        'name'     => "NHẬP HÀNG HOÀN",
        'position' => 56,
        'icon'     => 'fa fa-building',
    ]);
    $CI->app_menu->add_sidebar_menu_item('orders', [
        'href'     => admin_url('import_data/view'),
        'name'     => _l('managa_orders'),
        'position' => 100,
        'icon'     => 'fa fa-building',
    ]);

    // $CI->app_menu->add_sidebar_menu_item('shipping', [
    //     'href'     => admin_url('shipping'),
    //     'name'     => _l('managa_shipping'),
    //     'position' => 101,
    //     'icon'     => 'fa fa-cubes',
    // ]);

    // $CI->app_menu->add_sidebar_menu_item('shipping_orders', [
    //     'href'     => admin_url('shipping_orders'),
    //     'name'     => _l('code_shipping'),
    //     'position' => 102,
    //     'icon'     => 'fa fa-flag-checkered',
    // ]);













    if (has_permission('settings', '', 'view')) {
        $CI->app_menu->add_setup_menu_item('settings', [
                    'href'     => admin_url('settings'),
                    'name'     => _l('acs_settings'),
                    'position' => 200,
            ]);
    }

    if (has_permission('email_templates', '', 'view')) {
        $CI->app_menu->add_setup_menu_item('email-templates', [
                    'href'     => admin_url('emails'),
                    'name'     => _l('acs_email_templates'),
                    'position' => 40,
            ]);
    }
}

if(!function_exists('pre')){
    function pre($list, $exit = true)
    {
        echo '<pre>';
        print_r($list);
        if ($exit) {
            die();
        }
    }
}
