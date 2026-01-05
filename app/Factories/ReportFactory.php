<?php

namespace App\Factories;

use App\Reports\ReportInterface;
use App\Reports\HtmlReport;
use App\Reports\PdfReport;

/**
 * Factory Method untuk format laporan
 */
class ReportFactory
{
    public static function make(string $format): ReportInterface
    {
        return match ($format) {
            'pdf'   => new PdfReport(),
            default => new HtmlReport(),
        };
    }
}