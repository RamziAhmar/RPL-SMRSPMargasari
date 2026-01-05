<?php

namespace App\Reports;

class PdfReport implements ReportInterface
{
    public function render(array $data)
    {
        // Contoh saja (untuk laporan)
        // $pdf = PDF::loadView('laporan.pdf', $data);
        // return $pdf->download('laporan.pdf');

        return 'PDF Report Generated';
    }
}
