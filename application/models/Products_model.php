<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Products_model extends App_Model
{
	private $contact_columns;

    public function __construct()
    {
        parent::__construct();

        // $this->contact_columns = hooks()->apply_filters('contact_columns', ['firstname', 'lastname', 'email', 'phonenumber', 'title', 'password', 'send_set_password_email', 'donotsendwelcomeemail', 'permissions', 'direction', 'invoice_emails', 'estimate_emails', 'credit_note_emails', 'contract_emails', 'task_emails', 'project_emails', 'ticket_emails', 'is_primary']);

        // $this->load->model(['client_vault_entries_model', 'client_groups_model', 'statement_model']);
    }

    public function insertCategoryProducts($data)
    {
    	$this->db->insert('_category_products', $data);
    	return $this->db->insert_id();
    }

    public function rowCategoryProducts($id)
    {
        $this->db->select('*');
        $this->db->from('_category_products');
        $this->db->where('id', $id);
        return $this->db->get()->row_array();
    }

    public function updateCategoryProducts($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('_category_products', $data);
    }

    public function updateItems($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tblitems', $data);
    }

    public function deleteCategoryProducts($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('_category_products');
    }

    public function searchCategory($q, $limit = 50)
    {
        $this->db->select('tbl_category_products.id as id, tbl_category_products.name as name', false);
        $this->db->from('tbl_category_products');
        if (!empty($q))
        {
            $this->db->group_start();
            $this->db->like('tbl_category_products.code', $q);
            $this->db->or_like('tbl_category_products.name', $q);
            $this->db->group_end();
        }
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    public function insertProducts($data)
    {
        $this->db->insert('tbl_products', $data);
        return $this->db->insert_id();
    }

    public function updateProducts($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tbl_products', $data);
    }

    public function deleteProducts($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('tbl_products');
    }

    public function rowProduct($id)
    {
        $this->db->select('tbl_products.*, tbl_category_products.name as category_name', false);
        $this->db->from('tbl_products');
        $this->db->join('tbl_category_products', 'tbl_category_products.id = tbl_products.category_id', 'left');
        $this->db->where('tbl_products.id', $id);
        return $this->db->get()->row_array();
    }

    public function getColorsByProductId($product_id)
    {
        $this->db->select('tbl_colors.id, tbl_colors.name as color_name');
        $this->db->from('tbl_products_colors');
        $this->db->join('tbl_colors', 'tbl_colors.id = tbl_products_colors.color_id');
        $this->db->where('tbl_products_colors.product_id', $product_id);
        return $this->db->get()->result_array();
    }

    public function checkExistCategory($id)
    {
        $this->db->from('tbl_products');
        $this->db->where('tbl_products.category_id', $id);
        $this->db->limit(1);
        return $this->db->get()->num_rows();
    }

    //colors
    public function insertColors($data)
    {
        $this->db->insert('tbl_colors', $data);
        return $this->db->insert_id();
    }

    public function rowColors($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_colors');
        $this->db->where('id', $id);
        return $this->db->get()->row_array();
    }

    public function updateColors($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tbl_colors', $data);
    }

    public function deleteColors($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('tbl_colors');
    }

    public function searchColors($q, $limit = 50)
    {
        $this->db->select('tbl_colors.id as id, tbl_colors.name as name', false);
        $this->db->from('tbl_colors');
        if (!empty($q))
        {
            $this->db->group_start();
            $this->db->like('tbl_colors.code', $q);
            $this->db->or_like('tbl_colors.name', $q);
            $this->db->group_end();
        }
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    public function checkExistColors($id)
    {
        $this->db->from('tbl_products_colors');
        $this->db->where('color_id', $id);
        $this->db->limit(1);
        return $this->db->get()->num_rows();
    }
    //end colors

    //product color
    function insertBatchProductsColors($data)
    {
        return $this->db->insert_batch('tbl_products_colors', $data);
    }

    function deleteProductsColorsByProductId($product_id)
    {
        $this->db->where('product_id', $product_id);
        return $this->db->delete('tbl_products_colors');
    }

    public function searchSemiProducts($q, $limit = 50)
    {
        $this->db->select('tbl_products.id as id, tbl_products.name as name', false);
        $this->db->from('tbl_products');
        if (!empty($q))
        {
            $this->db->group_start();
            $this->db->like('tbl_products.code', $q);
            $this->db->or_like('tbl_products.name', $q);
            $this->db->group_end();
        }
        $this->db->where('type_products', 'semi_products');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    public function searchSelect2SemiProducts($q, $limit = 50, $type = "semi_products")
    {
        $this->db->select('tbl_products.id as id, CONCAT(tbl_products.name, "(", tbl_products.code, ")") as text', false);
        $this->db->from('tbl_products');
        if (!empty($q))
        {
            $this->db->group_start();
            $this->db->like('tbl_products.code', $q);
            $this->db->or_like('tbl_products.name', $q);
            $this->db->group_end();
        }
        $this->db->where('type_products', $type);
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    public function searchProducts($q, $limit = 50)
    {
        $this->db->select('tbl_products.id as id, tbl_products.code as name, tbl_products.name as product_name, IF(tbl_products.images IS NOT NULL && tbl_products.images != "", CONCAT("uploads/products/", "", tbl_products.images, ""), "") as images', false);
        $this->db->from('tbl_products');
        if (!empty($q))
        {
            $this->db->group_start();
            $this->db->like('tbl_products.code', $q);
            $this->db->or_like('tbl_products.name', $q);
            $this->db->group_end();
        }
        // $this->db->where('type_products', 'semi_products');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    public function searchProductsSelect2($q, $limit = 50)
    {
        $this->db->select('CONCAT(tbl_products.id, "__products") as id, tbl_products.code as text, tbl_products.name as item_name, IF(tbl_products.images IS NOT NULL && tbl_products.images != "", CONCAT("uploads/products/", "", tbl_products.images, ""), "") as images, tblunits.unit as unit_name, tbl_products.price_sell as price_sell, tbl_products.info as info', false);
        $this->db->from('tbl_products');
        $this->db->join('tblunits', 'tbl_products.unit_id = tblunits.unitid', 'left');
        if (!empty($q))
        {
            $this->db->group_start();
            $this->db->like('tbl_products.code', $q);
            $this->db->or_like('tbl_products.name', $q);
            $this->db->group_end();
        }
        // $this->db->where('type_products', 'semi_products');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    public function searchItemsSelect2($q, $limit = 50)
    {
        $this->db->select('CONCAT(tblitems.id, "__items") as id, tblitems.code as text, tblitems.name as item_name, tblitems.avatar as images, tblunits.unit as unit_name, tblitems.price as price_sell, tblitems.info as info', false);
        $this->db->from('tblitems');
        $this->db->join('tblunits', 'tblitems.unit = tblunits.unitid', 'left');
        if (!empty($q))
        {
            $this->db->group_start();
            $this->db->like('tblitems.code', $q);
            $this->db->or_like('tblitems.name', $q);
            $this->db->group_end();
        }
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    public function checkProductVersions($product_id, $versions)
    {
        $this->db->from('tbl_product_versions');
        $this->db->where('tbl_product_versions.product_id', $product_id);
        $this->db->where('tbl_product_versions.versions', $versions);
        return $this->db->get()->num_rows();
    }

    public function deleteProductVersionsByProductIdAndVersion($product_id, $versions)
    {
        $this->db->where('product_id', $product_id);
        $this->db->where('versions', $versions);
        return $this->db->delete('tbl_product_versions');
    }

    public function getProductVersionsByProductId($product_id)
    {
        $this->db->select('*');
        $this->db->from('tbl_product_versions');
        $this->db->where('tbl_product_versions.product_id', $product_id);
        $this->db->order_by('tbl_product_versions.versions', 'ASC');
        return $this->db->get()->result_array();
    }

    public function getProductVersionsById($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_product_versions');
        $this->db->where('tbl_product_versions.id', $id);
        return $this->db->get()->row_array();
    }

    public function getVersionsElementByVersionId($version_id)
    {
        $this->db->select('*');
        $this->db->from('tbl_versions_element');
        $this->db->where('version_id', $version_id);
        return $this->db->get()->result_array();
    }

    public function getElementItemsByElementId($element_id)
    {
        $this->db->select('tbl_element_items.*, tblunits.unit');
        $this->db->from('tbl_element_items');
        $this->db->join('tblunits', 'tblunits.unitid = tbl_element_items.unit_id', 'left');
        $this->db->where('element_id', $element_id);
        return $this->db->get()->result_array();
    }

    public function deleteProductsVersionsByProductId($product_id)
    {
        $versions = $this->getProductVersionsByProductId($product_id);
        $this->db->where('tbl_product_versions.product_id', $product_id);
        if ($this->db->delete('tbl_product_versions')) {
            foreach ($versions as $key => $value) {
                $vs_elements = $this->getVersionsElementByVersionId($value['id']);
                $this->db->where('tbl_versions_element.version_id', $value['id']);
                if ($this->db->delete('tbl_versions_element')) {
                    foreach ($vs_elements as $k => $val) {
                        $this->db->where('tbl_element_items.element_id', $val['id']);
                        $this->db->delete('tbl_element_items');
                    }
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public function deleteBOOMById($id)
    {
        $version = $this->getProductVersionsById($id);
        $this->db->where('tbl_product_versions.id', $id);
        if ($this->db->delete('tbl_product_versions')) {
            $vs_elements = $this->getVersionsElementByVersionId($id);
            $this->db->where('tbl_versions_element.version_id', $id);
            if ($this->db->delete('tbl_versions_element')) {
                foreach ($vs_elements as $k => $val) {
                    $this->db->where('tbl_element_items.element_id', $val['id']);
                    $this->db->delete('tbl_element_items');
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public function insertBOM($data, $status = "unapplication", $bom_id = 0, $actions = 'add') {
        if (!empty($data))
        {
            // print_arrays($data['element']);
            // if (!empty($bom_id)) {
            //     $this->deleteBOOMById($bom_id);
            // }
            if ($actions == "add") {
                $version_id = $this->db->insert('tbl_product_versions', [
                    'versions' => $data['versions'],
                    'product_id' => $data['product_id'],
                    'bm_id' => $data['bm_id'],
                    'date_start' => !empty($data['date_start']) ? $data['date_start'] : null,
                    'date_end' => !empty($data['date_end']) ? $data['date_end'] : null,
                    'date_created' => !empty($data['date_created']) ? $data['date_created'] : date('Y-m-d H:i:s'),
                    'created_by' => !empty($data['created_by']) ? $data['created_by'] : get_staff_user_id(),
                ]);
                $version_id = $this->db->insert_id();
            } else if ($actions == "edit") {
                $bom = $this->products_model->getProductVersionsById($bom_id);
                if (!empty($bom_id)) {
                    $this->deleteBOOMById($bom_id);
                }
                $version_id = $this->db->insert('tbl_product_versions', [
                    'versions' => $data['versions'],
                    'product_id' => $data['product_id'],
                    'date_start' => !empty($data['date_start']) ? $data['date_start'] : null,
                    'date_end' => !empty($data['date_end']) ? $data['date_end'] : null,
                    'date_created' => !empty($bom['date_created']) ? $bom['date_created'] : date('Y-m-d H:i:s'),
                    'created_by' => !empty($bom['created_by']) ? $bom['created_by'] : get_staff_user_id(),
                    'date_updated' => date('Y-m-d H:i:s'),
                    'updated_by' => get_staff_user_id(),
                ]);
                $version_id = $this->db->insert_id();
            }
            if (!empty($version_id)) {
                $element = $data['element'];
                if (!empty($element))
                {
                    foreach ($element as $k => $val) {
                        $el_id = $this->db->insert('tbl_versions_element', [
                            'version_id' => $version_id,
                            'element_name' => $val['element_name'],
                            'quantity' => $val['element_number'],
                        ]);
                        $el_id = $this->db->insert_id();
                        if ($el_id) {
                            if (!empty($val['items']))
                            {
                                $items = $val['items'];
                                foreach ($items as $v) {
                                    $this->db->insert('tbl_element_items', [
                                        'element_id' => $el_id,
                                        'type' => $v['type'],
                                        'item_id' => $v['item_id'],
                                        'unit_id' => $v['unit_id'],
                                        'quantity' => $v['element_item_number'],
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
            if ($version_id) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    public function insertStages($data)
    {
        $this->db->insert('tbl_stages', $data);
        return $this->db->insert_id();
    }

    public function rowStages($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_stages');
        $this->db->where('id', $id);
        return $this->db->get()->row_array();
    }

    public function updateStages($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tbl_stages', $data);
    }

    public function deleteStages($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('tbl_stages');
    }

    public function searchStages($q, $limit = 50)
    {
        $this->db->select('tbl_stages.id as id, tbl_stages.name as name', false);
        $this->db->from('tbl_stages');
        if (!empty($q))
        {
            $this->db->group_start();
            $this->db->like('tbl_stages.code', $q);
            $this->db->or_like('tbl_stages.name', $q);
            $this->db->group_end();
        }
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    public function checkProductStages($product_id, $versions)
    {
        $this->db->from('tbl_product_stages');
        $this->db->where('tbl_product_stages.product_id', $product_id);
        $this->db->where('tbl_product_stages.versions', $versions);
        return $this->db->get()->num_rows();
    }

    public function insertProductStages($data, $status = "unapplication", $vs_stage_id = 0)
    {
        if (!empty($vs_stage_id)) {
            $this->deleteProductStagesById($vs_stage_id);
        }
        // print_arrays($data['items']);
        $this->db->insert('tbl_product_stages', [
            'product_id' => $data['product_id'],
            'versions' => $data['versions'],
            'status' => $status,
        ]);
        $version_id = $this->db->insert_id();
        if ($version_id) {
            $items = $data['items'];
            if (!empty($items)) {
                foreach ($items as $key => $value) {
                    $this->db->insert('tbl_product_stages_versions', [
                        'version_id' => $version_id,
                        'stage_id' => $value['stage'],
                        'machines' => $value['machines'],
                        'number' => $value['number'],
                        'number_hours' => $value['number_hours']
                    ]);
                }
            }
            return true;
        }
        return false;
    }

    public function getProductStagesByProductId($product_id)
    {
        $this->db->select('*');
        $this->db->from('tbl_product_stages');
        $this->db->where('tbl_product_stages.product_id', $product_id);
        return $this->db->get()->result_array();
    }

    public function rowProductStagesById($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_product_stages');
        $this->db->where('tbl_product_stages.id', $id);
        return $this->db->get()->row_array();
    }

    public function getProductStagesVersions($version_id)
    {
        $this->db->select('tbl_product_stages_versions.*, tbl_stages.name as stage_name, tbl_stages.code as stage_code');
        $this->db->from('tbl_product_stages_versions');
        $this->db->join('tbl_stages', 'tbl_stages.id = tbl_product_stages_versions.stage_id', 'left');
        $this->db->where('tbl_product_stages_versions.version_id', $version_id);
        $this->db->order_by('tbl_product_stages_versions.number', 'ASC');
        return $this->db->get()->result_array();
    }

    public function deleteProductStages($product_id) {
        $product_stages = $this->getProductStagesByProductId($product_id);
        $this->db->where('tbl_product_stages.product_id', $product_id);
        if ($this->db->delete('tbl_product_stages')) {
            foreach ($product_stages as $key => $value) {
                $this->db->where('tbl_product_stages_versions.version_id', $value['id']);
                $this->db->delete('tbl_product_stages_versions');
            }
            return true;
        }
        return false;
    }

    public function deleteProductStagesById($id)
    {
        $this->db->where('tbl_product_stages.id', $id);
        if ($this->db->delete('tbl_product_stages')) {
                $this->db->where('tbl_product_stages_versions.version_id', $id);
                $this->db->delete('tbl_product_stages_versions');
            return true;
        }
        return false;
    }

    public function getBomByProductIdAndVersions($product_id, $versions)
    {
        $this->db->select('*');
        $this->db->from('tbl_product_versions');
        $this->db->where('tbl_product_versions.versions', $versions);
        $this->db->where('tbl_product_versions.product_id', $product_id);
        return $this->db->get()->row_array();
    }

    public function checkCategoryProductsByCode($code)
    {
        $this->db->from('tbl_category_products');
        $this->db->where('tbl_category_products.code', $code);
        $this->db->limit(1);
        return $this->db->get()->num_rows();
    }

    public function checkProductsByCode($code)
    {
        $this->db->from('tbl_products');
        $this->db->where('tbl_products.code', $code);
        $this->db->limit(1);
        return $this->db->get()->num_rows();
    }

    public function rowCategoryProductsByCode($code, $select, $option)
    {
        $this->db->select($select);
        $this->db->from('tbl_category_products');
        if ($option == "like") {
            $this->db->like('tbl_category_products.code', $code);
        } else if ($option == "where") {
            $this->db->where('tbl_category_products.code', $code);
        }
        return $this->db->get()->row_array();
    }

    public function rowColorByCode($code, $select, $option)
    {
        $this->db->select($select);
        $this->db->from('tbl_colors');
        if ($option == "like") {
            $this->db->like('tbl_colors.code', $code);
        } else if ($option == "where") {
            $this->db->where('tbl_colors.code', $code);
        }
        return $this->db->get()->row_array();
    }

    // public function getBomForProductionsCapacity($product_id, $versions)
    // {
    //     $this->db->select('');
    //     $this->db->from('tbl_product_versions');
    //     $this->db->join('tbl_versions_element', 'tbl_versions_element.version_id = tbl_product_versions.id');
    //     $this->db->join('tbl_versions_element', 'tbl_versions_element.version_id = tbl_product_versions.id');
    //     $this->db->where('tbl_product_versions.product_id', $product_id);
    //     $this->db->where('tbl_product_versions.versions', $product_id);
    // }

    public function checkStagesByParentId($id) {
        $this->db->from('tbl_stages');
        $this->db->where('tbl_stages.parent_id', $id);
        $this->db->limit(1);
        return $this->db->get()->num_rows();
    }

    public function checkStagesExist($id) {
        $this->db->from('tbl_product_stages_versions');
        $this->db->where('tbl_product_stages_versions.stage_id', $id);
        $this->db->limit(1);
        $q = $this->db->get()->num_rows();
        if ($q > 0) {
            return true;
        }
        return false;
    }

    public function getProductStagesByProductIdAndVersions($product_id, $versions) {
        $this->db->select('*');
        $this->db->from('tbl_product_stages');
        $this->db->where('tbl_product_stages.product_id', $product_id);
        $this->db->where('tbl_product_stages.versions', $versions);
        return $this->db->get()->row_array();
    }

    public function checkExistProducts($id)
    {
        $this->db->from('tbl_element_items');
        $this->db->where('tbl_element_items.item_id', $id);
        $this->db->where('tbl_element_items.type', 'semi_products');
        $this->db->limit(1);
        $q = $this->db->get()->num_rows();
        if ($q) {
            return $q;
        }

        //bom
        $this->db->from('tbl_boms_element_items');
        $this->db->where('tbl_boms_element_items.type !=', 'materials');
        $this->db->where('tbl_boms_element_items.item_id', $id);
        $this->db->limit(1);
        $q = $this->db->get()->num_rows();
        if ($q) {
            return $q;
        }
        //bom product
        $this->db->from('tbl_element_items');
        $this->db->where('tbl_element_items.type !=', 'materials');
        $this->db->where('tbl_element_items.item_id', $id);
        $this->db->limit(1);
        $q = $this->db->get()->num_rows();
        if ($q) {
            return $q;
        }

        //quotes 01419f
        $this->db->from('tblmodules');
        $this->db->where('module_name', 'quotes');
        $this->db->where('active', 1);
        $result = $this->db->get()->num_rows();
        if ($result) {
            $this->db->from('tbl_quote_items');
            $this->db->where('tbl_quote_items.item_id', $id);
            $this->db->where('tbl_quote_items.type_item', 'products');
            $this->db->limit(1);
            $q = $this->db->get()->num_rows();
            if ($q) {
                return $q;
            }
        } else {
            //quotes
            $this->db->from('tblquotes_orders_items');
            $this->db->where('tblquotes_orders_items.id_product', $id);
            $this->db->where('tblquotes_orders_items.type_items', 'products');
            $this->db->limit(1);
            $q = $this->db->get()->num_rows();
            if ($q) {
                return $q;
            }
        }

        //orders
        $this->db->from('tblorders_items');
        $this->db->where('tblorders_items.id_product', $id);
        $this->db->where('tblorders_items.type_items', 'products');
        $this->db->limit(1);
        $q = $this->db->get()->num_rows();
        if ($q) {
            return $q;
        }

        //business plan
        $this->db->from('tbl_business_plan_items');
        $this->db->where('tbl_business_plan_items.items_id', $id);
        $this->db->where('tbl_business_plan_items.type_items', 'products');
        $this->db->limit(1);
        $q = $this->db->get()->num_rows();
        if ($q) {
            return $q;
        }

        //purchase
        $this->db->from('tblpurchases_items');
        $this->db->where('tblpurchases_items.product_id', $id);
        $this->db->where('tblpurchases_items.type', 'product');
        $this->db->limit(1);
        $q = $this->db->get()->num_rows();
        if ($q) {
            return $q;
        }

        //purchase order
        $this->db->from('tblpurchase_order_items');
        $this->db->where('tblpurchase_order_items.product_id', $id);
        $this->db->where('tblpurchase_order_items.type', 'product');
        $this->db->limit(1);
        $q = $this->db->get()->num_rows();
        if ($q) {
            return $q;
        }

        //import_items
        $this->db->from('tblimport_items');
        $this->db->where('tblimport_items.product_id', $id);
        $this->db->where('tblimport_items.type', 'product');
        $this->db->limit(1);
        $q = $this->db->get()->num_rows();
        if ($q) {
            return $q;
        }

        //productions orders items
        $this->db->from('tbl_productions_orders_items');
        $this->db->where('tbl_productions_orders_items.items_id', $id);
        $this->db->where('tbl_productions_orders_items.type_items', 'products');
        $this->db->limit(1);
        $q = $this->db->get()->num_rows();
        if ($q) {
            return $q;
        }

        //productions plan items
        $this->db->from('tbl_productions_plan_items');
        $this->db->where('tbl_productions_plan_items.product_id', $id);
        $this->db->limit(1);
        $q = $this->db->get()->num_rows();
        if ($q) {
            return $q;
        }

        return false;
    }

    public function checkParentId($id)
    {
        $this->db->from('tbl_category_products');
        $this->db->where('tbl_category_products.parent_id', $id);
        return $this->db->get()->num_rows();
    }

    public function getUnitsByArrId($arr_id = [])
    {
        $this->db->select('*');
        $this->db->from('tblunits');
        $this->db->where_in('unitid', $arr_id);
        return $this->db->get()->result_array();
    }

    public function rowExchangeItems($item_id, $unit_id)
    {
        $this->db->select('*');
        $this->db->from('tbl_exchange_items');
        $this->db->where('tbl_exchange_items.item_id', $item_id);
        $this->db->where('tbl_exchange_items.unit_id', $unit_id);
        $this->db->limit(1);
        return $this->db->get()->row_array();
    }

    public function insertCategoryBOM($data, $status = "unapplication", $id = 0, $actions = 'add') {
        if (!empty($data))
        {
            if ($actions == 'add' || $actions == "copy")
            {
                $bom_id = $this->db->insert('tbl_boms', [
                    'versions' => $data['versions'],
                    'date_start' => $data['date_start'],
                    'date_end' => $data['date_end'],
                    'status_bom' => $data['status_bom'],
                    'date_created' => date('Y-m-d H:i:s'),
                    'created_by' => get_staff_user_id(),
                ]);
                $bom_id = $this->db->insert_id();
            } else if ($actions = 'edit') {
                $this->db->where('id', $id);
                $up = $this->db->update('tbl_boms', [
                    'versions' => $data['versions'],
                    'date_start' => $data['date_start'],
                    'date_end' => $data['date_end'],
                    'status_bom' => $data['status_bom'],
                    'date_updated' => date('Y-m-d H:i:s'),
                    'updated_by' => get_staff_user_id(),
                ]);
                if ($up) {
                    $bom_id = $id;
                    $bom_elements = $this->getBomsElementByBomId($id);
                    $this->db->where('tbl_boms_element.bom_id', $id);
                    if ($this->db->delete('tbl_boms_element')) {
                        foreach ($bom_elements as $k => $val) {
                            $this->db->where('tbl_boms_element_items.bom_element_id', $val['id']);
                            $this->db->delete('tbl_boms_element_items');
                        }
                    }
                }
            }
            if (!empty($bom_id)) {
                $element = $data['element'];
                if (!empty($element))
                {
                    foreach ($element as $k => $val) {
                        $el_id = $this->db->insert('tbl_boms_element', [
                            'bom_id' => $bom_id,
                            'element_name' => $val['element_name'],
                            'quantity' => $val['element_number'],
                        ]);
                        $el_id = $this->db->insert_id();
                        if ($el_id) {
                            if (!empty($val['items']))
                            {
                                $items = $val['items'];
                                foreach ($items as $v) {
                                    $this->db->insert('tbl_boms_element_items', [
                                        'bom_element_id' => $el_id,
                                        'type' => $v['type'],
                                        'item_id' => $v['item_id'],
                                        'unit_id' => $v['unit_id'],
                                        'quantity' => $v['element_item_number'],
                                    ]);
                                }
                            }
                        }
                    }
                }
                return true;
            }
            return false;
        }
        return false;
    }
    //
    public function rowBomById($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_boms');
        $this->db->where('tbl_boms.id', $id);
        return $this->db->get()->row_array();
    }

    public function getBomsElementByBomId($bom_id)
    {
        $this->db->select('*');
        $this->db->from('tbl_boms_element');
        $this->db->where('tbl_boms_element.bom_id', $bom_id);
        return $this->db->get()->result_array();
    }

    public function getBomsElementItemsByBEI($bom_element_id, $type = false)
    {
        $this->db->select('tbl_boms_element_items.*, tblunits.unit as unit');
        $this->db->from('tbl_boms_element_items');
        $this->db->join('tblunits', 'tblunits.unitid = tbl_boms_element_items.unit_id', 'left');
        $this->db->where('tbl_boms_element_items.bom_element_id', $bom_element_id);
        if ($type) {
            $this->db->where_in('tbl_boms_element_items.type', $type);
        }
        return $this->db->get()->result_array();
    }

    public function deleteCategoryBomById($id)
    {
        $this->db->where('tbl_boms.id', $id);
        if ($this->db->delete('tbl_boms')) {
            $bom_elements = $this->getBomsElementByBomId($id);
            $this->db->where('tbl_boms_element.bom_id', $id);
            if ($this->db->delete('tbl_boms_element')) {
                foreach ($bom_elements as $k => $val) {
                    $this->db->where('tbl_boms_element_items.bom_element_id', $val['id']);
                    $this->db->delete('tbl_boms_element_items');
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public function getBoms($not_version = false)
    {
        $this->db->select('*');
        $this->db->from('tbl_boms');
        if (!empty($not_version)) {
            $this->db->where_not_in('tbl_boms.versions', $not_version);
        }
        $this->db->where('tbl_boms.status_bom', 'active');
        return $this->db->get()->result_array();
    }

    public function rowBomByVersion($versions)
    {
        $this->db->select('*');
        $this->db->from('tbl_boms');
        $this->db->where('tbl_boms.versions', $versions);
        return $this->db->get()->row_array();
    }

    public function insertBatchProductWarehouse($data)
    {
        return $this->db->insert_batch('tbl_product_warehouse', $data);
    }

    public function insertBatchProductSuppliers($data)
    {
        return $this->db->insert_batch('tbl_product_suppliers', $data);
    }

    public function deleteProductSuppliersByProductId($product_id)
    {
        $this->db->where('product_id', $product_id);
        return $this->db->delete('tbl_product_suppliers');
    }

    public function deleteProductWarehouseByProductId($product_id)
    {
        $this->db->where('product_id', $product_id);
        return $this->db->delete('tbl_product_warehouse');
    }

    public function getGroupProductSuppliers($product_id) {
        $this->db->select('tblsuppliers.company as supplier_company, tbl_product_suppliers.supplier_id');
        $this->db->from('tbl_product_suppliers');
        $this->db->join('tblsuppliers', 'tblsuppliers.id = tbl_product_suppliers.supplier_id');
        $this->db->where('tbl_product_suppliers.product_id', $product_id);
        $this->db->group_by('tbl_product_suppliers.supplier_id');
        return $this->db->get()->result_array();
    }

    public function getProductSuppliersByProductAndSupplier($product_id, $supplier_id)
    {
        $this->db->select('tbl_product_suppliers.*, tblprocedure_client_detail.name as procedure_detail_name', false);
        $this->db->from('tbl_product_suppliers');
        $this->db->join('tblprocedure_client_detail', 'tblprocedure_client_detail.id = tbl_product_suppliers.procedure_id');
        $this->db->where('tbl_product_suppliers.product_id', $product_id);
        $this->db->where('tbl_product_suppliers.supplier_id', $supplier_id);
        $this->db->order_by('tbl_product_suppliers.sequence', 'asc');
        return $this->db->get()->result_array();
    }

    public function getProductWarehouse($product_id)
    {
        $this->db->select('tbl_product_warehouse.*, tblwarehouse.name as warehouse_name, tbllocaltion_warehouses.name as location_name');
        $this->db->from('tbl_product_warehouse');
        $this->db->join('tblwarehouse', 'tblwarehouse.id = tbl_product_warehouse.warehouse_id');
        $this->db->join('tbllocaltion_warehouses', 'tbllocaltion_warehouses.id = tbl_product_warehouse.location_id');
        $this->db->where('tbl_product_warehouse.product_id', $product_id);
        return $this->db->get()->result_array();
    }

    public function getProductSuppliersByProductId($product_id)
    {
        $this->db->select('tbl_product_suppliers.*', false);
        $this->db->from('tbl_product_suppliers');
        $this->db->where('tbl_product_suppliers.product_id', $product_id);
        return $this->db->get()->result_array();
    }

    public function getBomsProducts($product_id)
    {
        $this->db->select('*');
        $this->db->from('tbl_product_versions');
        $this->db->where('tbl_product_versions.product_id', $product_id);
        return $this->db->get()->result_array();
    }

    public function getProductVersionsByNotIdAndProduct($versions = false, $product_id)
    {
        $this->db->select('*');
        $this->db->from('tbl_product_versions');
        if (!empty($versions)) {
            $this->db->where_not_in('tbl_product_versions.versions', $versions);
        }
        $this->db->where('tbl_product_versions.product_id', $product_id);
        return $this->db->get()->result_array();
    }
}