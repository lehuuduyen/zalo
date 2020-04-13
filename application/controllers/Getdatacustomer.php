<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Getdatacustomer extends ClientsController
{
    public function index()
    {
      $data = $this->db->select('customer_shop_name,customer_phone,customer_password')->get_where('tblcustomers', array('customer_phone !=' => null))->result();
      echo json_encode($data);

    }


}
