<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Order extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
//        $this->load->model('dashboard_model');
    }
    public function getListTab4()
    {
        $jsonData = $_GET['jsonData'];
        $data = json_decode($jsonData);
        $this->load->model('Order_model');
        $order_model = new Order_model();

        $order = $order_model->getOrderTab4($data);
        $result = new stdClass();
        $result->data = $order;
        header('Content-Type: application/json');
        echo json_encode($result);
    }
    public function getListTab4Error($customer_id)
    {
        $this->load->model('Order_model');
        $order_model = new Order_model();

        $order = $order_model->getOrderTab4Error($customer_id);
        $result = new stdClass();
        $result->data = $order;
        header('Content-Type: application/json');
        echo json_encode($result);
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
        $this->load->view('admin/orders/order', $data);
    }
    /* This is admin dashboard view */
    public function print_create_order()
    {

        $string = $_GET['list_id'];
        $json = explode(',',$string);
        $this->load->model('Order_model');
        $order_model = new Order_model();
        $result['data'] = $order_model->getCreateOrderById($json);
        foreach ($result['data'] as $key => $val){
            $result['data'][$key]['required_code_in']=explode('.',$val['required_code'])[1];
        }
        $this->load->view('print_create_order', $result);
    }

    public function getDistrict()
    {
        $city = $_GET['city'];
        $this->load->model('Order_model');
        $order_model = new Order_model();
        $district = $order_model->getDistrict($city);
        $json['data'] = $district;
        print_r(json_encode($json));
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
        $order = $order_model->getOrderMultiStatus($data);
        $result = new stdClass();
        $result->data = $order;
        header('Content-Type: application/json');
        echo json_encode($result);
    }

    public function exportExcel()
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

        include APPPATH . 'third_party/PHPExcel/PHPExcel.php';
        $this->load->library("PHPExcel");
        $object = new PHPExcel();
//        $object->getActiveSheet()->setTitle("Danh Sách Đơn Hàng KH $data->customer Tạo Từ Ngày ".date('d/m/y',strtotime($data->date_form))." đến ngày ".date('d/m/y',strtotime($data->date_to)));

        $object->setActiveSheetIndex(0);

        $table_columns = array(
            "STT",
            "Tên Shop",
            "Mã Đối Soát",
            "Mã Đơn Khách Hàng",
            "Mã Đơn SuperShip",
            "Trạng Thái",
            "Thời Gian Tạo",
            "Khối Lượng",
            "Thu Hộ",
            "Trị Giá",
            "Trả Trước",
            "Phí Vận Chuyển",
            "Phí Bảo Hiểm",
            "Phí Chuyển Hoàn",
            "Khuyến Mãi",
            "Gói Cước",
            "Trả Phí",
            "Người Nhận",
            "Số Điện Thoại",
            "Địa chỉ",
            "Phường/Xã",
            "Quận/Huyện",
            "Tỉnh/Thành Phố",
            "Ghi Chú Giao Hàng",
            "Kho Hàng",
            "Sản Phẩm",
            "Ngày Đối Soát",
            "Loại",
            "Tỉnh/Thành Gửi",
            "Ngày Tính Nợ",
            "Ghi Chú Nội Bộ",
            "Phí Tổng Tính",
            "Doanh Thu Tổng Tính",
            "Doanh Thu Thực",
            "Chi Nhánh",
            "Cập Nhật Lần Cuối",
            "Đơn Vị Vận Chuyển",
            "Mã Đơn DVVC",
            "Nhóm Vùng Miền",
        );

        $column = 0;

        foreach ($table_columns as $field) {
            $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
            $column++;
        }
        //background
        $object->getActiveSheet()
            ->getStyle('A1:AM1')
            ->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('FF0001');
        //color
        $object->getActiveSheet()->getStyle("A1:AM1")->getFont()->setBold(true)
            ->setSize(12)
            ->getColor()->setRGB('FFFFFF');
        //auto width
        foreach ($object->getWorksheetIterator() as $worksheet) {

            $object->setActiveSheetIndex($object->getIndex($worksheet));

            $sheet = $object->getActiveSheet();
            $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(true);
            /** @var PHPExcel_Cell $cell */
            foreach ($cellIterator as $cell) {
                if ($cell->getColumn() == "T" || $cell->getColumn() == "Y" || $cell->getColumn() == "B" || $cell->getColumn() == "S" || $cell->getColumn() == "X" || $cell->getColumn() == "AE" || $cell->getColumn() == "Z") {
                    $sheet->getColumnDimension($cell->getColumn())->setWidth(30);

                } else {
                    $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
                }

            }
        }
        //auto width
        $excel_row = 2;

        foreach ($order as $key => $row) {
            $phi = $row->hd_fee;
            if ($row->hd_fee == null) {
                $phi = $row->hd_fee_stam;
            } else if ($row->is_hd_branch == 0) {
                $phi = $row->pay_transport;
            }
            $nameShop = 'Shop chi nhánh khác';
            $name_region = "";
            if ($row->is_hd_branch == 1) {
                $name_region = $row->name_region;
                $nameShop = 'Shop chi nhánh mình';
            }
            //format
            $row->date_debits = date('d/m/Y', strtotime($row->date_debits));
            $row->last_time_updated = date('d/m/Y', strtotime($row->last_time_updated));
            $row->date_create = date('d/m/Y H:i:s', strtotime($row->date_create));

            $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $key + 1);
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->shop);
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->control_code);
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->code_orders);
            $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->code_supership);
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->status);
            $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $row->date_create);
            $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $row->mass);
            $object->getActiveSheet()
                ->getStyle('H' . $excel_row)
                ->getNumberFormat()
                ->setFormatCode('#,##0');
            $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $row->collect);
            $object->getActiveSheet()
                ->getStyle('I' . $excel_row)
                ->getNumberFormat()
                ->setFormatCode('#,##0');
            $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $row->value);
            $object->getActiveSheet()
                ->getStyle('J' . $excel_row)
                ->getNumberFormat()
                ->setFormatCode('#,##0');
            $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $row->prepay);
            $object->getActiveSheet()
                ->getStyle('K' . $excel_row)
                ->getNumberFormat()
                ->setFormatCode('#,##0');
            $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $phi);
            $object->getActiveSheet()
                ->getStyle('L' . $excel_row)
                ->getNumberFormat()
                ->setFormatCode('#,##0');
            $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $row->insurance);
            $object->getActiveSheet()
                ->getStyle('M' . $excel_row)
                ->getNumberFormat()
                ->setFormatCode('#,##0');
            $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, $row->pay_refund);
            $object->getActiveSheet()
                ->getStyle('N' . $excel_row)
                ->getNumberFormat()
                ->setFormatCode('#,##0');
            $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $row->sale);
            $object->getActiveSheet()
                ->getStyle('O' . $excel_row)
                ->getNumberFormat()
                ->setFormatCode('#,##0');
            $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $row->pack_data);
            $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, $row->payer);
            $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $row->receiver);
            $object->getActiveSheet()->setCellValueExplicit("S" . $excel_row, $row->phone, PHPExcel_Cell_DataType::TYPE_STRING);
            $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $row->address);
            $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $row->ward);
            $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, $row->district);
            $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $row->city);
            $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, $row->note);
            $object->getActiveSheet()->setCellValueByColumnAndRow(24, $excel_row, $row->warehouses);
            $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $row->product);
            $object->getActiveSheet()->setCellValueByColumnAndRow(26, $excel_row, $row->control_date);
            $object->getActiveSheet()->setCellValueByColumnAndRow(27, $excel_row, $row->type);
            $object->getActiveSheet()->setCellValueByColumnAndRow(28, $excel_row, $row->city_send);
            $object->getActiveSheet()->setCellValueByColumnAndRow(29, $excel_row, $row->date_debits);
            $object->getActiveSheet()->setCellValueByColumnAndRow(30, $excel_row, $row->note_delay);
            $object->getActiveSheet()->setCellValueByColumnAndRow(31, $excel_row, $row->revenue_calculated);
            $object->getActiveSheet()
                ->getStyle('AF' . $excel_row)
                ->getNumberFormat()
                ->setFormatCode('#,##0');
            $object->getActiveSheet()->setCellValueByColumnAndRow(32, $excel_row, $row->doanh_thu_sps_tinh);
            $object->getActiveSheet()
                ->getStyle('AG' . $excel_row)
                ->getNumberFormat()
                ->setFormatCode('#,##0');
            $object->getActiveSheet()->setCellValueByColumnAndRow(33, $excel_row, $row->real_revenue);
            $object->getActiveSheet()
                ->getStyle('AH' . $excel_row)
                ->getNumberFormat()
                ->setFormatCode('#,##0');
            $object->getActiveSheet()->setCellValueByColumnAndRow(34, $excel_row, $nameShop);
            $object->getActiveSheet()->setCellValueByColumnAndRow(35, $excel_row, $row->last_time_updated);
            $object->getActiveSheet()->setCellValueByColumnAndRow(36, $excel_row, $row->DVVC);
            $object->getActiveSheet()->setCellValueByColumnAndRow(37, $excel_row, $row->code_ghtk);
            $object->getActiveSheet()->setCellValueByColumnAndRow(38, $excel_row, $name_region);

            $excel_row++;
        }

        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="ĐƠN HÀNG.xls"');
        $object_writer->save('php://output');
    }
    public function exportExcelKH()
    {
        $jsonData = $_GET['jsonData'];
        $data = json_decode($jsonData);
        $this->load->model('Order_model');
        $order_model = new Order_model();
        $data->id = "";
        if ($data->region != "") {
            $data = $order_model->getShopByRegion($data);
        }

        $order = $order_model->getOrderMultiStatus($data);

        include APPPATH . 'third_party/PHPExcel/PHPExcel.php';
        $this->load->library("PHPExcel");
        $object = new PHPExcel();

        $object->setActiveSheetIndex(0);

        $table_columns = array(
            "STT",
            "Mã Đơn Khách Hàng",
            "Mã Đơn SuperShip",
            "Trạng Thái",
            "Thời Gian Tạo",
            "Khối Lượng",
            "Thu Hộ",
            "Trị Giá",
//            "Trả Trước",
            "Phí Vận Chuyển",
//            "Phí Bảo Hiểm",
//            "Phí Chuyển Hoàn",
//            "Khuyến Mãi",
//            "Gói Cước",
//            "Trả Phí",
            "Người Nhận",
            "Số Điện Thoại",
            "Địa chỉ",
            "Phường/Xã",
            "Quận/Huyện",
            "Tỉnh/Thành Phố",
            "Ghi Chú Giao Hàng",
            "Kho Hàng",
            "Sản Phẩm",
//            "Ngày Đối Soát",
//            "Loại",
            "Tỉnh/Thành Gửi",
            "Ngày Tính Nợ",
            "Nhóm Vùng Miền",
        );

        $column = 0;

        foreach ($table_columns as $field) {
            $object->getActiveSheet()->setCellValueByColumnAndRow($column, 2, $field);
            $column++;
        }
        //background
        $object->getActiveSheet()
            ->getStyle('A2:AC2')
            ->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('FF0001');
        //color
        $object->getActiveSheet()->getStyle("A2:AC2")->getFont()->setBold(true)
            ->setSize(12)
            ->getColor()->setRGB('FFFFFF');
        $object->getActiveSheet()->getStyle("A1:AC1")->getFont()->setBold(true)
            ->setSize(15);
        //auto width
        foreach ($object->getWorksheetIterator() as $worksheet) {

            $object->setActiveSheetIndex($object->getIndex($worksheet));

            $sheet = $object->getActiveSheet();
            $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(true);
            /** @var PHPExcel_Cell $cell */
            foreach ($cellIterator as $cell) {
                if ($cell->getColumn() == "T" || $cell->getColumn() == "Y" || $cell->getColumn() == "B" || $cell->getColumn() == "S" || $cell->getColumn() == "X" || $cell->getColumn() == "R" || $cell->getColumn() == "W") {
                    $sheet->getColumnDimension($cell->getColumn())->setWidth(30);

                } else {
                    $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
                }

            }
        }
        //auto width
        $excel_row = 3;
        $dateFrom=date('d/m/y',strtotime($data->date_form));
        $dateTo=date('d/m/y',strtotime($data->date_to));
        $title ="Danh Sách Đơn Hàng KH $data->customer Tạo Từ Ngày $dateFrom đến ngày $dateTo" ;
        $object->getActiveSheet()->setCellValueByColumnAndRow(0, 1, $title);
        $object->getActiveSheet()->mergeCells('A1:AC1');
        foreach ($order as $key => $row) {
            $phi = $row->hd_fee;
            if ($row->hd_fee == null) {
                $phi = $row->hd_fee_stam;
            } else if ($row->is_hd_branch == 0) {
                $phi = $row->pay_transport;
            }
            $name_region = "";
            if ($row->is_hd_branch == 1) {
                $name_region = $row->name_region;
            }
            //format
            $row->date_debits = ($row->date_debits !="")?date('d/m/Y', strtotime($row->date_debits)):"";
            $row->last_time_updated = date('d/m/Y', strtotime($row->last_time_updated));
            $row->date_create = date('d/m/Y H:i:s', strtotime($row->date_create));

            $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $key + 1);
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->code_orders);
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->code_supership);
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->status);
            $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->date_create);
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->mass);
            $object->getActiveSheet()
                ->getStyle('F' . $excel_row)
                ->getNumberFormat()
                ->setFormatCode('#,##0');
            $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $row->collect);
            $object->getActiveSheet()
                ->getStyle('G' . $excel_row)
                ->getNumberFormat()
                ->setFormatCode('#,##0');
            $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $row->value);
            $object->getActiveSheet()
                ->getStyle('H' . $excel_row)
                ->getNumberFormat()
                ->setFormatCode('#,##0');
//            $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $row->prepay);
//            $object->getActiveSheet()
//                ->getStyle('I' . $excel_row)
//                ->getNumberFormat()
//                ->setFormatCode('#,##0');
            $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $phi);
            $object->getActiveSheet()
                ->getStyle('I' . $excel_row)
                ->getNumberFormat()
                ->setFormatCode('#,##0');
//            $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $row->insurance);
//            $object->getActiveSheet()
//                ->getStyle('K' . $excel_row)
//                ->getNumberFormat()
//                ->setFormatCode('#,##0');
//            $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $row->pay_refund);
//            $object->getActiveSheet()
//                ->getStyle('L' . $excel_row)
//                ->getNumberFormat()
//                ->setFormatCode('#,##0');
//            $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $row->sale);
//            $object->getActiveSheet()
//                ->getStyle('M' . $excel_row)
//                ->getNumberFormat()
//                ->setFormatCode('#,##0');
//            $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, $row->pack_data);
//            $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $row->payer);
            $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $row->receiver);
            $object->getActiveSheet()->setCellValueExplicit("K" . $excel_row, $row->phone, PHPExcel_Cell_DataType::TYPE_STRING);
            $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $row->address);
            $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $row->ward);
            $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, $row->district);
            $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $row->city);
            $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $row->note);
            $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, $row->warehouses);
            $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $row->product);
//            $object->getActiveSheet()->setCellValueByColumnAndRow(24, $excel_row, $row->control_date);
//            $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $row->type);
            $object->getActiveSheet()->setCellValueByColumnAndRow(18, $excel_row, $row->city_send);
            $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $row->date_debits);
            $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $name_region);

            $excel_row++;
        }
        $fileName = $data->customer." ". date('d_m_y',strtotime($data->date_form))."-".date('d_m_y',strtotime($data->date_to));
        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$fileName.'.xls"');
        $object_writer->save('php://output');
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
    public function update()
    {
        $note = $_GET['note'];
        $id = $_GET['id'];
        $data['note'] = json_decode($note);
        $data['id'] = $id;
        $this->load->model('Order_model');
        $order_model = new Order_model();
        $order = $order_model->updateCreateOrder($data);
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
