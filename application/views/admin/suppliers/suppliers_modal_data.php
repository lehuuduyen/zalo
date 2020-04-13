                    <?php if($type == 2){?>
                      <!-- hau -->
                    <div class="col-md-12">
                      <a onclick="edit()" class="mbot10">
                       <?php echo _l('edit'); ?>
                        <i class="fa fa-pencil-square-o"></i>
                      </a>
                    </div>
                   <?php }?>
                      <div class="col-md-12">
                        <div class="wap-left">
                          <div class="wap-left-title bold uppercase event_tab active" active-tab="1">
                            <?=_l('lead_info')?>
                          </div>
                          <?php if(!empty($info_suppliers)){
                            $tab_temp = 2;
                            foreach ($info_suppliers as $key => $value) {
                              $info_supplier_value = get_table_where('tblsuppliers_info_detail',array('id_suppliers_info'=>$value['id']));
                              if(!empty($info_supplier_value)){
                          ?>
                            <div class="wap-left-title bold uppercase event_tab" active-tab="<?=$tab_temp?>">
                              <?php echo $value['name']; ?>
                            </div>
                          <?php $tab_temp++; } } } ?>
                          <?php $custom_fields = get_custom_fields('suppliers',array()); ?>
                          <?php if(count($custom_fields) > 0) { ?>
                            <div class="wap-left-title bold uppercase event_tab" active-tab="<?=$tab_temp?>">
                              <?=_l('custom_fields')?>
                            </div>
                          <?php $tab_temp++; } ?>
                          <div class="evaluate_view_left">
                          </div>
                        </div>
                        <div class="wap-right">
                          <div class="fieldset active" role-fieldset="1">
                            <div class="wap-content firt">
                                <span class="text-muted lead-field-heading no-mtop bold"><?php echo _l('ch_code_suppliers'); ?>: </span>
                                <span class="bold font-medium-xs"><?php echo get_option('prefix_supplier') ?>-<?php echo (isset($supplierss) && $supplierss->code != '' ? $supplierss->code : '-') ?></span>
                            </div>
                            <div class="wap-content second">
                                <span class="text-muted lead-field-heading no-mtop bold"><?php echo _l('ch_name_suppliers'); ?>: </span>
                                <span class="bold font-medium-xs"><?php echo (isset($supplierss) && $supplierss->company != '' ? $supplierss->company : '-') ?></span>
                            </div>
                            <div class="wap-content firt">
                                <span class="text-muted lead-field-heading no-mtop bold"><?php echo _l('clients_phone'); ?>: </span>
                                <span class="bold font-medium-xs"><?php echo (isset($supplierss) && $supplierss->phone != '' ? '<a href="tel:'.$supplierss->phone.'">' . $supplierss->phone . '</a>' : '-') ?></span>
                            </div>
                            <div class="wap-content second">
                                <span class="text-muted lead-field-heading no-mtop bold"><?php echo _l('clients_email'); ?>: </span>
                                <span class="bold font-medium-xs"><?php echo (isset($supplierss) && $supplierss->email != '' ? '<a href="mailto:'.$supplierss->email.'">' . $supplierss->email.'</a>' : '-') ?></span>
                            </div>
                            <div class="wap-content firt">
                                <span class="text-muted lead-field-heading no-mtop bold"><?php echo _l('clients_vat'); ?>: </span>
                                <span class="bold font-medium-xs"><?php echo (isset($supplierss) && $supplierss->vat != '' ? $supplierss->vat : '-') ?></span>
                            </div>
                            <div class="wap-content second">
                                <span class="text-muted lead-field-heading no-mtop bold"><?php echo _l('invoice_add_edit_currency'); ?>: </span>
                                <span class="bold font-medium-xs"><?php echo (isset($supplierss) && !empty($supplierss->default_currency) ? $supplierss->default_currency->name : '-') ?></span>
                            </div>
                            <div class="wap-content firt">
                                <span class="text-muted lead-field-heading no-mtop bold"><?php echo _l('ch_debt_limit'); ?>: </span>
                                <span class="bold font-medium-xs"><?php echo (isset($supplierss) && !empty($supplierss->debt_limit) ? number_format($supplierss->debt_limit) : '-') ?></span>
                            </div>
                            <div class="wap-content second">
                                <span class="text-muted lead-field-heading no-mtop bold"><?php echo _l('client_address'); ?>: </span>
                                <span class="bold font-medium-xs"><?php echo (isset($supplierss) && $supplierss->address != '' ? $supplierss->address : '-') ?></span>
                            </div>
                            <div class="wap-content firt">
                                <span class="text-muted lead-field-heading no-mtop bold"><?php echo _l('ch_city'); ?>: </span>
                                <span class="bold font-medium-xs"><?php echo (isset($supplierss) && !empty($supplierss->city) ? ch_getProvince($supplierss->city)->name : '-') ?></span>
                            </div>
                            <div class="wap-content second">
                                <span class="text-muted lead-field-heading no-mtop bold"><?php echo _l('ch_district'); ?>: </span>
                                <span class="bold font-medium-xs"><?php echo (isset($supplierss) && !empty($supplierss->district) ? ch_getDistrict($supplierss->district)->name : '-') ?></span>
                            </div>
                            <div class="wap-content firt">
                                <span class="text-muted lead-field-heading no-mtop bold"><?php echo _l('ch_ward'); ?>: </span>
                                <span class="bold font-medium-xs"><?php echo (isset($supplierss) && !empty($supplierss->ward) ? ch_getWard($supplierss->ward)->name : '-') ?></span>
                            </div>
                            <div class="wap-content second">
                                <span class="text-muted lead-field-heading no-mtop bold"><?php echo _l('ch_country'); ?>: </span>
                                <span class="bold font-medium-xs"><?php echo (isset($supplierss) && !empty($supplierss->country) ? get_country($supplierss->country)->short_name : '-') ?></span>
                            </div>
                            <div class="wap-content firt">
                                <span class="text-muted lead-field-heading no-mtop bold"><?php echo _l('note'); ?>: </span>
                                <span class="bold font-medium-xs"><?php echo (isset($supplierss) && $supplierss->note != '' ? $supplierss->note : '-') ?></span>
                            </div>
                          </div>
                          
                          <?php if(!empty($info_suppliers)){
                            $tab_temp = 2;
                            foreach ($info_suppliers as $key => $value) {
                              $info_supplier_value = get_table_where('tblsuppliers_info_detail',array('id_suppliers_info'=>$value['id']));
                              if(!empty($info_supplier_value)){
                          ?>
                          <div class="fieldset" role-fieldset="<?=$tab_temp?>">
                            <?php
                            foreach($info_supplier_value as $k => $field) { ?>
                            <div class="wap-content <?=$k % 2 == 0 ? 'firt' : 'second'?>">
                                <span class="text-muted lead-field-heading no-mtop bold"><?php echo $field['name']; ?>: </span>
                                <?php $value = get_value_info_suppliers((isset($supplierss) && isset($supplierss->id) ? $supplierss->id : ''), $field['id'], $field['type_form']); ?> 
                                <span class="bold font-medium-xs"><?php echo (isset($supplierss) && $value != '' ? $value : '-') ?></span>
                            </div>
                            <?php }?>
                          </div>
                          <?php $tab_temp++; } } } ?>

                          <?php $custom_fields = get_custom_fields('suppliers',array()); ?>
                          <?php if(count($custom_fields) > 0) { ?>
                          <div class="fieldset" role-fieldset="<?=$tab_temp?>">
                            <?php
                              $custom_fields = get_custom_fields('suppliers',array());
                              foreach($custom_fields as $k => $field) { ?>
                              <div class="wap-content <?=$k % 2 == 0 ? 'firt' : 'second'?>">
                                <span class="text-muted lead-field-heading no-mtop bold"><?php echo $field['name']; ?>: </span>
                                <?php $value = get_custom_field_value((isset($supplierss) && isset($supplierss->id) ? $supplierss->id : ''), $field['id'], 'suppliers'); ?> 
                                <span class="bold font-medium-xs"><?php echo (isset($supplierss) && $value != '' ? $value : '-') ?></span>
                              </div>
                            <?php } ?>
                          </div>
                          <?php $tab_temp++; } ?>
                          <div class="evaluate_view" data-val="<?=$tab_temp?>"></div>
                        </div>
                    </div>
