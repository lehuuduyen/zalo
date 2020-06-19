<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Declare_controller extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('dashboard_model');
    }

    /* This is admin dashboard view */
    public function index()
    {
        $data = [];
        $this->load->model('Order_model');
        $order_model = new Order_model();
        $data['customers'] = $order_model->getCustomer();
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

        $data['list_status'] = $order_model->getStatus();
        $data['city'] = $order_model->getCity();
        $data['regions'] = $order_model->getRegion();
        $data['dvvc'] = $order_model->getDvvc();
        $this->load->view('admin/orders/declare', $data);
    }
    public function getDeadline(){
        $this->load->model('Deadline_model');
        $Deadline_model = new Deadline_model();
        $result = $Deadline_model->get();
        print_r(json_encode($result));

    }
    public function add(){
        $jsonData = $_GET['data'];
        $data = json_decode($jsonData);
        if(isset($data->id)){
            $id = $data->id;
            $result = $this->db->update('tbldeclare',[
                'name'=>$data->name,
                'fail'=>$data->fail,
                'success'=>$data->success,
                'debit'=>$data->debit,
                'status_end'=>$data->status_end,
                'color'=>$data->color,
                ],"id = $id");

        }else{
            $this->db->insert('tbldeclare',$data);
        }
        print_r(json_encode(["status"=>"success"]));

    }
	public function deadline_add(){
        $jsonData = $_GET['data'];
        $data = json_decode($jsonData);
        if(isset($data->id)){
            $id = $data->id;
            $result = $this->db->update('tbldeadline',[
                'name'=>$data->name,
                'note'=>$data->note,
                'time_nt'=>$data->time_nt,
                'time_nm'=>$data->time_nm,
                'time_lm'=>$data->time_lm,
                'dvvc'=>$data->dvvc,
                ],"id = $id");

        }else{
            $this->db->insert('tbldeadline',$data);
        }
        print_r(json_encode(["status"=>"success"]));

    }

	
    public function get(){
        print_r(json_encode($this->db->get('tbldeclare')->result()));
    }
	
	
    public function get_one($id){
        $this->db->where('id', $id);

        print_r(json_encode($this->db->get('tbldeclare')->row()));
    }
	public function deadline_get_one($id){
        $this->db->where('id', $id);

        print_r(json_encode($this->db->get('tbldeadline')->row()));
    }

	
    public function delete($id){

        $this->db->delete('tbldeclare',['id'=>$id]);

    }
    public function delete_deadline($id){

        $this->db->delete('tbldeadline',['id'=>$id]);

    }

}
