<?php

// header('Content-Type: text/html; charset=utf-8');
defined('BASEPATH') or exit('No direct script access allowed');

class Products extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('products_model');
        $this->load->model('items_model');
        $this->load->model('unit_model');
        $this->load->model('category_model');
        $this->load->model('departments_model');
        // $this->lang->load('vietnamese/form_validation_lang');
        $this->image_types = 'gif|jpg|jpeg|png|tif';
        $this->allowed_file_size = '1024';
        $this->upload_path = get_upload_path_by_type('products');
        $this->datetime_now = time();
        $this->custom_fields = get_custom_fields('products');
        $this->show_table_custom_fields = get_table_custom_fields('products');
        $this->show_table_product_fields = $this->site_model->getClientInfoDetailProduct();
    }

    public function index()
    {
        $data['tnh'] = true;
        $data['path'] = $this->upload_path;
        $data['title'] = _l('tnh_products');
        $th = '';
        $targets = 32;
        $script = '';
        if (!empty($this->show_table_custom_fields))
        {
            foreach ($this->show_table_custom_fields as $key => $value) {
                $th.= '<th>'._maybe_translate_custom_field_name($value['name'], $value['slug']).'</th>';
                $script.= '{
                    "targets": '.$targets.', "name": "'.$value['slug'].'", "width": "100px"
                },';
                $targets++;
            }
        }
        if (!empty($this->show_table_product_fields))
        {
            foreach ($this->show_table_product_fields as $key => $value) {
                $th.= '<th>'.$value['name'].'</th>';
                $script.= '{
                    "targets": '.$targets.', "name": "product_'.$value['id'].'", "width": "100px"
                },';
                $targets++;
            }
        }
        $data['targets'] = $targets;
        $data['script'] = $script;
        $data['th'] = $th;
        $this->load->view('admin/products/manage', $data);
    }

    function getProducts()
    {
        $type = $this->input->post('status_table');

        $colors = "(
            SELECT
                tbl_products_colors.product_id,
                GROUP_CONCAT(tbl_colors.name SEPARATOR '</br>') as color_name
            FROM tbl_products_colors
            INNER JOIN tbl_colors ON tbl_products_colors.color_id = tbl_colors.id
            GROUP BY tbl_products_colors.product_id
        ) as colors";

        $BOM = "(
            SELECT
                tbl_product_versions.product_id,
                GROUP_CONCAT(tbl_product_versions.versions SEPARATOR ':::') as versions
            FROM tbl_product_versions
            GROUP BY tbl_product_versions.product_id
        ) as BOM";

        $stages = "(
            SELECT
                tbl_product_stages.product_id,
                GROUP_CONCAT(tbl_product_stages.versions SEPARATOR ':::') as versions
            FROM tbl_product_stages
            GROUP BY tbl_product_stages.product_id
        ) as stages";

        $select_custom_fields = "";
        $custom = [];
        $custom_select = [];
        $target = 32;
        if (!empty($this->show_table_custom_fields))
        {
            foreach ($this->show_table_custom_fields as $key => $value) {
                $select = "COALESCE((
                    SELECT tblcustomfieldsvalues.value
                    FROM tblcustomfieldsvalues
                    WHERE tblcustomfieldsvalues.fieldto = 'products' AND tblcustomfieldsvalues.relid = tbl_products.id AND tblcustomfieldsvalues.fieldid = ".$value['id']."
                ), '') ";
                $select_custom_fields.= ", ". $select." as ".$value['slug'];
                $custom[] = [
                    'index' => $target,
                    // 'cloumn' => $select,
                    'select' => $value['slug'],
                ];
                $custom_select[$target] = $select;
                $target++;
            }
        }
        if (!empty($this->show_table_product_fields)) {
            foreach ($this->show_table_product_fields as $key => $value) {
                $select = "COALESCE((
                    SELECT GROUP_CONCAT(tblclient_info_detail_value.name SEPARATOR '</br>')
                    FROM tblproduct_group_info
                    INNER JOIN tblclient_info_detail_value ON tblproduct_group_info.id_value = tblclient_info_detail_value.id
                    WHERE tblproduct_group_info.id_product = tbl_products.id AND tblproduct_group_info.id_info = ".$value['id']."
                ), '') ";
                $select_custom_fields.= ", ". $select." as product_".$value['id'];
                $custom[] = [
                    'index' => $target,
                    'select' => 'product_'.$value['id'],
                ];
                $custom_select[$target] = $select;
                $target++;
            }
        }

        if (empty($select_custom_fields)) $select_custom_fields = ", ";
        if (!empty($select_custom_fields)) $select_custom_fields.= ", ";

        $this->datatables->select("
            tbl_products.id as id,
            tbl_products.images as images,
            tbl_category_products.name as category_name,
            tbl_products.type_products as type_products,
            tbl_products.code as code,
            tbl_products.name as name,
            tbl_products.code_system as code_system,
            tbl_products.name_customer as name_customer,
            tbl_products.name_supplier as name_supplier,
            tbl_products.type_gender as type_gender,
            tblcombobox_client.name as name_dt,
            tbl_products.size as size,
            tbl_products.weight as weight,
            tbl_products.structure as structure,
            tbl_products.description as description,
            tblunits.unit as unit_name,
            colors.color_name as color,
            tbl_products.mode as mode,
            tbl_products.price_import as price_import,
            tbl_products.price_domestic as price_domestic,
            tbl_products.price_foreign as price_foreign,
            tbl_products.price_processing as price_processing,
            tbl_products.quantity_minimum as quantity_minimum,
            tbl_products.quantity_max as quantity_max,
            tbl_products.number_hours_ap as number_hours_ap,
            BOM.versions as bm,
            stages.versions as st,
            tbl_products.code as barcode,
            tbl_products.number_labor as number_labor,
            tbl_products.note as note,
            tbl_products.versions as versions,
            tbl_products.versions_stage as versions_stage
            $select_custom_fields
            CONCAT(ct.firstname, ' ', ct.lastname,'') as created_by,
            tbl_products.date_created as date_created,
            CONCAT(ut.firstname, ' ', ut.lastname,'') as updated_by,
            tbl_products.date_updated as date_updated,
            ", FALSE)
        ->from('tbl_products')
        ->join('tbl_category_products', 'tbl_category_products.id = tbl_products.category_id', 'left')
        ->join('tblunits', 'tblunits.unitid = tbl_products.unit_id', 'left')
        ->join('tblcombobox_client', 'tblcombobox_client.id = tbl_products.type_dt', 'left')
        ->join('tblstaff ct', 'ct.staffid = tbl_products.created_by', 'left')
        ->join('tblstaff ut', 'ut.staffid = tbl_products.updated_by', 'left')
        ->join($colors, 'colors.product_id = tbl_products.id', 'left')
        ->join($BOM, 'BOM.product_id = tbl_products.id', 'left')
        ->join($stages, 'stages.product_id = tbl_products.id', 'left');

        $this->datatables->custom_ordering($custom);
        $this->datatables->custom_select($custom_select);

        if (!empty($type)) {
            $this->datatables->where('tbl_products.type_products', $type);
        }

        $edit = '<a class="tnh-modal" data-tnh="modal" data-toggle="modal" data-target="#myModal" href="'.base_url().'admin/products/edit_product/$1"><i class="fa fa-pencil width-icon-actions"></i> '.lang('edit').'</a>';

        $design_bom = '<a class="tnh-modal design_bom" data-tnh="modal" data-toggle="modal" data-target="#myModal" href="'.base_url().'admin/products/design_bom/$1"><i class="fa fa-bomb width-icon-actions"></i> '.lang('tnh_design_bom').'</a>';

        $stages = '<a class="tnh-modal stages" data-tnh="modal" data-toggle="modal" data-target="#myModal" href="'.base_url().'admin/products/design_stages/$1"><i class="fa fa-tasks width-icon-actions"></i> '.lang('stages').'</a>';

        $delete = '<a type="button" class="po" data-container="body" data-html="true" data-toggle="popover" data-placement="left" data-content="
            <button href=\''.base_url('admin/products/delete_product/$1').'\' class=\'btn btn-danger po-delete-json\'>'.lang('delete').'</button>
            <button class=\'btn btn-default po-close\'>'.lang('close').'</button>
        "><i class="fa fa-remove width-icon-actions"></i> '.lang('delete').'</a>';


        $actions = '
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle nav-link" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
            '.lang('actions').'
            <span class="caret"></span>
            </button>
            <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdownMenu1">
                <li>'.$edit.'</li>
                <li>'.$design_bom.'</li>
                <li>'.$stages.'</li>
                <li class="not-outside">'.$delete.'</li>
            </ul>
        </div>';

        $this->datatables->add_column('actions', $actions, 'id');
        echo $this->datatables->generate();
    }

    function check_versions()
    {
        $product_id = $this->input->post('product_id');
        $versions = trim($this->input->post('versions'));
        if ($this->products_model->checkProductVersions($product_id, $versions)) {
            $this->form_validation->set_message('check_versions', lang('exists_versions_products'));
            return false;
        } else {
            return true;
        }
    }

    public function design_bom($id, $bom_id = 0, $actions = 'add')
    {
        $products = $this->products_model->rowProduct($id);
        if ($products['type_products'] == 'semi_products_outside') {
            refererModel(lang('semi_products_outside_not_design_bom'));
        }
        if (!empty($bom_id)) {
            $bom = $this->products_model->getProductVersionsById($bom_id);
        }
        if ($this->input->post())
        {
            $this->form_validation->set_rules('product_id', lang("id"), 'required');
            if (empty($bom) || ($bom['product_id'] != $this->input->post('product_id') && $bom['versions'] != $this->input->post('versions'))) {
                $this->form_validation->set_rules('versions', lang("tnh_versions"), 'required|callback_check_versions');
            }
            if ($this->form_validation->run() == true)
            {
                $status = "unapplication";
                $versions = trim($this->input->post('versions'));
                $product_id = $this->input->post('product_id');
                $date_start = $this->input->post('date_start') ? to_sql_date($this->input->post('date_start')) : null;
                $date_end = $this->input->post('date_end') ? to_sql_date($this->input->post('date_end')) : null;
                $i = $this->input->post('i');
                $options['versions'] = $versions;
                $options['product_id'] = $product_id;
                $options['date_start'] = $date_start;
                $options['date_end'] = $date_end;
                $options['date_created'] = date('Y-m-d H:i:s');
                $options['created_by'] = get_staff_user_id();
                foreach ($i as $key => $value) {
                    $element_name = trim($this->input->post('element_name_'.$value));
                    if (empty($element_name)) continue;
                    $element_number = $this->input->post('element_number_'.$value);
                    $options['element'][$key]['element_name'] = $element_name;
                    $options['element'][$key]['element_number'] = $element_number;
                    $type_design_bom = $this->input->post('type_design_bom_'.$value);
                    if (!empty($type_design_bom)) {
                        foreach ($type_design_bom as $k => $val) {
                            if ($products['type_products'] != 'products' && $val != 'materials') continue;
                            $item_id = $this->input->post('items_'.$value)[$k];
                            $element_item_number = $this->input->post('element_item_number_'.$value)[$k];
                            $unit_id = $this->input->post('units_'.$value)[$k];
                            $options['element'][$key]['items'][$k]['type'] = $val;
                            $options['element'][$key]['items'][$k]['item_id'] = $item_id;
                            $options['element'][$key]['items'][$k]['unit_id'] = $unit_id;
                            $options['element'][$key]['items'][$k]['element_item_number'] = $element_item_number;
                        }
                    }
                }
                // print_arrays($options);
                $q = $this->products_model->insertBOM($options, $status, $bom_id, $actions);
                if ($q) {
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
            return;
        } else {
            $data['id'] = $id;
            $data['bom_id'] = $bom_id;
            $data['products'] = $products;
            if (!empty($bom_id)) {
                $data['bom'] = $bom;
                $html_BOM = '';
                $count_i = 0;
                $count_k = 0;
                $elements = $this->products_model->getVersionsElementByVersionId($bom['id']);
                foreach ($elements as $key => $value) {
                    $html_BOM .= '<tr>';
                    $html_BOM .= '<input type="hidden" name="i[]" id="i" class="form-control i" value="'.$count_i.'">';
                    $html_BOM .= '<td>
                                    <div class="text-center">
                                        <button type="button" class="btn btn-primary btn-icon btn-add-items">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </td>';

                    $html_BOM .= '<td colspan="2">
                                    <input type="text" name="element_name_'.$count_i.'" id="element_name_'.$count_i.'" class="form-control" value="'.$value['element_name'].'" placeholder="'.lang('tnh_element_name').'" required="required">
                                </td>';
                    $html_BOM .= '<td></td>';
                    $html_BOM .= '<td>
                                    <input type="number" name="element_number_'.$count_i.'" class="form-control" value="'.$value['quantity'].'" min="0">
                                </td>';
                    $html_BOM .= '<td>
                                    <div class="text-center"><i class="btn btn-danger fa fa-remove remove-element"></i></div>
                                </td>';
                    $html_BOM .= '</tr>';

                    $items = $this->products_model->getElementItemsByElementId($value['id']);
                    foreach ($items as $k => $val) {
                        $option = '<option value=""></option>';
                        $type_design = type_design_bom($products['type_products'] == 'products' ? 'all' : 'not_all');
                        foreach ($type_design as $e => $v) {
                            $option .= '<option '.($e == $val['type'] ? 'selected' : '').' value="'.$e.'">'.$v.'</option>';
                        }

                        $arr_unit_id = [];
                        if ($val['type'] == "semi_products" || $val['type'] == "semi_products_outside") {
                            $info = $this->products_model->rowProduct($val['item_id']);
                            array_push($arr_unit_id, $info['unit_id']);
                        } else {
                            $info = $this->items_model->rowMaterial($val['item_id']);
                            $exchange = $this->items_model->getExchangeItemsByItemId($val['item_id']);
                            array_push($arr_unit_id, $info['unit_id']);
                            if (!empty($exchange)) {
                                foreach ($exchange as $ke => $va) {
                                    array_push($arr_unit_id, $va['unit_id']);
                                }
                            }
                        }
                        array_push($arr_unit_id, $val['unit_id']);
                        $option_units = '';
                        if (!empty($arr_unit_id)) {
                            $units = $this->products_model->getUnitsByArrId($arr_unit_id);
                            foreach ($units as $a => $el) {
                                $selected_unit = ($el['unitid'] == $val['unit_id']) ? 'selected' : '';
                                $option_units.= '<option '.$selected_unit.' value="'.$el['unitid'].'">'.$el['unit'].'</option>';
                            }
                        }

                        $html_BOM .= '<tr class="tnh-item-'.$count_i.'">';
                        $html_BOM .= '<td></td>';
                        $html_BOM .= '<input type="hidden" name="k[]" id="k" class="form-control k" value="'.$count_k.'">';
                        $html_BOM .= '<td colspan="1" style="width: 200px;">
                        <select name="type_design_bom_'.$count_i.'[]" data-none-selected-text="'.lang('type').'" id="type_design_bom_'.$k.'" class="form-control type_design_bom" required="required">
                            '.$option.'
                        </select>
                        </td>';
                        // $html_BOM .= '<td colspan="1">
                        // <select name="items_'.$count_i.'[]" placeholder="'.lang('choose').'" data-live-search="true" data-none-selected-text="'.lang('choose').'" id="items_'.$count_k.'" class="form-control" required="required">
                        //     <option value="'.$val['item_id'].'"" selected>'.$info['name'].'</option>
                        // </select>
                        // </td>';

                        $html_BOM .= '<td colspan="1">
                            <input type="text" name="items_'.$count_i.'[]" id="items_'.$count_k.'" data-placeholder="'.lang('choose').'" class="modal-select2 it" style="width: 100%;" value="'.$val['item_id'].'" required="required">
                        </td>';
                        $html_BOM .= '<td colspan="1" class="class="td-unit"">
                            <select data-placeholder="'.lang('choose').'" id="units_'.$count_k.'" name="units_'.$count_i.'[]" class="modal-select2 units" style="width: 100%;" required>
                                '.$option_units.'
                            </select>
                        </td>';
                        $html_BOM .= '<td colspan="">
                        <input type="number" name="element_item_number_'.$count_i.'[]" class="form-control" value="'.$val['quantity'].'" min="0">
                        </td>';
                        $html_BOM .= '<td colspan="">
                        <div class="text-center"><i class="btn btn-danger fa fa-remove remove-element-item"></i></div>
                        </td>';
                        $html_BOM .= '</tr>';
                        $count_k++;
                    }
                    $count_i++;
                }
                $data['html_BOM'] = $html_BOM;
                $data['count_i'] = $count_i;
                $data['count_k'] = $count_k;
            }
            $data['actions'] = $actions;
            $this->load->view('admin/products/design_bom', $data);
        }
    }

    public function delete_bom($id) {
        $data = [];
        if(!empty($id)) {
            if ($this->products_model->deleteBOOMById($id))
            {
                $data['result'] = 1;
                $data['message'] = lang('success');
                $data['table'] = $id;
                $data['type'] = 'BOM';
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

    public function view_product($id)
    {
        $data['id'] = $id;
        $product = $this->products_model->rowProduct($id);
        $data['colors'] = $this->products_model->getColorsByProductId($id);
        $data['unit'] = $this->unit_model->rowUnit($product['unit_id']);
        $data['product'] = $product;

        $suppliers = $this->products_model->getGroupProductSuppliers($id);
        $warehouses = $this->products_model->getProductWarehouse($id);
        $dt = $this->site_model->rowComboboxClient($product['type_dt']);
        $kt = $this->site_model->rowComboboxClient($product['type_kt']);

        $data['info_client'] = get_table_where('tblclient_info_detail', ['view_modal' => 1, 'type_form !=' => 'input']);
        foreach($data['info_client'] as $kInfo => $vInfo)
        {
            // $data['info_client'][$kInfo]['detail'] = get_table_where('tblclient_info_detail_value', ['id_info_detail' => $vInfo['id']]);

            $this->db->select('group_concat(name) as value_name');
            $this->db->join('tblproduct_group_info', 'tblproduct_group_info.id_value = tblclient_info_detail_value.id and tblproduct_group_info.id_info = '.$vInfo['id']);
            $this->db->where('id_product', $id);
            $data['info_client'][$kInfo]['detail'] = $this->db->get('tblclient_info_detail_value')->row();
        }

        $data['suppliers'] = $suppliers;
        $data['warehouses'] = $warehouses;
        $data['dt'] = $dt;
        $data['kt'] = $kt;

        $versions = $this->products_model->getProductVersionsByProductId($id);
        $BOM = '';
        if (!empty($versions))
        {
            foreach ($versions as $key => $value) {
                $delete = '<a type="button" class="po btn btn-danger pull-right" data-container="body" data-html="true" data-toggle="popover" data-placement="bottom" data-content="
                <button href=\''.base_url('admin/products/delete_bom/'.$value['id']).'\' class=\'btn btn-danger po-delete-json\'>'.lang('delete').'</button>
                <button class=\'btn btn-default po-close\'>'.lang('close').'</button>
                ">'.lang('delete').'</a>';

                // $copy = '<a class="btn btn-success pull-right copy-bom" value="'.$value['id'].'" href="javascript:void(0)">'.lang('tnh_copy').'</a>';
                $copy = '';

                $BOM.= '<div class="table-responsive">
                            <table id="tb-datatable'.$key.'" data-bom="'. $value['id'] .'" class="tnh-table table-hover table-bordered table-condensed" style="margin-top: 10px;">
                                <thead>
                                    <tr class="" style="background: #5cb0d5;">
                                        <th colspan="4" class="info">
                                            <div>
                                                '. $value['versions'] .'
                                                '. $delete .'
                                                <a href="'.base_url("admin/products/design_bom/$id/".$value['id']."/edit").'" class="btn btn-warning pull-right tnh-modal" data-tnh="modal" data-toggle="modal" data-target="#myModal">'.lang('edit').'</a>
                                                '.$copy.'
                                            </div>
                                            <div>
                                            ('._d($value['date_start']).' - '._d($value['date_end']).')
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">'. lang('tnh_numbers') .'</th>
                                        <th>'. lang('tnh_element_name') .'</th>
                                        <th>'. lang('quantity') .'</th>
                                    </tr>
                                </thead>
                                <tbody>';
                            $elements = $this->products_model->getVersionsElementByVersionId($value['id']);
                            foreach ($elements as $k => $val) {
                                $BOM.= '<tr>
                                            <td style="width: 80px;" class="text-center"><button class="btn btn-primary cols" data-toggle="collapse" data-target="#demo'. $val['id'] .'">'. (++$k) .'</button></td>
                                            <td>'. $val['element_name'] .'</td>
                                            <td>'. $val['quantity'] .'</td>
                                        </tr>';
                                $items = $this->products_model->getElementItemsByElementId($val['id']);
                                $BOM.= '<tr id="demo'. $val['id'] .'" class="collapse">
                                            <td colspan="99">
                                                <table class="tbbb tnh-table-sub table-bordered table-condensed table-hover" style="margin-top: 0px;">
                                                    <thead>
                                                        <tr style="background: #4caf50d4;">
                                                            <th style="width: 50px;" class="text-center">#</th>
                                                            <th style="width: 150px;">'. lang('type') .'</th>
                                                            <th style="width: 150px;">'. lang('code') .'</th>
                                                            <th style="width: 150px;">'. lang('name') .'</th>
                                                            <th class="text-center" style="width: 100px;">'. lang('unit') .'</th>
                                                            <th class="text-center">'. lang('quantity') .'</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>';
                                                    foreach ($items as $i => $v) {
                                                        if ($v['type'] == "semi_products" || $v['type'] == "semi_products_outside") {
                                                            $info = $this->products_model->rowProduct($v['item_id']);
                                                        } else {
                                                            $info = $this->items_model->rowMaterial($v['item_id']);
                                                        }
                                                        $BOM.= '
                                                                <tr>
                                                                    <td class="text-center">'. (++$i) .'</td>
                                                                    <td>'. lang($v['type']) .'</td>
                                                                    <td>'. $info['code'] .'</td>
                                                                    <td>'. $info['name'] .'</td>
                                                                    <td class="text-center">'. $v['unit'] .'</td>
                                                                    <td class="text-center">'. $v['quantity'] .'</td>
                                                                </tr>';
                                                    }
                                                $BOM.= '
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>';
                            }
                    $BOM.='     </tbody>
                            </table>
                        </div>';
            }
        } else {
            $BOM = '<div class="text-center">
                        <div>'.lang('no_data_exists').'</div><img src="'.base_url().'assets/images/table-no-data.png">
                    </div>';
        }
        $data['BOM'] = $BOM;

        //stage
        $stages = $this->products_model->getProductStagesByProductId($id);
        $html_stages = '';
        if (!empty($stages))
        {
            foreach ($stages as $key => $value) {
                $items = $this->products_model->getProductStagesVersions($value['id']);
                $delete = '<a type="button" class="po btn btn-danger pull-right" data-container="body" data-html="true" data-toggle="popover" data-placement="bottom" data-content="
                <button href=\''.base_url('admin/products/delete_product_stage/'.$value['id']).'\' class=\'btn btn-danger po-delete-json\'>'.lang('delete').'</button>
                <button class=\'btn btn-default po-close\'>'.lang('close').'</button>
                ">'.lang('delete').'</a>';

                $html_stages .= '
                            <table data-stages="'. $value['id'] .'" class="tnh-table table-bordered table-hover table-condensed" style="margin-top: 10px;">
                                <thead>
                                    <tr style="background: #5cb0d5;">
                                        <th colspan="4" class="info">
                                            <div>
                                                '. $value['versions'] .'
                                                '. $delete .'
                                                <a href="'.base_url("admin/products/design_stages/$id/".$value['id']).'" class="btn btn-warning pull-right tnh-modal" data-tnh="modal" data-toggle="modal" data-target="#myModal">'.lang('edit').'</a>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="width: 80px;">'.lang('tnh_numbers').'</th>
                                        <th>'.lang('stages').'</th>
                                        <th>'.lang('tnh_machines').'</th>
                                        <th class="text-center">'.lang('tnh_number_hours').'</th>
                                    </tr>
                                </thead>
                                <tbody>';
                foreach ($items as $k => $val) {
                    $str_machines = '';
                    if (!empty($val['machines'])) {
                        $machines = $this->category_model->getMachinesByArrId(explode(',', $val['machines']));
                        if (!empty($machines)) {
                            foreach ($machines as $i => $v) {
                                $str_machines.= $v['name'].'</br>';
                            }
                        }
                    }
                    $html_stages .= '<tr>
                                        <td class="text-center">'.$val['number'].'</td>
                                        <td>'.$val['stage_name'].'</td>
                                        <td>'.$str_machines.'</td>
                                        <td class="text-center">'.$val['number_hours'].'</td>
                                    </tr>';
                }
                $html_stages .= '</tbody>
                            </table>';
            }
        } else {
            $html_stages = '<div class="text-center">
                                <div>'.lang('no_data_exists').'</div>
                                <img src="'.base_url().'assets/images/table-no-data.png">
                            </div>';
        }
        $data['html_stages'] = $html_stages;
        $data['custom_fields'] = $this->custom_fields;
        $data['created_by'] = get_staff_full_name($product['created_by']);
        if (!empty($product['updated_by']))
        {
            $data['updated_by'] = get_staff_full_name($product['updated_by']);
        } else {
            $data['updated_by'] = '';
        }
        $data['id'] = $id;
        $this->load->view('admin/products/view_product', $data);
    }

    public function add_product()
    {
        $data = [];
        if ($this->input->post())
        {
            $this->form_validation->set_rules('category', lang("category"), 'required');
            $this->form_validation->set_rules('type_products', lang("tnh_type_products"), 'required');
            $this->form_validation->set_rules('name', lang("name"), 'required');
            $this->form_validation->set_rules('unit', lang("unit"), 'required');
            $this->form_validation->set_rules('code', lang("code"), 'required|is_unique[tbl_products.code]');
            if ($this->form_validation->run() == true)
            {
                // print_arrays($this->input->post(), $_FILES);
                $category = $this->input->post('category');
                $type_products = $this->input->post('type_products');
                $name = $this->input->post('name');
                $code = $this->input->post('code');
                $name_customer = $this->input->post('name_customer');
                $name_supplier = $this->input->post('name_supplier');
                $note = $this->input->post('note');
                $price_import = number_unformat($this->input->post('price_import'));
                $price_sell = number_unformat($this->input->post('price_sell'));
                $price_processing = number_unformat($this->input->post('price_processing'));
                $number_labor = number_unformat($this->input->post('number_labor'));
                $quantity_minimum = number_unformat($this->input->post('quantity_minimum'));
                $quantity_max = number_unformat($this->input->post('quantity_max'));
                $number_hours_ap = number_unformat($this->input->post('number_hours_ap'));
                $price_domestic = number_unformat($this->input->post('price_domestic'));
                $price_foreign = number_unformat($this->input->post('price_foreign'));
                $unit = $this->input->post('unit');
                $colors = $this->input->post('colors');
                $mode = $this->input->post('mode');
                $note = $this->input->post('note');
                $size = $this->input->post('size');
                $weight = $this->input->post('weight');
                $structure = $this->input->post('structure');
                $description = $this->input->post('description');

                //Công bổ sung
                $type_dt = $this->input->post('type_dt');
                $type_kt = $this->input->post('type_kt');
                $type_gender = $this->input->post('type_gender') ? $this->input->post('type_gender') : 0;
                //end công bổ sung

                $options = [
                    'category_id' => $category,
                    'type_products' => $type_products,
                    'name' => $name,
                    'code' => $code,
                    'name_customer' => $name_customer,
                    'name_supplier' => $name_supplier,
                    'price_import' => $price_import,
                    'price_sell' => $price_sell,
                    'price_processing' => $price_processing,
                    'number_labor' => $number_labor,
                    'quantity_minimum' => $quantity_minimum,
                    'quantity_max' => $quantity_max,
                    'number_hours_ap' => $number_hours_ap,
                    'unit_id' => $unit,
                    'mode' => $mode,
                    'note' => $note,
                    'date_created' => date('Y-m-d H:i:s'),
                    'created_by' => get_staff_user_id(),
	                'type_dt' => $type_dt,
	                'type_kt' => $type_kt,
                    'type_gender' => $type_gender,
                    'size' => $size,
                    'weight' => $weight,
                    'structure' => $structure,
                    'description' => $description,
                    'price_domestic' => $price_domestic,
	                'price_foreign' => $price_foreign,
                ];

                $this->load->library('upload');
                if (!empty($_FILES['image']) && $_FILES['image']['size'] > 0) {
                    $config['upload_path'] = $this->upload_path;
                    $config['allowed_types'] = $this->image_types;
                    // $config['max_size'] = $this->allowed_file_size;
                    // $config['max_width'] = $this->Settings->iwidth;
                    // $config['max_height'] = $this->Settings->iheight;
                    $config['file_name'] = vn_to_str($code).'_'.$this->datetime_now;
                    $config['overwrite'] = TRUE;
                    $config['max_filename'] = 25;
                    $config['encrypt_name'] = false;
                    $this->upload->initialize($config);

                    if (!$this->upload->do_upload('image')) {
                        $error = $this->upload->display_errors();
                        $this->session->set_flashdata('error', $error);
                        $data['result'] = 0;
                        $data['message'] = $error;
                        echo json_encode($data);
                        return;
                    }
                    $images = $this->upload->file_name;
                    $options['images'] = $images;
                } else {
                    $options['images'] = NULL;
                }

                //image multiple
                if (!empty($_FILES['images_multiple']) && !empty($_FILES['images_multiple']['size'])) {
                    $fileCount = count($_FILES['images_multiple']['name']);
                    for ($i = 0; $i < $fileCount; $i++) {
                        $_FILES['file']['name'] = $_FILES['images_multiple']['name'][$i];
                        $_FILES['file']['type'] = $_FILES['images_multiple']['type'][$i];
                        $_FILES['file']['tmp_name'] = $_FILES['images_multiple']['tmp_name'][$i];
                        $_FILES['file']['error'] = $_FILES['images_multiple']['error'][$i];
                        $_FILES['file']['size'] = $_FILES['images_multiple']['size'][$i];

                        $config['upload_path'] = $this->upload_path;
                        $config['allowed_types'] = $this->image_types;
                        $config['file_name'] = vn_to_str($code).'_'.$i.'_'.$this->datetime_now;
                        $config['overwrite'] = TRUE;
                        $config['max_filename'] = 25;
                        $config['encrypt_name'] = false;
                        $this->upload->initialize($config);
                        if ($this->upload->do_upload('file')) {
                            $uploadData[$i] = $this->upload->file_name;
                        }
                    }
                }
                if (!empty($uploadData)) {
                    $options['images_multiple'] = implode('||', $uploadData);
                } else {
                    $options['images_multiple'] = NULL;
                }
                //end image multiple

                //handing warehouse locaiton
                $warehouses = $this->input->post('warehouses');
                if (!empty($warehouses)) {
                    foreach ($warehouses as $key => $value) {
                        if (empty($value)) continue;
                        $location_id = $this->input->post('location')[$key];
                        $productWarehouse[] = [
                            'warehouse_id' => $value,
                            'location_id' => $location_id,
                        ];
                    }
                }
                //end

                //handing suppliers
                $counter = $this->input->post('counter');
                if (!empty($counter)) {
                    foreach ($counter as $key => $value) {
                        $supplier_id = $this->input->post('suppliers')[$value];
                        if (empty($supplier_id)) continue;
                        $procedure_id = !empty($this->input->post('procedure')[$value]) ?  $this->input->post('procedure')[$value] : false;

                        if (!empty($procedure_id)) {
                            foreach ($procedure_id as $k => $val) {
                                $procedure_id = $this->input->post('procedure')[$value][$k];
                                $sequence = $this->input->post('sequence')[$value][$k];
                                $number_date = $this->input->post('number_date')[$value][$k];
                                $productSuppliers[] = [
                                    'supplier_id' => $supplier_id,
                                    'procedure_id' => $procedure_id,
                                    'sequence' => $sequence,
                                    'number_date' => $number_date,
                                ];
                            }
                        } else {
                            $productSuppliers[] = [
                                'supplier_id' => $supplier_id,
                                'procedure_id' => 0,
                                'sequence' => 0,
                                'number_date' => 0,
                            ];
                        }
                    }
                }
                //end
                $code_system = getReference('product_system');
                $options['code_system'] = $code_system;

                $id = $this->products_model->insertProducts($options);
                if ($id) {
                    updateReference('product_system');
                    if (!empty($colors))
                    {
                        $cl = [];
                        foreach ($colors as $key => $value) {
                            $cl[] = [
                                'product_id' => $id,
                                'color_id' => $value,
                            ];
                        }
                        $this->products_model->insertBatchProductsColors($cl);
                        //bom
                        $bom_id = $this->input->post('bom_id');
                        if (!empty($bom_id) && $type_products != "semi_products_outside")
                        {
                            $bom = $this->products_model->rowBomById($bom_id);
                            if (!empty($bom)) {
                                $fields = [
                                    'versions' => $bom['versions'],
                                    'product_id' => $id,
                                    'bm_id' => $bom_id,
                                    'status' => 'unapplication',
                                    'date_start' => $bom['date_start'],
                                    'date_end' => $bom['date_end'],
                                    'date_created' => $bom['date_created'],
                                    'created_by' => $bom['created_by'],
                                ];
                                $bom_element = $this->products_model->getBomsElementByBomId($bom_id);
                                foreach ($bom_element as $key => $value) {
                                    $fields['element'][$key]['element_name'] = $value['element_name'];
                                    $fields['element'][$key]['element_number'] = $value['quantity'];

                                    $type = false;
                                    if ($type_products == 'semi_products') $type = ['materials'];
                                    $items = $this->products_model->getBomsElementItemsByBEI($value['id'], $type);
                                    if (!empty($items)) {
                                        foreach ($items as $k => $val) {
                                            $fields['element'][$key]['items'][$k]['type'] = $val['type'];
                                            $fields['element'][$key]['items'][$k]['item_id'] = $val['item_id'];
                                            $fields['element'][$key]['items'][$k]['unit_id'] = $val['unit_id'];
                                            $fields['element'][$key]['items'][$k]['element_item_number'] = $val['quantity'];
                                        }
                                    }
                                }
                                if (!empty($fields)) {
                                    $ib = $this->products_model->insertBOM($fields, 'unapplication', 0, $actions = "add");
                                    if ($ib) {
                                        $this->products_model->updateProducts($id, ['versions' => $bom['versions'], 'bom_id' => $bom_id]);
                                    }
                                }
                            }
                        }
                    }
                    if ($this->input->post('custom_fields')) {
                        handle_custom_fields_post($id, $this->input->post('custom_fields'));
                    }

                    //insert warehouse locaiton
                    if (!empty($productWarehouse)) {
                        foreach ($productWarehouse as $key => $value) {
                            $productWarehouse[$key]['product_id'] = $id;
                        }
                        $this->products_model->insertBatchProductWarehouse($productWarehouse);
                    }
                    //end
                    //insert material suppliers
                    if (!empty($productSuppliers)) {
                        foreach ($productSuppliers as $key => $value) {
                            $productSuppliers[$key]['product_id'] = $id;
                        }
                        $this->products_model->insertBatchProductSuppliers($productSuppliers);
                    }
                    //end

                    $data['result'] = 1;
                    $data['message'] = lang('success');
                    //Công bổ sung
                    $info = $this->input->post('info');
                    if(!empty($info))
                    {
                    	foreach($info as $kInfo => $vInfo)
	                    {
		                    $arrayInfo = [
		                    	'id_product' => $id,
		                    	'id_info' => $kInfo,
		                    	'id_value' => $vInfo
		                    ];
	                    	$this->db->insert('tblproduct_group_info', $arrayInfo);
	                    }
                    }
                    //End công bổ sung
                } else {
                    if (file_exists($this->upload_path.''.$images)) {
                        @unlink($this->upload_path.''.$images);
                    }
                    if (!empty($uploadData)) {
                        foreach ($uploadData as $key => $value) {
                            if (file_exists($this->upload_path.''.$value)) {
                                unlink($this->upload_path.''.$value);
                            }
                        }
                    }
                    $data['result'] = 0;
                    $data['message'] = lang('fail');
                }
            } else {
                $data['result'] = 0;
                $data['message'] = validation_errors();
            }
            echo json_encode($data);
            return;
        } else {
            $data['custom_fields'] = $this->custom_fields;
            $data['units'] = $this->unit_model->getUnits();
            $data['boms'] = $this->products_model->getBoms();
            $data['warehouses'] = $this->site_model->getWarehouse();
            $data['procedure_detail'] = $this->site_model->getProcedureClientDetail('products');
            //Công bố sung
	        $data['kt'] = get_table_where('tblcombobox_client', ['type' => 'kt']);

	        $data['dt'] = get_table_where('tblcombobox_client', ['type' => 'dt']);

	        $data['info_client'] = get_table_where('tblclient_info_detail', ['view_modal' => 1, 'type_form !=' => 'input']);

	        foreach($data['info_client'] as $kInfo => $vInfo)
	        {
		        $data['info_client'][$kInfo]['detail'] = get_table_where('tblclient_info_detail_value', ['id_info_detail' => $vInfo['id']]);
	        }
	        //End công bổ sung

            $this->load->view('admin/products/add_item', $data);
        }
    }

    public function edit_product($id)
    {
        $data = [];
        $product = $this->products_model->rowProduct($id);
        if ($this->input->post())
        {
            $this->form_validation->set_rules('category', lang("tnh_item_materials_category"), 'required');
            $this->form_validation->set_rules('name', lang("name"), 'required');
            $this->form_validation->set_rules('unit', lang("unit"), 'required');
            if ($product['code'] != $this->input->post('code'))
            {
                $this->form_validation->set_rules('code', lang("code"), 'required|is_unique[tbl_materials.code]');
            }
            if ($this->form_validation->run() == true)
            {
                $images_old = $product['images'];
                $category = $this->input->post('category');
                $type_products = $this->input->post('type_products');
                $name = $this->input->post('name');
                $code = $this->input->post('code');
                $note = $this->input->post('note');
                $price_import = number_unformat($this->input->post('price_import'));
                $price_sell = number_unformat($this->input->post('price_sell'));
                $price_processing = number_unformat($this->input->post('price_processing'));
                $number_labor = number_unformat($this->input->post('number_labor'));
                $quantity_minimum = number_unformat($this->input->post('quantity_minimum'));
                $quantity_max = number_unformat($this->input->post('quantity_max'));
                $number_hours_ap = number_unformat($this->input->post('number_hours_ap'));
                $price_domestic = number_unformat($this->input->post('price_domestic'));
                $price_foreign = number_unformat($this->input->post('price_foreign'));
                $unit = $this->input->post('unit');
                $colors = $this->input->post('colors');
                $mode = $this->input->post('mode');
                $note = $this->input->post('note');
                $size = $this->input->post('size');
                $weight = $this->input->post('weight');
                $structure = $this->input->post('structure');
                $description = $this->input->post('description');

	            //Công bổ sung
	            $type_dt = $this->input->post('type_dt');
	            $type_kt = $this->input->post('type_kt');
	            $type_gender = $this->input->post('type_gender') ? $this->input->post('type_gender') : 0;
	            //end công bổ sung

                $options = [
                    'category_id' => $category,
                    'type_products' => $type_products,
                    'name' => $name,
                    'code' => $code,
                    'price_import' => $price_import,
                    'price_sell' => $price_sell,
                    'price_processing' => $price_processing,
                    'number_labor' => $number_labor,
                    'quantity_minimum' => $quantity_minimum,
                    'quantity_max' => $quantity_max,
                    'number_hours_ap' => $number_hours_ap,
                    'unit_id' => $unit,
                    'mode' => $mode,
                    'note' => $note,
                    'date_updated' => date('Y-m-d H:i:s'),
                    'updated_by' => get_staff_user_id(),
	                'type_dt' => $type_dt,
	                'type_kt' => $type_kt,
	                'type_gender' => $type_gender,
                    'size' => $size,
                    'weight' => $weight,
                    'structure' => $structure,
                    'description' => $description,
                    'price_domestic' => $price_domestic,
                    'price_foreign' => $price_foreign,
                ];

                $this->load->library('upload');
                if (!empty($_FILES['image']) && $_FILES['image']['size'] > 0) {
                    $config['upload_path'] = $this->upload_path;
                    $config['allowed_types'] = $this->image_types;
                    // $config['max_size'] = $this->allowed_file_size;
                    // $config['max_width'] = $this->Settings->iwidth;
                    // $config['max_height'] = $this->Settings->iheight;
                    $config['file_name'] = vn_to_str($code).'_'.$this->datetime_now;
                    $config['overwrite'] = TRUE;
                    $config['max_filename'] = 25;
                    $config['encrypt_name'] = false;
                    $this->upload->initialize($config);

                    if (!$this->upload->do_upload('image')) {
                        $error = $this->upload->display_errors();
                        $this->session->set_flashdata('error', $error);
                        $data['result'] = 0;
                        $data['message'] = $error;
                        echo json_encode($data);
                        return;
                    }
                    $images = $this->upload->file_name;
                    $options['images'] = $images;
                } else {
                    $options['images'] = $images_old;
                }

                //image multiple
                $images_multiple_old = $product['images_multiple'];
                $images_multiple_old_form = $this->input->post('images_old');
                if (!empty($_FILES['images_multiple']) && !empty($_FILES['images_multiple']['size'])) {
                    $fileCount = count($_FILES['images_multiple']['name']);
                    $ct = 0;
                    if (!empty($images_multiple_old)) {
                        $arr_images_multiple_old = explode('||', $images_multiple_old);
                        $image_last = $arr_images_multiple_old[count($arr_images_multiple_old) - 1];
                        $arr_last = explode('_', $image_last);
                        $ct = $arr_last[count($arr_last) - 2] + 1;
                    }
                    for ($i = 0; $i < $fileCount; $i++) {
                        $_FILES['file']['name'] = $_FILES['images_multiple']['name'][$i];
                        $_FILES['file']['type'] = $_FILES['images_multiple']['type'][$i];
                        $_FILES['file']['tmp_name'] = $_FILES['images_multiple']['tmp_name'][$i];
                        $_FILES['file']['error'] = $_FILES['images_multiple']['error'][$i];
                        $_FILES['file']['size'] = $_FILES['images_multiple']['size'][$i];

                        $config['upload_path'] = $this->upload_path;
                        $config['allowed_types'] = $this->image_types;
                        $config['file_name'] = vn_to_str($code).'_'.$ct.'_'.$this->datetime_now;
                        $config['overwrite'] = TRUE;
                        $config['max_filename'] = 25;
                        $config['encrypt_name'] = false;
                        $this->upload->initialize($config);
                        if ($this->upload->do_upload('file')) {
                            $uploadData[$i] = $this->upload->file_name;
                        }
                        $ct++;
                    }
                }
                if (!empty($uploadData)) {
                    if (!empty($images_multiple_old_form)) {
                        $options['images_multiple'] = implode('||', $images_multiple_old_form).'||'.implode('||', $uploadData);
                    } else {
                        $options['images_multiple'] = implode('||', $uploadData);
                    }
                } else {
                    if (!empty($images_multiple_old_form)) {
                        $options['images_multiple'] = implode('||', $images_multiple_old_form);
                    } else {
                        $options['images_multiple'] = null;
                    }
                }
                //end

                //handing warehouse locaiton
                $warehouses = $this->input->post('warehouses');
                if (!empty($warehouses)) {
                    foreach ($warehouses as $key => $value) {
                        if (empty($value)) continue;
                        $location_id = $this->input->post('location')[$key];
                        $productWarehouse[] = [
                            'product_id' => $id,
                            'warehouse_id' => $value,
                            'location_id' => $location_id,
                        ];
                    }
                }
                //end

                //handing suppliers
                $counter = $this->input->post('counter');
                if (!empty($counter)) {
                    foreach ($counter as $key => $value) {
                        $supplier_id = $this->input->post('suppliers')[$value];
                        if (empty($supplier_id)) continue;
                        $procedure_id = !empty($this->input->post('procedure')[$value]) ?  $this->input->post('procedure')[$value] : false;
                        // print_arrays()

                        if (!empty($procedure_id)) {
                            foreach ($procedure_id as $k => $val) {
                                $procedure_id = $this->input->post('procedure')[$value][$k];
                                $sequence = $this->input->post('sequence')[$value][$k];
                                $number_date = $this->input->post('number_date')[$value][$k];
                                $productSuppliers[] = [
                                    'product_id' => $id,
                                    'supplier_id' => $supplier_id,
                                    'procedure_id' => $procedure_id,
                                    'sequence' => $sequence,
                                    'number_date' => $number_date,
                                ];
                            }
                        } else {
                            $productSuppliers[] = [
                                'product_id' => $id,
                                'supplier_id' => $supplier_id,
                                'procedure_id' => 0,
                                'sequence' => 0,
                                'number_date' => 0,
                            ];
                        }
                    }
                }
                //end
                //handing boms
                $counter_bom = 0;
                $arr_id_bom = [];
                $using = $this->input->post('using');

                $product_versions = $this->input->post('product_versions');
                if (!empty($product_versions)) {
                    foreach ($product_versions as $key => $value) {
                        array_push($arr_id_bom, $value);
                        if ($counter_bom == $using) $options['versions'] = $value;
                        $counter_bom++;
                    }
                }
                $bs = $this->input->post('bs');
                if (!empty($bs)) {
                    foreach ($bs as $i => $el) {
                        $bom_id = $el;
                        $bom = $this->products_model->rowBomById($bom_id);
                        if (!empty($bom)) {
                            $fields[$i] = [
                                'versions' => $bom['versions'],
                                'product_id' => $id,
                                'bm_id' => $bom_id,
                                'status' => 'unapplication',
                                'date_start' => $bom['date_start'],
                                'date_end' => $bom['date_end'],
                                'date_created' => $bom['date_created'],
                                'created_by' => $bom['created_by'],
                            ];
                            $bom_element = $this->products_model->getBomsElementByBomId($bom_id);
                            foreach ($bom_element as $key => $value) {
                                $fields[$i]['element'][$key]['element_name'] = $value['element_name'];
                                $fields[$i]['element'][$key]['element_number'] = $value['quantity'];

                                $type = false;
                                if ($type_products == 'semi_products') $type = ['materials'];
                                $items = $this->products_model->getBomsElementItemsByBEI($value['id'], $type);
                                if (!empty($items)) {
                                    foreach ($items as $k => $val) {
                                        $fields[$i]['element'][$key]['items'][$k]['type'] = $val['type'];
                                        $fields[$i]['element'][$key]['items'][$k]['item_id'] = $val['item_id'];
                                        $fields[$i]['element'][$key]['items'][$k]['unit_id'] = $val['unit_id'];
                                        $fields[$i]['element'][$key]['items'][$k]['element_item_number'] = $val['quantity'];
                                    }
                                }
                            }
                            if ($counter_bom == $using) $options['versions'] = $bom['versions'];
                        }
                        $counter_bom++;
                    }
                }
                // print_arrays($options['versions']);
                //
                $up = $this->products_model->updateProducts($id, $options);
                if ($up) {
                    if (!empty($colors))
                    {
                        $this->products_model->deleteProductsColorsByProductId($id);
                        $cl = [];
                        foreach ($colors as $key => $value) {
                            $cl[] = [
                                'product_id' => $id,
                                'color_id' => $value,
                            ];
                        }
                        $this->products_model->insertBatchProductsColors($cl);
                    }
                    if (!empty($images)) {
                        if (file_exists($this->upload_path.''.$images_old)) {
                            @unlink($this->upload_path.''.$images_old);
                        }
                    }
                    if ($this->input->post('remove_image')) {
                        foreach (explode('||', $images_multiple_old) as $key => $value) {
                            if (!empty($images_multiple_old_form)) {
                                if (!in_array($value, $images_multiple_old_form)) {
                                    if (file_exists($this->upload_path.''.$value)) {
                                        @unlink($this->upload_path.''.$value);
                                    }
                                }
                            } else {
                                if (file_exists($this->upload_path.''.$value)) {
                                    @unlink($this->upload_path.''.$value);
                                }
                            }
                        }
                    }
                    if ($this->input->post('custom_fields')) {
                        handle_custom_fields_post($id, $this->input->post('custom_fields'));
                    }

                    $this->products_model->deleteProductSuppliersByProductId($id);
                    $this->products_model->deleteProductWarehouseByProductId($id);
                    //insert warehouse locaiton
                    if (!empty($productWarehouse)) {
                        $this->products_model->insertBatchProductWarehouse($productWarehouse);
                    }
                    //end
                    //insert material suppliers
                    if (!empty($productSuppliers)) {
                        $this->products_model->insertBatchProductSuppliers($productSuppliers);
                    }
                    //end

                    //up bom
                    $deleteBom = $this->products_model->getProductVersionsByNotIdAndProduct($arr_id_bom, $id);
                    if (!empty($deleteBom)) {
                        foreach ($deleteBom as $key => $value) {
                            $this->products_model->deleteBOOMById($value['id']);
                        }
                    }
                    if (!empty($fields)) {
                        foreach ($fields as $key => $field) {
                            if (!$this->products_model->checkProductVersions($id, $field['versions']))
                            {
                                $ib = $this->products_model->insertBOM($field, 'unapplication', 0, $actions = "add");
                            }
                        }
                    }
                    //

                    $data['result'] = 1;
                    $data['message'] = lang('success');

	                //Công bổ sung
	                $info = $this->input->post('info');
	                $array_not_delete = [];
	                if(!empty($info))
	                {
		                foreach($info as $kInfo => $vInfo)
		                {
			                $arrayInfo = [
				                'id_product' => $id,
				                'id_info' => $kInfo,
				                'id_value' => $vInfo
			                ];

			                $ktInfo = get_table_where('tblproduct_group_info', $arrayInfo, '', 'row');
			                if(!empty($ktInfo))
			                {
				                $array_not_delete[] = $ktInfo->id;
			                }
			                else
			                {
				                $this->db->insert('tblproduct_group_info', $arrayInfo);
				                if($this->db->insert_id())
				                {
					                $array_not_delete[] = $this->db->insert_id();
				                }
			                }
		                }
	                }
	                $this->db->where('id_product', $id);
	                if(!empty($array_not_delete))
	                {
	                	$this->db->where_not_in('id', $array_not_delete);
	                }
	                $this->db->delete('tblproduct_group_info');
	                //End công bổ sung



                } else {
                    if (file_exists($this->upload_path.''.$images)) {
                        unlink($this->upload_path.''.$images);
                    }
                    if (!empty($uploadData)) {
                        foreach ($uploadData as $key => $value) {
                            if (file_exists($this->upload_path.''.$value)) {
                                unlink($this->upload_path.''.$value);
                            }
                        }
                    }
                    $data['result'] = 0;
                    $data['message'] = lang('fail');
                }
            } else {
                $data['result'] = 0;
                $data['message'] = validation_errors();
            }
            echo json_encode($data);
            return;
        } else {
            $data['custom_fields'] = $this->custom_fields;
            $data['product'] = $product;
            $data['colors'] = $this->products_model->getColorsByProductId($id);
            $data['units'] = $this->unit_model->getUnits();
            $data['procedure_detail'] = $this->site_model->getProcedureClientDetail();
            $data['warehouses'] = $this->site_model->getWarehouse();
            $data['product_suppliers'] = $this->products_model->getGroupProductSuppliers($id);
            $data['product_warehouse'] = $this->products_model->getProductWarehouse($id);
            $data['boms_product'] = $this->products_model->getBomsProducts($id);
            $arr_bom_product = false;
            // if (!empty($data['boms_product'])) {
            //     foreach ($data['boms_product'] as $key => $value) {
            //         $arr_bom_product[] = trim($value['versions']);
            //     }
            // }
            $data['boms'] = $this->products_model->getBoms($arr_bom_product);
	        //Công bố sung
	        $data['kt'] = get_table_where('tblcombobox_client', ['type' => 'kt']);
	        $data['dt'] = get_table_where('tblcombobox_client', ['type' => 'dt']);
	        $data['info_client'] = get_table_where('tblclient_info_detail', ['view_modal' => 1, 'type_form !=' => 'input']);
	        foreach($data['info_client'] as $kInfo => $vInfo)
	        {
		        $data['info_client'][$kInfo]['detail'] = get_table_where('tblclient_info_detail_value', ['id_info_detail' => $vInfo['id']]);

		        $this->db->select('group_concat(id_value) as listVal');
		        $this->db->where('id_info', $vInfo['id']);
		        $this->db->where('id_product', $id);
		        $product_group_info = $this->db->get('tblproduct_group_info')->row();
		        $data['info_client'][$kInfo]['val'] = explode(',', $product_group_info->listVal);
	        }
	        //End công bổ sung
            $data['id'] = $id;
            $this->load->view('admin/products/edit_item', $data);
        }
    }

    function delete_product($id)
    {
        $data = [];
        if ($id) {
            $product = $this->products_model->rowProduct($id);
            if ($this->products_model->checkExistProducts($id)) {
                $data['result'] = 0;
                $data['message'] = lang('tnh_exist_not_delete');
                echo json_encode($data);
                return;
            }
            if ($this->products_model->deleteProducts($id)) {
                $this->products_model->deleteProductsColorsByProductId($id);
                $this->products_model->deleteProductsVersionsByProductId($id);
                $this->products_model->deleteProductStages($id);
                $this->products_model->deleteProductSuppliersByProductId($id);
                $this->products_model->deleteProductWarehouseByProductId($id);
                $this->site_model->deleteProductGroupInfo($id);
                deleteCustomFields('products', $id);
                if (!empty($product['images'])) {
                    if (file_exists($this->upload_path.''.$product['images'])) {
                        @unlink($this->upload_path.''.$product['images']);
                    }
                }
                if (!empty($product['images_multiple'])) {
                    foreach (explode('||', $product['images_multiple']) as $key => $value) {
                        if (file_exists($this->upload_path.''.$value)) {
                            @unlink($this->upload_path.''.$value);
                        }
                    }
                }
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

    function delete_products_multiple()
    {
        $data = [];
        if ($this->input->post()) {
            if (!$this->input->post('product_id')) {
                $data['result'] = 0;
                $data['message'] = lang('no_data_exists');
                echo json_encode($data); return;
            }
            $errors = '';
            $count = 0;
            foreach ($this->input->post('product_id') as $key => $id) {
                if ($this->products_model->checkExistProducts($id)) {
                    $row = $this->products_model->rowProduct($id);
                    $errors .= '<div class="text-danger">'.$row['code'].' '.lang('tnh_exist_not_delete').'</div>';
                    continue;
                }
                $product = $this->products_model->rowProduct($id);
                if ($this->products_model->deleteProducts($id)) {
                    $count++;
                    $this->products_model->deleteProductsColorsByProductId($id);
                    $this->products_model->deleteProductsVersionsByProductId($id);
                    $this->products_model->deleteProductStages($id);
                    $this->products_model->deleteProductSuppliersByProductId($id);
                    $this->products_model->deleteProductWarehouseByProductId($id);
                    $this->site_model->deleteProductGroupInfo($id);
                    deleteCustomFields('products', $id);
                    if (!empty($product['images'])) {
                        if (file_exists($this->upload_path.''.$product['images'])) {
                            @unlink($this->upload_path.''.$product['images']);
                        }
                        if (!empty($product['images_multiple'])) {
                            foreach (explode('||', $product['images_multiple']) as $key => $value) {
                                if (file_exists($this->upload_path.''.$value)) {
                                    @unlink($this->upload_path.''.$value);
                                }
                            }
                        }
                    }
                }
            }
            if ($count) {
                $data['result'] = 1;
                $data['message'] = lang('success');
            } else {
                $data['result'] = 0;
                $data['message'] = lang('fail');
            }
            $data['errors'] = $errors;
            echo json_encode($data); return;
        } else {
            $data['result'] = 0;
            $data['message'] = lang('fail');
        }
        echo json_encode($data);
    }

    public function category()
    {
        $data['tnh'] = true;
        $data['title'] = _l('tnh_category_product');
        $this->load->view('admin/products/category', $data);
    }

    public function add_category()
    {
        $data = [];
        if ($this->input->post())
        {
            $this->form_validation->set_rules('name', lang("name"), 'required');
            $this->form_validation->set_rules('code', lang("code"), 'required|is_unique[tbl_category_products.code]');
            if ($this->form_validation->run() == true)
            {
                $name = $this->input->post('name');
                $code = $this->input->post('code');
                $note = $this->input->post('note');
                $parent_id = $this->input->post('parent_id') ? $this->input->post('parent_id') : 0;

                $options = [
                    'name' => $name,
                    'code' => $code,
                    'parent_id' => $parent_id,
                    'note' => $note,
                ];

                $id = $this->products_model->insertCategoryProducts($options);
                if ($id) {
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
            return;
        } else {
            $this->load->view('admin/products/add_category', $data);
        }
    }

    public function edit_category($id)
    {
        $data = [];
        $category = $this->products_model->rowCategoryProducts($id);
        if ($this->input->post())
        {
            $this->form_validation->set_rules('name', lang("name"), 'required');
            if ($category['code'] != $this->input->post('code'))
            {
                $this->form_validation->set_rules('code', lang("code"), 'required|is_unique[tbl_category_products.code]');
            }
            if ($this->form_validation->run() == true)
            {
                $name = $this->input->post('name');
                $code = $this->input->post('code');
                $note = $this->input->post('note');
                $parent_id = $this->input->post('parent_id') ? $this->input->post('parent_id') : 0;

                $options = [
                    'name' => $name,
                    'code' => $code,
                    'parent_id' => $parent_id,
                    'note' => $note,
                ];

                $id = $this->products_model->updateCategoryProducts($id, $options);
                if ($id) {
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
            return;
        } else {
            $data['category'] = $category;
            $this->load->view('admin/products/edit_category', $data);
        }
    }

    function getCategory()
    {
        $this->datatables->select("
            tbl_category_products.id as id,
            0 as records,
            tbl_category_products.code as code,
            tbl_category_products.name as name,
            tbl_category_products.note as note,
            '' as sub
            ", FALSE)
        ->from('tbl_category_products');
        $this->datatables->where('tbl_category_products.parent_id', 0);

        $this->datatables->add_column('actions', '
            <div>
                <a class="tnh-modal btn btn-success btn-icon" data-tnh="modal" data-toggle="modal" data-target="#myModal" href="'.base_url().'admin/products/edit_category/$1"><i class="fa fa-pencil"></i></a>
                <button type="button" class="btn btn-danger po btn-icon" data-container="body" data-html="true" data-toggle="popover" data-placement="left" data-content="
                        <button href=\''.base_url('admin/products/delete_category/$1').'\' class=\'btn btn-danger po-delete-json\'>'.lang('delete').'</button>
                        <button class=\'btn btn-default po-close\'>'.lang('close').'</button>
                    "><i class="fa fa-remove"></i></button>
            </div>
        ', 'id');
        $result = json_decode($this->datatables->generate());
        foreach ($result->aaData as $key => $value) {
            $id = $value[0];
            $output = null;
            $result->aaData[$key][5] = $this->recursiveTableCategoryItems($output, $id);
        }
        echo (json_encode($result));
    }

    function recursiveTableCategoryItems(&$output = null, $parent_id = 0, $indent = null, $stt = 1) {

        $this->db->select('*');
        $this->db->from('tbl_category_products');
        $this->db->where('tbl_category_products.parent_id', $parent_id);
        $this->db->order_by('tbl_category_products.parent_id');
        $query = $this->db->get()->result_array();

        foreach ($query as $key => $item) {
            if ($item['parent_id'] == $parent_id) {
                $output .= '<tr>
                    <td>'. $indent .''. $item['code'] . '</td>
                    <td>'.$item['name'].'</td>
                    <td>'.$item['note'].'</td>
                    <td>
                        <div>
                        <a class="tnh-modal btn btn-success btn-icon" data-tnh="modal" data-toggle="modal" data-target="#myModal" href="'.base_url().'admin/products/edit_category/'.$item['id'].'"><i class="fa fa-pencil"></i></a>
                        <button type="button" class="btn btn-danger po btn-icon" data-container="body" data-html="true" data-toggle="popover" data-placement="bottom" data-content="
                        <button href=\''.base_url('admin/products/delete_category/'.$item['id']).'\' class=\'btn btn-danger po-delete-json\'>'.lang('delete').'</button>
                        <button class=\'btn btn-default po-close\'>'.lang('close').'</button>
                        "><i class="fa fa-remove"></i></button>
                        </div>
                    </td>
                </tr>';
                $this->recursiveTableCategoryItems($output, $item['id'], $indent . "|---", ++$stt);
            }
        }

        return $output;
    }

    function delete_category($id)
    {
        $data = [];
        if ($id) {
            if ($this->products_model->checkExistCategory($id)) {
                $data['result'] = 0;
                $data['message'] = lang('tnh_exist_not_delete');
                echo json_encode($data);
                return;
            }
            if ($this->products_model->checkParentId($id))
            {
                $data['result'] = 0;
                $data['message'] = lang('tnh_please_remove_sub_items');
                echo json_encode($data);
                die;
            }
            if ($this->products_model->deleteCategoryProducts($id)) {
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

    function delete_category_multiple()
    {
        $data = [];
        if ($this->input->post()) {
            if (!$this->input->post('category_id')) {
                $data['result'] = 0;
                $data['message'] = lang('no_data_exists');
                echo json_encode($data); return;
            }
            $errors = '';
            $count = 0;
            foreach ($this->input->post('category_id') as $key => $id) {
                if ($this->products_model->checkExistCategory($id)) {
                    $row = $this->products_model->rowCategoryProducts($id);
                    $errors .= '<div class="text-danger">'.$row['code'].' '.lang('tnh_exist_not_delete').'</div>';
                    continue;
                }
                if ($this->products_model->checkParentId($id))
                {
                    $row = $this->products_model->rowCategoryProducts($id);
                    $errors .= '<div class="text-danger">'.$row['code'].' '.lang('tnh_please_remove_sub_items').'</div>';
                    continue;
                }
                if ($this->products_model->deleteCategoryProducts($id)) {
                    $count++;
                }
            }
            if ($count) {
                $data['result'] = 1;
                $data['message'] = lang('success');
            } else {
                $data['result'] = 0;
                $data['message'] = lang('fail');
            }
            $data['errors'] = $errors;
            echo json_encode($data); return;
        } else {
            $data['result'] = 0;
            $data['message'] = lang('fail');
        }
        echo json_encode($data);
    }

    function searchCategory()
    {
        $data = [];
        if ($this->input->get())
        {
            $q = $this->input->get('q');
            $limit = 50;
            $data = $this->products_model->searchCategory($q, $limit);
        }
        echo json_encode($data);
    }

    public function colors()
    {
        $data['tnh'] = true;
        $data['title'] = _l('colors');
        $this->load->view('admin/products/colors', $data);
    }

    public function add_color()
    {
        $data = [];
        if ($this->input->post())
        {
            $this->form_validation->set_rules('name', lang("name"), 'required');
            $this->form_validation->set_rules('code', lang("code"), 'required|is_unique[tbl_colors.code]');
            if ($this->form_validation->run() == true)
            {
                $name = $this->input->post('name');
                $code = $this->input->post('code');
                $note = $this->input->post('note');
                $H_color = $this->input->post('color');

                $options = [
                    'name' => $name,
                    'code' => $code,
                    'color' => $H_color,
                    'note' => $note,
                ];

                $id = $this->products_model->insertColors($options);
                if ($id) {
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
            return;
        } else {
            $this->load->view('admin/products/add_color', $data);
        }
    }

    public function edit_color($id)
    {
        $data = [];
        $color = $this->products_model->rowColors($id);
        if ($this->input->post())
        {
            $this->form_validation->set_rules('name', lang("name"), 'required');
            if ($color['code'] != $this->input->post('code'))
            {
                $this->form_validation->set_rules('code', lang("code"), 'required|is_unique[tbl_colors.code]');
            }
            if ($this->form_validation->run() == true)
            {
                $name = $this->input->post('name');
                $code = $this->input->post('code');
                $H_color = $this->input->post('color');
                $note = $this->input->post('note');

                $options = [
                    'name' => $name,
                    'code' => $code,
                    'color' => $H_color,
                    'note' => $note,
                ];

                $id = $this->products_model->updateColors($id, $options);
                if ($id) {
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
            return;
        } else {
            $data['color'] = $color;
            $this->load->view('admin/products/edit_color', $data);
        }
    }

    function getColors()
    {
        $this->datatables->select("
            tbl_colors.id as id,
            tbl_colors.code as code,
            tbl_colors.name as name,
            tbl_colors.color as color,
            tbl_colors.note as note,
            ", FALSE)
        ->from('tbl_colors');

        $this->datatables->add_column('actions', '
            <div>
                <a class="tnh-modal btn btn-success btn-icon" data-tnh="modal" data-toggle="modal" data-target="#myModal" href="'.base_url().'admin/products/edit_color/$1"><i class="fa fa-pencil"></i></a>
                <button type="button" class="btn btn-danger po btn-icon" data-container="body" data-html="true" data-toggle="popover" data-placement="left" data-content="
                        <button href=\''.base_url('admin/products/delete_colors/$1').'\' class=\'btn btn-danger po-delete-json\'>'.lang('delete').'</button>
                        <button class=\'btn btn-default po-close\'>'.lang('close').'</button>
                    "><i class="fa fa-remove"></i></button>
            </div>
        ', 'id');
        $result = json_decode($this->datatables->generate());
        echo (json_encode($result));
    }

    function delete_colors($id)
    {
        $data = [];
        if ($id) {
            if ($this->products_model->checkExistColors($id)) {
                $data['result'] = 0;
                $data['message'] = lang('tnh_exist_not_delete');
                echo json_encode($data);
                return;
            }
            if ($this->products_model->deleteColors($id)) {
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

    function searchColors()
    {
        $data = [];
        if ($this->input->get())
        {
            $q = $this->input->get('q');
            $limit = 50;
            $data = $this->products_model->searchColors($q, $limit);
        }
        echo json_encode($data);
    }

    function searchSemiProducts()
    {
        $data = [];
        if ($this->input->get())
        {
            $q = $this->input->get('q');
            $limit = 50;
            $data = $this->products_model->searchSemiProducts($q, $limit);
        }
        echo json_encode($data);
    }

    function searchSelect2SemiProducts($id = false)
    {
        $data = [];
        // if ($this->input->get())
        // {
            $term = $this->input->get('term');
            $limit = 50;
            $data['results'] = $this->products_model->searchSelect2SemiProducts($term, $limit);
            if ($id) {
                $product = $this->products_model->rowProduct($id);
                $data['row'] = ['id' => $product['id'], 'text' => $product['name'].'('.$product['code'].')'];
            }
        // }
        echo json_encode($data);
    }

    function searchSelect2SemiProductsOutside($id = false)
    {
        $data = [];
        // if ($this->input->get())
        // {
            $term = $this->input->get('term');
            $limit = 50;
            $data['results'] = $this->products_model->searchSelect2SemiProducts($term, $limit, 'semi_products_outside');
            if ($id) {
                $product = $this->products_model->rowProduct($id);
                $data['row'] = ['id' => $product['id'], 'text' => $product['name'].'('.$product['code'].')'];
            }
        // }
        echo json_encode($data);
    }

    function change_versions()
    {
        $data = [];
        if ($this->input->post())
        {
            $product_id = $this->input->post('product_id');
            $material_bom = $this->input->post('material_bom');
            if ($this->products_model->updateProducts($product_id, ['versions' => $material_bom])) {
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

    //stages
    public function stages()
    {
        $data['tnh'] = true;
        $data['title'] = _l('stages');
        $this->load->view('admin/products/stages', $data);
    }

    public function add_stage()
    {
        $data = [];
        if ($this->input->post())
        {
            $this->form_validation->set_rules('name', lang("name"), 'required');
            $this->form_validation->set_rules('code', lang("code"), 'required|is_unique[tbl_stages.code]');
            if ($this->form_validation->run() == true)
            {
                $name = $this->input->post('name');
                $code = $this->input->post('code');
                $note = $this->input->post('note');

                $options = [
                    'name' => $name,
                    'code' => $code,
                    'note' => $note,
                    'parent_id' => 0
                ];

                $id = $this->products_model->insertStages($options);
                if ($id) {
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
            return;
        } else {
            $this->load->view('admin/products/add_stage', $data);
        }
    }

    public function edit_stage($id)
    {
        $data = [];
        $stage = $this->products_model->rowStages($id);
        if (empty($stage)) {
            $data['result'] = 0;
            $data['message'] = lang('no_data_exists');
            echo json_encode($data);
            return;
        }
        if ($this->input->post())
        {
            $this->form_validation->set_rules('name', lang("name"), 'required');
            if ($stage['code'] != $this->input->post('code'))
            {
                $this->form_validation->set_rules('code', lang("code"), 'required|is_unique[tbl_stages.code]');
            }
            if ($this->form_validation->run() == true)
            {
                $name = $this->input->post('name');
                $code = $this->input->post('code');
                $note = $this->input->post('note');

                $options = [
                    'name' => $name,
                    'code' => $code,
                    'note' => $note,
                ];

                $id = $this->products_model->updateStages($id, $options);
                if ($id) {
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
            return;
        } else {
            $data['stage'] = $stage;
            $this->load->view('admin/products/edit_stage', $data);
        }
    }

    public function add_stage_sub($id)
    {
        $data = [];
        $stage = $this->products_model->rowStages($id);
        if (empty($stage)) {
            $data['result'] = 0;
            $data['message'] = lang('no_data_exists');
            echo json_encode($data);
            return;
        }
        if ($this->input->post())
        {
            $this->form_validation->set_rules('name', lang("name"), 'required');
            $this->form_validation->set_rules('code', lang("code"), 'required|is_unique[tbl_stages.code]');
            if ($this->form_validation->run() == true)
            {
                $name = $this->input->post('name');
                $code = $this->input->post('code');
                $departments = $this->input->post('departments');
                $number_hours = number_unformat($this->input->post('number_hours'));
                $sequence = $this->input->post('sequence');
                $note = $this->input->post('note');

                $options = [
                    'name' => $name,
                    'code' => $code,
                    'note' => $note,
                    'departments_id' => $departments,
                    'number_hours' => $number_hours,
                    'sequence' => $sequence,
                    'parent_id' => $id
                ];

                $id = $this->products_model->insertStages($options);
                if ($id) {
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
            return;
        } else {
            $data['departments'] = $this->departments_model->getDepartments();
            $data['stage'] = $stage;
            $data['id'] = $id;
            $this->load->view('admin/products/add_stage_sub', $data);
        }
    }

    public function edit_stage_sub($id)
    {
        $data = [];
        $stage = $this->products_model->rowStages($id);
        if (empty($stage)) {
            $data['result'] = 0;
            $data['message'] = lang('no_data_exists');
            echo json_encode($data);
            return;
        }
        if ($this->input->post())
        {
            $this->form_validation->set_rules('name', lang("name"), 'required');
            if ($stage['code'] != $this->input->post('code'))
            {
                $this->form_validation->set_rules('code', lang("code"), 'required|is_unique[tbl_stages.code]');
            }
            if ($this->form_validation->run() == true)
            {
                $name = $this->input->post('name');
                $code = $this->input->post('code');
                $note = $this->input->post('note');
                $departments = $this->input->post('departments');
                $number_hours = number_unformat($this->input->post('number_hours'));
                $sequence = $this->input->post('sequence');

                $options = [
                    'name' => $name,
                    'code' => $code,
                    'note' => $note,
                    'departments_id' => $departments,
                    'number_hours' => $number_hours,
                    'sequence' => $sequence,
                ];

                $id = $this->products_model->updateStages($id, $options);
                if ($id) {
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
            return;
        } else {
            $data['departments'] = $this->departments_model->getDepartments();
            $data['stage'] = $stage;
            $this->load->view('admin/products/edit_stage_sub', $data);
        }
    }

    function getStages()
    {
        $this->db->simple_query('SET SESSION group_concat_max_len=42949672895');
        // $sub = "(
        //     SELECT
        //         tbl_stages.parent_id,
        //         GROUP_CONCAT(
        //             CONCAT(tbl_stages.id, '||', tbl_stages.code, '||', tbl_stages.name, '||', tbl_stages.note)
        //             SEPARATOR '____'
        //         ) as items
        //     FROM tbl_stages
        //     WHERE tbl_stages.parent_id != 0
        //     GROUP BY tbl_stages.parent_id
        // ) as sub";
        $this->datatables->select("
            tbl_stages.id as id,
            tbl_stages.id as records,
            tbl_stages.code as code,
            tbl_stages.name as name,
            tbl_stages.note as note,
            '' as items
            ", FALSE)
        ->from('tbl_stages');
        // ->join($sub, 'sub.parent_id = tbl_stages.id', 'left');
        $this->datatables->where('tbl_stages.parent_id', 0);

        $add_sub = '<a class="tnh-modal btn btn-primary btn-icon" data-tnh="modal" data-toggle="modal" data-target="#myModal" href="'.base_url().'admin/products/add_stage_sub/$1"><i class="fa fa-plus"></i></a>';

        $edit = '<a class="tnh-modal btn btn-success btn-icon" data-tnh="modal" data-toggle="modal" data-target="#myModal" href="'.base_url().'admin/products/edit_stage/$1"><i class="fa fa-pencil"></i></a>';

        $delete = '<button type="button" class="btn btn-danger po btn-icon" data-container="body" data-html="true" data-toggle="popover" data-placement="left" data-content="
                        <button href=\''.base_url('admin/products/delete_stage/$1').'\' class=\'btn btn-danger po-delete-json\'>'.lang('delete').'</button>
                        <button class=\'btn btn-default po-close\'>'.lang('close').'</button>
                    "><i class="fa fa-remove"></i></button>';

        $this->datatables->add_column('actions', '
            <div>
                '.$add_sub.'
                '.$edit.'
                '.$delete.'
            </div>
        ', 'id');
        $data = json_decode($this->datatables->generate());
        foreach ($data->aaData as $key => $value) {
            $id = $value[0];
            $this->db->select('tbl_stages.*, tbldepartments.name as departments_name')
                    ->from('tbl_stages')
                    ->join('tbldepartments', 'tbldepartments.departmentid = tbl_stages.departments_id', 'left')
                    ->where('tbl_stages.parent_id =', $id);
            $this->db->order_by('tbl_stages.sequence ASC');
            $sub = $this->db->get()->result_array();
            $data->aaData[$key][5] = $sub;
        }
        echo (json_encode($data));
    }

    function searchStages()
    {
        $data = [];
        if ($this->input->get())
        {
            $q = $this->input->get('q');
            $limit = 50;
            $data = $this->products_model->searchStages($q, $limit);
        }
        echo json_encode($data);
    }

    function delete_stage($id)
    {
        $data = [];
        if ($id) {
            // if (!$this->products_model->checkExistCategory($id)) {
            //     $data['result'] = 0;
            //     $data['message'] = lang('tnh_exist_not_delete');
            //     echo json_encode($data);
            //     return;
            // }
            if (!empty($this->products_model->checkStagesByParentId($id))) {
                $data['result'] = 0;
                $data['message'] = lang('tnh_please_remove_sub_items');
                echo json_encode($data);
                die;
            }
            if ($this->products_model->checkStagesExist($id)) {
                $data['result'] = 0;
                $data['message'] = lang('tnh_exist_not_delete');
                echo json_encode($data);
                die;
            }
            if ($this->products_model->deleteStages($id)) {
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

    function check_product_stages()
    {
        $product_id = $this->input->post('product_id');
        $versions = trim($this->input->post('versions'));
        if ($this->products_model->checkProductStages($product_id, $versions)) {
            $this->form_validation->set_message('check_versions', lang('exists_versions_products'));
            return false;
        } else {
            return true;
        }
    }

    function design_stages($id, $vs_stage_id = false)
    {
        $products = $this->products_model->rowProduct($id);
        if ($products['type_products'] == 'semi_products_outside') {
            refererModel(lang('semi_products_outside_not_design_stages'));
        }
        if (!empty($vs_stage_id)) {
            $stage = $this->products_model->rowProductStagesById($vs_stage_id);
        }
        if ($this->input->post())
        {
            $this->form_validation->set_rules('product_id', lang("id"), 'required');
            if (empty($stage) || ($stage['product_id'] != $this->input->post('product_id') && $stage['versions'] != $this->input->post('versions'))) {
                $this->form_validation->set_rules('versions', lang("tnh_versions"), 'required|callback_check_product_stages');
            }
            if ($this->form_validation->run() == true)
            {
                // print_arrays($this->input->post());
                $status = "unapplication";
                $versions = trim($this->input->post('versions'));
                $product_id = $this->input->post('product_id');
                $i_stage = $this->input->post('i_stage');
                $stage = $this->input->post('stage');
                $options['versions'] = $versions;
                $options['product_id'] = $product_id;
                foreach ($i_stage as $key => $value) {
                    $stage = $this->input->post('stage')[$key];
                    $number = $this->input->post('number')[$key];
                    $number_hours = number_unformat($this->input->post('number_hours')[$key]);
                    $machines = !empty($this->input->post('machines')[$value]) ? implode(',', $this->input->post('machines')[$value]) : NULL;
                    $options['items'][$key]['stage'] = $stage;
                    $options['items'][$key]['number'] = $number;
                    $options['items'][$key]['number_hours'] = $number_hours;
                    $options['items'][$key]['machines'] = $machines;
                }
                // print_arrays($options);
                $q = $this->products_model->insertProductStages($options, $status, $vs_stage_id);
                if ($q) {
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
            return;
        } else {
            $data['id'] = $id;
            $html_stages = '';
            if (!empty($vs_stage_id)) {
                $items = $this->products_model->getProductStagesVersions($vs_stage_id);
                foreach ($items as $key => $value) {
                    $html_stages.= '<tr class="sortable item">
                                        <input type="hidden" name="i_stage[]" id="i_stage" class="form-control i_stage" value="'.$key.'">
                                        <input type="hidden" name="number[]" id="number" class="form-control number" value="'.$value['number'].'">
                                        <td class="stt text-center dragger">'.$value['number'].'</td>

                                        <td>
                                            <select name="stage[]"  data-live-search="true" data-none-selected-text="'.lang('choose').'" id="stage_'.$key.'" class="form-control" required="required">
                                                <option value="'.$value['stage_id'].'" selected>'.$value['stage_name'].'</option>
                                            </select>
                                        </td>
                                        <td>';
                    $options = '';
                    if (!empty($value['machines'])) {
                        $machines = $this->category_model->getMachinesByArrId(explode(',', $value['machines']));
                        if (!empty($machines)) {
                            foreach ($machines as $i => $v) {
                                $options.= '<option value="'.$v['id'].'" selected>'.$v['name'].'</option>';
                            }
                        }
                    }
                    $html_stages.= '
                                            <select name="machines['.$key.'][]"  data-live-search="true" data-none-selected-text="'.lang('tnh_machines').'" id="machines_'.$key.'" class="form-control ajax-search">\
                                                <option value=""></option>\
                                                '.$options.'
                                            </select>';
                    $html_stages.=     '</td>
                                        <td>
                                            <input type="number" name="number_hours[]" id="input" class="form-control" value="'.$value['number_hours'].'" title="">
                                        </td>
                                        <td>
                                            <div class="text-center"><i class="btn btn-danger fa fa-remove remove-stage"></i></div>
                                        </td>
                                    </tr>';
                }
                $data['items'] = $items;
                $data['stage'] = $stage;
            }

            $list_stages = recursive_stages();
            $data['list_stages'] = $list_stages;
            $data['html_stages'] = $html_stages;
            $this->load->view('admin/products/design_stages', $data);
        }
    }

    public function delete_product_stage($id) {
        $data = [];
        if(!empty($id)) {
            if ($this->products_model->deleteProductStagesById($id))
            {
                $data['result'] = 1;
                $data['message'] = lang('success');
                $data['table'] = $id;
                $data['type'] = 'stages';
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

    function change_versions_stages()
    {
        $data = [];
        if ($this->input->post())
        {
            $product_id = $this->input->post('product_id');
            $vs_stage = $this->input->post('vs_stage');
            if ($this->products_model->updateProducts($product_id, ['versions_stage' => $vs_stage])) {
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


    function gen_barcode($product_code = NULL, $bcs = 'code128', $height = 30, $text = 1)
    {
        ob_end_clean();
        $drawText = ($text != 1) ? FALSE : TRUE;
        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');
        $barcodeOptions = array('text' => $product_code, 'barHeight' => $height, 'drawText' => $drawText);
        $rendererOptions = array('horizontalPosition' => 'center', 'verticalPosition' => 'middle');
        Zend_Barcode::render('code128', 'image', $barcodeOptions, $rendererOptions);
    }

    function bom()
    {
        $data['tnh'] = true;
        $data['title'] = _l('tnh_bom');
        $this->load->view('admin/products/bom', $data);
    }

    function getBom()
    {

        $BOM = "(
            SELECT
                tbl_product_versions.product_id,
                GROUP_CONCAT(tbl_product_versions.versions SEPARATOR ':::') as versions
            FROM tbl_product_versions
            GROUP BY tbl_product_versions.product_id
        ) as BOM";

        $this->datatables->select("
            tbl_products.id as id,
            '0' as records,
            tbl_products.code as code,
            tbl_products.name as name,
            BOM.versions as bm,
            tblunits.unit as unit_name,
            1 as quantity_productions,
            tbl_products.versions as vs,
            ", FALSE)
        ->from('tbl_products')
        ->join('tblunits', 'tblunits.unitid = tbl_products.unit_id', 'left')
        ->join($BOM, 'BOM.product_id = tbl_products.id', 'left');

        $this->datatables->where('tbl_products.versions !=', NULL);
        $iDisplayStart = $this->input->post('iDisplayStart');
        $data = json_decode($this->datatables->generate());
        // foreach ($data->aaData as $key => $value) {
        //     $data->aaData[$key][1] = ++$iDisplayStart;
        // }
        echo json_encode($data);
    }

    function show_bom()
    {
        $product_id = $this->input->post('product_id');
        $vs = $this->input->post('vs');
        $quantity = $this->input->post('quantity');
        if (empty($product_id) || empty($vs)) {
            echo lang('not_data_exists');
            die;
        }
        $html_bom = '';
        $version = $this->products_model->getBomByProductIdAndVersions($product_id, $vs);
        if (!empty($version)) {
            $elements = $this->products_model->getVersionsElementByVersionId($version['id']);
            $number_element = 1;
            foreach ($elements as $key => $value) {
                $quantity_element = $quantity * $value['quantity'];
                $html_bom .= '<tr>';
                $html_bom .= '<td class="">'.$number_element.'</td>';
                $html_bom .= '<td>'.$value['element_name'].'</td>';
                $html_bom .= '<td>'.$value['element_name'].'</td>';
                $html_bom .= '<td></td>';
                $html_bom .= '<td>'.lang('tnh_element').'</td>';
                $html_bom .= '<td class="text-center">'.$quantity_element.'</td>';
                $html_bom .= '</tr>';

                $items = $this->products_model->getElementItemsByElementId($value['id']);
                $number_item = 1;
                foreach ($items as $k => $val) {
                    if ($val['type'] == "semi_products") {
                        $info = $this->products_model->rowProduct($val['item_id']);
                    } else {
                        $info = $this->items_model->rowMaterial($val['item_id']);
                    }
                    $quantity_item = $value['quantity'] * $quantity * $val['quantity'];
                    $unit = $this->unit_model->rowUnit($val['unit_id']);
                    $html_bom .= '<tr>';
                    $html_bom .= '<td class="">'.$number_element.'.'.$number_item.'</td>';
                    $html_bom .= '<td>'.$info['code'].'</td>';
                    $html_bom .= '<td>'.$info['name'].'</td>';
                    $html_bom .= '<td>'.$unit['unit'].'</td>';
                    $html_bom .= '<td>'.lang($val['type']).'</td>';
                    $html_bom .= '<td class="text-center">'.$quantity_item.'</td>';
                    $html_bom .= '</tr>';
                    $number_item++;
                }
                $number_element++;
            }
        }
        $data['product_id'] = $product_id;
        $data['html_bom'] = $html_bom;
        $this->load->view('admin/products/show_bom', $data);
    }

    function print_bom()
    {
        $arr_id = $this->input->post('arr_id');
        $arr_vs = $this->input->post('arr_vs');
        $arr_quantity = $this->input->post('arr_quantity');
        $html_bom = '';
        if (!empty($arr_id))
        {
            $number_product = 1;
            foreach ($arr_id as $key => $value) {
                $product_id = $value;
                $vs = $arr_vs[$key];
                $quantity = $arr_quantity[$key];

                $product = $this->products_model->rowProduct($product_id);
                $unit = $this->unit_model->rowUnit($product['unit_id']);

                $html_bom .= '<tr>';
                $html_bom .= '<td class="">'.$number_product.'</td>';
                $html_bom .= '<td>'.$product['code'].'</td>';
                $html_bom .= '<td>'.$product['name'].'</td>';
                $html_bom .= '<td>'.$vs.'</td>';
                $html_bom .= '<td>'.$unit['unit'].'</td>';
                $html_bom .= '<td>'.lang('products').'</td>';
                $html_bom .= '<td style="text-align: center;" class="text-center">'.$quantity.'</td>';
                $html_bom .= '</tr>';

                $version = $this->products_model->getBomByProductIdAndVersions($product_id, $vs);
                if (!empty($version)) {
                    $elements = $this->products_model->getVersionsElementByVersionId($version['id']);
                    $number_element = 1;
                    foreach ($elements as $key => $value) {
                        $quantity_element = $quantity * $value['quantity'];
                        $html_bom .= '<tr>';
                        $html_bom .= '<td class="">'.$number_product.'.'.$number_element.'</td>';
                        $html_bom .= '<td>'.$value['element_name'].'</td>';
                        $html_bom .= '<td>'.$value['element_name'].'</td>';
                        $html_bom .= '<td></td>';
                        $html_bom .= '<td></td>';
                        $html_bom .= '<td>'.lang('tnh_element').'</td>';
                        $html_bom .= '<td style="text-align: center;" class="text-center">'.$quantity_element.'</td>';
                        $html_bom .= '</tr>';

                        $items = $this->products_model->getElementItemsByElementId($value['id']);
                        $number_item = 1;
                        foreach ($items as $k => $val) {
                            if ($val['type'] == "semi_products") {
                                $info = $this->products_model->rowProduct($val['item_id']);
                            } else {
                                $info = $this->items_model->rowMaterial($val['item_id']);
                            }
                            $quantity_item = $value['quantity'] * $quantity * $val['quantity'];
                            $unit = $this->unit_model->rowUnit($val['unit_id']);
                            $html_bom .= '<tr>';
                            $html_bom .= '<td class="">'.$number_product.'.'.$number_element.'.'.$number_item.'</td>';
                            $html_bom .= '<td>'.$info['code'].'</td>';
                            $html_bom .= '<td>'.$info['name'].'</td>';
                            $html_bom .= '<td></td>';
                            $html_bom .= '<td>'.$unit['unit'].'</td>';
                            $html_bom .= '<td>'.lang($val['type']).'</td>';
                            $html_bom .= '<td style="text-align: center;" class="text-center">'.$quantity_item.'</td>';
                            $html_bom .= '</tr>';
                            $number_item++;
                        }
                        $number_element++;
                    }
                }
                $number_product++;
            }
        }
        $data['html_bom'] = $html_bom;
        $this->load->view('admin/products/print_bom', $data);
    }

    function export_excel_category()
    {
        if ($this->input->post('export_excel'))
        {
            ini_set('memory_limit', '3500M');
            include APPPATH . 'third_party/PHPExcel/PHPExcel.php';
            $this->load->library('PHPExcel');

            // print_arrays($this->input->post());
            $cloumns = $this->input->post('cloumns');
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

            $this->db->select(implode(',', $cloumns), false);
            $this->db->from('tbl_category_products');
            $data = $this->db->get()->result_array();

            foreach($cloumns as $key => $value)
            {
                $objPHPExcel->getActiveSheet()->getColumnDimension($cloumns_excel[$key])->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->SetCellValue($cloumns_excel[$key].'1', _l('tnh_'.$value))->getStyle($cloumns_excel[$key].'1')->applyFromArray($style_excel['Background_header']);
            }

            $row = 2;
            foreach ($data as $key => $value) {
                foreach ($cloumns as $k => $val) {
                    $index = $cloumns_excel[$k].$row;
                    $el = $value[$val];
                    $objPHPExcel->getActiveSheet()->SetCellValue($index, $el)->getStyle($index)->applyFromArray($style_excel['BStyle']);
                }
                $row++;
            }

            $filename = lang('tnh_category_product').'.xls';
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
            die(json_encode($response));
        } else {
            $data['link'] = 'admin/products/export_excel_category';
            $list = [];
            $fields = get_fields_export($table = 'tbl_category_products', $arr_diff = false);
            foreach ($fields as $key => $value) {
                $list[] = [$value => mb_strtoupper(_l('tnh_' . $value), 'UTF-8')];
            }
            $data['list'] = $list;
            $this->load->view('admin/export_excel/export_excel', $data);
        }
    }

    function export_excel_products()
    {
        if ($this->input->post('export_excel'))
        {
            ini_set('memory_limit', '3500M');
            include APPPATH . 'third_party/PHPExcel/PHPExcel.php';
            $this->load->library('PHPExcel');

            // print_arrays($this->input->post());
            $cloumns = $this->input->post('cloumns');
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

            $select = '';
            $left_join = '';

            foreach($cloumns as $key => $value)
            {
                $objPHPExcel->getActiveSheet()->getColumnDimension($cloumns_excel[$key])->setAutoSize(true);

                //custom fields
                $custom = false;
                if (!empty($this->custom_fields)) {
                    foreach ($this->custom_fields as $k => $val) {
                        if ($value == ('custom_fields_'.$val['fieldto'].'_'.$val['id']))
                        {

                            $objPHPExcel->getActiveSheet()->SetCellValue($cloumns_excel[$key].'1', $val['name'])->getStyle($cloumns_excel[$key].'1')->applyFromArray($style_excel['Background_header']);

                            $value = $val['slug'];
                            $cloumns[$key] = $value;
                            $select.= "COALESCE((
                                        SELECT tblcustomfieldsvalues.value
                                        FROM tblcustomfieldsvalues
                                        WHERE tblcustomfieldsvalues.fieldto = 'products' AND tblcustomfieldsvalues.relid = tbl_products.id AND tblcustomfieldsvalues.fieldid = ".$val['id']."
                                    ), '') as $value, ";
                            $custom = true;
                            break;
                        }
                    }
                }
                if ($custom == true) continue;
                //end custom fields

                $objPHPExcel->getActiveSheet()->SetCellValue($cloumns_excel[$key].'1', _l('tnh_'.$value))->getStyle($cloumns_excel[$key].'1')->applyFromArray($style_excel['Background_header']);

                if ($value == "category_id")
                {
                    $value = 'category_name';
                    $cloumns[$key] = $value;
                    $select.= "tbl_category_products.name as $value, ";
                    $left_join.= " LEFT JOIN tbl_category_products ON tbl_category_products.id = tbl_products.category_id";
                }
                else if ($value == "unit_id")
                {
                    $value = 'unit_name';
                    $cloumns[$key] = $value;
                    $select.= "tblunits.unit as $value, ";
                    $left_join.= " LEFT JOIN tblunits ON tblunits.unitid = tbl_products.unit_id";
                }
                else if ($value == "versions_bom")
                {
                    $value = 'versions_bom';
                    $cloumns[$key] = $value;
                    $select.= "tbl_products.versions as $value, ";
                }
                else if ($value == "colors")
                {
                    $colors = "(
                        SELECT
                            tbl_products_colors.product_id,
                            GROUP_CONCAT(tbl_colors.name SEPARATOR '\n') as color_name
                        FROM tbl_products_colors
                        INNER JOIN tbl_colors ON tbl_products_colors.color_id = tbl_colors.id
                        GROUP BY tbl_products_colors.product_id
                    ) as colors";

                    $value = 'color_name';
                    $cloumns[$key] = $value;
                    $select.= "colors.color_name as $value, ";
                    $left_join.= " LEFT JOIN $colors ON colors.product_id = tbl_products.id";
                }
                else
                {
                    $select.= "tbl_products.$value, ";
                }
            }

            $select = trim($select);
            $select = substr($select, 0, -1);
            $query = "
                SELECT $select
                FROM tbl_products
                $left_join
            ";
            $data = $this->db->query($query)->result_array();
            // print_arrays($data);
            $row = 2;
            if (!empty($data)) {
                foreach ($data as $key => $value) {
                    foreach ($cloumns as $k => $val) {
                        $index = $cloumns_excel[$k].$row;
                        $el = $value[$val];
                        if ($val == "price_import" || $val == "price_sell" || $val == "price_processing") {
                            $objPHPExcel->getActiveSheet()->SetCellValue($index, $el)->getStyle($index)->applyFromArray($style_excel['BStyle']);
                            $objPHPExcel->getActiveSheet()->getStyle($index)->getNumberFormat()->setFormatCode('#,##0.00');
                        }
                        else if ($val == "images")
                        {
                            $objPHPExcel->getActiveSheet()->SetCellValue($index, !empty($el) ? base_url('uploads/materials/').''.$el : '')->getStyle($index)->applyFromArray($style_excel['BStyle']);
                        }
                        else if ($val == "type_products")
                        {
                            $objPHPExcel->getActiveSheet()->SetCellValue($index, lang($el))->getStyle($index)->applyFromArray($style_excel['BStyle']);
                        }
                        else {
                            $objPHPExcel->getActiveSheet()->SetCellValue($index, $el)->getStyle($index)->applyFromArray($style_excel['BStyle']);
                        }
                    }
                    $row++;
                }
            }

            $filename = lang('products').'.xls';
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
            die(json_encode($response));
        } else {
            $data['link'] = 'admin/products/export_excel_products';
            $list = [];
            $fields = get_fields_export($table = 'tbl_products',
                $arr_diff = [
                ],
                $arr_more = [
                    'colors'
                ]
            );
            foreach ($fields as $key => $value) {
                if ($value == "versions") $value = "versions_bom";
                $list[] = [$value => mb_strtoupper(_l('tnh_' . $value), 'UTF-8')];
            }
            foreach ($this->custom_fields as $key => $value) {
                $list[] = ['custom_fields_'.$value['fieldto'].'_'.$value['id'] => mb_strtoupper($value['name'], 'UTF-8')];
            }
            $data['list'] = $list;
            $this->load->view('admin/export_excel/export_excel', $data);
        }
    }

    public function import_category()
    {
        if ($this->input->post('save'))
        {
            $data = [];
            include APPPATH . 'third_party/PHPExcel/PHPExcel.php';
            $this->load->library('PHPExcel');

            $fullfile = $_FILES['file']['tmp_name'];
            if (empty($fullfile)) {
                $data['result'] = 0;
                $data['message'] = lang('tnh_file_not_required');
                echo json_encode($data); return;
            }
            $extension = strtoupper(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
            if($extension != 'XLSX' && $extension != 'XLS'){
                $data['result'] = 0;
                $data['message'] = lang('tnh_not_format_excel');
                echo json_encode($data); return;
            }

            if (empty($fullfile)) {
                $data['result'] = 0;
                $data['message'] = lang('tnh_file_not_required');
                echo json_encode($data); return;
            }

            if (!$this->input->post('fields')) {
                $data['result'] = 0;
                $data['message'] = lang('tnh_fields_not_required');
                echo json_encode($data); return;
            }
            // if ($_FILES['userfile']['size'] > $this->allowed_file_size * 1024) {
            //     $this->session->set_flashdata('warning', lang('Không vượt quá '. $this->allowed_file_size. ' size'));
            //     redirect($_SERVER["HTTP_REFERER"]);
            //     return;
            // }
            $inputFileType  = PHPExcel_IOFactory::identify($fullfile);
            $objReader      = PHPExcel_IOFactory::createReader($inputFileType);
            $objReader->setReadDataOnly(true);

            /**  Load $inputFileName to a PHPExcel Object  **/
            $objPHPExcel = $objReader->load("$fullfile");

            $total_sheets = $objPHPExcel->getSheetCount();

            $allSheetName       = $objPHPExcel->getSheetNames();
            $objWorksheet       = $objPHPExcel->setActiveSheetIndex(0);
            $highestRow         = $objWorksheet->getHighestRow();
            $highestColumn      = $objWorksheet->getHighestColumn();
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            $arraydata          = array();

            $row_start = $this->input->post('row_start') ? $this->input->post('row_start') : 2;
            $row_end = $this->input->post('row_end') ? $this->input->post('row_end') : $highestRow;
            $cloumn_excel = $this->input->post('cloumn_excel');
            $fields = $this->input->post('fields');
            for ($row = $row_start; $row <= $row_end; ++$row) {
                for ($col = 0; $col < $highestColumnIndex; ++$col) {
                    // $value = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                    if (!empty($cloumn_excel[$col]))
                    {
                        $cloumn_current = $cloumn_excel[$col];
                        $index_current = $fields[$col];
                    } else {
                        continue;
                    }
                    $value = $objWorksheet->getCell($cloumn_current.$row)->getValue();
                    $arraydata[$row][$index_current] = $value;
                }
            }
            $options = [];
            $count = 0;
            $errors = '';
            foreach ($arraydata as $key => $value) {
                $parent = !empty($value['parent_id']) ? trim($value['parent_id']) : '';
                $code = !empty($value['code']) ? trim($value['code']) : '';
                $name = !empty($value['name']) ? trim($value['name']) : '';
                $note = !empty($value['note']) ? trim($value['note']) : '';
                if (empty($code) || empty($name)) {
                    continue;
                }

                $parent_id = 0;
                if (!empty($parent)) {
                    $row_parent = $this->products_model->rowCategoryProductsByCode($parent, 'id', 'where');
                    if (!empty($row_parent)) {
                        $parent_id = $row_parent['id'];
                    } else {
                        $errors.= '<div class="text-danger">'.$parent.' '.lang('not_data_exists').'</div>';
                        continue;
                    }
                }

                $options = [
                    'code' => $code,
                    'name' => $name,
                    'note' => $note,
                    'parent_id' => $parent_id
                ];
                if ($this->products_model->checkCategoryProductsByCode($code)) {
                    $errors.= '<div class="text-danger">'.$code.' '.lang('tnh_exist_data').'</div>';
                    continue;
                }
                $id = $this->products_model->insertCategoryProducts($options);
                if ($id) {
                    $count++;
                }
            }
            if ($count) {
                $data['result'] = 1;
                $data['message'] = lang('success');
            } else {
                $data['result'] = 0;
                $data['message'] = lang('fail');
            }
            $data['errors'] = $errors;
            echo json_encode($data);
        } else {
            $data['tnh'] = true;
            $data['title'] = _l('tnh_import_excel');
            $list = [];
            $fields = get_fields_export($table = 'tbl_category_products', $arr_diff = ['id']);
            foreach ($fields as $key => $value) {
                $list[$value] = mb_strtoupper(lang('tnh_' . $value), 'UTF-8');
            }
            $required = [lang('tnh_name'), lang('tnh_code')];
            $data['list'] = $list;
            $data['required'] = $required;
            $this->load->view('admin/products/import_category', $data);
        }
    }

    public function import_products()
    {
        if ($this->input->post('save'))
        {
            $data = [];
            include APPPATH . 'third_party/PHPExcel/PHPExcel.php';
            $this->load->library('PHPExcel');

            $fullfile = $_FILES['file']['tmp_name'];
            if (empty($fullfile)) {
                $data['result'] = 0;
                $data['message'] = lang('tnh_file_not_required');
                echo json_encode($data); return;
            }
            $extension = strtoupper(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
            if($extension != 'XLSX' && $extension != 'XLS'){
                $data['result'] = 0;
                $data['message'] = lang('tnh_not_format_excel');
                echo json_encode($data); return;
            }

            if (empty($fullfile)) {
                $data['result'] = 0;
                $data['message'] = lang('tnh_file_not_required');
                echo json_encode($data); return;
            }

            if (!$this->input->post('fields')) {
                $data['result'] = 0;
                $data['message'] = lang('tnh_fields_not_required');
                echo json_encode($data); return;
            }
            // if ($_FILES['userfile']['size'] > $this->allowed_file_size * 1024) {
            //     $this->session->set_flashdata('warning', lang('Không vượt quá '. $this->allowed_file_size. ' size'));
            //     redirect($_SERVER["HTTP_REFERER"]);
            //     return;
            // }
            $inputFileType  = PHPExcel_IOFactory::identify($fullfile);
            $objReader      = PHPExcel_IOFactory::createReader($inputFileType);
            $objReader->setReadDataOnly(true);

            /**  Load $inputFileName to a PHPExcel Object  **/
            $objPHPExcel = $objReader->load("$fullfile");

            $total_sheets = $objPHPExcel->getSheetCount();

            $allSheetName       = $objPHPExcel->getSheetNames();
            $objWorksheet       = $objPHPExcel->setActiveSheetIndex(0);
            $highestRow         = $objWorksheet->getHighestRow();
            $highestColumn      = $objWorksheet->getHighestColumn();
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            $arraydata          = array();

            $row_start = $this->input->post('row_start') ? $this->input->post('row_start') : 2;
            $row_end = $this->input->post('row_end') ? $this->input->post('row_end') : $highestRow;
            $cloumn_excel = $this->input->post('cloumn_excel');
            $fields = $this->input->post('fields');
            for ($row = $row_start; $row <= $row_end; ++$row) {
                for ($col = 0; $col < $highestColumnIndex; ++$col) {
                    // $value = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                    if (!empty($cloumn_excel[$col]))
                    {
                        $cloumn_current = $cloumn_excel[$col];
                        $index_current = $fields[$col];
                    } else {
                        continue;
                    }
                    $value = $objWorksheet->getCell($cloumn_current.$row)->getValue();
                    $arraydata[$row][$index_current] = $value;
                }
            }

            $options = [];
            $count = 0;
            $errors = '';

            //option category
            $category_id_1 = $this->input->post('category_id_1');//where or like
            $category_id_2 = $this->input->post('category_id_2');//add or continue
            //unit
            $unit_id_1 = $this->input->post('unit_id_1');//where or like
            $unit_id_2 = $this->input->post('unit_id_2');//add or continue
            //colors
            $colors_1 = $this->input->post('colors_1');//where or like
            $colors_2 = $this->input->post('colors_2');//add or continue

            // print_arrays($arraydata);
            foreach ($arraydata as $key => $value) {
                $category = !empty($value['category_id']) ? trim($value['category_id']) : '';
                $type_products = !empty($value['type_products']) ? trim($value['type_products']) : '';
                $code = !empty($value['code']) ? trim($value['code']) : '';
                $name = !empty($value['name']) ? trim($value['name']) : '';
                $price_import = !empty($value['price_import']) ? number_unformat($value['price_import']) : 0;
                $price_sell = !empty($value['price_sell']) ? number_unformat($value['price_sell']) : 0;
                $price_processing = !empty($value['price_processing']) ? number_unformat($value['price_sell']) : 0;
                $unit = !empty($value['unit_id']) ? trim($value['unit_id']) : '';
                $mode = !empty($value['mode']) ? trim($value['mode']) : '';
                $note = !empty($value['note']) ? trim($value['note']) : '';
                $number_labor = !empty($value['number_labor']) ? number_unformat($value['number_labor']) : 0;
                $quantity_minimum = !empty($value['quantity_minimum']) ? number_unformat($value['quantity_minimum']) : 0;
                $quantity_max = !empty($value['quantity_max']) ? number_unformat($value['quantity_max']) : 0;
                $colors = !empty($value['colors']) ? trim($value['colors']) : '';
                $bom_version = !empty($value['bom_id']) ? trim($value['bom_id']) : '';
                if (empty($code) || empty($name) || empty($category) || empty($unit)) {
                    continue;
                }
                //category
                if ($category_id_1) {
                    $row_category = $this->products_model->rowCategoryProductsByCode($category, 'id', $category_id_1);
                    if (!empty($row_category)) {
                        $category_id = $row_category['id'];
                    } else if ($category_id_2 == 'add') {
                        $category_id = $this->products_model->insertCategoryProducts([
                            'code' => $category,
                            'name' => $category,
                        ]);
                    } else {
                        continue;
                    }
                }
                //unit
                if ($unit_id_1) {
                    $row_unit = $this->unit_model->rowUnitByCode($unit, 'unitid', $unit_id_1);
                    if (!empty($row_unit)) {
                        $unit_id = $row_unit['unitid'];
                    } else if ($unit_id_2 == 'add') {
                        $unit_id = $this->unit_model->insertUnit([
                            'unit' => $unit
                        ]);
                    } else {
                        continue;
                    }
                }
                //colors
                $arr_colors = '';
                if (!empty($colors))
                {
                    $colors = explode('//', $colors);
                    foreach ($colors as $k => $val) {
                        if (empty($val)) continue;
                        $row_color = $this->products_model->rowColorByCode($val, 'id', $colors_1);
                        if (!empty($row_color)) {
                            $color_id = $row_color['id'];
                        } else if ($colors_2 == 'add') {
                            $color_id = $this->products_model->insertColors([
                                'code' => $val,
                                'name' => $val
                            ]);
                        } else {
                            continue;
                        }
                        $arr_colors[$k]['color_id'] = $color_id;
                    }
                }

                //custom fields
                $custom_fields = [];
                foreach ($this->custom_fields as $k => $val) {
                    if (!empty($value['custom_fields_'.$val['fieldto'].'_'.$val['id']]))
                    {
                        $custom_fields[$val['fieldto']][$val['id']] = $value['custom_fields_'.$val['fieldto'].'_'.$val['id']];
                    }
                }

                //bom
                $bom_id = 0;
                $versions_bom = null;
                if (!empty($bom_version)) {
                    $bom = $this->products_model->rowBomByVersion($bom_version);
                    if (!empty($bom))
                    {
                        $bom_id = $bom['id'];
                        $versions_bom = $bom['versions'];
                    }
                }

                $options = [
                    'category_id' => $category_id,
                    'type_products' => $type_products,
                    'name' => $name,
                    'code' => $code,
                    'price_import' => $price_import,
                    'price_sell' => $price_sell,
                    'price_processing' => $price_processing,
                    'number_labor' => $number_labor,
                    'quantity_minimum' => $quantity_minimum,
                    'quantity_max' => $quantity_max,
                    'unit_id' => $unit_id,
                    'mode' => $mode,
                    'note' => $note,
                    'bom_id' => $bom_id,
                    'versions' => $versions_bom,
                    'date_created' => date('Y-m-d H:i:s'),
                    'created_by' => get_staff_user_id(),
                ];

                //check exist
                if ($this->products_model->checkProductsByCode($code)) {
                    $errors.= '<div class="text-danger">'.$code.' '.lang('tnh_exist_data').'</div>';
                    continue;
                }
                $id = $this->products_model->insertProducts($options);
                if ($id) {
                    $count++;
                    if (!empty($arr_colors))
                    {
                        $cl = [];
                        foreach ($arr_colors as $key => $value) {
                            $cl[] = [
                                'product_id' => $id,
                                'color_id' => $value['color_id'],
                            ];
                        }
                        $this->products_model->insertBatchProductsColors($cl);
                    }
                    if (!empty($custom_fields)) {
                        handle_custom_fields_post($id, $custom_fields);
                    }
                    //bom
                    if (!empty($bom) && $type_products != "semi_products_outside") {
                        $fields = [
                            'versions' => $bom['versions'],
                            'product_id' => $id,
                            'bm_id' => $bom_id,
                            'status' => 'unapplication',
                            'date_start' => $bom['date_start'],
                            'date_end' => $bom['date_end'],
                            'date_created' => $bom['date_created'],
                            'created_by' => $bom['created_by'],
                        ];
                        $bom_element = $this->products_model->getBomsElementByBomId($bom_id);
                        foreach ($bom_element as $key => $value) {
                            $fields['element'][$key]['element_name'] = $value['element_name'];
                            $fields['element'][$key]['element_number'] = $value['quantity'];

                            $type = false;
                            if ($type_products == 'semi_products') $type = ['materials'];
                            $items = $this->products_model->getBomsElementItemsByBEI($value['id'], $type);
                            if (!empty($items)) {
                                foreach ($items as $k => $val) {
                                    $fields['element'][$key]['items'][$k]['type'] = $val['type'];
                                    $fields['element'][$key]['items'][$k]['item_id'] = $val['item_id'];
                                    $fields['element'][$key]['items'][$k]['unit_id'] = $val['unit_id'];
                                    $fields['element'][$key]['items'][$k]['element_item_number'] = $val['quantity'];
                                }
                            }
                        }
                        if (!empty($fields)) {
                            $ib = $this->products_model->insertBOM($fields, 'unapplication', 0, $actions = "add");
                        }
                    }
                }
            }
            if ($count) {
                $data['result'] = 1;
                $data['message'] = lang('success');
            } else {
                $data['result'] = 0;
                $data['message'] = lang('tnh_not_data_add');
            }
            $data['errors'] = $errors;
            echo json_encode($data); die;
        } else {
            $data['tnh'] = true;
            $data['title'] = _l('tnh_import_excel');
            $list = [];
            $fields = get_fields_export($table = 'tbl_products', $arr_diff = ['id', 'images', 'quantity_begin', 'versions', 'versions_stage', 'info', 'images_multiple', 'code_system', 'date_created', 'created_by', 'updated_by', 'date_updated', 'type_wish'], $arr_more = ['colors']);
            foreach ($fields as $key => $value) {
                if ($value == "name_customer") {
                    $list[$value] = mb_strtoupper(lang('tnh_product_name_customer'), 'UTF-8');
                } else if ($value == "name_supplier") {
                    $list[$value] = mb_strtoupper(lang('tnh_product_name_supplier'), 'UTF-8');
                } else {
                    $list[$value] = mb_strtoupper(lang('tnh_' . $value), 'UTF-8');
                }
            }
            //custom fields
            foreach ($this->custom_fields as $key => $value) {
                $list['custom_fields_'.$value['fieldto'].'_'.$value['id']] = $value['name'];
            }
            $required = [lang('tnh_category_id'), lang('tnh_type_products'), lang('tnh_name'), lang('tnh_code'), lang('tnh_unit_id')];
            $data['list'] = $list;
            $data['required'] = $required;
            $this->load->view('admin/products/import_products', $data);
        }
    }

    function searchProducts()
    {
        $data = [];
        if ($this->input->get())
        {
            $q = $this->input->get('q');
            $limit = get_option('select2_limit');
            $data = $this->products_model->searchProducts($q, $limit);
        }
        echo json_encode($data);
    }

    function searchProductsSelect2($id = false)
    {
        $data = [];
        $term = $this->input->get('term', TRUE);
        $limit = get_option('select2_limit');
        $data['results'] = $this->products_model->searchProductsSelect2($term, $limit);
        if ($id) {
            $product = $this->products_model->rowProduct($id);
            $data['row'] = ['id' => $product['id'], 'text' => $product['code']];
        }
        echo json_encode($data);
    }

    function searchProductAndGoods($id = false)
    {
        $data = [];
        $term = $this->input->get('term', TRUE);
        $limit = get_option('select2_limit');
        $products = $this->products_model->searchProductsSelect2($term, $limit);
        $items = $this->products_model->searchItemsSelect2($term, $limit);
        $data['results'] = [
            [
                'text' => lang('products'), 'children' => $products
            ],
            [
                'text' => lang('ch_items'), 'children' => $items
            ]
        ];
        if ($id) {
            $dt = explode('__', $id);
            $id = $dt[0];
            $type_item = $dt[1];
            if ($type_item == "products") {
                $product = $this->products_model->rowProduct($id);
                $data['row'] = ['id' => $product['id'].'__'.'products', 'text' => $product['code']];
            } else if ($type_item == "items") {
                $item = $this->items_model->rowItems($id);
                $data['row'] = ['id' => $item['id'].'__'.'items', 'text' => $item['code']];
            }
        }
        echo json_encode($data);
    }

    public function rowItem()
    {
        $data = [];
        if ($this->input->get())
        {
            $item_id = $this->input->get('item_id');
            $type = $this->input->get('type');
            $arr_unit_id = [];
            $selected = '';
            if ($type == 'semi_products' || $type == 'semi_products_outside') {
                $semi_products = $this->products_model->rowProduct($item_id);
                $selected = $semi_products['unit_id'];
                array_push($arr_unit_id, $semi_products['unit_id']);
            } else {
                $material = $this->items_model->rowMaterial($item_id);
                $exchange = $this->items_model->getExchangeItemsByItemId($item_id);
                $selected = $material['unit_id'];
                array_push($arr_unit_id, $material['unit_id']);
                if (!empty($exchange)) {
                    foreach ($exchange as $key => $value) {
                        array_push($arr_unit_id, $value['unit_id']);
                    }
                }
            }
            $units = false;
            if (!empty($arr_unit_id)) {
                $units = $this->products_model->getUnitsByArrId($arr_unit_id);
            }
            $data['units'] = $units;
            $data['selected'] = $selected;
        }
        echo json_encode($data);
    }

    public function list_bom()
    {
        $data['tnh'] = true;
        $data['title'] = lang('tnh_list_bom');
        $this->load->view('admin/products/list_bom', $data);
    }

    public function add_bom($id = 0, $actions = 'add')
    {
        if (!empty($id)) {
            $bom = $this->products_model->rowBomById($id);
        }
        if ($this->input->post())
        {
            if ($actions == 'add' || $actions == 'copy')
            {
                $this->form_validation->set_rules('versions', lang("tnh_versions"), 'required|is_unique[tbl_boms.versions]');
            } else if ($actions == 'edit') {
                if (!empty($bom) && $bom['versions'] != trim($this->input->post('versions'))) {
                    $this->form_validation->set_rules('versions', lang("tnh_versions"), 'required|is_unique[tbl_boms.versions]');
                } else {
                    $this->form_validation->set_rules('versions', lang("tnh_versions"), 'required');
                }
            }
            if ($this->form_validation->run() == true)
            {
                $status = "unapplication";
                $versions = trim($this->input->post('versions'));
                $date_start = $this->input->post('date_start') ? to_sql_date($this->input->post('date_start')) : date('Y-m-d H:i:s');
                $date_end = $this->input->post('date_end') ? to_sql_date($this->input->post('date_end')) : null;
                $status_bom = $this->input->post('status');
                $i = $this->input->post('i');

                $options['versions'] = $versions;
                $options['date_start'] = $date_start;
                $options['date_end'] = $date_end;
                $options['status_bom'] = $status_bom;
                foreach ($i as $key => $value) {
                    $element_name = trim($this->input->post('element_name_'.$value));
                    if (empty($element_name)) continue;
                    $element_number = $this->input->post('element_number_'.$value);
                    $options['element'][$key]['element_name'] = $element_name;
                    $options['element'][$key]['element_number'] = $element_number;
                    $type_design_bom = $this->input->post('type_design_bom_'.$value);
                    if (!empty($type_design_bom)) {
                        foreach ($type_design_bom as $k => $val) {
                            // if ($products['type_products'] != 'products' && $val != 'materials') continue;
                            $item_id = $this->input->post('items_'.$value)[$k];
                            $element_item_number = $this->input->post('element_item_number_'.$value)[$k];
                            $unit_id = $this->input->post('units_'.$value)[$k];
                            $options['element'][$key]['items'][$k]['type'] = $val;
                            $options['element'][$key]['items'][$k]['item_id'] = $item_id;
                            $options['element'][$key]['items'][$k]['unit_id'] = $unit_id;
                            $options['element'][$key]['items'][$k]['element_item_number'] = $element_item_number;
                        }
                    }
                }

                $q = $this->products_model->insertCategoryBOM($options, $status, $id, $actions);
                if ($q) {
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
            return;
        } else {
            if (!empty($bom))
            {
                $data['bom'] = $bom;
                $html_BOM = '';
                $count_i = 0;
                $count_k = 0;

                $elements = $this->products_model->getBomsElementByBomId($bom['id']);
                foreach ($elements as $key => $value) {
                    $html_BOM .= '<tr>';
                    $html_BOM .= '<input type="hidden" name="i[]" id="i" class="form-control i" value="'.$count_i.'">';
                    $html_BOM .= '<td>
                                    <div class="text-center">
                                        <button type="button" class="btn btn-primary btn-icon btn-add-items">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </td>';

                    $html_BOM .= '<td colspan="2">
                                    <input type="text" name="element_name_'.$count_i.'" id="element_name_'.$count_i.'" class="form-control" value="'.$value['element_name'].'" placeholder="'.lang('tnh_element_name').'" required="required">
                                </td>';
                    $html_BOM .= '<td></td>';
                    $html_BOM .= '<td>
                                    <input type="number" name="element_number_'.$count_i.'" class="form-control" value="'.$value['quantity'].'" min="0">
                                </td>';
                    $html_BOM .= '<td>
                                    <div class="text-center"><i class="btn btn-danger fa fa-remove remove-element"></i></div>
                                </td>';
                    $html_BOM .= '</tr>';

                    $items = $this->products_model->getBomsElementItemsByBEI($value['id']);
                    foreach ($items as $k => $val) {
                        $option = '<option value=""></option>';
                        $type_design = type_design_bom('all');
                        foreach ($type_design as $e => $v) {
                            $option .= '<option '.($e == $val['type'] ? 'selected' : '').' value="'.$e.'">'.$v.'</option>';
                        }

                        $arr_unit_id = [];
                        if ($val['type'] == "semi_products" || $val['type'] == "semi_products_outside") {
                            $info = $this->products_model->rowProduct($val['item_id']);
                            array_push($arr_unit_id, $info['unit_id']);
                        } else {
                            $info = $this->items_model->rowMaterial($val['item_id']);
                            $exchange = $this->items_model->getExchangeItemsByItemId($val['item_id']);
                            array_push($arr_unit_id, $info['unit_id']);
                            if (!empty($exchange)) {
                                foreach ($exchange as $ke => $va) {
                                    array_push($arr_unit_id, $va['unit_id']);
                                }
                            }
                        }
                        array_push($arr_unit_id, $val['unit_id']);
                        $option_units = '';
                        if (!empty($arr_unit_id)) {
                            $units = $this->products_model->getUnitsByArrId($arr_unit_id);
                            foreach ($units as $a => $el) {
                                $selected_unit = ($el['unitid'] == $val['unit_id']) ? 'selected' : '';
                                $option_units.= '<option '.$selected_unit.' value="'.$el['unitid'].'">'.$el['unit'].'</option>';
                            }
                        }

                        $html_BOM .= '<tr class="tnh-item-'.$count_i.'">';
                        $html_BOM .= '<td></td>';
                        $html_BOM .= '<input type="hidden" name="k[]" id="k" class="form-control k" value="'.$count_k.'">';
                        $html_BOM .= '<td colspan="1" style="width: 200px;">
                        <select name="type_design_bom_'.$count_i.'[]" data-none-selected-text="'.lang('type').'" id="type_design_bom_'.$k.'" class="form-control type_design_bom" required="required">
                            '.$option.'
                        </select>
                        </td>';

                        $html_BOM .= '<td colspan="1">
                            <input type="text" name="items_'.$count_i.'[]" id="items_'.$count_k.'" data-placeholder="'.lang('choose').'" class="modal-select2 it" style="width: 100%;" value="'.$val['item_id'].'" required="required">
                        </td>';
                        $html_BOM .= '<td colspan="1" class="class="td-unit"">
                            <select data-placeholder="'.lang('choose').'" id="units_'.$count_k.'" name="units_'.$count_i.'[]" class="modal-select2 units" style="width: 100%;" required>
                                '.$option_units.'
                            </select>
                        </td>';
                        $html_BOM .= '<td colspan="">
                        <input type="number" name="element_item_number_'.$count_i.'[]" class="form-control" value="'.$val['quantity'].'" min="0">
                        </td>';
                        $html_BOM .= '<td colspan="">
                        <div class="text-center"><i class="btn btn-danger fa fa-remove remove-element-item"></i></div>
                        </td>';
                        $html_BOM .= '</tr>';
                        $count_k++;
                    }
                    $count_i++;
                }
                $data['html_BOM'] = $html_BOM;
                $data['count_i'] = $count_i;
                $data['count_k'] = $count_k;
            }

            $data['id'] = $id;
            $data['actions'] = $actions;
            $data['title'] = lang('tnh_add_bom');
            $this->load->view('admin/products/add_bom', $data);
        }
    }

    public function getBoms()
    {
        $this->datatables
            ->select("
                tbl_boms.id as id,
                tbl_boms.versions as versions,
                tbl_boms.date_start as date_start,
                tbl_boms.date_end as date_end,
                tbl_boms.date_created as date_created,
                CONCAT(tblstaff.firstname, ' ', tblstaff.lastname,'') as created_by,
                tbl_boms.status_bom as status_bom
                ", false)
            ->from('tbl_boms')
            ->join('tblstaff', 'tblstaff.staffid = tbl_boms.created_by', 'left');


        $view = '<a class="tnh-modal" data-tnh="modal" data-toggle="modal" data-target="#myModal" href="'.base_url().'admin/products/view_bom/$1"><i class="fa fa-file-text-o width-icon-actions"></i> '.lang('view').'</a>';

        $edit = '<a class="tnh-modal" data-tnh="modal" data-toggle="modal" data-target="#myModal" href="'.base_url().'admin/products/add_bom/$1/edit"><i class="fa fa-edit width-icon-actions"></i> '.lang('edit').'</a>';

        $copy = '<a class="tnh-modal" data-tnh="modal" data-toggle="modal" data-target="#myModal" href="'.base_url().'admin/products/add_bom/$1/copy"><i class="fa fa-copy width-icon-actions"></i> '.lang('copy').'</a>';

        $delete = '<a type="button" class="po" data-container="body" data-html="true" data-toggle="popover" data-placement="left" data-content="
            <button href=\''.base_url('admin/products/delete_category_bom/$1').'\' class=\'btn btn-danger po-delete-json\'>'.lang('delete').'</button>
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
                <li>'.$copy.'</li>
                <li class="not-outside">'.$delete.'</li>
            </ul>
        </div>';

        $this->datatables->add_column('actions', $actions, 'id');
        echo $this->datatables->generate();
    }

    public function view_bom($id)
    {
        $bom = $this->products_model->rowBomById($id);
        $bom_element = $this->products_model->getBomsElementByBomId($id);

        $bom_html = '';
        if (!empty($bom_element)) {
            $bom_html.=
                    '<div class="table-responsive">
                        <table id="tb-datatable" data-bom="1" class="tnh-table table-hover table-bordered table-condensed" style="margin-top: 10px;">
                            <thead>
                                <tr>
                                    <th class="text-center">'. lang('tnh_numbers') .'</th>
                                    <th>'. lang('tnh_element_name') .'</th>
                                    <th>'. lang('quantity') .'</th>
                                </tr>
                            </thead>
                            <tbody>';
                        foreach ($bom_element as $k => $val) {
                            $bom_html.=
                                    '<tr>
                                        <td style="width: 80px;" class="text-center"><button class="btn btn-primary cols" data-toggle="collapse" data-target="#demo'. $val['id'] .'">'. (++$k) .'</button></td>
                                        <td>'. $val['element_name'] .'</td>
                                        <td>'. $val['quantity'] .'</td>
                                    </tr>';
                            $items = $this->products_model->getBomsElementItemsByBEI($val['id']);
                            $bom_html.=
                                    '<tr id="demo'. $val['id'] .'" class="collapse in">
                                        <td colspan="99" style="overflow: hidden;">
                                            <table class="tbbb tnh-table-sub table-bordered table-condensed table-hover" style="margin-top: 0px;">
                                                <thead>
                                                    <tr style="background: #4caf50d4;">
                                                        <th style="width: 50px;" class="text-center">#</th>
                                                        <th style="width: 150px;">'. lang('type') .'</th>
                                                        <th style="width: 150px;">'. lang('code') .'</th>
                                                        <th style="width: 150px;">'. lang('name') .'</th>
                                                        <th class="text-center" style="width: 100px;">'. lang('unit') .'</th>
                                                        <th class="text-center">'. lang('quantity') .'</th>
                                                    </tr>
                                                </thead>
                                                <tbody>';
                                                foreach ($items as $i => $v) {
                                                    if ($v['type'] == "semi_products" || $v['type'] == "semi_products_outside") {
                                                        $info = $this->products_model->rowProduct($v['item_id']);
                                                    } else {
                                                        $info = $this->items_model->rowMaterial($v['item_id']);
                                                    }
                                                    $bom_html.= '
                                                            <tr>
                                                                <td class="text-center">'. (++$i) .'</td>
                                                                <td>'. lang($v['type']) .'</td>
                                                                <td>'. $info['code'] .'</td>
                                                                <td>'. $info['name'] .'</td>
                                                                <td class="text-center">'. $v['unit'] .'</td>
                                                                <td class="text-center">'. $v['quantity'] .'</td>
                                                            </tr>';
                                                }
                                            $bom_html.= '
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>';
                        }
                $bom_html.= '</tbody>
                        </table>
                    </div>';
        }
        $data['created_by'] = get_staff_full_name($bom['created_by']);
        if ($bom['updated_by']) {
            $data['updated_by'] = get_staff_full_name($bom['updated_by']);
        }
        $data['bom'] = $bom;
        $data['bom_html'] = $bom_html;
        $data['id'] = $id;
        $this->load->view('admin/products/view_bom', $data);
    }

    function delete_category_bom($id)
    {
        $data = [];
        if ($id) {
            $bom = $this->products_model->rowBomById($id);
            if ($this->products_model->deleteCategoryBomById($id))
            {
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
}
