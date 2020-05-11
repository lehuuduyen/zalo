<?php init_head(); ?>
<style>

    #table_ratio_staff_detail thead tr th:nth-child(1)
    {
        width: 10%;
    }
    #table_ratio_staff_detail thead tr th:nth-child(2)
    {
        width: 60%;
    }
    #table_ratio_staff_detail thead tr th:nth-child(3)
    {
        width: 30%;
    }

    #table_infomation_ratio tr th:nth-child(1),
    #table_infomation_ratio tr th:nth-child(3)
    {
        width: 20%;
    }
    #table_infomation_ratio tr td:nth-child(2),
    #table_infomation_ratio tr td:nth-child(4)
    {
        width: 30%;
    }
</style>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<?php echo form_open($this->uri->uri_string(), array('id' => 'ratio-form', 'class' => '_transaction_form orders-form')); ?>
            <input type="hidden" id="id_ratio" value="<?= !empty($ratio_staff) ? $ratio_staff->id : '' ?>">
			<div>
				<div class="panel_s">
				 	<div class="additional"></div>
				 	<div class="panel-body">
						<h4 class="bold no-margin font-medium">
					     	<?php echo (!empty($title) ? $title : ''); ?>
					   	</h4>
						<hr />
				 		<div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading"><?=_l('cong_information_ratio')?></div>
                                    <div class="panel-body">
                                        <table class="tnh-tb table-bordered table-hover dont-responsive-table m-group0" id="table_infomation_ratio">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <label for="name" class="control-label">
                                                            <?php echo _l('cong_name_ratio'); ?>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <?php
                                                            $value = !empty($ratio_staff) ? $ratio_staff->name : '';
                                                            echo render_input('name', '', $value);
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <label for="name" class="control-label">
                                                            <?php echo _l('cong_total_ratio_staff'); ?> (%)
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <div class="form-control total_ratio">100</div>
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label for="year" class="control-label">
			                                                <?php echo _l('cong_year'); ?>
                                                        </label>
                                                    </td>
                                                    <td class="w100">
		                                                <?php
		                                                $value = !empty($ratio_staff) ? $ratio_staff->year : '';
		                                                echo render_select('year', $year , ['id', 'name'], '', $value)
		                                                ?>
                                                    </td>

                                                    <td>
                                                        <label for="month" class="control-label">
                                                            <?php echo _l('cong_month'); ?>
                                                        </label>
                                                    </td>
                                                    <td class="w100">
                                                        <?php
                                                            $value = !empty($ratio_staff) ? $ratio_staff->month : '';
                                                            echo render_select('month', $month , ['id', 'name'], '', $value)
                                                        ?>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading"><?=_l('cong_infomation_ratio_staff')?></div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered dont-responsive-table mtop0 mbot0" id="table_ratio_staff_detail">
                                                <thead>
                                                    <tr>
                                                        <th><b><?=_l('cong_order')?></b></th> <!--STT-->
                                                        <th><b><?=_l('cong_staff')?></b></th> <!--Nhân viên-->
                                                        <th class="text-center"><b><?=_l('cong_ratio_staff')?> %</b></th> <!--Tỉ lệ phần trăm-->
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        foreach($staff as $key => $value) {?>
                                                            <tr>
                                                                <td><?=($key+1)?></td>
                                                                <td><?=$value['lastname']. ' '. $value['firstname']?><br/> (Email: <?=$value['email']?>)</td>
                                                                <td>
                                                                    <input type="number" class="form-control text-center ratio" id="staff[<?=$value['staffid']?>]" name="staff[<?=$value['staffid']?>]" value="<?= isset($value['ratio']) ? $value['ratio'] : ''?>">
                                                                </td>
                                                            </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
						</div>
						<div class="clearfix"></div>
				 	</div>
			 	</div>
                <div class="btn-bottom-toolbar btn-toolbar-container-out text-right">
                    <a href="<?=admin_url('ratio_staff')?>" class="btn btn-default go-back">
                        <?php echo _l( 'go_back'); ?>
                    </a>

                    <button class="btn btn-info only-save">
                        <?php echo _l( 'submit'); ?>
                    </button>
                </div>
			</div>

			<?php
            if(!empty($action)) {
                echo form_close();
            }
            ?>
		</div>
	</div>
</div>


<?php init_tail(); ?>

<script>

    var ValdateFrom = {
        name: 'required',
        month: 'required',
        year: 'required'
    };

    $(function() {
        appValidateForm($('#ratio-form'), ValdateFrom);
    })
    var total_ratio = 100;
    $('body').on('change', 'input.ratio', function(e){
        var total = total_ratio;
        var ratio = $('input.ratio');
        $.each(ratio, function(i, v){
            var Vquantity = intVal($(v).val());
            var id = $(v).attr('id');
            var afterQuantity = total - Vquantity;
            if(afterQuantity > 0)
            {
                if(Vquantity >=0 )
                {
                    ValdateFrom[id] = {
                        min: [-1, 0],
                        range : [0, Vquantity]
                    };
                }
                else
                {
                    ValdateFrom[id] = {
                        min: [-1, 0],
                        range : [0, total]
                    };
                }
            }
            else
            {
                ValdateFrom[id] = {
                    min: [-1, 0],
                    range : [0, total]
                };
            }
            total -= Vquantity;
            appValidateForm($('#ratio-form'), ValdateFrom);
        })
    })

    $('body').on('change', '#year', function(e){
        var id = $('#id_ratio').val();
        var year = $('#year').val();
        var data = {
            id :id,
            year : year
        };
        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        $.post(admin_url+'ratio_staff/getMonth', data, function (result) {
            result = JSON.parse(result);
            var String_option = '<option></option>';
            $.each(result, function(i, v){
                String_option += '<option value="'+i+'">'+v+'</option>';
            })
            $('#month').html(String_option).selectpicker('refresh');

        })
    })


</script>

