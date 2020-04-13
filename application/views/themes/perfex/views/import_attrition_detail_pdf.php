<?php
$dimensions = $pdf->getPageDimensions();

function mb_ucfirst($string, $encoding)
{
    return mb_convert_case($string, MB_CASE_TITLE, $encoding);
}
// $pdf->WatermarkText();
// Tag - used in BULK pdf exporter
if ($tag != '') {
    $pdf->SetFillColor(240, 240, 240);
    $pdf->SetDrawColor(245, 245, 245);
    $pdf->SetXY(0, 0);
    $pdf->SetFont($font_name, 'B', 15);
    $pdf->SetTextColor(0);
    $pdf->SetLineWidth(0.75);
    $pdf->StartTransform();
    $pdf->Rotate(-35, 109, 235);
    $pdf->Cell(100, 1, mb_strtoupper($tag, 'UTF-8'), 'TB', 0, 'C', '1');
    $pdf->StopTransform();
    $pdf->SetFont($font_name, '', $font_size-1);
    $pdf->setX(10);
    $pdf->setY(10);
}



$combo=1;
if($invoice->combo)
{
    $combo=$invoice->combo;
}
for($j=0;$j<$combo;$j++) {

    if ($j > 0 && $j % $combo == 1) {
        // $pdf->ln(3);
        $divide = '<hr style="margin-top: 30px;margin-bottom: 20px;border: 0;border-top: 1px solid #eee;" />';
        $y = $pdf->getY();
        $pdf->writeHTMLCell('', '', '', $y, $divide, 0, 0, false, true, ($swap == '1' ? 'R' : 'J'), true);
        $pdf->ln(5);
        $pdf->printHeader();
        $pdf->ln(20);
    }
    $pdf_text_color_array = hex2rgb(get_option('pdf_text_color'));
    if (is_array($pdf_text_color_array) && count($pdf_text_color_array) == 3) {
        $pdf->SetTextColor($pdf_text_color_array[0], $pdf_text_color_array[1], $pdf_text_color_array[2]);
    }
    if ($invoice->status == 0) {
        if(strtotime(date('Y-m-d'))>strtotime($invoice->date_last))
        {
            $plan_name = _l('PHIẾU THÔNG BÁO HẾT HẠN TÍNH TÀI SẢN CỐ ĐỊNH');
            $invoice->status=3;
        }
        else
        {
            $plan_name = _l('PHIẾU NHẬP TÀI SẢN CỐ ĐỊNH');
        }
    }
    else
    {
        $plan_name = _l('PHIẾU BÁN TÀI SẢN CỐ ĐỊNH');
    }
    $pdf->ln(5);
    $pdf->SetFont($font_name, 'B', 20);
    $pdf->Cell(0, 0, mb_strtoupper($plan_name, 'UTF-8'), 0, 1, 'C', 0, '', 0);
    $pdf->SetFont($font_name, '', $font_size - 1);
    $pdf->writeHTMLCell('', '', '', '', '<i>' . getStrDate($invoice->date) . '</i>', 0, 1, false, true, 'C', true);
    $pdf->ln(5);
    $tblhtml = '
        <table width="100%" bgcolor="#fff" cellspacing="0" cellpadding="5" border="1px">
            <tr height="30" bgcolor="' . get_option('pdf_table_heading_color') . '" style="color:' . get_option('pdf_table_heading_text_color') . ';">
                <th  width="' . ($invoice->status == '2' ? '20%' : '30%') . '" align="center">' . _l('Tên tài sản') . '</th>
                <th  width="' . ($invoice->status == '2' ? '10%' : '10%') . '" align="center">' . _l('Số lượng') . '</th>
                <th  width="13%" align="center">' . _l('Ngày nhập') . '</th>';
    $tblhtml .='<th  width="12%" align="center">' . _l('Đơn giá') . '</th>
                <th  width="' . ($invoice->status == '2' ? '12%' : '17%') . '" align="center">' . _l('Tổng tài sản') . '</th>
                <th  width="18%" align="center">' . _l('Thời gian khấu hao(Tháng)') . '</th>';
    if($invoice->status==2)
    {
        $tblhtml .= ' <th  width="15%" align="center">' . _l('Giá bán') . '</th>';
    }
    $tblhtml .= '</tr>';

    $tblhtml .= '<tr>';
        $tblhtml .= '<td align="center">' . $invoice->name . '</td>';
        $tblhtml .= '<td align="center">' . number_format_data($invoice->quantity) . '</td>';
        $tblhtml .= '<td align="center">' . _d($invoice->date). '</td>';
        $tblhtml .= '<td align="center">' . number_format_data($invoice->price) . '</td>';
        $tblhtml .= '<td align="center">' . number_format_data($invoice->price*$invoice->quantity) . '</td>';
        $tblhtml .= '<td align="center">' . number_format_data($invoice->month) . '</td>';
        if($invoice->status==2)
        {
            $tblhtml .= '<td align="center">' . number_format_data($invoice->price_buy) . '</td>';
        }
    $tblhtml .= '</tr>';

    $tblhtml .= '</tbody>';
    $tblhtml .= '</table>';
    $pdf->writeHTML($tblhtml, true, false, false, false, '');
    if($invoice->status==2)
    {
        $strmoney = '<ul>';
            $strmoney .= '<li>' . _l('Nội dung bán:') . ($invoice->note_buy ? '<b>' . _l('blank10') . $invoice->note_buy . '</b>' : _l('blank___')) . '</li>';;
        $strmoney .= '</ul>';
        $pdf->writeHTML($strmoney, false, false, false, false, 'L');
    }
    $pdf->Ln(3);
    $table = "<table style=\"width: 100%;text-align: center\" border=\"0\">
                <tr>
                    <td><b>" . mb_ucfirst(_l('creater'), "UTF-8") . "</b></td>
                    <td><b>" . mb_ucfirst(_l('deliver'), "UTF-8") . "</b></td>
                    <td><b>" . mb_ucfirst(_l('warehouseman'), "UTF-8") . "</b></td>
                    <td><b>" . mb_ucfirst(_l('chief_accountant'), "UTF-8") . "</b></td>
                </tr>
                <tr>
                    <td>(ký, ghi rõ họ tên)</td>
                    <td>(ký, ghi rõ họ tên)</td>
                    <td>(ký, ghi rõ họ tên)</td>
                    <td>(ký, ghi rõ họ tên)</td>
                </tr>
                <tr>
                    <td style=\"height: 100px\" colspan=\"3\"></td>
                </tr>
                <tr>
                    <td>" . mb_ucfirst($invoice->createrr, "UTF-8") . "</td>
                    <td>" . mb_ucfirst($invoice->deliver_name, "UTF-8") . "</td>
                    <td>" . mb_ucfirst($invoice->warehouseman, "UTF-8") . "</td>
                    <td>" . mb_ucfirst($invoice->chief_accountant, "UTF-8") . "</td>
                </tr>
        
        </table>";
    $pdf->writeHTML($table, true, false, false, false, '');
    $pdf->WatermarkText();
}
