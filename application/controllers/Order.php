<?php

defined('BASEPATH') or exit('No direct script access allowed');


class Order extends ClientsController
{

    public function __construct()
    {
        parent::__construct();

    }

    public function index()
    {
        $this->load->view('order');
//        $this->view('order');
//        $this->layout();
    }


}
