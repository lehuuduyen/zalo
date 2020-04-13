<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2019-11-13 14:55:04 --> Could not find the language line "Tài chính"
ERROR - 2019-11-13 14:55:04 --> Could not find the language line "IMPORT DATA"
ERROR - 2019-11-13 14:55:04 --> Could not find the language line "LỊCH NHẮC"
ERROR - 2019-11-13 14:55:04 --> Could not find the language line "Thông báo qua zalo"
ERROR - 2019-11-13 14:55:04 --> Could not find the language line "Thông báo"
ERROR - 2019-11-13 14:55:04 --> Could not find the language line "Turndownload"
ERROR - 2019-11-13 14:55:04 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near '
        id,
        0 as debt
    FROM tblorders_shop where  tblorders_shop.sho' at line 17 - Invalid query: 
    SELECT
        date as date,
        code as code,
        note as note,
        if (type = 1, price, 0) as pstang,
        if (type = 0, price, 0) as psgiam,
        id as id,
        0 as debt
    FROM tblcash_book where  groups = 14 AND DATE_FORMAT(date,"%Y-%m-%d") >= "2019-10-14" AND DATE_FORMAT(date,"%Y-%m-%d") <= "2019-11-13" 
        UNION
    SELECT
        control_date as date,
        code_supership as code,
        status as note,
        (if((control_date != null ||  status = "Không Giao Được" || status = "Xác Nhận Hoàn" ||status = "Đang Trả Hàng" ||status = "Đang Chuyển Kho Trả" ||status = "Đã Đối Soát Trả Hàng" ||status = "Đã Chuyển Kho Trả"),0,collect)) as pstang,
        (IFNULL(pay_transport, 0) + IFNULL(insurance, 0) + IFNULL(pay_refund, 0) - IFNULL(sale, 0)) as psgiam, 
        ,
        id,
        0 as debt
    FROM tblorders_shop where  tblorders_shop.shop = (select tblcustomers.customer_shop_code from tblcustomers where tblcustomers.customer_shop_code = tblorders_shop.shop) AND control_date is not null AND DATE_FORMAT(control_date,"%Y-%m-%d") >= "2019-10-14" AND DATE_FORMAT(control_date,"%Y-%m-%d") <= "2019-11-13" order by date desc

