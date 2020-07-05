<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Shipper extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('dashboard_model');
    }

    /* This is admin dashboard view */
    public function index()
    {

        $this->load->model('Order_model');
        $order_model = new Order_model();
        $data['list_status'] = $order_model->getStatus();
        //get date
        $now = date('Y-m-d');
        $date = new DateTime($now);
        $days = 7;
        date_sub($date, date_interval_create_from_date_string($days . ' days'));
        $date_from = date_format($date, 'Y-m-d');
        $date_to = date('Y-m-d');
        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;
        $this->load->view('admin/shipper/index',$data);
    }
    public function updateOrders(){
        $json = $this->input->get('list');
        $data = json_decode($json);
        $this->db->update_batch('tbldelivery_nb', $data, 'id');


    }
    public function getDeliveryByStaff($staffId)
    {

        $json = $this->input->get('jsonData');
        $data = json_decode($json);
        $this->db->select('tbldelivery_nb.id as delivery_id,tbldelivery_nb.date_create,tbldelivery_nb.orders,shop.*');
        $this->db->join('tblorders_shop as shop','shop.id = tbldelivery_nb.shop','left');

        if(!empty($staffId)){
            $this->db->where('tbldelivery_nb.sman',$staffId);
        }
        $this->db->where('date_report is NULL', NULL, FALSE);

        $this->db->order_by('tbldelivery_nb.orders', 'ASC');

        $this->db->from('tbldelivery_nb');
        $kq = $this->db->get()->result();

        $result = new stdClass();
        $result->data = $kq;
        header('Content-Type: application/json');
        echo json_encode($result);

    }
}
