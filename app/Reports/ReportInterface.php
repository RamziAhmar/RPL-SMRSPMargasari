<?php

namespace App\Reports;

/**
 * Product interface pada Factory Method
 */
interface ReportInterface
{
    /**
     * Render laporan
     */
    public function render(array $data);
}