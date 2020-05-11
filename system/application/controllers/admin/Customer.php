<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Customer extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }


    public function array_column(array $array, $columnKey, $indexKey = null)
    {
        $result = array();
        foreach ($array as $subArray) {
            $result[] = $subArray->customer_shop_code;
        }

        return $result;
    }


    public function read_html()
    {
        if ($this->input->is_ajax_request()) {
            $dataInSert = [];
            $dataUpdate = [];
            $cusExcel = $_POST['dataSendShop'];
            $cusCurrent = $this->db->select('customer_shop_code')->get('tblcustomers')->result();


            for ($i=0; $i < sizeof($cusExcel) ; $i++) {
                if (array_search($cusExcel[$i]['customer_shop_code'], $this->array_column($cusCurrent, 'customer_shop_code')) === false) {
                    if (array_search($cusExcel[$i]['customer_shop_code'], $this->array_column($dataInSert, 'customer_shop_code')) === false) {
                        $data = new \stdClass;
                        $data->customer_shop_code = $cusExcel[$i]['customer_shop_code'];
                        $data->customer_shop_name = $cusExcel[$i]['shop'];
                        $data->customer_phone = $cusExcel[$i]['customer_phone'];
                        $data->customer_email = $cusExcel[$i]['customer_email'];
                        $data->customer_password = '12345678';
                        $data->active = 0;
                        $dataInSert[] = $data;
                    }
                } else {
                    if (array_search($cusExcel[$i]['customer_shop_code'], $this->array_column($dataUpdate, 'customer_shop_code')) === false) {
                        $data = new \stdClass;
                        $data->customer_shop_code = $cusExcel[$i]['customer_shop_code'];
                        $data->customer_shop_name = $cusExcel[$i]['shop'];
                        $data->customer_phone = $cusExcel[$i]['customer_phone'];
                        $data->customer_email = $cusExcel[$i]['customer_email'];
                        $dataUpdate[] = $data;
                    }
                }
            }

            // echo "<pre>";
            // var_dump($dataUpdate);
            // die();
            if (sizeof($dataInSert) > 0) {
                $insert = $this->db->insert_batch('tblcustomers', $dataInSert);
            }

            if (sizeof($dataUpdate) > 0) {
                $update = $this->db->update_batch('tblcustomers', $dataUpdate, 'customer_shop_code');
            }

            die();
        }
        $this->load->view('admin/customer/import.php');
    }

    public function check_customer()
    {
        $dataInSert = [];
        $cusExcel =
      $this->db->select('shop')->get_where('tblorders_shop', array('city_send' => 'Tỉnh Hải Dương'))->result();

        $cusCurrent =
      $this->db->select('customer_shop_name')->get('tblcustomers')->result();

        for ($i=0; $i < sizeof($cusExcel) ; $i++) {
            if (!array_search($cusExcel[$i]->shop, $this->array_column($cusCurrent, 'customer_shop_name'))) {
                if (array_search($cusExcel[$i]->shop, $this->array_column($dataInSert, 'customer_shop_name')) === false) {
                    $data = new \stdClass;
                    $data->customer_shop_name = $cusExcel[$i]->shop;
                    $data->active = 0;
                    $dataInSert[] = $data;
                }
            }
        }



        if (sizeof($dataInSert) > 0) {
            $insert = $this->db->insert_batch('tblcustomers', $dataInSert);
        }


        die();
    }


    public function nonActive()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('customers_non_active');
        }
    }

    public function index()
    {
        $this->load->helper('fields_helper');

        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('customers');
        }

        $data['customer'] = get_table_where('tblcustomers');
        $data['staff'] = $this->db->get('tblstaff')->result();

        $this->load->view('admin/customer/index.php', $data);
    }


    public function add()
    {
        if (sizeof($_POST) == 0) {
            redirect(admin_url('customer'));
        }
        $staff = $this->db->get('tblstaff')->result();
        $data  = array('staff' => $staff);
        $this->load->library('form_validation');
        $this->load->helper('security');


        $this->form_validation->set_rules(
            'customer_email',
            'Email',
            'is_unique[tblcustomers.customer_email]',
            array(
                'is_unique'     => 'Email này đã tồn tại.'
            )
        );

        $this->form_validation->set_rules(
            'customer_phone',
            'Phone',
            'is_unique[tblcustomers.customer_phone]',
            array(
                'is_unique'     => 'Số Điện này đã tồn tại.'
            )
        );

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error_add', 1);

            $this->load->view('admin/customer/index.php', $data);
        } else {
            unset($_POST['search_employ']);
            $_POST['active'] = 1;
            $id = $this->db->insert('tblcustomers', $_POST);

            if ($id) {
                $this->session->set_flashdata('success', 1);
            }
            redirect(admin_url('customer'));
        }
    }

    public function delete($id)
    {
        $tables = array('tblcustomers');
        $this->db->where('id', $id);
        $delete = $this->db->delete($tables);
        $this->session->set_flashdata('delete_ok', 1);
        redirect(admin_url('/customer'));
    }

    public function getDataEdit($id)
    {
        $dataCustomer = $this->db->get_where('tblcustomers', array('id' => $id))->result()[0];
        echo json_encode($dataCustomer);
    }

    public function getEmploy($id)
    {
        $staff = $this->db->get_where('tblstaff', array('staffid' => $id))->result()[0];
        echo json_encode($staff);
    }

    public function edit($id)
    {
        if (sizeof($_POST) == 0) {
            redirect(admin_url('/customer'));
        } else {
            $staff = $this->db->get('tblstaff')->result();
            $data  = array('staff' => $staff);
            $this->load->library('form_validation');
            $this->load->helper('security');
            $original_value = $this->db->get_where('tblcustomers', array('id' => $id))->result()[0];
            $this->form_validation->set_rules('customer_email', 'customer_email', 'required');
            $this->form_validation->set_rules('customer_phone', 'customer_phone', 'required');
            if ($original_value->customer_email != $_POST['customer_email']) {
                $this->form_validation->set_rules(
                    'customer_email',
                    'Email',
                    'is_unique[tblcustomers.customer_email]',
                    array(
              'is_unique'     => 'Email này đã tồn tại.'
            )
                );
            }
            //
            if ($original_value->customer_phone != $_POST['customer_phone']) {
                $this->form_validation->set_rules(
                    'customer_phone',
                    'Phone',
                    'is_unique[tblcustomers.customer_phone]',
                    array(
              'is_unique'     => 'Số Điện này đã tồn tại.'
            )
                );
            }
            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error_add', 1);

                $this->load->view('admin/customer/index.php', $data);
            } else {
                unset($_POST['search_employ']);
                $_POST['active'] = 1;
                $this->db->where('id', $id);
                $this->db->update('tblcustomers', $_POST);
                $this->session->set_flashdata('success_edit', 1);
                redirect(admin_url('customer'));
            }
        }
    }
}
