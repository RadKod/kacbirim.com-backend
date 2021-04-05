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
                $months = floor($i % 12);
            }
            $return_array['year'] = $years ?: null;
            $return_array['month'] = $months;
        } else {
            $return_array['month_in'] = floor($wage / $unit);
        }

        return $return_array;
    }
}


if (!function_exists('wage_types')) {
    /**
     * @return string[]
     */
    function wage_types(): array
    {
        return [
            1 => 'Asgari Ücret',
            2 => 'Net Asgari Ücret',
            3 => 'Brüt Asgari Ücret',
            4 => 'Ortalama Asgari Ücret',
        ];
    }
}

if (!function_exists('wage_type_decode')) {
    /**
     * @param $wage_type
     * @return string
     */
    function wage_type_decode($wage_type): string
    {
        return wage_types()[$wage_type];
    }
}
