<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Deadline_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get()
    {
        $this->db->select('*');
        $this->db->from('tbldeadline');
        $result = $this->db->get()->result_array();
        return $result;
    }


}