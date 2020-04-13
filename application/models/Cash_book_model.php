<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cash_book_model extends App_Model
{
    private $statuses;
    function __construct()
    {
        parent::__construct();
    }
    function get()
    {
        // $contracts = $this->db->get('tblcontracts')->result_array();
    }
    function add($data)
    {
        if($data!=array())
        {
            $max_code = 0;
            $this->db->select('max(count_code) as max_count_code');
            $this->db->where('type',$data['type']);
            $this->db->where('payment_mode_id',$data['payment_mode_id']);
            $get_max_code=$this->db->get('tblcash_book')->row();
            if(is_numeric($get_max_code->max_count_code))
            {
                $max_code=$get_max_code->max_count_code;
            }
            $data['create_by']=get_staff_user_id();
            $data['date'] = to_sql_date($data['date']).' '.date('H:i:s');

            if(strtotime(to_sql_date($data['date_control'])) < strtotime(date('Y-m-d')))
            {
                $data['date_control'] = to_sql_date($data['date_control']).' 23:59:59';
            }
            else
            {
                $data['date_control'] = to_sql_date($data['date_control']).' '.date('H:i:s');
            }

            $data['date_create'] = date('Y-m-d');
            $data['price']=number_unformat($data['price']);
            $data['count_code']=$max_code+1;
            $data['code']=$this->get_cash_code($data['type'],$data['payment_mode_id']);
            $this->db->insert('tblcash_book',$data);
            if($this->db->insert_id())
            {
                return $this->db->insert_id();
            }
        }
        return false;
    }
    public function update($data,$id)
    {

        if(is_numeric($id))
        {
            unset($data['code']);
            $cash_book = get_table_where('tblcash_book',array('id'=>$id),'','row');

            $cash_book_date = strtotime(to_sql_date(_dt($cash_book->date), true));
            $cash_book_date = date("Y-m-d", $cash_book_date);
            if($cash_book_date != to_sql_date($data['date']))
            {
                $data['date'] = to_sql_date($data['date']).' '.date('H:i:s');
            }
            else {
                unset($data['date']);
            }

            $cash_book_date_control = strtotime(to_sql_date(_dt($cash_book->date_control), true));
            $cash_book_date_control = date("Y-m-d", $cash_book_date_control);
            if($cash_book_date_control != to_sql_date($data['date_control']))
            {
                if(strtotime(to_sql_date($data['date_control'])) < strtotime($cash_book_date_control))
                {
                    $data['date_control'] = to_sql_date($data['date_control']).' 23:59:59';
                }
                else
                {
                    $data['date_control'] = to_sql_date($data['date_control']).' '.date('H:i:s');
                }
            }
            else {
                unset($data['date_control']);
            }

            $data['price']=number_unformat($data['price']);
            $this->db->where('id',$id);
            if($this->db->update('tblcash_book',$data)){
                return true;
            }
        }
        return false;
    }
    public function delete($id)
    {
        if(is_numeric($id))
        {
            $cash=get_table_where('tblcash_book',array('id'=>$id),'','row');
            $this->db->where('id',$id);
            if($this->db->delete('tblcash_book'))
            {
                $staff=get_staff_user_id();
                logActivity('Xóa phiếu '.($cash->type==0?'Phiếu thu mã:':' Phiếu chi mã:'.$cash->code.': '.$cash->price.' bởi '.get_staff_full_name($staff)));
                return true;
            }
        }
        return false;
    }
    public function get_cash_code($type=NULL,$payment_id=NULL)
    {
        $code="";
        $prefix="";
        if(!is_numeric($type)&&empty($payment_id))
        {
            $type=$this->input->post('type');
            $payment_id=$this->input->post('payment_id');
        }
        if(is_numeric($type)&&!empty($payment_id))
        {
            $payment=get_table_where('tblinvoicepaymentsmodes',array('id'=>$payment_id),'','row');
            if(!empty($payment))
            {
                $number=getMaxIDCODE('count_code','tblcash_book',array('type'=>$type,'payment_mode_id'=>$payment_id))+1;
                if(empty($number))
                {
                    $number=1;
                }
                $code=$number;
                $prefix='PT-'.$payment->code.'-';
                if($type)
                {
                    $prefix='PC-'.$payment->code.'-';
                }
            }
        }
        return ($prefix.$code);
    }

}
