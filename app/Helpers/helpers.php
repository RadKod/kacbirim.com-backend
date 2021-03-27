<?php

namespace App\Helpers;

if (!function_exists('calculate_purchasing_power')) {
    /**
     * @param mixed $unit
     * @param mixed $wage
     * @return string
     */
    function calculate_purchasing_power($unit, $wage): string
    {
        if ($unit > $wage) {
            $new_wage = $wage;
            $mount = 0;
            $display = '1 Ay';
            $years = '';
            while ($new_wage <= $unit) {
                $mount++;
                $new_wage += $wage;
            }
            for ($i = 0; $i <= $mount; $i++) {
                if (!is_float($i / 12)) {
                    $years = floor($i / 12) . ' Yıl';
                    $years = $years . ($years > 1 ? ' ve' : '');
                    if ($years == 0) {
                        $years = '';
                    }
                }
                $months = ' ' . ($i % 12) . ' Ay';
                $display = $years . '' . $months;
            }
            $return_msg = $display . ' içinde alabiliyor.';
        } else {
            $return_msg = '1 Ayda ' . round($wage / $unit) . ' tane alınabilir.';
        }

        return $return_msg;
    }
}

