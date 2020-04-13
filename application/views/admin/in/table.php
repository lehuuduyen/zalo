<table class="table" id="table-print-data">
    <thead>
    <tr>
        <th>Ngày Tạo</th>
        <th>IN</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($data as $key => $value) {?>
        <?php $value['note_data'] = json_decode($value['note_data']); ?>
        <tr>
            <td><?= _d($value['date'])?></td>
            <td><button class="btn btn-default mtop25" onclick="print_data(<?=$value['id']?>)"><i class="fa fa-print" aria-hidden="true"></i> IN</button></td>
        </tr>
    <?php } ?>
    </tbody>
</table>