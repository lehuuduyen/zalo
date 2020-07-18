<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4 border-right">
                                <h4 class="bold no-margin font-medium"><?php echo _l('Báo cáo công nợ'); ?></h4>
                                <!-- debts_genernal_receivable_suppliers -->
                                <hr class="hr-10"/>
                                <!--                        <p><a href="#" class="font-medium" onclick="init_report(this,'debts_all'); return false;"><i class="fa fa-caret-down" aria-hidden="true"></i> -->
                                <?php //echo _l('Tổng hợp công nợ phải đòi'); ?><!--</a></p>-->
                                <!--                        <hr class="hr-10" />-->
                                <!--                        <p><a href="#" class="font-medium" onclick="init_report(this,'genernal-receivables-suppliers-debts-report'); return false;"><i class="fa fa-caret-down" aria-hidden="true"></i> -->
                                <?php //echo _l('Tổng hợp công nợ nhà cung cấp'); ?><!--</a></p>-->
                                <!--                        <hr class="hr-10" />-->
                                <!--                        <p><a href="#" class="font-medium" onclick="init_report(this,'debts_porters'); return false;"><i class="fa fa-caret-down" aria-hidden="true"></i> -->
                                <?php //echo _l('Tổng hợp công nợ bốc vác'); ?><!--</a></p>-->
                                <!--                        <hr class="hr-10" />-->
                                <!--                        <p><a href="#" class="font-medium" onclick="init_report(this,'debts_rack'); return false;"><i class="fa fa-caret-down" aria-hidden="true"></i> -->
                                <?php //echo _l('Tổng hợp công nợ Lái xe'); ?><!--</a></p>-->
                                <!--                        <hr class="hr-10" />-->
                                <p>
                                    <a href="#" class="font-medium" onclick="init_report(this,'debts_personal'); return false;">
                                        <i class="fa fa-caret-down" aria-hidden="true"></i> <?php echo _l('Tổng hợp công nợ cá nhân(Sổ quỹ[Vay-Mượn])'); ?>
                                    </a>
                                </p>

                                <p><a href="#" class="font-medium"
                                      onclick="init_report(this,'debts_customer'); return false;"><i
                                                class="fa fa-caret-down"
                                                aria-hidden="true"></i> <?php echo _l('Tổng hợp công nợ Khách Hàng'); ?>
                                    </a></p>

<!--                                <p>-->
<!--                                    <a href="--><?//= admin_url('report_cod_sum') ?><!--" target="_blank" class="font-medium">-->
<!--                                        <i class="fa fa-caret-down" aria-hidden="true"></i>-->
<!--                                        --><?php //echo _l('Công Nợ Đã Đối Soát'); ?>
<!--                                    </a>-->
<!--                                </p>-->
                                <p>
                                    <a href="#" class="font-medium" onclick="init_report(this,'report_cod_sum'); return false;">
                                        <i class="fa fa-caret-down" aria-hidden="true"></i>
                                        <?php echo _l('Công Nợ Đã Đối Soát SUPERSHIP'); ?>
                                    </a>
                                </p>

                                <p>
                                    <a href="#" class="font-medium" onclick="init_report(this,'debts_control'); return false;">
                                        <i class="fa fa-caret-down" aria-hidden="true"></i>
                                        <?php echo _l('Công Nợ Đợi Đối Soát'); ?>
                                    </a>
                                </p>
                                <p>
                                    <a href="#" class="font-medium" onclick="init_report(this,'debts_staff'); return false;">
                                        <i class="fa fa-caret-down" aria-hidden="true"></i>
                                        <?php echo _l('Công Nợ Nhân Viên'); ?>
                                    </a>
                                </p>

                            </div>


                            <div class="col-md-4 border-right hide">
                                <h4 class="bold no-margin font-medium"><?php echo _l('debts_reports'); ?></h4>
                                <hr class="hr-10"/>
                                <p><a href="#" class="font-medium"
                                      onclick="init_report(this,'debts_borrowing'); return false;"><i
                                                class="fa fa-caret-down"
                                                aria-hidden="true"></i> <?php echo _l('Báo cáo vay-mượn NVL'); ?></a>
                                </p>
                                <hr class="hr-10"/>
                                <p><a href="#" class="font-medium"
                                      onclick="init_report(this,'debts_clients'); return false;"><i
                                                class="fa fa-caret-down"
                                                aria-hidden="true"></i> <?php echo _l('Báo cáo công nợ Khách hàng'); ?>
                                    </a></p>
                            </div>
                            <div class="col-md-4">
                                <?php if (isset($currencies)) { ?>
                                    <div id="currency" class="form-group hide">
                                        <label for="currency"><i class="fa fa-question-circle" data-toggle="tooltip"
                                                                 title="<?php echo _l('report_sales_base_currency_select_explanation'); ?>"></i> <?php echo _l('currency'); ?>
                                        </label><br/>
                                        <select class="selectpicker" name="currency" data-width="100%"
                                                data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                            <?php foreach ($currencies as $currency) {
                                                $selected = '';
                                                if ($currency['isdefault'] == 1) {
                                                    $selected = 'selected';
                                                }
                                                ?>
                                                <option value="<?php echo $currency['id']; ?>" <?php echo $selected; ?>><?php echo $currency['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                <?php } ?>
                                <div id="income-years" class="hide mbot15">
                                    <label for="payments_years"><?php echo _l('year'); ?></label><br/>
                                    <select class="selectpicker" name="payments_years" data-width="100%"
                                            onchange="total_income_bar_report();"
                                            data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                        <?php foreach ($payments_years as $year) { ?>
                                            <option value="<?php echo $year['year']; ?>"<?php if ($year['year'] == date('Y')) {
                                                echo 'selected';
                                            } ?>>
                                                <?php echo $year['year']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="form-group hide" id="report-time">
                                    <label for="months-report"><?php echo _l('period_datepicker'); ?></label><br/>
                                    <select class="selectpicker" name="months-report" data-width="100%"
                                            data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                        <option value=""><?php echo _l('report_sales_months_all_time'); ?></option>
                                        <option value="3"><?php echo _l('report_sales_months_three_months'); ?></option>
                                        <option value="6"><?php echo _l('report_sales_months_six_months'); ?></option>
                                        <option value="12"><?php echo _l('report_sales_months_twelve_months'); ?></option>
                                        <option value="custom"><?php echo _l('period_datepicker'); ?></option>
                                    </select>
                                </div>

                                <div class="form-group hide" id="report-year">
                                    <label for="years_report"><?php echo _l('year_report'); ?></label><br/>
                                    <select class="selectpicker" name="years_report" id="years_report" data-width="100%"
                                            data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                        <option value=""><?php echo _l(''); ?></option>
                                        <?php foreach ($order_years as $key => $year) {
                                            $selected = '';
                                            if ($year->year == date('Y'))
                                                $selected = 'selected';
                                            ?>
                                            <option value="<?= $year->year ?>" <?= $selected ?> ><?= $year->year ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div id="date-range" class="hide animated mbot15">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="report-from"
                                                   class="control-label"><?php echo _l('report_sales_from_date'); ?></label>
                                            <div class="input-group date">
                                                <input type="text" class="form-control datepicker" id="report-from"
                                                       name="report-from">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar calendar-icon"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="report-to"
                                                   class="control-label"><?php echo _l('report_sales_to_date'); ?></label>
                                            <div class="input-group date">
                                                <input type="text" class="form-control datepicker" disabled="disabled"
                                                       id="report-to" name="report-to">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar calendar-icon"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-4 border-right hide">
                            <h4 class="bold no-margin font-medium"><?php echo _l('debts_reports'); ?></h4>
                            <hr class="hr-10"/>
                            <p><a href="#" class="font-medium"
                                  onclick="init_report(this,'debts_borrowing'); return false;"><i
                                            class="fa fa-caret-down"
                                            aria-hidden="true"></i> <?php echo _l('Báo cáo vay-mượn NVL'); ?></a></p>
                            <hr class="hr-10"/>
                            <p><a href="#" class="font-medium"
                                  onclick="init_report(this,'debts_clients'); return false;"><i class="fa fa-caret-down"
                                                                                                aria-hidden="true"></i> <?php echo _l('Báo cáo công nợ Khách hàng'); ?>
                                </a></p>
                        </div>
                        <div class="col-md-4">
                            <?php if (isset($currencies)) { ?>
                                <div id="currency" class="form-group hide">
                                    <label for="currency"><i class="fa fa-question-circle" data-toggle="tooltip"
                                                             title="<?php echo _l('report_sales_base_currency_select_explanation'); ?>"></i> <?php echo _l('currency'); ?>
                                    </label><br/>
                                    <select class="selectpicker" name="currency" data-width="100%"
                                            data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                        <?php foreach ($currencies as $currency) {
                                            $selected = '';
                                            if ($currency['isdefault'] == 1) {
                                                $selected = 'selected';
                                            }
                                            ?>
                                            <option value="<?php echo $currency['id']; ?>" <?php echo $selected; ?>><?php echo $currency['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            <?php } ?>
                            <div id="income-years" class="hide mbot15">
                                <label for="payments_years"><?php echo _l('year'); ?></label><br/>
                                <select class="selectpicker" name="payments_years" data-width="100%"
                                        onchange="total_income_bar_report();"
                                        data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                    <?php foreach ($payments_years as $year) { ?>
                                        <option value="<?php echo $year['year']; ?>"<?php if ($year['year'] == date('Y')) {
                                            echo 'selected';
                                        } ?>>
                                            <?php echo $year['year']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="form-group hide" id="report-time-2">
                                <label for="months-report"><?php echo _l('period_datepicker'); ?></label><br/>
                                <select class="selectpicker" name="months-report" data-width="100%"
                                        data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                    <option value=""><?php echo _l('report_sales_months_all_time'); ?></option>
                                    <option value="3"><?php echo _l('report_sales_months_three_months'); ?></option>
                                    <option value="6"><?php echo _l('report_sales_months_six_months'); ?></option>
                                    <option value="12"><?php echo _l('report_sales_months_twelve_months'); ?></option>
                                    <option value="custom"><?php echo _l('period_datepicker'); ?></option>
                                </select>
                            </div>

                            <div class="form-group hide" id="report-year">
                                <label for="years_report"><?php echo _l('year_report'); ?></label><br/>
                                <select class="selectpicker" name="years_report" id="years_report" data-width="100%"
                                        data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                    <option value=""><?php echo _l(''); ?></option>
                                    <?php foreach ($order_years as $key => $year) {
                                        $selected = '';
                                        if ($year->year == date('Y'))
                                            $selected = 'selected';
                                        ?>
                                        <option value="<?= $year->year ?>" <?= $selected ?> ><?= $year->year ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div id="date-range" class="hide animated mbot15">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="report-from"
                                               class="control-label"><?php echo _l('report_sales_from_date'); ?></label>
                                        <div class="input-group date">
                                            <input type="text" class="form-control datepicker" id="report-from"
                                                   name="report-from">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar calendar-icon"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="report-to"
                                               class="control-label"><?php echo _l('report_sales_to_date'); ?></label>
                                        <div class="input-group date">
                                            <input type="text" class="form-control datepicker" disabled="disabled"
                                                   id="report-to" name="report-to">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar calendar-icon"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Report Khach Hang -->

                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="panel_s hide" id="report">
                <div class="panel-body">
                    <h4 class="no-mtop" id="report_tiltle"><?php echo _l('reports_sales_generated_report'); ?></h4>
                    <hr/>
                    <?php $this->load->view('admin/reports/includes/debts/debts_genernal_receivable_suppliers'); ?>
                    <?php $this->load->view('admin/reports/includes/debts/debts_porters'); ?>
                    <?php $this->load->view('admin/reports/includes/debts/debts_customer'); ?>
                    <?php $this->load->view('admin/reports/includes/debts/debts_rack'); ?>
                    <?php $this->load->view('admin/reports/includes/debts/debts_personal'); ?>
                    <?php $this->load->view('admin/reports/includes/debts/debts_borrowing'); ?>
                    <?php $this->load->view('admin/reports/includes/debts/debts_clients'); ?>
                    <?php $this->load->view('admin/reports/includes/debts/debts_all'); ?>
                    <?php $this->load->view('admin/reports/includes/debts/debts_control'); ?>
                    <?php $this->load->view('admin/reports/includes/debts/debts_cod_sum'); ?>
                    <?php $this->load->view('admin/reports/includes/debts/debts_staff'); ?>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>


</div>
<?php init_tail(); ?>
<?php $this->load->view('admin/reports/includes/debts/debts_js'); ?>


<style type="text/css">
    .textR {
        color: red;
        font-weight: bold;
    }

    .textB {
        color: blue;
        font-weight: bold;
    }

    .textG {
        color: green;
        font-weight: bold;
    }

    .title {
        font-weight: bold;
        font-style: italic;
    }

    .table-detail-receivables-debts-report tr td:nth-child(2) {
        max-width: 200px;
        white-space: inherit;
        min-width: 200px;
    }
</style>
