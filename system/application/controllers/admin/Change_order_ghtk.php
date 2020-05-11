<?php

class Change_order_ghtk extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->view('admin/create_order/change_order_ghtk');
    }
}
