<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Returns extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    /* This is admin dashboard view */
    public function index()
    {
        $data = [];
        $this->load->model('Order_model');
        $order_model = new Order_model();
        $data['customers'] = $order_model->getCustomer();
        $data['list_status'] = $order_model->getStatus();

        //get date
        $now = date('Y-m-d');
        $date = new DateTime($now);
        $days = 7;
        date_sub($date, date_interval_create_from_date_string($days . ' days'));
        $date_from = date_format($date, 'Y-m-d');
        $date_to = date('Y-m-d');
        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;

        $data['list_status'] = $order_model->getStatus();
        $data['city'] = $order_model->getCity();
        $data['regions'] = $order_model->getRegion();
        $this->load->view('admin/orders/return', $data);
    }
    /* This is admin dashboard view */
    public function printPdf()
    {
        $result=[];
        $codeReturn = $_GET['code_return'];

        $this->load->model('Order_model');
        $order_model = new Order_model();
        $data = $order_model->getOrderReturn($codeReturn);

        foreach($data as $key => $orderReturn){
            $maDon = $order_model->getMaDon($orderReturn->code_return,$orderReturn->shop);
            foreach ($maDon as $keyMaDon => $value){
                $code_supership = $this->formatMaDon($value->code_supership);
                $data[$key]->list_code_supership[]=$code_supership;
        }
        }
        $result['data']=$data;
        $this->load->view('admin/orders/return_pdf', $result);
    }
    public function getPaymentTable(){
        $data = $this->input->get('data');
        $data =explode(",",$data);
        $this->load->model('Order_model');
        $order_model = new Order_model();
        $result = $order_model->getPayment($data);
        header('Content-Type: application/json');
        echo json_encode($result);

    }
    public function getTableDetail(){
        $listCodeReturn=[];
        $resultCodeReturn=[];
        $listtTablePhieu = [];
        $jsonData = $this->input->get('jsonData');
        $data = json_decode($jsonData);
        $this->load->model('Order_model');
        $order_model = new Order_model();
        $resultOrderReturn = $order_model->getReturnTableDetail($data);
        foreach ($resultOrderReturn as $value){
            $listCodeReturn[]=$value->code_return;
        }
        foreach ($listCodeReturn as $codeReturn){
            $resultCodeReturn[]= $order_model->getOrderReturn($codeReturn);
        }
        $listtTablePhieu[] = $resultCodeReturn[0];
        $result['table_detail']=$resultOrderReturn;
        $result['table_phieu']=$listtTablePhieu;

        header('Content-Type: application/json');
        echo json_encode($result);

    }

    public function createOrderReturn()
    {

        $result =[];
        $data = $this->input->get('data');
        $data =explode(",",$data);
        $is_check = true;
        $this->load->model('Order_model');
        $order_model = new Order_model();

        $codeReturn = substr(strtotime("now"), 1);

        foreach ($data as $orderShopId){
            $data =$order_model->createOrderReturn($codeReturn,$orderShopId);
            if(!$data){
                $is_check=false;
            }
        }

        if($is_check){
           $result = $order_model->getOrderReturn($codeReturn);
           //them ben shipper
            $order_model->createShipper($result);
        }
        header('Content-Type: application/json');
        print_r(json_encode($result));
    }
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public function getList()
    {
        $jsonData = $_GET['jsonData'];
        $data = json_decode($jsonData);
        $this->load->model('Order_model');
        $order_model = new Order_model();
        $data->id = "";
        if ($data->region != "") {
            $data = $order_model->getShopByRegion($data);
        }
        $order = $order_model->getOrder($data);
        $result = new stdClass();
        $result->data = $order;
        header('Content-Type: application/json');
        echo json_encode($result);
    }
    public function formatMaDon($madon){
        return explode('.',$madon)[1];
    }
    public function printExcel()
    {
        $codeReturn = $_GET['code_return'];

        $this->load->model('Order_model');
        $order_model = new Order_model();
        $result = $order_model->getOrderReturn($codeReturn);


        include APPPATH . 'third_party/PHPExcel/PHPExcel.php';
        $this->load->library("PHPExcel");

        $inputFileName = FCPATH.'assets/mau_hang_hoan_shop2.xlsx';
        $fileType = 'Excel2007';
        try{
            $inputFileType 	= 	PHPExcel_IOFactory::identify($inputFileName);
            $objReader 		= 	PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel 	= 	$objReader->load($inputFileName);
        }catch(Exception $e){
            die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $objWorksheet->getRowDimension(1)->setRowHeight(-1);


//   them row     $objWorksheet->insertNewRowBefore(13 + 1, 1);
        // Change the file
        $val = $objWorksheet->getCell('D10')->getValue();

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B7', "(Phiếu số: ".$result[0]->code_return.") ")
            ->setCellValue('B8', "Ngày tạo: ".date('d/m/Y',strtotime($result[0]->created_at)));
        $row = 10;
        foreach($result as $key => $orderReturn){
            $rowOld =$row;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$row,$orderReturn->shop."\n"."Số Đơn Trả: ".$orderReturn->total." Đơn" );
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$row,$val );
            $maDon = $order_model->getMaDon($orderReturn->code_return,$orderReturn->shop);
            foreach ($maDon as $keyMaDon => $value){
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$row,$this->formatMaDon($value->code_supership) );
                if(count($maDon) > $keyMaDon+1){
                    $row++;
                }
            }

            $objWorksheet->mergeCells("B$rowOld:B$row");
            $objWorksheet->mergeCells("D$rowOld:D$row");
            if(count($result) > $key+1){
                $row++;
            }
        }

        $objPHPExcel->getActiveSheet()->removeRow($row+1,10000);

        $style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );

        $objWorksheet->getStyle("B9:D$row")->applyFromArray($style);

        $objWorksheet->getStyle("B10:D$row")->applyFromArray(
            array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array('rgb' => '000000')
                    )
                )
            )
        );

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $fileType);
        $objWriter->save(FCPATH.'assets/mau_hang_hoan_shop.xlsx');
        $data['url']=base_url()."assets/mau_hang_hoan_shop.xlsx";

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function edit()
    {
        $note = $_GET['note'];
        $id = $_GET['id'];
        $data['note'] = json_decode($note);
        $data['id'] = $id;
        $this->load->model('Order_model');
        $order_model = new Order_model();
        $order = $order_model->updateOrder($data);
        header('Content-Type: application/json');
        echo json_encode($order);
    }

    /* Chart weekly payments statistics on home page / ajax */
    public function weekly_payments_statistics($currency)
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->dashboard_model->get_weekly_payments_statistics($currency));
            die();
        }
    }
}
