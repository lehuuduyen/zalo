<?php

// header('Content-Type: text/html; charset=utf-8');
defined('BASEPATH') or exit('No direct script access allowed');

class Stock extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('products_model');
        $this->load->model('items_model');
        $this->load->model('unit_model');
        $this->load->model('category_model');
        $this->load->model('manufactures_model');
        $this->load->model('purchases_model');
        $this->load->model('business_plan_model');
        $this->load->model('orders_model');
        $this->load->model('departments_model');
        $this->load->model('stock_model');
        // $this->lang->load('vietnamese/form_validation_lang');
        $this->image_types = 'gif|jpg|jpeg|png|tif';
        $this->allowed_file_size = '1024';
        $this->upload_path = get_upload_path_by_type('products');
        $this->datetime_now = time();
        $this->tnh = true;
    }

    public function exporting_producion()
    {
        $data['title'] = lang('tnh_exporting_stock_producion');
        $data['tnh'] = $this->tnh;
        $this->load->view('admin/stock/exporting_producion', $data);
    }

    public function getExportingProductions()
    {
        $this->datatables
            ->select("
                0 as number_records,
                tbl_suggest_exporting.id as id,
                tbl_suggest_exporting.date_convert_stock as date,
                tbl_suggest_exporting.reference_stock as reference_stock,
                tbl_suggest_exporting.reference_no as reference_no,
                tbl_productions_orders_details.reference_no as reference_production_detail,
                tbl_suggest_exporting.export_name as export_name,
                tblwarehouse.name as warehouse_name,
                tbl_suggest_exporting.note as note,
                CONCAT(tblstaff.firstname, ' ', tblstaff.lastname,'') as created_by,
                tbl_suggest_exporting.status_stock as status_stock,
                '' as user_status,
                tbl_suggest_exporting.type as type,
                '' as status_warehouse
            ", false)
            ->from('tbl_suggest_exporting')
            ->join('tbl_productions_orders_details', 'tbl_productions_orders_details.id = tbl_suggest_exporting.productions_orders_details_id', 'inner')
            ->join('tblstaff', 'tblstaff.staffid = tbl_suggest_exporting.convert_stock_by', 'left')
            ->join('tblwarehouse', 'tblwarehouse.id = tbl_suggest_exporting.warehouse_id', 'left');
            // ->join('tblstaff staff_status', 'staff_status.staffid = tbl_suggest_exporting.user_status', 'left');

        $this->datatables->where('tbl_suggest_exporting.status_stock IS NOT NULL');

        $view = '<a class="tnh-modal" title="'.lang('view').'" data-tnh="modal" data-toggle="modal" data-target="#myModal" href="'.base_url('admin/stock/view_exporting_production/$1').'"><i class="fa fa-file-text-o width-icon-actions"></i> '.lang('view').'</a>';

        $edit = '<a class="tnh-edit" title="'.lang('edit').'" href="'.base_url('admin/stock/edit_exporting_production/$1').'"><i class="fa fa-edit width-icon-actions"></i> '.lang('edit').'</a>';

        $delete = '<a type="button" class="po tnh-delete" title="'.lang('delete').'" data-container="body" data-html="true" data-toggle="popover" data-placement="left" data-content="
            <button href=\''.base_url('admin/stock/delete_suggest_exporting/$1').'\' class=\'btn btn-danger po-delete-json\'>'.lang('delete').'</button>
            <button class=\'btn btn-default po-close\'>'.lang('close').'</button>
        "><i class="fa fa-remove width-icon-actions"></i> '.lang('delete').'</a>';

        $actions = '
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle nav-link" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
            '.lang('actions').'
            <span class="caret"></span>
            </button>
            <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdownMenu1">
                <li>'.$view.'</li>
                <li>'.$edit.'</li>
                <li class="not-outside">'.$delete.'</li>
            </ul>
        </div>';

        $this->datatables->add_column('actions', $actions, 'id');
        $iDisplayStart = $this->input->post('iDisplayStart');
        $data = json_decode($this->datatables->generate());
        foreach ($data->aaData as $key => $value) {
            $data->aaData[$key][0] = ++$iDisplayStart;
        }
        echo json_encode($data);
    }

    public function view_exporting_production($id)
    {
        $suggest_exporting = $this->manufactures_model->rowSuggestExporting($id);
        $items = $this->manufactures_model->getSuggestExportingItemsView($id);
        $warehouse = $this->stock_model->rowWarehouse($suggest_exporting['warehouse_id']);
        $data['items'] = $items;
        $data['pod'] = $this->manufactures_model->rowProductionsOrdersDetais($suggest_exporting['productions_orders_details_id']);
        $data['created_by'] = get_staff_full_name($suggest_exporting['convert_stock_by']);
        $data['warehouse'] = $warehouse;
        if ($suggest_exporting['updated_by'] && $suggest_exporting['type'] == 2) {
            $data['updated_by'] = get_staff_full_name($suggest_exporting['updated_by']);
        }
        $data['suggest_exporting'] = $suggest_exporting;
        $data['id'] = $id;
        $this->load->view('admin/stock/view_exporting_production', $data);
    }

    public function add_exporting_production()
    {
        if ($this->input->post('add'))
        {
            $data = [];
            $this->form_validation->set_rules('reference_no', lang("tnh_reference_stock"), 'required|is_unique[tbl_suggest_exporting.reference_stock]');
            $this->form_validation->set_rules('date', lang("date"), 'required');
            $this->form_validation->set_rules('export_name', lang("tnh_export_name"), 'required');
            $this->form_validation->set_rules('warehouses', lang("tnh_warehouses"), 'required');
            if ($this->form_validation->run() == true)
            {
                // print_arrays($this->input->post());
                $reference_stock = $this->input->post('reference_no');
                $date = to_sql_date($this->input->post('date'), true);
                $productions_orders_detail_id = $this->input->post('productions_orders_detail_id');
                $note = $this->input->post('note');
                $export_name = $this->input->post('export_name');
                $warehouses = $this->input->post('warehouses');
                $items = $this->input->post('items_id');
                $total_quantity = 0;
                $count_items = 0;
                $total_quantity_exchange = 0;
                $type = 2;

                $errors = false;

                if (!empty($items)) {
                    foreach ($items as $key => $value) {
                        if (empty($value)) continue;
                        $unit_id = $this->input->post('unit_id')[$key];
                        $unit_parent_id = $this->input->post('unit_parent_id')[$key];
                        $number_exchange = $this->input->post('number_exchange')[$key];
                        $location = $this->input->post('locations')[$key];
                        $quantity_export = number_unformat($this->input->post('quantity')[$key]);
                        $arr_item = explode('__', $value);
                        $type_item = $arr_item[0];
                        $item_id = $arr_item[1];
                        if ($type_item == "semi_products_outside") {
                            $info_item = $this->products_model->rowProduct($item_id);
                        } else if ($type_item == "materials") {
                            $info_item = $this->items_model->rowMaterial($item_id);
                        }
                        if (empty($info_item)) continue;
                        if (empty($location)) {
                            $errors = lang('tnh_location_warehouse_required');
                            break;
                        }

                        $quantity_exchange = $quantity_export/$number_exchange;
                        $exporting_items[] = [
                            'type_item' => $type_item,
                            'item_id' => $item_id,
                            'item_code' => $info_item['code'],
                            'item_name' => $info_item['name'],
                            'unit_id' => $unit_id,
                            'quantity_export' => $quantity_export,
                            'unit_parent_id' => $unit_parent_id,
                            'number_exchange' => $number_exchange,
                            'quantity_exchange' => $quantity_exchange,
                            'location_id' => $location
                        ];
                        $total_quantity+= $quantity_export;
                        $total_quantity_exchange+= $quantity_exchange;
                    }
                }

                if (!empty($errors)) {
                    $data['result'] = 0;
                    $data['message'] = $errors;
                    echo json_encode($data); die;
                }

                if (empty($exporting_items)) {
                    $data['result'] = 0;
                    $data['message'] = lang('tnh_no_items');
                    echo json_encode($data); die;
                }
                $count_items = count($exporting_items);
                $fields = [
                    'productions_orders_details_id' => $productions_orders_detail_id,
                    'reference_no' => null,
                    'reference_stock' => $reference_stock,
                    'date' => $date,
                    'export_name' => $export_name,
                    'note' => $note,
                    'status' => 'un_approved',
                    'total_quantity' => $total_quantity,
                    'count_items' => $count_items,
                    'total_quantity_exchange' => $total_quantity_exchange,
                    'created_by' => get_staff_user_id(),
                    'date_created' => date('Y-m-d H:i:s'),
                    'convert_stock_by' => get_staff_user_id(),
                    'date_convert_stock' => date('Y-m-d H:i:s'),
                    'status_stock' => 'un_approved_stock',
                    'type' => '2',
                    'date_convert_stock' => $date,
                    'warehouse_id' => $warehouses
                ];
                $suggest_exporting_id = $this->manufactures_model->insertSuggestExporting($fields);
                if ($suggest_exporting_id) {
                    if (getReference('stock') == $this->input->post('reference_no')) {
                        updateReference('stock');
                    }
                    foreach ($exporting_items as $key => $value) {
                        $exporting_items[$key]['suggest_exporting_id'] = $suggest_exporting_id;
                    }
                    $this->manufactures_model->insertBatchSuggestExportingItems($exporting_items);
                    $data['result'] = 1;
                    $data['message'] = lang('success');
                } else {
                    $data['result'] = 0;
                    $data['message'] = lang('fail');
                }
            } else {
                $data['result'] = 0;
                $data['message'] = validation_errors();
            }
            echo json_encode($data); die;
        } else {
            $data['reference_no'] = getReference('stock');
            $data['warehouses'] = $this->stock_model->getWarehouses();
            $data['breadcrumb'] = [array('link' => base_url('admin/stock/exporting_producion'), 'page' => lang('tnh_exporting_stock_producion')), array('link' => '#', 'page' => lang('tnh_add_exporting_production'))];
            $data['title'] = lang('tnh_add_exporting_production');
            $data['tnh'] = $this->tnh;
            $this->load->view('admin/stock/add_exporting_production', $data);
        }
    }

    public function edit_exporting_production($id)
    {
        $suggest_exporting = $this->manufactures_model->rowSuggestExporting($id);
        if ($suggest_exporting['type'] != 2) {
            refererModel(lang('tnh_no_edit_convert_to_stock'));
        }
        if ($suggest_exporting['status_stock'] != 'un_approved_stock') {
            refererModel(lang('browsed_cannot_be_edited'));
        }
        if ($this->input->post('edit'))
        {
            $data = [];
            if ($suggest_exporting['reference_stock'] != $this->input->post('reference_no'))
            {
                $this->form_validation->set_rules('reference_no', lang("tnh_reference_stock"), 'required|is_unique[tbl_suggest_exporting.reference_stock]');
            }
            $this->form_validation->set_rules('date', lang("date"), 'required');
            $this->form_validation->set_rules('export_name', lang("tnh_export_name"), 'required');
            $this->form_validation->set_rules('warehouses', lang("tnh_warehouses"), 'required');
            if ($this->form_validation->run() == true)
            {
                $reference_stock = $this->input->post('reference_no');
                $date = to_sql_date($this->input->post('date'), true);
                // $productions_orders_detail_id = $this->input->post('productions_orders_detail_id');
                $note = $this->input->post('note');
                $export_name = $this->input->post('export_name');
                $warehouses = $this->input->post('warehouses');
                $items = $this->input->post('items_id');
                $total_quantity = 0;
                $count_items = 0;
                $total_quantity_exchange = 0;
                $type = 2;

                $errors = false;

                //insert
                if (!empty($items)) {
                    foreach ($items as $key => $value) {
                        if (empty($value)) continue;
                        $unit_id = $this->input->post('unit_id')[$key];
                        $unit_parent_id = $this->input->post('unit_parent_id')[$key];
                        $number_exchange = $this->input->post('number_exchange')[$key];
                        $location = $this->input->post('locations')[$key];
                        $quantity_export = number_unformat($this->input->post('quantity')[$key]);
                        $arr_item = explode('__', $value);
                        $type_item = $arr_item[0];
                        $item_id = $arr_item[1];
                        if ($type_item == "semi_products_outside") {
                            $info_item = $this->products_model->rowProduct($item_id);
                        } else if ($type_item == "materials") {
                            $info_item = $this->items_model->rowMaterial($item_id);
                        }
                        if (empty($info_item)) continue;
                        if (empty($location)) {
                            $errors = lang('tnh_location_warehouse_required');
                            break;
                        }

                        $quantity_exchange = $quantity_export/$number_exchange;
                        $exporting_items[] = [
                            'suggest_exporting_id' => $id,
                            'type_item' => $type_item,
                            'item_id' => $item_id,
                            'item_code' => $info_item['code'],
                            'item_name' => $info_item['name'],
                            'unit_id' => $unit_id,
                            'quantity_export' => $quantity_export,
                            'unit_parent_id' => $unit_parent_id,
                            'number_exchange' => $number_exchange,
                            'quantity_exchange' => $quantity_exchange,
                            'location_id' => $location
                        ];
                        $total_quantity+= $quantity_export;
                        $total_quantity_exchange+= $quantity_exchange;
                    }
                }
                //updated
                $arr_id = [];
                $suggest_exporting_items_id = $this->input->post('suggest_exporting_items_id');
                if (!empty($suggest_exporting_items_id)) {
                    foreach ($suggest_exporting_items_id as $key => $value) {
                        if (empty($value)) continue;
                        $row = $this->stock_model->rowSuggestExportingItems($value);
                        if (empty($row)) continue;
                        array_push($arr_id, $value);
                        $number_exchange = $row['number_exchange'];
                        $quantity_export = number_unformat($this->input->post('quantity_edit')[$key]);
                        $location = $this->input->post('locations_edit')[$key];
                        $quantity_exchange = $quantity_export/$number_exchange;
                        if (empty($location)) {
                            $errors = lang('tnh_location_warehouse_required');
                            break;
                        }
                        $exporting_items_up[] = [
                            'id' => $value,
                            'quantity_export' => $quantity_export,
                            'quantity_exchange' => $quantity_exchange,
                            'location_id' => $location
                        ];
                        $total_quantity+= $quantity_export;
                        $total_quantity_exchange+= $quantity_exchange;
                    }
                }
                //
                if (empty($exporting_items) && empty($exporting_items_up)) {
                    $data['result'] = 0;
                    $data['message'] = lang('tnh_no_items');
                    echo json_encode($data); die;
                }

                if (!empty($errors)) {
                    $data['result'] = 0;
                    $data['message'] = $errors;
                    echo json_encode($data); die;
                }

                $count_items = (!empty($exporting_items) ? count($exporting_items) : 0) + (!empty($exporting_items_up) ? count($exporting_items_up) : 0);
                $fields = [
                    'reference_no' => null,
                    'reference_stock' => $reference_stock,
                    'date' => $date,
                    'export_name' => $export_name,
                    'note' => $note,
                    'total_quantity' => $total_quantity,
                    'count_items' => $count_items,
                    'total_quantity_exchange' => $total_quantity_exchange,
                    'updated_by' => get_staff_user_id(),
                    'date_updated' => date('Y-m-d H:i:s'),
                    'date_convert_stock' => $date,
                ];
                $up = $this->manufactures_model->updateSuggestExportingById($id, $fields);
                if ($up) {
                    //delete
                    $delete = $this->manufactures_model->getSuggestExportingItemsByNotArrId($arr_id, $id);
                    if (!empty($delete)) {
                        foreach ($delete as $key => $value) {
                            $this->manufactures_model->deleteSuggestExportingItemsById($value['id']);
                        }
                    }
                    //add
                    if (!empty($exporting_items)) {
                        $this->manufactures_model->insertBatchSuggestExportingItems($exporting_items);
                    }
                    //edit
                    if (!empty($exporting_items_up)) {
                        $this->manufactures_model->updateBatchSuggestExportingItemsById($exporting_items_up);
                    }

                    $data['result'] = 1;
                    $data['message'] = lang('success');
                } else {
                    $data['result'] = 0;
                    $data['message'] = lang('fail');
                }
            } else {
                $data['result'] = 0;
                $data['message'] = validation_errors();
            }
            echo json_encode($data); die;
        } else {
            $suggest_exporting_items = $this->stock_model->getSuggestExportingItemsForStock($id);
            $data['suggest_exporting'] = $suggest_exporting;
            $data['warehouses'] = $this->stock_model->getWarehouses();
            $data['production_orders_detail'] = $this->manufactures_model->rowProductionsOrdersDetais($suggest_exporting['productions_orders_details_id']);
            $data['suggest_exporting_items'] = $suggest_exporting_items;
            $data['id'] = $id;
            $data['breadcrumb'] = [array('link' => base_url('admin/stock/exporting_producion'), 'page' => lang('tnh_exporting_stock_producion')), array('link' => '#', 'page' => lang('tnh_edit_exporting_production'))];
            $data['title'] = lang('tnh_edit_exporting_production');
            $data['tnh'] = $this->tnh;
            $this->load->view('admin/stock/edit_exporting_production', $data);
        }
    }

    public function searchProductionsOrdersDetail($id = false)
    {
        $data = [];
        $term = $this->input->get('term', TRUE);
        $limit = get_option('select2_limit');
        $data['results'] = $this->stock_model->searchProductionsOrdersDetailsForStock($term, $limit);
        if ($id) {
            // $data['row'] = ['id' => $product['id'], 'text' => $product['code']];
        }
        echo json_encode($data);
    }

    public function searchItemsByProductionDetail($id = false)
    {
        $data = [];
        $term = $this->input->get('term', TRUE);
        $limit = get_option('select2_limit');
        $params = $this->input->get('params');
        $productions_orders_detail_id = $params['productions_orders_detail_id'];
        $material = $this->stock_model->searchMaterialProductionsOrders($term, $limit, $productions_orders_detail_id);
        $semi_product_outside = $this->stock_model->searchSemiProductProductionsOrders($term, $limit, $productions_orders_detail_id);

        $results = [];
        if (!empty($material))
        {
            $results[]= ['text' => lang('materials'), 'children' => $material];
        }
        if (!empty($semi_product_outside)) {
            $results[]= ['text' => lang('semi_products_outside'), 'children' => $semi_product_outside];
        }
        $data['results'] = $results;

        if ($id) {
            // $data['row'] = ['id' => $product['id'], 'text' => $product['code']];
        }
        echo json_encode($data);
    }

    function refereshReferenceProductionsOrders()
    {
        $data = [];
        if ($this->input->get('referesh'))
        {
            $reference_no = getReference('stock');
            if ($this->stock_model->checkExistSuggestExportingReferenceStock($reference_no)) {
                $ct = countReferenceMinus('stock');
                $this->db->select("MAX(right(tbl_suggest_exporting.reference_stock, char_length(tbl_suggest_exporting.reference_stock) - $ct) + 0) as reference_no", false);
                $this->db->from('tbl_suggest_exporting');
                $rs = $this->db->get()->row_array();

                $max = $rs['reference_no'];
                $max++;
                // $max = subReference($max);
                updateReferenceNormal('stock', $max);
                $reference_no = getReference('stock');
            }
            $data['reference_no'] = $reference_no;
            $data['message'] = lang('tnh_referesh_success');
        }
        echo json_encode($data);
    }

    public function delete_suggest_exporting($id)
    {
        $data = [];
        if ($id) {
            $flag = false;
            $suggest_exporting = $this->manufactures_model->rowSuggestExporting($id);
            if ($suggest_exporting['status_stock'] != 'un_approved_stock') {
                $data['result'] = 0;
                $data['message'] = lang('browsed_cannot_be_deleted');
                echo json_encode($data); die;
            }
            if ($suggest_exporting['type'] == 2) {
                if ($this->manufactures_model->deleteSuggestExportingById($id)) {
                    $this->manufactures_model->deleteSuggestExportingItems($id);
                    $flag = true;
                }
            } else {
                $up = $this->manufactures_model->updateSuggestExportingById($id, [
                    'reference_stock' => null,
                    'status_stock' => null,
                    'date_convert_stock' => null,
                    'convert_stock_by' => null
                ]);
                if ($up) {
                    $flag = true;
                }
            }
            if ($flag) {
                $data['result'] = 1;
                $data['message'] = lang('success');
            } else {
                $data['result'] = 0;
                $data['message'] = lang('fail');
            }
        } else {
            $data['result'] = 0;
            $data['message'] = lang('fail');
        }
        echo json_encode($data);
    }

    public function rowItem()
    {
        $data = [];
        if ($this->input->post())
        {
            $item = $this->input->post('item_id');
            $warehouse_id = $this->input->post('warehouse_id');
            if (!empty($item)) {
                $item = explode('__', $item);
                $type_item = $item[0];
                $item_id = $item[1];

                $warehouses = $this->stock_model->getWarehouseItemsByItemIdAndTypeAndWarehouse($item_id, $type_item, $warehouse_id);
                foreach ($warehouses as $key => $value) {
                    $warehouses[$key]['location_name'] = recursiveLocations($value['localtion']);
                }
                $data['warehouses'] = $warehouses;
            }
        }
        echo json_encode($data);
    }

    public function searchMaterialAndSemiProducts($id = false)
    {
        $data = [];
        $term = $this->input->get('term', TRUE);
        $limit = get_option('select2_limit');
        $params = $this->input->get('params');
        $material = $this->stock_model->searchMaterials($term, $limit);
        $semi_product_outside = $this->stock_model->searchSemiProductsOutside($term, $limit);

        $results = [];
        if (!empty($material))
        {
            $results[]= ['text' => lang('materials'), 'children' => $material];
        }
        if (!empty($semi_product_outside)) {
            $results[]= ['text' => lang('semi_products_outside'), 'children' => $semi_product_outside];
        }
        $data['results'] = $results;

        if ($id) {
            // $data['row'] = ['id' => $product['id'], 'text' => $product['code']];
        }
        echo json_encode($data);
    }

    public function rowMaterialOrSemiProduct()
    {
        $data = [];
        if ($this->input->post())
        {
            $item_id = $this->input->post('item_id');
            $unit = $this->input->post('unit');
            $item = false;
            $arr_unit_id = [];
            $arr_number_exchange = [];
            $number_exchange = 1;
            if (!empty($item_id)) {
                $arr = explode('__', $item_id);
                $type_item = $arr[0];
                $id = $arr[1];
                if ($type_item == "semi_products_outside") {
                    $semi_products_outside = $this->products_model->rowProduct($id);
                    $selected = $semi_products_outside['unit_id'];
                    array_push($arr_unit_id, $semi_products_outside['unit_id']);
                    $arr_number_exchange[$semi_products_outside['unit_id']]['number_exchange'] = 1;
                    $item = $semi_products_outside;
                } else {
                    $material = $this->items_model->rowMaterial($id);
                    $exchange = $this->items_model->getExchangeItemsByItemId($id);
                    $selected = $material['unit_id'];
                    array_push($arr_unit_id, $material['unit_id']);
                    $arr_number_exchange[$material['unit_id']]['number_exchange'] = 1;
                    if (!empty($exchange)) {
                        foreach ($exchange as $key => $value) {
                            array_push($arr_unit_id, $value['unit_id']);
                            $arr_number_exchange[$value['unit_id']]['number_exchange'] = $value['number_exchange'];
                        }
                    }
                    $item = $material;
                }
                $units = false;
                if (!empty($arr_unit_id)) {
                    $units = $this->products_model->getUnitsByArrId($arr_unit_id);
                    foreach ($units as $key => $value) {
                        $units[$key]['number_exchange'] = $arr_number_exchange[$value['unitid']]['number_exchange'];
                    }
                }
                $data['item'] = $item;
                $data['units'] = $units;
                $data['selected'] = $selected;
                $data['number_exchange'] = $number_exchange;
            }
        }
        echo json_encode($data);
    }

    public function agreeStock()
    {
        $data = [];
        if ($this->input->get())
        {
            $suggest_exporting_id = $this->input->get('suggest_exporting_id');
            $status = $this->input->get('status');
            $suggest_exporting = $this->manufactures_model->rowSuggestExporting($suggest_exporting_id);
            $date = date('Y-m-d H:i:s');
            $user_id = get_staff_user_id();
            if ($suggest_exporting['status_stock'] == $status) {
                $data['result'] = 0;
                $data['message'] = lang('tnh_please_referesh_table');
                echo json_encode($data); die;
            }

            $up = $this->manufactures_model->updateSuggestExportingById($suggest_exporting_id, [
                'status_stock' => $status,
                'date_stock' => $date,
                'user_stock' => $user_id
            ]);

            if ($up) {
                $data['result'] = 1;
                $data['message'] = lang('success');
            } else {
                $data['result'] = 0;
                $data['message'] = lang('fail');
            }
        }
        echo json_encode($data);
    }
}