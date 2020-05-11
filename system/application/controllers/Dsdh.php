<?php
class Dsdh extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
        $this->load->helper('date');
        $days = now() - 60*60*24*7;
        $this->db->select('code_supership as code');
        $this->db->where('date_create >=', date('Y-m-d 00:00:00', $days));
        $this->db->where('date_create <=', date('Y-m-d 23:59:59', now()));
        $list = $this->db->get('tblorders_shop')->result();
        echo json_encode($list);
    }
	
	public function order_vtp(){
        $this->load->helper('date');
        $nameStatus = array(
            'Hủy', 'Đã Đối Soát Giao Hàng', 'Đã Trả Hàng', 'Đã Trả Hàng Một Phần'
        );
        $this->db->where_not_in('status', $nameStatus);
        $this->db->where('DVVC', 'VTP');
		$this->db->select('code_ghtk as code');
        $dateStr = date('Y-m-d');
        $date = new DateTime($dateStr);
        date_sub($date, date_interval_create_from_date_string("90 days"));
        $this->db->where('date_create >=', date_format($date, 'Y-m-d H:i:s'));
        $list = $this->db->get('tblorders_shop')->result();
        echo json_encode($list);
    }
}
