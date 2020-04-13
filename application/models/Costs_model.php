<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Costs_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function get_full_costs($id = '')
    {
        $costs = array();
        if($id == '') {
            $this->db->where('costs_parent', '0');
            $costs = $this->db->get('tblcosts')->result_array();
        }
        else {
            $this->db->where('costs_parent', $id);
            $costs = $this->db->get('tblcosts')->result_array();
        }
        return $costs;
    }
    public function get_by_id($id_parent=0,&$array_costs=[], $level=0) {
        if(is_numeric($level)) {
            $this->db->where(array('costs_parent' => $id_parent));
            $current_level = $this->db->get('tblcosts')->result_array();
            if($current_level)
            {
            foreach($current_level as $key=>$value) {
                $sub = "";
                for($i=0;$i<$level;$i++){
                    $sub.= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                }
                $sub.= "&#10154;";
                $current_level[$key]['name'] = $sub . " " .$current_level[$key]['name'];
                array_push($array_costs, $current_level[$key]);
                $this->get_by_id($value['id'], $array_costs, $level+1);
            }
            }else
            {
               return ;
            }
        }
    }
    public function get_costs_parent($id_parent=0,&$array_costs=[], $level=0) {
        if(is_numeric($level)) {
            $this->db->where(array('costs_parent' => $id_parent,'tblcosts.id >' => 0));
            $current_level = $this->db->get('tblcosts')->result_array();
            if($current_level)
            {   
            foreach($current_level as $key=>$value) {
                $sub = "";
                for($i=0;$i<$level;$i++){
                    $sub.= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                }
                if($level == 1)
                {
                    $sub.= "&rarr;";
                }elseif($level == 2)
                {
                    $sub.= "&#8649;";
                }elseif($level >= 3)
                {
                    $sub.= "&#8667;";
                }
                $current_level[$key]['code'] = $sub . " " .$current_level[$key]['code'];
                array_push($array_costs, $current_level[$key]);
                $this->get_costs_parent($value['id'], $array_costs, $level+1);
            }
            }else
            {
               return ; 
            }
        }
    }
}