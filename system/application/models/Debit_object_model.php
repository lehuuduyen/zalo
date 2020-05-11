<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Debit_object_model extends App_Model
{
    private $statuses;
    function __construct()
    {
        parent::__construct();
    }
    function add($data)
    {
        if($data!=array())
        {
            $data['create_by']=get_staff_user_id();
            $data['date'] = to_sql_date($data['date']);
            if(!empty($data['date']))
            {
                $data['date'] = $data['date'].' '.date('H:i:s');
            }
            $data['date_create']=date('Y-m-d');
            $data['price']=str_replace(",", "",$data['price']);
            if(empty($data['code']))
            {
                $number = getMaxIDCODE('code','tbldebit_object',array())+1;
                $code='CN-'.str_pad($number, 6, '0', STR_PAD_LEFT);
                $data['code']=$code;
            }
            $this->db->insert('tbldebit_object',$data);
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
            $data['price'] = str_replace(",", "", $data['price']);
            $this->db->select('tbldebit_object.*,DATE_FORMAT(date, "%Y-%m-%d") as date');
            $this->db->where('id', $id);
            $debit_object = $this->db->get('tbldebit_object')->row();

            $_date = strtotime(to_sql_date($data['date'], true));
            $_date = date("Y-m-d", $_date);
            if($debit_object->date != $_date)
            {
                $data['date'] = to_sql_date($data['date']);
                $data['date'] = $data['date'].' '.date('H:i:s');
            }
            else {
                unset($data['date']);
            }

            $this->db->where('id',$id);
            if($this->db->update('tbldebit_object',$data)){
                return true;
            }
        }
        return false;
    }
    public function delete($id)
    {
        if(is_numeric($id))
        {
            $this->db->where('id',$id);
            if($this->db->delete('tbldebit_object'))
            {
                return true;
            }
        }
        return false;
    }
    public function update_status($id,$data)
    {
        if(is_numeric($id)) {
            $this->db->where('id', $id);
            if ($this->db->update('tbldebit_object',$data)) {
                return true;
            }
        }
        return false;
    }

}
