<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="overlay-dark">
        <div id="loader-repo3" class="lds-ellipsis">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    <div class="content return-style">
        <div class="row">
            <div class="col-md-12">
                <select id="status" class="hidden" name="status">
                    <option></option>
                    <?php
                    foreach ($list_status as $status => $color) { ?>
                        <option data-color="<?= $color ?>" value="<?= $status ?>"><?= $status ?></option>
                    <?php }

                    ?>
                </select>
                <div class="panel_s">
                    <div class="panel-body">




<!---->
<?php $this->load->view('admin/includes/alerts'); ?>
<!---->
<?php hooks()->do_action( 'before_start_render_dashboard_content' ); ?>
<!---->
<div class="clearfix"></div>
<!---->
<div class="col-md-12 mtop30" data-container="top-12">
    <?php render_dashboard_widgets('top-12'); ?>
</div>

<?php hooks()->do_action('after_dashboard_top_container'); ?>

<div class="col-md-6" data-container="middle-left-6">
    <?php render_dashboard_widgets('middle-left-6'); ?>
</div>
<div class="col-md-6" data-container="middle-right-6">
    <?php render_dashboard_widgets('middle-right-6'); ?>
</div>

<?php hooks()->do_action('after_dashboard_half_container'); ?>

<div class="col-md-8" data-container="left-8">
    <?php render_dashboard_widgets('left-8'); ?>
</div>
<div class="col-md-4" data-container="right-4">
    <?php render_dashboard_widgets('right-4'); ?>
</div>

<div class="clearfix"></div>
                    </div>
                    <!--                    border red-->


                </div>
            </div>
        </div>
    </div>
</div>

<?php hooks()->do_action('after_dashboard'); ?>

<div id="wrapper">
    <div class="screen-options-area"></div>
    <div class="screen-options-btn">
        <?php echo _l('dashboard_options'); ?>
    </div>
    <div class="content">
        <div class="row"></div>
    </div>
</div>
<script>
    app.calendarIDs = '<?php echo json_encode($google_ids_calendars); ?>';
</script>
<?php init_tail(); ?>
<?php $this->load->view('admin/utilities/calendar_template'); ?>
<?php $this->load->view('admin/dashboard/dashboard_js'); ?>
</body>
</html>
