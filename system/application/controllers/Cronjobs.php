<?php

class Cronjobs extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function updateSPS()
    {
        ini_set('max_execution_time', 99999999);
        error_reporting(E_ALL);
        ini_set('display_errors', 'On');
        ini_set('memory_limit', '-1');

        /**
         * 1. hd_fee_stam = null
         */
        $this->db->where('hd_fee_stam', null);
        if (in_array($_SERVER['HTTP_HOST'], array('spshd.test.lediun.com')))
            $this->db->limit(10, 0);
        $this->db->from('tblorders_shop');
        $list_order_hd_fee_stam = $this->db->get()->result();

        foreach ($list_order_hd_fee_stam as $order_hd_fee) {

            $this->db->where('orders_shop_id', $order_hd_fee->id);
            $info_order = $this->db->get('tbl_create_order')->result();
            $price = 0;
            if ($info_order) {
                $price = $info_order[0]->price;
            }

            $this->_checkAndReplaceStam($order_hd_fee->code_supership, $order_hd_fee->id, $order_hd_fee, $price);
        }

        /**
         * 2. hd_fee is NULL
         */
        // TH1
        $nameStatus = ['Đã Đối Soát Giao Hàng', 'Đã Giao Hàng Toàn Bộ', 'Đã Giao Hàng Một Phần'];
        $this->db->where_in('status', $nameStatus);
        $this->db->where('hd_fee', null);

        if (in_array($_SERVER['HTTP_HOST'], array('spshd.test.lediun.com')))
            $this->db->limit(10, 0);

        $this->db->from('tblorders_shop');
        $list_order_hd_fee1 = $this->db->get()->result();
        foreach ($list_order_hd_fee1 as $order_hd_fee) {

            $this->db->where('orders_shop_id', $order_hd_fee->id);
            $info_order = $this->db->get('tbl_create_order')->result();
            $price = 0;
            if ($info_order) {
                $price = $info_order[0]->price;
            }

            $this->_checkAndReplace($order_hd_fee->code_supership, $order_hd_fee->id, $order_hd_fee, $price);
        }

        // TH2
        unset($nameStatus);
        $nameStatus = ['Không Giao Được',
            'Xác Nhận Hoàn',
            'Đang Trả Hàng',
            'Đang Chuyển Kho Trả',
            'Đã Đối Soát Trả Hàng',
            'Đã Chuyển Kho Trả',
            'Đã Trả Hàng',
            'Hoãn Trả Hàng', 'Đã Trả Hàng Một Phần'];
        $this->db->where_in('status', $nameStatus);
        $this->db->where('hd_fee', null);

        if (in_array($_SERVER['HTTP_HOST'], array('spshd.test.lediun.com')))
            $this->db->limit(10, 0);

        $this->db->from('tblorders_shop');
        $list_order_hd_fee2 = $this->db->get()->result();
        foreach ($list_order_hd_fee2 as $order_hd_fee) {
            $this->db->where('orders_shop_id', $order_hd_fee->id);
            $info_order = $this->db->get('tbl_create_order')->result();
            $price = 0;
            if ($info_order) {
                $price = $info_order[0]->price;
            }
            $this->checkAndReplace_fee($order_hd_fee->code_supership, $order_hd_fee->id, $order_hd_fee, $price);
        }

    }


/**
     * This function update region_id in tbl_create_order
     */
    public function updateRegion_id()
    {
        $this->db->where('region_id', 0);
        $this->db->limit(100, 0);
        $list_order = $this->db->get('tbl_create_order')->result();
		//        pre($this->db->last_query());
        $success = $errors = array();
        foreach ($list_order as $order){
            $this->db->select('*');
            $this->db->where('city', $order->province);
            $this->db->where('district', $order->district);
            $search_result = $this->db->get('tblregion_excel')->row();
            if ($search_result) {
                $this->db->select('*');
                $this->db->where('id', $search_result->region_id);
                $region = $this->db->get('tbldeclared_region')->row();

                $data = array('region_id' => $region->id);
                $this->db->where('id', $order->id);
                if(!$this->db->update('tbl_create_order', $data)){
                    array_push($errors, 'Cập nhật thất bại đơn id '.$order->id);
                }

                array_push($success, 'Cập nhật thành công đơn id '.$order->id);
            }
        }

        if(!empty($errors)){
            echo 'Số đơn cập nhật thất bại:' .count($errors);
        }

        if(!empty($success)){
            echo 'Số đơn cập nhật thành công:' .count($success);
        }
    }

    /**
     * Private function
     */
    /**
     * Function _checkAndReplaceStam là hàm tính phí có cộng thêm phụ phí thực hiện trên bảng tblorders_shop.
     * @param $code_supership là mã đơn hàng trên bảng tblorders_shop
     * @param $id_update là id của đơn hàng trên bảng tblorders_shop
     * @param $full_data_true là thông tin chi tiết đơn hàng trên bảng tblorders_shop
     * @param $price là phụ phí mặc định là 0.
     * @return bool true|false
     */
    private function _checkAndReplaceStam($code_supership = '', $id_update, $full_data_true, $price = 0)
    {
        $province = $full_data_true->city;
        $district = $full_data_true->district;
        $mass = $full_data_true->mass;
        $total = (int)$full_data_true->collect;
        $value = (int)$full_data_true->value;

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

            $supership_cost = (float)$data_for_calc->price_region;

            $massInput = (int)$mass;

            $massFree = (int)($data_for_calc->mass_region);

            $masscalc = ($massInput - $massFree) / (int)$data_for_calc->mass_region_free;

            if ($masscalc <= 0) {
                $masscalc = 0;
            } else {
                $masscalc = ceil($masscalc) * (int)$data_for_calc->price_over_mass_region;
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
                if ($total > (int)$data_for_calc->amount_of_free_insurance) {
                    $insurance = ($total / 100) * (int)$data_for_calc->insurance_price;
                    $insurance = round($insurance / 1000) * 1000;
                } else {
                    $insurance = 0;
                }
            } else {
                if ($value > (int)$data_for_calc->amount_of_free_insurance) {
                    $insurance = ($value / 100) * (int)$data_for_calc->insurance_price;
                    $insurance = round($insurance / 1000) * 1000;
                } else {
                    $insurance = 0;
                }
            }

            $supership_cost = $supership_cost + $insurance + $price;

            $replaceCalc = $this->replaceDataCode_Super_ship_stam($id_update, $supership_cost);
        } else {
            $region = $this->search_region($province, $district, $policy_id);

            $data_for_calc = null;

            if ($region !== null) {
                $data_for_calc = $region->data_region;
            }

            if ($data_for_calc !== null) {
                $supership_cost = (float)$data_for_calc->price_region;

                //Tính khối lượng
                $massInput = (int)$mass;

                $massFree = (int)($data_for_calc->mass_region);

                $masscalc = ($massInput - $massFree) / (int)$data_for_calc->mass_region_free;

                if ($masscalc <= 0) {
                    $masscalc = 0;
                } else {
                    $masscalc = ceil($masscalc) * (int)$data_for_calc->price_over_mass_region;
                }

                $volumecalc = 0;

                if ($masscalc > $volumecalc) {
                    $supership_cost += (float)$masscalc;
                } elseif ($masscalc < $volumecalc) {
                    $supership_cost += (float)$volumecalc;
                } else {
                    $supership_cost += (float)$masscalc;
                }

                //Tính bảo hiểm
                if ($total > $value) {
                    if ($total > (int)$data_for_calc->amount_of_free_insurance) {
                        $insurance = ($total / 100) * (int)$data_for_calc->insurance_price;
                        $insurance = round($insurance / 1000) * 1000;
                    } else {
                        $insurance = 0;
                    }
                } else {
                    if ($value > (int)$data_for_calc->amount_of_free_insurance) {
                        $insurance = ($value / 100) * (int)$data_for_calc->insurance_price;
                        $insurance = round($insurance / 1000) * 1000;
                    } else {
                        $insurance = 0;
                    }
                }

                $supership_cost = $supership_cost + $insurance + $price;

                $replaceCalc = $this->replaceDataCode_Super_ship_stam($id_update, $supership_cost);
            } else {
                $replaceCalc = $this->replaceDataCode_Super_ship_stam($id_update, 0);
            }
        }
    }

    private function _checkAndReplace($code_supership = '', $id_update, $full_data_true, $price = 0)
    {
        $province = $full_data_true->city;
        $district = $full_data_true->district;
        $mass = $full_data_true->mass;
        $total = (int)$full_data_true->collect;
        $value = (int)$full_data_true->value;

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

            $supership_cost = (float)$data_for_calc->price_region;

            $massInput = (int)$mass;

            $massFree = (int)($data_for_calc->mass_region);

            $masscalc = ($massInput - $massFree) / (int)$data_for_calc->mass_region_free;

            if ($masscalc <= 0) {
                $masscalc = 0;
            } else {
                $masscalc = ceil($masscalc) * (int)$data_for_calc->price_over_mass_region;
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
                if ($total > (int)$data_for_calc->amount_of_free_insurance) {
                    $insurance = ($total / 100) * (int)$data_for_calc->insurance_price;
                    $insurance = round($insurance / 1000) * 1000;
                } else {
                    $insurance = 0;
                }
            } else {
                if ($value > (int)$data_for_calc->amount_of_free_insurance) {
                    $insurance = ($value / 100) * (int)$data_for_calc->insurance_price;
                    $insurance = round($insurance / 1000) * 1000;
                } else {
                    $insurance = 0;
                }
            }

            $supership_cost = $supership_cost + $insurance + $price;

            $replaceCalc = $this->replaceDataCode_Super_ship($id_update, $supership_cost);
        } else {
            $region = $this->search_region($province, $district, $policy_id);

            $data_for_calc = null;

            if ($region !== null) {
                $data_for_calc = $region->data_region;
            }

            if ($data_for_calc !== null) {
                $supership_cost = (float)$data_for_calc->price_region;

                //Tính khối lượng
                $massInput = (int)$mass;

                $massFree = (int)($data_for_calc->mass_region);

                $masscalc = ($massInput - $massFree) / (int)$data_for_calc->mass_region_free;

                if ($masscalc <= 0) {
                    $masscalc = 0;
                } else {
                    $masscalc = ceil($masscalc) * (int)$data_for_calc->price_over_mass_region;
                }

                $volumecalc = 0;

                if ($masscalc > $volumecalc) {
                    $supership_cost += (float)$masscalc;
                } elseif ($masscalc < $volumecalc) {
                    $supership_cost += (float)$volumecalc;
                } else {
                    $supership_cost += (float)$masscalc;
                }

                //Tính bảo hiểm
                if ($total > $value) {
                    if ($total > (int)$data_for_calc->amount_of_free_insurance) {
                        $insurance = ($total / 100) * (int)$data_for_calc->insurance_price;
                        $insurance = round($insurance / 1000) * 1000;
                    } else {
                        $insurance = 0;
                    }
                } else {
                    if ($value > (int)$data_for_calc->amount_of_free_insurance) {
                        $insurance = ($value / 100) * (int)$data_for_calc->insurance_price;
                        $insurance = round($insurance / 1000) * 1000;
                    } else {
                        $insurance = 0;
                    }
                }

                $supership_cost = $supership_cost + $insurance + $price;

                $replaceCalc = $this->replaceDataCode_Super_ship($id_update, $supership_cost);
            }
        }
    }

    private function checkAndReplace_fee($code_supership = '', $id_update, $full_data_true, $price = 0)
    {
        $province = $full_data_true->city;
        $district = $full_data_true->district;
        $mass = $full_data_true->mass;
        $total = (int)$full_data_true->collect;
        $value = (int)$full_data_true->value;

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
            $fee_back_new = (float)$data_for_calc->fee_back_new;
            $supership_cost = (float)$data_for_calc->price_region;

            $massInput = (int)$mass;

            $massFree = (int)($data_for_calc->mass_region);

            $masscalc = ($massInput - $massFree) / (int)$data_for_calc->mass_region_free;

            if ($masscalc <= 0) {
                $masscalc = 0;
            } else {
                $masscalc = ceil($masscalc) * (int)$data_for_calc->price_over_mass_region;
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
                if ($total > (int)$data_for_calc->amount_of_free_insurance) {
                    $insurance = ($total / 100) * (int)$data_for_calc->insurance_price;
                    $insurance = round($insurance / 1000) * 1000;
                } else {
                    $insurance = 0;
                }
            } else {
                if ($value > (int)$data_for_calc->amount_of_free_insurance) {
                    $insurance = ($value / 100) * (int)$data_for_calc->insurance_price;
                    $insurance = round($insurance / 1000) * 1000;
                } else {
                    $insurance = 0;
                }
            }

            $supership_cost = (($supership_cost + $insurance) * $fee_back_new) / 100 + $price;

            $replaceCalc = $this->replaceDataCode_Super_ship($id_update, $supership_cost);
        } else {
            $region = $this->search_region($province, $district, $policy_id);

            $data_for_calc = null;

            if ($region !== null) {
                $data_for_calc = $region->data_region;
            }

            if ($data_for_calc !== null) {
                $fee_back_new = (float)$data_for_calc->fee_back_new;
                $supership_cost = (float)$data_for_calc->price_region;

                //Tính khối lượng
                $massInput = (int)$mass;

                $massFree = (int)($data_for_calc->mass_region);

                $masscalc = ($massInput - $massFree) / (int)$data_for_calc->mass_region_free;

                if ($masscalc <= 0) {
                    $masscalc = 0;
                } else {
                    $masscalc = ceil($masscalc) * (int)$data_for_calc->price_over_mass_region;
                }

                $volumecalc = 0;

                if ($masscalc > $volumecalc) {
                    $supership_cost += (float)$masscalc;
                } elseif ($masscalc < $volumecalc) {
                    $supership_cost += (float)$volumecalc;
                } else {
                    $supership_cost += (float)$masscalc;
                }

                //Tính bảo hiểm

                if ($total > $value) {
                    if ($total > (int)$data_for_calc->amount_of_free_insurance) {
                        $insurance = ($total / 100) * (int)$data_for_calc->insurance_price;
                        $insurance = round($insurance / 1000) * 1000;
                    } else {
                        $insurance = 0;
                    }
                } else {
                    if ($value > (int)$data_for_calc->amount_of_free_insurance) {
                        $insurance = ($value / 100) * (int)$data_for_calc->insurance_price;
                        $insurance = round($insurance / 1000) * 1000;
                    } else {
                        $insurance = 0;
                    }
                }

                $supership_cost = (($supership_cost + $insurance) * $fee_back_new) / 100 + $price;

                $replaceCalc = $this->replaceDataCode_Super_ship($id_update, $supership_cost);
            }
        }
    }

    private function get_customer_id($shop = '')
    {
        $this->db->select('id');
        $this->db->where('customer_shop_code', $shop);
        $this->db->from('tblcustomers');

        return $this->db->get()->row();
    }

    private function replaceDataCode_Super_ship($id, $value)
    {
        $this->db->set('hd_fee', $value);
        $this->db->where('id', $id);
        $update = $this->db->update('tblorders_shop');

        return $update;
    }

    private function replaceDataCode_Super_ship_stam($id, $value)
    {
        $this->db->set('hd_fee_stam', $value);
        $this->db->where('id', $id);
        $update = $this->db->update('tblorders_shop');

        return $update;
    }

    private function get_policy_id($id)
    {
        $this->db->select('*');
        $this->db->where('customer_id', $id);
        $search_result = $this->db->get('tblcustomer_policy')->row();

        if (empty($search_result)) {
            return false;
        }

        return $search_result->id;

        //
    }

    private function search_region($province, $district, $policy_id)
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
}
