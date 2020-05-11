<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Category_model extends App_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_full_detail($id='') {
        $categories = array();
        if($id == '') {
            $this->db->where('category_parent', '0');
            $categories = $this->db->get('tblcategories')->result_array();
        }
        else {
            $this->db->where('category_parent', $id);
            $categories = $this->db->get('tblcategories')->result_array();
        }
        return $categories;
    }
    public function update_category($data_vestion,$id)
    {
        if (is_admin()) {
            // var_dump($data_vestion);die();
            $this->db->where('id',$id);
            $this->db->update('tblcategories',$data_vestion);
            if ($this->db->affected_rows() >0) {
                return true;
            }
            return false;
        }
        return false;
    }
    public function delete_categories($data='')
    {
        // if($data['id'] != 0)
        // {
        $this->db->where('category_parent',$data['id']);
        $this->db->update('tblcategories',array('category_parent'=>$data['id_new']));
        $this->db->where('category_id',$data['id']);
        $this->db->update('tblitems',array('category_id'=>$data['id_new']));
        $this->db->delete('tblcategories',array('id'=>$data['id']));
        if ($this->db->affected_rows() >0) {
                return true;
            }
            return false;
        // }
        // return false;
    }
    public function add_category($data)
    {
        if (is_admin()) {
        	$data['staff_create'] = get_staff_user_id();
        	$data['date_create'] = date('Y-m-d H:i:s');
            $this->db->insert('tblcategories',$data);
            if ($this->db->affected_rows() >0) {
                return true;
            }
            return false;
        }
        return false;
    }
    public function get_by_id($id_parent=0,&$array_category=[], $level=0) {
        if(is_numeric($level)) {
            $this->db->where(array('category_parent' => $id_parent));
            $current_level = $this->db->get('tblcategories')->result_array();
            if($current_level)
            {
            foreach($current_level as $key=>$value) {
                $sub = "";
                for($i=0;$i<$level;$i++){
                    $sub.= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                }
                $sub.= "&#10154;";
                $current_level[$key]['category'] = $sub . " " .$current_level[$key]['category'];
                array_push($array_category, $current_level[$key]);
                $this->get_by_id($value['id'], $array_category, $level+1);
            }
            }else
            {
               return ;
            }
        }
    }
    // public function get_by_id($id_parent=0,&$array_category=[], $level=0) {
    //     if(is_numeric($level)) {
    //         $this->db->where(array('category_parent' => $id_parent));
    //         $current_level = $this->db->get('tblcategories')->result_array();

    //         foreach($current_level as $key=>$value) {
    //             $sub = "";
    //             for($i=0;$i<$level;$i++){
    //                 $sub.= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    //             }
    //             $sub.= "&#10154;";
    //             $current_level[$key]['category'] = $sub . " " .$current_level[$key]['category'];
    //             array_push($array_category, $current_level[$key]);
    //             if($level< 3)
    //                 $this->get_by_id($value['id'], $array_category, $level+1);
    //         }
    //     }
    // }
    //

    public function insertCapacity($data)
    {
        $this->db->insert('tbl_capacity', $data);
        return $this->db->insert_id();
    }

    public function rowCapacity($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_capacity');
        $this->db->where('id', $id);
        return $this->db->get()->row_array();
    }

    public function getCapacity()
    {
        $this->db->select('*');
        $this->db->from('tbl_capacity');
        return $this->db->get()->result_array();
    }

    public function updateCapacity($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tbl_capacity', $data);
    }

    public function deleteCapacity($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('tbl_capacity');
    }

    public function searchCapacity($q, $limit = 50)
    {
        $this->db->select('tbl_capacity.id as id, tbl_capacity.name as name', false);
        $this->db->from('tbl_capacity');
        if (!empty($q))
        {
            $this->db->group_start();
            $this->db->like('tbl_capacity.code', $q);
            $this->db->or_like('tbl_capacity.name', $q);
            $this->db->group_end();
        }
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    public function insertMachines($data)
    {
        $this->db->insert('tbl_machines', $data);
        return $this->db->insert_id();
    }

    public function rowMachines($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_machines');
        $this->db->where('id', $id);
        return $this->db->get()->row_array();
    }

    public function getMachinesByArrId($arr_id)
    {
        $this->db->select('*');
        $this->db->from('tbl_machines');
        $this->db->where_in('tbl_machines.id', $arr_id);
        return $this->db->get()->result_array();
    }

    public function updateMachines($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tbl_machines', $data);
    }

    public function deleteMachines($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('tbl_machines');
    }

    public function searchMachines($q, $limit = 50)
    {
        $this->db->select('tbl_machines.id as id, tbl_machines.name as name', false);
        $this->db->from('tbl_machines');
        if (!empty($q))
        {
            $this->db->group_start();
            $this->db->like('tbl_machines.code', $q);
            $this->db->or_like('tbl_machines.name', $q);
            $this->db->group_end();
        }
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    public function insertPackaging($data)
    {
        $this->db->insert('tbl_packaging', $data);
        return $this->db->insert_id();
    }

    public function rowPackaging($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_packaging');
        $this->db->where('id', $id);
        return $this->db->get()->row_array();
    }

    public function updatePackaging($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tbl_packaging', $data);
    }

    public function deletePackaging($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('tbl_packaging');
    }

    public function searchPackaging($q, $limit = 50)
    {
        $this->db->select('tbl_packaging.id as id, tbl_packaging.name as name', false);
        $this->db->from('tbl_packaging');
        if (!empty($q))
        {
            $this->db->group_start();
            $this->db->like('tbl_packaging.code', $q);
            $this->db->or_like('tbl_packaging.name', $q);
            $this->db->group_end();
        }
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }
}