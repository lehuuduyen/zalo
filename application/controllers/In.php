<?php
class In extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
//        $this->lang->load('english_lang');
    }

    public function index()
    {
        $data['date_start'] = '';
        $data['date_end'] = $date_end = date('Y-m-d');
        $data['date_start'] = date("Y-m-d", strtotime("$date_end -7 day"));
        $this->load->view('admin/in/manage', $data);
    }

    public function print_data() {
        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . 'PHPExcel' . DIRECTORY_SEPARATOR . 'PHPExcel.php');
        $this->load->helper('security');
        if (!empty($_FILES['file']))
        {

            $arrayData = [];
            foreach($_FILES['file']['name'] as $KF => $VF)
            {
                $continue = false;
                $fullfile = $_FILES['file']['tmp_name'][$KF];

                $extension = strtoupper(pathinfo($_FILES['file']['name'][$KF], PATHINFO_EXTENSION));
                if($extension != 'XLSX' && $extension != 'XLS'){
                    $this->session->set_flashdata('warning', _l('Không đúng định dạng excel'));
                    redirect($_SERVER["HTTP_REFERER"]);
                    return;
                }

                $inputFileType = PHPExcel_IOFactory::identify($fullfile); $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objReader->setReadDataOnly(true);
                $objPHPExcel = $objReader->load("$fullfile");
                $total_sheets = $objPHPExcel->getSheetCount();
                $allSheetName = $objPHPExcel->getSheetNames();
                $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
                $highestRow = $objWorksheet->getHighestRow();
                $highestColumn = $objWorksheet->getHighestColumn();
                $highestColumnIndex = PHPExcel_Cell::columnIndexFromString('AC');
                $array_colum = array();
                for ($row = 1; $row <= $highestRow; ++$row)
                {
                    for ($col = 0; $col < $highestColumnIndex; ++$col)
                    {
                        $value = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                        $array_colum[$row - 1][$col] = $value;
                    }
                }
                if(count($array_colum) >= 0) {

                    $this->db->insert('table_print', ['date' => date('Y-m-d H:i:s')]);
                    $id_detail = $this->db->insert_id();
                    $ktArray = [];
                    foreach ($array_colum as $key => $row) {
                        if($key > 0) {
                            if(empty($row[2])) {
                                continue;
                            }

                            if(empty($ktArray[$row[2]])) {
                                $ktArray[$row[2]] = true;
                            }
                            else {
                                continue;
                            }
                            $arrayData = ['id_detail' => $id_detail];
                            $arrayData['network'] = $row[1];
                            $arrayData['phone'] = $row[2];
                            $arrayData['name'] = $row[3];
                            $arrayData['note'] = $row[4];
                            $this->db->insert('tbltable_print_items', $arrayData);
                        }
                    }
                }
            }
            $this->session->set_flashdata('success', ('Thêm Thành công'));
            set_alert('success',  ('Thêm Thành công'));
            redirect(base_url('in'));

        }
        set_alert('success',  ('Thêm không Thành công'));
        redirect(base_url('in'));
    }

    public function getTable() {
        if($this->input->get('date_start')) {
            $date_start = $this->input->get('date_start');
            $date_start = to_sql_date(str_replace('-', '/', $date_start));
            $this->db->where('DATE_FORMAT(date, "%Y-%m-%d") >= "'. $date_start .'"');
        }
        if($this->input->get('date_end')) {
            $date_end = $this->input->get('date_end');
            $date_end = to_sql_date(str_replace('-', '/', $date_end));
            $this->db->where('DATE_FORMAT(date, "%Y-%m-%d") <= "'. $date_end .'"');
        }
        $this->db->order_by('date', 'desc');
        $data = $this->db->get('table_print')->result_array();
        $this->load->view('admin/in/table', ['data' => $data]);
    }

    public function print_out() {
        $id = $this->input->get('id');
        $id = explode('-', $id);
        if(!empty($id)) {
            $this->db->where_in('id', $id);
            $data['data'] = $this->db->get('table_print')->result_array();
            foreach($data['data'] as $key => $value) {
                $this->db->order_by('id', 'asc');
                $data['data'][$key]['detail'] = $this->db->get('tbltable_print_items')->result();
            }
        }
        $this->load->view('admin/in/print-out', $data);
    }

}
