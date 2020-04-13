<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Business_plan extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('products_model');
        $this->load->model('items_model');
        $this->load->model('unit_model');
        $this->load->model('category_model');
        $this->load->model('manufactures_model');
        $this->load->model('business_plan_model');
        $this->load->model('departments_model');
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
        $data['title'] = lang('tnh_business_plan');
        $this->load->view('admin/business_plan/manage', $data);
    }

    public function add()
    {
        if ($this->input->post('add'))
        {
            $data = [];
            $this->form_validation->set_rules('reference_no', lang("tnh_reference_business_plan"), 'trim|required|is_unique[tbl_business_plan.reference_no]');
            $this->form_validation->set_rules('date', lang("date"), 'required');
            $this->form_validation->set_rules('plan_name', lang("tnh_plan_name"), 'required');
            $this->form_validation->set_rules('departments', lang("departments"), 'required');
            if ($this->form_validation->run() == true)
            {
                $reference_no = $this->input->post('reference_no');
                $date = to_sql_date($this->input->post('date'), true);
                $note = $this->input->post('note');
                $plan_name = $this->input->post('plan_name');
                $departments = $this->input->post('departments');
                $total_quantity = 0;
                $count_items = 0;

                // $counter = $this->input->post('counter');
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
                    $sub = [];

                    $date_sub = $this->input->post('date_sub')[$counter];
                    $total_quantity_sub = 0;
                    if (!empty($date_sub)) {

                        foreach ($date_sub as $k => $val) {
                            if (empty($val)) continue;
                            $quantity_sub = $this->input->post('quantity_sub')[$counter][$k];
                            $sub[] = [
                                'date' => to_sql_date($val),
                                'quantity' => $quantity_sub
                            ];
                            $total_quantity_sub+= $quantity_sub;
                        }

                        if ($total_quantity_sub > $quantity) {
                            $data['result'] = 0;
                            $data['message'] = lang('tnh_check_date_enter');
                            echo json_encode($data);
                            die;
                        }
                    }

                    $items[] = [
                        'type_items' => $type_items,
                        'items_id' => $items_id,
                        'items_code' => $items_code,
                        'items_name' => $items_name,
                        'quantity' => $quantity,
                        'note_items' => $note_items,
                        'sub' => $sub
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
                    'plan_name' => $plan_name,
                    'departments_id' => $departments,
                    'total_quantity' => $total_quantity,
                    'count_items' => $count_items,
                    'note' => $note,
                    'status' => 'un_approved',
                    'productions_plan_id' => 0,
                    'date_created' => date('Y-m-d H:i'),
                    'created_by' => get_staff_user_id(),
                ];
                $business_plan_id = $this->business_plan_model->insertBusinessPlan($options);
                if ($business_plan_id) {
                    if (getReference('business_plan') == $this->input->post('reference_no')) {
                        updateReference('business_plan');
                    }
                    foreach ($items as $key => $value) {
                        $op = [
                            'business_plan_id' => $business_plan_id,
                            'type_items' => $value['type_items'],
                            'items_id' => $value['items_id'],
                            'items_code' => $value['items_code'],
                            'items_name' => $value['items_name'],
                            'quantity' => $value['quantity'],
                            'note_items' => $value['note_items'],
                        ];
                        $business_plan_item_id = $this->business_plan_model->insertBusinessPlanItems($op);
                        if ($business_plan_item_id) {
                            $sb = $value['sub'];
                            foreach ($sb as $k => $val) {
                                $sb[$k]['business_plan_items_id'] = $business_plan_item_id;
                            }
                            if (!empty($sb)) {
                                $this->business_plan_model->insertBatchBusinessPlanItemsDate($sb);
                            }
                        }
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
        $data['departments'] = $this->departments_model->getDepartments();
        $data['reference_no'] = getReference('business_plan');
        $data['tnh'] = true;
        $data['title'] = lang('tnh_add_business_plan');
        $data['breadcrumb'] = [array('link' => base_url('admin/business_plan'), 'page' => lang('tnh_business_plan')), array('link' => '#', 'page' => lang('tnh_add_business_plan'))];
        $this->load->view('admin/business_plan/add', $data);
    }

    public function edit($id)
    {
        $business_plan = $this->business_plan_model->rowBusinessPlanById($id);
        $business_plan_items = $this->business_plan_model->getBusinessPlanItemsByBusinessPlanId($id);
        if (empty($business_plan)) {
            set_alert('danger', lang('no_data_exists'));
            redirect($_SERVER["HTTP_REFERER"]);
            die;
        }
        if ($business_plan['status'] == "approved") {
            set_alert('danger', lang('browsed_cannot_be_edited'));
            redirect($_SERVER["HTTP_REFERER"]);
            die;
        }

        if ($this->input->post('edit'))
        {
            $data = [];
            if ($business_plan['reference_no'] != $this->input->post('reference_no')) {
                $this->form_validation->set_rules('reference_no', lang("tnh_reference_business_plan"), 'trim|required|is_unique[tbl_business_plan.reference_no]');
            }
            $this->form_validation->set_rules('date', lang("date"), 'required');
            $this->form_validation->set_rules('plan_name', lang("tnh_plan_name"), 'required');
            $this->form_validation->set_rules('departments', lang("departments"), 'required');
            if ($this->form_validation->run() == true)
            {
                $reference_no = $this->input->post('reference_no');
                $date = to_sql_date($this->input->post('date'), true);
                $note = $this->input->post('note');
                $plan_name = $this->input->post('plan_name');
                $departments = $this->input->post('departments');
                $total_quantity = 0;
                $count_items = 0;
                $i = 0;

                //update
                $business_plan_items_id = $this->input->post('business_plan_items_id');
                $arr_id_exists = [];
                if (!empty($business_plan_items_id)) {
                    foreach ($business_plan_items_id as $key => $value) {
                        $counter = $this->input->post('counter')[$i];
                        array_push($arr_id_exists, $value);

                        $quantity = number_unformat($this->input->post('quantity_edit')[$key]);
                        $note_items = $this->input->post('note_items_edit')[$key];
                        $sub = [];
                        $date_sub = $this->input->post('date_sub')[$counter];
                        $total_quantity_sub = 0;
                        if (!empty($date_sub)) {

                            foreach ($date_sub as $k => $val) {
                                if (empty($val)) continue;
                                $quantity_sub = $this->input->post('quantity_sub')[$counter][$k];
                                $sub[] = [
                                    'business_plan_items_id' => $value,
                                    'date' => to_sql_date($val),
                                    'quantity' => $quantity_sub
                                ];
                                $total_quantity_sub+= $quantity_sub;
                            }

                            if ($total_quantity_sub > $quantity) {
                                $data['result'] = 0;
                                $data['message'] = lang('tnh_check_date_enter');
                                echo json_encode($data);
                                die;
                            }
                        }

                        $items_up[] = [
                            'id' => $value,
                            'quantity' => $quantity,
                            'note_items' => $note_items,
                            'sub' => $sub
                        ];

                        $total_quantity+= $quantity;
                        $i++;
                    }
                }

                //add
                $items_id = $this->input->post('items_id');
                if (!empty($items_id)) {
                    foreach ($items_id as $key => $value) {
                        $items_id = $value;
                        $counter = $this->input->post('counter')[$i];
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
                        $sub = [];

                        $date_sub = $this->input->post('date_sub')[$counter];
                        $total_quantity_sub = 0;
                        if (!empty($date_sub)) {

                            foreach ($date_sub as $k => $val) {
                                if (empty($val)) continue;
                                $quantity_sub = $this->input->post('quantity_sub')[$counter][$k];
                                $sub[] = [
                                    'date' => to_sql_date($val),
                                    'quantity' => $quantity_sub
                                ];
                                $total_quantity_sub+= $quantity_sub;
                            }

                            if ($total_quantity_sub > $quantity) {
                                $data['result'] = 0;
                                $data['message'] = lang('tnh_check_date_enter');
                                echo json_encode($data);
                                die;
                            }
                        }

                        $items[] = [
                            'business_plan_id' => $id,
                            'type_items' => $type_items,
                            'items_id' => $items_id,
                            'items_code' => $items_code,
                            'items_name' => $items_name,
                            'quantity' => $quantity,
                            'note_items' => $note_items,
                            'sub' => $sub
                        ];

                        $total_quantity+= $quantity;
                        $i++;
                    }
                }

                if (empty($items) && empty($items_up)) {
                    $data['result'] = 0;
                    $data['message'] = lang('tnh_not_items');
                    echo json_encode($data);
                    die;
                }
                // print_arrays($items_up, $items);

                $count_items = (!empty($items) ? count($items) : 0) + (!empty($items_up) ? count($items_up) : 0);

                $options = [
                    'date' => $date,
                    'reference_no' => $reference_no,
                    'total_quantity' => $total_quantity,
                    'plan_name' => $plan_name,
                    'departments_id' => $departments,
                    'count_items' => $count_items,
                    'note' => $note,
                    'date_updated' => date('Y-m-d H:i'),
                    'updated_by' => get_staff_user_id(),
                ];
                // print_arrays($items);
                $up = $this->business_plan_model->updateBusinessPlan($id, $options);
                if ($up) {
                    $delete = $this->business_plan_model->getBusinessPlanItemsByNotArrId($arr_id_exists, $id);
                    //update
                    if (!empty($items_up)) {
                        foreach ($items_up as $key => $value) {
                            $sb = $value['sub'];
                            unset($value['sub']);
                            $up_item = $this->business_plan_model->updateBusinessPlanItems($value['id'], $value);
                            if ($up_item) {
                                $this->business_plan_model->deleteBusinessPlanItemsDateBusinessPlanItemsId($value['id']);
                                if (!empty($sb)) {
                                    $this->business_plan_model->insertBatchBusinessPlanItemsDate($sb);
                                }
                            }
                        }
                    }

                    //add
                    if (!empty($items)) {
                        foreach ($items as $key => $value) {
                            $op = [
                                'business_plan_id' => $value['business_plan_id'],
                                'type_items' => $value['type_items'],
                                'items_id' => $value['items_id'],
                                'items_code' => $value['items_code'],
                                'items_name' => $value['items_name'],
                                'quantity' => $value['quantity'],
                                'note_items' => $value['note_items'],
                            ];
                            $business_plan_item_id = $this->business_plan_model->insertBusinessPlanItems($op);
                            if ($business_plan_item_id) {
                                $sb = $value['sub'];
                                foreach ($sb as $k => $val) {
                                    $sb[$k]['business_plan_items_id'] = $business_plan_item_id;
                                }
                                if (!empty($sb)) {
                                    $this->business_plan_model->insertBatchBusinessPlanItemsDate($sb);
                                }
                            }
                        }
                    }

                    //delete
                    if (!empty($delete)) {
                        foreach ($delete as $key => $value) {
                            if ($this->business_plan_model->deleteBusinessPlanItems($value['id'])) {
                                $this->business_plan_model->deleteBusinessPlanItemsDateBusinessPlanItemsId($value['id']);
                            }
                        }
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

        $data['business_plan'] = $business_plan;
        // $data['business_plan_items'] = $business_plan_items;
        $counter = 0;
        $tr_html = '';
        if (!empty($business_plan_items)) {
            foreach ($business_plan_items as $key => $value) {
                $images = $value['images'];
                if (!empty($images)) {
                    $images = base_url().'uploads/products/'.$images;
                } else {
                    $images = base_url().'assets/images/tnh/no_image.png';
                }

                $sub_date = $this->business_plan_model->getBusinessPlanItemsDateByBusinessPlanItemsId($value['id']);
                $html_sub_date = '';
                if (!empty($sub_date)) {
                    foreach ($sub_date as $k => $val) {
                        $html_sub_date.= '<div class="row">'.
                                            '<div class="col-md-7" style="padding: 0px;"><input type="text" name="date_sub['.$counter.'][]" id="input" class="form-control datepicker date_sub" placeholder="'.lang('date').'" value="'._d($val['date']).'" style="width: 100%;" title=""></div>'.
                                            '<div class="col-md-4" style="padding: 0px;"><input type="text" onkeyup="formatNumBerKeyUpCus(this)" style="width: 100%;" name="quantity_sub['.$counter.'][]" id="input" class="form-control quantity_sub" value="'.number_format($val['quantity']).'" title=""></div>'.
                                            '<div class="col-md-1" style="padding: 0px;"><div style="margin: 50%;"><i class="fa fa-remove remove-sub pointer text-danger"></i></div></div>'.
                                        '</div>';
                    }
                }

                $td1 = '<div class="stt text-center">'.(++$key).'</div>';
                $td2 = '
                    <input type="hidden" name="business_plan_items_id[]" id="input" class="form-control" value="'.$value['id'].'">
                    <input type="hidden" name="counter[]" id="input" class="form-control" value="'.$counter.'">'.$value['items_code'];
                $td3 = '<div class="td-image">'.
                            '<div class="preview_image" style="width: auto;">'.
                                '<div class="display-block contract-attachment-wrapper img">'.
                                    '<div style="width:45px;">'.
                                        '<a href="'.$images.'" data-lightbox="customer-profile" class="display-block mbot5">'.
                                            '<div class="">'.
                                                '<img src="'.$images.'" style="border-radius: 50%">'.
                                            '</div>'.
                                        '</a>'.
                                    '</div>'.
                                '</div>'.
                            '</div>'.
                    '</div>';
                $td4 = '<div class="td-item-name">'.$value['items_name'].'</div>';
                $td5 = '<div class="td-quantity"><input type="text" onkeyup="formatNumBerKeyUpCus(this)" name="quantity_edit[]" id="quantity[]" class="form-control quantity" value="'.number_format($value['quantity']).'"></div>';
                $td6 = '<div class="td-date">'.
                        '<div class="sub">'.$html_sub_date.'</div>'.
                        '<a class="pointer" onclick="addRowShipping('.$counter.', this)"><i class="fa fa-plus"></i> '.lang('tnh_expected_date').'</a>'.
                        '<div class="text-danger show-errors"></div>'.
                    '</div>';
                $td7 = '<div class="td-note"><textarea name="note_items_edit[]" id="note_items[]" class="form-control" rows="3">'.$value['note_items'].'</textarea></div>';
                $td8 = '<div class="text-center"><i class="fa fa-remove btn btn-danger remove-row"></i></div>';

                $tr_html.= '<tr>
                                <td>'.$td1.'</td>
                                <td>'.$td2.'</td>
                                <td>'.$td3.'</td>
                                <td>'.$td4.'</td>
                                <td>'.$td5.'</td>
                                <td>'.$td6.'</td>
                                <td>'.$td7.'</td>
                                <td>'.$td8.'</td>
                            </tr>';
                $counter++;
            }
        }
        $data['departments'] = $this->departments_model->getDepartments();
        $data['tnh'] = true;
        $data['tr_html'] = $tr_html;
        $data['counter'] = $counter;
        $data['title'] = lang('tnh_edit_business_plan');
        $data['breadcrumb'] = [array('link' => base_url('admin/business_plan'), 'page' => lang('tnh_business_plan')), array('link' => '#', 'page' => lang('tnh_edit_business_plan'))];
        $this->load->view('admin/business_plan/edit', $data);
    }

    function refereshReference()
    {
        $data = [];
        if ($this->input->get('referesh'))
        {
            $reference_no = getReference('business_plan');
            if ($this->manufactures_model->checkExistProductionsPlanByReferenceNo($reference_no)) {
                $this->db->select('MAX(tbl_business_plan.reference_no) as reference_no', false);
                $this->db->from('tbl_business_plan');
                $rs = $this->db->get()->row_array();

                $max = $rs['reference_no'];
                $max = subReference($max);
                updateReferenceNormal('business_plan', $max);
                $reference_no = getReference('business_plan');
            }
            $data['reference_no'] = $reference_no;
            $data['message'] = lang('tnh_referesh_success');
        }
        echo json_encode($data);
    }

    function getBusinessPlan() {
        $this->datatables->select("
            tbl_business_plan.id as id,
            tbl_business_plan.date as date,
            tbl_business_plan.reference_no as reference_no,
            tbl_business_plan.plan_name as plan_name,
            tbldepartments.name as departments_name,
            tbl_business_plan.note as note,
            CONCAT(tblstaff.firstname, ' ', tblstaff.lastname,'') as created_by,
            tbl_business_plan.status as status,
            CONCAT(staff_status.firstname, ' ', staff_status.lastname,'') as user_status,
            tbl_business_plan.productions_plan_id as status_productions_plan
            ", FALSE)
        ->from('tbl_business_plan')
        ->join('tbldepartments', 'tbldepartments.departmentid = tbl_business_plan.departments_id', 'left')
        ->join('tblstaff', 'tblstaff.staffid = tbl_business_plan.created_by', 'left')
        ->join('tblstaff staff_status', 'staff_status.staffid = tbl_business_plan.user_status', 'left');

        // $iDisplayStart = $this->input->post('iDisplayStart');
        $data = json_decode($this->datatables->generate());
        // foreach ($data->aaData as $key => $value) {
        //     $data->aaData[$key][0] = ++$iDisplayStart;
        // }
        echo json_encode($data);
    }

    public function view_business_plan($id) {
        $business_plan = $this->business_plan_model->rowBusinessPlanById($id);
        $items = $this->business_plan_model->getBusinessPlanItemsByBusinessPlanId($id);
        $tr_html = '';
        if (!empty($items)) {
            foreach ($items as $key => $value) {
                $images = $value['images'];
                if (!empty($images)) {
                    $images = base_url().'uploads/products/'.$images;
                } else {
                    $images = base_url().'assets/images/tnh/no_image.png';
                }

                $sub_date = $this->business_plan_model->getBusinessPlanItemsDateByBusinessPlanItemsId($value['id']);
                $html_sub_date = '';
                if (!empty($sub_date)) {
                    foreach ($sub_date as $k => $val) {
                        $html_sub_date.= '<div class="">'.
                                            '<div class="col-md-8" style="padding: 0px;">'._d($val['date']).' </div>'.
                                            '<div class="col-md-4" style="padding: 0px;"> - '.number_format($val['quantity']).'</div>'.
                                        '</div>';
                    }
                }

                $td1 = '<div class="">'.(++$key).'</div>';
                $td2 = '<div class="td-image">'.
                            '<div class="preview_image" style="width: auto;">'.
                                '<div class="display-block contract-attachment-wrapper img">'.
                                    '<div style="width:45px;">'.
                                        '<a href="'.$images.'" data-lightbox="customer-profile" class="display-block mbot5">'.
                                            '<div class="">'.
                                                '<img src="'.$images.'" style="border-radius: 50%">'.
                                            '</div>'.
                                        '</a>'.
                                    '</div>'.
                                '</div>'.
                            '</div>'.
                        '</div>';
                $td3 = $value['items_code'];
                $td4 = $value['items_name'];
                $td5 = '<div class="text-center">'.number_format($value['quantity']).'</div>';
                $td6 = $html_sub_date;
                $td7 = $value['note_items'];

                $tr_html.= '<tr>
                                <td>'.$td1.'</td>
                                <td>'.$td2.'</td>
                                <td>'.$td3.'</td>
                                <td>'.$td4.'</td>
                                <td>'.$td5.'</td>
                                <td>'.$td6.'</td>
                                <td>'.$td7.'</td>
                            </tr>';
            }
        }

        $data['business_plan'] = $business_plan;
        $data['tr_html'] = $tr_html;
        $data['created_by'] = get_staff_full_name($business_plan['created_by']);
        $data['updated_by'] = get_staff_full_name($business_plan['updated_by']);
        $data['user_status'] = get_staff_full_name($business_plan['user_status']);
        $this->load->view('admin/business_plan/view_business_plan', $data);
    }

    function delete($id) {
        $data = [];
        if ($this->input->get('delete')) {
            $business_plan = $this->business_plan_model->rowBusinessPlanById($id);
            $items = $this->business_plan_model->getBusinessPlanItems($id);
            if ($business_plan['status'] == "approved") {
                $data['result'] = 0;
                $data['message'] = lang('browsed_cannot_be_deleted');
                echo json_encode($data); die;
            }
            if ($this->business_plan_model->deleteBusinessPlan($id)) {
                foreach ($items as $key => $value) {
                    if ($this->business_plan_model->deleteBusinessPlanItems($value['id'])) {
                        $this->business_plan_model->deleteBusinessPlanItemsDateBusinessPlanItemsId($value['id']);
                    }
                }
                $data['result'] = 1;
                $data['message'] = lang('success');
            } else {
                $data['result'] = 0;
                $data['message'] = lang('fail');
            }
        }
        echo json_encode($data);
    }

    function agree()
    {
        $data = [];
        if ($this->input->get())
        {
            $business_plan_id = $this->input->get('business_plan_id');
            $status = $this->input->get('status');
            $business_plan = $this->business_plan_model->rowBusinessPlanById($business_plan_id);
            $date = date('Y-m-d H:i');
            $user_id = get_staff_user_id();
            if ($business_plan['status'] == $status) {
                $data['result'] = 0;
                $data['message'] = lang('tnh_please_referesh_table');
                echo json_encode($data); die;
            }
            $up = $this->business_plan_model->updateBusinessPlan($business_plan_id, [
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
}

?>