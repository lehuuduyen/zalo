<?php

defined('BASEPATH') or exit('No direct script access allowed');

use app\services\ValidatesContact;

class Turndownload extends AdminController
{
    public function __construct()
    {
        parent::__construct();

    }

    public function index()
    {
        $data['title'] = 'Turn dùng download file nhanh';
        $this->load->view('turn/turndownload/manage', $data);
    }
    public function read_file() {
        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . 'PHPExcel' . DIRECTORY_SEPARATOR . 'PHPExcel.php');
        $this->load->helper('security');
        $result = [];

        if (!empty($_FILES['file_csv']))
        {

            $fullfile = $_FILES['file_csv']['tmp_name'];
            $extension = strtoupper(pathinfo($_FILES['file_csv']['name'], PATHINFO_EXTENSION));
            if($extension != 'XLSX' && $extension != 'XLS'){
                $this->session->set_flashdata('warning', lang('Không đúng định dạng excel'));
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
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString('Z');
            $array_colum = array();
            for ($row = 1; $row <= $highestRow; ++$row)
            {

                for ($col = 0; $col < $highestColumnIndex; ++$col)
                {
                    $value = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                    $array_colum[$row - 1][$col] = $value;
                }
            }
            $array_result = [];
            foreach ($array_colum as $key => $row)
            {
                if($key >= 1)
                {
                    if(!empty($row[5]) && $row[10] > 0 && $row[11] != 'Chưa Báo Cáo')
                    {
                        $this->db->where('tbldelivery_list.code_delivery', trim($row[5]));
                        $this->db->where('tbldelivery_orders.code_delivery', trim($row[5]));
                        $this->db->join('tbldelivery_orders', 'tbldelivery_orders.code_delivery = tbldelivery_list.code_delivery');
                        $this->db->join('tblorders_shop', 'tblorders_shop.code_supership = tbldelivery_orders.code_supership');
                        $kt_delivery_orders = $this->db->get('tbldelivery_list')->row();
                        if(empty($kt_delivery_orders))
                        {
                            $array_result[] = array(
                                'code' => $row[5],
                                'time' => to_sql_date($row[6],true)
                            );
                        }
                    }
                }
            }
            for ($i = 0; $i < count($array_result); $i++) {
                for ($j = $i; $j < count($array_result); $j++) {
                    if (strtotime($array_result[$i]['time']) > strtotime($array_result[$j]['time'])) {
                        $tam = $array_result[$j];
                        $array_result[$j] = $array_result[$i];
                        $array_result[$i] = $tam;
                    }
                }
            }


            if(count($array_result) > 0)
            {
                echo json_encode(array('success' => true, 'result' => ($array_result)));die();
            }

        }
        echo json_encode(array('success' => false));die();
    }
}
