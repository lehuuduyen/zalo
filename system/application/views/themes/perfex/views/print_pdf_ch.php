<?php
$dimensions = $pdf->getPageDimensions();
$pdf->MultiCell(40, 0, $data->img, 0, 'J', 0, 1, 10, 10, true, 0, true, true, 0);
$pdf->MultiCell(40, 0, $data->img, 0, 'J', 0, 1, 10, 155, true, 0, true, true, 0);
$pdf->MultiCell($dimensions['wk'] - ($dimensions['lm'] + 10), 0, $data->content, 0, 'J', 0, 1, '', 0, true, 0, true, true, 0);