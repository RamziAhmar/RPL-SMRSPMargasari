<?php

namespace App\Helpers;

use Maatwebsite\Excel\Facades\Excel;

class WhoHelper
{
    private static $cache = [];

    public static function load($file)
    {
        if (!isset(self::$cache[$file])) {
            $data = Excel::toArray([], database_path("data/$file"));
            $rows = $data[0];

            // ambil header
            $header = array_map('trim', $rows[0]);
            unset($rows[0]);

            $formatted = [];

            foreach ($rows as $row) {
                $row = array_combine($header, $row);

                $umur = (int)$row['Month'];

                $formatted[$umur] = [
                    'L' => (float)$row['L'],
                    'M' => (float)$row['M'],
                    'S' => (float)$row['S'],
                ];
            }

            self::$cache[$file] = $formatted;
        }

        return self::$cache[$file];
    }

    public static function getLms($umur, $gender)
    {
        if ($gender == 'L') {
            $data = $umur < 24
                ? self::load('boys_0-2.xlsx')
                : self::load('boys_2-5.xlsx');
        } else {
            $data = $umur < 24
                ? self::load('girls_0-2.xlsx')
                : self::load('girls_2-5.xlsx');
        }

        if (!isset($data[$umur])) {
            throw new \Exception("WHO data tidak ditemukan untuk umur $umur");
        }

        return $data[$umur];
    }

    public static function zScore($tinggi, $umur, $gender)
    {
        $lms = self::getLms($umur, $gender);

        $L = $lms['L'];
        $M = $lms['M'];
        $S = $lms['S'];

        if ($L == 0) {
            return log($tinggi / $M) / $S;
        }

        return (pow($tinggi / $M, $L) - 1) / ($L * $S);
    }

    public static function getMedian($umur, $gender)
    {
        $lms = self::getLms($umur, $gender);
        return $lms['M'];
    }

    public static function deltaTinggi($umur, $gender)
    {
        if ($umur <= 0) return 0;

        $m_now  = self::getMedian($umur, $gender);
        $m_prev = self::getMedian($umur - 1, $gender);

        return $m_now - $m_prev;
    }
}
