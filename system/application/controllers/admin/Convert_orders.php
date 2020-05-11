<?php

class Convert_orders extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $default_data = $query = $this->db->get('tbl_default_mass_volume')->result();

        $this->db->select('tbl_default_mass_volume_ghtk.mass_fake as mass_fake_ghtk ');
        $default_data2 = $query = $this->db->get('tbl_default_mass_volume_ghtk')->result();

        $this->db->select('tbl_default_mass_volume_vpost.mass_fake as mass_fake_vpost');
        $default_data3 = $query = $this->db->get('tbl_default_mass_volume_vpost')->result();
        $default_data[0]->mass_fake_ghtk = $default_data2[0]->mass_fake_ghtk;
        $default_data[0]->mass_fake_vpost = $default_data3[0]->mass_fake_vpost;

        if ($default_data) {
            $data['default_data'] = $default_data[0];

        } else {

            $data['default_data'] = null;

        }
        $this->load->view('admin/create_order/convert_orders', $data);
    }
}
