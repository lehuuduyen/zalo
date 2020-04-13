<?php

defined('BASEPATH') or exit('No direct script access allowed');

use app\services\ValidatesContact;

class Import_data extends AdminController
{
    public function __construct()
    {
        parent::__construct();

    }
    public function getDetailByShopSuperShip($code='')
    {
      return $this->db->get_where('tblorders_shop', array('code_supership' => $code))->row();
    }

    public function real_revenue($data) {

      $dataShop = $this->getDetailByShopSuperShip($data[0]);
      if ($dataShop) {
        if ($dataShop->is_hd_branch == 1) {
          if ($dataShop->hd_fee > 0) {
            $return = $dataShop->hd_fee - $data[2] + $data[1] - 0.05*$data[1];
            return $return;
          }else if ($dataShop->hd_fee == 0){
            return $data[1] - $data[2] - 0.05* $data[1];
          }
        }else {
          return 0.95* $data[1];
        }
      }else {
        return false;
      }


    }


    public function revenue_allocation() {
      ini_set('max_execution_time', 99999999);
      error_reporting(E_ALL);
      ini_set('display_errors', 'On');
      ini_set('memory_limit', '-1');
        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . 'PHPExcel' . DIRECTORY_SEPARATOR . 'PHPExcel.php');
        $this->load->helper('security');
        if (!empty($_FILES['file_revenue_allocation'])) {
            $count_add = 0;
            $count_update = 0;
            $varString = "";
            foreach($_FILES['file_revenue_allocation']['name'] as $KF => $VF) {

                $continue = false;
                $fullfile = $_FILES['file_revenue_allocation']['tmp_name'][$KF];

                $extension = strtoupper(pathinfo($_FILES['file_revenue_allocation']['name'][$KF], PATHINFO_EXTENSION));
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
                $highestColumnIndex = PHPExcel_Cell::columnIndexFromString('AT');
                $array_colum = array();


                for ($row = 1; $row <= $highestRow; ++$row) {
                    for ($col = 0; $col < $highestColumnIndex; ++$col)
                    {
                        $value = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                        $array_colum[$row - 1][$col] = $value;
                    }
                }



                if(count($array_colum) >= 1) {

                  $colum_format = [
                    "Mã Đơn SuperShip",
                    "Doanh Thu Tổng Tính",
                    "Phí Tổng Tính"
                  ];

                  $checkFormat = array_diff($colum_format,$array_colum[0]);



                  if (sizeof($checkFormat) !== 0) {
                    echo json_encode(array('success' => false, 'alert_type' => 'danger', 'message' => 'Định dạng file không hợp lệ' ));
                    die();
                  }




                  unset($array_colum[0]);


                  $dataUpdate =  array();

                  foreach ($array_colum as $key => $rowData) {

                    if ($rowData[0] != null) {

                      if ($this->real_revenue($rowData) != false) {
                        $rowPush = array('code_supership' => $rowData[0] , 'doanh_thu_sps_tinh' => $rowData[1] , 'revenue_calculated' => $rowData[2] ,'real_revenue' => $this->real_revenue($rowData));

                        $dataUpdate[] = $rowPush;
                      }


                    }


                  }




                  foreach ($dataUpdate as $key => $value) {
                    if (!$value['code_supership']) {
                      unset($dataUpdate[$key]);
                    }
                  }



                  $update_status = $this->db->update_batch('tblorders_shop', $dataUpdate, 'code_supership');




                  if ($update_status > 0) {
                    echo json_encode(array('success' => true, 'alert_type' => 'success', 'message' => $update_status. ' đơn hàng  cập nhật ' ));
                    die();
                  }else {
                    echo json_encode(array('success' => true, 'alert_type' => 'success', 'message' => 'Không có dữ liệu được cập nhật' ));
                    die();
                  }


                }

            }


        }
        echo json_encode(array('success' => false, 'alert_type' => 'danger', 'message' => 'Không tìm thấy file', 'file_not_type' => '' ));die();
    }

    public function index()
    {
        $data['title'] = 'Import data';
        $this->load->view('admin/import_data/import', $data);
    }
    public function add_file_orders()
    {
        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . 'PHPExcel' . DIRECTORY_SEPARATOR . 'PHPExcel.php');
        $this->load->helper('security');

        if (!empty($_FILES['file_order']))
        {
            $count_add = 0;
            $count_update = 0;
            $varString = "";
            foreach($_FILES['file_order']['name'] as $KF => $VF)
            {
                $continue = false;
                $fullfile = $_FILES['file_order']['tmp_name'][$KF];

                $extension = strtoupper(pathinfo($_FILES['file_order']['name'][$KF], PATHINFO_EXTENSION));
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
                if(count($array_colum) >= 0)
                {

                    $colum_table = $this->db->list_fields('tblorders_shop');
                    foreach ($array_colum as $key => $row)
                    {
                        if($key == 0)
                        {
                            $array_title = [
                                'STT',
                                'Tên Shop',
                                'Mã Đối Soát',
                                'Mã Đơn Khách Hàng',
                                'Mã Đơn SuperShip',
                                'Trạng Thái',
                                'Thời Gian Tạo',
                                'Khối Lượng',
                                'Thu Hộ',
                                'Trị Giá',
                                'Trả Trước',
                                'Phí Vận Chuyển',
                                'Phí Bảo Hiểm',
                                'Phí Chuyển Hoàn',
                                'Khuyến Mãi',
                                'Gói Cước',
                                'Trả Phí',
                                'Người Nhận',
                                'Số Điện Thoại',
                                'Địa chỉ',
                                'Phường/Xã',
                                'Quận/Huyện',
                                'Tỉnh/Thành Phố',
                                'Ghi Chú',
                                'Kho Hàng',
                                'Sản Phẩm',
                                'Ngày Đối Soát',
                                'Loại',
                                'Tỉnh/Thành Gửi',
                            ];
                            foreach($colum_table as $kColum => $vColum)
                            {
                                if($kColum > 0)
                                {
                                    if(!empty($array_title[$kColum]) && $array_title[$kColum] != $row[$kColum])
                                    {
                                        $continue = true;
                                        $varString.= $VF.',';
                                        break;
                                    }
                                }
                            }
                        }
                        else if($key > 0)
                        {
                            if($continue == true)
                            {
                                break;
                            }
                            $array_add = [];
                            foreach($colum_table as $kColum => $vColum) {
                                if($kColum > 0) {
                                    $array_add[$vColum] = $row[$kColum];
                                }
                            }

                            $array_add['is_hd_branch'] = 0;
                            if(!empty($row[32]) && $row[32] == 'Kho Hải Dương - Tỉnh Hải Dương') {
                                $array_add['is_hd_branch'] = 1;
                            }
                            if(!empty($row[32])) {
                                $array_add['warehouse_send'] = $row[32];
                            }

                            $array_add['date_create'] = to_sql_date($array_add['date_create'], true);

                            $array_add['control_date'] = to_sql_date($array_add['control_date'], true);
                            $this->db->where('code_supership', $array_add['code_supership']);
                            $orders_shop = $this->db->get('tblorders_shop')->row();
                            if(!empty($orders_shop))
                            {
                                continue;
                                $this->db->where('id', $orders_shop->id);
                                $this->db->update('tblorders_shop', $array_add);
                                if($this->db->affected_rows() > 0)
                                {
                                    ++$count_update;
                                }
                            }
                            else
                            {
                                if(empty($array_add['status_status']))
                                {
                                    $array_add['status_status'] = 0;
                                }
                                $array_add['status_debts'] = "Đơn Hàng";
                                $array_add['DVVC'] = "SPS";
                                $this->db->insert('tblorders_shop', $array_add);
                                if($this->db->insert_id())
                                {
                                    ++$count_add;
                                }
                            }
                        }
                    }
                }
            }
            if(!empty($varString))
            {
                echo json_encode(array('success' => false, 'alert_type' => 'success', 'message' => 'Thêm '.($count_add). ' đơn hàng và cập nhật '.$count_update.' đơn hàng và '.trim($varString, ',').' Không đúng định dạng', 'file_not_type' => trim($varString, ',')));die();
            }

            echo json_encode(array('success' => true, 'alert_type' => 'success', 'message' => 'Thêm '.($count_add). ' đơn hàng và cập nhật '.$count_update.' đơn hàng', 'file_not_type' => ''));die();

        }
        echo json_encode(array('success' => false, 'alert_type' => 'danger', 'message' => 'Không tìm thấy file', 'file_not_type' => ''));die();
    }

    public function add_file_delivery_orders()
    {
        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . 'PHPExcel' . DIRECTORY_SEPARATOR . 'PHPExcel.php');
        $this->load->helper('security');
        if (!empty($_FILES['file_delivery_order']))
        {
            $count_add = 0;
            $count_update = 0;
            $varString = "";
            foreach($_FILES['file_delivery_order']['name'] as $KF => $VF)
            {
                $continue = false;
                $fullfile = $_FILES['file_delivery_order']['tmp_name'][$KF];

                $extension = strtoupper(pathinfo($_FILES['file_delivery_order']['name'][$KF], PATHINFO_EXTENSION));
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
                $highestColumnIndex = PHPExcel_Cell::columnIndexFromString('J');
                $array_colum = array();
                for ($row = 1; $row <= $highestRow; ++$row)
                {
                    for ($col = 0; $col < $highestColumnIndex; ++$col)
                    {
                        $value = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                        $array_colum[$row - 1][$col] = $value;
                    }
                }
                if(count($array_colum) >= 0)
                {

                    $colum_table = $this->db->list_fields('tbldelivery_orders');
                    foreach ($array_colum as $key => $row)
                    {
                        if($key == 0)
                        {
                            $array_title = [
                                'STT',
                                'Mã Giao Hàng',
                                'Người Giao',
                                'Tên Công Ty/Tên Shop',
                                'Mã Đơn Khách Hàng',
                                'Mã Đơn SuperShip',
                                'Trạng Thái Thực Tế',
                                'Thu Hộ',
                                'Trạng Thái Báo Cáo',
                                'Thu Hộ Báo Cáo'
                            ];
                            foreach($colum_table as $kColum => $vColum)
                            {
                                if($kColum > 0)
                                {
                                    if(!empty($array_title[$kColum]) && $array_title[$kColum] != $row[$kColum])
                                    {
                                        $continue = true;
                                        $varString.= $VF.',';
                                        break;
                                    }
                                }
                            }
                        }
                        else
                        {
                            if($continue == true)
                            {
                                break;
                            }
                            $array_add = [];
                            foreach($colum_table as $kColum => $vColum)
                            {
                                if($kColum > 0)
                                {
                                    $array_add[$vColum] = $row[$kColum];
                                }
                            }
                            if(empty($array_add['code_delivery']) || empty( $array_add['code_supership']))
                            {
                                continue;
                            }

//                            $this->db->where('code_delivery', $array_add['code_delivery']);
                            $this->db->where('code_supership', $array_add['code_supership']);
                            $delivery_orders = $this->db->get('tbldelivery_orders')->row();
                            if(!empty($delivery_orders))
                            {
                                $this->db->where('id', $delivery_orders->id);
                                $this->db->update('tbldelivery_orders', $array_add);
                                if($this->db->affected_rows() > 0)
                                {
                                    ++$count_update;
                                }
                            }
                            else
                            {
                                $this->db->insert('tbldelivery_orders', $array_add);
                                if($this->db->insert_id())
                                {
                                    ++$count_add;
                                }
                            }
                        }
                    }
                }
            }
            if(!empty($varString))
            {
                echo json_encode(array('success' => false, 'alert_type' => 'success', 'message' => 'Thêm '.($count_add). ' đơn giao hàng và cập nhật '.$count_update.' đơn giao hàng và '.trim($varString, ',').' Không đúng định dạng', 'file_not_type' => trim($varString, ',')));die();
            }

            echo json_encode(array('success' => true, 'alert_type' => 'success', 'message' => 'Thêm '.($count_add). ' đơn giao hàng và cập nhật '.$count_update.' đơn giao hàng', 'file_not_type' => ''));die();

        }
        echo json_encode(array('success' => false, 'alert_type' => 'danger', 'message' => 'Không tìm thấy file', 'file_not_type' => '' ));die();
    }

    public function add_file_delivery_list()
    {
        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . 'PHPExcel' . DIRECTORY_SEPARATOR . 'PHPExcel.php');
        $this->load->helper('security');
        if (!empty($_FILES['file_delivery_list']))
        {
            $count_add = 0;
            $count_update = 0;
            $varString = "";
            foreach($_FILES['file_delivery_list']['name'] as $KF => $VF)
            {
                $continue = false;
                $fullfile = $_FILES['file_delivery_list']['tmp_name'][$KF];

                $extension = strtoupper(pathinfo($_FILES['file_delivery_list']['name'][$KF], PATHINFO_EXTENSION));
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
                if(count($array_colum) >= 0)
                {

                    $colum_table = $this->db->list_fields('tbldelivery_list');
                    foreach ($array_colum as $key => $row)
                    {
                        if($key == 0)
                        {
                            $array_title = [
                                'TT',
                                'Tỉnh/Thành Phố',
                                'Kho Làm Việc',
                                'Giao Hàng',
                                'Tạo Bởi',
                                'Mã Giao Hàng',
                                'Tạo Lúc',
                                'Số Đơn',
                                'Thu Dự Kiến',
                                'Thu Báo Cáo',
                                'Đơn Thành Công',
                                'Trạng Thái',
                                'Tiền Cập Nhật',
                                'Trạng Thái Thu',
                                'Tiền Đã Thu',
                                'Thu Bởi',
                                'Thu Lúc'
                            ];
                            foreach($colum_table as $kColum => $vColum)
                            {
                                if($kColum > 0)
                                {
                                    if(!empty($array_title[$kColum]) && $array_title[$kColum] != $row[$kColum])
                                    {
                                        $continue = true;
                                        $varString.= $VF.',';
                                        break;
                                    }
                                }
                            }
                        }
                        else
                        {
                            if($continue == true)
                            {
                                break;
                            }
                            $array_add = [];
                            foreach($colum_table as $kColum => $vColum)
                            {
                                if($kColum > 0)
                                {
                                    $array_add[$vColum] = $row[$kColum];
                                }
                            }
                            $array_add['date_create'] = to_sql_date($array_add['date_create'], true);
                            $array_add['date_collection'] = to_sql_date($array_add['date_collection'], true);
                            $this->db->where('code_delivery ', $array_add['code_delivery']);
                            $delivery_orders = $this->db->get('tbldelivery_list')->row();
                            if(!empty($delivery_orders))
                            {
                            	if(isset($array_add['created_bill'])) {
                            		unset($array_add['created_bill']);
                            	}
                                $this->db->where('id', $delivery_orders->id);
                                $this->db->update('tbldelivery_list', $array_add);
                                if($this->db->affected_rows() > 0)
                                {
                                    ++$count_update;
                                }
                            }
                            else
                            {
                            	if(!isset($array_add['created_bill'])) {
                            		$array_add['created_bill'] = 0;
                            	}
                                $this->db->insert('tbldelivery_list', $array_add);
                                if($this->db->insert_id())
                                {
                                    ++$count_add;
                                }
                            }
                        }
                    }
                }
            }

            if(!empty($varString))
            {
                echo json_encode(array('success' => false, 'alert_type' => 'success', 'message' => 'Thêm '.($count_add). ' danh sách giao hàng và cập nhật '.$count_update.' danh sách giao hàng và '.trim($varString, ',').' Không đúng định dạng', 'file_not_type' => trim($varString, ',')));die();
            }


            echo json_encode(array('success' => true, 'alert_type' => 'success', 'message' => 'Thêm '.($count_add). ' danh sách giao hàng và cập nhật '.$count_update.' danh sách giao hàng', 'file_not_type' => ''));die();

        }
        echo json_encode(array('success' => false, 'alert_type' => 'danger', 'message' => 'Không tìm thấy file', 'file_not_type' => '' ));die();
    }

    public function view()
    {
        $data = [];

        $this->db->select('distinct(shop)');
        $data['shop'] = $this->db->get('tblorders_shop')->result_array();
        $data['title'] = 'Danh sách đơn hàng đã upload';

        $this->db->select('status');
        $this->db->group_by('status');
        $data['status_orders'] = $this->db->get('tblorders_shop')->result_array();

        $this->db->select('city_send');
        $this->db->group_by('city_send');
        $data['city_send'] = $this->db->get('tblorders_shop')->result_array();

        $this->db->select('city');
        $this->db->group_by('city');
        $data['city'] = $this->db->get('tblorders_shop')->result_array();

        $this->db->select('deliver');
        $this->db->group_by('deliver');
        $data['deliver'] = $this->db->get('tbldelivery_orders')->result_array();


        $this->load->view('admin/import_data/manage', $data);
    }

    public function table()
    {
        $this->app->get_table_data('import_data');
    }

}
