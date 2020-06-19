<?php

defined('BASEPATH') or exit('No direct script access allowed');
function getprice_cash_book($where = null)
{
    $total_debt = 0;
    $CI =& get_instance();
    // Cash thu
    $CI->db->select('SUM(price) as amount', false);
    if ($where) {
        $CI->db->where($where);
    }
    $cash_thu = $CI->db->get_where('tblcash_book', array('type' => 0))->row();
    $total_debt += $cash_thu->amount;
    unset($cash_thu);

    // Cash chi
    $CI->db->select('SUM(price) as amount', false);
    if ($where) {
        $CI->db->where($where);
    }
    $cash_chi = $CI->db->get_where('tblcash_book', array('type' => 1))->row();
    $total_debt -= $cash_chi->amount;
    unset($cash_chi);
    return $total_debt;
}

function getquantity_borrowing($supplier_id = NULL, $product = NULL, $start = NULL, $end = NULL, $type = NULL)
{
    $total_debt = 0;
    if (is_numeric($supplier_id)) {
        $CI =& get_instance();
        if ($start == NULL && $supplier_id && $product) {
            $CI->db->select('debit as debt', false);
            $sodu = $CI->db->get_where('tblborrowing_debt', array('id_supplier' => $supplier_id, 'product_id' => $product))->row();
            $total_debt += $sodu->debt;
            unset($sodu);
        }
    }
    return $total_debt;
}


function getprice_personal($staff_id = NULL, $start = NULL, $end = NULL, $type = NULL){
    $total_debt=0;
    if($staff_id)
    {
        $CI =& get_instance();
        if($start==NULL)
        {
            $CI->db->select('opening_balance as debt',false);
            $sodu=$CI->db->get_where('tblother_object',array('id'=>$staff_id))->row();
            $total_debt+=$sodu->debt;
            unset($sodu);
        }

        // Cash thu
        if($type=='+'||$type==NULL) {
            $CI->db->select('SUM(price) as amount', false);
            if ($start && $end) {
                $CI->db->where('date>=', $start);
                $CI->db->where('date<=', $end);
            } else if ($start == null && $end) {
                $CI->db->where('date<=', $end);
            }
            $cash_thu = $CI->db->get_where('tblcash_book', array('type' => 0, 'groups' => 8, 'id_object' => 'tblother_object', 'staff_id' => $staff_id))->row();
            $total_debt += $cash_thu->amount;
            unset($cash_thu);
        }

        // Cash chi
        if($type=='-'||$type==NULL) {
            $CI->db->select('SUM(price) as amount', false);
            if ($start && $end) {
                $CI->db->where('date>=', $start);
                $CI->db->where('date<=', $end);
            } else if ($start == null && $end) {
                $CI->db->where('date<=', $end);
            }
            $cash_chi = $CI->db->get_where('tblcash_book', array('type' => 1, 'groups' => 8, 'id_object' => 'tblother_object', 'staff_id' => $staff_id))->row();
            $total_debt -= $cash_chi->amount;
            unset($cash_chi);
        }
        //Điều chỉnh công nợ
        if($type=='+'||$type==NULL) {
            $CI->db->select('SUM(price) as amount', false);
            if ($start && $end) {
                $CI->db->where('date>=', $start);
                $CI->db->where('date<=', $end);
            } else if ($start == null && $end) {
                $CI->db->where('date<=', $end);
            }
            $CI->db->where('price>',0);
            $CI->db->where('id_object','tblother_object');
            $CI->db->where('status','2');
            $debit_object = $CI->db->get_where('tbldebit_object', array('staff_id' => $staff_id))->row();
            $total_debt += $debit_object->amount;
            unset($debit_object);
        }
        if($type=='-'||$type==NULL) {
            $CI->db->select('SUM(price) as amount', false);
            if ($start && $end) {
                $CI->db->where('date>=', $start);
                $CI->db->where('date<=', $end);
            } else if ($start == null && $end) {
                $CI->db->where('date<=', $end);
            }
            $CI->db->where('price<',0);
            $CI->db->where('id_object','tblother_object');
            $CI->db->where('status','2');
            $debit_object = $CI->db->get_where('tbldebit_object', array('staff_id' => $staff_id))->row();
            $total_debt -= ($debit_object->amount)*(-1);
            unset($debit_object);
        }
    }
    return $total_debt;
}


function getMaxIDCODE($id, $table, $where)
{
    $CI =& get_instance();
    $table = trim($table);
    if (is_array($where)) {
        if (sizeof($where) > 0) {
            $CI->db->where($where);
        }
    } else if (strlen($where) > 0) {
        $CI->db->where($where);
    }
    if (isset($id)) {
        $CI->db->select_max($id);
        return $CI->db->get($table)->row()->{$id};
    }
    return '';
}

function get_status_label($id)
{
    $label = 'default';

    if ($id == 2) {
        $label = 'light-green';
    } else if ($id == 3) {
        $label = 'default';
    } else if ($id == 4) {
        $label = 'info';
    } else if ($id == 5) {
        $label = 'success';
    } else if ($id == 6) {
        $label = 'warning';
    }
    return $label;
}

function format_status_debit_object($id)
{

    $label = get_status_label($id);
    if ($id == 2) {
        $label = 'light-green';
        $status_name="Phiếu đã được duyệt";
    }
    else
    {
        $label = 'warning';
        $status_name="Phiếu chưa được duyệt";
    }
    $class = 'label label-' . $label;
    return '<span class="inline-block ' . $class . '">' . $status_name . '</span>';
}


function _dC($date)
{
    if ($date == '' || is_null($date) || $date == '0000-00-00') {
        return '';
    }
    $format = get_current_date_format();
    $date   = strftime($format, strtotime($date));
    return do_action('after_format_date', $date);
}



