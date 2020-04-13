<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Debt_suppliers extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        if (!has_permission('debt_suppliers', '', 'view')) {
                access_denied('Debt suppliers');
        }
        $this->db->select('tblsuppliers.*');
        $this->db->join('tblsuppliers','tblsuppliers.id = tblpurchase_order.suppliers_id');
        $this->db->group_by('tblsuppliers.id');
        $data['suppliers'] = $this->db->get('tblpurchase_order')->result_array();
        $data['title']          = _l('ch_debt_suppliers');
        $this->load->view('admin/debt_suppliers/manage', $data);
    }
  	public function table()
    {
        if (!has_permission('debt_suppliers', '', 'view')) {
                ajax_access_denied();
        }
        $this->app->get_table_data('debt_suppliers');
    }
    public function count_debt()
    {
        $id = array();
        $suppliers_id = $this->input->post('suppliers_id');
        if(!empty($suppliers_id))
        {
        $id = explode(',', $suppliers_id);
        }
        $this->db->select('SUM(tblimport.total) as total_import,(COALESCE(SUM(tblimport.amount_paid),0)+ COALESCE(SUM(tblimport.price_other_expenses),0) + COALESCE(SUM(tblpurchase_invoice.amount_paid),0))  as amount_paid_import,SUM(tblimport.price_other_expenses) as price_other_expenses_import');
        $this->db->where('((tblimport.status_pay != 2 AND tblimport.red_invoice = 0) or (tblpurchase_invoice.status != 2 AND tblimport.status_pay = 0))');
        if(!empty($id))
        {
        $this->db->where_in('tblsuppliers.id',$id);    
        }
        $this->db->having('(total_import - amount_paid_import) > 0');
        $this->db->join('tblimport','tblimport.suppliers_id = tblsuppliers.id', 'left');
        $this->db->join('tblpurchase_invoice','tblpurchase_invoice.id = tblimport.red_invoice', 'left');
        $this->db->group_by('tblsuppliers.id');
        $count  = $this->db->get('tblsuppliers')->result_array();  

        $this->db->select('SUM(tblimport.total) as total_import,(COALESCE(SUM(tblimport.amount_paid),0)+ COALESCE(SUM(tblimport.price_other_expenses),0) + COALESCE(SUM(tblpurchase_invoice.amount_paid),0))  as amount_paid_import,SUM(tblimport.price_other_expenses) as price_other_expenses_import');
        if(!empty($id))
        {
        $this->db->where_in('tblsuppliers.id',$id);    
        }
        $this->db->where('((tblimport.status_pay != 2 AND tblimport.red_invoice = 0) or (tblpurchase_invoice.status != 2 AND tblimport.status_pay = 0))');
        $this->db->where('tblsuppliers.debt_limit > 0 AND tblsuppliers.debt_limit < ((select(SUM(tblimport.total)) from tblimport where tblimport.suppliers_id=tblsuppliers.id ) -(select((COALESCE(SUM(tblimport.amount_paid),0)+ COALESCE(SUM(tblimport.price_other_expenses),0) + COALESCE(SUM(tblpurchase_invoice.amount_paid),0))) from tblimport left JOIN tblpurchase_invoice ON tblpurchase_invoice.id=tblimport.red_invoice where ((tblimport.status_pay != 2 AND tblimport.red_invoice = 0) or (tblpurchase_invoice.status != 2 AND tblimport.status_pay = 0)) AND tblimport.suppliers_id=tblsuppliers.id))');
        $this->db->having('(total_import - amount_paid_import) > 0');
        $this->db->join('tblimport','tblimport.suppliers_id = tblsuppliers.id', 'left');
        $this->db->join('tblpurchase_invoice','tblpurchase_invoice.id = tblimport.red_invoice', 'left');
        $this->db->group_by('tblsuppliers.id');
        $count_limit  = $this->db->get('tblsuppliers')->result_array(); 
        $data['count_limit'] = count($count_limit);
        $data['all'] = count($count);
        echo json_encode($data);
    }
    public function get_total_debt()
    {
        $this->db->select('SUM(tblimport.total) as total_import,(COALESCE(SUM(tblimport.amount_paid),0)+ COALESCE(SUM(tblimport.price_other_expenses),0) + COALESCE(SUM(tblpurchase_invoice.amount_paid),0))  as amount_paid_import,SUM(tblimport.price_other_expenses) as price_other_expenses_import');
        $this->db->where('((tblimport.status_pay != 2 AND tblimport.red_invoice = 0) or (tblpurchase_invoice.status != 2 AND tblimport.status_pay = 0))');
        $this->db->having('(total_import - amount_paid_import) > 0');
        $this->db->join('tblimport','tblimport.suppliers_id = tblsuppliers.id', 'left');
        $this->db->join('tblpurchase_invoice','tblpurchase_invoice.id = tblimport.red_invoice', 'left');
        $count  = $this->db->get('tblsuppliers')->row();
        $data['debt'] = number_format($count->total_import);
        $data['payment'] = number_format($count->amount_paid_import  + $count->price_other_expenses_import);
        $data['left'] = number_format($count->total_import - ($count->amount_paid_import  + $count->price_other_expenses_import));
        echo json_encode($data);die;
    }
}