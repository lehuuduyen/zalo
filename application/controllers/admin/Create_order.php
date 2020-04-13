<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Create_order extends AdminController
{
    public function __construct()
    {
      parent::__construct();
    }
    public function add_new_region(){

      if ($this->input->server('REQUEST_METHOD') == "POST") {

        $query = $this->db->get_where('tblregion_excel', array('city' => $_POST['city'] , 'district' => $_POST['district'] , 'region_id' => $_POST['region_id']) )->row();

        if ($query === NULL) {

          $city = $_POST['city_region'];
          $district = $_POST['district_region'];
          $region_id = $_POST['region_id'];

          $data_add  = array('city ' => $city , 'district' =>  $district , 'region_id' => $region_id );


          $id = $this->db->insert('tblregion_excel', $_POST);

          if ($id) {
            $this->session->set_flashdata('success_default_region',1);
          }
          redirect(admin_url('/create_order'));
        }else {
          $this->session->set_flashdata('error_default_region',1);

        }

      }

      $data['tbldeclared_region'] = $query = $this->db->get('tbldeclared_region')->result();

      $this->load->view('admin/create_order/add_new_region',$data);


    }
    public function delete($id) {
      $query = $this->db->get_where('tbl_create_order', array('id' => $id))->result()[0];
      $code = $query->code;
      $customerToken = $this->db->get_where('tblcustomers', array('id' => $query->customer_id))->result()[0]->token_customer;
      $data = array('code' => $code );
      $curl = curl_init();

      curl_setopt($curl, CURLOPT_URL, 'https://api.mysupership.vn/v1/partner/orders/cancel');
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
      curl_setopt($curl, CURLOPT_POST, 1);

      $headers = array();
      $headers[] = 'Accept: application/json';
      $headers[] = 'Authorization: Bearer '.$customerToken;
      $headers[] = 'Content-Type: application/json';
      curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
      $response = curl_exec($curl);
      curl_close($curl);

      if (empty($response)) {
        $this->session->set_flashdata('delete_order_error',1);
      } else {
          $response = json_decode($response, true);
          if($response['status'] == 'Success'){
              $this->db->set('status_cancel', 1);
              $this->db->where('id', $id);
              $update = $this->db->update('tbl_create_order');
              if ($update) {
                  $this->session->set_flashdata('delete_order',1);
              }
          } else{
              $this->session->set_flashdata('delete_order_error',2);
          }
      }
      redirect(admin_url('/create_order'));
    }

    public function add_default()
    {

      $_POST['mass_default'] = str_replace(",", "", $_POST['mass_default']);
      $_POST['volume_default'] = str_replace(",", "", $_POST['volume_default']);

      //add NEw
      if ($_POST['id_default'] == '') {
        unset($_POST['id_default']);

        $id = $this->db->insert('tbl_default_mass_volume', $_POST);

        if ($id) {
          $this->session->set_flashdata('success_default',1);
        }
      }else {



        $this->db->where('id', $_POST['id_default']);
        unset($_POST['id_default']);
        $update = $this->db->update('tbl_default_mass_volume', $_POST);

        if ($update) {
          $this->session->set_flashdata('success_default',1);
        }

      }

      redirect(admin_url('/create_order'));

    }

    public function check_soc($soc='') {
      $this->db->select('*');
      $this->db->where('soc', $soc);
      $search_result = $this->db->get('tbl_create_order')->result();
      echo json_encode($search_result);
    }

    public function api_create_order($data,$token) {



      unset($data['pickup_commune']);

      if ($data['value'] === "NaN") {
        $data['value'] = 0;
      }


      $curl = curl_init();

      curl_setopt($curl, CURLOPT_URL, 'https://api.mysupership.vn/v1/partner/orders/add');
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
      curl_setopt($curl, CURLOPT_POST, 1);

      $headers = array();
      $headers[] = 'Accept: application/json';
      $headers[] = 'Authorization: Bearer '.$token;
      $headers[] = 'Content-Type: application/json';
      curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);



      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);

      if ($err) {
        return $err;
      } else {
        return $response;
      }
    }



    public function add()
    {
      $token = $_POST['token'];
      unset($_POST['token']);
      $_POST['user_created'] = get_staff_user_id();
      $this->db->insert('tbl_create_order', $_POST);
      $id = $this->db->insert_id();
      if ($id) {
        $curl_status = $this->api_create_order($_POST,$token);

        if (json_decode($curl_status)->status === 'Error') {
          $this->db->delete('tbl_create_order', array('id' => $id));
          echo $curl_status;
          die();
        }else {
          $today  = date('Y-m-d');

          $this->db->set('code', json_decode($curl_status)->results->code);
          $this->db->set('created', $today);
          $this->db->where('id', $id);
          $update = $this->db->update('tbl_create_order');

          echo json_encode(array('success' => 'ok' , 'code' => json_decode($curl_status)->results->code));
          die();
        }

      }else {
        echo 'Luư dữ liệu không thành công';
        die();
      }

    }


    public function check_customer_policy_exits($id) {

      $this->db->select('*');
      $this->db->where('customer_id', $id);
      $search_result = $this->db->get('tblcustomer_policy')->result();

      if (sizeof($search_result) === 0) {

        echo 'custommer_no';
        die();
      }
      else {
        echo json_encode($search_result[0]);
        die();
      }
      //
    }


    public function search_region() {
      $this->db->select('*');
      $this->db->where('city', $_GET['province']);
      $this->db->where('district', $_GET['district']);
      $search_result = $this->db->get('tblregion_excel')->row();


      if ($search_result) {
        $this->db->select('*');
        $this->db->where('id', $search_result->region_id);
        $region = $this->db->get('tbldeclared_region')->row();


        $this->db->select('*');
        $this->db->where('id_policy', $_GET['policy_id']);
        $this->db->where('id_region', $region->id);
        $data_region = $this->db->get('tbldata_region')->row();

        $region->data_region = $data_region;

        echo json_encode($region);
      }else {
        $error = array('error' => true );
        echo json_encode($error);
      }


    }

    public function get_province() {


      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.mysupership.vn/v1/partner/areas/province",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
          "Accept: */*",
        ),
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);

      if ($err) {
        echo "cURL Error #:" . $err;
      } else {
        if ($this->input->is_ajax_request()) {
          echo json_encode(json_decode($response)->results);
        }

        $result = json_decode($response)->results;
        return $result;
      }
    }


    public function get_district_by_hd($code) {

      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.mysupership.vn/v1/partner/areas/district?province=".$code,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
          "Accept: */*",
        ),
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);
      curl_close($curl);
      if ($err) {

      } else {

        $result = json_decode($response)->results;

        if ($this->input->is_ajax_request()) {
          echo json_encode($result);
          die();
        }else {
          return $result;
        }



      }

    }

    public function get_commune_by_hd($code)
    {

      $curl = curl_init();
      //
      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.mysupership.vn/v1/partner/areas/commune?district=".$code,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
          "Accept: */*",
        ),
      ));
      //
      $response = curl_exec($curl);
      $err = curl_error($curl);
      curl_close($curl);
      if ($this->input->is_ajax_request()) {
        echo json_encode(json_decode($response)->results);
        die();
      }else {
        return json_decode($response)->results;
      }


    }




    public function index() {

      if ($this->input->is_ajax_request()) {
        if ($_POST['enable_filter']) {
          $this->app->get_table_data('_create_order_filter');
        }else {
          $this->app->get_table_data('_create_order');
        }

      }


      $province = $this->get_province();
      // $district_hd = $this->get_district_by_hd($code_hd);
      // $dataCustomers = $this->db->get_where('tblcustomers', array('active' => 1))->result();






      $default_data = $query = $this->db->get('tbl_default_mass_volume')->result();
      $this->db->select('*');
      $this->db->select('tblcustomers.id as id');
      $this->db->from('tblcustomers');
      $this->db->where('active' , 1);
      $this->db->join('tblcustomer_policy', 'tblcustomers.id = tblcustomer_policy.customer_id', 'left');
      $dataCustomers = $this->db->get()->result_array();



      $data = array('custommer' => $dataCustomers );
      $data['province'] = $province;
      if ($default_data) {
        $data['default_data'] = $default_data[0];
      }else {
        $data['default_data'] = null;
      }
      $data['tbldeclared_region'] = $query = $this->db->get('tbldeclared_region')->result();


      $this->db->select('staffid,firstname,lastname');
      $this->db->from('tblstaff');
      $data['staffs'] = $staff = $this->db->get()->result();


      $data['date_end'] = date('Y-m-d');
      $date = new DateTime($data['date_end']);
      date_sub($date, date_interval_create_from_date_string('30 days'));
      $data['date_start'] = date_format($date, 'Y-m-d');
      $dataC = get_table_where('tblcustomers');



      foreach ($dataC as $key => $value) {
        $stringCut = $value['customer_shop_code'];

        if (strpos($value['customer_shop_code'], '-') !== false) {
          $dataC[$key]['display_shop_code'] = trim(ltrim(strstr($stringCut, '-', false), '-'));
        }else {
          $dataC[$key]['display_shop_code'] = $value['customer_shop_code'];
        }

      }

      $data['customer'] = $dataC;


      $this->load->view('admin/create_order/index',$data);
    }

    public function export_exel_orders(){
        $params = $this->input->get();
        $this->load->model('create_order_model');
        $data = $this->create_order_model->get_orders_by_time($params);
        if(empty($data)){
            echo "Không có dữ liệu nào phù hợp";
            die();
        }
        $colums = array('A', 'B', 'C', 'D', 'E', 'F');
        include APPPATH . 'third_party/PHPExcel/PHPExcel.php';
        $this->load->library('PHPExcel');
        $objPHPExcel = new PHPExcel();
        for ($i = 0; $i < 5; $i++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($colums[$i])->setAutoSize(true);
        }

        $objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.2); // ~ 1.78cm
        $objPHPExcel->getActiveSheet()->getPageMargins()->setHeader(0.2); // ~1.02cm
        $objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.2); // ~
        $objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.2); // ~1.78cm
        $objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.2); // ~1.73cm
        $objPHPExcel->getActiveSheet()->getPageMargins()->setFooter(0); // ~1.02cm

        //end caách lề phiếu in

        $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

        //định dạng kiểu in ngang giấy A4

        $BStyle_not_border = array(
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '111112'),
                'size' => 10,
                'name' => 'Times New Roman'
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );
        $BStyle = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '111112'),
                'size' => 10,
                'name' => 'Times New Roman'
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $BStyle_not_center = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '111112'),
                'size' => 10,
                'name' => 'Times New Roman'
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );
        $BStyle_not_header = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),
            'font' => array(
                'bold' => false,
                'color' => array('rgb' => '111112'),
                'size' => 10,
                'name' => 'Times New Roman'
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $BStyle_header = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),
            'font' => array(
                'bold' => false,
                'color' => array('rgb' => '111112'),
                'size' => 10,
                'name' => 'Times New Roman'
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );
        $BStyle_not_header_left = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),
            'font' => array(
                'bold' => false,
                'color' => array('rgb' => '111112'),
                'size' => 10,
                'name' => 'Times New Roman'
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );
        $BStyle_not_header_right = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),
            'font' => array(
                'bold' => false,
                'color' => array('rgb' => '111112'),
                'size' => 10,
                'name' => 'Times New Roman'
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $Background_style = array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'F9F400')
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),
            'font' => array(
                'bold' => false,
                'color' => array('rgb' => '111112'),
                'size' => 10,
                'name' => 'Times New Roman'
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        for ($row = 0; $row <= count($dataPush); $row++) {
            $styleArray = [
                'font' => [
                    'size' => 12
                ]
            ];
            $objPHPExcel->getActiveSheet()
                ->getStyle("A2:E2")
                ->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(5);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(5);


            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(false)->setWidth(7);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(80);
            if(!empty($params['customer_id'])){
                $user = $this->create_order_model->get_customer_by_id($params['customer_id']);
            }
            if(!empty($user)){
                $TITLE = "BẢNG ĐƠN HÀNG KHÁCH HÀNG " . mb_strtoupper($user['customer_shop_code'], 'UTF-8') . " TỪ NGÀY {$params['startDate']} ĐẾN NGÀY {$params['endDate']}";
            } else{
                $TITLE = "BẢNG ĐƠN HÀNG KHÁCH HÀNG TỪ NGÀY {$params['startDate']} ĐẾN NGÀY {$params['endDate']}";
            }
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, $TITLE)->mergeCells('A1:E1')->getStyle('A1')->applyFromArray($BStyle_not_border);

            $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'STT')->getStyle('A2')->applyFromArray($Background_style);
            $objPHPExcel->getActiveSheet()->SetCellValue('B2', 'Ngày tạo')->getStyle('B2')->applyFromArray($Background_style);
            $objPHPExcel->getActiveSheet()->SetCellValue('C2', 'Mã đơn hàng')->getStyle('C2')->applyFromArray($Background_style);
            $objPHPExcel->getActiveSheet()->SetCellValue('D2', 'Trả Shop')->getStyle('D2')->applyFromArray($Background_style);
            $objPHPExcel->getActiveSheet()->SetCellValue('E2', 'Nội dung')->getStyle('E2')->applyFromArray($Background_style);
        }
        $j = 3;
        foreach ($data as $rom => $v) {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . ($j), ($rom + 1))->getStyle('A' . $j)->applyFromArray($BStyle_not_header);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . ($j), (date_format(date_create($v['date_create']), "d-m-Y")))->getStyle('B' . $j)->applyFromArray($BStyle_not_header_left);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . ($j), ($v['code_supership']))->getStyle('C' . $j)->applyFromArray($BStyle_not_header_left);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . ($j), (intval($v['collect']) - intval($v['hd_fee_stam'])))->getStyle('D' . $j)->applyFromArray($BStyle_not_header)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->setCellValue('E' . ($j), ("Thu hộ : " . number_format(intval($v['collect'])) . ", Phí: " . number_format(intval($v['hd_fee_stam'])) . ", (KL:{$v['mass']}, {$v['receiver']} {$v['phone']} - {$v['district']} - {$v['city']})"))->getStyle('E' . $j)->applyFromArray($BStyle_not_header_left);
            $j++;
        }
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . ($j), '')->getStyle('A' . ($j))->applyFromArray($Background_style);
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . ($j), '')->getStyle('B' . ($j))->applyFromArray($Background_style);
        $objPHPExcel->getActiveSheet()->SetCellValue('C' . ($j), '')->getStyle('C' . ($j))->applyFromArray($Background_style);
        $objPHPExcel->getActiveSheet()->SetCellValue('D' . ($j), '')->getStyle('D' . ($j))->applyFromArray($Background_style);
        $objPHPExcel->getActiveSheet()->SetCellValue('E' . ($j), '')->getStyle('E' . ($j))->applyFromArray($Background_style);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $j, 'Tổng')->mergeCells('A' . $j . ':C' . $j)->getStyle('A' . $j)->getNumberFormat()->setFormatCode('#,##0')->applyFromArray($Background_style);

        $objPHPExcel->getActiveSheet()->SetCellValue('D' . ($j), '=sum(D3:D' . ($j - 1) . ')')->getStyle('D' . ($j))->applyFromArray($Background_style)->getNumberFormat()->setFormatCode('#,##0');
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, ($j+2), 'Số tài khoản: ')->mergeCells('A'.($j+2).':B'.($j+2))->getStyle('A'.($j+2));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, ($j+2), ' '.$user['customer_number_bank'])->mergeCells('C'.($j+2).':D'.($j+2));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, ($j+3), 'Tên tài khoản: ')->mergeCells('A'.($j+3).':B'.($j+3));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, ($j+3), ' '.$user['customer_id_bank'])->mergeCells('C'.($j+3).':D'.($j+3));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, ($j+4), 'Tên ngân hàng: ')->mergeCells('A'.($j+4).':B'.($j+4));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, ($j+4), ' '.$user['customer_name_bank'])->mergeCells('C'.($j+4).':D'.($j+4));

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        if(!empty($user)){
            header('Content-Disposition: attachment;filename="DS Đơn Tạo - ' . $user['customer_shop_code'] . ' Từ ' . $params['startDate'] . ' Đến ' . $params['endDate'] . '.xls"');
        } else{
            header('Content-Disposition: attachment;filename="DS Đơn Tạo - Từ ' . $params['startDate'] . ' Đến ' . $params['endDate'] . '.xls"');
        }
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');
        exit();
    }
}
