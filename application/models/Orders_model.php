<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Orders_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function add($data = false)
    {
        if (!empty($data)) {
            $items = !empty($data['items']) ? $data['items'] : [];

            unset($data['items']);
            if(empty($items)) {
                return false;
            }
            $data['prefix'] = get_option('prefix_c_orders');
            $data['create_by'] = get_staff_user_id();
            $data['date_create'] = date('Y-m-d H:i:s');
            $data['date_want_to_receive'] = !empty($data['date_want_to_receive']) ? to_sql_date($data['date_want_to_receive'], true) : NULL;
            $data['date'] = to_sql_date($data['date']);
            $data['guest_giving'] = C_initNumber($data['guest_giving']);
            $data['total_cost_trans'] = C_initNumber($data['total_cost_trans']);

            if(!empty($data['shipping']))
            {
                $this->db->where('id', $data['shipping']);
                $shipping_client = $this->db->get('tblshipping_client')->row();
                if(!empty($shipping_client))
                {
                    $data['address_shipping'] = $shipping_client->address;
                }
            }
            $this->db->insert('tblorders', $data);
            $id = $this->db->insert_id();
            if (!empty($id)) {
				if(empty($data['draft'])) {
                    ChangeTag_manuals('client', $data['client'], $data['advisory_lead_id'], [
                    'advisory' => $data['advisory_lead_id'],
                    'orders' => $id
                ]);
				}
                $this->db->where('type', 'orders');
                $procedure_client = $this->db->get('tblprocedure_client')->row();
                if(!empty($procedure_client))
                {
                    $this->db->where('id_detail', $procedure_client->id);
                    $procedure_detail = $this->db->get('tblprocedure_client_detail')->result_array();
                }

                if(!empty($data['id_quotes_orders']))
                {
                    $this->db->where('id', $data['id_quotes_orders']);
                    $this->db->update('tblquotes_orders', ['status' => 1]);
                }
                $assInsert = 0;
                $_data = [
                    'total' => 0, // tổng tiền gốc
                    'total_item' => 0, // tổng số loại sản phẩm
                    'grand_total' => 0, // tổng giá trị sau chiết khấu
                    'total_international' => 0, // tổng tiền USD gốc    
                    'total_cost_trans_international' => 0, // tổng tiền USD vận chuyển
                    'grand_total_international' => 0, // tổng giá trị USD sau chiết khấu
                    'total_quantity' => 0, // tổng số lượng sản phẩm
                    'total_discount_percent' => 0, // tổng tiền chiết khấu theo %
                    'total_discount_money' => 0, // tổng số tiền chiết khấu tiền thẳng
                ]; //Các trường tổng

                foreach ($items as $kItem => $vItem) {
                    if(!empty($vItem['id_product']) && !empty($vItem['type_items']))
                    {
                        if(!is_numeric($vItem['id_product']))
                        {
                            $product = explode('_', $vItem['id_product']);
                            $vItem['id_product'] = $product[1];
                        }

                        if(!empty($vItem['shipping']))
                        {
                            $Shipping_orders =  $vItem['shipping'];
                            unset($vItem['shipping']);
                        }

                        $vItem['price'] = C_initNumber($vItem['price']);
                        $vItem['quantity'] = C_initNumber($vItem['quantity']);
                        $vItem['discount'] = C_initNumber($vItem['discount']);

                        $total = $vItem['price'] * $vItem['quantity'];
//                        $total_cost_trans = $vItem['cost_trans'];
                        $total_quantity = $vItem['quantity'];

                        $total_discount_percent = 0;
                        $total_discount_money = 0;

                        if ($vItem['type_discount'] == 1) {
                            $total_discount_percent = ($vItem['quantity'] * $vItem['price']) * ($vItem['discount'] / 100);
                        } else if ($vItem['type_discount'] == 2) {
                            $total_discount_money = $vItem['discount'];
                        }
                        $grand_total = $total - $total_discount_percent - $total_discount_money;
                        $_data['total'] += $total;
//                        $_data['total_cost_trans'] += $total_cost_trans;
                        $_data['grand_total'] += $grand_total;
                        $_data['total_quantity'] += $total_quantity;
                        $_data['total_discount_percent'] += $total_discount_percent;
                        $_data['total_discount_money'] += $total_discount_money;
                        $_data['total_item']++;


                        $vItem['total'] = $total;
                        $vItem['money_discount'] = $total_discount_money;
                        $vItem['grand_total'] = $grand_total;
                        $vItem['id_orders'] = $id;

                        if(!empty($vItem['id_customer'])) {
	                        $id_customer = explode('_', $vItem['id_customer']);
	                        $vItem['id_customer'] = $id_customer[1];
	                        $vItem['type_customer'] = $id_customer[0];
                        }

                        if ($vItem['type_items'] == "items")
                        {
                            $this->db->where('id', $vItem['id_product']);
                            $inItems = $this->db->get(db_prefix().'items')->row();
                            $vItem['name_product'] = $inItems->name;
                            $vItem['code_product'] = $inItems->code;
                        } else if ($vItem['type_items'] == "products") {
                            $inItems = $this->products_model->rowProduct($vItem['id_product']);
                            $vItem['name_product'] = $inItems['name'];
                            $vItem['code_product'] = $inItems['code'];
                        }

                        if (empty($inItems)) continue;
                        $this->db->insert('tblorders_items', $vItem);
                        if(!empty($this->db->insert_id()))
                        {
                            $id_detail = $this->db->insert_id();
                            //Thêm chi tiết giao hàng dự kiến
                            if(!empty($Shipping_orders))
                            {
                                foreach($Shipping_orders as $key => $value)
                                {
                                    if(!empty($value['date_shipping']) && !empty($value['quantity_shipping']))
                                    {
                                        $this->db->insert('tblorders_detail_shipping', [
                                            'id_orders' => $id,
                                            'id_detail' => $id_detail,
                                            'id_product' => $vItem['id_product'],
                                            'date_shipping' => to_sql_date($value['date_shipping']),
                                            'quantity_shipping' => $value['quantity_shipping']
                                        ]);
                                    }
                                }
                            }

                            if(!empty($procedure_detail))
                            {
                                $_date = $data['date'];
                                $this->db->where('id_orders', $id);
                                $orders_items = $this->db->get('tblorders_items')->result_array();
                                foreach($procedure_detail as $korders_item => $vorder_item)
                                {
                                    $leadtime = $vorder_item['leadtime'];
                                    $_date =  date("Y-m-d", strtotime("$_date +$leadtime day"));

                                    $array_step = [
                                        'id_orders' => $id,
                                        'id_procedure' => $vorder_item['id'],
                                        'id_orders_item' => $id_detail,
                                        'order_by' => $vorder_item['orders'],
                                        'name_procedure' => $vorder_item['name'],
                                        'date_expected' => $_date,
                                        'color' => $vorder_item['color']
                                    ];
                                    if($vorder_item['orders'] == 1)
                                    {
                                        $array_step['date_create'] = date('Y-m-d');
                                        $array_step['id_staff'] = get_staff_user_id();
                                        $array_step['active'] = 1;
                                        $this->db->where('id', $id_detail);
                                        $this->db->update('tblorders_items', ['statusActive' => $vorder_item['id']]);       
                                    }                             
                                    $this->db->insert('tblorders_step', $array_step);
                                }
                            }

                            $assInsert++;
                        }
                    }

                }
                if(empty($data['draft']))
                {
	                CreateCode('orders', $id);
                }
                else
                {
	                CreateCode('orders_draft', $id);
                }
                $currencies = get_table_where('tblcurrencies', array('id' => $data['currencies_id']),'','row');
                $_data['total_international'] = round($_data['total']/$currencies->amount_to_vnd, 2);
	            $_data['grand_total'] += $data['total_cost_trans'];
                $_data['total_cost_trans_international'] = round($data['total_cost_trans'] / $currencies->amount_to_vnd, 2);
                $_data['grand_total_international'] = round($_data['grand_total']/$currencies->amount_to_vnd, 2);
                $this->db->where('id', $id);
                $this->db->update('tblorders', $_data);
               
                if(!empty($id) && $assInsert > 0)
                {
                    return true;
                }
            }
        }
        return false;
    }

    public function update($id, $data = false)
    {
        if (!empty($id) && !empty($data)) {
            $items = !empty($data['items']) ? $data['items'] : [];
            $items_update = !empty($data['items_update']) ? $data['items_update'] : [];
            unset($data['id']);
            unset($data['items']);
            unset($data['items_update']);

            $data['prefix'] = get_option('prefix_c_orders');
            $data['create_by'] = get_staff_user_id();
            $data['date_create'] = date('Y-m-d H:i:s');
            $data['date'] = to_sql_date($data['date'], true);
            $data['guest_giving'] = C_initNumber($data['guest_giving']);
            $data['date_want_to_receive'] = to_sql_date($data['date_want_to_receive'], true);
	        $data['total_cost_trans'] = C_initNumber($data['total_cost_trans']);

            if(!empty($data['shipping']))
            {
                $this->db->where('id', $data['shipping']);
                $shipping_client = $this->db->get('tblshipping_client')->row();
                if(!empty($shipping_client))
                {
                    $data['address_shipping'] = $shipping_client->address;
                }
            }

            $this->db->where('id', $id);
            if($this->db->update(db_prefix() . 'orders', $data)) {

                $items_not_delete = [];
                $assInsert = 0;
                $_data = [
                    'total' => 0, // tổng tiền gốc
//                    'total_cost_trans' => 0, // tổng tiền vận chuyển
                    'total_item' => 0, // tổng số loại sản phẩm
                    'grand_total' => 0, // tổng giá trị sau chiết khấu
                    'total_international' => 0, // tổng tiền USD gốc    
                    'total_cost_trans_international' => 0, // tổng tiền USD vận chuyển
                    'grand_total_international' => 0, // tổng giá trị USD sau chiết khấu
                    'total_quantity' => 0, // tổng số lượng sản phẩm
                    'total_discount_percent' => 0, // tổng tiền chiết khấu theo %
                    'total_discount_money' => 0, // tổng số tiền chiết khấu tiền thẳng
                ]; //Các trường tổng



                $this->db->where('type', 'orders');
                $procedure_client = $this->db->get('tblprocedure_client')->row();
                if(!empty($procedure_client))
                {
                    $this->db->where('id_detail', $procedure_client->id);
                    $procedure_detail = $this->db->get('tblprocedure_client_detail')->result_array();
                }

                foreach ($items as $kItem => $vItem) {
                    if(!empty($vItem['id_product']) && !empty($vItem['type_items'])) {

                        if(!is_numeric($vItem['id_product']))
                        {
                            $product = explode('_', $vItem['id_product']);
                            $vItem['id_product'] = $product[1];
                        }

                        $Shipping_orders = $vItem['shipping'];
                        unset($vItem['shipping']);
                        $vItem['price'] = C_initNumber($vItem['price']);
                        $vItem['quantity'] = C_initNumber($vItem['quantity']);
                        $vItem['discount'] = C_initNumber($vItem['discount']);

                        $total = $vItem['price'] * $vItem['quantity'];
                        $total_quantity = $vItem['quantity'];

                        $total_discount_percent = 0;
                        $total_discount_money = 0;

                        if ($vItem['type_discount'] == 1) {
                            $total_discount_percent = ($vItem['quantity'] * $vItem['price']) * ($vItem['discount'] / 100);
                        } else if ($vItem['type_discount'] == 2) {
                            $total_discount_money = $vItem['discount'];
                        }
                        $grand_total = $total - $total_discount_percent - $total_discount_money;
                        $_data['total'] += $total;
                        $_data['grand_total'] += $grand_total;
                        $_data['total_quantity'] += $total_quantity;
                        $_data['total_discount_percent'] += $total_discount_percent;
                        $_data['total_discount_money'] += $total_discount_money;
                        $_data['total_item']++;

                        $vItem['total'] = $total;
                        $vItem['money_discount'] = $total_discount_money;
                        $vItem['grand_total'] = $grand_total;
                        $vItem['id_orders'] = $id;

	                    if(!empty($vItem['id_customer'])) {
		                    $id_customer = explode('_', $vItem['id_customer']);
		                    $vItem['id_customer'] = $id_customer[1];
		                    $vItem['type_customer'] = $id_customer[0];
	                    }

                        if ($vItem['type_items'] == "items")
                        {
                            $this->db->where('id', $vItem['id_product']);
                            $inItems = $this->db->get(db_prefix().'items')->row();
                            $vItem['name_product'] = $inItems->name;
                            $vItem['code_product'] = $inItems->code;
                        } else if ($vItem['type_items'] == "products") {
                            $inItems = $this->products_model->rowProduct($vItem['id_product']);
                            $vItem['name_product'] = $inItems['name'];
                            $vItem['code_product'] = $inItems['code'];
                        }
                        if (empty($inItems)) continue;
                        
                        $this->db->insert('tblorders_items', $vItem);
                        if ($this->db->insert_id()) {
                            $items_not_delete[] = $this->db->insert_id();
                            $id_detail = $this->db->insert_id();
                            //Thêm chi tiết giao hàng dự kiến
                            if(!empty($Shipping_orders))
                            {
                                foreach($Shipping_orders as $key => $value)
                                {
                                    if(!empty($value['date_shipping']) && !empty($value['quantity_shipping']))
                                    {
                                        $this->db->insert('tblorders_detail_shipping', [
                                            'id_orders' => $id,
                                            'id_detail' => $id_detail,
                                            'id_product' => $vItem['id_product'],
                                            'date_shipping' => to_sql_date($value['date_shipping']),
                                            'quantity_shipping' => $value['quantity_shipping']
                                        ]);
                                    }
                                }
                                if(!empty($procedure_client))
                                {

                                    $_date = $data['date'];
                                    $this->db->where('id_orders', $id);
                                    $orders_items = $this->db->get('tblorders_items')->result_array();
                                    foreach($procedure_detail as $korders_item => $vorder_item)
                                    {
                                        $leadtime = $vorder_item['leadtime'];
                                        $_date =  date("Y-m-d", strtotime("$_date +$leadtime day"));

                                        $array_step = [
                                            'id_orders' => $id,
                                            'id_procedure' => $vorder_item['id'],
                                            'order_by' => $vorder_item['orders'],
                                            'id_orders_item' => $id_detail,
                                            'name_procedure' => $vorder_item['name'],
                                            'date_expected' => $_date,
                                            'color' => $vorder_item['color']
                                        ];
                                        if($vorder_item['orders'] == 1)
                                        {
                                            $array_step['date_create'] = date('Y-m-d');
                                            $array_step['id_staff'] = get_staff_user_id();
                                            $array_step['active'] = 1;
                                            $this->db->where('id', $id_detail);
                                            $this->db->update('tblorders_items', ['statusActive' => $vorder_item['id']]);            
                                        }   
                                        $this->db->insert('tblorders_step', $array_step);
                                    }
                                }
                            }
                            $assInsert++;
                        }
                    }

                }

                foreach ($items_update as $kUpdate => $vUpdate) {
                    if(!empty($vUpdate['id_product'])) {
                        if (empty($vUpdate['id'])) {
                            continue;
                        }

                        if(!is_numeric($vUpdate['id_product']))
                        {
                            $product = explode('_', $vUpdate['id_product']);
                            $vUpdate['id_product'] = $product[1];
                        }
                        $Shipping_orders = !empty($vUpdate['shipping']) ? $vUpdate['shipping'] : [];
                        unset($vUpdate['shipping']);

                        $vUpdate['price'] = C_initNumber($vUpdate['price']);
                        $vUpdate['quantity'] = C_initNumber($vUpdate['quantity']);
                        $vUpdate['discount'] = C_initNumber($vUpdate['discount']);

                        $total = $vUpdate['price'] * $vUpdate['quantity'];
                        $total_quantity = $vUpdate['quantity'];
                        $total_discount_percent = 0;
                        $total_discount_money = 0;

                        if ($vUpdate['type_discount'] == 1) {
                            $total_discount_percent = ($vUpdate['quantity'] * $vUpdate['price']) * ($vUpdate['discount'] / 100);
                        } else if ($vUpdate['type_discount'] == 2) {
                            $total_discount_money = $vUpdate['discount'];
                        }
                        $grand_total = $total - $total_discount_percent - $total_discount_money;

                        $_data['total'] += $total;
                        $_data['grand_total'] += $grand_total;
                        $_data['total_quantity'] += $total_quantity;
                        $_data['total_discount_percent'] += $total_discount_percent;
                        $_data['total_discount_money'] += $total_discount_money;
                        $_data['total_item']++;

                        $vUpdate['total'] = $total;
                        $vUpdate['money_discount'] = $total_discount_money;
                        $vUpdate['grand_total'] = $grand_total;
                        $vUpdate['id_orders'] = $id;

	                    if(!empty($vUpdate['id_customer'])) {
		                    $id_customer = explode('_', $vUpdate['id_customer']);
		                    $vUpdate['id_customer'] = $id_customer[1];
		                    $vUpdate['type_customer'] = $id_customer[0];
	                    }

                        $this->db->where('id', $vUpdate['id']);
                        if ($this->db->update(db_prefix() . 'orders_items', $vUpdate)) {
                            $items_not_delete[] = $vUpdate['id'];
                            //Thêm mới chi tiết giao hàng dự kiến
                            if(!empty($Shipping_orders))
                            {
                                $array_delete_shipping = [];
                                $id_detail = $vUpdate['id'];
                                foreach($Shipping_orders as $key => $value)
                                {
                                    if(!empty($value['date_shipping']) && !empty($value['quantity_shipping']))
                                    {
                                        if(!empty($value['id']))
                                        {
                                            $this->db->where('id', $value['id']);
                                            $updateShipping = $this->db->update('tblorders_detail_shipping', [
                                                'id_product' => $vUpdate['id_product'],
                                                'date_shipping' => to_sql_date($value['date_shipping']),
                                                'quantity_shipping' => $value['quantity_shipping']
                                            ]);
                                            if(!empty($updateShipping))
                                            {
                                                $array_delete_shipping[] =  $value['id'];
                                            }
                                        }
                                        else
                                        {
                                            $this->db->insert('tblorders_detail_shipping', [
                                                'id_detail' => $id_detail,
                                                'id_orders' => $id,
                                                'id_product' => $vUpdate['id_product'],
                                                'date_shipping' => to_sql_date($value['date_shipping']),
                                                'quantity_shipping' => $value['quantity_shipping']
                                            ]);
                                            $insertShipping = $this->db->insert_id();
                                            if(!empty($insertShipping))
                                            {
                                                $array_delete_shipping[] =  $insertShipping;
                                            }
                                        }
                                        //Xóa các shipping không tìm thấy trong cập nhật
                                        $this->db->where('id_detail', $id_detail);
                                        $this->db->where('id_orders', $id);
                                        if(!empty($array_delete_shipping))
                                        {
                                            $this->db->where_not_in('id', $array_delete_shipping);
                                        }
                                        $this->db->delete('tblorders_detail_shipping');
                                    }
                                }
                            }

                            $assInsert++;
                        }
                    }
                }

                $currencies = get_table_where('tblcurrencies',array('id'=>$data['currencies_id']),'','row');

                $_data['total_international'] = round($_data['total']/$currencies->amount_to_vnd, 2);

                $_data['total_cost_trans_international'] = round($data['total_cost_trans'] / $currencies->amount_to_vnd, 2);

                $_data['grand_total'] += $data['total_cost_trans'];

                $_data['grand_total_international'] = round($_data['grand_total']/$currencies->amount_to_vnd, 2);

                $this->db->where('id', $id);
                $this->db->update('tblorders', $_data);


                $this->db->select('group_concat(id) as listid');
                if(!empty($items_not_delete))
                {
                    $this->db->where_not_in('id', $items_not_delete);
                }
                $this->db->where('id_orders', $id);
                $orders_items_delete = $this->db->get('tblorders_items')->row();
                if(!empty($orders_items_delete->listid))
                {
                    $this->db->where('id_orders_item', explode(',', $orders_items_delete->listid));
                    $this->db->delete('tblorders_step');
                }

                if(!empty($items_not_delete))
                {
                    $this->db->where_not_in('id', $items_not_delete);
                }
                $this->db->where('id_orders', $id);
                $this->db->delete('tblorders_items');

                $this->db->where('id_orders', $id);
                if(!empty($items_not_delete))
                {
                    $this->db->where_not_in('id_detail', $items_not_delete);
                }
                $this->db->delete('tblorders_detail_shipping');

                if($assInsert >0)
                {
                    return true;
                }
            }
        }
        return false;
    }

    public function get($id = "")
    {
        if(!empty($id))
        {
            $this->db->where('id', $id);
            $orders = $this->db->get(db_prefix().'orders')->row();
            if(!empty($orders))
            {
                $this->db->select(
                    db_prefix().'orders_items.*,
                    IF(tblorders_items.type_items = "items", '.db_prefix().'items.name, tbl_products.name) as name,
                    IF(tblorders_items.type_items = "items", tblitems.avatar, CONCAT("uploads/products/", "", tbl_products.images, "")) as avatar,
                    IF(tblorders_items.type_items = "items", '.db_prefix().'items.code, tbl_products.code) as code_items
                ');
                $this->db->where('id_orders', $id);
                $this->db->join('tblitems', 'tblitems.id = tblorders_items.id_product AND tblorders_items.type_items = "items"', 'left');

                $this->db->join('tbl_products', 'tbl_products.id = tblorders_items.id_product AND tblorders_items.type_items = "products"', 'left');
                $orders->detail = $this->db->get(db_prefix().'orders_items')->result();
                foreach($orders->detail as $key => $value)
                {
                    $this->db->where('id_detail', $value->id);
                    $orders->detail[$key]->shipping = $this->db->get('tblorders_detail_shipping')->result();
                }
                return $orders;
            }
        }
        return false;
    }

    public function get_view($id = "")
    {
        if(!empty($id))
        {
            $this->db->select(
                'tblorders.*, tblclients.name_system,
                concat(COALESCE(tbladvisory_lead.prefix), COALESCE(tbladvisory_lead.code), "-", COALESCE(tbladvisory_lead.type_code)) as fullcode_advisory_lead,
                tblclients.code_system,
                tblclients.zcode,
                concat(COALESCE(prefix_client), COALESCE(code_client)," - ", COALESCE(tblclients.code_type)) as full_code_client,
                concat(COALESCE(prefix_lead), COALESCE(code_lead)," - ", COALESCE(tblleads.code_type)) as full_code_lead,
                tblshipping_client.name as name_shipping,
                tblshipping_client.phone as phone_shipping,
                tblshipping_client.address as address_shipping,
                '
            );
            $this->db->where('tblorders.id', $id);
            $this->db->join('tblclients', 'tblclients.userid = tblorders.client', 'left');
            $this->db->join('tblleads', 'tblleads.id = tblclients.leadid', 'left');
            $this->db->join('tbladvisory_lead', 'tbladvisory_lead.id = tblorders.advisory_lead_id', 'left');
            $this->db->join('tblshipping_client', 'tblshipping_client.id = tblorders.shipping', 'left');
            $orders = $this->db->get('tblorders')->row();
            if(!empty($orders))
            {
                $this->db->select(
                    'tblorders_items.*,
                    IF(tblorders_items.type_items = "items", tblitems.name, tbl_products.name) as name,
                    IF(tblorders_items.type_items = "items", tblitems.avatar, CONCAT("uploads/products/", "", tbl_products.images, "")) as avatar,
                    IF(tblorders_items.type_items = "items", tblitems.code, tbl_products.code) as code_items
                ');
                $this->db->where('id_orders', $id);
                $this->db->join('tblitems', 'tblitems.id = tblorders_items.id_product AND tblorders_items.type_items = "items"', 'left');

                $this->db->join('tbl_products', 'tbl_products.id = tblorders_items.id_product AND tblorders_items.type_items = "products"', 'left');
                $orders->detail = $this->db->get('tblorders_items')->result();
                foreach($orders->detail as $key => $value)
                {
                    $this->db->where('id_detail', $value->id);
                    $orders->detail[$key]->shipping = $this->db->get('tblorders_detail_shipping')->result();
                }
                return $orders;
            }
        }
        return false;
    }

    public function getClientOrder($client = "")
    {
        if(!empty($client))
        {
            $this->db->select('tblorders.*, tblclients.company');
            $this->db->where('client', $client);
            $this->db->where('status != -3');
            $this->db->join('tblclients', 'tblclients.userid = tblorders.client');
            $orders = $this->db->get('orders')->result();
            if(!empty($orders))
            {
                foreach($orders as $Korder => $Vorder)
                {
                    $this->db->select(
                        'tblorders_items.*,
                        IF(tblorders_items.type_items = "items", tblitems.name, tbl_products.name) as name,
                        IF(tblorders_items.type_items = "items", tblitems.avatar, CONCAT("uploads/products/", "", tbl_products.images, "")) as avatar,
                        IF(tblorders_items.type_items = "items", tblitems.code, tbl_products.code) as code_items
                    ');
                    $this->db->where('id_orders', $Vorder->id);
                    $this->db->join('tblitems', 'tblitems.id = tblorders_items.id_product AND tblorders_items.type_items = "items"', 'left');
                    $this->db->join('tbl_products', 'tbl_products.id = tblorders_items.id_product AND tblorders_items.type_items = "products"', 'left');
                    $orders[$Korder]->detail = $this->db->get('tblorders_items')->result();
                }
            }
            return $orders;
        }
        return false;
    }

    public function getClientOrderOr($client = "", $draft = [])
    {
        if(!empty($client) || (!empty($draft) && !empty($draft['id_object_draft']) && !empty($draft['type_object_draft'])))
        {
            $this->db->select('tblorders.*');

	        if(!empty($client))
	        {
		        $this->db->group_start();
	            $this->db->where('client', $client);
	            $this->db->where('status != -3');
		        $this->db->group_end();
	        }

	        if(!empty($draft) && !empty($draft['id_object_draft']) && !empty($draft['type_object_draft']))
	        {
		        $this->db->or_group_start();
		        $this->db->where('draft', 1);
		        $this->db->where('id_object_draft', $draft['id_object_draft']);
		        $this->db->where('type_object_draft', $draft['type_object_draft']);
		        $this->db->group_end();
	        }
            $orders = $this->db->get('orders')->result();
            if(!empty($orders))
            {
                foreach($orders as $Korder => $Vorder)
                {
                    $this->db->select(
                        'tblorders_items.*,
                        IF(tblorders_items.type_items = "items", tblitems.name, tbl_products.name) as name,
                        IF(tblorders_items.type_items = "items", tblitems.avatar, CONCAT("uploads/products/", "", tbl_products.images, "")) as avatar,
                        IF(tblorders_items.type_items = "items", tblitems.code, tbl_products.code) as code_items
                    ');
                    $this->db->where('id_orders', $Vorder->id);
                    $this->db->join('tblitems', 'tblitems.id = tblorders_items.id_product AND tblorders_items.type_items = "items"', 'left');
                    $this->db->join('tbl_products', 'tbl_products.id = tblorders_items.id_product AND tblorders_items.type_items = "products"', 'left');
                    $orders[$Korder]->detail = $this->db->get('tblorders_items')->result();
                }
            }
            return $orders;
        }
        return false;
    }

    public function get_status_orders($id = NULL, $status = 0)
    {
        $name_status = "";
        if($status > 0)
        {
            $this->db->select('id_procedure, name_procedure, name');
            $this->db->where('id_procedure', $status);
            $this->db->where('id_orders', $id);
            $this->db->join('tblprocedure_client_detail', 'tblprocedure_client_detail.id = tblorders_step.id_procedure', 'left');
            $orders_step = $this->db->get(db_prefix().'orders_step')->row();
            if(!empty($orders_step))
            {
                $name_status = !empty($orders_step->name) ? $orders_step->name : $orders_step->name_procedure.'('._l('cong_not_found').')';
            }
            else
            {
                $name_status = _l('cong_not_found');
            }
        }
        else
        {
            if($status == 0)
            {
                $name_status = _l('cong_orders_warning');
            }
            else if($status == -1)
            {
                $name_status = _l('cong_orders_success');
            }
            else if($status == -2)
            {
                $name_status = _l('cong_orders_delay');
            }
            else if($status == -3)
            {
                $name_status = _l('cong_orders_cancel');
            }
        }
        return $name_status;
    }

    public function updateOrders($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tblorders', $data);
    }

    public function updateOrdersByProductionsPlan($productions_plan_id, $data)
    {
        $this->db->where('productions_plan_id', $productions_plan_id);
        return $this->db->update('tblorders', $data);
    }

    public function insertOrders($data) {
        $this->db->insert('tblorders', $data);
        return $this->db->insert_id();
    }

    public function insertOrdersItems($data) {
        $this->db->insert('tblorders_items', $data);
        return $this->db->insert_id();
    }

    public function insertOrdersDetailShipping($data) {
        $this->db->insert('tblorders_detail_shipping', $data);
        return $this->db->insert_id();
    }

}
