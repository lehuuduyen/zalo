<style>
    .progressbar:not(.initli) {
        margin: 0;
        padding: 0;
        counter-reset: step;
    }

    .progressbar li span {
        font-size: 11px;
    }

    .progressbar li:not(.initli) {
        list-style-type: none;
        width: 16%;
        float: left;
        font-size: 10px;
        position: relative;
        text-align: center;
        /* text-transform: uppercase; */
        color: #7d7d7d;
        z-index: 0;
    }

    .progressbar li:not(.initli):before {
        width: 10px;
        height: 10px;
        content: ' ';
        counter-increment: step;
        line-height: 51px;
        border: 5px solid #7d7d7d;
        display: block;
        text-align: center;
        margin: 0 auto 10px auto;
        border-radius: 50%;
        background-color: white;
    }

    .progressbar li:not(.initli):after {
        width: 100% !important;
        height: 2px !important;
        content: '' !important;
        position: absolute !important;
        background-color: #7d7d7d !important;
        top: 4px !important;
        left: -50% !important;
        z-index: -1 !important;
    }

    .progressbar li:first-child:after {
        content: none;
        display: none;
    }

    .progressbar li.active:not(.initli) {
        color: green;
    }

    .progressbar li.active:not(.initli):before {
        border-color: #55b776;
    }

    .progressbar li.cancel:before {
        border-color: red;
    }

    .progressbar li.active + li:after {
        background-color: #55b776 !important;
    }

    .font11 {
        font-size: 11px;
    }

    .btn-info.active, .btn-info:active {
        background-color: #094865;
    }

    .table-orders th, .table-orders td {
        white-space: nowrap;
    }

    .mw600 {
        min-width: 600px;
    }

    .li_pad10 {
        white-space: normal;
        padding-left: 10px;
    }

    .CRa {
        color: #55b776;
    }

    .progressbar_img {
        text-align: center !important;
        display: flex;
        flex-direction: row;
        justify-content: center;
    }

    ul.progressbar_img li {
        width: 100px;
        float: left;
    }

    .CRwa {
        color: red;
    }

    .initli {
        width: 100px;
    }

    tr.bg-warning {
        background-color: #fcf8e3;
    }

    tr.bg-dd {
        background-color: #dddddd;
    }

    .initli .status_orders {
        color: red;
    }
</style>

<?php if (!empty($data)) { ?>
    <?php foreach ($data as $kData => $vData) { ?>
        <div class="div_advisory panel panel-info mtop5">
            <div class="panel-heading">
                <i class="fa fa-share-alt" aria-hidden="true"></i>
                <?= mb_strtoupper(_l('cong_advisory_panel'), 'UTF-8'); ?>
                <?php if(!empty($vData)){ ?>
                    (<?= $vData->prefix . $vData->code ?>)
                <?php } ?>
            </div>

            <div class="panel-body">
                <?php if(!empty($vData)){?>
                <ul class="progressbar">
                        <?php $active = 1;?>
                        <?php foreach($vData->detail as $key => $value){?>
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

                            <li class="<?=isset($value->active) ? 'active' : ''?>">
                                <a class="<?= isset($value->active) ? 'text-success' : (!empty($next_step) ? ('text-danger update_status_lead') : '') ?>" <?=(!isset($value->active) ? 'id-data="'.$vData->id.'" status-procedure="'.$value->id.'"' : '')?>>
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
                    echo '<p class="text-danger">'.mb_strtoupper(_l('cong_not_advisory_panel'), 'UTF-8').'</p>';
                } ?>
            </div>
        </div>
    <?php } ?>
<?php } ?>
<script>
    $('.update_status_lead').click(function(e){
        var id_assigned  = $(this).attr('id-data');
        var status_procedure  = $(this).attr('status-procedure');
        if($.isNumeric(id_assigned)) {
            var data = {};
            var button = $(this);
            button.button({loadingText: '<?=_l('cong_please_wait')?>'});
            button.button('loading');
            if (typeof (csrfData) !== 'undefined') {
                data[csrfData['token_name']] = csrfData['hash'];
            }
            data['id'] = id_assigned;
            data['status_procedure'] = status_procedure;
            $.post(admin_url + 'advisory_lead/update_status/', data, function (data) {
                data = JSON.parse(data);
                if (data.success) {
                    var id_facebook = $('#id_facebook').val();
                    varInfoUser(id_facebook);
                }
                alert_float(data.alert_type, data.message);
            }).always(function () {
                button.button('reset')
            });
        }
    })
</script>
