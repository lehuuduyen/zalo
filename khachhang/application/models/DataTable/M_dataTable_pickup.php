<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_dataTable_pickup extends CI_Model {

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

    public function Datatables($dt='')
    {


      $start  = $dt['start'];
      $length = $dt['length'];




      $this->db->select("created,tblpickuppoint.id,customer_id,tblcustomers.customer_shop_code as customer_shop_code,phone_customer,repo_customer,note,status,name_customer_new,modified,district_filter,commune_filter,address_filter,user_reg,tblstaff.firstname as firstname,tblstaff.lastname as lastname");
      $this->db->from('tblpickuppoint');
      $this->db->where('customer_id' , $dt['id_customer']);
      $this->db->where_in('status' , array('0' , 0 , '2' , 2));
      $this->db->join('tblcustomers' ,'tblpickuppoint.customer_id = tblcustomers.id');
      $this->db->join('tblstaff' ,'tblstaff.staffid = tblpickuppoint.user_reg','left');
      $rowCount = $this->db->count_all_results();


      $this->db->select("created,tblpickuppoint.id,customer_id,tblcustomers.customer_shop_code as customer_shop_code,phone_customer,repo_customer,note,status,name_customer_new,modified,district_filter,commune_filter,address_filter,user_reg,tblstaff.firstname as firstname,tblstaff.lastname as lastname");
      $this->db->from('tblpickuppoint');
      $this->db->where('customer_id' , $dt['id_customer']);
      $this->db->where_in('status' , array('0' , 0 , '2' , 2));
      $this->db->join('tblcustomers' ,'tblpickuppoint.customer_id = tblcustomers.id');
      $this->db->join('tblstaff' ,'tblstaff.staffid = tblpickuppoint.user_reg' , 'left');
      $this->db->limit($length,$start);
      $this->db->order_by('created', 'DESC');

      $list = $this->db->get()->result();

      $option['draw']            = $dt['draw'];
      $option['recordsTotal']    = $rowCount;
      $option['recordsFiltered'] = $rowCount;
      $option['data']            = array();

      $columnd     = array(
        'created',
        'id',
        'customer_id',
        'customer_shop_code',
        'phone_customer',
        'repo_customer',
        'note',
        'status',
        'name_customer_new',
        "modified",
        "district_filter",
        "commune_filter",
        "address_filter",
        'user_reg',
        'firstname',
        'lastname'
      );


      $count_c = sizeof($columnd);

      $list = json_decode(json_encode($list), true);
      foreach ($list as $row) {

         $rows = array();

         for ($i=0; $i < $count_c; $i++) {
             $rows[] = $row[$columnd[$i]];
         }
         $rows[15] = $rows[15] .' '.$rows[14];

         $rows[7] = 'Chưa Lấy';

         $icon_delete = '<a href="javasript:;"  data-id="'.$rows['1'] .'" class="btn btn-danger delete-reminder-custom btn-icon">
         <i class="fa fa-remove"></i>
         </a>';


         $icon_edit = "<a data-id='". $rows['1'] ."'  style='padding: 3px;' class='btn btn-primary edit-customer' href='javascript:;'><i class='fa fa-pencil' ></i></a>";

         $rows[16] = $icon_delete.$icon_edit.'</div>';

         $option['data'][] = $rows;
      }

      // eksekusi json
      echo json_encode($option);
      die();


    }


    public function DatatablesPicked($dt='')
    {
      $start  = $dt['start'];
      $length = $dt['length'];




      $this->db->select("created,tblpickuppoint.id,customer_id,tblcustomers.customer_shop_code as customer_shop_code,phone_customer,repo_customer,note,status,name_customer_new,modified,district_filter,commune_filter,address_filter,user_geted,tblstaff.firstname as firstname,tblstaff.lastname as lastname,number_order_get");
      $this->db->from('tblpickuppoint');
      $this->db->where('customer_id' , $dt['id_customer']);
      $this->db->where_in('status' , array('1' , 1));
      $this->db->join('tblcustomers' ,'tblpickuppoint.customer_id = tblcustomers.id');
      $this->db->join('tblstaff' ,'tblstaff.staffid = tblpickuppoint.user_geted','left');
      $rowCount = $this->db->count_all_results();


      $this->db->select("created,tblpickuppoint.id,customer_id,tblcustomers.customer_shop_code as customer_shop_code,phone_customer,repo_customer,note,status,name_customer_new,modified,district_filter,commune_filter,address_filter,user_geted,tblstaff.firstname as firstname,tblstaff.lastname as lastname,number_order_get");
      $this->db->from('tblpickuppoint');
      $this->db->where('customer_id' , $dt['id_customer']);
      $this->db->where_in('status' , array('1' , 1));
      $this->db->join('tblcustomers' ,'tblpickuppoint.customer_id = tblcustomers.id' , 'left');
      $this->db->join('tblstaff' ,'tblstaff.staffid = tblpickuppoint.user_geted' , 'left');
      $this->db->limit($length,$start);
      $this->db->order_by('created', 'DESC');

      $list = $this->db->get()->result();

      $option['draw']            = $dt['draw'];
      $option['recordsTotal']    = $rowCount;
      $option['recordsFiltered'] = $rowCount;
      $option['data']            = array();

      $columnd     = array(
        'created',
        'id',
        'customer_id',
        'customer_shop_code',
        'phone_customer',
        'repo_customer',
        'note',
        'status',
        'name_customer_new',
        "modified",
        "district_filter",
        "commune_filter",
        "address_filter",
        'user_geted',
        'firstname',
        'lastname',
        'number_order_get'
      );
      $count_c = sizeof($columnd);

      $list = json_decode(json_encode($list), true);
      foreach ($list as $row) {

         $rows = array();

         for ($i=0; $i < $count_c; $i++) {
             $rows[] = $row[$columnd[$i]];
         }
         $rows[15] = $rows[15] .' '.$rows[14];

         $rows[7] = 'Đã Lấy';



         $option['data'][] = $rows;
      }

      // eksekusi json
      echo json_encode($option);
      die();
    }



}
