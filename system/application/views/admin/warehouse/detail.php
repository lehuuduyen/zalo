<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <?php if(has_permission('warehouse','','create')){ ?>
  <div class="panel_s mbot10 H_scroll" id="H_scroll">
    <div class="panel-body _buttons">
        <span class="bold uppercase fsize18 H_title"><?=$title?></span>
    </div>
  </div>
  <?php } ?>
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
          	<div class="col-md-12">
                <p class="bold"><?php echo _l('filter_by'); ?></p>
            </div>
            <div class="col-md-3">
				<?php
		         echo render_select('type_items', $type_items, array('type', 'name'),'ch_type');
				?>
			</div>
       <div class="col-md-3 select_custom_item_select">
        <div class="form-group select-placeholder ">
             <label for="custom_item_select" class="control-label"><span class="text-danger">* </span><?php echo _l('item_name'); ?></label>
             <select id="custom_item_select" name="custom_item_select" data-live-search="true" data-width="100%" class="ajax-search" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
             </select>
          </div>
      </div>
			<!-- <div class="col-md-3">
				<div class="form-group mbot25">
					<label for="custom_item_select"><?=_l('item_name')?></label>
					<select class="selectpicker no-margin" data-width="100%" id="custom_item_select" name="custom_item_select" data-none-selected-text="<?php echo _l('add_item'); ?>" data-live-search="true">
						<option value=""></option>
					</select>
				</div>
			</div> -->
      <div class="col-md-3">
        <div class="form-group mbot25">
          <label for="localtion"><?=_l('warehouse_localtion')?></label>
          <select class="selectpicker no-margin" data-width="100%" id="localtion" name="localtion" data-none-selected-text="<?php echo _l('warehouse_localtion'); ?>" data-live-search="true">
            <?=$localtion?>
          </select>
        </div>
      </div>
          	<div class="clearfix"></div>
            <?php render_datatable(array(
              _l('#'),
              _l('item_code'),
              _l('ch_color'),
              _l('warehouse_localtion'),
              _l('quantity'),
            ),'warehouse_items'); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
<script>
  $(function(){
    var type = $('#type_items').val();
    if(empty(type))
    {
      $('.select_custom_item_select').addClass('hide');
    }
  init_ajax_searchs('items','#custom_item_select')
  function init_ajax_searchs(e, t, a, i) {
    var n = $("body").find(t);
    if (n.length) {
        var s = {
            ajax: {
                url: void 0 === i ? admin_url + "misc/get_relation_data" : i,
                data: function() {
                    var type = $('#type_items').val();

                    var t = {[csrfData.token_name] : csrfData.hash};
                    return t.type = e, t.rel_id = "", t.q = "{{{q}}}",t.type_items = type, void 0 !== a && jQuery.extend(t, a), t
                }
            },
            locale: {
                emptyTitle: app.lang.search_ajax_empty,
                statusInitialized: app.lang.search_ajax_initialized,
                statusSearching: app.lang.search_ajax_searching,
                statusNoResults: app.lang.not_results_found,
                searchPlaceholder: app.lang.search_ajax_placeholder,
                currentlySelected: app.lang.currently_selected
            },
            requestDelay: 500,
            cache: !1,
            preprocessData: function(e) {
                for (var t = [], a = e.length, i = 0; i < a; i++) {
                    var n = {
                        value: e[i].id,
                        text: e[i].name
                    };
                    e[i].subtext && (n.data = {
                        subtext: e[i].subtext
                    }), t.push(n)
                }
                return t
            },
            preserveSelectedPosition: "after",
            preserveSelected: !0
        };
        n.data("empty-title") && (s.locale.emptyTitle = n.data("empty-title")), n.selectpicker().ajaxSelectPicker(s)
    }
}
  	var CustomersServerParams = {
     'type_items' : '[name="type_items"]',
     'custom_item_select' : '[name="custom_item_select"]',
     'localtion' : '[name="localtion"]',
    };
    var tAPI = initDataTable('.table-warehouse_items', admin_url+'warehouse/table_warehouse_items/'+<?=$id?>, [0], [0],CustomersServerParams,[0,'asc']);
    $.each(CustomersServerParams, function(filterIndex, filterItem){
      $('' + filterItem).on('change', function(){
        tAPI.ajax.reload();
      });
    });
  });
  $('#type_items').on('change', function(e){
    $('#custom_item_select').selectpicker('val','');
    $('#custom_item_select').selectpicker('refresh');
    var type = $('#type_items').val();
    if(empty(type))
    {
      $('.select_custom_item_select').addClass('hide');
    }else
    {
      $('.select_custom_item_select').removeClass('hide');
    }
  });

    // function loadItems(type){
    //     var custom_item_select.find('option:gt(0)').remove();
    //     custom_item_select.selectpicker('refresh');
    //     if(custom_item_select.length) {
    //         $.ajax({
    //             url : admin_url + 'invoice_items/items/' + type,
    //             dataType : 'json',
    //         })
    //         .done(function(data){
    //             $.each(data, function(key,value){
    //       custom_item_select.append('<option value="' + value.id + '">' + value.name+'</option>');
    //             });
    //             custom_item_select.selectpicker('refresh');
    //         });
    //     }
    // }
</script>
</body>
</html>
