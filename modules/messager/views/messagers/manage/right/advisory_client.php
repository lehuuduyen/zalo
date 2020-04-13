<style>

</style>
<?php if (!empty($data)) { ?>
    <?php foreach ($data as $kData => $vData) { ?>
        <div class="div_advisory panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-share-alt" aria-hidden="true"></i>
                <?= mb_strtoupper(_l('cong_advisory_panel'), 'UTF-8'); ?>
                <?php if (!empty($vData)) { ?>
                    (<?= $vData->prefix . $vData->code ?>)
                <?php } ?>
            </div>
            <div class="panel-body">
                <?php if (!empty($vData)) { ?>
                    <ul class="progressbar">
                        <?php $active = 1; ?>
                        <?php foreach ($vData->detail as $key => $value) { ?>
                            <li class="<?=isset($value->active) ? 'active' : ''?>">
                                <?php
                                $next_step = false;
                                if (!empty($value->date_create)) {
                                    $date_expected = $value->date_create;
                                }
                                else if($key > 0  && isset($vData->detail[$key-1]->active) && !empty($vData->detail[$key-1]->date_create))
                                {

                                    $date_expected = $vData->detail[$key-1]->date_create;
                                    $leadtime = $value->leadtime;
                                    $date_expected = date("Y-m-d", strtotime("$date_expected +$leadtime day"));
                                    if(strtotime($date_expected) <= strtotime(date('Y-m-d')))
                                    {
                                        $next_step = true;
                                    }
                                }
                                else if ($key == 0 || !isset($vData->detail[$key-1]->active)) {
                                    if($key == 0)
                                    {
                                        $date_expected = $vData->date_expected;
                                        if(strtotime($date_expected) <= strtotime(date('Y-m-d')))
                                        {
                                            $next_step = true;
                                        }
                                    }
                                    else
                                    {
                                        $leadtime = $value->leadtime;
                                        $date_expected = date("Y-m-d", strtotime("$date_expected +$leadtime day"));
                                        $next_step = false;
                                    }
                                }
                                ?>
                                <a class="pointer <?= isset($value->active) ? 'text-success' : (!empty($next_step) ? ('text-danger update_status_client') : '') ?>" <?=(!isset($value->active) ? 'id-data="'.$vData->id.'" status-procedure="'.$value->id.'"' : '')?>>
                                    <p>
                                        <?= $value->name ?>
                                        <?php if(!empty($date_expected) && isset($value->active)){
                                            echo ' (' . (_dt($value->date_create, false)) . ')';
                                        }
                                        else
                                        {
                                            echo ' (' . (_dC($date_expected, false)) . ')';
                                        }
                                        ?>
                                    </p>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                <?php } else {
                    echo '<p class="text-danger">' . mb_strtoupper(_l('cong_not_advisory_panel'), 'UTF-8') . '</p>';
                } ?>
            </div>
        </div>
    <?php } ?>
<?php } ?>
<script>
    $('.update_status_client').click(function(e){
        var id_assigned  = $(this).attr('id-data');
        var status_procedure  = $(this).attr('status-procedure');
        if($.isNumeric(id_assigned))
        {
            var button = $(this);
            button.button({loadingText: '<?=_l('cong_please_wait')?>'});
            button.button('loading');
            var data = {};
            if (typeof(csrfData) !== 'undefined') {
                data[csrfData['token_name']] = csrfData['hash'];
            }
            data['id'] = id_assigned;
            data['status_procedure'] = status_procedure;
            $.post(admin_url+'care_of_clients/update_status/', data, function(data){
                data = JSON.parse(data);
                if(data.success)
                {
                    var id_facebook = $('#id_facebook').val();
                    varInfoUser(id_facebook);
                }
                alert_float(data.alert_type, data.message);
            }).always(function() {
                button.button('reset')
            });
        }
    })
</script>
