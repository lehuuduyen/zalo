<style>
    .table-reviews tbody tr td, .table-reviews   thead tr th {
        white-space: unset;
        max-width: 300px!important;
        min-width: 300px;
    }
    .table-reviews tbody tr td:nth-child(1), .table-reviews tbody tr td:nth-child(2), .table-reviews   thead tr th:nth-child(1), .table-reviews   thead tr th:nth-child(2) {
        white-space: nowrap;
        width: auto;
        max-width: auto!important;
        min-width: auto;
    }
</style>
<h4><?= _l('cong_review_experience_advisory_care_of')?></h4>
<?php
    $query = '
        SELECT DISTINCT name, GROUP_CONCAT(id) from (
            SELECT name, concat(id, "_advisory") as id FROM tblexperience_advisory
            UNION 
            SELECT name, concat(id, "_care_of")as id FROM tblexperience_care_of_client WHERE theme is null order by name asc
        ) as experience 
        group by name
    ';

    $arrayDataTable = array(
        _l('cong_code_advisory_lead'),
        _l('cong_fullcode_care_of')
    );

    $titleQuery = get_table_query_cong($query);
    foreach($titleQuery as $key => $value) {
        $arrayDataTable[] = $value['name'];
    }
?>
<?php render_datatable($arrayDataTable,'reviews'); ?>


<script>
    $(window).bind("load", function() {
        var table_reviews = initDataTableCustom('.table-reviews', admin_url + 'clients/get_table_reviews/<?=$client->userid?>?leadid=<?=$client->leadid?>', [0], [0], [], [0, 'asc'], fixedColumns = {
            leftColumns: 2,
            rightColumns: 0
        });

        $('.table-reviews').on('draw.dt', function() {
            var invoiceReportsTable = $(this).DataTable();
            var output = invoiceReportsTable.ajax.json();
            $('.b_reviews').text(output.iTotalDisplayRecords);
        })
    })
</script>
