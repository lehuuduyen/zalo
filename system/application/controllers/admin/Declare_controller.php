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

        $province = $this->get_province();
        $data['province'] = $province;

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

    public function getDeadline()
    {
        $this->load->model('Deadline_model');
        $Deadline_model = new Deadline_model();
        $result = $Deadline_model->get();
        print_r(json_encode($result));

    }

    public function add()
    {
        $jsonData = $_GET['data'];
        $data = json_decode($jsonData);
        if (isset($data->id)) {
            $id = $data->id;
            $result = $this->db->update('tbldeclare', [
                'name' => $data->name,
                'fail' => $data->fail,
                'success' => $data->success,
                'debit' => $data->debit,
                'status_end' => $data->status_end,
                'color' => $data->color,
            ], "id = $id");

        } else {
            $this->db->insert('tbldeclare', $data);
        }
        print_r(json_encode(["status" => "success"]));

    }

    public function deadline_add()
    {
        $jsonData = $_GET['data'];
        $data = json_decode($jsonData);
        if (isset($data->id)) {
            $id = $data->id;
            $result = $this->db->update('tbldeadline', [
                'name' => $data->name,
                'note' => $data->note,
                'time_nt' => $data->time_nt,
                'time_nm' => $data->time_nm,
                'time_lm' => $data->time_lm,
                'dvvc' => $data->dvvc,
            ], "id = $id");

        } else {
            $this->db->insert('tbldeadline', $data);
        }
        print_r(json_encode(["status" => "success"]));

    }


    public function get()
    {
        print_r(json_encode($this->db->get('tbldeclare')->result()));
    }


    public function get_one($id)
    {
        $this->db->where('id', $id);

        print_r(json_encode($this->db->get('tbldeclare')->row()));
    }

    public function deadline_get_one($id)
    {
        $this->db->where('id', $id);

        print_r(json_encode($this->db->get('tbldeadline')->row()));
    }


    public function delete($id)
    {
        $ac = $this->input->get('t');
        if ($ac != 'warehouse')
            $this->db->delete('tbldeclare', ['id' => $id]);
        else {
            $this->db->where('id', $id);
            $info = $this->db->get('tbl_warehouse_send')->row();
            if(!empty($info->is_default)){
                $this->db->where('id <>', $id);
                $this->db->where('is_default', 0);
                $this->db->order_by('rand()');
                $this->db->limit(1);
                $query = $this->db->get('tbl_warehouse_send')->row();

                $data = array('is_default' => 1);

                $this->db->where('id', $query->id);
                $this->db->update('tbl_warehouse_send', $data);
            }
            $this->db->delete('tbl_warehouse_send', ['id' => $id]);
        }
        redirect(base_url('admin/declare_controller'));
    }

    public function delete_deadline($id)
    {

        $this->db->delete('tbldeadline', ['id' => $id]);

    }

    public function getWarehouse()
    {
        $listWarehouse = $this->db->get('tbl_warehouse_send')->result();
        print_r(json_encode($listWarehouse));
    }


    public function get_province()
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.mysupership.vn/v1/partner/areas/province',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'Accept: */*',
            ],

        ]);

        $response = curl_exec($curl);

        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo 'cURL Error #:' . $err;
        } else {
            if ($this->input->is_ajax_request()) {

                echo json_encode(json_decode($response)->results);

            }

            $result = json_decode($response)->results;

            return $result;

        }

    }

}
