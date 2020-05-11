<?php

// header('Content-Type: text/html; charset=utf-8');
defined('BASEPATH') or exit('No direct script access allowed');

class Manufactures extends AdminController
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

    public function index()
    {
        $data['tnh'] = true;
        $data['title'] = lang('productions_plan');
        $this->load->view('admin/manufactures/productions_plan', $data);
    }

    public function productions_plan()
    {
        $data['tnh'] = true;
        $data['title'] = lang('productions_plan');
        $this->load->view('admin/manufactures/productions_plan', $data);
    }

    function check_options()
    {
        $options1 = $this->input->post('options1');
        $options2 = $this->input->post('options2');

        if (empty($options1) && empty($options2)) {
            $this->form_validation->set_message('check_options', lang('tnh_options_or_required'));
            return false;
        }
        return true;
    }

    public function add_productions_plan()
    {
        if ($this->input->post('add'))
        {
            $data = [];

            $this->form_validation->set_rules('reference_no', lang("tnh_reference_productions_plan"), 'trim|required|is_unique[tbl_productions_plan.reference_no]');
            $this->form_validation->set_rules('date', lang("date"), 'required');
            $this->form_validation->set_rules('planning_cycle', lang("tnh_planning_cycle"), 'required');
            $this->form_validation->set_rules('options1', lang("tnh_options"), 'callback_check_options');
            if ($this->form_validation->run() == true)
            {
                $reference_no = $this->input->post('reference_no');
                $date = to_sql_date($this->input->post('date'), true);
                $planning_cycle = $this->input->post('planning_cycle');
                $options1 = $this->input->post('options1') ? $this->input->post('options1') : 0;
                $options2 = $this->input->post('options2') ? $this->input->post('options2') : 0;
                $safe_inventory = $this->input->post('safe_inventory') ? $this->input->post('safe_inventory') : 0;
                $note = $this->input->post('note');
                $status = 'un_approved';
                $select_custom_fields = "";
                $where_custom_fields = "";
                $date_start = NULL;
                $date_end = NULL;
                $sales_orders = NULL;
                $sales_orders_id = NULL;
                $business_plan = NULL;
                $business_plan_id = NULL;
                $arr_date = [];
                if (!empty($planning_cycle)) {
                    $arr_plan = explode('-', trim($planning_cycle));

                    $date1 = to_sql_date(trim($arr_plan[0]));
                    $date2 = to_sql_date(trim($arr_plan[1]));

                    if ($options1 == true) {
                        // $query = "(
                        //     SELECT
                        //         GROUP_CONCAT(distinct(tblorders.id) SEPARATOR ',') as id,
                        //         GROUP_CONCAT(distinct(CONCAT(tblorders.prefix, '', tblorders.code)) SEPARATOR ', ') as reference_orders
                        //     FROM tblorders
                        //     INNER JOIN tblorders_items ON tblorders_items.id_orders = tblorders.id
                        //     WHERE tblorders.status > 0 AND DATE_FORMAT(tblorders.date, '%Y-%m-%d') >= '$date1' AND DATE_FORMAT(tblorders.date, '%Y-%m-%d') <= '$date2' AND tblorders_items.type_items = 'products'
                        // )";
                        $query = "(
                            SELECT
                                GROUP_CONCAT(distinct(tblorders.id) SEPARATOR ',') as id,
                                GROUP_CONCAT(distinct(CONCAT(tblorders.prefix, '', tblorders.code)) SEPARATOR ', ') as reference_orders
                            FROM tblorders
                            INNER JOIN tblorders_items ON tblorders_items.id_orders = tblorders.id
                            INNER JOIN tblorders_detail_shipping ON tblorders_detail_shipping.id_detail = tblorders_items.id
                            WHERE tblorders.status > 0 AND DATE_FORMAT(tblorders_detail_shipping.date_shipping, '%Y-%m-%d') >= '$date1' AND DATE_FORMAT(tblorders_detail_shipping.date_shipping, '%Y-%m-%d') <= '$date2' AND tblorders_items.type_items = 'products'
                        )";
                        $rs = $this->db->query($query)->row_array();
                        $sales_orders = $rs['reference_orders'];
                        $sales_orders_id = $rs['id'];
                    }

                    if ($options2 == true) {
                        $query = "(
                            SELECT
                                GROUP_CONCAT(distinct(tbl_business_plan.id) SEPARATOR ',') as id,
                                GROUP_CONCAT(distinct(tbl_business_plan.reference_no) SEPARATOR ', ') as reference_no
                            FROM tbl_business_plan
                            INNER JOIN tbl_business_plan_items ON tbl_business_plan_items.business_plan_id = tbl_business_plan.id
                            INNER JOIN tbl_business_plan_items_date ON tbl_business_plan_items_date.business_plan_items_id = tbl_business_plan_items.id
                            WHERE tbl_business_plan.status = 'approved' AND DATE_FORMAT(tbl_business_plan_items_date.date, '%Y-%m-%d') >= '$date1' AND DATE_FORMAT(tbl_business_plan_items_date.date, '%Y-%m-%d') <= '$date2' AND tbl_business_plan_items.type_items = 'products'
                        )";
                        $rs = $this->db->query($query)->row_array();
                        $business_plan = $rs['reference_no'];
                        $business_plan_id = $rs['id'];
                    }

                    $date_start = $date1;
                    $date_end = $date2;

                    $date1 = new DateTime($date1);
                    $date2 = new DateTime($date2);

                    $diff = $date1->diff($date2);
                    $days = $diff->days;

                    for ($i = 0; $i <= $days; $i++) {
                        $pdate = date('Y-m-d', strtotime(to_sql_date(trim($arr_plan[0])). " + $i days"));
                        $select = " 0  ";
                        $flag = false;
                        if ($options1 == 1) {
                            $flag = true;
                            // $select = "COALESCE((
                            //     SELECT
                            //         SUM(tblorders_items.quantity)
                            //     FROM tblorders
                            //     INNER JOIN tblorders_items ON tblorders_items.id_orders = tblorders.id
                            //     WHERE tblorders.status > 0 AND DATE_FORMAT(tblorders.date, '%Y-%m-%d') = '$pdate' AND tblorders_items.type_items = 'products' AND tblorders_items.id_product = tbl_products.id
                            // ), 0)";
                            $select = "COALESCE((
                                SELECT
                                    SUM(tblorders_detail_shipping.quantity_shipping)
                                FROM tblorders
                                INNER JOIN tblorders_items ON tblorders_items.id_orders = tblorders.id
                                INNER JOIN tblorders_detail_shipping ON tblorders_detail_shipping.id_detail = tblorders_items.id
                                WHERE tblorders.status > 0 AND DATE_FORMAT(tblorders_detail_shipping.date_shipping, '%Y-%m-%d') = '$pdate' AND tblorders_items.type_items = 'products' AND tblorders_items.id_product = tbl_products.id AND tblorders.productions_plan_id = 0
                            ), 0)";
                        }
                        if ($options2 == 1) {
                            $flag = true;
                            $bus = "COALESCE((
                                SELECT
                                    SUM(tbl_business_plan_items_date.quantity)
                                FROM tbl_business_plan
                                INNER JOIN tbl_business_plan_items ON tbl_business_plan_items.business_plan_id = tbl_business_plan.id
                                INNER JOIN tbl_business_plan_items_date ON tbl_business_plan_items_date.business_plan_items_id = tbl_business_plan_items.id
                                WHERE tbl_business_plan.status = 'approved' AND DATE_FORMAT(tbl_business_plan_items_date.date, '%Y-%m-%d') = '$pdate' AND tbl_business_plan_items.type_items = 'products' AND tbl_business_plan_items.items_id = tbl_products.id AND tbl_business_plan.productions_plan_id = 0
                            ), 0)";
                            $select.= " + $bus ";
                        }
                        $select_custom_fields.= ", ". $select." as '".$pdate."'";
                        if ($flag) {
                            $where_custom_fields.= $select . ' > 0 OR ';
                        }
                        array_push($arr_date, $pdate);
                    }
                }

                $warehouses = "(
                    SELECT
                        tblwarehouse_items.id_items,
                        SUM(tblwarehouse_items.product_quantity) as quantity_warehouses
                    FROM tblwarehouse_items
                    WHERE tblwarehouse_items.type_items = 'product'
                    GROUP BY tblwarehouse_items.id_items
                ) warehouses";

                $this->db->select("
                    tbl_products.id as product_id,
                    COALESCE(tbl_products.quantity_minimum, 0) as quantity_minimum,
                    COALESCE(warehouses.quantity_warehouses, 0) as quantity_warehouses
                    $select_custom_fields
                    ", FALSE)
                ->from('tbl_products')
                ->join($warehouses, 'warehouses.id_items = tbl_products.id', 'left');

                if (!empty($where_custom_fields)) {
                    $where_custom_fields = trim($where_custom_fields);
                    $where_custom_fields = substr($where_custom_fields, 0, -2);
                    $this->datatables->where("( $where_custom_fields )");
                }

                if ($safe_inventory) {
                    $this->db->where('tbl_products.quantity_minimum >', 0);
                }

                $result = $this->db->get()->result_array();

                if (empty($result)) {
                    $data['result'] = 0;
                    $data['message'] = lang('not_data_exists');
                    echo json_encode($data); die;
                    // set_alert('danger', lang('not_data_exists'));
                    // redirect('admin/manufactures/add_productions_plan');
                }

                $productions_plan = [
                    'reference_no' => $reference_no,
                    'date' => $date,
                    'date_start' => $date_start,
                    'date_end' => $date_end,
                    'safe_inventory' => $safe_inventory,
                    'options1' => $options1,
                    'options1_reference_no' => $sales_orders,
                    'options1_id' => $sales_orders_id,
                    'options2' => $options2,
                    'options2_reference_no' => $business_plan,
                    'options2_id' => $business_plan_id,
                    'note' => $note,
                    'status' => $status,
                    'date_created' => date('Y-m-d H:i:s'),
                    'created_by' => get_staff_user_id(),
                ];

                // print_arrays($productions_plan);
                $productions_plan_id = $this->manufactures_model->insertProductionsPlan($productions_plan);
                if ($productions_plan_id) {
                    if (getReference('productions_plan') == $this->input->post('reference_no')) {
                        updateReference('productions_plan');
                    }

                    if (!empty($sales_orders_id)) {
                        foreach (explode(',', $sales_orders_id) as $key => $value) {
                            $this->orders_model->updateOrders($value, ['productions_plan_id' => $productions_plan_id]);
                        }
                    }
                    if (!empty($business_plan_id)) {
                        foreach (explode(',', $business_plan_id) as $key => $value) {
                            $this->business_plan_model->updateBusinessPlan($value, ['productions_plan_id' => $productions_plan_id]);
                        }
                    }

                    foreach ($result as $key => $value) {
                        $productions_plan_items = [
                            'productions_plan_id' => $productions_plan_id,
                            'product_id' => $value['product_id'],
                            'quantity_minimum' => $value['quantity_minimum'],
                            'quantity_warehouses' => $value['quantity_warehouses'],
                            'status' => 'not',
                        ];
                        $productions_plan_item_id = $this->manufactures_model->insertProductionsPlanItems($productions_plan_items);
                        $quantity_total_details = 0;
                        foreach ($arr_date as $k => $val) {
                            if (empty($value[$val])) continue;
                            $productions_plan_details = [
                                'productions_plan_item_id' => $productions_plan_item_id,
                                'date' => $val,
                                'quantity' => $value[$val]
                            ];
                            $quantity_total_details+= $value[$val];
                            $this->manufactures_model->insertProductionsPlanDetails($productions_plan_details);
                        }
                        $this->manufactures_model->updateProductionsPlanItems($productions_plan_item_id, ['quantity_total_details' => $quantity_total_details]);
                    }

                    set_alert('success', lang('success'));
                    $data['result'] = 1;
                    $data['message'] = lang('success');
                    // redirect('admin/manufactures/productions_plan');
                    // return;
                }
            } else {
                $data['result'] = 0;
                $data['message'] = validation_errors();
                // set_alert('danger', validation_errors());
                // redirect('admin/manufactures/add_productions_plan');
            }
            echo json_encode($data);
            die;
        }
        $data['tnh'] = $this->tnh;
        $data['reference_no'] = getReference('productions_plan');
        $data['breadcrumb'] = [array('link' => base_url('admin/manufactures/productions_plan'), 'page' => lang('productions_plan')), array('link' => '#', 'page' => lang('tnh_add_productions_plan'))];
        $data['title'] = lang('tnh_add_productions_plan');
        $this->load->view('admin/manufactures/add_productions_plan', $data);
    }

    public function show_table_productions_plan()
    {
        $planning_cycle = $this->input->get('planning_cycle');
        $options1 = $this->input->get('options1');
        $options2 = $this->input->get('options2');
        $safe_inventory = $this->input->get('safe_inventory');
        $sales_orders = '';
        $business_plan = '';
        $arr_plan = [];
        $arr_date = [];
        $th = '';
        $targets = 6;
        $script = '';
        if (!empty($planning_cycle)) {
            $arr_plan = explode('-', trim($planning_cycle));

            $date1 = to_sql_date(trim($arr_plan[0]));
            $date2 = to_sql_date(trim($arr_plan[1]));

            if ($options1 == true) {
                // $query = "(
                //     SELECT
                //         GROUP_CONCAT(distinct(CONCAT(tblorders.prefix, '', tblorders.code)) SEPARATOR ', ') as reference_orders
                //     FROM tblorders
                //     INNER JOIN tblorders_items ON tblorders_items.id_orders = tblorders.id
                //     WHERE tblorders.status > 0 AND DATE_FORMAT(tblorders.date, '%Y-%m-%d') >= '$date1' AND DATE_FORMAT(tblorders.date, '%Y-%m-%d') <= '$date2' AND tblorders_items.type_items = 'products'
                // )";
                $query = "(
                    SELECT
                        GROUP_CONCAT(distinct(CONCAT(tblorders.prefix, '', tblorders.code)) SEPARATOR ', ') as reference_orders
                    FROM tblorders
                    INNER JOIN tblorders_items ON tblorders_items.id_orders = tblorders.id
                    INNER JOIN tblorders_detail_shipping ON tblorders_detail_shipping.id_detail = tblorders_items.id
                    WHERE tblorders.status > 0 AND DATE_FORMAT(tblorders_detail_shipping.date_shipping, '%Y-%m-%d') >= '$date1' AND DATE_FORMAT(tblorders_detail_shipping.date_shipping, '%Y-%m-%d') <= '$date2' AND tblorders_items.type_items = 'products' AND tblorders.productions_plan_id = 0
                )";
                $rs = $this->db->query($query)->row_array();
                $sales_orders = $rs['reference_orders'];
            }

            if ($options2 == true) {
                $query = "(
                    SELECT
                        GROUP_CONCAT(distinct(tbl_business_plan.reference_no) SEPARATOR ', ') as reference_no
                    FROM tbl_business_plan
                    INNER JOIN tbl_business_plan_items ON tbl_business_plan_items.business_plan_id = tbl_business_plan.id
                    INNER JOIN tbl_business_plan_items_date ON tbl_business_plan_items_date.business_plan_items_id = tbl_business_plan_items.id
                    WHERE tbl_business_plan.status = 'approved' AND tbl_business_plan.productions_plan_id = 0 AND DATE_FORMAT(tbl_business_plan_items_date.date, '%Y-%m-%d') >= '$date1' AND DATE_FORMAT(tbl_business_plan_items_date.date, '%Y-%m-%d') <= '$date2' AND tbl_business_plan_items.type_items = 'products'
                )";
                $rs = $this->db->query($query)->row_array();
                $business_plan = $rs['reference_no'];
            }

            $date1 = new DateTime($date1);
            $date2 = new DateTime($date2);

            $diff = $date1->diff($date2);
            $days = $diff->days;

            for ($i = 0; $i <= $days; $i++) {
                $plus_date = date('d/m/Y', strtotime(to_sql_date(trim($arr_plan[0])). " + $i days"));

                $th.= '<th>'.$plus_date.'</th>';
                $script.= '{
                    "targets": '.$targets.', "name": "date'.$i.'", "className": "text-center",
                },';
                $targets++;
            }
        }
        $data['th'] = $th;
        $data['script'] = $script;
        $data['targets'] = $targets;
        $data['safe_inventory'] = $safe_inventory;
        $data['planning_cycle'] = $planning_cycle;
        $data['options1'] = $options1;
        $data['options2'] = $options2;
        $data['sales_orders'] = $sales_orders;
        $data['business_plan'] = $business_plan;
        $this->load->view('admin/manufactures/view_table_productions_plan', $data);
    }

    public function getShowTableProductionsPlan()
    {
        $condition_safe_inventory = $this->input->post('condition_safe_inventory');
        $condition_planning_cycle = $this->input->post('condition_planning_cycle');
        $condition_options1 = $this->input->post('condition_options1');
        $condition_options2 = $this->input->post('condition_options2');

        $select_custom_fields = "";
        $where_custom_fields = "";
        $custom = [];
        $custom_select = [];
        $target = 6;
        if (!empty($condition_planning_cycle)) {
            $arr_plan = explode('-', trim($condition_planning_cycle));
            $date1 = to_sql_date(trim($arr_plan[0]));
            $date2 = to_sql_date(trim($arr_plan[1]));

            $date1 = new DateTime($date1);
            $date2 = new DateTime($date2);

            $diff = $date1->diff($date2);
            $days = $diff->days;

            for ($i = 0; $i <= $days; $i++) {
                $date = date('Y-m-d', strtotime(to_sql_date(trim($arr_plan[0])). " + $i days"));
                $select = " 0  ";
                $flag = false;
                if ($condition_options1 == 1) {
                    $flag = true;
                    // $select = "COALESCE((
                    //     SELECT
                    //         SUM(tblorders_items.quantity)
                    //     FROM tblorders
                    //     INNER JOIN tblorders_items ON tblorders_items.id_orders = tblorders.id
                    //     WHERE tblorders.status > 0 AND DATE_FORMAT(tblorders.date, '%Y-%m-%d') = '$date' AND tblorders_items.type_items = 'products' AND tblorders_items.id_product = tbl_products.id
                    // ), 0)";

                    $select = "COALESCE((
                        SELECT
                            SUM(tblorders_detail_shipping.quantity_shipping)
                        FROM tblorders
                        INNER JOIN tblorders_items ON tblorders_items.id_orders = tblorders.id
                        INNER JOIN tblorders_detail_shipping ON tblorders_detail_shipping.id_detail = tblorders_items.id
                        WHERE tblorders.status > 0 AND DATE_FORMAT(tblorders_detail_shipping.date_shipping, '%Y-%m-%d') = '$date' AND tblorders_items.type_items = 'products' AND tblorders_items.id_product = tbl_products.id AND tblorders.productions_plan_id = 0
                    ), 0)";
                }
                if ($condition_options2 == 1) {
                    $flag = true;
                    $bus = "COALESCE((
                        SELECT
                            SUM(tbl_business_plan_items_date.quantity)
                        FROM tbl_business_plan
                        INNER JOIN tbl_business_plan_items ON tbl_business_plan_items.business_plan_id = tbl_business_plan.id
                        INNER JOIN tbl_business_plan_items_date ON tbl_business_plan_items_date.business_plan_items_id = tbl_business_plan_items.id
                        WHERE tbl_business_plan.status = 'approved' AND DATE_FORMAT(tbl_business_plan_items_date.date, '%Y-%m-%d') = '$date' AND tbl_business_plan_items.type_items = 'products' AND tbl_business_plan_items.items_id = tbl_products.id AND tbl_business_plan.productions_plan_id = 0
                    ), 0)";
                    $select.= " + $bus ";
                }
                $select_custom_fields.= ", ". $select." as date".$i;
                if ($flag) {
                    $where_custom_fields.= $select . ' > 0 OR ';
                }
                $custom[] = [
                    'index' => $target,
                    'select' => "date".$i,
                ];
                $custom_select[$target] = $select;
                $target++;
            }
        }

        $warehouses = "(
            SELECT
                tblwarehouse_items.id_items,
                SUM(tblwarehouse_items.product_quantity) as quantity_warehouses
            FROM tblwarehouse_items
            WHERE tblwarehouse_items.type_items = 'product'
            GROUP BY tblwarehouse_items.id_items
        ) warehouses";

        $this->datatables->select("
            0 as number_records,
            tbl_products.code as product_code,
            tbl_products.name as product_name,
            tblunits.unit as unit,
            tbl_products.quantity_minimum as quantity_minimum,
            COALESCE(warehouses.quantity_warehouses, 0) as quantity_warehouses
            $select_custom_fields
            ", FALSE)
        ->from('tbl_products')
        ->join('tblunits', 'tblunits.unitid = tbl_products.unit_id', 'left')
        ->join($warehouses, 'warehouses.id_items = tbl_products.id', 'left');

        if ($condition_safe_inventory) {
            $this->datatables->where('tbl_products.quantity_minimum >', 0);
        }

        if (!empty($where_custom_fields)) {
            $where_custom_fields = trim($where_custom_fields);
            $where_custom_fields = substr($where_custom_fields, 0, -2);
            $this->datatables->where("( $where_custom_fields )");
        }
        // print_arrays($this->db->get_compiled_select('tbl_products'), FALSE);
        $this->datatables->custom_ordering($custom);
        $this->datatables->custom_select($custom_select);

        $iDisplayStart = $this->input->post('iDisplayStart');
        $data = json_decode($this->datatables->generate());
        foreach ($data->aaData as $key => $value) {
            $data->aaData[$key][0] = ++$iDisplayStart;
        }
        echo json_encode($data);
    }

    public function getProductionsPlan()
    {
        $status_table = $this->input->post('status_table');

        $this->datatables->select("
            tbl_productions_plan.id as id,
            0 as number_records,
            tbl_productions_plan.date as date,
            tbl_productions_plan.reference_no as reference_no,
            CONCAT(DATE_FORMAT(tbl_productions_plan.date_start, '%d/%m/%Y'), ' - ', DATE_FORMAT(tbl_productions_plan.date_end, '%d/%m/%Y'), '') as planning_cycle,
            tbl_productions_plan.safe_inventory as safe_inventory,
            CONCAT(tbl_productions_plan.options1, '-', tbl_productions_plan.options2, '') as options,
            tbl_productions_plan.note as note,
            CONCAT(tblstaff.firstname, ' ', tblstaff.lastname,'') as created_by,
            tbl_productions_plan.status as status,
            CONCAT(staff_status.firstname, ' ', staff_status.lastname,'') as user_status,
            tbl_productions_orders.reference_no as reference_orders
            ", FALSE)
        ->from('tbl_productions_plan')
        ->join('tbl_productions_orders', 'tbl_productions_orders.id = tbl_productions_plan.productions_orders_id', 'left')
        ->join('tblstaff', 'tblstaff.staffid = tbl_productions_plan.created_by', 'left')
        ->join('tblstaff staff_status', 'staff_status.staffid = tbl_productions_plan.user_status', 'left');

        if ($status_table) {
            $this->datatables->where('tbl_productions_plan.status', $status_table);
        }

        $hidden = '<input type="hidden" name="productions_plan_id[]" class="productions_plan_id" value="$1">';

        $view = '<a class="tnh-modal" title="'.lang('view').'" data-tnh="modal" data-toggle="modal" data-target="#myModal" href="'.base_url('admin/manufactures/view_productions_plan/$1').'"><i class="fa fa-file-text-o width-icon-actions"></i> '.lang('view').'</a>';

        $excel = '<a class="export-excel" value="$1" title="'.lang('excel').'" href="javascript:void(0)"><i class="fa fa-file-excel-o width-icon-actions"></i> '.lang('excel').'</a>';

        $delete = '<a type="button" class="po" title="'.lang('delete').'" data-container="body" data-html="true" data-toggle="popover" data-placement="left" data-content="
            <button href=\''.base_url('admin/manufactures/delete_productions_plan/$1').'\' class=\'btn btn-danger po-delete-json\'>'.lang('delete').'</button>
            <button class=\'btn btn-default po-close\'>'.lang('close').'</button>
        "><i class="fa fa-remove width-icon-actions"></i> '.lang('delete').'</a>';

        // $actions = '<div>'.$view.' '.$excel.' '.$delete.'</div>';

        $actions = '
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle nav-link" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
            '.lang('actions').'
            <span class="caret"></span>
            </button>
            <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdownMenu1">
                <li>'.$hidden.'</li>
                <li>'.$view.'</li>
                <li>'.$excel.'</li>
                <li class="not-outside">'.$delete.'</li>
            </ul>
        </div>';

        $this->datatables->add_column('actions', $actions, 'id');
        // $this->datatables->unset_column('id');

        $iDisplayStart = $this->input->post('iDisplayStart');
        $data = json_decode($this->datatables->generate());
        foreach ($data->aaData as $key => $value) {
            $data->aaData[$key][1] = ++$iDisplayStart;
        }
        echo json_encode($data);
    }

    public function view_productions_plan($id) {
        $productions_plan = $this->manufactures_model->rowProductionsPlan($id);

        $th = '';
        $targets = 6;
        $script = '';
        $date1 = date_create($productions_plan['date_start']);
        $date2 = date_create($productions_plan['date_end']);
        $diff = date_diff($date1, $date2);
        $days = $diff->days;

        for ($i = 0; $i <= $days; $i++) {
            $plus_date = date('d/m/Y', strtotime($productions_plan['date_start']. " + $i days"));
            $th.= '<th>'.$plus_date.'</th>';
            $script.= '{
                "targets": '.$targets.', "name": "date'.$i.'", "className": "text-center",
            },';
            $targets++;
        }
        $data['th'] = $th;
        $data['script'] = $script;
        $data['targets'] = $targets;
        $data['created_by'] = get_staff_full_name($productions_plan['created_by']);
        $data['productions_plan'] = $productions_plan;
        $this->load->view('admin/manufactures/view_productions_plan', $data);
    }

    public function getViewProductionsPlan()
    {
        $filter_productions_plan_id = $this->input->post('filter_productions_plan_id');
        $filter_date_start = $this->input->post('filter_date_start');
        $filter_date_end = $this->input->post('filter_date_end');

        $target = 6;
        $date1 = date_create($filter_date_start);
        $date2 = date_create($filter_date_end);
        $diff = date_diff($date1, $date2);
        $days = $diff->days;

        $select_custom_fields = "";
        $custom = [];
        $custom_select = [];

        for ($i = 0; $i <= $days; $i++) {
            $date = date('Y-m-d', strtotime($filter_date_start. " + $i days"));
            $select = "
                COALESCE((
                    SELECT
                        tbl_productions_plan_details.quantity
                    FROM tbl_productions_plan_details
                    WHERE tbl_productions_plan_details.productions_plan_item_id = tbl_productions_plan_items.id AND DATE_FORMAT(tbl_productions_plan_details.date, '%Y-%m-%d') = '$date'
                ), 0)
            ";
            $select_custom_fields.= ", ". $select." as date".$i;

            $custom[] = [
                'index' => $target,
                'select' => "date".$i,
            ];
            $custom_select[$target] = $select;
            $target++;
        }

        $this->datatables->select("
            0 as number_records,
            tbl_products.code as product_code,
            tbl_products.name as product_name,
            tblunits.unit as unit,
            tbl_productions_plan_items.quantity_minimum as quantity_minimum,
            tbl_productions_plan_items.quantity_warehouses as quantity_warehouses,
            $select_custom_fields
            ", false)
        ->from('tbl_productions_plan_items')
        ->join('tbl_products', 'tbl_products.id = tbl_productions_plan_items.product_id')
        ->join('tblunits', 'tblunits.unitid = tbl_products.unit_id', 'left');

        $this->datatables->where('tbl_productions_plan_items.productions_plan_id', $filter_productions_plan_id);

        $this->datatables->custom_ordering($custom);
        $this->datatables->custom_select($custom_select);

        $iDisplayStart = $this->input->post('iDisplayStart');
        $data = json_decode($this->datatables->generate());
        foreach ($data->aaData as $key => $value) {
            $data->aaData[$key][0] = ++$iDisplayStart;
        }
        echo json_encode($data);
    }

    function delete_productions_plan($id)
    {
        $data = [];
        if ($id) {
            $productions_plan = $this->manufactures_model->rowProductionsPlan($id);
            if ($productions_plan['status'] == "un_approved") {
                if ($this->manufactures_model->deleteProductionsPlan($id)) {
                    if (!empty($productions_plan['options1_id'])) {
                        $this->orders_model->updateOrdersByProductionsPlan($id, ['productions_plan_id' => 0]);
                    }
                    if (!empty($productions_plan['options2_id'])) {
                        $this->business_plan_model->updateBusinessPlanByProductionPlan($id, ['productions_plan_id' => 0]);
                    }
                    $data['result'] = 1;
                    $data['message'] = lang('success');
                } else {
                    $data['result'] = 0;
                    $data['message'] = lang('fail');
                }
            } else {
                $data['result'] = 0;
                $data['message'] = lang('browsed_cannot_be_deleted');
            }
        } else {
            $data['result'] = 0;
            $data['message'] = lang('fail');
        }
        echo json_encode($data);
    }

    public function export_excel_production_plan()
    {
        $response = [];
        if ($this->input->post('export_excel'))
        {
            ini_set('memory_limit', '3500M');
            include APPPATH . 'third_party/PHPExcel/PHPExcel.php';
            $this->load->library('PHPExcel');
            $productions_plan_id = $this->input->post('productions_plan_id');
            $productions_plan = $this->manufactures_model->rowProductionsPlan($productions_plan_id);
            if (empty($productions_plan)) {
                $response =  array(
                    'result' => 0,
                    'message' => lang('not_data_exists'),
                );
                echo json_encode($response); die;
            }
            $style_excel = style_excel();
            $cloumns_excel = cloumns_excel();

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.2); // ~ 1.78cm
            $objPHPExcel->getActiveSheet()->getPageMargins()->setHeader(0.2); // ~1.02cm
            $objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.2); // ~
            $objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.2); // ~1.78cm
            $objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.2); // ~1.73cm
            $objPHPExcel->getActiveSheet()->getPageMargins()->setFooter(0); // ~1.02cm

            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);

            $objPHPExcel->getActiveSheet()->SetCellValue('A1', lang('date'))->getStyle('A1')->applyFromArray($style_excel['bold']);
            $objPHPExcel->getActiveSheet()->SetCellValue('B1', _dt($productions_plan['date']));

            $objPHPExcel->getActiveSheet()->SetCellValue('A2', lang('tnh_planning_cycle'))->getStyle('A2')->applyFromArray($style_excel['bold']);
            $objPHPExcel->getActiveSheet()->SetCellValue('B2', _d($productions_plan['date_start']).' - '._d($productions_plan['date_end']));

            $objPHPExcel->getActiveSheet()->SetCellValue('A3', lang('tnh_applied_standard'))->getStyle('A3')->applyFromArray($style_excel['bold']);
            $objPHPExcel->getActiveSheet()->SetCellValue('B3', ($productions_plan['safe_inventory'] == 1 ? lang('tnh_safe_inventory') : lang('tnh_not')));

            $objPHPExcel->getActiveSheet()->SetCellValue('A4', lang('tnh_options'))->getStyle('A4')->applyFromArray($style_excel['bold']);
            $objPHPExcel->getActiveSheet()->SetCellValue('B4', ($productions_plan['options1'] == 1 ? lang('tnh_sales_orders') : '').' - '. ($productions_plan['options2'] == 1 ? lang('tnh_business_plan') : ''));

            $objPHPExcel->getActiveSheet()->SetCellValue('A5', lang('note'))->getStyle('A5')->applyFromArray($style_excel['bold']);
            $objPHPExcel->getActiveSheet()->SetCellValue('B5', strip_tags($productions_plan['note']));

            $objPHPExcel->getActiveSheet()->mergeCells('A6:H6');
            $objPHPExcel->getActiveSheet()->SetCellValue('A6', lang('productions_plan'))->getStyle('A6')->applyFromArray($style_excel['title']);;

            $date1 = date_create($productions_plan['date_start']);
            $date2 = date_create($productions_plan['date_end']);
            $diff = date_diff($date1, $date2);
            $days = $diff->days;
            $select_custom_fields = "";
            $arr_date = [];

            for ($i = 0; $i <= $days; $i++) {
                $date = date('d/m/Y', strtotime($productions_plan['date_start']. " + $i days"));
                $select = "
                    COALESCE((
                        SELECT
                            tbl_productions_plan_details.quantity
                        FROM tbl_productions_plan_details
                        WHERE tbl_productions_plan_details.productions_plan_item_id = tbl_productions_plan_items.id AND DATE_FORMAT(tbl_productions_plan_details.date, '%d/%m/%Y') = '$date'
                    ), 0)
                ";
                $select_custom_fields.= ", ". $select." as '".$date."'";
                array_push($arr_date, $date);
            }

            $this->db->select("
                tbl_productions_plan_items.id as id,
                tbl_products.code as product_code,
                tbl_products.name as product_name,
                tblunits.unit as unit,
                tbl_productions_plan_items.quantity_minimum as quantity_minimum,
                tbl_productions_plan_items.quantity_warehouses as quantity_warehouses
                $select_custom_fields
                ", false)
            ->from('tbl_productions_plan_items')
            ->join('tbl_products', 'tbl_products.id = tbl_productions_plan_items.product_id')
            ->join('tblunits', 'tblunits.unitid = tbl_products.unit_id', 'left');

            $this->db->where('tbl_productions_plan_items.productions_plan_id', $productions_plan_id);

            $data = $this->db->get()->result_array();

            $th = 7;
            $th_continue = 'G';
            $row = 8;
            $number_records = 1;
            foreach ($data as $key => $value) {
                if ($key == 0) {
                    $objPHPExcel->getActiveSheet()->SetCellValue("A$th", lang('tnh_numbers'));
                    $objPHPExcel->getActiveSheet()->SetCellValue("B$th", lang('tnh_product_code'));
                    $objPHPExcel->getActiveSheet()->SetCellValue("C$th", lang('tnh_product_name'));
                    $objPHPExcel->getActiveSheet()->SetCellValue("D$th", lang('unit'));
                    $objPHPExcel->getActiveSheet()->SetCellValue("E$th", lang('tnh_safe_inventory'));
                    $objPHPExcel->getActiveSheet()->SetCellValue("F$th", lang('tnh_quantity_warehouses'));
                }
                $cloumn = 0;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($cloumn++, $row, $number_records);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($cloumn++, $row, $value['product_code']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($cloumn++, $row, $value['product_name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($cloumn++, $row, $value['unit']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($cloumn++, $row, $value['quantity_minimum']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($cloumn++, $row, $value['quantity_warehouses']);

                $cloumn = 'G';
                foreach ($arr_date as $k => $val) {
                    if (!empty($value[$val]))
                    {
                        if ($key == 0) {
                            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($th_continue++, $th, $val);
                            $objPHPExcel->getActiveSheet()->getColumnDimension("$th_continue")->setAutoSize(true);
                            $objPHPExcel->getActiveSheet()->SetCellValue("$th_continue$th", $val);
                            $th_continue++;
                        }
                        $objPHPExcel->getActiveSheet()->SetCellValue("$cloumn$row", $value[$val]);
                        $cloumn++;
                        // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($cloumn++, $row, $value[$val]);
                    }
                }

                $row++;
                $number_records++;
            }

            $objPHPExcel->getActiveSheet()->getStyle("A7:$th_continue$row")->applyFromArray($style_excel['border']);
            $objPHPExcel->getActiveSheet()->getStyle("A7:$th_continue".'7')->applyFromArray($style_excel['bold']);

            $filename = lang('productions_plan').'.xls';
            $objPHPExcel->getActiveSheet()->freezePane('A1');

            ob_start();
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="$filename"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
            $objWriter->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();

            $response =  array(
                'result' => 1,
                'filename' => $filename,
                'message' => lang('success'),
                'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
            );
        }
        echo json_encode($response);
    }

    function refereshReference()
    {
        $data = [];
        if ($this->input->get('referesh'))
        {
            $reference_no = getReference('productions_plan');
            if ($this->manufactures_model->checkExistProductionsPlanByReferenceNo($reference_no)) {
                $this->db->select('MAX(tbl_productions_plan.reference_no) as reference_no', false);
                $this->db->from('tbl_productions_plan');
                $rs = $this->db->get()->row_array();

                $max = $rs['reference_no'];
                $max = subReference($max);
                updateReferenceNormal('productions_plan', $max);
                $reference_no = getReference('productions_plan');
            }
            $data['reference_no'] = $reference_no;
            $data['message'] = lang('tnh_referesh_success');
        }
        echo json_encode($data);
    }

    function agreeProductionsPlan()
    {
        $data = [];
        if ($this->input->get())
        {
            $productions_plan_id = $this->input->get('productions_plan_id');
            $status = $this->input->get('status');
            $productions_plan = $this->manufactures_model->rowProductionsPlan($productions_plan_id);
            $date = date('Y-m-d H:i');
            $user_id = get_staff_user_id();
            if ($productions_plan['status'] == $status) {
                $data['result'] = 0;
                $data['message'] = lang('tnh_please_referesh_table');
                echo json_encode($data); die;
            }
            if ($productions_plan['productions_capacity_id'] > 0) {
                $data['result'] = 0;
                $data['message'] = lang('tnh_capacity').' '.lang('tnh_not_un_agree');
                echo json_encode($data); die;
            }
            if ($productions_plan['productions_orders_id'] > 0) {
                $data['result'] = 0;
                $data['message'] = lang('tnh_status_productions_orders').' '.lang('tnh_not_un_agree');
                echo json_encode($data); die;
            }
            $up = $this->manufactures_model->updateProductionsPlan($productions_plan_id, [
                'status' => $status,
                'date_status' => $date,
                'user_status' => $user_id
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

    function searchProductionsPlan()
    {
        $data = [];
        if ($this->input->get())
        {
            $q = $this->input->get('q');
            // $limit = 50;
            $limit = get_option('select2_limit');
            $data = $this->manufactures_model->searchProductionsPlan($q, $limit);
        }
        echo json_encode($data);
    }

    //productions_capacity
    public function productions_capacity() {
        $data['tnh'] = $this->tnh;
        $data['title'] = lang('productions_capacity');
        $this->load->view('admin/manufactures/productions_capacity', $data);
    }

    function getProductionsCapacity()
    {
        $status_table = $this->input->post('status_table');

        $number_purchases = "(
            SELECT
                COUNT(*)
            FROM tbl_productions_capacity_items_purchases
            WHERE tbl_productions_capacity_items_purchases.productions_capacity_id = tbl_productions_capacity.id AND tbl_productions_capacity_items_purchases.quantity_purchase_sub > 0
        )";

        $this->datatables->select("
            tbl_productions_capacity.id as id,
            0 as number_records,
            tbl_productions_capacity.date as date,
            tbl_productions_capacity.reference_no as reference_no,
            tbl_productions_capacity.productions_plan_reference_no as productions_plan_reference_no,
            $number_purchases as number_purchases,
            tbl_productions_capacity.note as note,
            CONCAT(tblstaff.firstname, ' ', tblstaff.lastname,'') as created_by,
            tbl_productions_capacity.status as status,
            CONCAT(staff_status.firstname, ' ', staff_status.lastname,'') as user_status,
            CONCAT(tbl_productions_capacity.status_purchases, '__', COALESCE(tblpurchases.prefix, ''), COALESCE(tblpurchases.code, ''), '') as status_purchases
            ", FALSE)
        ->from('tbl_productions_capacity')
        ->join('tblstaff', 'tblstaff.staffid = tbl_productions_capacity.created_by', 'left')
        ->join('tblpurchases', 'tblpurchases.id = tbl_productions_capacity.purchases_id', 'left')
        ->join('tblstaff staff_status', 'staff_status.staffid = tbl_productions_capacity.user_status', 'left');

        $hidden = '<input type="hidden" name="productions_plan_id[]" class="productions_plan_id" value="$1">';

        $convert_purchases = '<a class="tnh-modal convert-purchase" data-tnh="modal" data-toggle="modal" data-target="#myModal" title="'.lang('tnh_convert_purchases').'" target="_blank" href="'.base_url('admin/manufactures/capacity_convert_purchase/$1').'"><i class="fa fa-exchange width-icon-actions"></i> '.lang('tnh_convert_purchases').'</a>';

        $view = '<a class="tnh-modal" title="'.lang('view').'" data-tnh="modal" data-toggle="modal" data-target="#myModal" href="'.base_url('admin/manufactures/view_productions_capacity/$1').'"><i class="fa fa-file-text-o width-icon-actions"></i> '.lang('view').'</a>';

        // $excel = '<a class="btn btn-success export-excel" value="$1" title="'.lang('excel').'" href="javascript:void(0)"><i class="fa fa-file-excel-o width-icon-actions"></i></a>';
        $excel = '';
        $delete = '<a type="button" class="po" title="'.lang('delete').'" data-container="body" data-html="true" data-toggle="popover" data-placement="left" data-content="
            <button href=\''.base_url('admin/manufactures/delete_productions_capacity/$1').'\' class=\'btn btn-danger po-delete-json\'>'.lang('delete').'</button>
            <button class=\'btn btn-default po-close\'>'.lang('close').'</button>
        "><i class="fa fa-remove width-icon-actions"></i> '.lang('delete').'</a>';

        if ($status_table == 'purchases' || $status_table == 'un_purchases') {
            $this->datatables->where('tbl_productions_capacity.status_purchases', $status_table);
        } else if ($status_table) {
            $this->datatables->where('tbl_productions_capacity.status', $status_table);
        }

        // $actions = '<div>'.$convert_purchases.' '.$view.' '.$excel.' '.$delete.'</div>';
        $actions = '
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle nav-link" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
            '.lang('actions').'
            <span class="caret"></span>
            </button>
            <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdownMenu1">
                <li>'.$convert_purchases.'</li>
                <li>'.$view.'</li>
                <li>'.$excel.'</li>
                <li class="not-outside">'.$delete.'</li>
            </ul>
        </div>';
        $this->datatables->add_column('actions', $actions, 'id');

        $iDisplayStart = $this->input->post('iDisplayStart');
        $data = json_decode($this->datatables->generate());
        foreach ($data->aaData as $key => $value) {
            $data->aaData[$key][1] = ++$iDisplayStart;
        }
        echo json_encode($data);
    }

    public function add_productions_capacity() {
        if ($this->input->post('add'))
        {
            $data = [];
            $this->form_validation->set_rules('reference_no', lang("tnh_reference_productions_plan"), 'trim|required|is_unique[tbl_productions_capacity.reference_no]');
            $this->form_validation->set_rules('date', lang("date"), 'required');
            $this->form_validation->set_rules('productions_plan_id[]', lang("productions_plan"), 'required');
            if ($this->form_validation->run() == true)
            {
                $reference_no = $this->input->post('reference_no');
                $date = to_sql_date($this->input->post('date'), true);
                $note = $this->input->post('note');
                $productions_plan_id = $this->input->post('productions_plan_id');
                $check_productions_plan = $this->manufactures_model->checkStatusProductionsPlan($productions_plan_id)['reference_no'];
                if ($check_productions_plan) {
                    $data['result'] = 0;
                    $data['message'] = lang('tnh_review').' '.$check_productions_plan;
                    echo json_encode($data);
                    die;
                }
                $productions_plan_reference = $this->manufactures_model->rowReferenceProductionsPlanByArrId($productions_plan_id)['reference_no'];
                $status = 'un_approved';

                $where_in = "('".str_replace(',', "','", implode(',', $productions_plan_id))."')";
                $items = "(
                    SELECT
                        tbl_productions_plan_items.product_id as product_id,
                        SUM(tbl_productions_plan_details.quantity) as total_quantity
                    FROM tbl_productions_plan
                    INNER JOIN tbl_productions_plan_items ON tbl_productions_plan.id = tbl_productions_plan_items.productions_plan_id
                    INNER JOIN tbl_productions_plan_details ON tbl_productions_plan_details.productions_plan_item_id = tbl_productions_plan_items.id
                    WHERE tbl_productions_plan_items.productions_plan_id IN $where_in AND tbl_productions_plan.status = 'approved'
                    GROUP BY tbl_productions_plan_items.product_id
                ) as items";

                $warehouses = "(
                    SELECT
                        tblwarehouse_items.id_items,
                        SUM(tblwarehouse_items.product_quantity) as quantity_warehouses
                    FROM tblwarehouse_items
                    WHERE tblwarehouse_items.type_items = 'product'
                    GROUP BY tblwarehouse_items.id_items
                ) warehouses";

                $this->db->select("
                    tbl_products.id as product_id,
                    tbl_products.code as code,
                    tbl_products.name as name,
                    tbl_products.quantity_minimum as quantity_minimum,
                    COALESCE(warehouses.quantity_warehouses, 0) as quantity_warehouses,
                    COALESCE(items.total_quantity, 0) as quantity_use,
                    COALESCE(items.total_quantity, 0) - COALESCE(warehouses.quantity_warehouses, 0) + tbl_products.quantity_minimum as quantity_productions,
                    tbl_products.number_labor as number_labor,
                    tbl_products.versions as versions,
                    tbl_products.versions_stage as versions_stage,
                    '' as sub,
                    '' as st,
                ", false)
                ->from("tbl_products")
                ->join($warehouses, 'warehouses.id_items = tbl_products.id', 'left')
                ->join($items, 'items.product_id = tbl_products.id');
                $rs = $this->db->get()->result_array();

                if (empty($rs)) {
                    $data['result'] = 0;
                    $data['message'] = lang('not_data_exists');
                    echo json_encode($data);
                    die;
                }

                $arr_purchases = [];
                $arr_id_sub = [];
                $arr_type_sub = [];

                foreach ($rs as $key => $value) {
                    $product_id = $value['product_id'];
                    // $product = $this->products_model->rowProduct($product_id);
                    // $vs = $product['versions'];
                    $vs = $value['versions'];
                    $quantity = $value['quantity_productions'];
                    if ($quantity < 0) {
                        $quantity = 0;
                        $rs[$key]['quantity_productions'] = 0;
                    }
                    if (!empty($vs)) {
                        $version = $this->products_model->getBomByProductIdAndVersions($product_id, $vs);
                        if (!empty($version)) {
                            $elements = $this->products_model->getVersionsElementByVersionId($version['id']);
                            $rs[$key]['sub'] = [];
                            foreach ($elements as $k => $val) {
                                $quantity_element = $quantity * $val['quantity'];
                                $rs[$key]['sub'][] = [
                                    'type_sub' => 'element',
                                    'id_sub' => 0,
                                    'code_sub' => $val['element_name'],
                                    'name_sub' => $val['element_name'],
                                    'quantity' => $val['quantity'],
                                    'quantity_minimum_sub' => 0,
                                    'quantity_warehouse_sub' => 0,
                                    'quantity_plan_sub' => $quantity_element,
                                    'quantity_purchase_sub' => $quantity_element,
                                    'unit_id' => 0,
                                    'quantity_exchange' => 1,
                                ];
                                $items = $this->products_model->getElementItemsByElementId($val['id']);
                                foreach ($items as $n => $v) {
                                    $type_warehouse = '';
                                    if ($v['type'] == "semi_products" || $v['type'] == "semi_products_outside") {
                                        $info = $this->products_model->rowProduct($v['item_id']);
                                        $type_warehouse = 'product';
                                        $unit_id = $v['unit_id'];
                                        $unit_purchase_id = $info['unit_id'];
                                        $quantity_exchange = 1;
                                    } else {
                                        $info = $this->items_model->rowMaterial($v['item_id']);
                                        $type_warehouse = 'nvl';
                                        $unit_id = $v['unit_id'];
                                        $unit_purchase_id = $info['unit_id'];
                                        $row_exchange = $this->products_model->rowExchangeItems($v['item_id'], $unit_id);
                                        $quantity_exchange = 1;
                                        if (!empty($row_exchange)) {
                                            $quantity_exchange = $row_exchange['number_exchange'];
                                        }
                                    }
                                    $quantity_warehouse = 0;

                                    $quantity_plan = $quantity * $v['quantity'];
                                    $quantity_purchase = 0;
                                    $unit = $this->unit_model->rowUnit($info['unit_id']);

                                    $rs[$key]['sub'][] = [
                                        'parent_id' => 0,
                                        'type_sub' => $v['type'],
                                        'id_sub' => $v['item_id'],
                                        'code_sub' => $info['code'],
                                        'name_sub' => $info['name'],
                                        'quantity' => $v['quantity'],
                                        'quantity_minimum_sub' => $info['quantity_minimum'],
                                        'quantity_warehouse_sub' => $quantity_warehouse,
                                        'quantity_plan_sub' => $quantity_plan,
                                        'quantity_purchase_sub' => $quantity_purchase,
                                        'unit_id' => $unit_id,
                                        'quantity_exchange' => $quantity_exchange,
                                    ];

                                    if ($quantity_plan > 0) {
                                        if ($v['type'] == "semi_products")
                                        {
                                            $semi_p = $this->products_model->rowProduct($v['item_id']);
                                            $version_semi = $this->products_model->getBomByProductIdAndVersions($v['item_id'], $info['versions']);
                                            if (!empty($version_semi)) {
                                                $elements_semi = $this->products_model->getVersionsElementByVersionId($version_semi['id']);
                                                if (!empty($elements_semi)) {
                                                    foreach ($elements_semi as $ksm => $vsm) {
                                                        $quantity_element_semi = $quantity_plan * $vsm['quantity'];
                                                        $items_semi = $this->products_model->getElementItemsByElementId($vsm['id']);
                                                        foreach ($items_semi as $nn => $vv) {
                                                            $info_material = $this->items_model->rowMaterial($vv['item_id']);
                                                            $unit_id_material = $vv['unit_id'];
                                                            $unit_purchase_id_material = $info_material['unit_id'];
                                                            $row_exchange_material = $this->products_model->rowExchangeItems($vv['item_id'], $unit_id_material);
                                                            $quantity_exchange_material = 1;
                                                            if (!empty($row_exchange_material)) {
                                                                $quantity_exchange_material = $row_exchange_material['number_exchange'];
                                                            }

                                                            $quantity_plan_material = $quantity_element_semi * $vv['quantity'];
                                                            $quantity_plan_material = $quantity_plan_material/$quantity_exchange_material;

                                                            $arr_purchases[] = [
                                                                'type_sub' => $vv['type'],
                                                                'id_sub' => $vv['item_id'],
                                                                'code_sub' => $info_material['code'],
                                                                'name_sub' => $info_material['name'],
                                                                'quantity' => $vv['quantity'],
                                                                'quantity_minimum_sub' => $info_material['quantity_minimum'],
                                                                'quantity_warehouse_sub' => 0,
                                                                'quantity_plan_sub' => $quantity_plan_material,
                                                                'quantity_purchase_sub' => 0,
                                                                // 'unit_id' => $unit_id_material,
                                                                'unit_id' => $unit_purchase_id_material,
                                                                'quantity_exchange' => $quantity_exchange_material,
                                                            ];
                                                        }
                                                    }
                                                }
                                            }
                                            // print_arrays($arr_purchases);
                                        } else {
                                            $quantity_plan = $quantity_plan/$quantity_exchange;
                                            $arr_purchases[] = [
                                                'type_sub' => $v['type'],
                                                'id_sub' => $v['item_id'],
                                                'code_sub' => $info['code'],
                                                'name_sub' => $info['name'],
                                                'quantity' => $v['quantity'],
                                                'quantity_minimum_sub' => $info['quantity_minimum'],
                                                'quantity_warehouse_sub' => $quantity_warehouse,
                                                'quantity_plan_sub' => $quantity_plan,
                                                'quantity_purchase_sub' => 0,
                                                // 'unit_id' => $unit_id,
                                                'unit_id' => $unit_purchase_id,
                                                'quantity_exchange' => $quantity_exchange,
                                            ];
                                        }
                                    }
                                }
                            }
                        }
                    }
                    //stages
                    $st = $value['versions_stage'];
                    if (!empty($st)) {
                        $versions = $this->products_model->getProductStagesByProductIdAndVersions($product_id, $st);
                        if (!empty($versions)) {
                            $psv = $this->products_model->getProductStagesVersions($versions['id']);
                            if (!empty($psv)) {
                                $rs[$key]['st'] = [];
                                foreach ($psv as $k => $val) {
                                    $str_machines = '';
                                    $str_machines_code = '';
                                    if (!empty($val['machines'])) {
                                        $machines = $this->category_model->getMachinesByArrId(explode(',', $val['machines']));
                                        if (!empty($machines)) {
                                            foreach ($machines as $i => $v) {
                                                $str_machines.= $v['name'].'</br>';
                                                $str_machines_code.= $v['code'].'</br>';
                                            }
                                        }
                                    }
                                    $rs[$key]['st'][] = [
                                        'stage_id' => $val['stage_id'],
                                        'stage_name' => $val['stage_name'],
                                        'stage_code' => $val['stage_code'],
                                        'machine_id' => $val['machines'],
                                        'machine_name' => $str_machines,
                                        'machine_code' => $str_machines_code,
                                        'number_hours' => $val['number_hours'] * $quantity
                                    ];
                                }
                            }
                        }
                    }
                }

                //for purchases
                for ($i = 0; $i < count($arr_purchases); $i++) {
                    $temp_purchases = [];
                    for ($j = $i + 1; $j < count($arr_purchases); $j++) {
                        if (!empty($arr_purchases[$j]['id_sub']))
                        {
                            if ($arr_purchases[$i]['id_sub'] == $arr_purchases[$j]['id_sub'] && $arr_purchases[$i]['type_sub'] == $arr_purchases[$j]['type_sub'] && $arr_purchases[$i]['unit_id'] == $arr_purchases[$j]['unit_id'])
                            {
                                $temp_purchases[] = $j;
                            }
                        }
                    }
                    foreach ($temp_purchases as $key => $value) {
                        $arr_purchases[$i]['quantity_plan_sub']+= $arr_purchases[$value]['quantity_plan_sub'];
                        $arr_purchases[$i]['quantity_purchase_sub']+= $arr_purchases[$value]['quantity_purchase_sub'];
                        unset($arr_purchases[$value]);
                    }
                    $arr_purchases = array_values($arr_purchases);
                }

                // print_arrays($arr_purchases);

                $productions_capacity = [
                    'reference_no' => $reference_no,
                    'date' => $date,
                    'productions_plan_id' => implode(',', $productions_plan_id),
                    'productions_plan_reference_no' => $productions_plan_reference,
                    'note' => $note,
                    'status' => $status,
                    'status_purchases' => 'un_purchases',
                    'purchases_id' => 0,
                    'date_created' => date('Y-m-d H:i'),
                    'created_by' => get_staff_user_id(),
                ];
                $productions_capacity_id = $this->manufactures_model->insertProductionsCapacity($productions_capacity);
                if ($productions_capacity_id) {
                    if (getReference('productions_capacity') == $this->input->post('reference_no')) {
                        updateReference('productions_capacity');
                    }
                    //purchases
                    if (!empty($arr_purchases)) {
                        foreach ($arr_purchases as $k => $val) {
                            $arr_purchases[$k]['productions_capacity_id'] = $productions_capacity_id;

                            //warehouse
                            $type_warehouse = '';
                            if ($val['type_sub'] == "semi_products" || $val['type_sub'] == "semi_products_outside") {
                                $type_warehouse = 'product';
                                $type_purchase = 'product';
                                $unit_id = $val['unit_id'];
                            } else {
                                $type_warehouse = 'nvl';
                                $type_purchase = 'nvl';
                                $unit_id = $val['unit_id'];
                            }

                            //tồn kho
                            $quantity_warehouse = number_unformat($this->site_model->sumWarehouseItems($val['id_sub'], $type_warehouse)['quantity_warehouse']);
                            // $quantity_exchange = $arr_purchases[$k]['quantity_exchange'];
                            $quantity_exchange = 1;
                            $quantity_warehouse = $quantity_warehouse * $quantity_exchange;
                            // var_dump()
                            //hoach dinh chua tao yeu cau mua hang
                            // $total_capacity = number_unformat($this->manufactures_model->totalCapacityNotPurchase($val['id_sub'], $val['type_sub'])['total_capacity']);
                            $total_capacity = 0;

                            //yêu câu mua hang
                            // $quantity_proposed = $this->manufactures_model->totalProposedOfPurchases($val['id_sub'], $type_purchase)['total_proposed'];
                            $total_purchase = number_unformat($this->manufactures_model->totalProposedOfPurchasesNotOrder($val['id_sub'], $type_purchase)['total_proposed']);
                            $purchase = $this->manufactures_model->totalProposedOfPurchasesApartOrder($val['id_sub'], $type_purchase);

                            $total_purchase_had_order = number_unformat($purchase['total_proposed']);
                            if (!empty($purchase['purchase_id'])) {
                                $purchase_id = explode(',', $purchase['purchase_id']);
                                $total_order = number_unformat($this->manufactures_model->totalPurchaseOrderByPurchaseId($purchase_id, $val['id_sub'], $type_purchase)['total_ordering']);
                            } else {
                                $total_order = 0;
                            }
                            $quantity_proposed = $total_purchase + $total_purchase_had_order - $total_order;
                            if (empty($quantity_proposed)) $quantity_proposed = 0;

                            //đơn đặt hàng PO
                            $total_ordering = number_unformat($this->manufactures_model->totalOrderingOfPurchaseOrderNotImportAndNotCancel($val['id_sub'], $type_purchase)['total_ordering']);
                            $ordering_import = $this->manufactures_model->totalOrderingOfPurchaseOrderImportAndNotCancel($val['id_sub'], $type_purchase);

                            $total_ordering_had_import = number_unformat($ordering_import['total_ordering_had_import']);
                            if (!empty($ordering_import['order_id'])) {
                                $order_id = explode(',', $ordering_import['order_id']);
                                $total_import = number_unformat($this->manufactures_model->totalImportOfImportOrderId($order_id, $val['id_sub'], $type_purchase)['total_import']);
                            } else {
                                $total_import = 0;
                            }

                            $quantity_ordering = $total_ordering + $total_ordering_had_import - $total_import;
                            if (empty($quantity_ordering)) $quantity_ordering = 0;

                            //Tong doi san xuat
                            $quantity_waiting_production = 0;

                            $quantity_warehouse_reality = $quantity_warehouse + $quantity_proposed + $quantity_ordering - $quantity_waiting_production - $total_capacity;

                            $quantity_plan = $arr_purchases[$k]['quantity_plan_sub'];
                            $quantity_purchase = 0;
                            $min = $arr_purchases[$k]['quantity_minimum_sub'];

                            if ($quantity_warehouse_reality >= ($quantity_plan + $min)) {
                                $quantity_purchase = 0;
                            } else {
                                $quantity_purchase = $quantity_plan - $quantity_warehouse_reality + $min;
                            }

                            $arr_purchases[$k]['quantity_capacity'] = $total_capacity;
                            $arr_purchases[$k]['quantity_warehouse_sub'] = $quantity_warehouse;
                            $arr_purchases[$k]['quantity_mini_exchange'] = $min;
                            $arr_purchases[$k]['quantity_proposed'] = $quantity_proposed;
                            $arr_purchases[$k]['quantity_ordering'] = $quantity_ordering;
                            $arr_purchases[$k]['quantity_waiting_production'] = $quantity_waiting_production;
                            $arr_purchases[$k]['quantity_warehouse_reality'] = $quantity_warehouse_reality;
                            $arr_purchases[$k]['quantity_purchase_sub'] = $quantity_purchase;

                            // $arr_purchases[$k]['quantity_mini_exchange'] = $arr_purchases[$k]['quantity_minimum_sub'] * $arr_purchases[$k]['quantity_exchange'];
                            // $arr_purchases[$k]['quantity_purchase_sub'] = $arr_purchases[$k]['quantity_plan_sub'] - $arr_purchases[$k]['quantity_warehouse_sub'] + $arr_purchases[$k]['quantity_mini_exchange'];

                            // if ($val['quantity_purchase_sub'] < 0) {
                            //     $arr_purchases[$k]['quantity_purchase_sub'] = 0;
                            // }
                        }
                        $this->manufactures_model->insertBatchProductionsCapacityItemsPurchases($arr_purchases);
                    }
                    //
                    foreach ($rs as $key => $value) {
                        $op = [
                            'productions_capacity_id' => $productions_capacity_id,
                            'type_items' => 'products',
                            'items_id' => $value['product_id'],
                            'items_code' => $value['code'],
                            'items_name' => $value['name'],
                            'quantity_minimum' => $value['quantity_minimum'],
                            'quantity_warehouse' => $value['quantity_warehouses'],
                            'quantity_plan' => $value['quantity_use'],
                            'quantity_purchase' => $value['quantity_productions'],
                            'number_labor' => $value['number_labor'],
                            'versions_bom' => $value['versions'],
                            'versions_stages' => $value['versions_stage'],
                        ];
                        $productions_capacity_items_id = $this->manufactures_model->insertProductionsCapacityItems($op);
                        if ($productions_capacity_items_id) {
                            $sub = $value['sub'];
                            if (!empty($sub)) {
                                $o = [];
                                foreach ($sub as $k => $val) {
                                    $o[] = [
                                        'productions_capacity_items_id' => $productions_capacity_items_id,
                                        'type_sub' => $val['type_sub'],
                                        'id_sub' => $val['id_sub'],
                                        'code_sub' => $val['code_sub'],
                                        'name_sub' => $val['name_sub'],
                                        'quantity' => $val['quantity'],
                                        'quantity_minimum_sub' => $val['quantity_minimum_sub'],
                                        'quantity_warehouse_sub' => $val['quantity_warehouse_sub'],
                                        'quantity_plan_sub' => $val['quantity_plan_sub'],
                                        'quantity_purchase_sub' => $val['quantity_purchase_sub'],
                                        'unit_id' => $val['unit_id'],
                                        'quantity_exchange' => $val['quantity_exchange'],
                                    ];
                                }
                                $this->manufactures_model->insertBatchProductionsCapacityItemsSub($o);
                            }
                            $st = $value['st'];
                            if (!empty($st)) {
                                $s = [];
                                foreach ($st as $k => $val) {
                                    $s[] = [
                                        'productions_capacity_items_id' => $productions_capacity_items_id,
                                        'stage_id' => $val['stage_id'],
                                        'stage_name' => $val['stage_name'],
                                        'stage_code' => $val['stage_code'],
                                        'machine_id' => $val['machine_id'],
                                        'machine_name' => $val['machine_name'],
                                        'machine_code' => $val['machine_code'],
                                        'number_hours' => $val['number_hours'],
                                    ];
                                }
                                $this->manufactures_model->insertBatchProductionsCapacityItemsStages($s);
                            }
                        }
                    }

                    //sub semi_product
                    $items_sub_semi_product = $this->manufactures_model->getProductionsCapacityItemsSubBySemiProduct($productions_capacity_id);
                    foreach ($items_sub_semi_product as $key => $value) {
                        $semi_p = $this->products_model->rowProduct($value['id_sub']);
                        $version_semi = $this->products_model->getBomByProductIdAndVersions($value['id_sub'], $semi_p['versions']);
                        if (!empty($version_semi)) {
                            $elements_semi = $this->products_model->getVersionsElementByVersionId($version_semi['id']);
                            if (!empty($elements_semi)) {
                                foreach ($elements_semi as $ksm => $vsm) {
                                    $quantity_element_semi = $value['quantity_plan_sub'] * $vsm['quantity'];
                                    $osm[] = [
                                        'parent_id' => $value['id'],
                                        'productions_capacity_items_id' => $value['productions_capacity_items_id'],
                                        'type_sub' => 'element',
                                        'id_sub' => 0,
                                        'code_sub' => $vsm['element_name'],
                                        'name_sub' => $vsm['element_name'],
                                        'quantity' => $vsm['quantity'],
                                        'quantity_minimum_sub' => 0,
                                        'quantity_warehouse_sub' => 0,
                                        'quantity_plan_sub' => $quantity_element_semi,
                                        'quantity_purchase_sub' => 0,
                                        'unit_id' => 0,
                                        'quantity_exchange' => 1,
                                    ];
                                    $items_semi = $this->products_model->getElementItemsByElementId($vsm['id']);
                                    foreach ($items_semi as $nn => $vv) {
                                        $info_material = $this->items_model->rowMaterial($vv['item_id']);
                                        $unit_id_material = $vv['unit_id'];
                                        $row_exchange_material = $this->products_model->rowExchangeItems($vv['item_id'], $unit_id_material);
                                        $quantity_exchange_material = 1;
                                        if (!empty($row_exchange_material)) {
                                            $quantity_exchange_material = $row_exchange_material['number_exchange'];
                                        }

                                        $quantity_plan_material = $quantity_element_semi * $vv['quantity'];

                                        $osm[] = [
                                            'parent_id' => $value['id'],
                                            'productions_capacity_items_id' => $value['productions_capacity_items_id'],
                                            'type_sub' => $vv['type'],
                                            'id_sub' => $vv['item_id'],
                                            'code_sub' => $info_material['code'],
                                            'name_sub' => $info_material['name'],
                                            'quantity' => $vv['quantity'],
                                            'quantity_minimum_sub' => $info_material['quantity_minimum'],
                                            'quantity_warehouse_sub' => 0,
                                            'quantity_plan_sub' => $quantity_plan_material,
                                            'quantity_purchase_sub' => 0,
                                            'unit_id' => $unit_id_material,
                                            'quantity_exchange' => $quantity_exchange_material,
                                        ];
                                    }
                                }
                            }
                        }
                    }

                    // print_arrays(123);
                    if (!empty($osm)) {
                        $this->manufactures_model->insertBatchProductionsCapacityItemsSub($osm);
                    }

                    foreach ($productions_plan_id as $key => $value) {
                        $this->manufactures_model->updateProductionsPlan($value, ['status' => 'capacity', 'productions_capacity_id' => $productions_capacity_items_id]);
                    }
                    set_alert('success', lang('success'));
                    $data['result'] = 1;
                    $data['message'] = lang('success');
                }
            } else {
                $data['result'] = 0;
                $data['message'] = validation_errors();
            }
            echo json_encode($data);
            die;
        }
        $data['tnh'] = $this->tnh;
        $data['reference_no'] = getReference('productions_capacity');
        $data['breadcrumb'] = [array('link' => base_url('admin/manufactures/productions_capacity'), 'page' => lang('productions_capacity')), array('link' => '#', 'page' => lang('add_productions_capacity'))];
        $data['title'] = lang('add_productions_capacity');
        $this->load->view('admin/manufactures/add_productions_capacity', $data);
    }

    public function calPurchases() {
        $data = [];
        if ($this->input->post())
        {
            $productions_capacity_id = $this->input->post('productions_capacity_id');
            $items_sub = $this->manufactures_model->getProductionsCapacityItemsSubForPurchase($productions_capacity_id);
            $arr_purchases = [];
            foreach ($items_sub as $key => $value) {
                $type_sub = $value['type_sub'];
                $item_id = $value['id_sub'];
                if ($type_sub == "semi_products_outside") {
                    $info = $this->products_model->rowProduct($item_id);
                    $unit_id = $info['unit_id'];
                } else {
                    $info = $this->items_model->rowMaterial($item_id);
                    $unit_id = $info['unit_id'];
                }
                $quantity_exchange = 1;
                $quantity_plan_sub = $value['quantity_plan'];

                $type_warehouse = '';
                if ($type_sub == "semi_products" || $type_sub == "semi_products_outside") {
                    $type_warehouse = 'product';
                    $type_purchase = 'product';
                } else {
                    $type_warehouse = 'nvl';
                    $type_purchase = 'nvl';
                }

                $quantity_warehouse = number_unformat($this->site_model->sumWarehouseItems($item_id, $type_warehouse)['quantity_warehouse']);
                $total_capacity = 0;
                // $total_capacity = number_unformat($this->manufactures_model->totalCapacityNotPurchase($item_id, $type_sub)['total_warehouse_reality']);

                $total_purchase = number_unformat($this->manufactures_model->totalProposedOfPurchasesNotOrder($item_id, $type_purchase)['total_proposed']);

                $purchase = $this->manufactures_model->totalProposedOfPurchasesApartOrder($item_id, $type_purchase);

                $total_purchase_had_order = number_unformat($purchase['total_proposed']);
                if (!empty($purchase['purchase_id'])) {
                    $purchase_id = explode(',', $purchase['purchase_id']);
                    $total_order = number_unformat($this->manufactures_model->totalPurchaseOrderByPurchaseId($purchase_id, $item_id, $type_purchase)['total_ordering']);
                } else {
                    $total_order = 0;
                }
                $quantity_proposed = $total_purchase + $total_purchase_had_order - $total_order;
                if (empty($quantity_proposed)) $quantity_proposed = 0;

                //đơn đặt hàng PO
                $total_ordering = number_unformat($this->manufactures_model->totalOrderingOfPurchaseOrderNotImportAndNotCancel($item_id, $type_purchase)['total_ordering']);
                $ordering_import = $this->manufactures_model->totalOrderingOfPurchaseOrderImportAndNotCancel($item_id, $type_purchase);
                $total_ordering_had_import = number_unformat($ordering_import['total_ordering_had_import']);
                if (!empty($ordering_import['order_id'])) {
                    $order_id = explode(',', $ordering_import['order_id']);
                    $total_import = number_unformat($this->manufactures_model->totalImportOfImportOrderId($order_id, $item_id, $type_purchase)['total_import']);
                    $total_import_not_agree = number_unformat($this->manufactures_model->totalImportNotAgreeWarehouseNotPlan($order_id, $item_id, $type_purchase)['total_import']);
                } else {
                    $total_import = 0;
                    $total_import_not_agree = number_unformat($this->manufactures_model->totalImportNotAgreeWarehouseNotPlan(false, $item_id, $type_purchase)['total_import']);
                }

                $quantity_ordering = $total_ordering + $total_ordering_had_import - $total_import + $total_import_not_agree;
                if (empty($quantity_ordering)) $quantity_ordering = 0;


                //Tong doi san xuat
                $quantity_waiting_production = 0;

                $quantity_warehouse_reality = $quantity_warehouse + $quantity_proposed + $quantity_ordering - $quantity_waiting_production - $total_capacity;

                $quantity_plan = $quantity_plan_sub;
                $quantity_purchase = 0;
                $min = $info['quantity_minimum'];

                if ($quantity_warehouse_reality >= ($quantity_plan + $min)) {
                    $quantity_purchase = 0;
                } else {
                    $quantity_purchase = $quantity_plan - $quantity_warehouse_reality + $min;
                }

                // if ($item_id == 3) {
                //     print_arrays($quantity_proposed, '</br>', $total_ordering, '</br>', $quantity_warehouse_reality);
                // }

                $arr_purchases[$key]['productions_capacity_id'] = $productions_capacity_id;
                $arr_purchases[$key]['type_sub'] = $type_sub;
                $arr_purchases[$key]['id_sub'] = $item_id;
                $arr_purchases[$key]['code_sub'] = $info['code'];
                $arr_purchases[$key]['name_sub'] = $info['name'];
                $arr_purchases[$key]['unit_id'] = $unit_id;
                $arr_purchases[$key]['quantity_exchange'] = $quantity_exchange;
                $arr_purchases[$key]['quantity'] = 1;
                $arr_purchases[$key]['quantity_minimum_sub'] = $info['quantity_minimum'];
                $arr_purchases[$key]['quantity_mini_exchange'] = $info['quantity_minimum'];
                $arr_purchases[$key]['quantity_capacity'] = $total_capacity;
                $arr_purchases[$key]['quantity_warehouse_sub'] = $quantity_warehouse;
                $arr_purchases[$key]['quantity_mini_exchange'] = $min;
                $arr_purchases[$key]['quantity_proposed'] = $quantity_proposed;
                $arr_purchases[$key]['quantity_ordering'] = $quantity_ordering;
                $arr_purchases[$key]['quantity_waiting_production'] = $quantity_waiting_production;
                $arr_purchases[$key]['quantity_warehouse_reality'] = $quantity_warehouse_reality;
                $arr_purchases[$key]['quantity_plan_sub'] = $quantity_plan_sub;
                $arr_purchases[$key]['quantity_purchase_sub'] = $quantity_purchase;
            }

            if (!empty($arr_purchases)) {
                $this->manufactures_model->deleteProductionsCapacityPurchases($productions_capacity_id);
                $this->manufactures_model->insertBatchProductionsCapacityItemsPurchases($arr_purchases);
            }
            $productions_capacity_items = $this->manufactures_model->getProductionsCapacityItemsPurchases($productions_capacity_id);
            $data['items'] = $productions_capacity_items;
            $data['result'] = 1;
            $data['message'] = lang('success');
        }
        echo json_encode($data);
    }

    public function delete_productions_capacity($id)
    {
        $data = [];
        if ($id) {
            $productions_capacity = $this->manufactures_model->rowProductionsCapacity($id);
            if ($productions_capacity['status'] == "un_approved") {
                if ($productions_capacity['status_purchases'] == 'un_purchases') {
                    $productions_capacity_items = $this->manufactures_model->getProductionsCapacityItems($id);
                    if ($this->manufactures_model->deleteProductionsCapacity($id)) {
                        $this->manufactures_model->deleteProductionsCapacityItems($id);
                        $this->manufactures_model->deleteProductionsCapacityPurchases($id);
                        foreach ($productions_capacity_items as $key => $value) {
                            $this->manufactures_model->deleteProductionsCapacityItemsSub($value['id']);
                            $this->manufactures_model->deleteProductionsCapacityItemsStages($value['id']);
                        }
                        foreach (explode(',', $productions_capacity['productions_plan_id']) as $key => $value) {
                            $this->manufactures_model->updateProductionsPlan($value, ['status' => 'approved', 'productions_capacity_id' => 0]);
                        }
                        $data['result'] = 1;
                        $data['message'] = lang('success');
                    } else {
                        $data['result'] = 0;
                        $data['message'] = lang('fail');
                    }
                } else {
                    $data['result'] = 0;
                    $data['message'] = lang('tnh_created_purchase_order_form_not_delete');
                }
            } else {
                $data['result'] = 0;
                $data['message'] = lang('browsed_cannot_be_deleted');
            }
        } else {
            $data['result'] = 0;
            $data['message'] = lang('fail');
        }
        echo json_encode($data);
    }

    function viewTableProductionsCapacity()
    {
        $data['tnh'] = $this->tnh;
        $arr_productions_plan_id = $this->input->post('arr_productions_plan_id');
        $data['arr_productions_plan_id'] = $this->input->post('arr_productions_plan_id');

        $this->load->view('admin/manufactures/view_table_productions_capacity', $data);
    }

    function getTableProductionsCapacity()
    {
        $arr_productions_plan_id = $this->input->post('arr_productions_plan_id');
        $where_in = "('".str_replace(',', "','", $arr_productions_plan_id)."')";
        $items = "(
            SELECT
                tbl_productions_plan_items.product_id as product_id,
                SUM(tbl_productions_plan_details.quantity) as total_quantity
            FROM tbl_productions_plan
            INNER JOIN tbl_productions_plan_items ON tbl_productions_plan.id = tbl_productions_plan_items.productions_plan_id
            INNER JOIN tbl_productions_plan_details ON tbl_productions_plan_details.productions_plan_item_id = tbl_productions_plan_items.id
            WHERE tbl_productions_plan_items.productions_plan_id IN $where_in AND tbl_productions_plan.status = 'approved'
            GROUP BY tbl_productions_plan_items.product_id
        ) as items";

        $warehouses = "(
            SELECT
                tblwarehouse_items.id_items,
                SUM(tblwarehouse_items.product_quantity) as quantity_warehouses
            FROM tblwarehouse_items
            WHERE tblwarehouse_items.type_items = 'product'
            GROUP BY tblwarehouse_items.id_items
        ) warehouses";

        $this->datatables->select("
                tbl_products.id as product_id,
                tbl_products.code as code,
                tbl_products.name as name,
                tblunits.unit as unit,
                tbl_products.quantity_minimum as quantity_minimum,
                COALESCE(warehouses.quantity_warehouses, 0) as quantity_warehouses,
                COALESCE(items.total_quantity, 0) as quantity_use,
                COALESCE(items.total_quantity, 0) - COALESCE(warehouses.quantity_warehouses, 0) + tbl_products.quantity_minimum as quantity_productions,
                tbl_products.number_labor as number_labor,
                '' as sub,
                '' as st,
            ", false)
            ->from("tbl_products")
            ->join('tblunits', 'tblunits.unitid = tbl_products.unit_id', 'left')
            ->join($warehouses, 'warehouses.id_items = tbl_products.id', 'left')
            ->join($items, 'items.product_id = tbl_products.id');

        $iDisplayStart = $this->input->post('iDisplayStart');
        $data = json_decode($this->datatables->generate());
        foreach ($data->aaData as $key => $value) {
            $number_records = ++$iDisplayStart;
            $data->aaData[$key][0] = $number_records;

            $product_id = $value[0];
            $product = $this->products_model->rowProduct($product_id);
            $vs = $product['versions'];
            $quantity = $value[7];
            if ($quantity < 0) {
                $quantity = 0;
                $data->aaData[$key][7] = 0;
            }
            if (!empty($vs)) {
                $version = $this->products_model->getBomByProductIdAndVersions($product_id, $vs);
                if (!empty($version)) {
                    $elements = $this->products_model->getVersionsElementByVersionId($version['id']);
                    $number_element = 1;
                    $ii = 9;
                    $data->aaData[$key][$ii] = [];
                    foreach ($elements as $k => $val) {
                        $quantity_element = $quantity * $val['quantity'];
                        $data->aaData[$key][$ii][$k] = [
                            'number_records' => $number_records.'.'.$number_element,
                            'code' => $val['element_name'],
                            'name' => $val['element_name'],
                            'unit' => '',
                            'type' => lang('tnh_element'),
                            'versions' => $vs,
                            'quantity' => $quantity_element
                        ];

                        $items = $this->products_model->getElementItemsByElementId($val['id']);
                        $number_item = 1;
                        foreach ($items as $n => $v) {
                            $type_warehouse = '';
                            if ($v['type'] == "semi_products" || $v['type'] == "semi_products_outside") {
                                $info = $this->products_model->rowProduct($v['item_id']);
                                $type_warehouse = 'product';
                            } else {
                                $info = $this->items_model->rowMaterial($v['item_id']);
                                $type_warehouse = 'nvl';
                            }
                            $quantity_warehouse = $this->site_model->sumWarehouseItems($v['item_id'], $type_warehouse)['quantity_warehouse'];
                            $quantity_item = $quantity * $v['quantity'];
                            $quantity_purchase = $quantity_item + $info['quantity_minimum'] - $quantity_warehouse;
                            $unit = $this->unit_model->rowUnit($v['unit_id']);
                            $data->aaData[$key][$ii][$k]['sub_items'][] = [
                                'number_records' => $number_records.'.'.$number_element.'.'.$number_item,
                                'code' => $info['code'],
                                'name' => $info['name'],
                                'unit' => $unit['unit'],
                                'type' => lang($v['type']),
                                'quantity_minimum' => $info['quantity_minimum'],
                                'quantity_warehouse' => $quantity_warehouse,
                                'quantity' => $quantity_item,
                                'quantity_purchase' => $quantity_purchase
                            ];
                            $number_item++;
                        }
                        $number_element++;
                    }
                }
            }

            //stages
            $st = $product['versions_stage'];
            if (!empty($st)) {
                $ij = 10;
                $number = 1;
                $versions = $this->products_model->getProductStagesByProductIdAndVersions($product_id, $st);
                if (!empty($versions)) {
                    $psv = $this->products_model->getProductStagesVersions($versions['id']);
                    if (!empty($psv)) {
                        $data->aaData[$key][$ij] = [];
                        foreach ($psv as $k => $val) {
                            $str_machines = '';
                            if (!empty($val['machines'])) {
                                $machines = $this->category_model->getMachinesByArrId(explode(',', $val['machines']));
                                if (!empty($machines)) {
                                    foreach ($machines as $i => $v) {
                                        $str_machines.= $v['name'].'</br>';
                                    }
                                }
                            }
                            $data->aaData[$key][$ij][$k] = [
                                'number_records' => $number,
                                'stage_name' => $val['stage_name'],
                                'machine_name' => $str_machines,
                                'versions' => $st,
                                'number_hours' => $val['number_hours'] * $quantity
                            ];
                            $number++;
                        }
                    }
                }
            }
        }

        // //statistical
        // $this->db->select('
        //     tbl_products.id
        //     ', false);
        // $this->db->from('tbl_products');
        // $this->db->join($warehouses, 'warehouses.id_items = tbl_products.id', 'left');
        // $this->db->join($items, 'items.product_id = tbl_products.id');

        // $data->statistical = $iDisplayStart;
        // print_arrays($data);
        echo json_encode($data);
    }

    function refereshReferenceProductionsCapacity()
    {
        $data = [];
        if ($this->input->get('referesh'))
        {
            $reference_no = getReference('productions_capacity');
            if ($this->manufactures_model->checkExistProductionsPlanByReferenceNo($reference_no)) {
                $this->db->select('MAX(tbl_productions_capacity.reference_no) as reference_no', false);
                $this->db->from('tbl_productions_capacity');
                $rs = $this->db->get()->row_array();

                $max = $rs['reference_no'];
                $max = subReference($max);
                updateReferenceNormal('productions_capacity', $max);
                $reference_no = getReference('productions_capacity');
            }
            $data['reference_no'] = $reference_no;
            $data['message'] = lang('tnh_referesh_success');
        }
        echo json_encode($data);
    }

    public function view_productions_capacity($id) {
        $productions_capacity = $this->manufactures_model->rowProductionsCapacity($id);
        $data['id'] = $id;
        $data['created_by'] = get_staff_full_name($productions_capacity['created_by']);
        if (!empty($productions_capacity['user_status']))
        {
            $data['user_status'] = get_staff_full_name($productions_capacity['user_status']);
        } else {
            $data['user_status'] = '';
        }
        $data['id'] = $id;
        $data['productions_capacity'] = $productions_capacity;
        $this->load->view('admin/manufactures/view_productions_capacity', $data);
    }

    function getViewProductionsCapacity() {
        $productions_capacity_id = $this->input->post('view_productions_capacity_id');

        $this->datatables->select("
            0 as number_records,
            tbl_productions_capacity_items.items_code as code,
            tbl_productions_capacity_items.items_name as name,
            tblunits.unit as unit,
            tbl_productions_capacity_items.quantity_minimum as quantity_minimum,
            COALESCE(tbl_productions_capacity_items.quantity_warehouse, 0) as quantity_warehouses,
            COALESCE(tbl_productions_capacity_items.quantity_plan, 0) as quantity_use,
            COALESCE(tbl_productions_capacity_items.quantity_purchase, 0) as quantity_productions,
            tbl_productions_capacity_items.number_labor as number_labor,
            tbl_productions_capacity_items.versions_bom as sub,
            tbl_productions_capacity_items.versions_stages as st,
            tbl_productions_capacity_items.id as id,
            ", false)
        ->from('tbl_productions_capacity_items')
        ->join('tbl_products', 'tbl_products.id = tbl_productions_capacity_items.items_id', 'left')
        ->join('tblunits', 'tblunits.unitid = tbl_products.unit_id', 'left');

        $this->datatables->where('tbl_productions_capacity_items.productions_capacity_id', $productions_capacity_id);
        $iDisplayStart = $this->input->post('iDisplayStart');
        $data = json_decode($this->datatables->generate());
        foreach ($data->aaData as $key => $value) {
            $number_records = ++$iDisplayStart;
            $data->aaData[$key][0] = $number_records;
            $isub = 9;
            $ist = 10;
            $iid = 11;
            $versions_bom = $value[$isub];
            $versions_stages = $value[$ist];
            $productions_capacity_items_id = $value[$iid];
            $sub = $this->manufactures_model->getProductionsCapacityItemsSub($productions_capacity_items_id);
            $st = $this->manufactures_model->getProductionsCapacityItemsStages($productions_capacity_items_id);
            $data->aaData[$key][$isub] = $sub;
            $data->aaData[$key][$isub][0]['versions_bom'] = $versions_bom;
            $data->aaData[$key][$ist] = $st;
            $data->aaData[$key][$ist][0]['versions_stages'] = $versions_stages;
            unset($data->aaData[$key][$iid]);
        }
        // print_arrays($data->aaData);
        echo json_encode($data);
    }

    function getViewProductionsCapacityStatistical() {
        $productions_capacity_id = $this->input->post('view_productions_capacity_id');

        $this->datatables->select("
            0 as number_records,
            tbl_productions_capacity_items_purchases.code_sub as code,
            tbl_productions_capacity_items_purchases.name_sub as name,
            tbl_productions_capacity_items_purchases.type_sub as type,
            tblunits.unit as unit_name,
            tbl_productions_capacity_items_purchases.quantity_mini_exchange as quantity_minimum,
            tbl_productions_capacity_items_purchases.quantity_warehouse_reality as quantity_warehouse,
            tbl_productions_capacity_items_purchases.quantity_plan_sub as quantity_plan,
            tbl_productions_capacity_items_purchases.quantity_purchase_sub as quantity_purchase,
            ", false)
        ->from('tbl_productions_capacity_items_purchases')
        ->join('tblunits', 'tblunits.unitid = tbl_productions_capacity_items_purchases.unit_id', 'left');

        $this->datatables->where('tbl_productions_capacity_items_purchases.productions_capacity_id', $productions_capacity_id);
        $this->datatables->where('tbl_productions_capacity_items_purchases.quantity_purchase_sub >', 0);
        $iDisplayStart = $this->input->post('iDisplayStart');
        $data = json_decode($this->datatables->generate());
        foreach ($data->aaData as $key => $value) {
            $number_records = ++$iDisplayStart;
            $data->aaData[$key][0] = $number_records;
        }
        // print_arrays($data->aaData);
        echo json_encode($data);
    }

    function agreeProductionsCapacity()
    {
        $data = [];
        if ($this->input->get())
        {
            $productions_capacity_id = $this->input->get('productions_capacity_id');
            $status = $this->input->get('status');
            $productions_capacity = $this->manufactures_model->rowProductionsCapacity($productions_capacity_id);

            $date = date('Y-m-d H:i');
            $user_id = get_staff_user_id();
            if ($productions_capacity['status'] == $status) {
                $data['result'] = 0;
                $data['message'] = lang('tnh_please_referesh_table');
                echo json_encode($data); die;
            }
            if ($productions_capacity['purchases_id'] > 0) {
                $data['result'] = 0;
                $data['message'] = lang('tnh_created_requrest');
                echo json_encode($data); die;
            }
            $up = $this->manufactures_model->updateProductionsCapacity($productions_capacity_id, [
                'status' => $status,
                'date_status' => $date,
                'user_status' => $user_id
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

    public function capacity_convert_purchase($id) {
        $productions_capacity = $this->manufactures_model->rowProductionsCapacity($id);
        $productions_capacity_items = $this->manufactures_model->getProductionsCapacityItemsPurchases($id);
        if ($productions_capacity['status_purchases'] == "purchases") {
            refererModel(lang('tnh_created_purchase_order_form'));
        }
        if ($this->input->post('save')) {
            $data = [];
            $this->form_validation->set_rules('date', lang("date"), 'required');
            if ($this->form_validation->run() == true)
            {
                $productions_capacity_id = $this->input->get('productions_capacity');
                if (empty($productions_capacity) || empty($productions_capacity_items)) {
                    $data['result'] = 0;
                    $data['message'] = lang('no_data_exists');
                } else {
                    if ($productions_capacity['status'] != "approved") {
                        $data['result'] = 0;
                        $data['message'] = lang('tnh_productions_capacity_status_convert_purchases');
                    } else {
                        if ($productions_capacity['status_purchases'] != "purchases")
                        {
                            $date = $this->input->post('date');
                            $name = $this->input->post('name');
                            $note = $this->input->post('note');

                            foreach ($productions_capacity_items as $key => $value) {
                                $type = $value['type_sub'];
                                $quantity_exchange = $value['quantity_exchange'];
                                if ($type == "materials") {
                                    $type = "nvl";
                                    // $quantity = $value['quantity_purchase_sub']/$quantity_exchange;
                                    $quantity = $value['quantity_purchase_sub'];
                                } else if ($type == "semi_products" || $type == "semi_products_outside") {
                                    $type = "product";
                                    $quantity = $value['quantity_purchase_sub'];
                                }
                                if ($quantity <= 0) {
                                    continue;
                                }
                                $items[] = [
                                    'id' => $value['id_sub'],
                                    'quantity' => $quantity,
                                    'quantity_net' => $quantity,
                                    'type' => $type,
                                    'note' => ''
                                ];
                            }

                            if (empty($items)) {
                                $data['result'] = 0;
                                $data['message'] = lang('un_not_items_purchase');
                                echo json_encode($data);
                                die;
                            }

                            // print_arrays($items);
                            $fields = [
                                'id_plan' => $id,
                                'name' => $name,
                                'reason' => $note,
                                'date' => $date,
                                'items' => $items
                            ];
                            $purchases_id = $this->purchases_model->convertCapactityToPurchase($fields);
                            if ($purchases_id > 0) {
                                $this->manufactures_model->updateProductionsCapacity($id, [
                                    'status_purchases' => 'purchases',
                                    'purchases_id' => $purchases_id
                                ]);
                                $data['result'] = 1;
                                $data['message'] = lang('success');
                            } else {
                                $data['result'] = 0;
                                $data['message'] = lang('fail');
                            }
                        } else {
                            $data['result'] = 0;
                            $data['message'] = lang('tnh_created_purchase_order_form');
                        }
                    }
                }
            } else {
                $data['result'] = 0;
                $data['message'] = validation_errors();
            }
            echo json_encode($data);
        } else {
            $data['productions_capacity_items'] = $productions_capacity_items;
            $data['id'] = $id;
            $this->load->view('admin/manufactures/capacity_convert_purchase', $data);
        }
    }

    //productions orders
    public function productions_orders()
    {
        $data['tnh'] = $this->tnh;
        $data['title'] = lang('productions_orders');
        $this->load->view('admin/manufactures/productions_orders', $data);
    }

    function getProductionsOrders()
    {
        $status_table = $this->input->post('status_table');
        $this->db->simple_query('SET SESSION group_concat_max_len=88888885000');
        $productions_orders_items = "(
            SELECT
                GROUP_CONCAT(
                    CONCAT(COALESCE(tbl_products.images, ''), '___', tbl_products.code, '___', tbl_products.name)
                    SEPARATOR ':::'
                )
            FROM tbl_productions_orders_items
            LEFT JOIN tbl_products ON tbl_products.id = tbl_productions_orders_items.items_id
            WHERE tbl_productions_orders_items.productions_orders_id = tbl_productions_orders.id AND tbl_productions_orders_items.type_items = 'products'
        )";

        $this->datatables->select("
            tbl_productions_orders.id as id,
            0 as number_records,
            tbl_productions_orders.date as date,
            tbl_productions_orders.reference_no as reference_no,
            tbl_capacity.name as location_name,
            tbl_productions_orders.productions_plan_reference_no as productions_plan_reference_no,
            $productions_orders_items as items,
            tbl_productions_orders.total_quantity as total_quantity,
            tbl_productions_orders.note as note,
            CONCAT(tblstaff.firstname, ' ', tblstaff.lastname,'') as created_by,
            tbl_productions_orders.status as status,
            CONCAT(staff_status.firstname, ' ', staff_status.lastname,'') as user_status,
            CONCAT(tbl_productions_orders.status_details, '||', '') as status_productions
            ", FALSE)
        ->from('tbl_productions_orders')
        ->join('tbl_capacity', 'tbl_capacity.id = tbl_productions_orders.location_id', 'left')
        ->join('tblstaff', 'tblstaff.staffid = tbl_productions_orders.created_by', 'left')
        ->join('tblstaff staff_status', 'staff_status.staffid = tbl_productions_orders.user_status', 'left');

        $view = '<a class="tnh-modal" title="'.lang('view').'" data-tnh="modal" data-toggle="modal" data-target="#myModal" href="'.base_url('admin/manufactures/view_productions_orders/$1').'"><i class="fa fa-file-text-o width-icon-actions"></i> '.lang('view').'</a>';

        $edit = '<a class="" title="'.lang('edit').'" href="'.base_url('admin/manufactures/edit_productions_orders/$1').'"><i class="fa fa-pencil width-icon-actions"></i> '.lang('edit').'</a>';

        $delete = '<a type="button" class="po" title="'.lang('delete').'" data-container="body" data-html="true" data-toggle="popover" data-placement="left" data-content="
            <button href=\''.base_url('admin/manufactures/delete_productions_orders/$1').'\' class=\'btn btn-danger po-delete-json\'>'.lang('delete').'</button>
            <button class=\'btn btn-default po-close\'>'.lang('close').'</button>
        "><i class="fa fa-remove width-icon-actions"></i> '.lang('delete').'</a>';

        // if ($status_table == 'purchases' || $status_table == 'un_purchases') {
        //     $this->datatables->where('tbl_productions_orders.status_purchases', $status_table);
        // } else if ($status_table) {
        //     $this->datatables->where('tbl_productions_orders.status', $status_table);
        // }

        $created_productions_detail = '<a class="tnh-modal created-detail" data-tnh="modal" data-toggle="modal" data-target="#myModal" title="'.lang('created_productions_detail').'" href="'.base_url('admin/manufactures/created_productions_detail/$1').'"><i class="fa fa-plus width-icon-actions"></i> '.lang('created_productions_detail').'</a>';

        $deleted_productions_detail = '<a type="button" data-toggle="popover" class="po-delete delete-detail" title="'.lang('delete').'" data-container="body" data-html="true" data-placement="left" data-content="
            <button href=\''.base_url('admin/manufactures/delete_productions_orders_detail/$1').'\' class=\'btn btn-danger po-delete-json\'>'.lang('delete').'</button>
            <button class=\'btn btn-default po-close-new\'>'.lang('close').'</button>
        "><i class="fa fa-remove width-icon-actions"></i> '.lang('tnh_delete_productions_order_detail').'</a>';

        $actions = '
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle nav-link" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
            '.lang('actions').'
            <span class="caret"></span>
            </button>
            <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdownMenu1">
                <li>'.$view.'</li>
                <li>'.$edit.'</li>
                <li>'.$created_productions_detail.'</li>
                <li class="not-outside">'.$deleted_productions_detail.'</li>
                <li class="not-outside">'.$delete.'</li>
            </ul>
        </div>';
        $this->datatables->add_column('actions', $actions, 'id');
        // $this->datatables->add_column('hide', '', 'id');

        $iDisplayStart = $this->input->post('iDisplayStart');
        $data = json_decode($this->datatables->generate());
        foreach ($data->aaData as $key => $value) {
            $data->aaData[$key][1] = ++$iDisplayStart;
        }
        echo json_encode($data);
    }

    public function add_productions_orders()
    {
        if ($this->input->post('add'))
        {
            $data = [];
            $this->form_validation->set_rules('reference_no', lang("tnh_reference_productions_orders"), 'trim|required|is_unique[tbl_productions_orders.reference_no]');
            $this->form_validation->set_rules('date', lang("date"), 'required');
            $this->form_validation->set_rules('location', lang("tnh_location"), 'required');
            if ($this->form_validation->run() == true)
            {
                $reference_no = $this->input->post('reference_no');
                $date = to_sql_date($this->input->post('date'), true);
                $note = $this->input->post('note');
                $location = $this->input->post('location');
                $productions_plan = $this->input->post('productions_plan_id');
                $total_quantity = 0;
                $count_items = 0;

                $productions_plan_reference = '';
                if (!empty($productions_plan))
                {

                    $arr_productions_plan_id = explode(',', $productions_plan);
                    $check_productions_plan = $this->manufactures_model->checkStatusProductionsPlanForOrders($arr_productions_plan_id)['reference_no'];
                    if ($check_productions_plan) {
                        $data['result'] = 0;
                        $data['message'] = lang('tnh_review').' '.$check_productions_plan;
                        echo json_encode($data);
                        die;
                    }
                    $productions_plan_reference = $this->manufactures_model->rowReferenceProductionsPlanByArrId($arr_productions_plan_id)['reference_no'];
                }

                $items_id = $this->input->post('items_id');
                foreach ($items_id as $key => $value) {
                    $items_id = $value;
                    $counter = $this->input->post('counter')[$key];
                    $type_items = 'products';
                    if ($type_items == "products") {
                        $info = $this->products_model->rowProduct($items_id);
                    }
                    if (empty($info)) {
                        continue;
                    }
                    $items_code = $info['code'];
                    $items_name = $info['name'];
                    $quantity = number_unformat($this->input->post('quantity')[$key]);
                    $note_items = $this->input->post('note_items')[$key];
                    $versions_bom = $info['versions'];
                    $versions_stage = $info['versions_stage'];

                    $items[] = [
                        'type_items' => $type_items,
                        'items_id' => $items_id,
                        'items_code' => $items_code,
                        'items_name' => $items_name,
                        'quantity' => $quantity,
                        'note_items' => $note_items,
                        'versions_bom' => $versions_bom,
                        'versions_stage' => $versions_stage
                    ];

                    $total_quantity+= $quantity;
                }

                if (empty($items)) {
                    $data['result'] = 0;
                    $data['message'] = lang('tnh_not_items');
                    echo json_encode($data);
                    die;
                }

                $count_items = count($items);
                // print_arrays($productions_plan);

                $options = [
                    'date' => $date,
                    'reference_no' => $reference_no,
                    'location_id' => $location,
                    'productions_plan_id' => $productions_plan,
                    'productions_plan_reference_no' => $productions_plan_reference,
                    'total_quantity' => $total_quantity,
                    'count_items' => $count_items,
                    'note' => $note,
                    'status' => 'un_approved',
                    'date_created' => date('Y-m-d H:i'),
                    'created_by' => get_staff_user_id(),
                ];

                $productions_orders_id = $this->manufactures_model->insertProductionsOrders($options);
                if ($productions_orders_id) {
                    if (getReference('productions_orders') == $this->input->post('reference_no')) {
                        updateReference('productions_orders');
                    }
                    foreach ($items as $key => $value) {
                        $value['productions_orders_id'] = $productions_orders_id;
                        $this->manufactures_model->insertProductionsOrdersItems($value);
                    }
                    if (!empty($arr_productions_plan_id)) {
                        foreach ($arr_productions_plan_id as $key => $value) {
                            $this->manufactures_model->updateProductionsPlan($value, ['productions_orders_id' => $productions_orders_id]);
                        }
                    }
                    //more
                    $sub = [];
                    $productions_orders_items = $this->manufactures_model->getProductionsOrdersItems($productions_orders_id);
                    foreach ($productions_orders_items as $key => $value) {
                        $type_items = $value['type_items'];
                        if ($type = "products") {
                            $item_id = $value['items_id'];
                            $quantity = $value['quantity'];

                            //sub
                            $versions_bom = $value['versions_bom'];
                            $version = $this->products_model->getBomByProductIdAndVersions($item_id, $versions_bom);
                            $elements = $this->products_model->getVersionsElementByVersionId($version['id']);
                            if (!empty($elements)) {
                                foreach ($elements as $k => $val) {
                                    $quantity_element = $val['quantity'];
                                    $total_quantity_element = $quantity * $quantity_element;
                                    $sub[] = [
                                        'productions_orders_id' => $productions_orders_id,
                                        'productions_orders_items_id' => $value['id'],
                                        'type' => 'element',
                                        'item_id' => 0,
                                        'item_code' => $val['element_name'],
                                        'item_name' => $val['element_name'],
                                        'unit_id' => 0,
                                        'quantity_single' => $quantity_element,
                                        'quantity' => $total_quantity_element,
                                        'unit_parent_id' => 0,
                                        'quantity_exchange' => 1,
                                        'quantity_primary' => $total_quantity_element
                                    ];
                                    $element_items = $this->products_model->getElementItemsByElementId($val['id']);
                                    if (!empty($element_items)) {
                                        foreach ($element_items as $i => $el) {
                                            $quantity_single = $el['quantity'];
                                            $total_quantity_item = $total_quantity_element * $quantity_single;
                                            if ($el['type'] == "semi_products" || $el['type'] == "semi_products_outside") {
                                                $info = $this->products_model->rowProduct($el['item_id']);
                                                $unit_parent_id = $info['unit_id'];
                                                $quantity_exchange = 1;
                                                $quantity_primary = $total_quantity_item;
                                            } else {
                                                $info = $this->items_model->rowMaterial($el['item_id']);
                                                $unit_id = $el['unit_id'];
                                                $unit_parent_id = $info['unit_id'];
                                                $row_exchange = $this->products_model->rowExchangeItems($el['item_id'], $unit_id);
                                                $quantity_exchange = 1;
                                                if (!empty($row_exchange)) {
                                                    $quantity_exchange = $row_exchange['number_exchange'];
                                                }
                                                $quantity_primary = $total_quantity_item/$quantity_exchange;
                                            }
                                            $sub[] = [
                                                'productions_orders_id' => $productions_orders_id,
                                                'productions_orders_items_id' => $value['id'],
                                                'type' => $el['type'],
                                                'item_id' => $el['item_id'],
                                                'item_code' => $info['code'],
                                                'item_name' => $info['name'],
                                                'unit_id' => $el['unit_id'],
                                                'quantity_single' => $quantity_single,
                                                'quantity' => $total_quantity_item,
                                                'unit_parent_id' => $unit_parent_id,
                                                'quantity_exchange' => $quantity_exchange,
                                                'quantity_primary' => $quantity_primary
                                            ];
                                        }
                                    }
                                }
                            }
                            //end sub

                            //stages
                            $versions_stage = $value['versions_stage'];
                            $vs = $this->products_model->getProductStagesByProductIdAndVersions($item_id, $versions_stage);
                            if (!empty($vs)) {
                                $product_stages = $this->products_model->getProductStagesVersions($vs['id']);
                                if (!empty($product_stages)) {
                                    foreach ($product_stages as $i => $el) {
                                        $stages[] = [
                                            'productions_orders_items_sub_id' => 0,
                                            'productions_orders_id' => $productions_orders_id,
                                            'productions_orders_items_id' => $value['id'],
                                            'stage_id' => $el['stage_id'],
                                            'machines' => $el['machines'],
                                            'number' => $el['number'],
                                            'number_hours' => $el['number_hours'],
                                        ];
                                    }
                                }
                            }
                        } else {
                            continue;
                        }
                    }

                    if (!empty($sub)) {
                        $this->manufactures_model->insertBatchProductionOrdersItemsSub($sub);
                    }
                    if (!empty($stages)) {
                        $this->manufactures_model->insertBatchProductionOrdersItemsStages($stages);
                    }

                    //update sub semi product
                    $items_sub_semi_product = $this->manufactures_model->getProductionsOrdersItemsSubBySemiProduct($productions_orders_id);
                    if (!empty($items_sub_semi_product)) {
                        foreach ($items_sub_semi_product as $key => $value) {
                            $items_id = $value['item_id'];
                            $quantity = $value['quantity'];
                            $semi_p = $this->products_model->rowProduct($items_id);
                            $version_semi = $this->products_model->getBomByProductIdAndVersions($items_id, $semi_p['versions']);
                            if (!empty($version_semi)) {
                                $elements_semi = $this->products_model->getVersionsElementByVersionId($version_semi['id']);
                                if (!empty($elements_semi)) {
                                    foreach ($elements_semi as $ksm => $vsm) {
                                        $quantity_element_semi = $quantity * $vsm['quantity'];
                                        $iup[] = [
                                            'productions_orders_id' => $productions_orders_id,
                                            'productions_orders_items_id' => $value['productions_orders_items_id'],
                                            'parent_id' => $value['id'],
                                            'type' => 'element',
                                            'item_id' => 0,
                                            'item_code' => $vsm['element_name'],
                                            'item_name' => $vsm['element_name'],
                                            'unit_id' => 0,
                                            'quantity_single' => $vsm['quantity'],
                                            'quantity' => $quantity_element_semi,
                                            'unit_parent_id' => 0,
                                            'quantity_exchange' => 1,
                                            'quantity_primary' => $quantity_element_semi
                                        ];
                                        $items_semi = $this->products_model->getElementItemsByElementId($vsm['id']);
                                        foreach ($items_semi as $nn => $vv) {
                                            $info_material = $this->items_model->rowMaterial($vv['item_id']);
                                            $unit_id_material = $vv['unit_id'];
                                            $quantity_single_sub = $vv['quantity'];
                                            $quantity_sub = $quantity_element_semi * $quantity_single_sub;

                                            $unit_parent_id = $info_material['unit_id'];
                                            $row_exchange = $this->products_model->rowExchangeItems($vv['item_id'], $unit_id_material);
                                            $quantity_exchange = 1;
                                            if (!empty($row_exchange)) {
                                                $quantity_exchange = $row_exchange['number_exchange'];
                                            }
                                            $quantity_primary = $quantity_sub/$quantity_exchange;

                                            $iup[] = [
                                                'productions_orders_id' => $productions_orders_id,
                                                'productions_orders_items_id' => $value['productions_orders_items_id'],
                                                'parent_id' => $value['id'],
                                                'type' => $vv['type'],
                                                'item_id' => $vv['item_id'],
                                                'item_code' => $info_material['code'],
                                                'item_name' => $info_material['name'],
                                                'unit_id' => $unit_id_material,
                                                'quantity_single' => $quantity_single_sub,
                                                'quantity' => $quantity_sub,
                                                'unit_parent_id' => $unit_parent_id,
                                                'quantity_exchange' => $quantity_exchange,
                                                'quantity_primary' => $quantity_primary
                                            ];
                                        }
                                    }
                                }
                            }

                            //stage semi product
                            $versions_stage = $semi_p['versions_stage'];
                            $vs = $this->products_model->getProductStagesByProductIdAndVersions($items_id, $versions_stage);
                            if (!empty($vs)) {
                                $product_stages = $this->products_model->getProductStagesVersions($vs['id']);
                                if (!empty($product_stages)) {
                                    foreach ($product_stages as $i => $el) {
                                        $stages_semi_product[] = [
                                            'productions_orders_items_sub_id' => $value['id'],
                                            'productions_orders_id' => $productions_orders_id,
                                            'productions_orders_items_id' => $value['productions_orders_items_id'],
                                            'stage_id' => $el['stage_id'],
                                            'machines' => $el['machines'],
                                            'number' => $el['number'],
                                            'number_hours' => $el['number_hours'],
                                        ];
                                    }
                                }
                            }
                        }
                    }

                    if (!empty($iup)) {
                        $this->manufactures_model->insertBatchProductionOrdersItemsSub($iup);
                    }

                    if (!empty($stages_semi_product)) {
                        $this->manufactures_model->insertBatchProductionOrdersItemsStages($stages_semi_product);
                    }
                    //end up

                    set_alert('success', lang('success'));
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
            echo json_encode($data);
            die;
        }
        $data['locations'] = $this->category_model->getCapacity();
        $data['reference_no'] = getReference('productions_orders');
        $data['tnh'] = $this->tnh;
        $data['title'] = lang('add_productions_orders');
        $data['breadcrumb'] = [array('link' => base_url('admin/manufactures/productions_orders'), 'page' => lang('productions_orders')), array('link' => '#', 'page' => lang('add_productions_orders'))];
        $this->load->view('admin/manufactures/add_productions_orders', $data);
    }

    public function edit_productions_orders($id)
    {
        $productions_orders = $this->manufactures_model->rowProductionsOrdersById($id);
        if (empty($productions_orders)) {
            set_alert('danger', lang('no_data_exists'));
            redirect($_SERVER["HTTP_REFERER"]);
            die;
        }
        if ($productions_orders['status'] == "approved") {
            set_alert('danger', lang('browsed_cannot_be_edited'));
            redirect($_SERVER["HTTP_REFERER"]);
            die;
        }
        $productions_orders_items = $this->manufactures_model->getProductionOrdersItemsAndProducts($id);
        if ($this->input->post('save'))
        {
            $data = [];
            if ($productions_orders['reference_no'] != $this->input->post('reference_no'))
            {
                $this->form_validation->set_rules('reference_no', lang("tnh_reference_productions_orders"), 'trim|required|is_unique[tbl_productions_orders.reference_no]');
            }
            $this->form_validation->set_rules('date', lang("date"), 'required');
            $this->form_validation->set_rules('location', lang("tnh_location"), 'required');
            if ($this->form_validation->run() == true)
            {
                $reference_no = $this->input->post('reference_no');
                $date = to_sql_date($this->input->post('date'), true);
                $note = $this->input->post('note');
                $location = $this->input->post('location');
                // $productions_plan = $this->input->post('productions_plan_id');
                $total_quantity = 0;
                $count_items = 0;

                // $productions_plan_reference = '';
                // if (!empty($productions_plan))
                // {

                //     $arr_productions_plan_id = explode(',', $productions_plan);
                //     $check_productions_plan = $this->manufactures_model->checkStatusProductionsPlanForOrders($arr_productions_plan_id)['reference_no'];
                //     if ($check_productions_plan) {
                //         $data['result'] = 0;
                //         $data['message'] = lang('tnh_review').' '.$check_productions_plan;
                //         echo json_encode($data);
                //         die;
                //     }
                //     $productions_plan_reference = $this->manufactures_model->rowReferenceProductionsPlanByArrId($arr_productions_plan_id)['reference_no'];
                // }

                $items_id = $this->input->post('items_id');
                foreach ($items_id as $key => $value) {
                    $items_id = $value;
                    $counter = $this->input->post('counter')[$key];
                    $type_items = 'products';
                    if ($type_items == "products") {
                        $info = $this->products_model->rowProduct($items_id);
                    }
                    if (empty($info)) {
                        continue;
                    }
                    $items_code = $info['code'];
                    $items_name = $info['name'];
                    $quantity = number_unformat($this->input->post('quantity')[$key]);
                    $note_items = $this->input->post('note_items')[$key];
                    $versions_bom = $info['versions'];
                    $versions_stage = $info['versions_stage'];

                    $items[] = [
                        'productions_orders_id' => $id,
                        'type_items' => $type_items,
                        'items_id' => $items_id,
                        'items_code' => $items_code,
                        'items_name' => $items_name,
                        'quantity' => $quantity,
                        'note_items' => $note_items,
                        'versions_bom' => $versions_bom,
                        'versions_stage' => $versions_stage,
                    ];

                    $total_quantity+= $quantity;
                }

                if (empty($items)) {
                    $data['result'] = 0;
                    $data['message'] = lang('tnh_not_items');
                    echo json_encode($data);
                    die;
                }

                $count_items = count($items);

                $options = [
                    'date' => $date,
                    'reference_no' => $reference_no,
                    'location_id' => $location,
                    // 'productions_plan_id' => $productions_plan,
                    // 'productions_plan_reference_no' => $productions_plan_reference,
                    'total_quantity' => $total_quantity,
                    'count_items' => $count_items,
                    'note' => $note,
                    'status' => 'un_approved',
                    'date_updated' => date('Y-m-d H:i'),
                    'updated_by' => get_staff_user_id(),
                ];

                $up = $this->manufactures_model->updateProductionsOrders($id, $options);
                if ($up) {
                    $this->manufactures_model->deleteProductionsOrdersItems($id);
                    $this->manufactures_model->insertBatchProductionsOrdersItems($items);

                    $this->manufactures_model->deleteProductionsOrdersItemsSub($id);
                    $this->manufactures_model->deleteProductionsOrdersItemsStages($id);
                    //more
                    $sub = [];
                    $productions_orders_items = $this->manufactures_model->getProductionsOrdersItems($id);
                    foreach ($productions_orders_items as $key => $value) {
                        $type_items = $value['type_items'];
                        if ($type = "products") {
                            $item_id = $value['items_id'];
                            $quantity = $value['quantity'];

                            //sub
                            $versions_bom = $value['versions_bom'];
                            $version = $this->products_model->getBomByProductIdAndVersions($item_id, $versions_bom);
                            $elements = $this->products_model->getVersionsElementByVersionId($version['id']);
                            if (!empty($elements)) {
                                foreach ($elements as $k => $val) {
                                    $quantity_element = $val['quantity'];
                                    $total_quantity_element = $quantity * $quantity_element;
                                    $sub[] = [
                                        'productions_orders_id' => $id,
                                        'productions_orders_items_id' => $value['id'],
                                        'type' => 'element',
                                        'item_id' => 0,
                                        'item_code' => $val['element_name'],
                                        'item_name' => $val['element_name'],
                                        'unit_id' => 0,
                                        'quantity_single' => $quantity_element,
                                        'quantity' => $total_quantity_element,
                                        'unit_parent_id' => 0,
                                        'quantity_exchange' => 1,
                                        'quantity_primary' => $total_quantity_element
                                    ];
                                    $element_items = $this->products_model->getElementItemsByElementId($val['id']);
                                    if (!empty($element_items)) {
                                        foreach ($element_items as $i => $el) {
                                            $quantity_single = $el['quantity'];
                                            $total_quantity_item = $total_quantity_element * $quantity_single;
                                            if ($el['type'] == "semi_products" || $el['type'] == "semi_products_outside") {
                                                $info = $this->products_model->rowProduct($el['item_id']);
                                                $unit_parent_id = $info['unit_id'];
                                                $quantity_exchange = 1;
                                                $quantity_primary = $total_quantity_item;
                                            } else {
                                                $info = $this->items_model->rowMaterial($el['item_id']);
                                                $unit_id = $el['unit_id'];
                                                $unit_parent_id = $info['unit_id'];
                                                $row_exchange = $this->products_model->rowExchangeItems($el['item_id'], $unit_id);
                                                $quantity_exchange = 1;
                                                if (!empty($row_exchange)) {
                                                    $quantity_exchange = $row_exchange['number_exchange'];
                                                }
                                                $quantity_primary = $total_quantity_item/$quantity_exchange;
                                            }
                                            $sub[] = [
                                                'productions_orders_id' => $id,
                                                'productions_orders_items_id' => $value['id'],
                                                'type' => $el['type'],
                                                'item_id' => $el['item_id'],
                                                'item_code' => $info['code'],
                                                'item_name' => $info['name'],
                                                'unit_id' => $el['unit_id'],
                                                'quantity_single' => $quantity_single,
                                                'quantity' => $total_quantity_item,
                                                'unit_parent_id' => $unit_parent_id,
                                                'quantity_exchange' => $quantity_exchange,
                                                'quantity_primary' => $quantity_primary
                                            ];
                                        }
                                    }
                                }
                            }
                            //end sub

                            //stages
                            $versions_stage = $value['versions_stage'];
                            $vs = $this->products_model->getProductStagesByProductIdAndVersions($item_id, $versions_stage);
                            if (!empty($vs)) {
                                $product_stages = $this->products_model->getProductStagesVersions($vs['id']);
                                if (!empty($product_stages)) {
                                    foreach ($product_stages as $i => $el) {
                                        $stages[] = [
                                            'productions_orders_id' => $id,
                                            'productions_orders_items_id' => $value['id'],
                                            'stage_id' => $el['stage_id'],
                                            'machines' => $el['machines'],
                                            'number' => $el['number'],
                                            'number_hours' => $el['number_hours'],
                                        ];
                                    }
                                }
                            }
                        } else {
                            continue;
                        }
                    }
                    if (!empty($sub)) {
                        $this->manufactures_model->insertBatchProductionOrdersItemsSub($sub);
                    }
                    if (!empty($stages)) {
                        $this->manufactures_model->insertBatchProductionOrdersItemsStages($stages);
                    }

                    //update sub semi product
                    $items_sub_semi_product = $this->manufactures_model->getProductionsOrdersItemsSubBySemiProduct($id);
                    if (!empty($items_sub_semi_product)) {
                        foreach ($items_sub_semi_product as $key => $value) {
                            $items_id = $value['item_id'];
                            $quantity = $value['quantity'];
                            $semi_p = $this->products_model->rowProduct($items_id);
                            $version_semi = $this->products_model->getBomByProductIdAndVersions($items_id, $semi_p['versions']);
                            if (!empty($version_semi)) {
                                $elements_semi = $this->products_model->getVersionsElementByVersionId($version_semi['id']);
                                if (!empty($elements_semi)) {
                                    foreach ($elements_semi as $ksm => $vsm) {
                                        $quantity_element_semi = $quantity * $vsm['quantity'];
                                        $iup[] = [
                                            'productions_orders_id' => $id,
                                            'productions_orders_items_id' => $value['productions_orders_items_id'],
                                            'parent_id' => $value['id'],
                                            'type' => 'element',
                                            'item_id' => 0,
                                            'item_code' => $vsm['element_name'],
                                            'item_name' => $vsm['element_name'],
                                            'unit_id' => 0,
                                            'quantity_single' => $vsm['quantity'],
                                            'quantity' => $quantity_element_semi,
                                            'unit_parent_id' => 0,
                                            'quantity_exchange' => 1,
                                            'quantity_primary' => $quantity_element_semi
                                        ];
                                        $items_semi = $this->products_model->getElementItemsByElementId($vsm['id']);
                                        foreach ($items_semi as $nn => $vv) {
                                            $info_material = $this->items_model->rowMaterial($vv['item_id']);
                                            $unit_id_material = $vv['unit_id'];
                                            $quantity_single_sub = $vv['quantity'];
                                            $quantity_sub = $quantity_element_semi * $quantity_single_sub;

                                            $unit_parent_id = $info_material['unit_id'];
                                            $row_exchange = $this->products_model->rowExchangeItems($vv['item_id'], $unit_id_material);
                                            $quantity_exchange = 1;
                                            if (!empty($row_exchange)) {
                                                $quantity_exchange = $row_exchange['number_exchange'];
                                            }
                                            $quantity_primary = $quantity_sub/$quantity_exchange;
                                            $iup[] = [
                                                'productions_orders_id' => $id,
                                                'productions_orders_items_id' => $value['productions_orders_items_id'],
                                                'parent_id' => $value['id'],
                                                'type' => $vv['type'],
                                                'item_id' => $vv['item_id'],
                                                'item_code' => $info_material['code'],
                                                'item_name' => $info_material['name'],
                                                'unit_id' => $unit_id_material,
                                                'quantity_single' => $quantity_single_sub,
                                                'quantity' => $quantity_sub,
                                                'unit_parent_id' => $unit_parent_id,
                                                'quantity_exchange' => $quantity_exchange,
                                                'quantity_primary' => $quantity_primary
                                            ];
                                        }
                                    }
                                }
                            }

                            //stage semi product
                            $versions_stage = $semi_p['versions_stage'];
                            $vs = $this->products_model->getProductStagesByProductIdAndVersions($items_id, $versions_stage);
                            if (!empty($vs)) {
                                $product_stages = $this->products_model->getProductStagesVersions($vs['id']);
                                if (!empty($product_stages)) {
                                    foreach ($product_stages as $i => $el) {
                                        $stages_semi_product[] = [
                                            'productions_orders_items_sub_id' => $value['id'],
                                            'productions_orders_id' => $id,
                                            'productions_orders_items_id' => $value['productions_orders_items_id'],
                                            'stage_id' => $el['stage_id'],
                                            'machines' => $el['machines'],
                                            'number' => $el['number'],
                                            'number_hours' => $el['number_hours'],
                                        ];
                                    }
                                }
                            }
                        }
                    }

                    if (!empty($iup)) {
                        $this->manufactures_model->insertBatchProductionOrdersItemsSub($iup);
                    }

                    if (!empty($stages_semi_product)) {
                        $this->manufactures_model->insertBatchProductionOrdersItemsStages($stages_semi_product);
                    }

                    set_alert('success', lang('success'));
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
            echo json_encode($data);
            die;
        }

        $data['productions_orders'] = $productions_orders;
        $data['productions_orders_items'] = $productions_orders_items;
        $data['locations'] = $this->category_model->getCapacity();
        $data['tnh'] = $this->tnh;
        $data['title'] = lang('edit_productions_orders');
        $data['breadcrumb'] = [array('link' => base_url('admin/manufactures/productions_orders'), 'page' => lang('productions_orders')), array('link' => '#', 'page' => lang('edit_productions_orders'))];
        $this->load->view('admin/manufactures/edit_productions_orders', $data);
    }

    public function view_productions_orders($id) {
        $productions_orders = $this->manufactures_model->rowProductionsOrdersById($id);
        $location = $this->category_model->rowCapacity($productions_orders['location_id']);
        $productions_orders_items = $this->manufactures_model->getProductionOrdersItemsAndProducts($id);
        $subs = $this->manufactures_model->getProductionsOrdersItemsSubTotalView($id);

        if (!empty($productions_orders['updated_by']))
        {
            $data['updated_by'] = get_staff_full_name($productions_orders['updated_by']);
        } else {
            $data['updated_by'] = '';
        }
        if ($productions_orders['user_status']) {
            $data['user_status'] = get_staff_full_name($productions_orders['user_status']);
        } else {
            $data['user_status'] = '';
        }
        $data['created_by'] = get_staff_full_name($productions_orders['created_by']);
        $data['productions_orders'] = $productions_orders;
        $data['productions_orders_items'] = $productions_orders_items;
        $data['subs'] = $subs;
        $data['location'] = $location;
        $this->load->view('admin/manufactures/view_productions_orders', $data);
    }

    function delete_productions_orders($id)
    {
        $data = [];
        if ($id) {
            $productions_orders = $this->manufactures_model->rowProductionsOrdersById($id);
            if ($productions_orders['status'] == "un_approved") {
                if ($this->manufactures_model->deleteProductionsOrders($id)) {
                    if (!empty($productions_orders['productions_plan_id'])) {
                        $this->manufactures_model->updateProductionsPlanByOrders($id, ['productions_orders_id' => 0]);
                    }
                    $this->manufactures_model->deleteProductionsOrdersItems($id);
                    $this->manufactures_model->deleteProductionsOrdersItemsSub($id);
                    $this->manufactures_model->deleteProductionsOrdersItemsStages($id);
                    $data['result'] = 1;
                    $data['message'] = lang('success');
                } else {
                    $data['result'] = 0;
                    $data['message'] = lang('fail');
                }
            } else {
                $data['result'] = 0;
                $data['message'] = lang('browsed_cannot_be_deleted');
            }
        } else {
            $data['result'] = 0;
            $data['message'] = lang('fail');
        }
        echo json_encode($data);
    }

    function delete_productions_orders_detail($id)
    {
        $data = [];
        if ($id) {
            $productions_orders = $this->manufactures_model->rowProductionsOrdersById($id);
            if ($productions_orders['status'] == "approved") {
                $check = $this->manufactures_model->checkCoditionDeleteDetail($id);
                if (!empty($check)) {
                    $data['result'] = 0;
                    $data['message'] = $check;
                    echo json_encode($data); die;
                }
                if ($this->manufactures_model->deleteProductionsOdersDetail($id)) {
                    $this->manufactures_model->updateProductionsOrders($id, ['status_details' => 0]);
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
        } else {
            $data['result'] = 0;
            $data['message'] = lang('fail');
        }
        echo json_encode($data);
    }

    function agreeProductionsOrders()
    {
        $data = [];
        if ($this->input->get())
        {
            $productions_orders_id = $this->input->get('productions_orders_id');
            $status = $this->input->get('status');
            $productions_orders = $this->manufactures_model->rowProductionsOrdersById($productions_orders_id);
            if ($productions_orders['status_details']) {
                $data['result'] = 0;
                $data['message'] = lang('tnh_created_an_order_details_not_unapproved');
                echo json_encode($data); die;
            }
            $date = date('Y-m-d H:i');
            $user_id = get_staff_user_id();
            if ($productions_orders['status'] == $status) {
                $data['result'] = 0;
                $data['message'] = lang('tnh_please_referesh_table');
                echo json_encode($data); die;
            }
            $up = $this->manufactures_model->updateProductionsOrders($productions_orders_id, [
                'status' => $status,
                'date_status' => $date,
                'user_status' => $user_id
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

    function refereshReferenceProductionsOrders()
    {
        $data = [];
        if ($this->input->get('referesh'))
        {
            $reference_no = getReference('productions_orders');
            if ($this->manufactures_model->checkExistProductionsOrdersByReferenceNo($reference_no)) {
                // SELECT max(right(tbl_productions_orders.reference_no, char_length(tbl_productions_orders.reference_no)-locate('-',tbl_productions_orders.reference_no) - 8) + 0) FROM `tbl_productions_orders` WHERE 1
                // $this->db->select('MAX(tbl_productions_orders.reference_no) as reference_no', false);
                // $this->db->select("MAX(right(tbl_productions_orders.reference_no, char_length(tbl_productions_orders.reference_no) - locate('-', tbl_productions_orders.reference_no) - 8) + 0) as reference_no", false);
                $ct = countReferenceMinus('productions_orders');
                $this->db->select("MAX(right(tbl_productions_orders.reference_no, char_length(tbl_productions_orders.reference_no) - $ct) + 0) as reference_no", false);
                $this->db->from('tbl_productions_orders');
                $rs = $this->db->get()->row_array();

                $max = $rs['reference_no'];
                $max++;
                // $max = subReference($max);
                updateReferenceNormal('productions_orders', $max);
                $reference_no = getReference('productions_orders');
            }
            $data['reference_no'] = $reference_no;
            $data['message'] = lang('tnh_referesh_success');
        }
        echo json_encode($data);
    }

    function searchProductionsPlanForOrders($id = false)
    {
        $data = [];
        $term = $this->input->get('term', TRUE);
        // $limit = 100;
        $limit = get_option('select2_limit');
        $data['results'] = $this->manufactures_model->searchProductionsPlanForOrders($term, $limit);
        if ($id) {
            // $product = $this->products_model->getProductId($id);
            // $data['row'] = ['id' => $product['id'], 'text' => $product['code']];
        }
        echo json_encode($data);
    }

    function getItemsProductionsPlan()
    {
        $data = [];
        if ($this->input->get()) {
            $productions_plan_id = $this->input->get('productions_plan_id');
            $data['result'] = $this->manufactures_model->getProductionsPlanForProductionsOrders(explode(',', $productions_plan_id));
        }
        echo json_encode($data);
    }
    //
    function created_productions_detail($id)
    {
        $productions_orders = $this->manufactures_model->rowProductionsOrdersById($id);
        $items = $this->manufactures_model->getProductionsORdersItemsForCreated($id);
        if ($productions_orders['status'] != "approved") {
            refererModel(lang('tnh_please_approved'));
        }
        if ($productions_orders['status_details']) {
            refererModel(lang('tnh_created_an_order_details'));
        }
        if ($this->input->post('save')) {
            $data = [];
            $this->form_validation->set_rules('deadline[]', lang("deadline"), 'required');
            $this->form_validation->set_rules('departments[]', lang("departments"), 'required');
            $details = [];
            if ($this->form_validation->run() == true)
            {
                $flag = false;
                foreach ($items as $key => $value) {
                    $deadline = $this->input->post('deadline')[$key] ? to_sql_date($this->input->post('deadline')[$key]) : '0000-00-00';
                    $departments = $this->input->post('departments')[$key];

                    $details = [
                        'reference_no' => getReference('productions_orders_details'),
                        'productions_orders_id' => $id,
                        'productions_orders_item_id' => $value['id'],
                        'deadline' => $deadline,
                        'departments' => $departments,
                        'status' => 'un_produced',
                        'created_by' => get_staff_user_id(),
                        'date_created' => date('Y-m-d H:i'),
                    ];

                    $pod_id = $this->manufactures_model->insertProductionsOrdersDetails($details);
                    if ($pod_id) {
                        $flag = true;
                        updateReference('productions_orders_details');
                    }
                }
                if ($flag == true) {
                    $this->manufactures_model->updateProductionsOrders($id, ['status_details' => 1]);
                    $data['result'] = 1;
                    $data['message'] = lang('success');
                }
            } else {
                $data['result'] = 0;
                $data['message'] = validation_errors();
            }
            echo json_encode($data);
            die;
        } else {
            $data['id'] = $id;
            $data['items'] = $items;
            $data['productions_orders'] = $productions_orders;
            $data['departments'] = $this->departments_model->getDepartments();
            $this->load->view('admin/manufactures/created_productions_detail', $data);
        }
    }

    //productions details
    public function order_production_details()
    {
        $data['tnh'] = $this->tnh;
        $data['title'] = lang('tnh_productions_order_details');
        $this->load->view('admin/manufactures/order_production_details', $data);
    }

    public function getProductionsOrdersDetails()
    {
        $this->datatables
            ->select("
                tbl_productions_orders_details.id as id,
                0 as number_records,
                tbl_productions_orders.reference_no as reference_no_order,
                tbl_productions_orders_details.reference_no as reference_no,
                tbl_productions_orders_details.deadline as deadline,
                tbldepartments.name as department_name,
                tbl_productions_orders_items.items_code as items_code,
                tbl_productions_orders_items.items_name as items_name,
                tbl_productions_orders_items.quantity as quantity,
                0 as quantity_finished,
                0 as precent_finished,
                tbl_productions_orders_details.status as status
                ", false)
            ->from('tbl_productions_orders_details')
            ->join('tbl_productions_orders_items', 'tbl_productions_orders_items.id = tbl_productions_orders_details.productions_orders_item_id', 'left')
            ->join('tbl_productions_orders', 'tbl_productions_orders.id = tbl_productions_orders_items.productions_orders_id', 'left')
            ->join('tbldepartments', 'tbldepartments.departmentid = tbl_productions_orders_details.departments', 'left');

        $detail = '<a class="" title="'.lang('tnh_detail').'" target="_blank" href="'.base_url('admin/manufactures/detail_productions/$1').'"><i class="fa fa-file-text-o width-icon-actions" ></i> '.lang('tnh_detail').'</a>';

        $delete = '<a type="button" class="po" title="'.lang('delete').'" data-container="body" data-html="true" data-toggle="popover" data-placement="left" data-content="
            <button href=\''.base_url('admin/manufactures/delete_productions_orders/$1').'\' class=\'btn btn-danger po-delete-json\'>'.lang('delete').'</button>
            <button class=\'btn btn-default po-close\'>'.lang('close').'</button>
        "><i class="fa fa-remove width-icon-actions"></i> '.lang('delete').'</a>';

        $actions = '
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle nav-link" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
            '.lang('actions').'
            <span class="caret"></span>
            </button>
            <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdownMenu1">
                <li>'.$detail.'</li>
            </ul>
        </div>';

        $this->datatables->add_column('actions', $actions, 'id');
        $iDisplayStart = $this->input->post('iDisplayStart');
        $data = json_decode($this->datatables->generate());
        foreach ($data->aaData as $key => $value) {
            $data->aaData[$key][1] = ++$iDisplayStart;
        }

        echo json_encode($data);
    }

    public function detail_productions($id)
    {
        $production_detail = $this->manufactures_model->rowProductionsOrdersByDetail($id);
        $production_stage_parent = $this->manufactures_model->getProductionsOrdersItemsStagesOfProduct($production_detail['productions_orders_item_id']);
        $semi_products = $this->manufactures_model->getProductionsOrdersItemsSubByDetail($production_detail['productions_orders_item_id']);
        $data['production_detail'] = $production_detail;
        $data['production_stage_parent'] = $production_stage_parent;
        $data['semi_products'] = $semi_products;
        $data['id'] = $id;
        $data['tnh'] = $this->tnh;
        $data['employees'] = $this->manufactures_model->getAllStaff();
        $data['breadcrumb'] = [array('link' => base_url('admin/manufactures/order_production_details'), 'page' => lang('tnh_productions_order_details')), array('link' => '#', 'page' => lang('tnh_detail_productions'))];
        $data['title'] = lang('tnh_detail_productions');
        $this->load->view('admin/manufactures/detail_productions', $data);
    }

    public function getMaterialDetailProductions($productions_orders_items_id)
    {
        $this->datatables
            ->select("
                0 as number_records,
                CONCAT(tbl_productions_orders_items_sub.item_code, '__', tbl_productions_orders_items_sub.type) as item_code,
                tbl_productions_orders_items_sub.item_name as item_name,
                tblunits.unit as unit_name,
                SUM(tbl_productions_orders_items_sub.quantity) as quantity,
                SUM(tbl_productions_orders_items_sub.quantity_primary) as quantity_exchange,
                0 as quantity_export,
                SUM(tbl_productions_orders_items_sub.quantity_primary) as quantity_missing,
            ", false)
            ->from('tbl_productions_orders_items_sub')
            ->join('tblunits', 'tblunits.unitid = tbl_productions_orders_items_sub.unit_id', 'left');

        $this->datatables->group_by('tbl_productions_orders_items_sub.item_id, tbl_productions_orders_items_sub.unit_id');

        $this->datatables->where("(tbl_productions_orders_items_sub.type = 'materials' OR  tbl_productions_orders_items_sub.type = 'semi_products_outside')");
        $this->datatables->where('tbl_productions_orders_items_sub.productions_orders_items_id', $productions_orders_items_id);

        $iDisplayStart = $this->input->post('iDisplayStart');
        $data = json_decode($this->datatables->generate());
        foreach ($data->aaData as $key => $value) {
            $data->aaData[$key][0] = ++$iDisplayStart;
        }
        echo json_encode($data);
    }

    public function export_supplies($id)
    {
        $production_detail = $this->manufactures_model->rowProductionsOrdersByDetail($id);
        if ($this->input->post('save'))
        {
            $data = [];
            $this->form_validation->set_rules('date', lang("date"), 'required');
            $this->form_validation->set_rules('export_name', lang("tnh_export_name"), 'required');
            if ($this->form_validation->run() == true)
            {
                $date = to_sql_date($this->input->post('date'), true);
                $export_name = $this->input->post('export_name');
                $note = $this->input->post('note');
                $items = $this->input->post('item_id');
                $total_quantity = 0;
                $total_quantity_exchange = 0;
                if (!empty($items)) {
                    foreach ($items as $key => $value) {
                        $unit_id = $this->input->post('unit_id')[$key];
                        $unit_parent_id = $this->input->post('unit_parent_id')[$key];
                        $number_exchange = $this->input->post('quantity_exchange')[$key];
                        $quantity_export = number_unformat($this->input->post('quantity_export')[$key]);
                        if ($quantity_export > 0) {
                            $type_item = $this->input->post('type_item')[$key];
                            if ($type_item == "semi_products_outside") {
                                $info_item = $this->products_model->rowProduct($value);
                            } else if ($type_item == "materials") {
                                $info_item = $this->items_model->rowMaterial($value);
                            }
                            if (empty($info_item)) continue;
                            $quantity_exchange = $quantity_export/$number_exchange;
                            $exporting_items[] = [
                                'type_item' => $type_item,
                                'item_id' => $value,
                                'item_code' => $info_item['code'],
                                'item_name' => $info_item['name'],
                                'unit_id' => $unit_id,
                                'quantity_export' => $quantity_export,
                                'unit_parent_id' => $unit_parent_id,
                                'number_exchange' => $number_exchange,
                                'quantity_exchange' => $quantity_exchange,
                            ];
                            $total_quantity+= $quantity_export;
                            $total_quantity_exchange+= $quantity_exchange;
                        }
                    }
                }
                if (empty($exporting_items)) {
                    $data['result'] = 0;
                    $data['message'] = lang('tnh_there_is_not_export_quantity_greater_than_zero');
                    echo json_encode($data); die;
                }
                $count_items = count($exporting_items);

                $fields = [
                    'productions_orders_details_id' => $id,
                    'reference_no' => getReference('suggest_exporting'),
                    'date' => $date,
                    'export_name' => $export_name,
                    'note' => $note,
                    'status' => 'un_approved',
                    'total_quantity' => $total_quantity,
                    'count_items' => $count_items,
                    'total_quantity_exchange' => $total_quantity_exchange,
                    'created_by' => get_staff_user_id(),
                    'date_created' => date('Y-m-d H:i:s'),
                    'type' => '1'
                ];
                $suggest_exporting_id = $this->manufactures_model->insertSuggestExporting($fields);
                if ($suggest_exporting_id) {
                    updateReference('suggest_exporting');
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
            $materials = $this->manufactures_model->getMaterialProductionsForExportSupplies($production_detail['productions_orders_item_id']);
            $data['production_detail'] = $production_detail;
            $data['materials'] = $materials;
            $data['id'] = $id;
            $this->load->view('admin/manufactures/export_supplies', $data);
        }
    }

    public function edit_suggest_exporting($id)
    {
        $suggest_exporting = $this->manufactures_model->rowSuggestExporting($id);
        if ($suggest_exporting['status'] != "un_approved") {
            refererModel(lang('browsed_cannot_be_edited'));
        }
        if ($this->input->post('save'))
        {
            $data = [];
            $this->form_validation->set_rules('date', lang("date"), 'required');
            $this->form_validation->set_rules('export_name', lang("tnh_export_name"), 'required');
            if ($this->form_validation->run() == true)
            {
                $date = to_sql_date($this->input->post('date'), true);
                $export_name = $this->input->post('export_name');
                $note = $this->input->post('note');
                $exporting_items_id = $this->input->post('exporting_items_id');
                $total_quantity = 0;
                $total_quantity_exchange = 0;
                $arr_not_delete = [];
                if (!empty($exporting_items_id)) {
                    foreach ($exporting_items_id as $key => $value) {
                        $number_exchange = $this->input->post('quantity_exchange')[$key];
                        $quantity_export = number_unformat($this->input->post('quantity_export')[$key]);
                        if ($quantity_export > 0) {
                            $quantity_exchange = $quantity_export/$number_exchange;
                            $exporting_items[] = [
                                'id' => $value,
                                'quantity_export' => $quantity_export,
                                'number_exchange' => $number_exchange,
                                'quantity_exchange' => $quantity_exchange,
                            ];
                            $total_quantity+= $quantity_export;
                            $total_quantity_exchange+= $quantity_exchange;
                            array_push($arr_not_delete, $value);
                        }
                    }
                }
                if (empty($exporting_items)) {
                    $data['result'] = 0;
                    $data['message'] = lang('tnh_there_is_not_export_quantity_greater_than_zero');
                    echo json_encode($data); die;
                }
                $count_items = count($exporting_items);

                $fields = [
                    'date' => $date,
                    'export_name' => $export_name,
                    'note' => $note,
                    'total_quantity' => $total_quantity,
                    'count_items' => $count_items,
                    'total_quantity_exchange' => $total_quantity_exchange,
                    'updated_by' => get_staff_user_id(),
                    'date_updated' => date('Y-m-d H:i:s'),
                ];
                $up = $this->manufactures_model->updateSuggestExportingById($id, $fields);
                if ($up) {
                    $delete = $this->manufactures_model->getSuggestExportingItemsByNotArrId($arr_not_delete, $id);
                    if (!empty($delete)) {
                        foreach ($delete as $key => $value) {
                            $this->manufactures_model->deleteSuggestExportingItemsById($value['id']);
                        }
                    }
                    if (!empty($exporting_items)) {
                        $this->manufactures_model->updateBatchSuggestExportingItemsById($exporting_items);
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
            $data['suggest_exporting'] = $suggest_exporting;
            $data['exporting_items'] = $this->manufactures_model->getSuggestExportingItemsView($id);
            $data['id'] = $id;
            $this->load->view('admin/manufactures/edit_export_supplies', $data);
        }
    }

    public function getSuggestExportingDetail($productions_orders_details_id)
    {
        $this->datatables
            ->select("
                0 as number_records,
                tbl_suggest_exporting.id as id,
                tbl_suggest_exporting.date as date,
                tbl_suggest_exporting.reference_no as reference_no,
                tbl_suggest_exporting.export_name as export_name,
                tbl_suggest_exporting.note as note,
                CONCAT(tblstaff.firstname, ' ', tblstaff.lastname,'') as created_by,
                tbl_suggest_exporting.status as status,
                tbl_suggest_exporting.type as type
            ", false)
            ->from('tbl_suggest_exporting')
            ->join('tblstaff', 'tblstaff.staffid = tbl_suggest_exporting.created_by', 'left');

        $this->datatables->where('tbl_suggest_exporting.productions_orders_details_id', $productions_orders_details_id);
        $this->datatables->where('(tbl_suggest_exporting.type = 1 OR tbl_suggest_exporting.type = 3)');

        $view = '<a class="tnh-modal" title="'.lang('view').'" data-tnh="modal" data-toggle="modal" data-target="#myModal" href="'.base_url('admin/manufactures/view_suggest_exporting/$1').'"><i class="fa fa-file-text-o width-icon-actions"></i> '.lang('view').'</a>';

        $edit = '<a class="tnh-modal tnh-edit" title="'.lang('edit').'" data-tnh="modal" data-toggle="modal" data-target="#myModal" href="'.base_url('admin/manufactures/edit_suggest_exporting/$1').'"><i class="fa fa-edit width-icon-actions"></i> '.lang('edit').'</a>';

        $delete = '<a type="button" class="po tnh-delete" title="'.lang('delete').'" data-container="body" data-html="true" data-toggle="popover" data-placement="left" data-content="
            <button href=\''.base_url('admin/manufactures/delete_suggest_exporting/$1').'\' class=\'btn btn-danger po-delete-json\'>'.lang('delete').'</button>
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

    public function view_suggest_exporting($id)
    {
        $suggest_exporting = $this->manufactures_model->rowSuggestExporting($id);
        $items = $this->manufactures_model->getSuggestExportingItemsView($id);
        $data['items'] = $items;
        $data['created_by'] = get_staff_full_name($suggest_exporting['created_by']);
        if ($suggest_exporting['updated_by']) {
            $data['updated_by'] = get_staff_full_name($suggest_exporting['updated_by']);
        }
        $data['suggest_exporting'] = $suggest_exporting;
        $data['id'] = $id;
        $this->load->view('admin/manufactures/view_suggest_exporting', $data);
    }

    public function delete_suggest_exporting($id, $type = 1)
    {
        $data = [];
        if ($id) {
            $suggest_exporting = $this->manufactures_model->rowSuggestExporting($id);
            if ($suggest_exporting['status'] == "un_approved") {
                if ($suggest_exporting['type'] != $type) {
                    $data['result'] = 0;
                    $data['message'] = lang('fail');
                    echo json_encode($data); die;
                }

                if ($this->manufactures_model->deleteSuggestExportingById($id)) {
                    $this->manufactures_model->deleteSuggestExportingItems($id);
                    $data['result'] = 1;
                    $data['type'] = "export";
                    $data['table'] = 'dtSuggest';
                    $data['message'] = lang('success');
                } else {
                    $data['result'] = 0;
                    $data['message'] = lang('fail');
                }
            } else {
                $data['result'] = 0;
                $data['message'] = lang('browsed_cannot_be_deleted');
            }
        } else {
            $data['result'] = 0;
            $data['message'] = lang('fail');
        }
        echo json_encode($data);
    }

    public function list_suggest_exporting()
    {
        $data['tnh'] = $this->tnh;
        $data['title'] = lang('list_suggest_exporting');
        $this->load->view('admin/manufactures/list_suggest_exporting', $data);
    }

    public function getSuggestExporting()
    {
        $this->datatables
            ->select("
                0 as number_records,
                tbl_suggest_exporting.id as id,
                tbl_suggest_exporting.date as date,
                tbl_suggest_exporting.reference_no as reference_no,
                tbl_productions_orders_details.reference_no as reference_production_detail,
                tbl_suggest_exporting.export_name as export_name,
                tbl_suggest_exporting.note as note,
                CONCAT(tblstaff.firstname, ' ', tblstaff.lastname,'') as created_by,
                tbl_suggest_exporting.status as status,
                CONCAT(staff_status.firstname, ' ', staff_status.lastname,'') as user_status,
                tbl_suggest_exporting.status_stock as status_stock,
                tbl_suggest_exporting.type as type
            ", false)
            ->from('tbl_suggest_exporting')
            ->join('tbl_productions_orders_details', 'tbl_productions_orders_details.id = tbl_suggest_exporting.productions_orders_details_id', 'inner')
            ->join('tblstaff', 'tblstaff.staffid = tbl_suggest_exporting.created_by', 'left')
            ->join('tblstaff staff_status', 'staff_status.staffid = tbl_suggest_exporting.user_status', 'left');

        $this->datatables->where('(tbl_suggest_exporting.type = 1 OR tbl_suggest_exporting.type = 3)');

        $view = '<a class="tnh-modal" title="'.lang('view').'" data-tnh="modal" data-toggle="modal" data-target="#myModal" href="'.base_url('admin/manufactures/view_suggest_exporting/$1').'"><i class="fa fa-file-text-o width-icon-actions"></i> '.lang('view').'</a>';

        // $stock = '<a class="tnh-stock" title="'.lang('view').'" href="'.base_url('admin/manufactures/convert_stock/$1').'"><i class="fa fa-exchange width-icon-actions"></i> '.lang('tnh_convert_to_export_stock').'</a>';

        $stock = '<a class="tnh-modal tnh-stock" title="'.lang('view').'" data-tnh="modal" data-toggle="modal" data-target="#myModal" href="'.base_url('admin/manufactures/convert_stock/$1').'"><i class="fa fa-exchange width-icon-actions"></i> '.lang('tnh_convert_to_export_stock').'</a>';

        $edit = '<a class="tnh-edit" title="'.lang('edit').'" href="'.base_url('admin/manufactures/edit_exporting_production/$1').'"><i class="fa fa-edit width-icon-actions"></i> '.lang('edit').'</a>';

        $delete = '<a type="button" class="po tnh-delete" title="'.lang('delete').'" data-container="body" data-html="true" data-toggle="popover" data-placement="left" data-content="
            <button href=\''.base_url('admin/manufactures/delete_suggest_exporting/$1/3').'\' class=\'btn btn-danger po-delete-json\'>'.lang('delete').'</button>
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
                <li class="">'.$stock.'</li>
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

    public function agreeSuggestExporting()
    {
        $data = [];
        if ($this->input->get())
        {
            $suggest_exporting_id = $this->input->get('suggest_exporting_id');
            $status = $this->input->get('status');
            $suggest_exporting = $this->manufactures_model->rowSuggestExporting($suggest_exporting_id);
            $date = date('Y-m-d H:i');
            $user_id = get_staff_user_id();
            if ($suggest_exporting['status'] == $status) {
                $data['result'] = 0;
                $data['message'] = lang('tnh_please_referesh_table');
                echo json_encode($data); die;
            }
            if (!empty($suggest_exporting['status_stock'])) {
                $data['result'] = 0;
                $data['message'] = lang('tnh_created_convert_stock');
                echo json_encode($data); die;
            }
            $up = $this->manufactures_model->updateSuggestExportingById($suggest_exporting_id, [
                'status' => $status,
                'date_status' => $date,
                'user_status' => $user_id
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

    public function convert_stock($id)
    {
        $suggest_exporting = $this->manufactures_model->rowSuggestExporting($id);
        $suggest_exporting_items = $this->manufactures_model->getSuggestExportingItemsView($id);
        if ($this->input->post())
        {
            $data = [];
            if ($suggest_exporting['status'] != 'approved') {
                $data['result'] = 0;
                $data['message'] = lang('tnh_please_approved');
                echo json_encode($data); die;
            }
            if (!empty($suggest_exporting['status_stock'])) {
                $data['result'] = 0;
                $data['message'] = lang('tnh_created_convert_stock');
                echo json_encode($data); die;
            }
            $this->form_validation->set_rules('warehouses', lang("tnh_warehouses"), 'required');
            $this->form_validation->set_rules('locations[]', lang("tnh_location_warehouse"), 'required');
            if ($this->form_validation->run() == true)
            {
                $date = date('Y-m-d H:i:s');
                $user_id = get_staff_user_id();
                $warehouses = $this->input->post('warehouses');
                $counter = $this->input->post('counter');
                foreach ($counter as $key => $value) {
                    $suggest_exporting_items_id = $this->input->post('suggest_exporting_items_id')[$value];
                    $location = $this->input->post('locations')[$value];
                    $items[] = [
                        'id' => $suggest_exporting_items_id,
                        'location_id' => $location,
                    ];
                }

                $up = $this->manufactures_model->updateSuggestExportingById($id, [
                    'warehouse_id' => $warehouses,
                    'reference_stock' => getReference('stock'),
                    'status_stock' => 'un_approved_stock',
                    'date_convert_stock' => $date,
                    'convert_stock_by' => $user_id
                ]);
                if ($up) {
                    updateReference('stock');
                    if (!empty($items)) {
                        $this->manufactures_model->updateBatchSuggestExportingItemsById($items);
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
            echo json_encode($data);
        } else {
            $data['warehouses'] = $this->stock_model->getWarehouses();
            $data['suggest_exporting'] = $suggest_exporting;
            $data['suggest_exporting_items'] = $suggest_exporting_items;
            $data['id'] = $id;
            $this->load->view('admin/manufactures/convert_stock', $data);
        }
    }

    public function getLocationsForItems()
    {
        $data = [];
        if ($this->input->post())
        {
            $warehouse_id = $this->input->post('warehouse_id');
            $id = $this->input->post('id');
            $items = $this->manufactures_model->getSuggestExportingItems($id);
            $locations = [];
            foreach ($items as $key => $value) {
                $warehouses = $this->stock_model->getWarehouseItemsByItemIdAndTypeAndWarehouse($value['item_id'], $value['type_item'], $warehouse_id);
                foreach ($warehouses as $k => $val) {
                    $location_name = recursiveLocations($val['localtion']);
                    $locations[$value['id']][] = [
                        'location_id' => $val['localtion'],
                        'location_name' => $location_name,
                    ];
                }
            }
            $data['locations'] = $locations;
        }
        echo json_encode($data);
    }

    public function getExportStockByDetailId($id)
    {
        $this->datatables
            ->select("
                0 as number_records,
                tbl_suggest_exporting.id as id,
                tbl_suggest_exporting.date_convert_stock as date,
                tbl_suggest_exporting.reference_stock as reference_stock,
                tbl_suggest_exporting.export_name as export_name,
                tblwarehouse.name as warehouse_name,
                tbl_suggest_exporting.note as note,
                CONCAT(tblstaff.firstname, ' ', tblstaff.lastname,'') as created_by,
                tbl_suggest_exporting.status_stock as status_stock,
                '' as status_warehouse
            ", false)
            ->from('tbl_suggest_exporting')
            ->join('tbl_productions_orders_details', 'tbl_productions_orders_details.id = tbl_suggest_exporting.productions_orders_details_id', 'inner')
            ->join('tblstaff', 'tblstaff.staffid = tbl_suggest_exporting.convert_stock_by', 'left')
            ->join('tblwarehouse', 'tblwarehouse.id = tbl_suggest_exporting.warehouse_id', 'left');

        $this->datatables->where('tbl_suggest_exporting.status_stock IS NOT NULL');

        $iDisplayStart = $this->input->post('iDisplayStart');
        $data = json_decode($this->datatables->generate());
        foreach ($data->aaData as $key => $value) {
            $data->aaData[$key][0] = ++$iDisplayStart;
        }
        echo json_encode($data);
    }

    public function add_suggest_exporting()
    {
        if ($this->input->post('add'))
        {
            $data = [];
            $this->form_validation->set_rules('reference_no', lang("tnh_reference_no_suggest"), 'required|is_unique[tbl_suggest_exporting.reference_no]');
            $this->form_validation->set_rules('date', lang("date"), 'required');
            $this->form_validation->set_rules('export_name', lang("tnh_export_name"), 'required');
            $this->form_validation->set_rules('productions_orders_detail_id', lang("tnh_reference_productions_orders_details"), 'required');
            if ($this->form_validation->run() == true)
            {
                $reference_no = $this->input->post('reference_no');
                $date = to_sql_date($this->input->post('date'), true);
                $productions_orders_detail_id = $this->input->post('productions_orders_detail_id');
                $note = $this->input->post('note');
                $export_name = $this->input->post('export_name');
                $items = $this->input->post('items_id');
                $total_quantity = 0;
                $count_items = 0;
                $total_quantity_exchange = 0;
                $type = 3;

                $errors = false;

                if (!empty($items)) {
                    foreach ($items as $key => $value) {
                        if (empty($value)) continue;
                        $unit_id = $this->input->post('unit_id')[$key];
                        $unit_parent_id = $this->input->post('unit_parent_id')[$key];
                        $number_exchange = $this->input->post('number_exchange')[$key];
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
                    'reference_no' => $reference_no,
                    'reference_stock' => null,
                    'date' => $date,
                    'export_name' => $export_name,
                    'note' => $note,
                    'status' => 'un_approved',
                    'total_quantity' => $total_quantity,
                    'count_items' => $count_items,
                    'total_quantity_exchange' => $total_quantity_exchange,
                    'created_by' => get_staff_user_id(),
                    'date_created' => date('Y-m-d H:i:s'),
                    'type' => '3',
                ];
                $suggest_exporting_id = $this->manufactures_model->insertSuggestExporting($fields);
                if ($suggest_exporting_id) {
                    if (getReference('suggest_exporting') == $this->input->post('reference_no')) {
                        updateReference('suggest_exporting');
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
            $data['reference_no'] = getReference('suggest_exporting');
            $data['breadcrumb'] = [array('link' => base_url('admin/manufactures/list_suggest_exporting'), 'page' => lang('list_suggest_exporting')), array('link' => '#', 'page' => lang('tnh_add_suggest_exporting'))];
            $data['title'] = lang('tnh_add_suggest_exporting');
            $data['tnh'] = $this->tnh;
            $this->load->view('admin/manufactures/add_suggest_exporting', $data);
        }
    }

    public function edit_exporting_production($id)
    {
        $suggest_exporting = $this->manufactures_model->rowSuggestExporting($id);
        if ($suggest_exporting['status'] != "un_approved") {
            refererModel(lang('browsed_cannot_be_edited'));
        }
        if ($suggest_exporting['type'] != 3) {
            refererModel(lang('tnh_not_edit_for_bom'));
        }
        if ($this->input->post('edit'))
        {
            $data = [];
            if ($suggest_exporting['reference_no'] != $this->input->post('reference_no'))
            {
                $this->form_validation->set_rules('reference_no', lang("tnh_reference_no_suggest"), 'required|is_unique[tbl_suggest_exporting.reference_no]');
            }
            $this->form_validation->set_rules('date', lang("date"), 'required');
            $this->form_validation->set_rules('export_name', lang("tnh_export_name"), 'required');
            $this->form_validation->set_rules('productions_orders_detail_id', lang("tnh_reference_productions_orders_details"), 'required');

            if ($this->form_validation->run() == true)
            {
                $reference_no = $this->input->post('reference_no');
                $date = to_sql_date($this->input->post('date'), true);
                // $productions_orders_detail_id = $this->input->post('productions_orders_detail_id');
                $note = $this->input->post('note');
                $export_name = $this->input->post('export_name');
                $items = $this->input->post('items_id');
                $total_quantity = 0;
                $count_items = 0;
                $total_quantity_exchange = 0;
                $type = 3;

                $errors = false;

                //insert
                if (!empty($items)) {
                    foreach ($items as $key => $value) {
                        if (empty($value)) continue;
                        $unit_id = $this->input->post('unit_id')[$key];
                        $unit_parent_id = $this->input->post('unit_parent_id')[$key];
                        $number_exchange = $this->input->post('number_exchange')[$key];
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
                        $quantity_exchange = $quantity_export/$number_exchange;

                        $exporting_items_up[] = [
                            'id' => $value,
                            'quantity_export' => $quantity_export,
                            'quantity_exchange' => $quantity_exchange,
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
                    'reference_no' => $reference_no,
                    'reference_stock' => null,
                    'date' => $date,
                    'export_name' => $export_name,
                    'note' => $note,
                    'total_quantity' => $total_quantity,
                    'count_items' => $count_items,
                    'total_quantity_exchange' => $total_quantity_exchange,
                    'updated_by' => get_staff_user_id(),
                    'date_updated' => date('Y-m-d H:i:s'),
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
            $data['production_orders_detail'] = $this->manufactures_model->rowProductionsOrdersDetais($suggest_exporting['productions_orders_details_id']);
            $data['suggest_exporting_items'] = $suggest_exporting_items;
            $data['id'] = $id;
            $data['breadcrumb'] = [array('link' => base_url('admin/manufactures/list_suggest_exporting'), 'page' => lang('list_suggest_exporting')), array('link' => '#', 'page' => lang('tnh_edit_suggest_exporting'))];
            $data['title'] = lang('tnh_edit_suggest_exporting');
            $data['tnh'] = $this->tnh;
            $this->load->view('admin/manufactures/edit_exporting_production', $data);
        }
    }

    function refereshReferenceSuggestExporting()
    {
        $data = [];
        if ($this->input->get('referesh'))
        {
            $reference_no = getReference('suggest_exporting');
            if ($this->manufactures_model->checkExistSuggestExportingReferenceNo($reference_no)) {
                $ct = countReferenceMinus('suggest_exporting');
                $this->db->select("MAX(right(tbl_suggest_exporting.reference_no, char_length(tbl_suggest_exporting.reference_no) - $ct) + 0) as reference_no", false);
                $this->db->from('tbl_suggest_exporting');
                $rs = $this->db->get()->row_array();

                $max = $rs['reference_no'];
                $max++;
                // $max = subReference($max);
                updateReferenceNormal('suggest_exporting', $max);
                $reference_no = getReference('suggest_exporting');
            }
            $data['reference_no'] = $reference_no;
            $data['message'] = lang('tnh_referesh_success');
        }
        echo json_encode($data);
    }
}

