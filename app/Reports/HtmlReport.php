<?php

namespace App\Reports;

class HtmlReport implements ReportInterface
{
    public function render(array $data)
    {
        // render ke blade view
        return view('laporan.index', $data);
    }
}
