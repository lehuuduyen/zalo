<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Report_cod_sum extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('contracts_model');
    }

    /* List all contracts */
    public function index()
    {
        $data['date_end'] = date('Y-m-d');
        $date = new DateTime($data['date_end']);;
        date_sub($date, date_interval_create_from_date_string('30 days'));
        $data['date_start'] = date_format($date, 'Y-m-d');

        $data['title']         = 'CÔNG NỢ COD TỔNG';
        $this->load->view('admin/report_cod_sum/manage', $data);
    }

    public function table($clientid = "")
    {
        $this->app->get_table_data('report_cod_sum', [
            'clientid' => $clientid,
        ]);
    }

}
