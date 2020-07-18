<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Api_order_update extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function update_is_hd_branch()
    {
        $this->load->model('orders_shop_model');
        $rows = $this->orders_shop_model->update_is_hd_branch();

        if ($rows) {
            die('Done');
        }
        die('Error');
    }

    public function upgrade_api_v2()
    {
        $this->load->model('orders_shop_model');
        $data = $this->orders_shop_model->get_orders_used_api();
        $data_update = [];
        $code_errors = 0;
        foreach ($data as $k => $v) {
            $data_replace = [
                'id' => $v['id'],
            ];
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.mysupership.vn/v1/partner/orders/info?code='.$v['code_supership'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => [
                    'Accept: */*',
                    'Content-Type: application/json',
                ],
            ]);
            $response = curl_exec($curl);

            if (! empty($response)) {
                $response = json_decode($response);

                if ($response->status == 'Success') {
                    $result = $response->results;
                    $key_journey = null;

                    if (! empty($result->journeys)) {
                        $data_replace['last_time_updated'] = $result->journeys[count($result->journeys) - 1]->time > $result->updated_at ? $result->journeys[count($result->journeys) - 1]->time : $result->updated_at;
                        foreach ($result->journeys as $key => $value) {
                            if (strpos($value->status, 'Đối Soát') !== false) {
                                $key_journey = $key;
                            }
                        }
                    } else {
                        $data_replace['last_time_updated'] = $result->updated_at;
                    }

                    if (! empty($key_journey)) {
                        $data_replace['control_date'] = $result->journeys[$key_journey]->time;
                        $data_replace['status'] = $result->journeys[$key_journey]->status;
                    } else {
                        $data_replace['control_date'] = null;
                        $data_replace['status'] = $result->status_name;
                    }
                    $data_replace['address'] = $result->receiver->address;
                    $addressArray = explode(',', $result->receiver->formatted_address);
                    $data_replace['city'] = $addressArray[count($addressArray) - 1];
                    $data_replace['district'] = $addressArray[count($addressArray) - 2];
                    $data_replace['collect'] = $result->amount;
                    $data_replace['mass'] = $result->weight;
                    $data_replace['value'] = $result->value;

                    if ($result->fee) {
                        $data_replace['pay_transport'] = $result->fee->shipment;
                        $data_replace['insurance'] = $result->fee->insurance;
                        $data_replace['pay_refund'] = $result->fee->return;
                    }

                    if (empty($v['date_debits'])) {
                        $date_debits = null;
                        foreach ($result->journeys as $key => $value) {
                            if ($value->status == 'Đã Đối Soát Giao Hàng') {
                                $date_debits = $value->time;
                            } elseif ($value->status == 'Đã Đối Soát Trả Hàng' && $date_debits != 'Đã Đối Soát Giao Hàng') {
                                $date_debits = $value->time;
                            } elseif ($value->status == 'Đã Giao Hàng Toàn Bộ' && $date_debits != 'Đã Đối Soát Giao Hàng' && $date_debits != 'Đã Đối Soát Trả Hàng') {
                                $date_debits = $value->time;
                            } elseif ($value->status == 'Đã Giao Hàng Một Phần' && $date_debits == null) {
                                $date_debits = $value->time;
                            }
                        }
                        $data_replace['date_debits'] = $date_debits;
                    }

                    if ($result->status_name === 'Hoãn Giao Hàng') {
                        $key_journey_hoan = null;
                        foreach ($result->journeys as $key => $value) {
                            if ($value->status !== 'Hoãn Giao Hàng') {
                                $key_journey_hoan = $key;
                            }
                        }
                        $data_replace['delivery_delay_time'] = $result->journeys[$key_journey_hoan]->time;
                    } else {
                        $data_replace['delivery_delay_time'] = null;
                    }

                    if ($data_replace['status'] == 'Huỷ') {
                        if ($v['status'] == 'Chờ Lấy Hàng' || $v['status'] == 'Đã Nhập Kho') {
                            array_push($data_update, $data_replace);
                        } else {
                            $data_error = ['code_sps' => $v['code_supership'], 'type_error' => 'Huỷ Nhưng không phải Chờ Lấy Hàng hoặc Đã Nhập Kho'];
                            $this->add_to_error($data_error);
                            $code_errors++;
                        }
                    } else {
                        array_push($data_update, $data_replace);
                    }
                } else {
                    $data_error = ['code_sps' => $v['code_supership'], 'type_error' => json_decode($response)->errors];
                    $status_replace = $this->add_to_error($data_error);
                    $code_errors++;
                }
            }
        }

        if (! empty($data_update)) {
            $rows = $this->orders_shop_model->update_batch($data_update);
            echo "Đã quét số bản ghi: {count($data)} bản ghi </br>";
            echo "Số bản ghi thay đổi: {$rows} bản ghi </br>";
            echo "Số bản ghi cập nhật ở api_error: {$code_errors} bản ghi \n";
            die();
        }
        die('Không có bản ghi nào cần cập nhật');
    }

    // public function index2() {
    //
    //   ini_set('max_execution_time', 99999999);
    //   error_reporting(E_ALL);
    //   ini_set('display_errors', 'On');
    //   ini_set('memory_limit', '-1');
    //   $this->get_api_replace('HDGS417267NM.1064576' , 4236 , NULL);
    //    die();
    //   $this->db->select('code_supership , control_date , id , date_debits , hd_fee');
    //   $this->db->from('tblorders_shop');
    //   $this->db->where('control_date !=' , NULL);
    //   $this->db->where('code_supership !=' , NULL);
    //   $this->db->where('date_debits' , NULL);
    //   $q = $this->db->get()->result();
    //   // var_dump($q);
    //   // die();
    //
    //   $total = 0;
    //
    //
    //
    //   foreach ($q as $key => $value) {
    //
    //     $sta = $this->get_api_replace($value->code_supership , $value->id , $value->date_debits);
    //
    //
    //     if ($sta) {
    //       $total = $total + 1;
    //     }else {
    //       $total = $total;
    //     }
    //   }
    //
    //
    //   echo "Đã quét xong " + strval($total) +  "đơn hàng";
    //
    // }

    public function all()
    {
        $this->db->select('code_supership , control_date , id , date_debits, status , shop , city_send , value , city , district , mass');
        $this->db->from('tblorders_shop');
        $q = $this->db->get()->result();

        $total = 0;
        //
        foreach ($q as $key => $value) {
            $sta = $this->get_api_replace2($value->code_supership, $value->id, $value->date_debits, $value->shop);

            if ($sta) {
                $total = $total + 1;
            } else {
                $total = $total;
            }
        }

        echo 'Đã quét xong ' + strval($total) + 'đơn hàng';
    }

    public function index()
    {
        $this->load->model('orders_change_weight_model');
        // $this->get_api_replace('HNIS781286NM.1619178', 24007, null);
        // die();
        $date = date('Y-m-d');
        $this->db->select('code_supership , control_date , id , date_debits, status , shop , city_send , value , city , district , mass,mass_fake, collect');

        $this->db->from('tblorders_shop');
        // $this->db->where('code_supership !=' , NULL);
        $this->db->where('status !=', 'Đã Đối Soát Giao Hàng');
        $this->db->where('status !=', 'Đã Trả Hàng');
		$this->db->where('status !=', 'Đã Trả Hàng Toàn Bộ');
        $this->db->where('status !=', 'Đã Trả Hàng Một Phần');
        // $this->db->where('date_debits' , NULL);
        $this->db->where('status !=', 'Huỷ');
        $this->db->where('DVVC', 'SPS');
        $this->db->where('DATE_FORMAT(date_create, "%Y-%m-%d") >= "'.date('Y-m-d', strtotime($date.'- 90 days')).'"');
		
		if($this->input->get('test')){
			$this->db->limit(50, 0);
		}
		
        $q = $this->db->get()->result();
// pre($q);
        $total = 0;

        foreach ($q as $key => $value) {
            $sta = $this->get_api_replace($value->code_supership, $value->id, $value->date_debits, $value->shop, $value->status, $value->mass, $value->collect);

            // echo $sta
            // echo $value->code_supership;

            if ($sta) {
                $total = $total + 1;
            } else {
                $total = $total;
            }
        }

        echo 'Đã quét xong ' + strval($total) + 'đơn hàng';
    }

    public function check_po1($data)
    {
        $match = false;
        foreach ($data as $key => $value) {
            if ($value->status == 'Đã Đối Soát Giao Hàng') {
                $match = $value->time;
            }
        }

        return $match;
    }

    public function check_po2($data)
    {
        $match = false;
        foreach ($data as $key => $value) {
            if ($value->status == 'Đã Đối Soát Trả Hàng') {
                $match = $value->time;
            }
        }

        return $match;
    }

    public function check_po3($data)
    {
        $match = false;
        foreach ($data as $key => $value) {
            if ($value->status == 'Đã Giao Hàng Toàn Bộ') {
                $match = $value->time;
            }
        }

        return $match;
    }

    public function check_po4($data)
    {
        $match = false;
        foreach ($data as $key => $value) {
            if ($value->status == 'Đã Giao Hàng Một Phần') {
                $match = $value->time;
            }
        }

        return $match;
    }

    public function checkCustomer($shop = '')
    {
        $this->db->select('id');
        $this->db->from('tblcustomers');
        $this->db->where('customer_shop_code', $shop);
        $q = $this->db->get()->row();

        if ($q) {
            return true;
        }

        return false;
    }

    public function calc_fee_postage($sender_province = '', $sender_district = '', $receiver_province = '', $receiver_district = '', $weight = '', $value = '')
    {
    }

    public function get_api_replace2($code = '', $id = '', $date_debits, $shop = '')
    {
        $is_hd_branch = 0;

        if ($this->checkCustomer($shop)) {
            $is_hd_branch = 1;
        }
        $data_replace['is_hd_branch'] = $is_hd_branch;

        $status_replace = $this->db->update('tblorders_shop', $data_replace, 'id ='.$id);

        return $status_replace;
    }

    public function get_api_replace($code = '', $id = '', $date_debits, $shop = '', $status_before, $weight, $amount)
    {
        $is_hd_branch = false;

        if ($this->checkCustomer($shop)) {
            $is_hd_branch = true;
        }


        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.mysupership.vn/v1/partner/orders/info?code={$code}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'Accept: */*',
                'Content-Type: application/json',
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo $code;
            echo '<br/>';
            echo 'cURL Error #:'.$err;
            echo '<br/>';
        } else {
            $return = false;
            $result = json_decode($response)->results;

            if ($result) {
                $key_journey = null;
                //Taij sao comment code o day https://prnt.sc/q1nt8w

                // if (strpos(mb_strtolower($result->status_name), 'đã đối soát') !== false) {
                //     foreach ($result->journeys as $key => $value) {
                //         if (strpos($value->status, $result->status_name) !== false) {
                //             $key_journey = $key;
                //         }
                //     }
                // }

                foreach ($result->journeys as $key => $value) {
                    if (strstr($value->status, 'Đối Soát')) {
                        $key_journey = $key;
                    }
                }

                if ($key_journey) {
                    $data_replace['control_date'] = $result->journeys[$key_journey]->time;
                } else {
                    $data_replace['control_date'] = null;
                }

                $data_replace['status'] = $result->status_name;
                $data_replace['address'] = $result->receiver->address;
                $addressArray = explode(',', $result->receiver->formatted_address);
                $data_replace['city'] = $addressArray[count($addressArray) - 1];
                $data_replace['district'] = $addressArray[count($addressArray) - 2];
                $data_replace['collect'] = $result->amount;
				$data_replace['mass_fake'] = $result->weight;

                if ($result->weight > $weight && $is_hd_branch) {
                    $data_insert = [
                        'order_shop_id' => $id,
                        'shop_name' => $shop,
                        'code' => $code,
                        'old_weight' => $weight,
                        'new_weight' => $result->weight,
                        'created_date' => date('Y-m-d H:i:s'),
                    ];
                    $this->orders_change_weight_model->insert($data_insert);

                    $data_replace['mass'] = $result->weight;
                }

                if ($result->amount != $amount) {
                    $data_insert_money = [
                        'order_shop_id' => $id,
                        'shop_name' => $shop,
                        'code' => $code,
                        'old_money' => $amount,
                        'new_money' => $result->amount,
                        'created_date' => date('Y-m-d H:i:s'),
                    ];
                    $this->orders_change_weight_model->insert_money($data_insert_money);
                }

                $data_replace['value'] = $result->value;
//                $data_replace['is_hd_branch'] = $is_hd_branch;

                if ($result->fee) {
                    $data_replace['pay_transport'] = $result->fee->shipment;
                    $data_replace['insurance'] = $result->fee->insurance;
                    $data_replace['pay_refund'] = $result->fee->return;
                }

                if (! empty($result->journeys)) {
                    $data_replace['last_time_updated'] = $result->journeys[count($result->journeys) - 1]->time > $result->updated_at ? $result->journeys[count($result->journeys) - 1]->time : $result->updated_at;
                } else {
                    $data_replace['last_time_updated'] = $result->updated_at;
                }

                if ($date_debits == null) {
                    $po_1 = $this->check_po1($result->journeys);
                    $po_2 = $this->check_po2($result->journeys);
                    $po_3 = $this->check_po3($result->journeys);
                    $po_4 = $this->check_po4($result->journeys);

                    if ($po_1) {
                        $po = $po_1;
                        $data_replace['date_debits'] = $po;
                    } elseif ($po_1 === false && $po_2) {
                        $po = $po_2;
                        $data_replace['date_debits'] = $po;
                    } elseif ($po_1 === false && $po_2 === false && $po_3) {
                        $po = $po_3;
                        $data_replace['date_debits'] = $po;
                    } elseif ($po_1 === false && $po_2 === false && $po_3 === false && $po_4) {
                        $po = $po_4;
                        $data_replace['date_debits'] = $po;
                    } else {
                        $data_replace['date_debits'] = null;
                    }
                }

                if ($result->status_name === 'Hoãn Giao Hàng') {
                    $key_journey_hoan = null;
                    foreach ($result->journeys as $key => $value) {
                        if ($value->status !== 'Hoãn Giao Hàng') {
                            $key_journey_hoan = $key;
                        }
                    }
                    $data_replace['delivery_delay_time'] = $result->journeys[$key_journey_hoan]->time;
                } else {
                    $data_replace['delivery_delay_time'] = null;
                }

                if ($data_replace['status'] == 'Huỷ') {
                    if ($status_before == 'Chờ Lấy Hàng' || $status_before == 'Đã Nhập Kho') {
                        $status_replace = $this->db->update('tblorders_shop', $data_replace, 'id ='.$id);
                    } else {
                        $data_error = ['code_sps' => $code, 'type_error' => 'Huỷ Nhưng không phải Chờ Lấy Hàng hoặc Đã Nhập Kho'];
                        $status_replace = $this->add_to_error($data_error);
                    }
                } else {
                    $status_replace = $this->db->update('tblorders_shop', $data_replace, 'id ='.$id);
                }

                if ($status_replace) {
                    $return = true;
                } else {
                    $return = false;
                }

                return $return;
            }

            //save to error
            // $status_replace = $this->db->update('tblorders_shop', array('status' => "Huỷ" ), "id =".$id);

            $data_error = ['code_sps' => $code, 'type_error' => json_decode($response)->errors];

            $status_replace = $this->add_to_error($data_error);

            if ($status_replace) {
                $return = true;
            } else {
                $return = false;
            }

            return $return;
        }
    }

    public function add_to_error($data = '')
    {
        $query = $this->db->get_where('tbl_api_error', ['code_sps' => $data['code_sps']])->row();

        if ($query) {
            $this->db->where('code_sps', $data['code_sps']);
            $return = $this->db->update('tbl_api_error', $data);
        } else {
            $return = $this->db->insert('tbl_api_error', $data);
        }

        return $return;
    }

    public function replaceDataCode_Super_ship($id, $value)
    {
        $this->db->set('hd_fee', $value);
        $this->db->where('id', $id);
        $update = $this->db->update('tblorders_shop');

        return $update;
    }

    public function replaceDataCode_Super_ship_stam($id, $value)
    {
        $this->db->set('hd_fee_stam', $value);
        $this->db->where('id', $id);
        $update = $this->db->update('tblorders_shop');

        return $update;
    }

    public function get_province()
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.mysupership.vn/v1/partner/areas/province',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'Accept: */*',
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo 'cURL Error #:'.$err;
        } else {
            if ($this->input->is_ajax_request()) {
                echo json_encode(json_decode($response)->results);
            }

            $result = json_decode($response)->results;

            return $result;
        }
    }

    public function get_district_by_hd($code)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.mysupership.vn/v1/partner/areas/district?province='.$code,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'Accept: */*',
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
        } else {
            $result = json_decode($response)->results;

            if ($this->input->is_ajax_request()) {
                echo json_encode($result);
                die();
            }

            return $result;
        }
    }

    public function SearchTextRegion($province, $city)
    {
        foreach ($province as $key => $value) {
            if ($value->name === trim($city)) {
                return $value;
            }
        }
    }

    public function SearchTextDistrict($district_list, $district)
    {
        foreach ($district_list as $key => $value) {
            if ($value->name === trim($district)) {
                return $value;
            }
        }
    }

    public function get_policy_id($id)
    {
        $this->db->select('*');
        $this->db->where('customer_id', $id);
        $search_result = $this->db->get('tblcustomer_policy')->row();

        if (count($search_result) === 0) {
            return false;
        }

        return $search_result->id;

        //
    }

    public function search_region($province, $district, $policy_id)
    {
        $region = null;
        $this->db->select('*');
        $this->db->where('city', trim($province));
        $this->db->where('district', trim($district));
        $search_result = $this->db->get('tblregion_excel')->row();

        if ($search_result !== null) {
            $this->db->select('*');
            $this->db->where('id', $search_result->region_id);
            $region = $this->db->get('tbldeclared_region')->result()[0];

            $this->db->select('*');
            $this->db->where('id_policy', $policy_id);
            $this->db->where('id_region', $region->id);
            $data_region = $this->db->get('tbldata_region')->row();
            $region->data_region = $data_region;
        }

        return $region;
    }

    public function searchForCode($code_supership, $tbl_create_order)
    {
        foreach ($tbl_create_order as $key => $val) {
            if ($val->code == $code_supership) {
                return $key;
            }
        }
    }

    public function checkAndReplaceStam($code_supership = '', $tbl_create_order, $id_update, $full_data_true)
    {
        $key_or_null = $this->searchForCode($code_supership, $tbl_create_order);

        if ($key_or_null != null) {
            $this->replaceDataCode_Super_ship_stam($id_update, $tbl_create_order[$key_or_null]->supership_value);
        } else {
            $province = $full_data_true->city;
            $district = $full_data_true->district;
            $mass = $full_data_true->mass;
            $total = (int) $full_data_true->collect;
            $value = (int) $full_data_true->value;

            $id = null;

            if ($this->get_customer_id($full_data_true->shop) !== null) {
                $id = $this->get_customer_id($full_data_true->shop)->id;
            }

            if ($id !== null) {
                $policy_id = $this->get_policy_id($id);
            } else {
                $policy_id = false;
            }

            if ($policy_id === false) {
                $region = $this->search_region($province, $district, $policy_id);

                $data_for_calc = $region;

                $supership_cost = (float) $data_for_calc->price_region;

                $massInput = (int) $mass;

                $massFree = (int) ($data_for_calc->mass_region);

                $masscalc = ($massInput - $massFree) / (int) $data_for_calc->mass_region_free;

                if ($masscalc <= 0) {
                    $masscalc = 0;
                } else {
                    $masscalc = ceil($masscalc) * (int) $data_for_calc->price_over_mass_region;
                }

                $volumecalc = 0;

                if ($masscalc > $volumecalc) {
                    $supership_cost += $masscalc;
                } elseif ($masscalc < $volumecalc) {
                    $supership_cost += $volumecalc;
                } else {
                    $supership_cost += $masscalc;
                }
                //Tính bảo hiểm
                if ($total > $value) {
                    if ($total > (int) $data_for_calc->amount_of_free_insurance) {
                        $insurance = ($total / 100) * (int) $data_for_calc->insurance_price;
                        $insurance = round($insurance / 1000) * 1000;
                    } else {
                        $insurance = 0;
                    }
                } else {
                    if ($value > (int) $data_for_calc->amount_of_free_insurance) {
                        $insurance = ($value / 100) * (int) $data_for_calc->insurance_price;
                        $insurance = round($insurance / 1000) * 1000;
                    } else {
                        $insurance = 0;
                    }
                }

                $supership_cost = $supership_cost + $insurance;

                $replaceCalc = $this->replaceDataCode_Super_ship_stam($id_update, $supership_cost);
            } else {
                $region = $this->search_region($province, $district, $policy_id);

                $data_for_calc = null;

                if ($region !== null) {
                    $data_for_calc = $region->data_region;
                }

                if ($data_for_calc !== null) {
                    $supership_cost = (float) $data_for_calc->price_region;

                    //Tính khối lượng
                    $massInput = (int) $mass;

                    $massFree = (int) ($data_for_calc->mass_region);

                    $masscalc = ($massInput - $massFree) / (int) $data_for_calc->mass_region_free;

                    if ($masscalc <= 0) {
                        $masscalc = 0;
                    } else {
                        $masscalc = ceil($masscalc) * (int) $data_for_calc->price_over_mass_region;
                    }

                    $volumecalc = 0;

                    if ($masscalc > $volumecalc) {
                        $supership_cost += (float) $masscalc;
                    } elseif ($masscalc < $volumecalc) {
                        $supership_cost += (float) $volumecalc;
                    } else {
                        $supership_cost += (float) $masscalc;
                    }

                    //Tính bảo hiểm
                    if ($total > $value) {
                        if ($total > (int) $data_for_calc->amount_of_free_insurance) {
                            $insurance = ($total / 100) * (int) $data_for_calc->insurance_price;
                            $insurance = round($insurance / 1000) * 1000;
                        } else {
                            $insurance = 0;
                        }
                    } else {
                        if ($value > (int) $data_for_calc->amount_of_free_insurance) {
                            $insurance = ($value / 100) * (int) $data_for_calc->insurance_price;
                            $insurance = round($insurance / 1000) * 1000;
                        } else {
                            $insurance = 0;
                        }
                    }

                    $supership_cost = $supership_cost + $insurance;

                    $replaceCalc = $this->replaceDataCode_Super_ship_stam($id_update, $supership_cost);
                } else {
                    $replaceCalc = $this->replaceDataCode_Super_ship_stam($id_update, 0);
                }
            }
        }
    }

    public function checkAndReplace($code_supership = '', $tbl_create_order, $id_update, $full_data_true)
    {
        $key_or_null = $this->searchForCode($code_supership, $tbl_create_order);

        if ($key_or_null != null) {
            $this->replaceDataCode_Super_ship($id_update, $full_data_true->hd_fee_stam);
        // $this->replaceDataCode_Super_ship($id_update , $tbl_create_order[$key_or_null]->supership_value);
        } else {
            $province = $full_data_true->city;
            $district = $full_data_true->district;
            $mass = $full_data_true->mass;
            $total = (int) $full_data_true->collect;
            $value = (int) $full_data_true->value;

            $id = null;

            if ($this->get_customer_id($full_data_true->shop) !== null) {
                $id = $this->get_customer_id($full_data_true->shop)->id;
            }

            if ($id !== null) {
                $policy_id = $this->get_policy_id($id);
            } else {
                $policy_id = false;
            }

            if ($policy_id === false) {
                $region = $this->search_region($province, $district, $policy_id);

                $data_for_calc = $region;

                $supership_cost = (float) $data_for_calc->price_region;

                $massInput = (int) $mass;

                $massFree = (int) ($data_for_calc->mass_region);

                $masscalc = ($massInput - $massFree) / (int) $data_for_calc->mass_region_free;

                if ($masscalc <= 0) {
                    $masscalc = 0;
                } else {
                    $masscalc = ceil($masscalc) * (int) $data_for_calc->price_over_mass_region;
                }

                $volumecalc = 0;

                if ($masscalc > $volumecalc) {
                    $supership_cost += $masscalc;
                } elseif ($masscalc < $volumecalc) {
                    $supership_cost += $volumecalc;
                } else {
                    $supership_cost += $masscalc;
                }
                //Tính bảo hiểm
                if ($total > $value) {
                    if ($total > (int) $data_for_calc->amount_of_free_insurance) {
                        $insurance = ($total / 100) * (int) $data_for_calc->insurance_price;
                        $insurance = round($insurance / 1000) * 1000;
                    } else {
                        $insurance = 0;
                    }
                } else {
                    if ($value > (int) $data_for_calc->amount_of_free_insurance) {
                        $insurance = ($value / 100) * (int) $data_for_calc->insurance_price;
                        $insurance = round($insurance / 1000) * 1000;
                    } else {
                        $insurance = 0;
                    }
                }

                $supership_cost = $supership_cost + $insurance;

                $replaceCalc = $this->replaceDataCode_Super_ship($id_update, $supership_cost);
            } else {
                $region = $this->search_region($province, $district, $policy_id);

                $data_for_calc = null;

                if ($region !== null) {
                    $data_for_calc = $region->data_region;
                }

                if ($data_for_calc !== null) {
                    $supership_cost = (float) $data_for_calc->price_region;

                    //Tính khối lượng
                    $massInput = (int) $mass;

                    $massFree = (int) ($data_for_calc->mass_region);

                    $masscalc = ($massInput - $massFree) / (int) $data_for_calc->mass_region_free;

                    if ($masscalc <= 0) {
                        $masscalc = 0;
                    } else {
                        $masscalc = ceil($masscalc) * (int) $data_for_calc->price_over_mass_region;
                    }

                    $volumecalc = 0;

                    if ($masscalc > $volumecalc) {
                        $supership_cost += (float) $masscalc;
                    } elseif ($masscalc < $volumecalc) {
                        $supership_cost += (float) $volumecalc;
                    } else {
                        $supership_cost += (float) $masscalc;
                    }

                    //Tính bảo hiểm
                    if ($total > $value) {
                        if ($total > (int) $data_for_calc->amount_of_free_insurance) {
                            $insurance = ($total / 100) * (int) $data_for_calc->insurance_price;
                            $insurance = round($insurance / 1000) * 1000;
                        } else {
                            $insurance = 0;
                        }
                    } else {
                        if ($value > (int) $data_for_calc->amount_of_free_insurance) {
                            $insurance = ($value / 100) * (int) $data_for_calc->insurance_price;
                            $insurance = round($insurance / 1000) * 1000;
                        } else {
                            $insurance = 0;
                        }
                    }

                    $supership_cost = $supership_cost + $insurance;

                    $replaceCalc = $this->replaceDataCode_Super_ship($id_update, $supership_cost);
                }
            }
        }
    }

    public function checkAndReplace_fee($code_supership = '', $tbl_create_order, $id_update, $full_data_true)
    {
        $key_or_null = $this->searchForCode($code_supership, $tbl_create_order);

        if ($key_or_null != null) {
            $province = $full_data_true->city;
            $district = $full_data_true->district;
            $mass = $full_data_true->mass;
            $total = (int) $full_data_true->collect;
            $value = (int) $full_data_true->value;

            $id = null;

            if ($this->get_customer_id($full_data_true->shop) !== null) {
                $id = $this->get_customer_id($full_data_true->shop)->id;
            }

            if ($id !== null) {
                $policy_id = $this->get_policy_id($id);
            } else {
                $policy_id = false;
            }

            if ($policy_id === false) {
                $fee_back_new = (float) $data_for_calc->fee_back_new;
            } else {
                $region = $this->search_region($province, $district, $policy_id);

                $data_for_calc = null;

                if ($region !== null) {
                    $data_for_calc = $region->data_region;

                    if ($data_for_calc !== null) {
                        $fee_back_new = (float) $data_for_calc->fee_back_new;
                    }
                }
            }

            $this->replaceDataCode_Super_ship($id_update, ($full_data_true->hd_fee_stam * $fee_back_new) / 100);
        // $this->replaceDataCode_Super_ship($id_update , ($tbl_create_order[$key_or_null]->supership_value*$fee_back_new)/100);
        } else {
            $province = $full_data_true->city;
            $district = $full_data_true->district;
            $mass = $full_data_true->mass;
            $total = (int) $full_data_true->collect;
            $value = (int) $full_data_true->value;

            $id = null;

            if ($this->get_customer_id($full_data_true->shop) !== null) {
                $id = $this->get_customer_id($full_data_true->shop)->id;
            }

            if ($id !== null) {
                $policy_id = $this->get_policy_id($id);
            } else {
                $policy_id = false;
            }

            if ($policy_id === false) {
                $region = $this->search_region($province, $district, $policy_id);

                $data_for_calc = $region;
                $fee_back_new = (float) $data_for_calc->fee_back_new;
                $supership_cost = (float) $data_for_calc->price_region;

                $massInput = (int) $mass;

                $massFree = (int) ($data_for_calc->mass_region);

                $masscalc = ($massInput - $massFree) / (int) $data_for_calc->mass_region_free;

                if ($masscalc <= 0) {
                    $masscalc = 0;
                } else {
                    $masscalc = ceil($masscalc) * (int) $data_for_calc->price_over_mass_region;
                }

                $volumecalc = 0;

                if ($masscalc > $volumecalc) {
                    $supership_cost += $masscalc;
                } elseif ($masscalc < $volumecalc) {
                    $supership_cost += $volumecalc;
                } else {
                    $supership_cost += $masscalc;
                }
                //Tính bảo hiểm
                if ($total > $value) {
                    if ($total > (int) $data_for_calc->amount_of_free_insurance) {
                        $insurance = ($total / 100) * (int) $data_for_calc->insurance_price;
                        $insurance = round($insurance / 1000) * 1000;
                    } else {
                        $insurance = 0;
                    }
                } else {
                    if ($value > (int) $data_for_calc->amount_of_free_insurance) {
                        $insurance = ($value / 100) * (int) $data_for_calc->insurance_price;
                        $insurance = round($insurance / 1000) * 1000;
                    } else {
                        $insurance = 0;
                    }
                }

                $supership_cost = (($supership_cost + $insurance) * $fee_back_new) / 100;

                $replaceCalc = $this->replaceDataCode_Super_ship($id_update, $supership_cost);
            } else {
                $region = $this->search_region($province, $district, $policy_id);

                $data_for_calc = null;

                if ($region !== null) {
                    $data_for_calc = $region->data_region;
                }

                if ($data_for_calc !== null) {
                    $fee_back_new = (float) $data_for_calc->fee_back_new;
                    $supership_cost = (float) $data_for_calc->price_region;

                    //Tính khối lượng
                    $massInput = (int) $mass;

                    $massFree = (int) ($data_for_calc->mass_region);

                    $masscalc = ($massInput - $massFree) / (int) $data_for_calc->mass_region_free;

                    if ($masscalc <= 0) {
                        $masscalc = 0;
                    } else {
                        $masscalc = ceil($masscalc) * (int) $data_for_calc->price_over_mass_region;
                    }

                    $volumecalc = 0;

                    if ($masscalc > $volumecalc) {
                        $supership_cost += (float) $masscalc;
                    } elseif ($masscalc < $volumecalc) {
                        $supership_cost += (float) $volumecalc;
                    } else {
                        $supership_cost += (float) $masscalc;
                    }

                    //Tính bảo hiểm

                    if ($total > $value) {
                        if ($total > (int) $data_for_calc->amount_of_free_insurance) {
                            $insurance = ($total / 100) * (int) $data_for_calc->insurance_price;
                            $insurance = round($insurance / 1000) * 1000;
                        } else {
                            $insurance = 0;
                        }
                    } else {
                        if ($value > (int) $data_for_calc->amount_of_free_insurance) {
                            $insurance = ($value / 100) * (int) $data_for_calc->insurance_price;
                            $insurance = round($insurance / 1000) * 1000;
                        } else {
                            $insurance = 0;
                        }
                    }

                    $supership_cost = (($supership_cost + $insurance) * $fee_back_new) / 100;

                    $replaceCalc = $this->replaceDataCode_Super_ship($id_update, $supership_cost);
                }
            }
        }
    }

    public function get_customer_id($shop = '')
    {
        $this->db->select('id');
        $this->db->where('customer_shop_code', $shop);
        $this->db->from('tblcustomers');

        return $this->db->get()->row();
    }

    // Update tblorders_shop set `hd_fee` = NULL

    public function UpdateSuperShipFee()
    {
        ini_set('max_execution_time', 99999999);
        error_reporting(E_ALL);
        ini_set('display_errors', 'On');
        ini_set('memory_limit', '-1');

        $this->db->select('*');
        $this->db->from('tbl_create_order');
        $tbl_create_order = $this->db->get()->result();

        $this->db->select('*');
        $this->db->where('hd_fee', null);
        $this->db->where('hd_fee_stam', null);//
        // $this->db->where('city_send' , 'Tỉnh Hải Dương');
//        $this->db->where('is_hd_branch', 1); Xong rồi a
        $this->db->from('tblorders_shop');
        $all_order_fee_hd_stam = $this->db->get()->result();

        for ($i = 0; $i < count($all_order_fee_hd_stam); $i++) {
            $this->checkAndReplaceStam($all_order_fee_hd_stam[$i]->code_supership, $tbl_create_order, $all_order_fee_hd_stam[$i]->id, $all_order_fee_hd_stam[$i]);
        }

        // Trường hợp 1 trước
        // là những đơn có trạng thái Đã Đối Soát Giao Hàng
        // nếu trùng trong csdl
        // tức là chay qua 2754 đơn này
        // đơn nào có trong csdl bên kia
        // thì thay phí supership
        // còn không thì kiểm tra chính sách
        // rồi tính theo chính sách
        // nếu không có chính sách thì tính theo mặc định
        //

        $this->db->select('*');
        // $this->db->where('code_supership' , 'HDGS493832NT.1372253');
        $this->db->where('hd_fee', null);
        // $this->db->where('city_send' , 'Tỉnh Hải Dương');
//        $this->db->where('is_hd_branch', 1);
        $nameStatus = ['Đã Đối Soát Giao Hàng', 'Đã Giao Hàng Toàn Bộ', 'Đã Giao Hàng Một Phần'];
        $this->db->where_in('status', $nameStatus);
        $this->db->from('tblorders_shop');
        $all_order_true = $this->db->get()->result();
        for ($i = 0; $i < count($all_order_true); $i++) {
            $re = $this->checkAndReplace($all_order_true[$i]->code_supership, $tbl_create_order, $all_order_true[$i]->id, $all_order_true[$i]);
        }
		
		/**
         * News code update order
         * @author Lediun Software
         */
        // foreach ($all_order_true as $order) {
            // $data = array('hd_fee' => $order->hd_fee_stam);
            // $this->db->where('id', $order->id);
            // $this->db->update('tblorders_shop', $data);
        // }

        // Trường hợp 2 trước
        // là những đơn có trạng thái Đã Đối Soát Trả Hàng
        // nếu trùng trong csdl
        // tức là chay qua hết đơn này
        // đơn nào có trong csdl bên kia
        // thì thay phí supership
        // còn không thì kiểm tra chính sách
        // rồi tính theo chính sách
        // nếu không có chính sách thì tính theo mặc định
        //
        //
        // Trường hơp 2: Những đơn hàng có status là: "Không Giao Được", "Xác Nhận Hoàn", "Đang Trả Hàng", "Đang Chuyển Kho Trả", "Đã Đối Soát Trả Hàng", "Đã Chuyển Kho Trả", thì tính như trường hợp 1 nhưng phải nhân thêm % phí hoàn tương ứng với vùng miền đó
        $nameStatus = [
            'Không Giao Được',
            'Xác Nhận Hoàn',
            'Đang Trả Hàng',
            'Đang Chuyển Kho Trả',
            'Đã Đối Soát Trả Hàng',
            'Đã Chuyển Kho Trả',
            'Đã Trả Hàng',
            'Hoãn Trả Hàng',
        ];
        $this->db->select('*');
        $this->db->where('hd_fee', null);
        // $this->db->where('city_send' , 'Tỉnh Hải Dương');
//        $this->db->where('is_hd_branch', 1);

        $this->db->where_in('status', $nameStatus);
        $this->db->from('tblorders_shop');
        $all_order_false = $this->db->get()->result();

        for ($i = 0; $i < count($all_order_false); $i++) {
            $this->checkAndReplace_fee($all_order_false[$i]->code_supership, $tbl_create_order, $all_order_false[$i]->id, $all_order_false[$i]);
        }
    }
}
