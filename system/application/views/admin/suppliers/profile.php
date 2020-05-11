
<h4 class="bold no-margin"><?php echo _l('supplier_add_edit_profile'); ?></h4>

  <hr class="no-mbot no-border" />
    <div class="row">
        <?php echo form_open($this->uri->uri_string(),array('class'=>'supplier-form','autocomplete'=>'off')); ?>
        <div class="additional"></div>
        <div class="col-md-12">
            <ul class="nav nav-tabs profile-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#contact_info" aria-controls="contact_info" role="tab" data-toggle="tab">
                        <?php echo _l( 'supplier_profile_details'); ?>
                    </a>
                </li>
                 <?php if(isset($supplier) && !empty($taman)){ ?>
                <li role="presentation">
                    <a href="#contracts" aria-controls="receipts" role="tab" data-toggle="tab">
                        <?php echo _l( 'Hợp Đồng'); ?>
                    </a>
                </li>
                <?php } ?>
                 <?php if(isset($supplier) && !empty($taman)){ ?>
                <li role="presentation">
                    <a href="#imports" aria-controls="receipts" role="tab" data-toggle="tab">
                        <?php echo _l( 'Nhập Kho'); ?>
                    </a>
                </li>
                <?php } ?>
                 <?php if(isset($supplier) && !empty($taman)){ ?>
                <li role="presentation">
                    <a href="#_exports" aria-controls="receipts" role="tab" data-toggle="tab">
                        <?php echo _l( 'Xuất Kho'); ?>
                    </a>
                </li>
                <?php } ?>

            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="contact_info">
                    <div class="row">
                    <?php if(!isset($supplier) || isset($supplier) && !is_empty_customer_company($supplier->userid)) { ?>
                        <div class="col-md-12">
                           <div class="checkbox checkbox-success mbot20 no-mtop">
                               <input type="checkbox" name="show_primary_contact"<?php if(isset($supplier) && $supplier->show_primary_contact == 1){echo ' checked';}?> value="1" id="show_primary_contact">
                               <label for="show_primary_contact"><?php echo _l('show_primary_contact',_l('invoices').', '._l('estimates').', '._l('payments')); ?></label>
                           </div>
                       </div>

                       <?php } ?>
                       <div class="col-md-6">
                          <div class="form-group">
                               <label for="number"><?php echo _l('Mã nhà cung cấp'); ?></label>
                               <div class="input-group">
                                <span class="input-group-addon">
                                  <?php echo get_option('prefix_supplier') ?></span>
                                  <?php
                                    // var_dump($purchase);
                                    if(!empty($supplier))
                                    {

                                      $number = str_replace(get_option('prefix_supplier'),'',$supplier->supplier_code);
                                    }
                                    else
                                    {
                                      $number = sprintf('%06d',getMaxID('userid','tblsuppliers')+1);
                                    }
                                  ?>
                                  <input type="text" name="supplier_code" class="form-control" value="<?=$number ?>" data-isedit="<?php echo !empty($isedit) ? $isedit : ''; ?>" data-original-number="<?php echo !empty($data_original_number) ? $data_original_number : ''; ?>" readonly>
                                </div>
                            </div>
                        <?php
                        $value= ( isset($supplier) ? $supplier->company : '');
                        $attrs = (isset($supplier) ? array() : array('autofocus'=>true,'required'=>'true'));
                        $value = ( isset($supplier) ? $supplier->company : ''); ?>
                        <?php echo render_input( 'company', _l("supplier_name"),$value,'text',$attrs); ?>

                        <?php
                        $c_attrs_personal = (isset($supplier) ? ($supplier->client_type == 1 ? array() : array('style' => 'display:none')) : array());
                        $short_name = ( isset($supplier) ? $supplier->short_name : "" );
                        echo render_input( 'short_name', 'client_shortname' , $short_name, 'text', array(), $c_attrs_personal); ?>


                        <?php
                        $c_attrs_personal = array();
                        $bussiness_registration_number = ( isset($supplier) ? $supplier->bussiness_registration_number : "" );
                        echo render_input( 'bussiness_registration_number', 'bussiness_registration_number' , $bussiness_registration_number, 'text', array(), $c_attrs_personal ); ?>

                        <!-- <?php
                        $legal_representative = ( isset($supplier) ? $supplier->legal_representative : "" );
                        echo render_input( 'legal_representative', 'legal_representative' , $legal_representative, 'text', array(), $c_attrs_personal ); ?> -->

                        <?php
                        $email = ( isset($supplier) ? $supplier->email : "" );
                        echo render_input( 'email', 'email' , $email ,'email',array('autocomplete'=>'off')); ?>

                        <?php
                        $cooperative_day = ( isset($supplier) ? _d($supplier->cooperative_day) : _d(date('Y-m-d')));
                        echo render_date_input( 'cooperative_day', 'cooperative_day' , $cooperative_day, array(), $c_attrs_personal ); ?>

                        <?php
                        if(get_option('company_requires_vat_number_field') == 1){
                            $value=( isset($supplier) ? $supplier->vat : '');
                            echo render_input( 'vat', 'client_vat_number',$value, 'text', array(), $c_attrs_personal);
                        }
                        $s_attrs = array('data-none-selected-text'=>_l('system_default_string'));
                        $selected = '';
                        if(isset($supplier) && client_have_transactions($supplier->userid)){
                          $s_attrs['disabled'] = true;
                      }
//                      foreach($currencies as $currency){
//                        if(isset($supplier)){
//                          if($currency['id'] == $supplier->default_currency){
//                            $selected = $currency['id'];
//                            }
//                        }
//                      }
                ?>
                <?php if(!isset($supplier)){ ?>
                <i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('customer_currency_change_notice'); ?>"></i>
                <?php } ?>
<!--                --><?php //echo render_select('default_currency',$currencies,array('id','name','symbol'),'invoice_add_edit_currency',$selected,$s_attrs); ?>
                   <?php $value=( isset($supplier) ? $supplier->debt : ''); ?>
                   <?php echo render_input( 'debt', 'debt',$value); ?>

              </div>
              <div class="col-md-6">


                <?php
                $c_attrs_personal = array('required'=>true);

                $phonenumber = ( isset($supplier) ? $supplier->phonenumber : "" );
                echo render_input( 'phonenumber', 'client_phonenumber',$phonenumber, 'text', array(), $c_attrs_personal);

                ?>



                <?php $value=( isset($supplier) ? $supplier->website : ''); ?>
                <?php echo render_input( 'website', 'client_website',$value); ?>



           </div>
            <?php if(has_permission('suppliers','','create')||(isset($supplier)&&has_permission('suppliers','','edit'))){?>
                <button class="btn btn-info mtop20 only-save customer-form-submiter">
                    <?php echo _l( 'submit'); ?>
                </button>
                <?php if(!isset($supplier)){ ?>
                    <button class="btn btn-info mtop20 save-and-add-contact customer-form-submiter">
                        <?php echo _l( 'save_customer_and_add_contact'); ?>
                    </button>
                <?php } ?>
            <?php }?>

        <?php echo form_close(); ?>

    </div>
    </div>
                <?php if(isset($supplier) && !empty($taman)){ ?>
                <div role="tabpanel" class="tab-pane" id="contacts">
                    <?php if(has_permission('customers','','create') || is_customer_admin($supplier->userid)){
                        $disable_new_contacts = false;
                        if(is_empty_customer_company($supplier->userid) && total_rows('tblcontacts',array('userid'=>$supplier->userid)) == 1){
                           $disable_new_contacts = true;
                       }
                       ?>
                       <div class="inline-block"<?php if($disable_new_contacts){ ?> data-toggle="tooltip" data-title="<?php echo _l('customer_contact_person_only_one_allowed'); ?>"<?php } ?>>
                        <a href="#" onclick="contact(<?php echo $supplier->userid; ?>); return false;" class="btn btn-info mbot25<?php if($disable_new_contacts){echo ' disabled';} ?>"><?php echo _l('new_contact'); ?></a>
                    </div>
                    <?php } ?>
                    <?php
                    $table_data = array(_l('client_firstname'),_l('client_lastname'),_l('client_email'),_l('contact_position'),_l('client_phonenumber'),_l('contact_active'),_l('clients_list_last_login'));
                    $custom_fields = get_custom_fields('contacts',array('show_on_table'=>1));
                    foreach($custom_fields as $field){
                       array_push($table_data,$field['name']);
                   }
                   array_push($table_data,_l('options'));
                   echo render_datatable($table_data,'contacts'); ?>
                </div>

        <?php } ?>

        <?php if(isset($supplier)){ ?>
                <div role="tabpanel" class="tab-pane" id="contracts">
                    <?php
                    $table_data = array(
                        _l('contract_list_code'),
                        _l('Ngày ký'),
                        _l('Thời gian trả'),
                        _l('Nguyên liệu'),
                        _l('Đơn vị tính'),
                       _l('Lượng ký'),
                       _l('Đơn giá'),
                       _l('Thành tiền'),
                       _l('Đặt cọc'),
                        _l('Lượng còn'),
                       _l('Thuộc tính'),
                    );
                   echo render_datatable($table_data,'contracts_tab'); ?>
                </div>


                <div role="tabpanel" class="tab-pane" id="_exports">

                    <?php
                    $table_data = array(
                        _l('Mã phiếu'),
                        _l('Ngày'),
                        _l('Tên'),
                        _l('Số lượng'),
                        _l('Người tạo'),
                        _l('Ghi chú'),
                        _l('Thuộc tính'),
                    );
                   echo render_datatable($table_data,'_exports_tab'); ?>
                </div>


                <div role="tabpanel" class="tab-pane" id="imports">

                    <?php
                    $table_data = array(
                        _l('Mã phiếu'),
                        _l('Tên phiếu'),
                        _l('Hợp đồng'),
                        _l('Người tạo'),
                        _l('Kho'),
                        _l('Tên'),
                        _l('Số lượng'),
                        _l('Ngày tạo'),
                        _l('Thuộc tính'),
                    );
                   echo render_datatable($table_data,'imp_internal'); ?>
                </div>

        <?php } ?>
        </div>
        </div>
    </div>
<div id="contact_data"></div>
