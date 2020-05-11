<style type="text/css">
  .border_ch{
        /*border-bottom: 1px solid #49a1d6;*/
  }
</style>
<div class="modal fade" id="items_view" tabindex="-1" role="dialog">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?php echo $title; ?></h4>
         </div>
    <div class="modal-body">
          <div class="modal-body">
            <ul class="nav nav-tabs" role="tablist">
               <li role="presentation" class="active">
                <a href="#ch_view_items" aria-controls="ch_view_items" role="tab" data-toggle="tab">
                <?php echo _l( 'ch_view_items'); ?>
                </a>
              </li>     
               <li role="presentation">
                <a href="#item_price_history" aria-controls="item_price_history" role="tab" data-toggle="tab">
                <?php echo _l( 'item_price_history'); ?>
                </a>
              </li>  
              <?php if(isset($items) && $items->type==2){ ?>
              <li role="presentation">
                <a href="#combo" aria-controls="combo" role="tab" data-toggle="tab">
                <?php echo _l( 'combo_items'); ?>
                </a>
              </li> 
              <?php }?>
            </ul>
              <div class="tab-content">
                  <div role="tabpanel" class="tab-pane" id="combo">
                      <div class="row">
                          <div class="col-md-12">
                              <?php render_datatable(array(
                                  _l('STT'),
                                  _l('item_name'),
                                  _l('item_quantity'),
                                  _l('ch_option'),
                                ),
                                  'combo-items'); 
                              ?>
                          </div>
                      </div>
                  </div>
                  <div role="tabpanel" class="tab-pane" id="item_price_history">
                      <div class="row">
                          <div class="col-md-12">
                              <h3><?php echo _l('item_price') ?></h3>
                              <?php render_datatable(array(
                                  _l('item_price_date'),
                                  _l('item_old_price'),
                                  _l('item_new_price'),
                                  _l('als_staff'),
                                  ),
                                  'invoice-item-price-history'); ?>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-12">
                              <h3><?php echo _l('item_price_single') ?></h3>
                              <?php render_datatable(array(
                                  _l('item_price_date'),
                                  _l('item_old_price'),
                                  _l('item_new_price'),
                                  _l('als_staff'),
                                  ),
                                  'invoice-item-price-single-history'); ?>
                          </div>
                      </div>
                  </div>
                  <div role="tabpanel" class="tab-pane active" id="ch_view_items">
                   <!--  <a href="#" onclick="edit()">
                     <?php echo _l('edit'); ?>
                     <i class="fa fa-pencil-square-o"></i>
                    </a> -->
                    <div class="clearfix"></div>
                    <div class="lead-info-heading">
                       <h4 class="no-margin font-medium-xs bold">
                          <?php echo _l('ch_view_basic_items'); ?>
                       </h4>
                    </div>
                    <div class="col-md-4">
                      <div class="preview_image" style="width: auto;">
                        <div class="center display-block contract-attachment-wrapper img-'.$aRow['id'].'">
                            <div style="width:100px">
                                <a href="<?=(file_exists($items->avatar) ? base_url($items->avatar) : base_url('assets/images/preview-not-available.jpg'))?>" data-lightbox="customer-profile" class="display-block mbot5">
                                    <div class="table-image">
                                        <img src="<?=(file_exists($items->avatar) ? base_url($items->avatar) : base_url('assets/images/preview-not-available.jpg'))?>" style="border-radius: 50%;width: 30%;height: 100%;" />
                                    </div>
                                </a>
                            </div>
                        </div>
                      </div>
                      <div class="center">
                        <?php echo '<img src="'.base_url('Barcode/set_barcode/').$items->code.'" />' ?>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group"> 
                        <label class="form-label control-label ng-binding"><?php echo _l('item_code'); ?>:</label> 
                        <span>
                          <strong class="ng-binding"><?php echo (isset($items) && $items->code != '' ? $items->code : '-') ?></strong>
                        </span> 
                      </div>
                      <div class="form-group"> 
                        <label class="form-label control-label ng-binding"><?php echo _l('item_name'); ?>:</label> 
                        <span>
                          <strong class="ng-binding"><?php echo (isset($items) && $items->name != '' ? $items->name : '-') ?></strong>
                        </span> 
                      </div>
                      <div class="form-group"> 
                        <label class="form-label control-label ng-binding"><?php echo _l('ch_categories'); ?>:</label> 
                        <span>
                          <strong class="ng-binding"><?php echo (isset($items) && $items->category != '' ? $items->category : '-') ?></strong>
                        </span> 
                      </div>
                      <div class="form-group"> 
                        <label class="form-label control-label ng-binding"><?php echo _l('item_unit'); ?>:</label> 
                        <span>
                          <strong class="ng-binding"><?php echo (isset($items) && $items->unit_name != '' ? $items->unit_name : '-') ?></strong>
                        </span> 
                      </div>
                      <div class="form-group"> 
                        <label class="form-label control-label ng-binding"><?php echo _l('item_brand'); ?>:</label> 
                        <span>
                          <strong class="ng-binding"><?php echo (isset($items) && $items->brand_id != '' ? (!empty(ch_get_brands($items->brand_id))?ch_get_brands($items->brand_id)->name : '-') : '-') ?></strong>
                        </span> 
                      </div>
                      <div class="form-group"> 
                        <label class="form-label control-label ng-binding"><?php echo _l('item_group_id'); ?>:</label> 
                        <span>
                          <strong class="ng-binding"><?php echo (isset($items) && !empty($items->group_id) ? ch_get_item_groups($items->group_id)->name : '-') ?></strong>
                        </span> 
                      </div>
                      <div class="form-group"> 
                        <label class="form-label control-label ng-binding"><?php echo _l('item_country_id'); ?>:</label> 
                        <span>
                          <strong class="ng-binding"><?php echo (isset($items) && $items->short_name != '' ? $items->short_name : '-') ?></strong>
                        </span> 
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group"> 
                        <label class="form-label control-label ng-binding"><?php echo _l('item_price'); ?>:</label> 
                        <span>
                          <strong class="ng-binding"><?php echo (isset($items) && is_numeric($items->price) ? number_format($items->price) : '-') ?></strong>
                        </span> 
                      </div>
                      <div class="form-group"> 
                        <label class="form-label control-label ng-binding"><?php echo _l('item_price_single'); ?>:</label> 
                        <span>
                          <strong class="ng-binding"><?php echo (isset($items) && is_numeric($items->price_single) ? number_format($items->price_single) : '-') ?></strong>
                        </span> 
                      </div>
                      <div class="form-group"> 
                        <label class="form-label control-label ng-binding"><?php echo _l('minimum_quantity'); ?>:</label> 
                        <span>
                          <strong class="ng-binding"><?php echo (isset($items) && is_numeric($items->minimum_quantity) ? number_format($items->minimum_quantity) : '-') ?></strong>
                        </span> 
                      </div>
                      <div class="form-group"> 
                        <label class="form-label control-label ng-binding"><?php echo _l('maximum_quantity'); ?>:</label> 
                        <span>
                          <strong class="ng-binding"><?php echo (isset($items) && is_numeric($items->maximum_quantity) ? number_format($items->maximum_quantity) : '-') ?></strong>
                        </span> 
                      </div>
                      <div class="form-group"> 
                        <label class="form-label control-label ng-binding"><?php echo _l('item_date'); ?>:</label> 
                        <span>
                          <strong class="ng-binding"><?php echo (isset($items) && $items->date != '' ? $items->date : '-') ?> <?php echo _l('month') ?></strong>
                        </span> 
                      </div>
                      <div class="form-group"> 
                        <label class="form-label control-label ng-binding"><?php echo _l('item_warranty'); ?>:</label> 
                        <span>
                          <strong class="ng-binding"><?php echo (isset($items) && $items->warranty != '' ? $items->warranty : '-') ?> <?php echo _l('month') ?></strong>
                        </span> 
                      </div>
                    </div>
                  </div>
              </div>
              <div class="clearfix"></div>
           </div>
        </div>
         <div class="clearfix"></div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
         </div>
      </div>
   </div>
</div>
<script type="text/javascript">
    $(function(){
    <?php if(isset($items)) { ?>
    var notSortableAndSearchableItemColumns = [];
    initDataTable('.table-invoice-item-price-history','<?=admin_url('invoice_items/invoice_item_price_history/' . $items->id)?>', notSortableAndSearchableItemColumns, notSortableAndSearchableItemColumns,'undefined',[1,'asc']);
    initDataTable('.table-invoice-item-price-single-history','<?=admin_url('invoice_items/invoice_item_price_single_history/' . $items->id)?>', notSortableAndSearchableItemColumns, notSortableAndSearchableItemColumns,'undefined',[1,'asc']);
    var tAPI = initDataTable('.table-combo-items','<?=admin_url('invoice_items/combo_items/' . $items->id.'/1')?>', notSortableAndSearchableItemColumns, notSortableAndSearchableItemColumns,'undefined',[1,'asc']);
    tAPI.columns(3).visible(false, false);
    <?php } ?>
  });
</script>