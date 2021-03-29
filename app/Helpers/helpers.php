<?php

namespace App\Helpers;

if (!function_exists('calculate_purchasing_power')) {
    /**
     * @param mixed $unit
     * @param mixed $wage
     * @return null[]
     */
    function calculate_purchasing_power($unit, $wage): array
    {
        $return_array = [
            'year' => null,
            'month' => null,
            'month_in' => null,
        ];
        if ($unit > $wage) {
            $new_wage = $wage;
            $mount = 1;
            $months = null;
            $years = null;
            while ($new_wage < $unit) {
                $new_wage += $wage;
                $mount++;
            }
            for ($i = 0; $i <= $mount; $i++) {
                if (!is_float($i / 12)) {
                    $years = floor($i / 12);
                }
                $months = ceil($i % 12);
            }
            $return_array['year'] = $years ? $years : null;
            $return_array['month'] = $months;
        } else {
            $return_array['month_in'] = round($wage / $unit);
        }

        return $return_array;
    }
}
