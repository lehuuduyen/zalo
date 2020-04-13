<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_dataTable_order extends CI_Model
{

    /**
     * @vars
     */
    private $_db;


    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
    }

    public function Datatables($dt = '')
    {
        $start = $dt['start'];
        $length = $dt['length'];
        $columnd = array('tbl_create_order.id',
            'created',
            'product',
            'required_code',
            'customer_id',
            'code',
            'supership_value',
            'amount',
            'name',
            'phone',
            'sphone',
            'address',
            'commune',
            'district',
            'province',
            'cod',
            'weight',
            'volume',
            'soc',
            'note',
            'service',
            'config',
            'payer',
            'barter',
            'value',
            'user_created',
            'tblstaff.firstname',
            'status_cancel',
        );

        if ($dt['mobile']) {
            // get data SPS
            $this->db->select($columnd);
            $this->db->from('tbl_create_order');
            $this->db->where('customer_id', $dt['id_customer']);
            $this->db->where('dvvc', null);
			$this->db->where('status_cancel', 0);
            if ($dt['filter']) {

                $this->db->where('created <', date('Y-m-d', strtotime(str_replace('-', '-', $dt['date_end_customer_order']))));
                $this->db->where('created >', date('Y-m-d', strtotime(str_replace('-', '-', $dt['date_start_customer_order']))));
                if ($dt['province_filter'] != 'null') {
                    $this->db->where('province', $dt['province_filter']);
                    if ($dt['district_filter'] != 'null') {
                        $this->db->where('district', $dt['district_filter']);
                    }
                }
            }
            $this->db->join('tblstaff', 'tblstaff.staffid = tbl_create_order.user_created', 'left');
            $this->db->limit(50);
            $this->db->order_by('id', 'DESC');

            $list = $this->db->get()->result();

            echo json_encode(array('list' => $list));
            die();
        }


        // SPS
        $this->db->select($columnd);
        $this->db->from('tbl_create_order');
        $this->db->where('customer_id', $dt['id_customer']);
        $this->db->where('dvvc', null);
		$this->db->where('status_cancel', 0);
        if ($dt['filter']) {
            $this->db->where('created <', date('Y-m-d', strtotime(str_replace('-', '-', $dt['date_end_customer_order']))));
            $this->db->where('created >', date('Y-m-d', strtotime(str_replace('-', '-', $dt['date_start_customer_order']))));
            if ($dt['province_filter'] != 'null') {
                $this->db->where('province', $dt['province_filter']);
                if ($dt['district_filter'] != 'null') {
                    $this->db->where('district', $dt['district_filter']);
                }
            }
        }

        $this->db->join('tblstaff', 'tblstaff.staffid = tbl_create_order.user_created', 'left');
        $rowCount = $this->db->count_all_results();

        $this->db->select($columnd);
        $this->db->from('tbl_create_order');
        $this->db->where('customer_id', $dt['id_customer']);
        $this->db->where('dvvc', null);
		$this->db->where('status_cancel', 0);
        if ($dt['filter']) {
            $this->db->where('created <', date('Y-m-d', strtotime(str_replace('-', '-', $dt['date_end_customer_order']))));
            $this->db->where('created >', date('Y-m-d', strtotime(str_replace('-', '-', $dt['date_start_customer_order']))));
            if ($dt['province_filter'] != 'null') {
                $this->db->where('province', $dt['province_filter']);
                if ($dt['district_filter'] != 'null') {
                    $this->db->where('district', $dt['district_filter']);
                }
            }
        }

        $this->db->join('tblstaff', 'tblstaff.staffid = tbl_create_order.user_created', 'left');
        $this->db->limit($length, $start);
        $this->db->order_by('id', 'DESC');

        $list = $this->db->get()->result();

        $option['draw'] = $dt['draw'];
        $option['recordsTotal'] = $rowCount;
        $option['recordsFiltered'] = $rowCount;
        $option['data'] = array();

        $count_c = sizeof($columnd);

        $list = json_decode(json_encode($list), true);

        foreach ($list as $row) {

            $rows = array();

            for ($i = 0; $i < $count_c; $i++) {

                if ($columnd[$i] == 'tbl_create_order.id') {
                    $rows[] = $row['id'];
                } else if ($columnd[$i] == 'tblstaff.firstname') {
                    $rows[] = $row['firstname'];

                } else if ($columnd[$i] == 'tblcustomers.customer_shop_code as customer_shop_code') {
                    $rows[] = $row['customer_shop_code'];

                } else if ($columnd[$i] == 'supership_value') {
                    $rows[] = $this->number_format_data($row[$columnd[$i]]);
                } else if ($columnd[$i] == 'cod') {
                    $rows[] = $this->number_format_data($row[$columnd[$i]]);
                } else if ($columnd[$i] == 'amount') {
                    $rows[] = $this->number_format_data($row[$columnd[$i]]);
                } else if ($columnd[$i] == 'weight') {
                    $rows[] = $this->number_format_data($row[$columnd[$i]]);
                } else if ($columnd[$i] == 'volume') {
                    $rows[] = $this->number_format_data($row[$columnd[$i]]);
                } else if ($columnd[$i] == 'value') {
                    $rows[] = $this->number_format_data($row[$columnd[$i]]);
                } else if ($columnd[$i] == 'barter') {
                    if ($row[$columnd[$i]] === '1') {
                        $rows[] = "có";
                    } else {
                        $rows[] = "không";
                    }
                } else if ($columnd[$i] == 'service') {
                    if ($row[$columnd[$i]] === '1') {
                        $rows[] = "Tốc Hành";
                    } else {
                        $rows[] = "Tiết Kiệm";
                    }
                } else if ($columnd[$i] == 'config') {
                    if ($row[$columnd[$i]] === '1') {
                        $rows[] = "Cho Xem Hàng Nhưng Không Cho Thử Hàng";
                    } else if ($row[$columnd[$i]] === '2') {
                        $rows[] = "Cho Thử Hàng";
                    } else {
                        $rows[] = "Không Cho Xem Hàng";
                    }
                } else if ($columnd[$i] == 'payer') {
                    if ($row[$columnd[$i]] === '1') {
                        $rows[] = "Người Gửi";
                    } else {
                        $rows[] = "Người Nhận";
                    }
                } else {
                    $rows[] = $row[$columnd[$i]];
                }
            }

            $icon_delete = '<a href="javascript:;" data-id= "' . $rows[0] . '" class="btn btn-danger delete-reminder-custom-order btn-icon">
        <i class="fa fa-remove"></i>
        </a>';
            $rows[] = $icon_delete . '</div>';

            $rows[9] = $rows[9] . ', ' . $rows[10] . ', ' . $rows[11] . ', ' . $rows[12];

            if ($rows[27] == '1') {
                $rows[26] = "<span style='color:red;font-weight:bold'> Đã Hủy </span>";
            }
            $option['data'][] = $rows;

        }

        // eksekusi json

        echo json_encode($option);
        die();


    }


    function number_format_data($number, $type = true)
    {

        if (!empty($number)) {
            $number = str_replace(",", "", $number);
            $number = str_replace(",", "", number_format($number, 3));
            $_number = explode('.', $number);
            if (rtrim($_number[1], '0') != "") {
                if ($type) {
                    return ($_number[0] === '-0' ? '-' : '') . number_format($_number[0]) . '.' . rtrim($_number[1], '0');
                }
                return $_number[0] . '.' . rtrim($_number[1], '0');
            } else {
                if ($type) {
                    return ($_number[0] === '-0' ? '-' : '') . number_format($_number[0]);
                }
                return $_number[0];
            }
        } else {
            return $number;
        }
    }


}
