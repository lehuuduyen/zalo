<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2019-10-13 22:05:58 --> Could not find the language line "Tài chính"
ERROR - 2019-10-13 22:05:58 --> Could not find the language line "IMPORT DATA"
ERROR - 2019-10-13 22:05:58 --> Could not find the language line "LỊCH NHẮC"
ERROR - 2019-10-13 22:05:58 --> Could not find the language line "Thông báo qua zalo"
ERROR - 2019-10-13 22:05:58 --> Could not find the language line "Thông báo"
ERROR - 2019-10-13 22:05:58 --> Could not find the language line "Turndownload"
ERROR - 2019-10-13 22:05:58 --> Query error: Unknown column 'date_control' in 'where clause' - Invalid query: 
                (
                    SELECT 
                        id , 
                        date_debits AS date_create ,
                        DATE_FORMAT(date_create, "%Y-%m-%d") as created ,
                        status_debts ,
                        code_supership AS code_display ,
                        status , 
                        collect AS ps_in ,
                        hd_fee AS ps_de ,
                        note ,
                        mass ,
                        receiver ,
                        city ,
                        district 
                    FROM tblorders_shop 
                    WHERE shop = "S107432 - Vanbibi"
                         AND DATE_FORMAT(date_debits,"%Y-%m-%d") >= "2019-09-13 00:00:00" AND DATE_FORMAT(date_debits,"%Y-%m-%d") <= "2019-10-13 23:59:59"
                        AND status != "Huỷ"
                )
                UNION ALL
                (
                    SELECT 
                         id ,
                         date_control AS date_create ,
                         DATE_FORMAT(date, "%Y-%m-%d") as created ,
                         type AS status_debts ,
                         code AS code_display ,
                         status , price AS ps_in ,
                         price AS ps_de ,
                         note ,
                         mass ,
                         receiver ,
                         city , 
                         district 
                     FROM tblcash_book
                     WHERE id_object = "tblcustomers"
                          AND staff_id = "1662"
                          AND DATE_FORMAT(date_control,"%Y-%m-%d") >= "2019-09-13 00:00:00"AND DATE_FORMAT(date_control,"%Y-%m-%d") <= "2019-10-13 23:59:59"
               )
               UNION ALL
               (
                    SELECT 
                        id ,
                        date AS date_create ,
                        DATE_FORMAT(date_create, "%Y-%m-%d") as created ,
                        status_debts ,
                        code AS code_display ,
                        status ,
                        price AS ps_in ,
                        price AS ps_de , 
                        note , 
                        mass , 
                        receiver ,
                        city ,
                        district 
                    FROM tbldebit_object 
                    WHERE id_object = "tblcustomers" 
                        AND staff_id = "1662"
                        AND DATE_FORMAT(date_control,"%Y-%m-%d") >= "2019-09-13 00:00:00"AND DATE_FORMAT(date_control,"%Y-%m-%d") <= "2019-10-13 23:59:59"
               )
                    ORDER BY date_create DESC
                
ERROR - 2019-10-13 22:08:35 --> Could not find the language line "Tài chính"
ERROR - 2019-10-13 22:08:35 --> Could not find the language line "IMPORT DATA"
ERROR - 2019-10-13 22:08:35 --> Could not find the language line "LỊCH NHẮC"
ERROR - 2019-10-13 22:08:35 --> Could not find the language line "Thông báo qua zalo"
ERROR - 2019-10-13 22:08:35 --> Could not find the language line "Thông báo"
ERROR - 2019-10-13 22:08:35 --> Could not find the language line "Turndownload"
ERROR - 2019-10-13 22:08:35 --> Query error: Unknown column 'date_control' in 'where clause' - Invalid query: 
                (
                    SELECT 
                        id , 
                        date_debits AS date_create ,
                        DATE_FORMAT(date_create, "%Y-%m-%d") as created ,
                        status_debts ,
                        code_supership AS code_display ,
                        status , 
                        collect AS ps_in ,
                        hd_fee AS ps_de ,
                        note ,
                        mass ,
                        receiver ,
                        city ,
                        district 
                    FROM tblorders_shop 
                    WHERE shop = "S107432 - Vanbibi"
                         AND DATE_FORMAT(date_debits,"%Y-%m-%d") >= "2019-09-13 00:00:00" AND DATE_FORMAT(date_debits,"%Y-%m-%d") <= "2019-10-13 23:59:59"
                        AND status != "Huỷ"
                )
                UNION ALL
                (
                    SELECT 
                         id ,
                         date_control AS date_create ,
                         DATE_FORMAT(date, "%Y-%m-%d") as created ,
                         type AS status_debts ,
                         code AS code_display ,
                         status , price AS ps_in ,
                         price AS ps_de ,
                         note ,
                         mass ,
                         receiver ,
                         city , 
                         district 
                     FROM tblcash_book
                     WHERE id_object = "tblcustomers"
                          AND staff_id = "1662"
                          AND DATE_FORMAT(date_control,"%Y-%m-%d") >= "2019-09-13 00:00:00"AND DATE_FORMAT(date_control,"%Y-%m-%d") <= "2019-10-13 23:59:59"AND DATE_FORMAT(date,"%Y-%m-%d") <= "2019-10-13 23:59:59"
               )
               UNION ALL
               (
                    SELECT 
                        id ,
                        date AS date_create ,
                        DATE_FORMAT(date_create, "%Y-%m-%d") as created ,
                        status_debts ,
                        code AS code_display ,
                        status ,
                        price AS ps_in ,
                        price AS ps_de , 
                        note , 
                        mass , 
                        receiver ,
                        city ,
                        district 
                    FROM tbldebit_object 
                    WHERE id_object = "tblcustomers" 
                        AND staff_id = "1662"
                        AND DATE_FORMAT(date_control,"%Y-%m-%d") >= "2019-09-13 00:00:00"AND DATE_FORMAT(date_control,"%Y-%m-%d") <= "2019-10-13 23:59:59"AND DATE_FORMAT(date,"%Y-%m-%d") <= "2019-10-13 23:59:59"
               )
                    ORDER BY date_create DESC
                
