<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="panel_s mbot10 H_scroll" id="H_scroll">
        <div class="panel-body _buttons">
            <span class="bold uppercase fsize18 H_title"><?=$title?></span>
            <a class="btn btn-info mright5 test pull-right H_action_button" data-toggle="collapse" data-target="#search">
                <?php echo _l('search'); ?>
            </a>
            <div class="line-sp"></div>
            <a href="#" class="btn btn-info pull-right H_action_button" onclick="AddBotFanpage('', this)">
                <i class="lnr lnr-plus-circle" aria-hidden="true"></i>
                <?php echo _l('cong_button_add_add_fanpage'); ?>
            </a>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="clearfix"></div>
                            <?php render_datatable(array(
                                _l('id'),
                                _l('cong_code'),
                                _l('cong_name'),
                                _l('cong_status'),
                                _l('cong_create_by'),
                                _l('cong_date_create'),
                                _l('cong_fanpage'),
                                _l('options')
                            ),'bot_fanpage dont-responsive-table'); ?>

                    </div>
                    <div class="spanTag">
                        <select id="tag" multiple>
                            <option value="ALa" color="red">Alabama</option>
                            <option value="ALb" color="blue">Alabama</option>
                            <option value="ALc" color="black">Alabama</option>
                            <option value="WY" color="red">Wyoming</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="view_modal"></div>

<?php init_tail(); ?>
<style>
    .spanTag .select2-choices{
        background-image: none;
    }
    .spanTag .select2-choices{
        border: none;
    }
    .spanTag .select2-search-choice-close{
        top: 4px;
    }
    .spanTag .select2-container-multi .select2-choices .select2-search-choice{
        padding: 5px 5px 5px 23px;
    }
</style>
<script>
    function format(state) {
        if (!state.id) return state.text; // optgroup
        return "<i class='fa fa-circle' style='color:#0000ff' aria-hidden='true'></i> "+ state.text;
    }
    $(document).ready(function() {
        $("#tag").select2({
            formatResult: format,
            formatSelection: format,
            escapeMarkup: function(m) { return m; }
        });
    });
    $('body').on('change', '#tag', function(e){
        console.log($(this).val());
        var color = $(this).find('option:selected');
        console.log();
    })
    // $('.select2-search-field').click();
</script>
<script>

    var filterList = {
        'datestart' : '[name="date_start"]',
        'dateend' : '[name="date_end"]'
    };
    $(function(){
        initDataTable('.table-bot_fanpage', admin_url+'bot_fanpage/table', [0], [0], {}, [1, 'desc']);
    });

    function AddBotFanpage(id = "", _this)
    {
        var button = $(_this);
        button.button({loadingText: '<?=_l('cong_please_wait')?>'});
        button.button('loading');
        var data = {};
        if (typeof (csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        if($.isNumeric(id))
        {
            data['id'] = id;
        }
        $.post(admin_url+'bot_fanpage/getModal', data, function(data){
            $('#view_modal').html(data);
        }).always(function() {
            button.button('reset')
        });
    }

</script>
<script>
    function ListDetail(id = '')
    {
        if($.isNumeric(id))
        {
            var button = $(_this);
            button.button({loadingText: '<?=_l('cong_please_wait')?>'});
            button.button('loading');
            var data = {};
            if (typeof (csrfData) !== 'undefined') {
                data[csrfData['token_name']] = csrfData['hash'];
            }
            data['id'] = id;
            $.post(admin_url+'bot_fanpage/SetupModal', data, function(data){
                $('#view_modal').html(data);
            }).always(function() {
                button.button('reset')
            });
        }
    }
</script>



</body>
</html>
